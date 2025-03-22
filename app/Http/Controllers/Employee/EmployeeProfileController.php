<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobApplication;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class EmployeeProfileController extends Controller
{
    // Show the profile
   
    public function showProfile()
    {
        $user = Auth::user();
    
        if (!$user) {
            abort(403, 'Unauthorized access');
        }
    
        // Fetch employee details
        $employee = $user->employee;
    
        if (!$employee) {
            $employee = new Employee([
                'user_id' => $user->id,
                'address' => '',
                'soft_skills' => '',
                'hard_skills' => ''
            ]);
        }
    
        // Fetch latest job application with job relation
        $jobApplication = JobApplication::where('user_id', $user->id)
            ->with('job') // Load job details
            ->latest()
            ->first();
    
        // Check if jobApplication exists before accessing properties
        $job = $jobApplication ? $jobApplication->job : null;
        $resume = $jobApplication && $jobApplication->resume
            ? asset('storage/' . $jobApplication->resume)
            : null;
    
        // Debugging: Check if resume is retrieved
      
    
        return view('employee.profile', compact('user', 'employee', 'job', 'jobApplication', 'resume'));
    }
    

    
    
    
    

    
 
    public function update(Request $request, $id)
{
    

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'phone' => 'nullable|string|max:15',
        'address' => 'nullable|string',
        'soft_skills' => 'nullable|string',
        'hard_skills' => 'nullable|string',
    ]);

    $user = User::findOrFail($id);

   

    // Update user information
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->save();

    // Update employee information
    $employee = $user->employee; 
 

    $employee->phone = $request->input('phone');
    $employee->address = $request->input('address');
    $employee->soft_skills = $request->input('soft_skills', 'N/A'); // Default value if NULL
    $employee->hard_skills = $request->input('hard_skills', 'N/A');
    $employee->save();

 

    return redirect()->route('employee.profile.show', $id)->with('success', 'Profile updated successfully');
}

}

