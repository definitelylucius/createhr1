<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use App\Models\JobApplication;
use App\Models\Candidate;


class AdminController extends Controller
{
    // Show the Admin Dashboard
    public function dashboard()
{
    
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


 



    // Admin dashboard - list candidates pending approval
    public function candidates()
    {
        $candidates = Candidate::with(['tags', 'licenseVerification', 'tests'])
            ->where('status', 'pending_approval')
            ->latest()
            ->paginate(10);

        return view('admin.candidates.index', compact('candidates'));
    }

    // Show candidate details for approval
    public function reviewCandidate(Candidate $candidate)
    {
        $candidate->load(['tags', 'licenseVerification', 'tests', 'documents']);

        return view('admin.candidates.review', compact('candidate'));
    }

    // Approve candidate
    public function approveCandidate(Request $request, Candidate $candidate)
    {
        $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $candidate->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate approved successfully');
    }

    // Reject candidate
    public function rejectCandidate(Request $request, Candidate $candidate)
    {
        $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $candidate->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate rejected');
    }


}


