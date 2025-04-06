<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\JobApplication;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalCandidates' => Candidate::count(),
            'newCandidatesThisWeek' => Candidate::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'pendingApproval' => Candidate::where('status', 'pending')->count(),
            'overdueApprovals' => Candidate::where('status', 'pending')->where('created_at', '<', now()->subDays(3))->count(),
            'forReviewCount' => JobApplication::where('status', 'under_review')->count(),
            'reviewToday' => JobApplication::where('status', 'under_review')->whereDate('updated_at', today())->count(),
            'hiredCandidates' => JobApplication::where('status', 'hired')->count(),
            'hiredThisMonth' => JobApplication::where('status', 'hired')->whereMonth('updated_at', now()->month)->count(),
            'notifications' => auth()->user()->unreadNotifications()->limit(5)->get(),
            'approvalQueue' => Candidate::with('job')->where('status', 'pending')->limit(5)->get(),
            'statusData' => [
                JobApplication::where('status', 'new')->count(),
                JobApplication::where('status', 'under_review')->count(),
                JobApplication::where('status', 'approved')->count(),
                JobApplication::where('status', 'rejected')->count(),
                JobApplication::where('status', 'hired')->count(),
            ]
        ]);
    }

    // You might also want to add method for the chart data
    public function getChartData()
    {
        $data = JobApplication::select(
                DB::raw('status'),
                DB::raw('count(*) as count')
            )
            ->groupBy('status')
            ->get();
        
        return response()->json($data);
    }
}