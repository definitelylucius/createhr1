<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Job;
use App\Notifications\SendTwoFactorCode;
use PragmaRX\Google2FA\Google2FA;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (!Auth::validate($credentials)) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'The email or password you entered is incorrect. Please try again.',
                ]);
        }
    
        $user = User::where('email', $credentials['email'])->first();
    
        // Always require 2FA (remove the hasTwoFactorEnabled check)
        $user->generateTwoFactorCode();
        $user->notify(new SendTwoFactorCode());
        
        $request->session()->put([
            '2fa_user_id' => $user->id,
            '2fa_remember' => $request->filled('remember'),
            '2fa_login_attempt' => true,
        ]);
    
        return redirect()->route('two-factor.challenge');
    }

    
    public function showTwoFactorForm(Request $request)
    {
        Log::debug('Showing 2FA form', [
            'has_user_id' => $request->session()->has('2fa_user_id'),
            'has_login_attempt' => $request->session()->has('2fa_login_attempt'),
            'session_data' => $request->session()->all()
        ]);
    
        if (!$request->session()->has('2fa_user_id')) {
            Log::warning('Unauthorized 2FA access attempt');
            return redirect()->route('login')->withErrors(['error' => 'Please login first']);
        }
    
        return view('auth.two-factor-challenge');
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);
        
        if (!$request->session()->has('2fa_user_id')) {
            return redirect()->route('login')->withErrors(['error' => 'Session expired']);
        }
    
        $user = User::find($request->session()->get('2fa_user_id'));
    
        if (!$user || !$user->verifyTwoFactorCode($request->code)) {
            return back()->withErrors(['code' => 'Invalid verification code']);
        }
    
        Auth::login($user, $request->session()->pull('2fa_remember', false));
        
        $request->session()->forget([
            '2fa_user_id',
            '2fa_login_attempt'
        ]);
    
        $user->resetTwoFactorCode();
    
        return $this->redirectToDashboard($user);
    }
    // Redirect based on user role
    protected function redirectToDashboard($user)
    {
        $route = match($user->role) {
            'superadmin' => 'superadmin.dashboard',
            'admin' => 'admin.dashboard',
            'staff' => 'staff.recruitment.dashboard',
            'employee' => 'employee.dashboard',
            default => null
        };

        if ($route) {
            return redirect()->route($route);
        }

        $jobs = Job::all();
        return view('welcome', compact('jobs'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function enableTwoFactor(User $user)
{
    $user->forceFill([
        'two_factor_secret' => encrypt(app(Google2FA::class)->generateSecretKey()),
    ])->save();
    
    return back()->with('status', 'Two-factor authentication enabled');
}

    
}