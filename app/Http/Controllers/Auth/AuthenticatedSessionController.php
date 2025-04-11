<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Job;
use App\Notifications\SendTwoFactorCode;

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

    // Validate without logging in
    if (!Auth::validate($credentials)) {
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    $user = User::where('email', $credentials['email'])->first();

    if ($user->hasTwoFactorEnabled()) {
        $user->generateTwoFactorCode();
        $user->notify(new SendTwoFactorCode());
        
        // Set all required session variables
        $request->session()->put([
            '2fa_user_id' => $user->id,
            '2fa_remember' => $request->filled('remember'),
            '2fa_login_attempt' => true,
            '2fa_verified' => false // Explicitly false
        ]);

        // Partial logout - maintains our session
        Auth::guard('web')->logout();
        
        return redirect()->route('two-factor.challenge');
    }

    // Direct login for non-2FA users
    Auth::login($user, $request->filled('remember'));
    return $this->redirectToDashboard($user);
}

    public function showTwoFactorForm(Request $request)
    {
        if (!$request->session()->has('2fa_user_id')) {
            Log::warning('Unauthorized 2FA access attempt');
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);
    
        // Validate session state
        abort_unless(
            $request->session()->has(['2fa_user_id', '2fa_login_attempt']),
            403,
            'Invalid authentication state'
        );
    
        $user = User::findOrFail($request->session()->get('2fa_user_id'));
    
        if (!$user->verifyTwoFactorCode($request->code)) {
            return back()->withErrors(['code' => 'Invalid verification code']);
        }
    
        // Finalize authentication
        Auth::login($user, $request->session()->get('2fa_remember'));
        $request->session()->put('2fa_verified', true);
        $request->session()->forget(['2fa_login_attempt', '2fa_user_id']);
        $user->resetTwoFactorCode();
    
        return $this->redirectToDashboard($user);
    }
    protected function redirectToDashboard($user)
    {
        $route = match($user->role) {
            'superadmin' => 'superadmin.dashboard',
            'admin' => 'admin.dashboard',
            'staff' => 'staff.dashboard',
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
}