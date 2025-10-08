<?php

namespace App\Http\Controllers;

use App\Models\CompanyInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CompanyInvitationController extends Controller
{
    /**
     * Show the invitation acceptance form.
     */
    public function show(Request $request, $token)
    {
        $invitation = CompanyInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->with('company')
            ->first();

        if (!$invitation) {
            return redirect()->route('login')
                ->with('error', 'Invitation invalide ou expirée.');
        }

        if ($invitation->isExpired()) {
            return redirect()->route('login')
                ->with('error', 'Cette invitation a expiré.');
        }

        return view('auth.invitation-accept', compact('invitation'));
    }

    /**
     * Accept the invitation and create user account.
     */
    public function accept(Request $request, $token)
    {
        $invitation = CompanyInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->with('company')
            ->first();

        if (!$invitation || $invitation->isExpired()) {
            return redirect()->route('login')
                ->with('error', 'Invitation invalide ou expirée.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        // Vérifier si l'entreprise peut encore ajouter des utilisateurs
        if (!$invitation->company->canAddUser()) {
            return back()->with('error', 'Cette entreprise a atteint sa limite d\'utilisateurs.')
                ->withInput();
        }

        return DB::transaction(function () use ($request, $invitation) {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $request->name,
                'email' => $invitation->email,
                'password' => Hash::make($request->password),
                'role' => $invitation->role,
                'company_id' => $invitation->company_id,
                'position' => $request->position,
                'department' => $request->department,
                'is_active' => true,
            ]);

            // Marquer l'invitation comme acceptée
            $invitation->accept($user);

            // Connecter l'utilisateur
            auth()->login($user);

            return redirect()->route('dashboard')
                ->with('success', "Bienvenue dans l'équipe {$invitation->company->name} !");
        });
    }

    /**
     * Decline the invitation.
     */
    public function decline($token)
    {
        $invitation = CompanyInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->first();

        if ($invitation) {
            $invitation->delete();
        }

        return redirect()->route('login')
            ->with('info', 'Invitation déclinée.');
    }

    /**
     * Resend invitation.
     */
    public function resend(CompanyInvitation $invitation)
    {
        // Vérifier les permissions
        $this->authorize('inviteUsers', $invitation->company);

        if ($invitation->isAccepted()) {
            return back()->with('error', 'Cette invitation a déjà été acceptée.');
        }

        // Générer un nouveau token et étendre la date d'expiration
        $invitation->update([
            'token' => \Illuminate\Support\Str::random(40),
            'expires_at' => now()->addDays(7),
        ]);

        // TODO: Envoyer l'email d'invitation
        // Mail::to($invitation->email)->send(new CompanyInvitationMail($invitation));

        return back()->with('success', 'Invitation renvoyée avec succès.');
    }

    /**
     * Cancel invitation.
     */
    public function cancel(CompanyInvitation $invitation)
    {
        // Vérifier les permissions
        $this->authorize('inviteUsers', $invitation->company);

        if ($invitation->isAccepted()) {
            return back()->with('error', 'Impossible d\'annuler une invitation déjà acceptée.');
        }

        $invitation->delete();

        return back()->with('success', 'Invitation annulée.');
    }
}