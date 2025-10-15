<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmployeeInvitationController extends Controller
{
    /**
     * Afficher la page d'invitation d'employé
     */
    public function showInviteForm()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            abort(403, 'Vous devez appartenir à une entreprise pour inviter des employés.');
        }

        $pendingInvitations = $company->invitations()
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->get();

        return view('entreprise.admin.invite-employee', compact('company', 'pendingInvitations'));
    }

    /**
     * Envoyer une invitation à un employé
     */
    public function sendInvitation(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            abort(403, 'Vous devez appartenir à une entreprise pour inviter des employés.');
        }

        // Vérifier si l'entreprise peut ajouter un utilisateur
        if (!$company->canAddUser()) {
            return back()->with('error', 'Vous avez atteint la limite d\'utilisateurs pour votre plan. Veuillez upgrader votre abonnement.');
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:user_entreprise',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'role.required' => 'Le rôle est obligatoire.',
            'role.in' => 'Le rôle sélectionné n\'est pas valide.',
        ]);

        // Vérifier qu'il n'y a pas déjà une invitation en cours pour cet email
        $existingInvitation = CompanyInvitation::where('company_id', $company->id)
            ->where('email', $validated['email'])
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvitation) {
            return back()->with('error', 'Une invitation est déjà en cours pour cette adresse email.');
        }

        try {
            DB::beginTransaction();

            // Créer l'invitation
            $invitation = CompanyInvitation::createInvitation(
                $company->id,
                $validated['email'],
                $validated['role'],
                $user->id
            );

            // Stocker les informations supplémentaires
            $invitation->update([
                'position' => $validated['position'] ?? 'Employé',
                'department' => $validated['department'] ?? 'Général',
            ]);

            // Envoyer l'email d'invitation
            $this->sendInvitationEmail($invitation);

            DB::commit();

            return back()->with('success', 'L\'invitation a été envoyée avec succès à ' . $validated['email']);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur lors de l\'envoi de l\'invitation: ' . $e->getMessage());
            
            return back()->with('error', 'Une erreur est survenue lors de l\'envoi de l\'invitation. Veuillez réessayer.');
        }
    }

    /**
     * Afficher la page d'acceptation d'invitation
     */
    public function showAcceptInvitation($token)
    {
        $invitation = CompanyInvitation::where('token', $token)->first();

        if (!$invitation) {
            abort(404, 'Invitation non trouvée.');
        }

        if ($invitation->isExpired()) {
            return view('entreprise.invitation-expired', compact('invitation'));
        }

        if ($invitation->isAccepted()) {
            return view('entreprise.invitation-already-accepted', compact('invitation'));
        }

        return view('entreprise.accept-invitation', compact('invitation'));
    }

    /**
     * Traiter l'acceptation d'invitation
     */
    public function acceptInvitation(Request $request, $token)
    {
        $invitation = CompanyInvitation::where('token', $token)->first();

        if (!$invitation) {
            abort(404, 'Invitation non trouvée.');
        }

        if ($invitation->isExpired()) {
            return redirect()->route('invitations.accept', $token)
                ->with('error', 'Cette invitation a expiré.');
        }

        if ($invitation->isAccepted()) {
            return redirect()->route('invitations.accept', $token)
                ->with('error', 'Cette invitation a déjà été acceptée.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Votre nom est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        try {
            DB::beginTransaction();

            // Créer l'utilisateur
            $user = User::create([
                'name' => $validated['name'],
                'email' => $invitation->email,
                'password' => Hash::make($validated['password']),
                'role' => $invitation->role,
                'company_id' => $invitation->company_id,
                'position' => $invitation->position ?? 'Employé',
                'department' => $invitation->department ?? 'Général',
                'is_active' => true,
            ]);

            // Marquer l'invitation comme acceptée
            $invitation->accept($user);

            DB::commit();

            // Connecter automatiquement l'utilisateur
            Auth::login($user);

            return redirect()->route('entreprise.dashboard')
                ->with('success', 'Bienvenue dans votre entreprise ! Votre compte a été créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur lors de l\'acceptation de l\'invitation: ' . $e->getMessage());
            
            return redirect()->route('invitations.accept', $token)
                ->with('error', 'Une erreur est survenue lors de la création de votre compte. Veuillez réessayer.');
        }
    }

    /**
     * Refuser une invitation
     */
    public function declineInvitation($token)
    {
        $invitation = CompanyInvitation::where('token', $token)->first();

        if (!$invitation) {
            abort(404, 'Invitation non trouvée.');
        }

        if ($invitation->isAccepted()) {
            return redirect()->route('invitations.accept', $token)
                ->with('error', 'Cette invitation a déjà été acceptée.');
        }

        // Marquer l'invitation comme refusée (optionnel - on peut juste la laisser expirer)
        $invitation->update(['expires_at' => now()]);

        return view('entreprise.invitation-declined', compact('invitation'));
    }

    /**
     * Annuler une invitation en cours
     */
    public function cancelInvitation($invitationId)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            abort(403, 'Vous devez appartenir à une entreprise pour annuler des invitations.');
        }

        $invitation = CompanyInvitation::where('id', $invitationId)
            ->where('company_id', $company->id)
            ->first();

        if (!$invitation) {
            abort(404, 'Invitation non trouvée.');
        }

        if ($invitation->isAccepted()) {
            return back()->with('error', 'Cette invitation a déjà été acceptée et ne peut pas être annulée.');
        }

        $invitation->update(['expires_at' => now()]);

        return back()->with('success', 'L\'invitation a été annulée avec succès.');
    }

    /**
     * Renvoyer une invitation
     */
    public function resendInvitation($invitationId)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            abort(403, 'Vous devez appartenir à une entreprise pour renvoyer des invitations.');
        }

        $invitation = CompanyInvitation::where('id', $invitationId)
            ->where('company_id', $company->id)
            ->first();

        if (!$invitation) {
            abort(404, 'Invitation non trouvée.');
        }

        if ($invitation->isAccepted()) {
            return back()->with('error', 'Cette invitation a déjà été acceptée.');
        }

        try {
            // Prolonger l'expiration de 7 jours
            $invitation->update([
                'expires_at' => now()->addDays(7),
                'token' => \Illuminate\Support\Str::random(40), // Nouveau token pour sécurité
            ]);

            // Renvoyer l'email
            $this->sendInvitationEmail($invitation);

            return back()->with('success', 'L\'invitation a été renvoyée avec succès.');

        } catch (\Exception $e) {
            \Log::error('Erreur lors du renvoi de l\'invitation: ' . $e->getMessage());
            
            return back()->with('error', 'Une erreur est survenue lors du renvoi de l\'invitation. Veuillez réessayer.');
        }
    }

    /**
     * Envoyer l'email d'invitation
     */
    private function sendInvitationEmail(CompanyInvitation $invitation)
    {
        try {
            Mail::send('emails.company-invitation', [
                'invitation' => $invitation,
                'company' => $invitation->company,
                'invitedBy' => $invitation->invitedBy,
                'acceptUrl' => route('invitations.accept', $invitation->token)
            ], function ($message) use ($invitation) {
                $message->to($invitation->email)
                    ->subject('Invitation à rejoindre ' . $invitation->company->name . ' sur Planify');
            });

            \Log::info('Invitation email envoyée', [
                'email' => $invitation->email,
                'company' => $invitation->company->name,
                'url' => route('invitations.accept', $invitation->token),
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email invitation: ' . $e->getMessage());
            throw $e;
        }
    }
}
