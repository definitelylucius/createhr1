<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnboardingTask;
use App\Models\Employee;
use App\Models\OrientationVideo;
use App\Models\EmployeeOnboardingProgress; // Updated model
use Illuminate\Support\Facades\Auth;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\OnboardingProgress;
use App\Models\OnboardingTaskStatus;
use App\Models\EmployeeOnboarding;
use App\Models\Document;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;


class EmployeeController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|min:6',
        ]);
    
        // Create the employee
        $employee = Employee::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'onboarding_status' => 'pending', // Set default onboarding status
        ]);
    
        // Assign onboarding tasks
        $tasks = [
            'Document Submission (ID, tax forms, contract)',
            'Company Policy Acknowledgment',
            'Account Setup (email, payroll)',
            'Training & Orientation Schedule',
            'Orientation Video'
        ];
    
        foreach ($tasks as $taskName) {
            $task = OnboardingTask::create([
                'employee_id' => $employee->id,
                'task_name' => $taskName,
                'status' => 'Not Started'
            ]);
    
            // Initialize progress tracking
            EmployeeOnboardingProgress::create([
                'employee_id' => $employee->id,
                'task_id' => $task->id,
                'is_completed' => false
            ]);
        }
    
        return redirect()->route('employee.onboarding.orientation', ['employeeId' => $employee->id])
            ->with('success', 'Employee added with onboarding tasks!');
    }

    public function onboarding()
    {
        $employee = auth()->user();

        // Fetch onboarding tasks
        $tasks = OnboardingTask::where('employee_id', $employee->id)->get();
        
        // Calculate completion rate
        $completionRate = $this->calculateCompletionRate($employee->id);

        // Fetch the latest orientation video
        $latestVideo = OrientationVideo::latest()->first();
        
        return view('employee.onboarding.index', compact('completionRate', 'employee', 'latestVideo', 'tasks'));
    }

    // Function to calculate completion rate
    private function calculateCompletionRate($userId)
    {
        $totalTasks = OnboardingTask::where('employee_id', $userId)->count();
        $completedTasks = EmployeeOnboardingProgress::where('employee_id', $userId)
            ->where('is_completed', true)
            ->count();
            
        return ($totalTasks > 0) ? ($completedTasks / $totalTasks) * 100 : 0;
    }


   
