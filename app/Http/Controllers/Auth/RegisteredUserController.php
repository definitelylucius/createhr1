<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration form for applicants.
     */
    public function create()
    {
        return view('auth.register'); // Ensure this view exists for applicants
    }

    /**
     * Handle applicant registration.
     */
    public function store(Request $request)
{
    // Validate input
    $request->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    // Create applicant
    $user = User::create([
        'first_name' => $request->first_name,  // Save first name
        'last_name' => $request->last_name,    // Save last name
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'applicant', // Default role for applicants
    ]);

    event(new Registered($user));

    // Automatically log in the applicant
    Auth::login($user);

    return redirect()->route('login')->with('success', 'Registration successful');
}

    /**
     * Show the user creation form for super admin.
     */
    public function createUserForm()
    {
        return view('superadmin.create_user'); // Ensure this view exists for super admin
    }

    /**
     * Handle super admin creating users.
     */
    public function storeUser(Request $request)
    {
        // Ensure only super admins can create users
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Unauthorized action.');
        }

        // Validate input
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,staff,employee,applicant'], // Role selection
        ]);

        // Create the user with selected role
        User::create([
            'firstname' => $request->firstname,  // Save firstname
            'lastname' => $request->lastname,    // Save lastname
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Assign the chosen role
        ]);

        return redirect()->back()->with('success', 'User created successfully');
    }
}
