<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdminEntreprise
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

        // Vérifier que l'utilisateur est un admin entreprise
        if (!$user->isAdminEntreprise()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs d\'entreprise peuvent accéder à cette page.');
        }

        // Vérifier que l'utilisateur fait partie d'une entreprise
        if (!$user->company_id) {
            Auth::logout();
            return redirect()->route('entreprise.login')
                ->with('error', 'Votre compte n\'est pas associé à une entreprise.');
        }

        return $next($request);
    }
}
