<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EntrepriseUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isAdminEntreprise');
    }

    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            abort(403, 'Aucune entreprise associée à votre compte.');
        }

        // Récupérer tous les utilisateurs de l'entreprise
        $users = User::where('company_id', $company->id)
            ->with(['projects'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Récupérer les invitations en attente
        $pendingInvitations = CompanyInvitation::where('company_id', $company->id)
            ->where('status', 'pending')
            ->with(['invitedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalUsers = $users->total();
        $maxUsers = $company->max_users ?? 10;

        return view('entreprise.utilisateurs.index', compact(
            'users', 
            'company', 
            'pendingInvitations', 
            'totalUsers', 
            'maxUsers'
        ));
    }

    public function createInvitation()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            abort(403, 'Aucune entreprise associée à votre compte.');
        }

        // Vérifier la limite d'utilisateurs
        $currentUsers = User::where('company_id', $company->id)->count();
        $maxUsers = $company->max_users ?? 10;

        if ($currentUsers >= $maxUsers) {
            return redirect()->route('entreprise.utilisateurs.index')
                ->with('error', 'Limite d\'utilisateurs atteinte. Veuillez mettre à niveau votre abonnement.');
        }

        return view('entreprise.utilisateurs.inviter', compact('company', 'currentUsers', 'maxUsers'));
    }

    public function sendInvitation(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            abort(403, 'Aucune entreprise associée à votre compte.');
        }

        $request->validate([
            'email' => 'required|email|max:255',
            'role' => 'required|in:user_entreprise,admin_entreprise'
        ]);

        // Vérifier la limite d'utilisateurs
        $currentUsers = User::where('company_id', $company->id)->count();
        $maxUsers = $company->max_users ?? 10;

        if ($currentUsers >= $maxUsers) {
            return redirect()->route('entreprise.utilisateurs.index')
                ->with('error', 'Limite d\'utilisateurs atteinte. Veuillez mettre à niveau votre abonnement.');
        }

        // Vérifier si l'email existe déjà
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            if ($existingUser->company_id === $company->id) {
                return back()->with('error', 'Cet utilisateur fait déjà partie de votre entreprise.');
            } else {
                return back()->with('error', 'Cet email est déjà utilisé par un autre compte.');
            }
        }

        // Vérifier si une invitation est déjà en attente
        $existingInvitation = CompanyInvitation::where('email', $request->email)
            ->where('company_id', $company->id)
            ->where('status', 'pending')
            ->first();

        if ($existingInvitation) {
            return back()->with('error', 'Une invitation est déjà en attente pour cet email.');
        }

        // Créer l'invitation
        $invitation = CompanyInvitation::createInvitation(
            $company->id,
            $request->email,
            $request->role,
            $user->id
        );

        // Mettre à jour les informations supplémentaires
        $invitation->update([
            'position' => 'Employé',
            'department' => 'Général',
        ]);

        // Envoyer l'email d'invitation
        try {
            Mail::send('emails.company-invitation', [
                'invitation' => $invitation,
                'company' => $company,
                'invitedBy' => $user,
                'acceptUrl' => route('invitations.accept', $invitation->token)
            ], function ($message) use ($request, $company) {
                $message->to($request->email)
                    ->subject('Invitation à rejoindre ' . $company->name . ' sur Planify');
            });

            return redirect()->route('entreprise.utilisateurs.index')
                ->with('success', 'Invitation envoyée avec succès à ' . $request->email);
        } catch (\Exception $e) {
            $invitation->delete();
            return back()->with('error', 'Erreur lors de l\'envoi de l\'invitation. Veuillez réessayer.');
        }
    }

    public function destroy(User $user)
    {
        $currentUser = Auth::user();
        $company = $currentUser->company;

        // Vérifier que l'utilisateur appartient à l'entreprise
        if ($user->company_id !== $company->id) {
            abort(403, 'Accès non autorisé à cet utilisateur.');
        }

        // Empêcher l'auto-suppression
        if ($user->id === $currentUser->id) {
            return back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }

        // Empêcher la suppression du dernier admin
        if ($user->role === 'admin_entreprise') {
            $adminCount = User::where('company_id', $company->id)
                ->where('role', 'admin_entreprise')
                ->count();
            
            if ($adminCount <= 1) {
                return back()->with('error', 'Vous ne pouvez pas supprimer le dernier administrateur de l\'entreprise.');
            }
        }

        // Supprimer l'utilisateur
        $user->delete();

        return redirect()->route('entreprise.utilisateurs.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function updateRole(Request $request, User $user)
    {
        $currentUser = Auth::user();
        $company = $currentUser->company;

        // Vérifier que l'utilisateur appartient à l'entreprise
        if ($user->company_id !== $company->id) {
            abort(403, 'Accès non autorisé à cet utilisateur.');
        }

        // Empêcher l'auto-modification de rôle
        if ($user->id === $currentUser->id) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        $request->validate([
            'role' => 'required|in:user_entreprise,admin_entreprise'
        ]);

        // Empêcher la suppression du dernier admin
        if ($user->role === 'admin_entreprise' && $request->role === 'user_entreprise') {
            $adminCount = User::where('company_id', $company->id)
                ->where('role', 'admin_entreprise')
                ->count();
            
            if ($adminCount <= 1) {
                return back()->with('error', 'Vous ne pouvez pas supprimer le dernier administrateur de l\'entreprise.');
            }
        }

        $user->update(['role' => $request->role]);

        return redirect()->route('entreprise.utilisateurs.index')
            ->with('success', 'Rôle de l\'utilisateur mis à jour avec succès.');
    }

    public function cancelInvitation(CompanyInvitation $invitation)
    {
        $user = Auth::user();
        $company = $user->company;

        // Vérifier que l'invitation appartient à l'entreprise
        if ($invitation->company_id !== $company->id) {
            abort(403, 'Accès non autorisé à cette invitation.');
        }

        $invitation->update(['status' => 'cancelled']);

        return redirect()->route('entreprise.utilisateurs.index')
            ->with('success', 'Invitation annulée avec succès.');
    }
}
