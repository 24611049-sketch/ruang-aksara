<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek jika user belum login
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'data' => null
                ], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = $user->role;

        // Debug info untuk development
        if (config('app.debug')) {
            \Log::info('CheckRole Middleware', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $userRole,
                'required_roles' => $roles,
                'url' => $request->fullUrl()
            ]);
        }

        // Cek jika user memiliki salah satu role yang diizinkan
        if (!in_array($userRole, $roles)) {
            \Log::warning('Access denied for user', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $userRole,
                'required_roles' => $roles,
                'ip_address' => $request->ip()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses tidak diizinkan. Role yang diperlukan: ' . implode(', ', $roles),
                    'data' => null
                ], 403);
            }

            abort(403, 'Akses tidak diizinkan. Role yang diperlukan: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}