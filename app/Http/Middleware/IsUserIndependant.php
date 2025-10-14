<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsUserIndependant
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
            return redirect()->route('login.indep');
        }

        $user = auth()->user();
        
        // Vérifier que l'utilisateur est indépendant
        if ($user->company_id || $user->role !== 'user_independant') {
            abort(403, 'Accès réservé aux utilisateurs indépendants.');
        }

        return $next($request);
    }
}
