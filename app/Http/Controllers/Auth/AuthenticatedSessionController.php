<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        if ($user->role && $user->role->name === 'restaurateur') {
            return Redirect::intended(Route::getRoutes()->hasNamedRoute('restaurateur.dashboard') ? 
                route('restaurateur.dashboard') : '/restaurateur/dashboard');
        }
        if ($user->role && $user->role->name === 'employee') {
            return Redirect::intended(Route::getRoutes()->hasNamedRoute('employee.dashboard') ? 
                route('employee.dashboard') : '/employee/dashboard');
        }

        return Redirect::intended('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
