<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Employee;
use App\Models\HiringProcessStage;
use App\Models\JobApplication;
use App\Models\User;
use App\Model\Interview;

class DashboardController extends Controller
{
    public function index()
    {
        // Count applications by status category
        $stats = [
            'applied' => JobApplication::where('status', JobApplication::STATUS_APPLIED)->count(),
            'initial_interview' => JobApplication::whereIn('status', [
                JobApplication::STATUS_INITIAL_INTERVIEW_SCHEDULED,
                JobApplication::STATUS_INITIAL_INTERVIEW_COMPLETED,
                JobApplication::STATUS_INITIAL_INTERVIEW_PASSED,
                JobApplication::STATUS_INITIAL_INTERVIEW_FAILED,
            ])->count(),
            'demo' => JobApplication::whereIn('status', [
                JobApplication::STATUS_DEMO_SCHEDULED,
                JobApplication::STATUS_DEMO_COMPLETED,
                JobApplication::STATUS_DEMO_PASSED,
                JobApplication::STATUS_DEMO_FAILED,
            ])->count(),
            'exam' => JobApplication::whereIn('status', [
                JobApplication::STATUS_EXAM_SCHEDULED,
                JobApplication::STATUS_EXAM_COMPLETED,
                JobApplication::STATUS_EXAM_PASSED,
                JobApplication::STATUS_EXAM_FAILED,
            ])->count(),
            'final_interview' => JobApplication::whereIn('status', [
                JobApplication::STATUS_FINAL_INTERVIEW_SCHEDULED,
                JobApplication::STATUS_FINAL_INTERVIEW_COMPLETED,
                JobApplication::STATUS_FINAL_INTERVIEW_PASSED,
                JobApplication::STATUS_FINAL_INTERVIEW_FAILED,
            ])->count(),
            'pre_employment' => JobApplication::where('status', JobApplication::STATUS_PRE_EMPLOYMENT)->count(),
            'onboarding' => JobApplication::where('status', JobApplication::STATUS_ONBOARDING)->count(),
            'hired' => JobApplication::where('status', JobApplication::STATUS_HIRED)->count(),
            'rejected' => JobApplication::where('status', JobApplication::STATUS_REJECTED)->count(),
        ];

        // Get candidate lists for each stage
        $initialInterviewCandidates = JobApplication::whereIn('status', [
                JobApplication::STATUS_INITIAL_INTERVIEW_SCHEDULED,
                JobApplication::STATUS_INITIAL_INTERVIEW_COMPLETED,
            ])
            ->with('job')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $demoCandidates = JobApplication::whereIn('status', [
                JobApplication::STATUS_DEMO_SCHEDULED,
                JobApplication::STATUS_DEMO_COMPLETED,
            ])
            ->with('job')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $examCandidates = JobApplication::whereIn('status', [
                JobApplication::STATUS_EXAM_SCHEDULED,
                JobApplication::STATUS_EXAM_COMPLETED,
            ])
            ->with('job')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $finalInterviewCandidates = JobApplication::whereIn('status', [
                JobApplication::STATUS_FINAL_INTERVIEW_SCHEDULED,
                JobApplication::STATUS_FINAL_INTERVIEW_COMPLETED,
            ])
            ->with('job')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $preEmploymentCandidates = JobApplication::where('status', JobApplication::STATUS_PRE_EMPLOYMENT)
            ->with(['job', 'preEmploymentDocument'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $onboardingCandidates = JobApplication::where('status', JobApplication::STATUS_ONBOARDING)
            ->with(['job', 'onboarding'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Recent activities
        $recentActivities = JobApplication::with('job')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($application) {
                return [
                    'name' => $application->firstname . ' ' . $application->lastname,
                    'job' => $application->job ? $application->job->title : 'N/A',
                    'status' => $application->status,
                    'time' => $application->updated_at->diffForHumans(),
                    'action' => 'Status changed to ' . str_replace('_', ' ', $application->status),
                ];
            });

        return view('admin.dashboard', [
            'stats' => $stats,
            'initialInterviewCandidates' => $initialInterviewCandidates,
            'demoCandidates' => $demoCandidates,
            'examCandidates' => $examCandidates,
            'finalInterviewCandidates' => $finalInterviewCandidates,
            'preEmploymentCandidates' => $preEmploymentCandidates,
            'onboardingCandidates' => $onboardingCandidates,
            'recentActivities' => $recentActivities
        ]);
    }
    
}