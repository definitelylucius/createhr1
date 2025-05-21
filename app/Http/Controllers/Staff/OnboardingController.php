<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\OnboardingTask;
use App\Models\VideoProgress;
use App\Models\OrientationVideo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\OnboardingProgress;
use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Log;

class OnboardingController extends Controller
{
    /**
     * Display onboarding for a specific application
     */
    public function show($id)
    {
        try {
            // Get only hired applications with required relationships
            $application = JobApplication::with(['job', 'user', 'onboarding'])
                ->where('status', 'hired')
                ->findOrFail($id);
    
            // Get or create onboarding record
            $onboarding = $application->onboarding ?? OnboardingProgress::create([
                'job_application_id' => $id,
                'status' => 'in_progress'
            ]);
    
            // Get staff members for supervisor dropdown
            $supervisors = User::where('role', 'staff')->get();
    
            return view('staff.onboarding.index', [
                'application' => $application,
                'onboarding' => $onboarding,
                'supervisors' => $supervisors
            ]);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Return view with empty data when application not found or not hired
            return view('staff.recruitment.onboarding', [
                'application' => null,
                'onboarding' => null,
                'supervisors' => collect()
            ])->with('error', 'Application not found or not in hired status');
        }
    }
    

    /**
     * Complete the onboarding process
     */
    public function complete(Request $request, $applicationId)
    {
        $request->validate([
            'first_day_instructions' => 'required|string',
            'start_date' => 'required|date',
            'work_location' => 'required|string',
            'supervisor_id' => 'required|exists:users,id'
        ]);

        $application = JobApplication::findOrFail($applicationId);
        $onboarding = $application->onboarding;
        
        if (!$onboarding->allDocumentsUploaded()) {
            return back()->with('error', 'All onboarding documents must be uploaded first');
        }

        // Update onboarding status
        $onboarding->update([
            'status' => 'completed',
            'first_day_instructions' => $request->first_day_instructions,
            'start_date' => $request->start_date,
            'work_location' => $request->work_location,
            'supervisor_id' => $request->supervisor_id
        ]);

        // Update application status
        $application->update(['status' => 'onboarding_completed']);

        return redirect()->route('staff.recruitment.index')
            ->with('success', 'Onboarding completed successfully!');
    }

    /**
     * Handle document uploads
     */
    public function uploadDocument(Request $request, $applicationId, $documentType)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip|max:2048'
        ]);

        $application = JobApplication::findOrFail($applicationId);
        $onboarding = $application->onboarding;
        
        $path = $request->file('file')->store("onboarding_documents/{$applicationId}", 'public');
        
        $onboarding->update([
            $documentType => $path
        ]);

        return back()->with('success', ucfirst(str_replace('_', ' ', $documentType)) . ' uploaded successfully!');
    }

    /**
     * Document collection view
     */
    public function documents()
    {
        $employees = Employee::with(['job', 'user'])
            ->whereHas('jobApplication', function($query) {
                $query->where('status', 'hired');
            })
            ->get();
    
        return view('staff.onboarding.documentcollection', compact('employees'));
    }

    /**
     * Assign tasks to employees
     */
    public function assignTask(Request $request)
    {
        $request->validate([
            'employees_id' => 'required|exists:employees,id',
            'tasks' => 'required|array|min:1',
            'tasks.*' => 'string',
        ]);

        $employee = Employee::findOrFail($request->employees_id);

        foreach ($request->tasks as $taskName) {
            OnboardingTask::create([
                'employees_id' => $employee->id,
                'task_name' => $taskName,
                'status' => 'Pending',
                'task_type' => 'documents',
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Tasks assigned successfully!']);
    }

    /**
     * Upload orientation video
     */
    public function uploadVideo(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video' => 'required|mimes:mp4,avi,mov,mkv|max:100000',
            'employee_id' => 'required|exists:employees,id',
        ]);

        $path = $request->file('video')->store('orientation_videos', 'public');

        OrientationVideo::create([
            'title' => $request->title,
            'video_path' => $path,
            'employee_id' => $request->employee_id,
            'uploaded_by' => Auth::user()->name,
            'progress' => 'not started',
        ]);

        return back()->with('success', 'Video uploaded successfully!');
    }

    /**
     * Show video upload form
     */
    public function showUploadForm()
    {
        $employees = Employee::all();
        return view('staff.upload_orientation_video', compact('employees'));
    }
    
    
    
    public function index()
    {
        // Get all applications that are ready for onboarding (status = hired)
        $applications = JobApplication::with(['job', 'user'])
            ->where('status', 'hired')
            ->paginate(10);
    
        return view('staff.recruitment.onboarding', compact('applications'));
    }
}