<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Super admin peut voir tous les utilisateurs
        if ($user->is_super_admin()) {
            $users = User::with('company')->get();
        }
        // Admin entreprise peut voir les utilisateurs de son entreprise
        elseif ($user->isAdminEntreprise()) {
            $users = User::where('company_id', $user->company_id)->with('company')->get();
        }
        // Autres utilisateurs ne peuvent pas voir la liste
        else {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Seuls les super admins et admins d'entreprise peuvent créer des utilisateurs
        if (!$user->is_super_admin() && !$user->isAdminEntreprise()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user_independant,admin_entreprise,user_entreprise,super_admin',
            'company_id' => 'nullable|exists:companies,id',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        // Vérifier les permissions selon le rôle
        if ($validated['role'] === 'super_admin' && !$user->is_super_admin()) {
            return response()->json(['error' => 'Seuls les super admins peuvent créer des super admins'], 403);
        }

        if (in_array($validated['role'], ['admin_entreprise', 'user_entreprise'])) {
            if (!$user->is_super_admin() && $user->company_id !== $validated['company_id']) {
                return response()->json(['error' => 'Vous ne pouvez créer des utilisateurs que pour votre entreprise'], 403);
            }
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'company_id' => $validated['company_id'] ?? null,
            'position' => $validated['position'] ?? null,
            'department' => $validated['department'] ?? null,
        ]);

        return response()->json($user->load('company'), 201);
    }

    public function show(User $user)
    {
        $currentUser = Auth::user();
        
        // Vérifier les permissions
        if (!$currentUser->is_super_admin() && 
            !$currentUser->isAdminEntreprise() && 
            $currentUser->id !== $user->id) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        return response()->json($user->load('company'));
    }

    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Vérifier les permissions
        if (!$currentUser->is_super_admin() && 
            !$currentUser->isAdminEntreprise() && 
            $currentUser->id !== $user->id) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8',
            'role' => 'sometimes|in:user_independant,admin_entreprise,user_entreprise,super_admin',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'is_premium' => 'sometimes|boolean',
            'permissions' => 'nullable|json',
        ]);

        // Seuls les super admins peuvent changer les rôles
        if (isset($validated['role']) && !$currentUser->is_super_admin()) {
            unset($validated['role']);
        }

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return response()->json($user->load('company'));
    }

    public function destroy(User $user)
    {
        $currentUser = Auth::user();
        
        // Seuls les super admins et admins d'entreprise peuvent supprimer des utilisateurs
        if (!$currentUser->is_super_admin() && !$currentUser->isAdminEntreprise()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        // Un utilisateur ne peut pas se supprimer lui-même
        if ($currentUser->id === $user->id) {
            return response()->json(['error' => 'Vous ne pouvez pas vous supprimer vous-même'], 403);
        }

        $user->delete();
        return response()->json(null, 204);
    }

    /**
     * Obtenir le profil de l'utilisateur connecté
     */
    public function profile()
    {
        $user = Auth::user();
        return response()->json($user->load('company'));
    }

    /**
     * Mettre à jour le profil de l'utilisateur connecté
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return response()->json($user->load('company'));
    }
}
