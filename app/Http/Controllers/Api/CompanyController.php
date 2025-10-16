<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Seuls les super admins peuvent voir toutes les entreprises
        if (!$user->is_super_admin()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $companies = Company::with(['admin', 'users'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($companies);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Seuls les super admins peuvent créer des entreprises
        if (!$user->is_super_admin()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'website' => 'nullable|url',
            'admin_id' => 'required|exists:users,id',
            'plan' => 'nullable|in:starter,growth,enterprise',
            'max_users' => 'nullable|integer|min:1',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);

        $company = Company::create($validated);
        return response()->json($company->load(['admin', 'users']), 201);
    }

    public function show(Company $company)
    {
        $user = Auth::user();
        
        // Super admin peut voir toutes les entreprises
        if ($user->is_super_admin()) {
            return response()->json($company->load(['admin', 'users']));
        }
        
        // Admin d'entreprise peut voir sa propre entreprise
        if ($user->isAdminEntreprise() && $user->company_id === $company->id) {
            return response()->json($company->load(['admin', 'users']));
        }

        return response()->json(['error' => 'Accès non autorisé'], 403);
    }

    public function update(Request $request, Company $company)
    {
        $user = Auth::user();
        
        // Super admin peut modifier toutes les entreprises
        if ($user->is_super_admin()) {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:companies,email,' . $company->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'website' => 'nullable|url',
                'admin_id' => 'sometimes|exists:users,id',
                'plan' => 'sometimes|in:starter,growth,enterprise',
                'max_users' => 'sometimes|integer|min:1',
                'status' => 'sometimes|in:active,inactive,suspended',
            ]);
        }
        // Admin d'entreprise peut modifier sa propre entreprise (limité)
        elseif ($user->isAdminEntreprise() && $user->company_id === $company->id) {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:companies,email,' . $company->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'website' => 'nullable|url',
            ]);
        }
        else {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $company->update($validated);
        return response()->json($company->load(['admin', 'users']));
    }

    public function destroy(Company $company)
    {
        $user = Auth::user();
        
        // Seuls les super admins peuvent supprimer des entreprises
        if (!$user->is_super_admin()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $company->delete();
        return response()->json(null, 204);
    }

    /**
     * Obtenir les utilisateurs d'une entreprise
     */
    public function users(Company $company)
    {
        $user = Auth::user();
        
        // Vérifier les permissions
        if (!$user->is_super_admin() && 
            (!$user->isAdminEntreprise() || $user->company_id !== $company->id)) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $users = $company->users()->with('company')->get();
        return response()->json($users);
    }

    /**
     * Obtenir les statistiques d'une entreprise
     */
    public function stats(Company $company)
    {
        $user = Auth::user();
        
        // Vérifier les permissions
        if (!$user->is_super_admin() && 
            (!$user->isAdminEntreprise() || $user->company_id !== $company->id)) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $stats = [
            'total_users' => $company->users()->count(),
            'total_projects' => $company->projects()->count(),
            'total_tasks' => $company->tasks()->count(),
            'active_projects' => $company->projects()->where('status', 'in-progress')->count(),
            'completed_projects' => $company->projects()->where('status', 'completed')->count(),
            'completed_tasks' => $company->tasks()->where('status', 'completed')->count(),
            'overdue_tasks' => $company->tasks()
                ->where('due_date', '<', now())
                ->where('status', '!=', 'completed')
                ->count(),
        ];

        return response()->json($stats);
    }
}