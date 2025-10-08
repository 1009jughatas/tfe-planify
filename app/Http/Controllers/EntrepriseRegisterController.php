<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EntrepriseRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Afficher le formulaire d'inscription entreprise
     */
    public function showRegistrationForm()
    {
        // Rediriger les utilisateurs déjà connectés vers le dashboard approprié
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isCompanyAdmin()) {
                return redirect()->route('company-admin.dashboard');
            } elseif ($user->company_id) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return view('auth.entreprise-register');
    }

    /**
     * Traiter l'inscription entreprise
     */
    public function register(Request $request)
    {
        // Vérifier que l'utilisateur n'est pas déjà connecté
        if (auth()->check()) {
            return redirect()->route('dashboard')->with('error', 'Vous êtes déjà connecté.');
        }

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
     * Rediriger vers Stripe Checkout
     */
    private function redirectToStripeCheckout($amount, $companyName)
    {
        // Pour l'instant, simuler le paiement et créer directement l'entreprise
        // TODO: Intégrer Stripe Checkout réel
        
        return $this->handleSuccessfulPayment();
    }

    /**
     * Traiter le paiement réussi (webhook Stripe ou simulation)
     */
    public function handleSuccessfulPayment()
    {
        $registrationData = session('entreprise_registration');
        
        if (!$registrationData) {
            return redirect()->route('entreprise.register')->with('error', 'Session expirée. Veuillez recommencer.');
        }

        try {
            DB::beginTransaction();

            // Créer l'entreprise
            $company = Company::create([
                'name' => $registrationData['company_name'],
                'email' => $registrationData['company_email'],
                'slug' => Str::slug($registrationData['company_name']),
                'monthly_price' => $registrationData['price'],
                'user_limit' => $registrationData['user_limit'],
                'status' => 'active',
                'admin_id' => null, // Sera mis à jour après création de l'admin
            ]);

            // Créer l'administrateur d'entreprise
            $admin = User::create([
                'name' => $registrationData['admin_name'],
                'email' => $registrationData['admin_email'],
                'password' => $registrationData['password'],
                'role' => 'company_admin',
                'company_id' => $company->id,
                'position' => 'Directeur Général',
                'department' => 'Direction',
                'is_active' => true,
            ]);

            // Mettre à jour l'entreprise avec l'ID de l'admin
            $company->update(['admin_id' => $admin->id]);

            DB::commit();

            // Nettoyer la session
            session()->forget('entreprise_registration');

            // Connecter automatiquement l'utilisateur
            Auth::login($admin);

            return redirect()->route('company-admin.dashboard')
                ->with('success', 'Votre entreprise a été créée avec succès ! Bienvenue dans votre espace d\'administration.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Nettoyer la session en cas d'erreur
            session()->forget('entreprise_registration');
            
            return redirect()->route('entreprise.register')
                ->with('error', 'Une erreur est survenue lors de la création de votre entreprise. Veuillez réessayer.');
        }
    }

    /**
     * Webhook Stripe pour confirmer le paiement
     */
    public function stripeWebhook(Request $request)
    {
        // TODO: Implémenter la vérification du webhook Stripe
        // Pour l'instant, on simule un paiement réussi
        
        return response()->json(['status' => 'success']);
    }

    /**
     * Page de succès après paiement
     */
    public function paymentSuccess(Request $request)
    {
        return $this->handleSuccessfulPayment();
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
}
