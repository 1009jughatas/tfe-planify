<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsEntreprise
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
        
        if (!$user->company_id) {
            // Rediriger vers le dashboard indépendant
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
