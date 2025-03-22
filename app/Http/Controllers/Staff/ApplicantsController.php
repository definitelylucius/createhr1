<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\Job;

class ApplicantsController extends Controller 

{
    public function trackApplications()
{
    $applications = JobApplication::selectRaw('application_status, count(*) as total')
        ->groupBy('application_status')
        ->get();
    
    // Debug output
 

    $applicationsCount = $applications->pluck('total', 'application_status')->toArray();

    // Default to 0 if no applications are found for a specific status
    $applicationsCount = array_merge([
        'new_application' => 0,
        'qualified' => 0,
        'scheduled' => 0,
        'interviewed' => 0,
        'hired' => 0,
    ], $applicationsCount);

    $jobs = Job::all();
    return view('staff.applicants.track', compact('applicationsCount', 'jobs'));
}




    public function view()
    {
        return view('staff.applicants.view');
    }
    public function submitApplication(Request $request, $jobId)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'resume' => 'required|mimes:pdf|max:2048',
    ]);

    // Check if user is authenticated
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'You must be logged in to apply.');
    }

    // Upload resume
    $filePath = $request->file('resume')->store('resumes', 'public');

    // Create job application
    JobApplication::create([
        'user_id' => Auth::id(),
        'job_id' => $jobId,
        'name' => $request->name,
        'email' => $request->email,
        'resume' => $filePath,
        'application_status' => 'new_application', // Change from 'Pending' to 'new_application'
    ]);
    


    // Redirect to the track page with success message
    return redirect('/')->with('success', 'Application submitted successfully!');


}

    
public function index()
{
     // Fetch applications
     $applications = JobApplication::with('job')->get();

     // Return the view with the data
     return view('staff.applicants.view', compact('applications'));
}



}
