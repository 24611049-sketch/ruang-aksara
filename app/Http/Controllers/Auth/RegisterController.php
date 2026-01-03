<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'alamat' => ['required', 'string', 'max:500'],
        ];

        // Password hanya required jika bukan dari Google
        if (!isset($data['from_google']) || !$data['from_google']) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        // Check if user is registering via Google
        $isFromGoogle = isset($data['from_google']) && $data['from_google'];

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'alamat' => $data['alamat'] ?? null,
            'role' => 'user',
            'points' => 0,
            'email_verified_at' => now(), // Email already verified by Google
        ];

        if ($isFromGoogle) {
            // Google user - no password, store Google credentials
            $userData['password'] = null;
            $userData['google_id'] = $data['google_id'] ?? null;
            $userData['google_token'] = $data['google_token'] ?? null;
            $userData['google_refresh_token'] = $data['google_refresh_token'] ?? null;
            $userData['avatar'] = $data['avatar'] ?? null;
        } else {
            // Regular user - hash password
            $userData['password'] = Hash::make($data['password']);
        }

        return User::create($userData);
    }
}