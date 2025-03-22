<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\User;
use App\Notifications\NewApplicantNotification;
use App\Models\Applicant;

class ApplicantController extends Controller
{
    public function showApplicationForm($jobId)
    {
        $job = Job::findOrFail($jobId); // Fetch job by ID
    
        return view('apply', compact('job')); // Pass job to view
    }

    public function store(Request $request)
    {
        $applicant = Applicant::create($request->all());
    
        // Find all staff users
        $staffUsers = User::where('role', 'staff')->get();
    
        // Notify all staff members
        foreach ($staffUsers as $staff) {
            $staff->notify(new NewApplicantNotification($applicant));
        }
    
        return redirect()->back()->with('success', 'Application submitted successfully!');
    }


}

