<?php


namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterviewInvitation;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Notifications\ApplicantHiredNotification;
use App\Notifications\InterviewApprovedNotification;
use App\Models\Employee;
use App\Notifications\ApplicantRecommendedNotification;



class RecruitmentController extends Controller
{
    public function trackApplications()
    {
        $applications = JobApplication::with(['user', 'job'])->get(); 
        $application = $applications->first(); 

        // Fetch application status counts
        $applicationStatusCounts = JobApplication::selectRaw('application_status, count(*) as total')
            ->groupBy('application_status')
            ->get()
            ->pluck('total', 'application_status')
            ->toArray();

        // Fetch interview outcome counts
        $statusCounts = JobApplication::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        // Ensure default values exist for both
        $applicationStatusCounts = array_merge([
            'pending_review' => 0,
            'for_admin_review' => 0,
            'rejected' => 0,
        ], $applicationStatusCounts);

        $statusCounts = array_merge([
            'new_application' => 0,
            'qualified' => 0,
            'scheduled' => 0,
            'interviewed' => 0,
            'hired' => 0,
            'rejected' => 0,
        ], $statusCounts);

        // Fetch applicants per day for the last 7 days
        $applicantsPerDay = JobApplication::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            $data[] = optional($applicantsPerDay->where('date', $date)->first())->total ?? 0;
        }

        return view('staff.recruitment.interview', compact('applications', 'application', 'applicationStatusCounts', 'statusCounts', 'labels', 'data'));
    }

    

    public function interview()
    {
        $applications = JobApplication::with(['user', 'job'])->get(); 
        return view('staff.recruitment.interview', compact('applications'));
    }

    public function feedback()
    {
        $applications = JobApplication::all();
        return view('staff.recruitment.feedback', compact('applications'));
    }

    public function documents()
    {
        return view('staff.recruitment.documents');
    }

   public function updateApplicationStatusByRequest(Request $request, $applicationId)
{
    Log::info('Incoming Data:', $request->all());

    // Validate request
    $request->validate([
        'application_status' => 'required|in:pending_review,for_admin_review,rejected',
    ]);
    // Fetch application
    $application = JobApplication::find($applicationId);

    // Check if application exists
    if (!$application) {
        return redirect()->back()->with('error', 'Application not found.');
    }

    // Update application status
    $application->application_status = $request->application_status;

    // Set reviewed_by if not already set
    if (is_null($application->reviewed_by)) {
        $application->reviewed_by = Auth::id();
    }

    $application->save();

    Log::info('Application status updated:', ['new_status' => $application->application_status]);

    return redirect()->back()->with('success', 'Application status updated successfully.');
}


    public function index()
    {
        $applications = JobApplication::all();
        return view('staff.recruitment.interview', compact('applications'));
    }

    public function sendInterviewEmail($id, Request $request)
    {
        // Using find() instead of findOrFail
        $application = JobApplication::find($id);
    
        // Check if the application exists
        if (!$application) {
            return back()->with('error', 'Application not found.');
        }
    
        $subject = $request->subject;
        $customMessage = $request->message;
    
        // Send the interview invitation email
        Mail::to($application->user->email)->send(new InterviewInvitation($application, $subject, $customMessage));
    
        return back()->with('success', 'Interview invitation sent successfully!');
    }
    

    public function showApplicants()
    {
        $applications = JobApplication::with('job', 'user')->get();
        return view('staff.recruitment.interview', compact('applications'));
    }

    public function storeInterviewResult(Request $request, $id)
{
    $application = JobApplication::findOrFail($id);

    // Update status based on interview outcome
    $application->update([
        'status' => $request->input('interview_status') === 'passed' ? 'interviewed' : 'failed',
    ]);

    return redirect()->back()->with('success', 'Interview status updated successfully!');
}


public function handleInterviewForm($id, Request $request)
{
    // Retrieve the JobApplication by its ID
    $application = JobApplication::find($id);

    // Ensure the application exists
    if (!$application) {
        return back()->with('error', 'Application not found.');
    }

    // Handle interview result (update application status, etc.)
    $application->interview_status = $request->input('interview_status'); // Example
    $application->save();

    // Redirect or return with a success message
    return back()->with('success', 'Interview result submitted successfully!');
}

public function updateInterviewOutcome(Request $request, $applicationId)
{
    $application = JobApplication::findOrFail($applicationId);

    // Validate the outcome status (either recommended_for_hiring or rejected)
    $validated = $request->validate([
        'outcome_status' => 'required|in:recommended_for_hiring,rejected',
    ]);

    // Check if the outcome is 'recommended_for_hiring'
    if ($validated['outcome_status'] == 'recommended_for_hiring') {
        $application->status = 'recommended_for_hiring';

        // Find an admin to notify
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new ApplicantRecommendedNotification($application));
            Log::info("Notification sent to admin for application ID: " . $application->id);
        } else {
            Log::error("No admin found to notify about the recommended applicant.");
        }
    } 
    // Check if the outcome is 'rejected'
    elseif ($validated['outcome_status'] == 'rejected') {
        $application->status = 'rejected';
    }

    // Save the updated application status
    $application->save();

    return redirect()->back()->with('status', 'Applicant outcome updated successfully!');
}



public function recommendApplicant($id)
{
    $application = JobApplication::findOrFail($id);

    // Update the job application status
    $application->update(['status' => 'recommended']);

    // Find the admin to notify
    $admin = User::where('role', 'admin')->first();

    if ($admin) {
        $admin->notify(new ApplicantRecommendedNotification($application));
    }

    return redirect()->back()->with('success', 'Applicant recommended for hiring.');
}


}


    
    
