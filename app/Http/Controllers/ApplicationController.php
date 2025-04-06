<?php
namespace App\Http\Controllers;
use App\Services\LocalResumeParser;
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
use App\Models\Candidate;


class ApplicationController extends Controller
{
    
    
    public function store(Request $request, $jobId)
    {
        // Validate incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:candidates,email',
            'resume' => 'required|file|mimes:pdf,docx|max:5120',
        ]);
    
        try {
            $user = Auth::user();
            if (!$user) {
                return back()->with('error', 'You must be logged in to apply.');
            }
    
            $job = Job::findOrFail($jobId);
    
            // Handle file upload
            $file = $request->file('resume');
            $fileName = 'user_'.$user->id.'_'.time().'.'.$file->extension();
    
            try {
                $filePath = $file->storeAs(
                    "job_$jobId",
                    $fileName,
                    'resumes'
                );
    
                $absolutePath = Storage::disk('resumes')->path($filePath);
    
                if (!file_exists($absolutePath)) {
                    throw new \Exception("File storage verification failed at: " . $absolutePath);
                }
    
                if (filesize($absolutePath) === 0) {
                    throw new \Exception("Stored file is empty");
                }
    
            } catch (\Exception $e) {
                Log::error("File storage failed", [
                    'original_name' => $file->getClientOriginalName(),
                    'error' => $e->getMessage(),
                    'storage_root' => config('filesystems.disks.resumes.root')
                ]);
                return back()->with('error', 'Failed to store resume: ' . $e->getMessage());
            }
    
            // Resume parsing
            try {
                $parser = new LocalResumeParser();
                $parsedData = $parser->parse($absolutePath, $job->department) ?? $this->getDefaultParsedData();
            } catch (\Exception $e) {
                Log::error("Resume parsing failed: " . $e->getMessage());
                $parsedData = $this->getDefaultParsedData();
            }
    
            // Split full name into first and last
            $nameParts = explode(' ', trim($validated['name']), 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';
    
            // Store into candidates table
            $candidate = Candidate::create([
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'email'         => $validated['email'],
                'phone'         => $parsedData['phone'] ?? null,
                'status'        => ($parsedData['match_score'] ?? 0) < 50 ? 'rejected' : 'under_review',
                'staff_notes'   => json_encode([
                    'skills' => $parsedData['skills'] ?? [],
                    'match_score' => $parsedData['match_score'] ?? null,
                    'cdl_status' => $parsedData['cdl_mentioned'] ?? false,
                    'parser_used' => $parsedData['parser_used'] ?? 'LocalResumeParser',
                    'resume' => $filePath,
                ]),
            ]);
    
            return redirect()->route('application.success', $candidate->id)
                   ->with('success', 'Application submitted successfully!');
            
        } catch (\Exception $e) {
            if (isset($filePath) && Storage::disk('resumes')->exists($filePath)) {
                Storage::disk('resumes')->delete($filePath);
            }
    
            Log::error("Application Error: " . $e->getMessage());
            return back()->with('error', 'Application processing failed. Please try again.');
        }
    }
    
    

// Helper methods
protected function extractCombinedSkills(array $parsedData): string
{
    return implode(', ', array_unique(array_merge(
        $parsedData['skills'] ?? [],
        $parsedData['ml_predictions']['skills'] ?? []
    )));
}

protected function determineCdlStatus(array $parsedData): bool
{
    return $parsedData['cdl_mentioned'] || 
          ($parsedData['ml_predictions']['cdl_mentioned'] ?? false);
}

protected function calculateEnhancedMatchScore(array $parsedData): int
{
    $baseScore = $parsedData['match_score'] ?? 0;
    $mlScore = $parsedData['ml_predictions']['department_match_score'] ?? 0;
    return (int) round(($baseScore * 0.4) + ($mlScore * 0.6));
}

    
    protected function getDefaultParsedData(): array
    {
        return [
            'skills' => [],
            'department_skills' => [],
            'cdl_mentioned' => false,
            'experience' => [],
            'certifications' => [],
            'match_score' => 0,
            'raw_text' => '',
            'parser_used' => 'LocalResumeParser',
            'ml_predictions' => [
                'ml_available' => false,
                'error' => 'Default fallback data'
            ],
            'success' => false
        ];
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