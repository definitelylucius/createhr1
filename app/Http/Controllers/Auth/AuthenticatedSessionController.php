<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Job;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;


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
    
        /**
         * Handle an authentication attempt.
         */
        public function store(Request $request)
        {
            // Validate login credentials
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            // Attempt to log the user in
            if (Auth::attempt($request->only('email', 'password'))) {
                $request->session()->regenerate();
                return $this->redirectToDashboard(Auth::user());
            }
    
            return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
        }
    
        /**
         * Redirect user to their respective dashboard.
         */
        protected function redirectToDashboard($user)
        {
            switch ($user->role) {
                case 'superadmin':
                    return redirect()->route('superadmin.dashboard');
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'staff':
                    return redirect()->route('staff.dashboard');
                case 'employee':
                    return redirect()->route('employee.dashboard');
                case 'applicant':
                    default:
                    $jobs = Job::all(); // Fetch all jobs from the database
                    return view('welcome', compact('jobs')); // Pass jobs to the welcome view
            }; // Show the welcome.blade.php view
}
    
        /**
         * Log the user out.
         */
        public function destroy(Request $request)
        {
            $guards = ['superadmin', 'admin', 'staff', 'employee', 'applicant', 'web'];
        
            foreach ($guards as $guard) {
                if (Auth::guard($guard)->check()) {
                    Auth::guard($guard)->logout();
                }
            }
        
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        
            return redirect('/'); // Redirect to homepage or login page
        }

      
    }