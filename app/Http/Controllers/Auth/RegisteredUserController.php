<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    // ==========================================
    // TRACK 1: YOUR ORIGINAL PUBLIC REGISTRATION
    // ==========================================
    
    public function create(): View
    {
        // This still loads your original student/vendor dropdown view
        return view('auth.register'); 
    }

    public function store(Request $request): RedirectResponse
    {
        // This processes your student and vendor users
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            // FIXED: Switched 'vendor' to 'vendor_user' to match your system's dashboard router matrix
            'role' => ['required', 'string', 'in:student,vendor_user'], 
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role, 
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    // ==========================================
    // TRACK 2: NEW HIDDEN ADMIN REGISTRATION
    // ==========================================

    public function createAdmin(): View
    {
        // This loads the new, separate admin form
        return view('auth.register-admin');
    }

    public function storeAdmin(Request $request): RedirectResponse
    {
        // This processes the admin user only if they know the secret key
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'admin_secret_key' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->admin_secret_key !== 'SuperSecretAdminKey123') {
            return back()->withErrors(['admin_secret_key' => 'Invalid admin key.'])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'admin', // Hardcoded safely
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}