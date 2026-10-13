<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Process authentication request.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            AuditLogger::log('user.login', 'User', $user->id, [
                'email' => $user->email,
                'role' => $user->role,
            ]);

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->isOrganizer()) {
                return redirect()->intended(route('organizer.dashboard'));
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form.
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Process user registration.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:participant,organizer'],
            'phone' => ['nullable', 'string', 'max:20'],
            'organization_name' => ['required_if:role,organizer', 'nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ]);

        if ($user->isOrganizer()) {
            Organization::create([
                'user_id' => $user->id,
                'name' => $validated['organization_name'] ?? ($user->name.' Organization'),
                'description' => 'Organisasi resmi terdaftar di EventHub.',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        AuditLogger::log('user.registered', 'User', $user->id, [
            'email' => $user->email,
            'role' => $user->role,
        ]);

        if ($user->isOrganizer()) {
            return redirect()->route('organizer.dashboard')->with('success', 'Selamat datang! Akun Organizer Anda telah aktif.');
        }

        return redirect()->route('home')->with('success', 'Pendaftaran akun berhasil! Selamat menjelajah event kampus.');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        $userId = Auth::id();
        if ($userId) {
            AuditLogger::log('user.logout', 'User', $userId);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Anda telah berhasil keluar.');
    }
}
