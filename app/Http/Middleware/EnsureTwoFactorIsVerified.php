<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnsureTwoFactorIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        // Bypass for auth-related routes
        if ($request->routeIs('login', 'logout', 'two-factor*', 'password.*')) {
            return $next($request);
        }
    
        $user = $request->user();
        
        if ($user && $user->hasTwoFactorEnabled()) {
            // Strict verification - must have completed 2FA
            if (!session('2fa:verified')) {
                // If they have partial auth (password passed but not 2FA)
                if (session('2fa:auth_passed')) {
                    return redirect()->route('two-factor.challenge');
                }
                
                // Otherwise force full re-authentication
                auth()->logout();
                return redirect()->route('login')->withErrors([
                    '2fa' => 'Two-factor authentication required'
                ]);
            }
        }
    
        return $next($request);
    }
}