// Function to calculate completion rate


    public function onboardingDashboard()
    {
        $totalNewHires = Employee::count();
        $completedOnboarding = Employee::whereHas('onboardingProgress', function ($query) {
            $query->where('is_completed', true);
        })->count();

        $completionRate = ($totalNewHires > 0) ? ($completedOnboarding / $totalNewHires) * 100 : 0;
        $employee = auth()->user()->employee ?? null;

        return view('employee.onboarding.dashboard', compact('completionRate', 'employee'));
    }

    public function orientation($employeeId = null)
    {
        $employee = $employeeId ? Employee::findOrFail($employeeId) : auth()->user();

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee not found.');
        }

        $totalTasks = OnboardingTask::where('employee_id', $employee->id)->count();
        $completedTasks = EmployeeOnboardingProgress::where('employee_id', $employee->id)
            ->where('is_completed', true)
            ->count();

        $completionRate = ($totalTasks > 0) ? ($completedTasks / $totalTasks) * 100 : 0;
        $latestVideo = OrientationVideo::latest()->first();

        return view('employee.onboarding.orientation', compact('employee', 'completionRate', 'latestVideo'));
    }

    public function showOnboardingTasks($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $tasks = OnboardingTask::where('employee_id', $employeeId)->get();

        return view('employee.onboarding', compact('employee', 'tasks'));
    }

    // Mark onboarding task as completed
    public function completeTask(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'task_name' => 'required|string'
        ]);
    
        $taskName = $validated['task_name'];
    
        $updateFields = match ($taskName) {
            'orientation' => ['watched_orientation_video' => true],
            'documents' => ['submitted_documents' => true],
            'policies' => ['signed_policies' => true],
            'final' => ['completed_final_review' => true],
            default => null
        };
    
        if (!$updateFields) {
            return response()->json(['success' => false, 'message' => 'Invalid task name'], 400);
        }
    
        $onboarding = EmployeeOnboarding::updateOrCreate(
            ['employee_id' => $employeeId],
            array_merge($updateFields, [
                'progress_percentage' => $this->calculateProgress($employeeId),
            ])
        );
    
        return response()->json(['success' => true, 'message' => ucfirst(str_replace('_', ' ', $taskName)) . ' marked as completed!']);
    }
    

    public function uploadDocuments(Request $request)
{
    $employee = Employee::where('user_id', Auth::id())->first(); // Assuming there's a user_id in employees table

    if (!$employee) {
        return redirect()->back()->with('error', 'Employee record not found.');
    }

    $request->validate([
        'government_id' => 'required|file|mimes:pdf,jpg,png',
        'tax_forms' => 'required|file|mimes:pdf,jpg,png',
    ]);

    // Store files
    $governmentIdPath = $request->file('government_id')->store('documents/government_ids', 'public');
    $taxFormsPath = $request->file('tax_forms')->store('documents/tax_forms', 'public');

    // Store document details in the database
    Document::updateOrCreate(
        ['employee_id' => $employee->id], // Get the employee ID from relationship
        [
            'government_id_local_path' => $governmentIdPath,
            'tax_forms_local_path' => $taxFormsPath,
        ]
    );

    // Update onboarding progress
    EmployeeOnboarding::where('employee_id', $employee->id)->update(['submitted_documents' => true]);

    return redirect()->back()->with('success', 'Documents uploaded successfully.');
}

public function checkDocumentStatus()
{
    $employee = auth()->user(); // Get the logged-in employee

    // Check if the user has uploaded required documents
    $hasDocuments = !empty($employee->government_id) && !empty($employee->tax_forms);

    return response()->json(['submitted' => $hasDocuments]);
}

/**
 * Calculate onboarding progress based on completed tasks.
 */
private function calculateProgress($employeeId)
{
    $onboarding = EmployeeOnboarding::where('employee_id', $employeeId)->first();

    if (!$onboarding) {
        return 0;
    }

    $tasks = [
        'watched_orientation_video',
        'submitted_documents',
        'signed_policies',
        'completed_final_review',
    ];

    $completedTasks = collect($tasks)->filter(fn($task) => $onboarding->$task)->count();

    return ($completedTasks / count($tasks)) * 100; // Return percentage
}


