<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\JobApplication;
use App\Mail\ApplicationRejected;
use App\Mail\AdminReviewNotification;
use App\Mail\InterviewScheduled;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterviewInvitation;
use App\Mail\RejectionEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class ApplicationController extends Controller
{
        
    public function store(Request $request, $jobId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to apply.');
        }
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'resume' => 'required|file|mimes:pdf|max:2048',
        ]);
    
        // Get file
        $file = $request->file('resume');
        $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
    
        // Store locally in "storage/app/public/resumes"
        $filePath = $file->storeAs('public/resumes', $fileName);
    
        // Get public URL
        $fileUrl = Storage::url($filePath);
    
        // Store application in the database
        JobApplication::create([
            'user_id' => Auth::id(),
            'job_id' => $jobId,
            'name' => $request->name,
            'email' => $request->email,
            'resume' => $fileUrl,
            'application_status' => 'pending_review',
            'status' => 'new_application',
        ]);
    
        return redirect()->back()->with('success', 'Your application has been submitted.');
    }
    


    public function submitApplication(Request $request, $jobId)
    {
        if (!Auth::check()) {
            return redirect()->route('register')->with('error', 'You must be registered and logged in to apply.');
        }
    
        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);
    
        if (JobApplication::where('user_id', Auth::id())->where('job_id', $jobId)->exists()) {
            return redirect()->back()->with('error', 'You have already applied for this job.');
        }
    
        $file = $request->file('resume');
        $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
    
        // Store locally in "storage/app/public/resumes"
        $filePath = $file->storeAs('public/resumes', $fileName);
    
        // Get public URL
        $fileUrl = Storage::url($filePath);
    
        // Save application in the database
        JobApplication::create([
            'user_id' => Auth::id(),
            'job_id'  => $jobId,
            'name'    => Auth::user()->name,
            'email'   => Auth::user()->email,
            'resume'  => $fileUrl,
            'status'  => 'Pending',
        ]);
    
        return redirect()->route('/')->with('success', 'Application submitted successfully!');
    }




    public function trackApplications()
    {
        $applications = Application::where('user_id', Auth::id())->get();
        return view('staff.recruitment.applicants.track', compact('applications'));
    }

   


    public function viewApplications()
    {
        $applications = Application::with('job')->get();
        return view('staff.recruitment.applicants.view', compact('applications'));
    }

    // ✅ Show Scan Page for Staff
    public function showScanPage()
    {
        $applications = Application::where('status', 'pending_review')->get();
        return view('staff.applicants.scan', compact('applications'));
    }

    // ✅ Staff Updates Applicant Status
    public function updateStatus(Request $request, $id)
{
    // Validate the incoming request
    $request->validate([
        'application_status' => 'required|in:for_admin_review,rejected',
    ]);
    
    $application = Application::findOrFail($id);
    $application->update([
        'application_status' => $request->application_status,
    ]);

    // Redirect back with success message or return JSON response
    return redirect()->back()->with('status', 'Application status updated successfully.');
}


    // ✅ Admin Reviews Applicants
    public function viewAdminApplications()
    {
        $applications = Application::where('status', 'for_admin_review')->get();
        return view('admin.applicants.review', compact('applications'));
    }

    // ✅ Admin Schedules Interview
    public function scheduleInterview(Request $request, $id)
    {
        $request->validate([
            'interview_date' => 'required|date',
            'interview_time' => 'required',
        ]);

        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'interview_scheduled',
            'interview_date' => $request->interview_date,
            'interview_time' => $request->interview_time,
        ]);

        // Send Interview Invitation Email
        Mail::to($application->email)->send(new InterviewInvitation($application));

        return back()->with('success', 'Interview scheduled and invitation sent.');
    }

    // ✅ Show Interview Page for Staff
    public function showInterviewPage()
    {
        $applications = Application::where('status', 'interview_scheduled')->get();
        return view('staff.applicants.interview', compact('applications'));
    }

    // ✅ Staff Marks Interview as Completed
    public function completeInterview(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->update(['status' => 'interview_completed']);

        return back()->with('success', 'Interview marked as completed.');
    }

    // ✅ Show Finalize Page for Staff
    public function showFinalizePage()
    {
        $applications = Application::where('status', 'interview_completed')->get();
        return view('staff.applicants.finalize', compact('applications'));
    }

    // ✅ Staff Marks Applicant as Hired
    public function markHired(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->update(['status' => 'hired']);

        return back()->with('success', 'Applicant marked as hired.');
    }

    // ✅ Show Finalize Hiring Page for Admin
    public function showFinalizeHiringPage()
    {
        $applications = Application::where('status', 'hired')->get();
        return view('admin.applicants.finalize', compact('applications'));
    }

    // ✅ Admin Finalizes Hiring
    public function finalizeHiring(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->update(['status' => 'finalized']);

        return back()->with('success', 'Hiring finalized and applicant added to employees list.');
    }

    // ✅ Send Interview Invitation Email
    public function sendInterviewInvitation($id)
    {
        $application = Application::findOrFail($id);
        Mail::to($application->email)->send(new InterviewInvitation($application));

        return back()->with('success', 'Interview invitation email sent successfully.');
    }

    public function updateApplicationStatus(Request $request)
    {
        // Validate request
        $request->validate([
            'application_id' => 'required|exists:job_applications,id',
            'application_status' => 'required|string',
            'status' => 'nullable|string',
        ]);
    
        // Find the job application
        $application = JobApplication::findOrFail($request->application_id);
    
        if (!$application) {
            return back()->with('error', 'Application not found.');
        }
    
        // Only update fields if they are provided
        if ($request->has('application_status')) {
            $application->application_status = $request->application_status;
        }
    
        if ($request->has('status')) {
            $application->status = $request->status;
        }
    
        $application->save();
    
        return back()->with('success', 'Application status updated successfully.');
    }
    public function showResume($filename)
    {
        $path = storage_path("app/public/resumes/{$filename}");
    
        if (file_exists($path)) {
            return response()->file($path);
        }
    
        return abort(404);
    }
}