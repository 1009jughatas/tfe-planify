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
                'price' => 29,
                'max_users' => 5,
                'features' => [
                    'Jusqu\'à 5 utilisateurs',
                    'Projets illimités',
                    'Tâches illimitées',
                    'Support email',
                    'Stockage 10GB'
                ]
            ],
            'professional' => [
                'name' => 'Professional',
                'price' => 59,
                'max_users' => 15,
                'features' => [
                    'Jusqu\'à 15 utilisateurs',
                    'Projets illimités',
                    'Tâches illimitées',
                    'Support prioritaire',
                    'Stockage 50GB',
                    'Rapports avancés',
                    'Intégrations API'
                ]
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price' => 99,
                'max_users' => 50,
                'features' => [
                    'Jusqu\'à 50 utilisateurs',
                    'Projets illimités',
                    'Tâches illimitées',
                    'Support dédié 24/7',
                    'Stockage illimité',
                    'Rapports personnalisés',
                    'Intégrations avancées',
                    'SSO et sécurité avancée'
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
            'professional' => 15,
            'enterprise' => 50
        ];

        if ($currentUsers > $planLimits[$newPlan]) {
            return back()->with('error', 'Impossible de passer au plan ' . ucfirst($newPlan) . '. Vous avez trop d\'utilisateurs (' . $currentUsers . '). Veuillez supprimer des utilisateurs ou choisir un plan supérieur.');
        }

        // Mettre à jour le plan
        $company->update([
            'plan' => $newPlan,
            'max_users' => $planLimits[$newPlan]
        ]);

        // TODO: Intégrer avec Stripe pour le paiement
        // Pour l'instant, on simule juste le changement

        return redirect()->route('entreprise.abonnement.index')
            ->with('success', 'Plan mis à jour avec succès vers ' . ucfirst($newPlan) . '.');
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

        // Passer au plan gratuit
        $company->update([
            'plan' => 'starter',
            'max_users' => 2 // Plan gratuit limité
        ]);

        // TODO: Annuler l'abonnement Stripe

        return redirect()->route('entreprise.abonnement.index')
            ->with('success', 'Abonnement annulé avec succès. Vous êtes maintenant sur le plan gratuit.');
    }
}
