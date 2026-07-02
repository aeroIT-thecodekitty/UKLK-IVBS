<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the public login view (Students & Vendors).
     */
    public function create(): View
    {
        return view('auth.login');
    }


    
    /**
     * Handle an incoming authentication request for public users.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // 🛡️ BLOCK ADMINS from logging in through the student/vendor form
        if (Auth::user()->role === 'admin') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Administrators must use the dedicated Admin Portal sign-in.',
            ]);
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Display the dedicated admin login view.
     */
    public function createAdmin(): View
    {
        return view('auth.login-admin');
    }

    /**
     * Handle an incoming admin authentication request.
     */
    public function storeAdmin(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // 🛡️ ENFORCE: Only records with database role 'admin' pass this gate
        if (Auth::user()->role !== 'admin') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Access denied. This portal is strictly for system administrators.',
            ]);
        }

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