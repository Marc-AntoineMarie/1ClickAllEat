<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsEmployee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->role || Auth::user()->role->name !== 'employee') {
            if (Auth::check()) {
                return redirect('/'); // Rediriger vers l'accueil si l'utilisateur est connecté mais n'est pas un employé
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
