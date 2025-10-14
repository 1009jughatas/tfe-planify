<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsEntreprise
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
        if (!auth()->check()) {
            return redirect()->route('entreprise.login');
        }

        $user = auth()->user();
        
        // Vérifier que l'utilisateur fait partie d'une entreprise
        if (!$user->company_id) {
            abort(403, 'Accès réservé aux utilisateurs d\'entreprise.');
        }

        return $next($request);
    }
}
