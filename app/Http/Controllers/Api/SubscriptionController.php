<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Obtenir les informations d'abonnement de l'entreprise
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isPartOfCompany()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $company = $user->company;
        
        if (!$company) {
            return response()->json(['error' => 'Aucune entreprise associée'], 404);
        }

        $subscription = [
            'company_id' => $company->id,
            'company_name' => $company->name,
            'plan' => $company->plan,
            'monthly_price' => $company->monthly_price,
            'max_users' => $company->max_users,
            'status' => $company->status,
            'stripe_customer_id' => $company->stripe_customer_id,
            'stripe_subscription_id' => $company->stripe_subscription_id,
            'created_at' => $company->created_at,
            'updated_at' => $company->updated_at,
        ];

        return response()->json($subscription);
    }

    /**
     * Obtenir les plans disponibles
     */
    public function plans()
    {
        $plans = [
            'starter' => [
                'name' => 'Starter',
                'price' => 399,
                'max_users' => 10,
                'features' => [
                    '10 utilisateurs',
                    'Projets illimités',
                    'Support email',
                    'Interface moderne'
                ]
            ],
            'growth' => [
                'name' => 'Growth',
                'price' => 599,
                'max_users' => 20,
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
                'features' => [
                    'Utilisateurs illimités',
                    'Projets illimités',
                    'Support prioritaire',
                    'Statistiques avancées',
                    'API personnalisée'
                ]
            ]
        ];

        return response()->json($plans);
    }

    /**
     * Mettre à jour l'abonnement (pour les super admins)
     */
    public function update(Request $request, Company $company)
    {
        $user = Auth::user();
        
        // Seuls les super admins peuvent modifier les abonnements
        if (!$user->is_super_admin()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'plan' => 'required|in:starter,growth,enterprise',
            'max_users' => 'nullable|integer|min:1',
            'monthly_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);

        // Définir les limites selon le plan
        $planLimits = [
            'starter' => ['max_users' => 10, 'monthly_price' => 399],
            'growth' => ['max_users' => 20, 'monthly_price' => 599],
            'enterprise' => ['max_users' => -1, 'monthly_price' => 999],
        ];

        if (isset($planLimits[$validated['plan']])) {
            $validated['max_users'] = $planLimits[$validated['plan']]['max_users'];
            $validated['monthly_price'] = $planLimits[$validated['plan']]['monthly_price'];
        }

        $company->update($validated);
        
        return response()->json([
            'message' => 'Abonnement mis à jour avec succès',
            'subscription' => [
                'company_id' => $company->id,
                'plan' => $company->plan,
                'max_users' => $company->max_users,
                'monthly_price' => $company->monthly_price,
                'status' => $company->status,
            ]
        ]);
    }

    /**
     * Annuler l'abonnement
     */
    public function cancel()
    {
        $user = Auth::user();
        
        if (!$user->isPartOfCompany()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $company = $user->company;
        
        if (!$company) {
            return response()->json(['error' => 'Aucune entreprise associée'], 404);
        }

        // Passer au plan gratuit
        $company->update([
            'plan' => 'starter',
            'max_users' => 10,
            'monthly_price' => 0,
            'stripe_subscription_id' => null,
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Abonnement annulé avec succès',
            'subscription' => [
                'plan' => $company->plan,
                'max_users' => $company->max_users,
                'monthly_price' => $company->monthly_price,
            ]
        ]);
    }

    /**
     * Obtenir l'historique des abonnements (pour les super admins)
     */
    public function history(Company $company)
    {
        $user = Auth::user();
        
        if (!$user->is_super_admin()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        // Pour l'instant, retourner les informations actuelles
        // Dans une vraie application, on aurait une table d'historique
        $history = [
            [
                'date' => $company->created_at,
                'plan' => $company->plan,
                'price' => $company->monthly_price,
                'action' => 'created',
            ]
        ];

        return response()->json($history);
    }
}