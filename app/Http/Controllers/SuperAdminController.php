<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    // Dashboard View
    public function index()
    {
        // Fetching necessary data for the dashboard
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $staffCount = User::where('role', 'staff')->count();
        $employeeCount = User::where('role', 'employee')->count();
        $applicantCount = User::where('role', 'applicant')->count();

        // Fetch all users
        $users = User::all();

        // Passing data to the view
        return view('superadmin.dashboard', compact('totalUsers', 'adminCount', 'staffCount', 'employeeCount', 'applicantCount', 'users'));
    }

    // Create User Form
    public function create()
    {
        $users = User::all(); // Fetch all users
        return view('superadmin.create-user', compact('users'));
    }
    

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,staff,employee,applicant',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect()->route('superadmin.dashboard')->with('success', 'User created successfully.');
    }

    // Delete a user
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('superadmin.dashboard')->with('success', 'User deleted successfully.');
    }
}
