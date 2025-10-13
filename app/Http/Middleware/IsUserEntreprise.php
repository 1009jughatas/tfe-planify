<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUserEntreprise
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('entreprise.login');
        }

        $user = auth()->user();
        
        if ($user->role !== 'user_entreprise' || !$user->company_id) {
            abort(403, 'Accès non autorisé. Seuls les employés d\'entreprise peuvent accéder à cette section.');
        }

        return $next($request);
    }
}