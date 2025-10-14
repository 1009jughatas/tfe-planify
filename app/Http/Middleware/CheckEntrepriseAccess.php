<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckEntrepriseAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est connecté
        if (!$user) {
            return redirect()->route('entreprise.login');
        }

        // Vérifier que l'utilisateur fait partie d'une entreprise
        if (!$user->company_id) {
            Auth::logout();
            return redirect()->route('entreprise.login')
                ->with('error', 'Votre compte n\'est pas associé à une entreprise.');
        }

        // Vérifier que l'utilisateur a le bon rôle
        if (!$user->isAdminEntreprise() && !$user->isUserEntreprise()) {
            Auth::logout();
            return redirect()->route('entreprise.login')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à l\'espace entreprise.');
        }

        return $next($request);
    }
}
