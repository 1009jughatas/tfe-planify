<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthIndepController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Afficher le formulaire d'inscription pour les indépendants
     */
    public function showRegister()
    {
        // Rediriger les utilisateurs déjà connectés vers le dashboard approprié
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->role === 'user_independant') {
                return redirect()->route('dashboard');
            } elseif ($user->company_id) {
                return redirect()->route('entreprise.dashboard');
            }
        }

        return view('independant.auth.register');
    }

    /**
     * Traiter l'inscription des indépendants
     */
    public function register(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Votre nom est obligatoire.',
            'email.required' => 'Votre adresse email est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // Créer l'utilisateur indépendant
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user_independant',
            'company_id' => null,
            'is_active' => true,
        ]);

        // Connecter automatiquement l'utilisateur
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Votre compte a été créé avec succès ! Bienvenue sur Planify.');
    }

    /**
     * Afficher le formulaire de connexion pour les indépendants
     */
    public function showLogin()
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->role === 'user_independant') {
                return redirect()->route('dashboard');
            } elseif ($user->company_id) {
                return redirect()->route('entreprise.dashboard');
            }
        }

        return view('independant.auth.login');
    }

    /**
     * Traiter la connexion des indépendants
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Vérifier que l'utilisateur est bien un indépendant
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && $user->company_id) {
            return back()->withErrors([
                'email' => 'Cette adresse email correspond à un compte entreprise. Veuillez utiliser le formulaire de connexion entreprise.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Vérifier que l'utilisateur est bien un indépendant
            if ($user->role !== 'user_independant' || $user->company_id) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Ce compte ne correspond pas à un utilisateur indépendant.',
                ])->onlyInput('email');
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('independant.login');
    }
}
