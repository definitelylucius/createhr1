<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\HiringDecision;
use App\Models\OnboardingTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HiringDecisionController extends Controller
{
   

    public function index()
    {
        $decisions = HiringDecision::with(['candidate', 'approver'])->get();
        $candidatesReady = Candidate::where('status', 'final_interview_completed')
            ->whereDoesntHave('hiringDecision')
            ->with(['job', 'finalInterview'])
            ->get();
        
        return view('admin.hiring-decisions.index', compact('decisions', 'candidatesReady'));
    }

    public function readyForHire()
    {
        $candidates = Candidate::where('status', 'final_interview_completed')
            ->whereDoesntHave('hiringDecision')
            ->with(['job', 'finalInterview'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.hiring-decisions.ready', compact('candidates'));
    }

    public function create(Candidate $candidate)
    {
        // Only basic validation (no permissions)
        if ($candidate->status !== 'final_interview_completed') {
            return redirect()->back()->with('error', 'Candidate must complete final interview first');
        }
    
        if ($candidate->hiringDecision) {
            return redirect()->back()->with('error', 'A hiring decision already exists for this candidate');
        }
    
        return view('admin.hiring-decisions.create', [
            'candidate' => $candidate,
            'interview' => $candidate->finalInterview
        ]);
    }

    public function store(Request $request, Candidate $candidate)
    {

        
        $validated = $request->validate([
            'hire_date' => 'required|date',
            'salary' => 'required|numeric',
            'position' => 'required|string',
            'department' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        // Create hiring decision
        $decision = HiringDecision::create([
            'candidate_id' => $candidate->id,
            'approved_by' => auth()->id(), // Still track who approved
            'hire_date' => $validated['hire_date'],
            'salary' => $validated['salary'],
            'position' => $validated['position'],
            'department' => $validated['department'],
            'notes' => $validated['notes']
        ]);

    }

    public function show(HiringDecision $hiringDecision)
{
    return view('admin.hiring-decisions.show', compact('hiringDecision'));
}

    protected function assignOnboardingTasks(User $employee)
    {
        $tasks = OnboardingTask::all();
        
        foreach ($tasks as $task) {
            $dueDate = $employee->hire_date->addDays($task->days_before_due);
            
            $employee->onboardingTasks()->attach($task->id, [
                'due_date' => $dueDate,
                'status' => 'pending'
            ]);
        }
    }
}