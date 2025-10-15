<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminAbonnementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isSuperAdmin']);
    }

    /**
     * Afficher tous les abonnements
     */
    public function index(Request $request)
    {
        $query = Company::with('admin');

        // Filtres
        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $companies = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total_companies' => Company::count(),
            'active_companies' => Company::where('status', 'active')->count(),
            'starter_plan' => Company::where('plan', 'starter')->count(),
            'growth_plan' => Company::where('plan', 'growth')->count(),
            'enterprise_plan' => Company::where('plan', 'enterprise')->count(),
            'with_stripe' => Company::whereNotNull('stripe_customer_id')->count(),
        ];

        // Utilisateurs indépendants premium
        $premiumUsers = User::where('role', 'user_independant')
            ->where('is_premium', true)
            ->with('preferences')
            ->get();

        return view('superadmin.abonnements.index', compact('companies', 'stats', 'premiumUsers'));
    }

    /**
     * Modifier manuellement un abonnement
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'plan' => 'required|in:starter,growth,enterprise',
            'status' => 'required|in:active,past_due,cancelled',
            'max_users' => 'nullable|integer|min:1',
        ]);

        $company = Company::findOrFail($id);

        $company->update([
            'plan' => $request->plan,
            'status' => $request->status,
            'max_users' => $request->max_users ?? $company->max_users,
        ]);

        return redirect()->back()
            ->with('success', 'Abonnement modifié avec succès.');
    }

    /**
     * Résilier un abonnement
     */
    public function cancel($id)
    {
        $company = Company::findOrFail($id);

        $company->update([
            'status' => 'cancelled',
            'stripe_subscription_id' => null,
        ]);

        return redirect()->back()
            ->with('success', 'Abonnement résilié avec succès.');
    }
}