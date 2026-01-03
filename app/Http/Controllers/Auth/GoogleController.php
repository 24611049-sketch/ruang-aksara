<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class GoogleController extends Controller
{
    /**
     * Quick login - redirect directly to Google
     */
    public function quickLogin()
    {
        return $this->redirectToGoogle();
    }

    /**
     * Redirect to Google OAuth page
     */
    public function redirectToGoogle()
    {
        try {
            // Force account selection prompt
            /** @var \Laravel\Socialite\Two\AbstractProvider $provider */
            $provider = Socialite::driver('google');
            return $provider->with(['prompt' => 'select_account'])->redirect();
        } catch (\Exception $e) {
            \Log::error('Google redirect error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect('/')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            /** @var \Laravel\Socialite\Two\User $googleUser */
            $googleUser = Socialite::driver('google')->user();

            // Check schema safety: detect optional OAuth columns
            $hasGoogleId = Schema::hasColumn('users', 'google_id');
            $hasGoogleToken = Schema::hasColumn('users', 'google_token');
            $hasGoogleRefresh = Schema::hasColumn('users', 'google_refresh_token');
            $hasAvatar = Schema::hasColumn('users', 'avatar');

            // Find existing user
            $query = User::query();
            if ($hasGoogleId) {
                $query->where('google_id', $googleUser->id);
            }
            $query->orWhere('email', $googleUser->email);
            $user = $query->first();

            if ($user) {
                // User exists - update Google credentials
                $update = [];
                if ($hasGoogleToken) {
                    $update['google_token'] = $googleUser->token;
                }
                if ($hasGoogleRefresh) {
                    $update['google_refresh_token'] = $googleUser->refreshToken ?? null;
                }
                if ($hasAvatar) {
                    $update['avatar'] = $googleUser->avatar ?? $user->avatar;
                }
                if ($hasGoogleId) {
                    $update['google_id'] = $googleUser->id;
                }
                $user->update($update);

                // Store user info in session for confirmation page
                session([
                    'google_login_pending' => true,
                    'pending_user_id' => $user->id,
                    'pending_user_email' => $user->email,
                    'pending_user_name' => $user->name,
                    'pending_user_role' => $user->role,
                    'pending_user_avatar' => $googleUser->avatar ?? null,
                ]);

                return redirect()->route('google.confirm.page');
            } else {
                // User doesn't exist - DO NOT create user yet.
                // Save Google data in session and redirect to confirmation page
                session([
                    'google_register_pending' => true,
                    'pending_user_email' => $googleUser->email,
                    'pending_user_name' => $googleUser->name,
                    'pending_user_role' => 'user',
                    'pending_user_avatar' => $googleUser->avatar ?? null,
                    'pending_google_token' => $googleUser->token ?? null,
                    'pending_google_refresh_token' => $googleUser->refreshToken ?? null,
                    'pending_google_id' => $googleUser->id ?? null,
                ]);

                return redirect()->route('google.confirm.page');
            }

        } catch (\Exception $e) {
            \Log::error('Google OAuth error: ' . $e->getMessage());
            
            return redirect('/')
                ->with('error', 'Error Google OAuth: ' . $e->getMessage());
        }
    }
}

