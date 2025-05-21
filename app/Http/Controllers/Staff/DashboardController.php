<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\OnboardingTask;

class DashboardController extends Controller
{
    public function index()
    {
        $assignedTasks = OnboardingTask::with('candidate')
            ->where('assigned_to', auth()->id())
            ->where('status', '!=', 'completed')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        $assignedCandidates = Candidate::whereHas('hiringProcessStages', function($query) {
                $query->where('conducted_by', auth()->id())
                    ->where('status', '!=', 'completed');
            })
            ->with(['currentStage', 'jobPosition'])
            ->limit(5)
            ->get();

        return view('staff.dashboard', compact('assignedTasks', 'assignedCandidates'));
    }
}