public function getProgressAttribute()
{
    $tasks = [
        'watched_orientation_video',
        'submitted_documents',
        'signed_policies',
        'completed_final_review',
    ];

    $completedTasks = collect($tasks)->filter(fn($task) => $this->$task)->count();

    return ($completedTasks / count($tasks)) * 100;
}
    public function verifyOnboarding($id)
    {
        $onboarding = EmployeeOnboardingProgress::findOrFail($id);
        $onboarding->update(['is_completed' => true]);

        return back()->with('success', 'Onboarding Verified');
    }

    public function updateOnboardingProgress(Request $request)
{
    $user = auth()->user();
    $task = $request->task;

    $onboarding = EmployeeOnboarding::firstOrCreate(['user_id' => $user->id]);

    switch ($task) {
        case 'video':
            $onboarding->watched_orientation_video = true;
            break;
        case 'documents':
            $onboarding->submitted_documents = true;
            break;
        case 'policies':
            $onboarding->signed_policies = true;
            break;
        case 'final':
            $onboarding->completed_final_review = true;
            break;
    }

    // Calculate progress percentage
    $completedTasks = collect([
        $onboarding->watched_orientation_video,
        $onboarding->submitted_documents,
        $onboarding->signed_policies,
        $onboarding->completed_final_review
    ])->filter()->count();

    $onboarding->progress_percentage = ($completedTasks / 4) * 100;
    $onboarding->save();

    return response()->json(['success' => true, 'progress' => $onboarding->progress_percentage]);
}
 // Show onboarding page
 public function showOnboarding()
 {
     $user = Auth::user();

     // Get the latest onboarding video (if available)
     $latestVideo = OrientationVideo::latest()->first();

     // Define onboarding tasks
     $onboardingTasks = [
         ['type' => 'orientation', 'label' => 'Start Orientation', 'video_url' => $latestVideo ? asset('storage/' . $latestVideo->video_path) : ''],
         ['type' => 'documents', 'label' => 'Document Submission'],
         ['type' => 'policies', 'label' => 'Read & Accept Company Policies'],
         ['type' => 'final', 'label' => 'Final Onboarding Evaluation']
     ];

     // Fetch or create onboarding progress record for the user
     $onboarding = EmployeeOnboarding::firstOrCreate(
         ['user_id' => $user->id],
         [
             'watched_orientation_video' => false,
             'submitted_documents' => false,
             'signed_policies' => false,
             'completed_final_review' => false,
             'progress_percentage' => 0
         ]
     );

     return view('employee.onboarding', compact('onboardingTasks', 'latestVideo', 'onboarding'));
 }

 /**
  * Handle onboarding task completion.
  */
  public function updateProgress(Request $request)
  {
      $employee = Auth::user();
      
      // Find the employee's onboarding progress record
      $progress = EmployeeOnboarding::where('employee_id', $employee->id)
                  ->where('task', $request->task)
                  ->first();
  
      if ($progress) {
          $progress->update(['status' => 'Completed']);
          return response()->json(['success' => true, 'message' => 'Task updated successfully']);
      }
  
      return response()->json(['success' => false, 'message' => 'Task not found'], 404);
  }
  public function finalizeOnboarding(Request $request)
{
    try {
        // Log request data
        Log::info("Received onboarding data: ", $request->all());

        // Get the logged-in user
        $employeeId = Auth::id();
        $employee = Employee::where('user_id', $employeeId)->first();

        if (!$employee) {
           
            return response()->json(['success' => false, 'error' => 'Employee not found'], 404);
        }

        // Retrieve or create an onboarding record
        $onboarding = EmployeeOnboarding::updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'watched_orientation_video' => $request->watched_orientation_video ?? false,
                'submitted_documents' => $request->submitted_documents ?? false,
                'signed_policies' => $request->signed_policies ?? false,
                'completed_final_review' => true, // Ensure final review is marked complete
                'progress_percentage' => 100, // Mark progress as 100%
            ]
        );

       

        return response()->json(['success' => true, 'message' => 'Onboarding finalized successfully']);
    } catch (\Exception $e) {
      
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

public function checkOnboardingStatus()
{
    // Get the authenticated employee's ID
    $employeeId = Auth::id();

    // Retrieve the onboarding record for the employee
    $onboarding = EmployeeOnboarding::where('employee_id', $employeeId)->first();

    if (!$onboarding) {
        return response()->json(['error' => 'Onboarding record not found.'], 404);
    }

    // Check if any of the steps are incomplete
    if (!$onboarding->watched_orientation_video || 
        !$onboarding->submitted_documents || 
        !$onboarding->signed_policies || 
        !$onboarding->completed_final_review) {

        return response()->json(['message' => 'Employee has not completed all onboarding steps.'], 400);
    }

    // If all steps are complete
    return response()->json(['message' => 'All onboarding steps are completed.'], 200);
}


public function updateTask(Request $request)
{
    $task = $request->task;
    $employeeId = auth()->id(); // Adjust based on how you track employees

    // Find or create onboarding record
    $onboarding = Onboarding::updateOrCreate(
        ['employee_id' => $employeeId],
        [$task => true] // Mark task as completed
    );

    return response()->json(['success' => true, 'message' => 'Task updated successfully']);
}

public function showProfile($id)
{
    $employee = Employee::findOrFail($id);
    return view('employee.profile', compact('employee'));
}
}
  







