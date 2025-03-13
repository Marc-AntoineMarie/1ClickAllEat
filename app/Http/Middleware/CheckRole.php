<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        switch ($role) {
            case 'admin':
                if ($user->role !== User::ROLE_ADMIN) {
                    abort(403, 'Accès non autorisé. Vous devez être administrateur.');
                }
                break;
            case 'restaurateur':
                if ($user->role !== User::ROLE_RESTAURATEUR) {
                    abort(403, 'Accès non autorisé. Vous devez être restaurateur.');
                }
                break;
            case 'client':
                if ($user->role !== User::ROLE_CLIENT) {
                    abort(403, 'Accès non autorisé. Vous devez être client.');
                }
                break;
            default:
                abort(403, 'Rôle non valide.');
        }

        return $next($request);
    }
}
