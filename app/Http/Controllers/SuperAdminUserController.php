<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;

class SuperAdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isSuperAdmin']);
    }

    /**
     * Afficher tous les utilisateurs
     */
    public function index(Request $request)
    {
        $query = User::with('company');

        // Filtres
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('company')) {
            $query->where('company_id', $request->company);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total' => User::count(),
            'independants' => User::where('role', 'user_independant')->count(),
            'admin_entreprise' => User::where('role', 'admin_entreprise')->count(),
            'user_entreprise' => User::where('role', 'user_entreprise')->count(),
            'super_admin' => User::where('role', 'super_admin')->count(),
        ];

        // Liste des entreprises pour le filtre
        $companies = Company::orderBy('name')->get();

        return view('superadmin.users.index', compact('users', 'stats', 'companies'));
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show($id)
    {
        $user = User::with(['company', 'projects', 'tasks'])->findOrFail($id);
        
        // Statistiques de l'utilisateur
        $userStats = [
            'projects_count' => $user->projects()->count(),
            'tasks_count' => $user->tasks()->count(),
            'tickets_count' => $user->ticketSupports()->count(),
            'last_login' => $user->last_login_at ?? 'Jamais',
        ];

        return view('superadmin.users.show', compact('user', 'userStats'));
    }

    /**
     * Activer/Désactiver un utilisateur
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'activé' : 'désactivé';
        
        return redirect()->back()
            ->with('success', "Utilisateur {$status} avec succès.");
    }
}