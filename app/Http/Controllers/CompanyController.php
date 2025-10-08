<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies (Super Admin only).
     */
    public function index()
    {
        $this->authorize('viewAny', Company::class);
        
        $companies = Company::with(['admin', 'users'])
            ->withCount('users')
            ->paginate(15);
            
        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company.
     */
    public function create()
    {
        $this->authorize('create', Company::class);
        
        return view('admin.companies.create');
    }

    /**
     * Store a newly created company.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Company::class);
        
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|unique:companies,email',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
            'plan' => 'required|in:starter,growth,enterprise',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
        ]);

        return DB::transaction(function () use ($request) {
            // Créer l'admin de l'entreprise
            $admin = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'role' => 'company_admin',
                'position' => 'Directeur',
                'is_active' => true,
            ]);

            // Définir les détails du plan
            $planDetails = [
                'starter' => ['price' => 399, 'users' => 10],
                'growth' => ['price' => 599, 'users' => 20],
                'enterprise' => ['price' => 999, 'users' => -1], // Illimité
            ];

            // Créer l'entreprise
            $company = Company::create([
                'name' => $request->company_name,
                'slug' => Str::slug($request->company_name),
                'email' => $request->company_email,
                'phone' => $request->phone,
                'address' => $request->address,
                'website' => $request->website,
                'plan' => $request->plan,
                'monthly_price' => $planDetails[$request->plan]['price'],
                'user_limit' => $planDetails[$request->plan]['users'],
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(14), // 14 jours d'essai
                'admin_id' => $admin->id,
            ]);

            // Associer l'admin à l'entreprise
            $admin->update(['company_id' => $company->id]);

            return redirect()->route('admin.companies.index')
                ->with('success', "L'entreprise {$company->name} a été créée avec succès. L'admin peut maintenant inviter ses employés.");
        });
    }

    /**
     * Display the specified company.
     */
    public function show(Company $company)
    {
        $this->authorize('view', $company);
        
        $company->load(['admin', 'users', 'projects', 'invitations']);
        
        return view('admin.companies.show', compact('company'));
    }

    /**
     * Show the form for editing the company.
     */
    public function edit(Company $company)
    {
        $this->authorize('update', $company);
        
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Update the specified company.
     */
    public function update(Request $request, Company $company)
    {
        $this->authorize('update', $company);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('companies')->ignore($company->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
            'plan' => 'required|in:starter,growth,enterprise',
            'status' => 'required|in:active,suspended,trial',
        ]);

        // Définir les détails du plan
        $planDetails = [
            'starter' => ['price' => 399, 'users' => 10],
            'growth' => ['price' => 599, 'users' => 20],
            'enterprise' => ['price' => 999, 'users' => -1],
        ];

        $company->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'website' => $request->website,
            'plan' => $request->plan,
            'monthly_price' => $planDetails[$request->plan]['price'],
            'user_limit' => $planDetails[$request->plan]['users'],
            'status' => $request->status,
        ]);

        return redirect()->route('admin.companies.show', $company)
            ->with('success', 'Entreprise mise à jour avec succès.');
    }

    /**
     * Remove the specified company.
     */
    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);
        
        $companyName = $company->name;
        
        // Supprimer l'entreprise (les utilisateurs et projets seront supprimés par cascade)
        $company->delete();
        
        return redirect()->route('admin.companies.index')
            ->with('success', "L'entreprise {$companyName} a été supprimée avec succès.");
    }

    /**
     * Invite a user to the company.
     */
    public function inviteUser(Request $request, Company $company)
    {
        $this->authorize('inviteUsers', $company);
        
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:member,company_admin',
        ]);

        // Vérifier si l'entreprise peut ajouter un utilisateur
        if (!$company->canAddUser()) {
            return back()->with('error', 'Limite d\'utilisateurs atteinte pour ce plan.');
        }

        // Vérifier si l'email n'est pas déjà invité
        $existingInvitation = $company->invitations()
            ->where('email', $request->email)
            ->whereNull('accepted_at')
            ->first();

        if ($existingInvitation && !$existingInvitation->isExpired()) {
            return back()->with('error', 'Une invitation est déjà en cours pour cet email.');
        }

        // Créer l'invitation
        $invitation = $company->invitations()->create([
            'email' => $request->email,
            'role' => $request->role,
            'token' => Str::random(40),
            'expires_at' => now()->addDays(7),
        ]);

        // TODO: Envoyer l'email d'invitation
        // Mail::to($request->email)->send(new CompanyInvitationMail($invitation));

        return back()->with('success', "Invitation envoyée à {$request->email}.");
    }

    /**
     * Show company registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.company-register');
    }

    /**
     * Register a new company.
     */
    public function register(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|unique:companies,email',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
            'plan' => 'required|in:starter,growth,enterprise',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
            'terms' => 'required|accepted',
        ]);

        return DB::transaction(function () use ($request) {
            // Créer l'admin de l'entreprise
            $admin = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'role' => 'company_admin',
                'position' => 'Directeur',
                'is_active' => true,
            ]);

            // Définir les détails du plan
            $planDetails = [
                'starter' => ['price' => 399, 'users' => 10],
                'growth' => ['price' => 599, 'users' => 20],
                'enterprise' => ['price' => 999, 'users' => -1],
            ];

            // Créer l'entreprise
            $company = Company::create([
                'name' => $request->company_name,
                'slug' => Str::slug($request->company_name),
                'email' => $request->company_email,
                'phone' => $request->phone,
                'address' => $request->address,
                'website' => $request->website,
                'plan' => $request->plan,
                'monthly_price' => $planDetails[$request->plan]['price'],
                'user_limit' => $planDetails[$request->plan]['users'],
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
                'admin_id' => $admin->id,
            ]);

            // Associer l'admin à l'entreprise
            $admin->update(['company_id' => $company->id]);

            // Connecter l'admin
            auth()->login($admin);

            return redirect()->route('dashboard')
                ->with('success', "Votre entreprise {$company->name} a été créée avec succès ! Vous pouvez maintenant inviter vos employés.");
        });
    }
}