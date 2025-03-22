<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use App\Models\JobApplication;


class AdminController extends Controller
{
    // Show the Admin Dashboard
    public function dashboard()
{
    $admin = Auth::user(); // Get the logged-in admin
    $notifications = $admin->unreadNotifications; // Fetch unread notifications
    $applications = JobApplication::with(['user', 'job'])->get(); // Fetch applications with related user and job data

    $applicationStatusCounts = [
        'for_admin_review' => JobApplication::where('application_status', 'for_admin_review')->count(), // ✅ Updated key
        'new_application' => JobApplication::where('status', 'pending_review')->count(),
        'rejected' => JobApplication::where('status', 'rejected')->count(),
    ];

    $statusCounts = [
        'interview_scheduled' => JobApplication::where('status', 'interview_scheduled')->count(),
        'interviewed' => JobApplication::where('status', 'interviewed')->count(),
        'recommended_for_hiring' => JobApplication::where('status', 'recommended_for_hiring')->count(),
        'hired' => JobApplication::where('status', 'hired')->count(),
    ];

    return view('admin.dashboard', compact('applications', 'applicationStatusCounts', 'statusCounts','notifications'));
}

    

   

public function welcome()
{
    $jobs = Job::latest()->get(); // Fetch jobs from DB
    return view('welcome', compact('jobs'));
}


    // Show the Job Posting Form
    public function createJob()
    {
        return view('admin.jobs.create');
    }

    // Store a new job post
    public function storeJob(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'type' => 'required|string',
            'location' => 'required|string|max:255',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'description' => 'required|string',
            'responsibilities' => 'required|string',
            'qualifications' => 'required|string',
            'experience_level' => 'required|string',
            'application_deadline' => 'required|date',
            'status' => 'required|string'
        ]);

        // Convert new lines to <br> before storing
        $validated['description'] = nl2br(e($validated['description']));
        $validated['responsibilities'] = nl2br(e($validated['responsibilities']));
        $validated['qualifications'] = nl2br(e($validated['qualifications']));
    
        Job::create($validated);
    
        return redirect()->route('admin.dashboard')->with('success', 'Job posted successfully!');
    }

    // Fetch Jobs for Alpine.js
    public function fetchJobs()
    {
        return response()->json(Job::latest()->get());
    }


}