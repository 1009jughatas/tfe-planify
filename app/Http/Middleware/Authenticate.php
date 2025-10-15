<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (!Auth::guard($guards[0] ?? null)->check()) {
            // Déterminer la route de redirection selon le type de requête
            if ($request->is('entreprise/*')) {
                return redirect()->route('entreprise.login');
            } else {
                return redirect()->route('login.indep');
            }
        }

        return $next($request);
    }
}
