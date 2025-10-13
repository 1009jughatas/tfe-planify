<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfEntreprise
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
        if (Auth::check()) {
            $user = Auth::user();
            
            // Si l'utilisateur fait partie d'une entreprise
            if ($user->company_id) {
                // Rediriger vers le dashboard entreprise approprié
                if ($user->isCompanyAdmin()) {
                    return redirect()->route('company-admin.dashboard');
                } else {
                    // Utilisateur employé d'une entreprise
                    return redirect()->route('entreprise.dashboard');
                }
            }
        }

        return $next($request);
    }
}
