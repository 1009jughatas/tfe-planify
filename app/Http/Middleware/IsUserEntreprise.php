<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsUserEntreprise
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
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('entreprise.login');
        }
        
        // Vérifier que l'utilisateur appartient à une entreprise
        if (!$user->company_id || !in_array($user->role, ['admin_entreprise', 'user_entreprise'])) {
            abort(403, 'Accès réservé aux utilisateurs d\'entreprise.');
        }

        return $next($request);
    }
}
