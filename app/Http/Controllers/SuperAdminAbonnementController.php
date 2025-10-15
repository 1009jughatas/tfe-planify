<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class SuperAdminAbonnementController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::with(['users']);

        // Filtres
        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $companies = $query->paginate(20);

        // Statistiques
        $stats = [
            'total_companies' => Company::count(),
            'active_subscriptions' => Company::where('status', 'active')->count(),
            'premium_users' => \App\Models\User::where('is_premium', true)->count(),
            'monthly_revenue' => Company::where('status', 'active')->sum('monthly_price') ?? 0,
        ];

        return view('superadmin.abonnements.index', compact('companies', 'stats'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'plan' => 'required|in:starter,growth,enterprise',
            'monthly_price' => 'required|numeric|min:0',
            'max_users' => 'required|integer|min:-1',
        ]);

        $company->update([
            'plan' => $request->plan,
            'monthly_price' => $request->monthly_price,
            'max_users' => $request->max_users,
            'user_limit' => $request->max_users,
        ]);

        return back()->with('success', 'Abonnement mis à jour avec succès.');
    }

    public function cancel(Company $company)
    {
        $company->update([
            'status' => 'cancelled'
        ]);

        return response()->json(['success' => true, 'message' => 'Abonnement annulé avec succès.']);
    }
}