<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsIndependant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login.indep');
        }

        $user = auth()->user();
        
        if ($user->role !== 'user_independant' || $user->company_id) {
            // Rediriger vers le bon dashboard selon le rôle
            if ($user->company_id) {
                return redirect()->route('entreprise.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
