<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthEntrepriseController extends Controller
{
    public function __construct()
    {
        // Permettre l'accès aux pages d'inscription/connexion même si connecté
        // pour permettre le changement de type de compte
        // Pas de middleware guest pour permettre l'accès libre
    }

    /**
     * Afficher le formulaire d'inscription entreprise
     */
    public function showRegister()
    {
        // Permettre l'accès même si connecté (pour créer une nouvelle entreprise)
        // L'utilisateur peut vouloir créer une nouvelle entreprise même s'il a déjà un compte
        return view('entreprise.auth.register');
    }

    /**
     * Traiter l'inscription entreprise
     */
    public function register(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|unique:companies,email',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'plan' => 'required|in:starter,professional,enterprise',
        ], [
            'company_name.required' => 'Le nom de l\'entreprise est obligatoire.',
            'company_email.required' => 'L\'email de l\'entreprise est obligatoire.',
            'company_email.unique' => 'Cette adresse email d\'entreprise est déjà utilisée.',
            'name.required' => 'Votre nom est obligatoire.',
            'email.required' => 'Votre adresse email est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'plan.required' => 'Vous devez choisir un plan d\'abonnement.',
            'plan.in' => 'Le plan sélectionné n\'est pas valide.',
        ]);

        // Définir les prix et limites selon le plan
        $planConfig = [
            'starter' => ['price' => 399, 'user_limit' => 10],
            'professional' => ['price' => 599, 'user_limit' => 20],
            'enterprise' => ['price' => 999, 'user_limit' => null],
        ];

        $selectedPlan = $planConfig[$validated['plan']];

        // Stocker les données en session pour le processus de paiement
        session([
            'entreprise_registration' => [
                'company_name' => $validated['company_name'],
                'company_email' => $validated['company_email'],
                'admin_name' => $validated['name'],
                'admin_email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'plan' => $validated['plan'],
                'price' => $selectedPlan['price'],
                'user_limit' => $selectedPlan['user_limit'],
            ]
        ]);

        // Rediriger vers la page de paiement Stripe
        return $this->redirectToStripeCheckout($selectedPlan['price'], $validated['company_name']);
    }

    /**
     * Afficher le formulaire de connexion entreprise
     */
    public function showLogin()
    {
        // Permettre l'accès même si connecté (pour changer de compte)
        // L'utilisateur peut vouloir se connecter avec un autre compte entreprise
        return view('entreprise.auth.login');
    }

    /**
     * Traiter la connexion entreprise
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Vérifier que l'utilisateur appartient à une entreprise
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && !$user->company_id) {
            return back()->withErrors([
                'email' => 'Cette adresse email ne correspond pas à un compte entreprise. Veuillez utiliser le formulaire de connexion indépendant.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Vérifier que l'utilisateur appartient bien à une entreprise
            if (!$user->company_id) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Ce compte ne correspond pas à un utilisateur d\'entreprise.',
                ])->onlyInput('email');
            }

            // Rediriger selon le rôle
            if ($user->role === 'admin_entreprise') {
                return redirect()->intended(route('entreprise.dashboard'));
            } elseif ($user->company_id) {
                return redirect()->intended(route('entreprise.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('entreprise.login');
    }

    /**
     * Rediriger vers Stripe Checkout
     */
    private function redirectToStripeCheckout($amount, $companyName)
    {
        try {
            $registrationData = session('entreprise_registration');
            $stripeService = new StripeService();
            
            // Préparer les données pour Stripe
            $stripeData = [
                'plan_name' => config("stripe.plans.{$registrationData['plan']}.name"),
                'plan' => $registrationData['plan'],
                'amount' => $amount,
                'company_name' => $registrationData['company_name'],
                'company_email' => $registrationData['company_email'],
                'admin_name' => $registrationData['admin_name'],
                'admin_email' => $registrationData['admin_email'],
                'session_data' => $registrationData,
            ];
            
            // Créer la session Stripe
            $session = $stripeService->createCheckoutSession($stripeData);
            
            // Rediriger vers Stripe Checkout
            return redirect($session->url);
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la session Stripe: ' . $e->getMessage());
            return redirect()->route('entreprise.register')
                ->with('error', 'Une erreur est survenue lors de l\'initialisation du paiement. Veuillez réessayer.');
        }
    }

    /**
     * Traiter le paiement réussi
     */
    public function handleSuccessfulPayment()
    {
        $registrationData = session('entreprise_registration');
        
        if (!$registrationData) {
            \Log::error('Session entreprise_registration non trouvée');
            return redirect()->route('entreprise.register')->with('error', 'Session expirée. Veuillez recommencer.');
        }

        \Log::info('Début de la création de l\'entreprise', $registrationData);

        try {
            DB::beginTransaction();

            // Créer l'entreprise sans admin_id (nullable maintenant)
            $company = Company::create([
                'name' => $registrationData['company_name'],
                'email' => $registrationData['company_email'],
                'slug' => Str::slug($registrationData['company_name']),
                'plan' => $registrationData['plan'],
                'monthly_price' => $registrationData['price'],
                'user_limit' => $registrationData['user_limit'],
                'status' => 'active',
                'admin_id' => null, // Sera mis à jour après création de l'admin
            ]);

            \Log::info('Entreprise créée avec l\'ID: ' . $company->id);

            // Créer l'administrateur d'entreprise
            $admin = User::create([
                'name' => $registrationData['admin_name'],
                'email' => $registrationData['admin_email'],
                'password' => $registrationData['password'],
                'role' => 'admin_entreprise',
                'company_id' => $company->id,
                'position' => 'Directeur Général',
                'department' => 'Direction',
                'is_active' => true,
            ]);

            \Log::info('Administrateur créé avec l\'ID: ' . $admin->id);

            // Mettre à jour l'entreprise avec l'ID de l'admin
            $company->update(['admin_id' => $admin->id]);

            \Log::info('Entreprise mise à jour avec admin_id: ' . $admin->id);

            DB::commit();

            // Nettoyer la session
            session()->forget('entreprise_registration');

            // Connecter automatiquement l'utilisateur
            Auth::login($admin);

            return redirect()->route('entreprise.dashboard')
                ->with('success', 'Votre entreprise a été créée avec succès ! Bienvenue dans votre espace d\'administration.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log détaillé de l'erreur
            \Log::error('Erreur lors de la création de l\'entreprise', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'registration_data' => $registrationData
            ]);
            
            // Nettoyer la session en cas d'erreur
            session()->forget('entreprise_registration');
            
            return redirect()->route('entreprise.register')
                ->with('error', 'Une erreur est survenue lors de la création de votre entreprise: ' . $e->getMessage());
        }
    }

    /**
     * Page de succès après paiement
     */
    public function paymentSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            return redirect()->route('entreprise.register')
                ->with('error', 'Session de paiement invalide.');
        }
        
        try {
            $stripeService = new StripeService();
            
            // Vérifier que le paiement est bien effectué
            if (!$stripeService->isSessionPaid($sessionId)) {
                return redirect()->route('entreprise.payment.failed')
                    ->with('error', 'Le paiement n\'a pas été confirmé.');
            }
            
            // Récupérer les métadonnées de la session
            $metadata = $stripeService->getSessionMetadata($sessionId);
            
            // Restaurer les données de session à partir des métadonnées
            if (isset($metadata['session_data'])) {
                $sessionData = json_decode(base64_decode($metadata['session_data']), true);
                session(['entreprise_registration' => $sessionData]);
            }
            
            return $this->handleSuccessfulPayment();
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la vérification du paiement: ' . $e->getMessage());
            return redirect()->route('entreprise.payment.failed')
                ->with('error', 'Erreur lors de la vérification du paiement.');
        }
    }

    /**
     * Page d'échec après paiement
     */
    public function paymentFailed(Request $request)
    {
        session()->forget('entreprise_registration');
        
        return redirect()->route('entreprise.register')
            ->with('error', 'Le paiement a échoué. Veuillez réessayer.');
    }

    /**
     * Webhook Stripe
     */
    public function stripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('stripe.webhook_secret');
        
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            \Log::error('Invalid payload Stripe webhook', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            \Log::error('Invalid signature Stripe webhook', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }
        
        // Gérer l'événement
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                \Log::info('Session de paiement complétée', ['session_id' => $session->id]);
                break;
                
            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                \Log::info('Paiement de facture réussi', ['invoice_id' => $invoice->id]);
                break;
                
            case 'invoice.payment_failed':
                $invoice = $event->data->object;
                \Log::warning('Échec du paiement de facture', ['invoice_id' => $invoice->id]);
                break;
                
            default:
                \Log::info('Événement Stripe non géré', ['type' => $event->type]);
        }
        
        return response()->json(['status' => 'success']);
    }
}
