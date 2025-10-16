<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntrepriseSubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isAdminEntreprise');
    }

    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            abort(403, 'Aucune entreprise associée à votre compte.');
        }

        // Statistiques d'utilisation
        $currentUsers = \App\Models\User::where('company_id', $company->id)->count();
        $maxUsers = $company->max_users ?? 10;
        $usersRemaining = $maxUsers - $currentUsers;

        // Projets de l'entreprise
        $totalProjects = \App\Models\Project::where('company_id', $company->id)->count();
        $activeProjects = \App\Models\Project::where('company_id', $company->id)
            ->where('status', 'in-progress')
            ->count();

        // Tâches de l'entreprise
        $totalTasks = \App\Models\Task::where('company_id', $company->id)->count();
        $completedTasks = \App\Models\Task::where('company_id', $company->id)
            ->where('status', 'completed')
            ->count();

        // Plans disponibles (identiques à la page d'accueil)
        $plans = [
            'starter' => [
                'name' => 'Starter',
                'price' => 399,
                'max_users' => 10,
                'stripe_price_id' => 'price_starter_monthly',
                'features' => [
                    '10 utilisateurs',
                    'Projets illimités',
                    'Support email',
                    'Tableaux Kanban'
                ]
            ],
            'growth' => [
                'name' => 'Growth',
                'price' => 599,
                'max_users' => 20,
                'stripe_price_id' => 'price_growth_monthly',
                'features' => [
                    '20 utilisateurs',
                    'Projets illimités',
                    'Support prioritaire',
                    'Statistiques avancées'
                ]
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price' => 999,
                'max_users' => -1, // Illimité
                'stripe_price_id' => 'price_enterprise_monthly',
                'features' => [
                    'Utilisateurs illimités',
                    'Projets illimités',
                    'Support dédié',
                    'Fonctionnalités premium'
                ]
            ]
        ];

        // Plan actuel - utiliser les vraies données de l'abonnement
        $currentPlan = $company->plan ?? 'starter';
        
        // Mapper les anciens plans vers les nouveaux
        $planMapping = [
            'professional' => 'growth',
            'starter' => 'starter',
            'enterprise' => 'enterprise'
        ];
        
        $mappedPlan = $planMapping[$currentPlan] ?? $currentPlan;
        $currentPlanData = $plans[$mappedPlan] ?? $plans['starter'];
        
        // TOUJOURS utiliser les vraies données de l'abonnement de la base de données
        $currentPlanData['price'] = $company->monthly_price ?? $currentPlanData['price'];
        $currentPlanData['max_users'] = $company->max_users ?? $currentPlanData['max_users'];
        
        // Debug: Log des données pour vérification
        \Log::info('Données d\'abonnement', [
            'company_id' => $company->id,
            'plan' => $company->plan,
            'monthly_price' => $company->monthly_price,
            'max_users' => $company->max_users,
            'current_plan_data' => $currentPlanData
        ]);

        // Vérifier si un changement de plan est nécessaire
        $needsUpgrade = $currentUsers >= $maxUsers;
        $canDowngrade = $currentUsers <= ($plans['starter']['max_users'] ?? 10);

        return view('entreprise.abonnements.index', compact(
            'company',
            'currentUsers',
            'maxUsers',
            'usersRemaining',
            'totalProjects',
            'activeProjects',
            'totalTasks',
            'completedTasks',
            'plans',
            'currentPlan',
            'currentPlanData',
            'needsUpgrade',
            'canDowngrade'
        ));
    }

    public function update(Request $request)
    {
        \Log::info('EntrepriseSubscriptionController::update - Début', [
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);
        
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            \Log::error('EntrepriseSubscriptionController::update - Aucune entreprise associée', [
                'user_id' => $user->id
            ]);
            abort(403, 'Aucune entreprise associée à votre compte.');
        }

        $request->validate([
            'plan' => 'required|in:starter,growth,enterprise'
        ]);

        $newPlan = $request->plan;
        $currentUsers = \App\Models\User::where('company_id', $company->id)->count();

        // Vérifier si le nouveau plan peut accueillir tous les utilisateurs actuels
        $planLimits = [
            'starter' => 10,
            'growth' => 20,
            'enterprise' => -1 // Illimité
        ];

        if ($planLimits[$newPlan] !== -1 && $currentUsers > $planLimits[$newPlan]) {
            return back()->with('error', 'Impossible de passer au plan ' . ucfirst($newPlan) . '. Vous avez trop d\'utilisateurs (' . $currentUsers . '). Veuillez supprimer des utilisateurs ou choisir un plan supérieur.');
        }

        // Plans avec prix Stripe (identiques à la page d'accueil)
        $plans = [
            'starter' => [
                'name' => 'Starter',
                'price' => 399,
                'max_users' => 10,
                'stripe_price_id' => 'price_starter_monthly'
            ],
            'growth' => [
                'name' => 'Growth',
                'price' => 599,
                'max_users' => 20,
                'stripe_price_id' => 'price_growth_monthly'
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price' => 999,
                'max_users' => -1, // Illimité
                'stripe_price_id' => 'price_enterprise_monthly'
            ]
        ];

        $selectedPlan = $plans[$newPlan];

        // Rediriger vers Stripe Checkout
        return $this->createStripeCheckoutSession($company, $selectedPlan);
    }

    private function createStripeCheckoutSession($company, $plan)
    {
        \Log::info('EntrepriseSubscriptionController::createStripeCheckoutSession - Début', [
            'company_id' => $company->id,
            'plan' => $plan
        ]);
        
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            \Log::info('EntrepriseSubscriptionController::createStripeCheckoutSession - Clé Stripe configurée');

            // Pour les changements de plan, on crée toujours une nouvelle session de paiement
            // Le portail client Stripe sera utilisé pour d'autres actions (annulation, etc.)

            \Log::info('EntrepriseSubscriptionController::createStripeCheckoutSession - Création de la session Stripe');
            
            $sessionData = [
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Plan ' . $plan['name'] . ' - ' . $company->name,
                            'description' => 'Abonnement mensuel' . ($plan['max_users'] === -1 ? ' (utilisateurs illimités)' : ' pour ' . $plan['max_users'] . ' utilisateurs'),
                        ],
                        'unit_amount' => $plan['price'] * 100, // Prix en centimes
                        'recurring' => [
                            'interval' => 'month',
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => route('entreprise.abonnement.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('entreprise.abonnement.index'),
                'metadata' => [
                    'company_id' => $company->id,
                    'plan' => $plan['name'],
                    'max_users' => $plan['max_users']
                ]
            ];

            // Si l'entreprise a déjà un customer Stripe, l'utiliser
            if ($company->stripe_customer_id) {
                $sessionData['customer'] = $company->stripe_customer_id;
            } else {
                $sessionData['customer_email'] = auth()->user()->email;
            }

            $checkout_session = \Stripe\Checkout\Session::create($sessionData);

            \Log::info('EntrepriseSubscriptionController::createStripeCheckoutSession - Session créée avec succès', [
                'session_id' => $checkout_session->id,
                'url' => $checkout_session->url
            ]);

            return redirect($checkout_session->url);
        } catch (\Exception $e) {
            \Log::error('Stripe Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la création de la session de paiement. Veuillez réessayer.');
        }
    }

    private function redirectToCustomerPortal($company)
    {
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            
            $session = \Stripe\BillingPortal\Session::create([
                'customer' => $company->stripe_customer_id,
                'return_url' => route('entreprise.abonnement.index'),
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            \Log::error('Stripe Customer Portal Error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'accès au portail client. Veuillez réessayer.');
        }
    }

    public function success(Request $request)
    {
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            
            $session = \Stripe\Checkout\Session::retrieve($request->session_id);
            
            if ($session->payment_status === 'paid') {
                $company = \App\Models\Company::find($session->metadata->company_id);
                
                if ($company) {
                    // Mettre à jour le plan de l'entreprise
                    $maxUsers = $session->metadata->max_users;
                    if ($maxUsers == -1) {
                        $maxUsers = 999999; // Valeur très élevée pour représenter l'illimité
                    }
                    
                    // Récupérer les informations du plan depuis les métadonnées
                    $planName = strtolower($session->metadata->plan);
                    $plans = [
                        'starter' => ['price' => 399, 'max_users' => 10],
                        'growth' => ['price' => 599, 'max_users' => 20],
                        'enterprise' => ['price' => 999, 'max_users' => -1]
                    ];
                    
                    $planData = $plans[$planName] ?? $plans['starter'];
                    
                    $company->update([
                        'plan' => $planName,
                        'max_users' => $maxUsers,
                        'monthly_price' => $planData['price'],
                        'stripe_subscription_id' => $session->subscription,
                        'stripe_customer_id' => $session->customer,
                        'status' => 'active'
                    ]);

                    return redirect()->route('entreprise.abonnement.index')
                        ->with('success', 'Abonnement activé avec succès ! Votre plan ' . $session->metadata->plan . ' est maintenant actif.');
                }
            }
            
            return redirect()->route('entreprise.abonnement.index')
                ->with('error', 'Erreur lors de l\'activation de l\'abonnement.');
                
        } catch (\Exception $e) {
            \Log::error('Stripe Success Error: ' . $e->getMessage());
            return redirect()->route('entreprise.abonnement.index')
                ->with('error', 'Erreur lors de la vérification du paiement.');
        }
    }

    public function cancel()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            abort(403, 'Aucune entreprise associée à votre compte.');
        }

        // Vérifier qu'il reste au moins un utilisateur
        $currentUsers = \App\Models\User::where('company_id', $company->id)->count();
        if ($currentUsers > 1) {
            return back()->with('error', 'Impossible d\'annuler l\'abonnement. Il reste ' . ($currentUsers - 1) . ' utilisateur(s) dans l\'entreprise.');
        }

        try {
            // Annuler l'abonnement Stripe si il existe
            if ($company->stripe_subscription_id) {
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
                $subscription = \Stripe\Subscription::retrieve($company->stripe_subscription_id);
                $subscription->cancel();
            }

            // Passer au plan gratuit
            $company->update([
                'plan' => 'starter',
                'max_users' => 10, // Plan starter avec 10 utilisateurs
                'stripe_subscription_id' => null
            ]);

            return redirect()->route('entreprise.abonnement.index')
                ->with('success', 'Abonnement annulé avec succès. Vous êtes maintenant sur le plan gratuit.');

        } catch (\Exception $e) {
            \Log::error('Stripe Cancel Error: ' . $e->getMessage());
            
            // Même en cas d'erreur Stripe, on passe au plan gratuit
            $company->update([
                'plan' => 'starter',
                'max_users' => 10,
                'stripe_subscription_id' => null
            ]);

            return redirect()->route('entreprise.abonnement.index')
                ->with('success', 'Abonnement annulé avec succès. Vous êtes maintenant sur le plan gratuit.');
        }
    }

    /**
     * Webhook Stripe pour gérer les changements d'abonnement
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');
        
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
            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;
                
            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;
                
            case 'invoice.payment_succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
                
            case 'invoice.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
                
            default:
                \Log::info('Événement Stripe non géré', ['type' => $event->type]);
        }
        
        return response()->json(['status' => 'success']);
    }

    private function handleSubscriptionUpdated($subscription)
    {
        try {
            $company = \App\Models\Company::where('stripe_subscription_id', $subscription->id)->first();
            
            if ($company) {
                // Mettre à jour le plan selon les métadonnées Stripe
                $metadata = $subscription->metadata ?? [];
                if (isset($metadata['plan'])) {
                    $planName = $metadata['plan'];
                    $maxUsers = $metadata['max_users'] ?? 10;
                    
                    // Récupérer le prix du plan
                    $plans = [
                        'starter' => ['price' => 399],
                        'growth' => ['price' => 599],
                        'enterprise' => ['price' => 999]
                    ];
                    
                    $planData = $plans[$planName] ?? $plans['starter'];
                    
                    $company->update([
                        'plan' => $planName,
                        'max_users' => $maxUsers,
                        'monthly_price' => $planData['price']
                    ]);
                    
                    \Log::info('Abonnement mis à jour', [
                        'company_id' => $company->id,
                        'new_plan' => $planName,
                        'new_price' => $planData['price']
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour de l\'abonnement', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function handleSubscriptionDeleted($subscription)
    {
        try {
            $company = \App\Models\Company::where('stripe_subscription_id', $subscription->id)->first();
            
            if ($company) {
                // Passer au plan gratuit
                $company->update([
                    'plan' => 'starter',
                    'max_users' => 10,
                    'stripe_subscription_id' => null
                ]);
                
                \Log::info('Abonnement annulé', ['company_id' => $company->id]);
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'annulation de l\'abonnement', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function handlePaymentSucceeded($invoice)
    {
        \Log::info('Paiement réussi', ['invoice_id' => $invoice->id]);
    }

    private function handlePaymentFailed($invoice)
    {
        \Log::warning('Échec du paiement', ['invoice_id' => $invoice->id]);
    }

    /**
     * Rediriger vers le portail client Stripe
     */
    public function portal()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            abort(403, 'Aucune entreprise associée à votre compte.');
        }

        if (!$company->stripe_customer_id) {
            return back()->with('error', 'Aucun abonnement actif trouvé.');
        }

        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            
            $session = \Stripe\BillingPortal\Session::create([
                'customer' => $company->stripe_customer_id,
                'return_url' => route('entreprise.abonnement.index'),
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            \Log::error('Stripe Customer Portal Error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'accès au portail client. Veuillez réessayer.');
        }
    }
}
