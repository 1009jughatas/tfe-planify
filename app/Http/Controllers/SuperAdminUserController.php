<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['company', 'projects', 'tasks', 'ticketSupports']);

        // Filtres
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('company')) {
            $query->where('company_id', $request->company);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => User::count(),
            'independants' => User::where('role', 'user_independant')->count(),
            'admin_entreprise' => User::where('role', 'admin_entreprise')->count(),
            'user_entreprise' => User::where('role', 'user_entreprise')->count(),
            'super_admin' => User::where('role', 'super_admin')->count(),
        ];

        $companies = Company::all();

        return view('superadmin.users.index', compact('users', 'stats', 'companies'));
    }

    public function show(User $user)
    {
        $user->load(['company', 'projects', 'tasks', 'ticketSupports']);
        
        return view('superadmin.users.show', compact('user'));
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        return back()->with('success', 'Statut de l\'utilisateur mis à jour avec succès.');
    }
}