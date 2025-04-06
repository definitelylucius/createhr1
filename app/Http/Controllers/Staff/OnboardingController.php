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



use Illuminate\Support\Facades\Log;

class OnboardingController extends Controller
{
    /**
     * Display a list of employees who need to submit documents.
     */
    public function documents()
    {
        // Eager load the 'job' relationship (which includes the department)
        $employees = Employee::with('job')->get();
    
        return view('staff.onboarding.documentcollection', compact('employees'));
    }
    

    public function documentCollectionView()
    {
        $employees = User::where('role', 'employee')->get();
        return view('staff.onboarding.documentcollection', compact('employees'));
    }

    public function assignTask(Request $request)
{
    \Log::info('🟢 Received Task Assignment Data:', $request->all());

    $request->validate([
        'employees_id' => 'required|exists:employees,id',
        'tasks' => 'required|array|min:1',
        'tasks.*' => 'string',
    ]);

    $employeeId = $request->employees_id;

    // Debugging: Log Employee ID
    \Log::info("🔍 Searching for Employee with ID: " . $employeeId);

    $employee = Employee::find($employeeId);

    if (!$employee) {
        \Log::error("❌ Employee not found with ID: " . $employeeId);
        return response()->json(['error' => 'Employee not found'], 404);
    }

    // Assign tasks
    foreach ($request->tasks as $taskName) {
        \Log::info("📝 Assigning Task: Employee ID = {$employeeId}, Task Name = {$taskName}");

        $task = OnboardingTask::create([
            'employees_id' => $employeeId,
            'task_name' => $taskName,
            'status' => 'Pending',
            'task_type' => 'documents',
        ]);

        \Log::info("✅ Task Created:", $task->toArray());
    }

    return response()->json(['success' => true, 'message' => 'Tasks assigned successfully!'], 200);
}

    
    
    
    


    
   public function upload(Request $request)
{
    Log::info('📥 Upload request received.', $request->all());

    // ✅ Validate first
    $request->validate([
        'title' => 'required|string|max:255',
        'video' => 'required|mimes:mp4,avi,mov,mkv|max:100000',
        'employee_id' => 'required|exists:employees,id',
    ]);

    // ✅ Find the employee AFTER validation
    $employee = Employee::find($request->employee_id);
    if (!$employee) {
        Log::warning("⚠️ Employee not found: " . $request->employee_id);
        return back()->with('error', 'Employee not found.');
    }

    if ($request->hasFile('video') && $request->file('video')->isValid()) {
        try {
            $path = $request->file('video')->store('orientation_videos', 'public');

            Log::info('📂 Video stored at: ' . $path);

            OrientationVideo::create([
                'title' => $request->input('title'),
                'video_path' => $path,
                'employee_id' => $request->input('employee_id'),
                'uploaded_by' => Auth::user()->name,
                'progress' => 'not started',
            ]);

            Log::info('✅ Orientation video record created successfully.');

            return back()->with('success', 'Video uploaded successfully!');
        } catch (\Exception $e) {
            Log::error('❌ Video upload error: ' . $e->getMessage());
            return back()->with('error', 'Error uploading the video.');
        }
    }

    return back()->with('error', 'Invalid video file.');
}
public function showUploadForm()
{
    $employees = Employee::all();

    if ($employees->isEmpty()) {
        Log::warning('No employees found in the database.');
    } else {
        Log::info('Employees retrieved successfully.', ['count' => $employees->count()]);
    }

    return view('staff.upload_orientation_video', compact('employees'));
}


public function index()
{ $userId = Auth::id();
    // Fetch employees who are in onboarding
     $employee = Employee::where('user_id', $userId)->first();


    return view('staff.onboarding.index', compact('employees'));
}

}

    

