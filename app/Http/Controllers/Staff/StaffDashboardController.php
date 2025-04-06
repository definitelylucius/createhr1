<?php

namespace App\Http\Controllers\Staff;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Candidate;
use App\Models\FinalInterview;



class StaffDashboardController extends Controller
{
    public function index()
    {
        return view('staff.dashboard', [
            'totalCandidates' => Candidate::count(),
            'newCandidatesThisWeek' => Candidate::where('created_at', '>=', now()->subWeek())->count(),
            'scheduledInterviews' => FinalInterview::count(),
            'interviewsToday' => FinalInterview::whereDate('scheduled_at', today())->count(),
            'pendingApproval' => Candidate::where('status', 'pending_approval')->count(),
            'overdueApprovals' => 0, // Add your logic
            'hiredCandidates' => Candidate::where('status', 'hired')->count(),
            'hiredThisMonth' => Candidate::where('status', 'hired')
                                      ->whereMonth('updated_at', now()->month)
                                      ->count(),
            'statusLabels' => ['New', 'Under Review', 'Test Scheduled', 'Approved', 'Rejected', 'Hired'],
            'statusData' => [
                Candidate::where('status', 'new')->count(),
                Candidate::where('status', 'under_review')->count(),
                Candidate::where('status', 'test_scheduled')->count(),
                Candidate::where('status', 'approved')->count(),
                Candidate::where('status', 'rejected')->count(),
                Candidate::where('status', 'hired')->count(),
            ],
           
            'upcomingInterviews' => FinalInterview::with('candidate')
                                                ->where('scheduled_at', '>=', now())
                                                ->orderBy('scheduled_at')
                                                ->take(5)
                                                ->get(),
            'recentCandidates' => Candidate::with('job')
                                          ->latest()
                                          ->take(5)
                                          ->get(),
        ]);
    }
}
