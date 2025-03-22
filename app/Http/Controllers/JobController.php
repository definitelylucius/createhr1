<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;

class JobController extends Controller
{
    // Show job creation form
    public function create()
    {
        return view('admin.jobs.create');
    }

    // Store job in database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'type' => 'required|string',
            'location' => 'required|string',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'description' => 'required|string',
            'responsibilities' => 'required|string',
            'qualifications' => 'required|string',
            'experience_level' => 'required|string',
            'application_deadline' => 'required|date',
            'status' => 'required|string|in:Active,Draft',
        ]);

        Job::create([
            'title' => $request->title,
            'department' => $request->department,
            'type' => $request->type,
            'location' => $request->location,
            'min_salary' => $request->min_salary,
            'max_salary' => $request->max_salary,
            'description' => $request->description,
            'responsibilities' => $request->responsibilities,
            'qualifications' => $request->qualifications,
            'experience_level' => $request->experience_level,
            'application_deadline' => $request->application_deadline,
            'status' => $request->status,
            'posted_by' => Auth::id(), // Logged-in admin
        ]);

        return redirect()->route('admin.jobs.create')->with('success', 'Job posted successfully!');
    }

    // Fetch jobs for job posting page
    public function fetchJobs()
    {
        return response()->json(Job::where('status', 'Active')->get());
    }

    public function welcome()
    {
        $jobs = Job::where('status', 'Active')->get(); // Fetch only active jobs
        return view('welcome', compact('jobs')); // Pass $jobs to the view
    }
    

    

    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);
    
        $job->update([
            'title' => $request->input('title'),
            'department' => $request->input('department'),
            'type' => $request->input('type'),
            'location' => $request->input('location'),
            'experience_level' => $request->input('experience_level'),
            'application_deadline' => $request->input('application_deadline'),
            'status' => $request->input('status'),
            'min_salary' => $request->input('min_salary'),
            'max_salary' => $request->input('max_salary'),
            'description' => $request->input('description'),
            'responsibilities' => $request->input('responsibilities'),
            'qualifications' => $request->input('qualifications'),
        ]);
    
        return response()->json(['message' => 'Job updated successfully!']);
    }
    public function destroy($id)
{
    $job = Job::findOrFail($id);
    $job->delete();

    return response()->json(['message' => 'Job deleted successfully!']);
}

    public function edit($id)
    {
        $job = Job::findOrFail($id);
        return view('admin.jobs.manage', compact('job'));
    }

    public function manage()
{
    $jobs = Job::all(); // Fetch all jobs
    return view('admin.jobs.manage', compact('jobs')); 
}

public function show($id)
{
    $job = Job::findOrFail($id); // Retrieve job by ID

    return view('jobs.show', compact('job')); // Pass $job to the view
}

public function showApplicationForm($id) {
    $job = Job::findOrFail($id);
    return view('apply', compact('job'));
}

public function submitApplication(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'resume' => 'required|mimes:pdf,doc,docx|max:2048',
    ]);

    $resumePath = $request->file('resume')->store('resumes', 'public');

    Application::create([
        'job_id' => $id,
        'name' => $request->name,
        'email' => $request->email,
        'resume' => $resumePath,
    ]);

    return redirect('/')->with('success', 'Application submitted successfully!');


}



public function showWelcome()
{
    $jobs = Job::all(); // Fetch all jobs
    return view('welcome', compact('jobs'));
}



    }



