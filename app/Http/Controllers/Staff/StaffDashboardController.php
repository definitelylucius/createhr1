<?php

namespace App\Http\Controllers\Staff;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\Job;

class StaffDashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        $user = Auth::user(); // Get authenticated user

        // Count applications by `application_status`
        $applicationStatusCounts = JobApplication::whereHas('user', function ($query) {
            $query->where('role', 'applicant');
        })
        ->selectRaw('application_status, count(*) as total')
        ->groupBy('application_status')
        ->pluck('total', 'application_status')
        ->toArray();

        // Ensure all `application_status` values exist
        $applicationStatusCounts = array_merge([
            'new_application'   => 0,
            'pending_review'    => 0,
            'for_admin_review'  => 0,
            'rejected'          => 0,
        ], $applicationStatusCounts);

        // Count applications by `status`
        $statusCounts = JobApplication::whereHas('user', function ($query) {
            $query->where('role', 'applicant');
        })
        ->selectRaw('status, count(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

        // Ensure all `status` values exist
        $statusCounts = array_merge([
            'Pending'          => 0,
            'qualified'        => 0,
            'interviewed'      => 0,
            'hired'            => 0,
        ], $statusCounts);

        // Fetch applications only from applicants
        $applications = JobApplication::whereHas('user', function ($query) {
            $query->where('role', 'applicant');
        })
        ->with(['user', 'job'])
        ->get();

        // Fetch applicants per day for the last 7 days
        $applicantsPerDay = JobApplication::whereHas('user', function ($query) {
            $query->where('role', 'applicant');
        })
        ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

        // Prepare labels and data for the chart
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::now()->subDays($i)->format('M d');
            $data[] = optional($applicantsPerDay->where('date', $date)->first())->total ?? 0;
        }

        return view('staff.dashboard', compact(
            'user',
            'applications',
            'applicationStatusCounts',
            'statusCounts',
            'labels',
            'data'
        ));
    }

    public function submitApplication(Request $request, $jobId)
    {
        // Validate input fields
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Ensure the user is logged in
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'You must be logged in to apply.');
        }

        // Store resume with a unique name
        $resumePath = $request->file('resume')->storeAs(
            'resumes', time() . '_' . $request->file('resume')->getClientOriginalName(), 'public'
        );

        // Create the job application
        JobApplication::create([
            'user_id' => Auth::id(),
            'job_id' => $jobId,
            'email' => $request->email,
            'name' => $request->name,
            'resume' => $resumePath,
            'status' => 'Pending',  // ✅ Fixed default status
            'application_status' => 'new_application',  // ✅ Fixed default application_status
        ]);

        return redirect()->route('staff.applicants.track')->with('success', 'Application submitted successfully!');
    }

    public function trackApplications()
    {
        // Count applications based on `application_status`
        $applicationStatusCounts = JobApplication::selectRaw('application_status, count(*) as total')
            ->groupBy('application_status')
            ->pluck('total', 'application_status')
            ->toArray();

        // Count applications based on `status`
        $statusCounts = JobApplication::whereHas('user', function ($query) {
            $query->where('role', 'applicant');
        })
        ->selectRaw('status, count(*) as total')  // ✅ Fixed to `status`
        ->groupBy('status')
        ->pluck('total', 'status')  // ✅ Fixed to `status`
        ->toArray();

        // Ensure all `application_status` values exist
        $applicationStatusCounts = array_merge([
            'new_application'   => 0,
            'pending_review'    => 0,
            'for_admin_review'  => 0,
            'rejected'          => 0,
        ], $applicationStatusCounts);

        // Ensure all `status` values exist
        $statusCounts = array_merge([
            'Pending'          => 0,
            'qualified'        => 0,
            'interviewed'      => 0,
            'hired'            => 0,
        ], $statusCounts);

        // Fetch jobs and applications
        $jobs = Job::all();
        $applications = JobApplication::with(['user', 'job'])->get();

        return view('staff.dashboard', compact('applications', 'applicationStatusCounts', 'statusCounts', 'jobs'));
    }
}
