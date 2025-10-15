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

        // Plans disponibles
        $plans = [
            'starter' => [
                'name' => 'Starter',
                'price' => 19,
                'max_users' => 5,
                'stripe_price_id' => 'price_starter_monthly',
                'features' => [
                    'Jusqu\'à 5 utilisateurs',
                    'Projets illimités',
                    'Tâches illimitées',
                    'Support email',
                    'Stockage 10GB',
                    'Export PDF basique'
                ]
            ],
            'professional' => [
                'name' => 'Professional',
                'price' => 49,
                'max_users' => 20,
                'stripe_price_id' => 'price_professional_monthly',
                'features' => [
                    'Jusqu\'à 20 utilisateurs',
                    'Projets illimités',
                    'Tâches illimitées',
                    'Support prioritaire',
                    'Stockage 100GB',
                    'Export PDF avancé',
                    'Rapports détaillés',
                    'Intégrations API',
                    'Gestion des permissions'
                ]
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price' => 99,
                'max_users' => 100,
                'stripe_price_id' => 'price_enterprise_monthly',
                'features' => [
                    'Jusqu\'à 100 utilisateurs',
                    'Projets illimités',
                    'Tâches illimitées',
                    'Support dédié 24/7',
                    'Stockage illimité',
                    'Export PDF personnalisé',
                    'Rapports personnalisés',
                    'Intégrations avancées',
                    'SSO et sécurité avancée',
                    'API complète',
                    'Formation personnalisée'
                ]
            ]
        ];

        // Plan actuel
        $currentPlan = $company->plan ?? 'starter';
        $currentPlanData = $plans[$currentPlan] ?? $plans['starter'];

        // Vérifier si un changement de plan est nécessaire
        $needsUpgrade = $currentUsers >= $maxUsers;
        $canDowngrade = $currentUsers <= ($plans['starter']['max_users'] ?? 5);

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
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            abort(403, 'Aucune entreprise associée à votre compte.');
        }

        $request->validate([
            'plan' => 'required|in:starter,professional,enterprise'
        ]);

        $newPlan = $request->plan;
        $currentUsers = \App\Models\User::where('company_id', $company->id)->count();

        // Vérifier si le nouveau plan peut accueillir tous les utilisateurs actuels
        $planLimits = [
            'starter' => 5,
            'professional' => 20,
            'enterprise' => 100
        ];

        if ($currentUsers > $planLimits[$newPlan]) {
            return back()->with('error', 'Impossible de passer au plan ' . ucfirst($newPlan) . '. Vous avez trop d\'utilisateurs (' . $currentUsers . '). Veuillez supprimer des utilisateurs ou choisir un plan supérieur.');
        }

        // Plans avec prix Stripe
        $plans = [
            'starter' => [
                'name' => 'Starter',
                'price' => 19,
                'max_users' => 5,
                'stripe_price_id' => 'price_starter_monthly'
            ],
            'professional' => [
                'name' => 'Professional',
                'price' => 49,
                'max_users' => 20,
                'stripe_price_id' => 'price_professional_monthly'
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price' => 99,
                'max_users' => 100,
                'stripe_price_id' => 'price_enterprise_monthly'
            ]
        ];

        $selectedPlan = $plans[$newPlan];

        // Rediriger vers Stripe Checkout
        return $this->createStripeCheckoutSession($company, $selectedPlan);
    }

    private function createStripeCheckoutSession($company, $plan)
    {
        try {
            \Stripe\Stripe::setApiKey(config('stripe.secret'));

            $checkout_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Plan ' . $plan['name'] . ' - ' . $company->name,
                            'description' => 'Abonnement mensuel pour ' . $plan['max_users'] . ' utilisateurs',
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
                'customer_email' => auth()->user()->email,
                'metadata' => [
                    'company_id' => $company->id,
                    'plan' => $plan['name'],
                    'max_users' => $plan['max_users']
                ]
            ]);

            return redirect($checkout_session->url);
        } catch (\Exception $e) {
            \Log::error('Stripe Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la création de la session de paiement. Veuillez réessayer.');
        }
    }

    public function success(Request $request)
    {
        try {
            \Stripe\Stripe::setApiKey(config('stripe.secret'));
            
            $session = \Stripe\Checkout\Session::retrieve($request->session_id);
            
            if ($session->payment_status === 'paid') {
                $company = \App\Models\Company::find($session->metadata->company_id);
                
                if ($company) {
                    // Mettre à jour le plan de l'entreprise
                    $company->update([
                        'plan' => strtolower($session->metadata->plan),
                        'max_users' => $session->metadata->max_users,
                        'stripe_subscription_id' => $session->subscription,
                        'stripe_customer_id' => $session->customer
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
                \Stripe\Stripe::setApiKey(config('stripe.secret'));
                $subscription = \Stripe\Subscription::retrieve($company->stripe_subscription_id);
                $subscription->cancel();
            }

            // Passer au plan gratuit
            $company->update([
                'plan' => 'starter',
                'max_users' => 2, // Plan gratuit limité
                'stripe_subscription_id' => null
            ]);

            return redirect()->route('entreprise.abonnement.index')
                ->with('success', 'Abonnement annulé avec succès. Vous êtes maintenant sur le plan gratuit.');

        } catch (\Exception $e) {
            \Log::error('Stripe Cancel Error: ' . $e->getMessage());
            
            // Même en cas d'erreur Stripe, on passe au plan gratuit
            $company->update([
                'plan' => 'starter',
                'max_users' => 2,
                'stripe_subscription_id' => null
            ]);

            return redirect()->route('entreprise.abonnement.index')
                ->with('success', 'Abonnement annulé avec succès. Vous êtes maintenant sur le plan gratuit.');
        }
    }
}
