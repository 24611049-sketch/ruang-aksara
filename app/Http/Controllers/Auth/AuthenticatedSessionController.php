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

        // Redirect based on user role
        $user = Auth::user();
        
        // Set login notification untuk admin dan owner
        if ($user->role === 'owner') {
            return redirect()->intended(route('admin.dashboard', absolute: false))
                ->with('login_notification', [
                    'message' => 'Kamu login sebagai Owner',
                    'role' => $user->role,
                    'name' => $user->name
                ]);
        } elseif ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard', absolute: false))
                ->with('login_notification', [
                    'message' => 'Kamu login sebagai Admin',
                    'role' => $user->role,
                    'name' => $user->name
                ]);
        }
        
        return redirect()->intended(route('home', absolute: false));
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
