<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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

        // 1. OJT Supervisor Redirect
        if (method_exists($user, 'hasRole') && $user->hasRole('ojt_supervisor')) {
            $supervisorRoute = Route::has('supervisor.dashboard') ? 'supervisor.dashboard' : 'dashboard';
            return redirect()->intended(route($supervisorRoute, absolute: false));
        }

        // 2. OJT Intern Redirect
        if (($user->is_ojt ?? false) || (method_exists($user, 'hasRole') && $user->hasRole('ojt'))) {
            $ojtRoute = Route::has('ojt.dashboard') 
                ? 'ojt.dashboard' 
                : (Route::has('ojt.index') ? 'ojt.index' : 'dashboard');

            return redirect()->intended(route($ojtRoute, absolute: false));
        }

        // 3. Default Redirect (Admin, IT Staff, Regular Users)
        return redirect()->intended(route('dashboard', absolute: false));
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