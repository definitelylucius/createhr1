<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\OnboardingTask;

class DashboardController extends Controller
{
    public function index()
    {
        $tasks = OnboardingTask::with('candidate')
            ->where('candidate_id', auth()->user()->employee->candidate_id)
            ->orderBy('due_date', 'asc')
            ->get();

        return view('employee.dashboard', compact('tasks'));
    }
}