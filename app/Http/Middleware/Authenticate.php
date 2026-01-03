<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        // ✅ PERBAIKI INI: pastikan redirect ke route yang ada
        return $request->expectsJson() ? null : route('login');
    }
}