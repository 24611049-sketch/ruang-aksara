<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        '/cart/api/*',
        '/order/api/*',
        '/api/*',
    ];

    public function handle($request, Closure $next)
    {
        \Log::info('CSRF Token Middleware', [
            'session_token' => $request->session()->token(),
            'header_token' => $request->header('X-CSRF-TOKEN')
        ]);

        return parent::handle($request, $next);
    }
}