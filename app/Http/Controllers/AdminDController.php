<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\RecruitmentProcess;
use App\Mail\StageInvitationEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExamInstructionsEmail;
use App\Mail\DemoInstructionsEmail;
use App\Mail\InitialInterviewInvitation;
use App\Mail\InterviewScheduled;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Mail\InterviewInvitation;
use App\Mail\DemoInstructions;
use App\Mail\ExamInstructions;
use App\Mail\FinalInterviewInvitation;
use Illuminate\Support\Facades\DB;
use App\Models\ExamEvaluation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AdminDController extends Controller
{
    public function showScheduleForm(JobApplication $application)
    {
        $interviewers = User::whereIn('role', ['staff', 'admin'])
                              ->select(['id', 'first_name', 'last_name'])
                              ->orderBy('first_name')
                              ->orderBy('last_name')
                              ->get();
    
        return view('admin.recruitment.schedule_initial_interview', [
            'application' => $application,
            'interviewers' => $interviewers
        ]);
    }
    
    public function showApplications()
    {
        $applications = JobApplication::all();
        return view('admin.recruitment.applications', [
            'applications' => $applications
        ]);
    }

    public function initialInterview(Request $request, $applicationId)
    {
        try {
            $validated = $request->validate([
                'interview_date' => 'required|date',
                'interview_time' => 'required',
                'interviewer_id' => 'required|exists:users,id',
                'interview_type' => 'required|in:virtual,in_person',
                'location' => 'nullable|string',
                'meeting_link' => 'nullable|url',
                'notes' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $application = JobApplication::with('job', 'user')->findOrFail($applicationId);

            if ($application->status !== JobApplication::STATUS_APPLIED) {
                return back()->with('error', 'Only applications with "applied" status can be scheduled for an initial interview.');
            }

            $interviewer = User::findOrFail($validated['interviewer_id']);
            $scheduledAt = Carbon::createFromFormat('Y-m-d H:i', $validated['interview_date'].' '.$validated['interview_time']);

            $application->update([
                'status' => JobApplication::STATUS_INITIAL_INTERVIEW_SCHEDULED,
                'current_stage' => 'initial_interview'
            ]);

            $recruitmentProcess = RecruitmentProcess::updateOrCreate(
                [
                    'application_id' => $applicationId,
                    'stage' => 'initial_interview'
                ],
                [
                    'scheduled_at' => $scheduledAt,
                    'interviewer' => "{$interviewer->first_name} {$interviewer->last_name}",
                    'location' => $validated['interview_type'] === 'in_person' ? $validated['location'] : null,
                    'meeting_link' => $validated['interview_type'] === 'virtual' ? $validated['meeting_link'] : null,
                    'notes' => $validated['notes'],
                    'passed' => null,
                    'completed_at' => null,
                    'status' => 'scheduled'
                ]
            );

            if ($request->boolean('send_email', false)) {
                $mailData = [
                    'candidate_name' => $application->user->name,
                    'job_title' => $application->job->title,
                    'interview_type' => $validated['interview_type'],
                    'scheduled_at' => $scheduledAt->toDayDateTimeString(),
                    'date' => $scheduledAt->toDateString(),
                    'time' => $scheduledAt->format('h:i A'),
                    'type' => $validated['interview_type'],
                    'location' => $validated['location'] ?? null,
                    'meeting_link' => $validated['meeting_link'] ?? null,
                    'interviewer_name' => "{$interviewer->first_name} {$interviewer->last_name}",
                    'notes' => $validated['notes'] ?? null,
                    'company_name' => config('app.name')
                ];

                Mail::to($application->email)->send(new InitialInterviewInvitation($application, $mailData));
            }

            DB::commit();

            return redirect()->route('admin.recruitment.initial_interviews')
                ->with('success', 'Interview scheduled!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function markPassed(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'passed' => 'required|boolean',
            'notes' => 'nullable|string|max:500'
        ]);
    
        RecruitmentProcess::updateOrCreate(
            [
                'application_id' => $application->id,
                'stage' => 'initial_interview'
            ],
            [
                'passed' => $validated['passed'],
                'stage_result' => $validated['passed'] ? 'passed' : 'failed',
                'notes' => $validated['notes'],
                'completed_at' => now(),
                'status' => 'completed'
            ]
        );
    
        $newStatus = $validated['passed'] 
            ? JobApplication::STATUS_INITIAL_INTERVIEW_PASSED
            : JobApplication::STATUS_INITIAL_INTERVIEW_FAILED;
    
        $application->update(['status' => $newStatus]);
    
        if ($validated['passed']) {
            RecruitmentProcess::updateOrCreate(
                [
                    'application_id' => $application->id,
                    'stage' => 'demo'
                ],
                [
                    'status' => 'scheduled',
                    'scheduled_at' => now()->addDays(3)
                ]
            );
        }
    
        return redirect()
               ->route('admin.recruitment.initial_interviews')
               ->with('success', $validated['passed'] 
                   ? 'Candidate passed initial interview - Demo stage scheduled' 
                   : 'Candidate did not pass initial interview');
    }

    public function initialInterviews()
    {
        $interviews = RecruitmentProcess::with(['application.user', 'application.job'])
            ->where('stage', 'initial_interview')
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);
    
        $applications = JobApplication::with('job')
            ->where('current_stage', 'initial_interview')
            ->whereDoesntHave('recruitmentProcess', function($query) {
                $query->where('stage', 'initial_interview')
                      ->whereColumn('job_applications.id', 'recruitment_process.application_id');
            })
            ->paginate(10);
    
        $recruitmentCounts = [
            'total' => RecruitmentProcess::where('stage', 'initial_interview')->count(),
            'pending' => RecruitmentProcess::where('stage', 'initial_interview')->whereNull('completed_at')->count(),
            'completed' => RecruitmentProcess::where('stage', 'initial_interview')->whereNotNull('completed_at')->count(),
            'rescheduled' => RecruitmentProcess::where('stage', 'initial_interview')->where('rescheduled', true)->count()
        ];
    
        return view('admin.recruitment.initial_interviews', [
            'interviews' => $interviews,
            'applications' => $applications,
            'counts' => $recruitmentCounts
        ]);
    }

    public function demos()
    {
        $scheduledDemos = JobApplication::with(['user', 'job'])
            ->where('status', JobApplication::STATUS_DEMO_SCHEDULED)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $unscheduledDemos = JobApplication::with(['user', 'job'])
            ->where('status', JobApplication::STATUS_INITIAL_INTERVIEW_PASSED)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $demoStats = [
            'total' => JobApplication::where('status', JobApplication::STATUS_DEMO_SCHEDULED)->count(),
            'pending' => JobApplication::where('status', JobApplication::STATUS_DEMO_SCHEDULED)->count(),
            'completed' => JobApplication::whereIn('status', [
                JobApplication::STATUS_DEMO_COMPLETED,
                JobApplication::STATUS_DEMO_PASSED, 
                JobApplication::STATUS_DEMO_FAILED
            ])->count(),
            'passed' => JobApplication::where('status', JobApplication::STATUS_DEMO_PASSED)->count(),
            'failed' => JobApplication::where('status', JobApplication::STATUS_DEMO_FAILED)->count()
        ];

        return view('admin.recruitment.demos', [
            'scheduledDemos' => $scheduledDemos,
            'unscheduledDemos' => $unscheduledDemos,
            'demoStats' => $demoStats
        ]);
    }

    public function exams()
    {
        $applications = JobApplication::whereIn('status', [
                JobApplication::STATUS_EXAM_SCHEDULED,
                JobApplication::STATUS_EXAM_COMPLETED,
                JobApplication::STATUS_EXAM_PASSED,
                JobApplication::STATUS_EXAM_FAILED
            ])
            ->with(['job', 'recruitmentProcess' => function($query) {
                $query->where('stage', 'exam');
            }])
            ->latest()
            ->paginate(10);
        
        $evaluations = ExamEvaluation::with(['application', 'application.job'])
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->paginate(10, ['*'], 'evaluations_page');

        $totalExams = JobApplication::whereIn('status', [
                JobApplication::STATUS_EXAM_SCHEDULED,
                JobApplication::STATUS_EXAM_COMPLETED,
                JobApplication::STATUS_EXAM_PASSED,
                JobApplication::STATUS_EXAM_FAILED
            ])->count();
            
        $completedExams = JobApplication::whereIn('status', [
                JobApplication::STATUS_EXAM_COMPLETED,
                JobApplication::STATUS_EXAM_PASSED,
                JobApplication::STATUS_EXAM_FAILED
            ])->count();
            
        $pendingExams = JobApplication::where('status', JobApplication::STATUS_EXAM_SCHEDULED)
            ->count();

        return view('admin.recruitment.exams', [
            'applications' => $applications,
            'evaluations' => $evaluations,
            'totalExams' => $totalExams,
            'completedExams' => $completedExams,
            'pendingExams' => $pendingExams,
        ]);
    }

    public function finalInterviews()
    {
        $allFinalInterviews = RecruitmentProcess::with(['application', 'application.job'])
            ->where('stage', 'final_interview')
            ->orderBy('scheduled_at', 'desc');

        $finalInterviewCount = $allFinalInterviews->count();
        $completedCount = (clone $allFinalInterviews)->whereNotNull('completed_at')->count();
        $pendingCount = $finalInterviewCount - $completedCount;
        
        $passedCount = JobApplication::whereHas('recruitmentProcess', function($query) {
                $query->where('stage', 'final_interview');
            })
            ->where('status', JobApplication::STATUS_FINAL_INTERVIEW_PASSED)
            ->count();

        $failedCount = JobApplication::whereHas('recruitmentProcess', function($query) {
                $query->where('stage', 'final_interview');
            })
            ->where('status', JobApplication::STATUS_FINAL_INTERVIEW_FAILED)
            ->count();

        $interviews = $allFinalInterviews->paginate(10);

        return view('admin.recruitment.final_interviews', compact(
            'interviews',
            'finalInterviewCount',
            'completedCount',
            'pendingCount',
            'passedCount',
            'failedCount'
        ));
    }

    public function scheduleDemo(Request $request, $applicationId)
    {
        $request->validate([
            'scheduled_at' => 'required|date',
            'location' => 'required|string',
            'instructions' => 'required|string'
        ]);

        $application = JobApplication::findOrFail($applicationId);
        
        RecruitmentProcess::updateOrCreate(
            ['application_id' => $applicationId, 'stage' => 'demo'],
            [
                'scheduled_at' => $request->scheduled_at,
                'location' => $request->location,
                'notes' => $request->instructions
            ]
        );

        Mail::to($application->email)->send(new DemoInstructions($application, [
            'date' => $request->scheduled_at,
            'location' => $request->location,
            'instructions' => $request->instructions
        ]));

        return back()->with('success', 'Demo scheduled successfully.');
    }

    public function completeDemo(Request $request, JobApplication $application)
    {
        if ($request->isMethod('get')) {
            return view('admin.recruitment.complete-demo', compact('application'));
        }

        $request->validate([
            'passed' => 'required|boolean',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            RecruitmentProcess::updateOrCreate(
                ['application_id' => $application->id, 'stage' => 'demo'],
                [
                    'completed_at' => now(),
                    'passed' => $request->passed,
                    'notes' => $request->notes,
                    'status' => 'completed'
                ]
            );

            $status = $request->passed 
                ? JobApplication::STATUS_DEMO_PASSED 
                : JobApplication::STATUS_DEMO_FAILED;
            
            $application->update(['status' => $status]);

            DB::commit();

            if ($request->passed) {
                return redirect()->route('admin.recruitment.scheduleExam', $application->id)
                    ->with('success', 'Demo completed. Please schedule the exam.');
            }

            return back()->with('success', 'Demo marked as completed.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to complete demo: '.$e->getMessage());
        }
    }

    public function storeExam(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:job_applications,id',
            'exam_type' => 'required|string',
            'scheduled_at' => 'required|date',
            'duration' => 'required|integer|min:30',
            'location' => 'required|string',
            'instructions' => 'nullable|string',
            'exam_content' => 'nullable|string',
            'map_link' => 'nullable|url',
        ]);

        $application = JobApplication::with('job')->findOrFail($validated['application_id']);

        $exam = RecruitmentProcess::create([
            'application_id' => $validated['application_id'],
            'stage' => 'exam',
            'exam_type' => $validated['exam_type'],
            'scheduled_at' => $validated['scheduled_at'],
            'duration' => $validated['duration'],
            'location' => $validated['location'],
            'notes' => $validated['instructions'],
        ]);

        $application->update([
            'status' => JobApplication::STATUS_EXAM_SCHEDULED,
            'current_stage' => 'exam'
        ]);

        $emailData = [
            'date' => $exam->scheduled_at,
            'location' => $exam->location,
            'duration' => $exam->duration . ' minutes',
            'exam_content' => $validated['exam_content'] ?? null,
            'map_link' => $validated['map_link'] ?? null,
        ];

        Mail::to($application->email)->send(new ExamInstructions($application, $emailData));

        return redirect()->route('admin.recruitment.exams')
            ->with('success', 'Exam scheduled successfully and candidate notified!');
    }

    public function completeExam(Request $request, $applicationId)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|between:0,100',
            'passed' => 'required|boolean',
            'criteria' => 'required|array',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'feedback' => 'nullable|string'
        ]);

        DB::transaction(function () use ($validated, $applicationId) {
            ExamEvaluation::create([
                'application_id' => $applicationId,
                'evaluator_id' => auth()->id(),
                'score' => $validated['score'],
                'passed' => $validated['passed'],
                'criteria_scores' => $validated['criteria'],
                'strengths' => $validated['strengths'],
                'weaknesses' => $validated['weaknesses'],
                'feedback' => $validated['feedback'],
                'completed_at' => now()
            ]);

            RecruitmentProcess::updateOrCreate(
                ['application_id' => $applicationId, 'stage' => 'exam'],
                [
                    'completed_at' => now(),
                    'passed' => $validated['passed'],
                    'score' => $validated['score'],
                    'notes' => $validated['feedback']
                ]
            );

            $application = JobApplication::findOrFail($applicationId);
            $status = $validated['passed'] 
                ? JobApplication::STATUS_EXAM_PASSED 
                : JobApplication::STATUS_EXAM_FAILED;
            $application->update(['status' => $status]);
        });

        return redirect()->back()
            ->with('success', 'Exam evaluation submitted successfully');
    }

    public function scheduleFinalInterview(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'interviewers' => 'required|string|max:255',
            'interview_type' => 'required|in:in_person,virtual',
            'location' => 'required_if:interview_type,in_person|nullable|string|max:255',
            'meeting_link' => 'required_if:interview_type,virtual|nullable|url|max:255',
            'notes' => 'nullable|string'
        ]);

        $process = RecruitmentProcess::create([
            'application_id' => $application->id,
            'stage' => 'final_interview',
            'scheduled_at' => $validated['scheduled_at'],
            'interviewers' => $validated['interviewers'],
            'interview_type' => $validated['interview_type'],
            'location' => $validated['location'] ?? null,
            'meeting_link' => $validated['meeting_link'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        Mail::to($application->email)->send(new FinalInterviewInvitation($application, [
            'type' => 'Final Interview',
            'date' => $request->scheduled_at,
            'location' => $request->location,
            'meeting_link' => $request->meeting_link,
            'interviewer' => $request->interviewer
        ]));

        $application->update([
            'status' => JobApplication::STATUS_FINAL_INTERVIEW_SCHEDULED,
            'current_stage' => 'final_interview'
        ]);

        return redirect()->route('admin.recruitment.finalInterviews')
            ->with('success', 'Final interview scheduled successfully!');
    }

    public function updateResult(RecruitmentProcess $interview, Request $request)
    {
        $validated = $request->validate([
            'passed' => 'required|boolean'
        ]);

        $interview->update([
            'passed' => $validated['passed'],
            'result' => $validated['passed'] ? 'passed' : 'failed',
            'completed_at' => now()
        ]);

        $status = $validated['passed'] 
            ? JobApplication::STATUS_FINAL_INTERVIEW_PASSED
            : JobApplication::STATUS_FINAL_INTERVIEW_FAILED;

        $interview->application()->update([
            'status' => $status,
        ]);

        return redirect()->back()->with(
            'success', 
            $validated['passed'] ? 'Candidate passed!' : 'Candidate failed!'
        );
    }

    public function index()
    {
        // Count applications by status category
        $stats = [
            'applicants' => JobApplication::where('status', JobApplication::STATUS_APPLIED)->count(),
            'initial_interviews' => JobApplication::whereIn('status', [
                JobApplication::STATUS_INITIAL_INTERVIEW_SCHEDULED,
                JobApplication::STATUS_INITIAL_INTERVIEW_COMPLETED,
                JobApplication::STATUS_INITIAL_INTERVIEW_PASSED,
                JobApplication::STATUS_INITIAL_INTERVIEW_FAILED,
            ])->count(),
            'demos' => JobApplication::whereIn('status', [
                JobApplication::STATUS_DEMO_SCHEDULED,
                JobApplication::STATUS_DEMO_COMPLETED,
                JobApplication::STATUS_DEMO_PASSED,
                JobApplication::STATUS_DEMO_FAILED,
            ])->count(),
            'exams' => JobApplication::whereIn('status', [
                JobApplication::STATUS_EXAM_SCHEDULED,
                JobApplication::STATUS_EXAM_COMPLETED,
                JobApplication::STATUS_EXAM_PASSED,
                JobApplication::STATUS_EXAM_FAILED,
            ])->count(),
            'final_interviews' => JobApplication::whereIn('status', [
                JobApplication::STATUS_FINAL_INTERVIEW_SCHEDULED,
                JobApplication::STATUS_FINAL_INTERVIEW_COMPLETED,
                JobApplication::STATUS_FINAL_INTERVIEW_PASSED,
                JobApplication::STATUS_FINAL_INTERVIEW_FAILED,
            ])->count(),
            'pre_employment' => JobApplication::where('status', JobApplication::STATUS_PRE_EMPLOYMENT)->count(),
            'onboarding' => JobApplication::where('status', JobApplication::STATUS_ONBOARDING)->count(),
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
        $recentActivities = JobApplication::query()
        ->select(['firstname', 'lastname', 'status', 'updated_at'])
        ->whereNotNull('status')
        ->latest('updated_at')
        ->limit(5)
        ->get()
        ->map(function ($application) {
            $statusInfo = match($application->status) {
                // Application Stage
                JobApplication::STATUS_APPLIED => [
                    'description' => "Applied for position",
                    'icon' => 'fi fi-sr-file-edit',
                    'class' => 'bg-blue-100 text-blue-500'
                ],
                
                // Initial Interview Stages
                JobApplication::STATUS_INITIAL_INTERVIEW_SCHEDULED => [
                    'description' => "Initial interview scheduled",
                    'icon' => 'fi fi-sr-calendar-clock',
                    'class' => 'bg-purple-100 text-purple-500'
                ],
                JobApplication::STATUS_INITIAL_INTERVIEW_COMPLETED => [
                    'description' => "Completed initial interview",
                    'icon' => 'fi fi-sr-check-circle',
                    'class' => 'bg-amber-100 text-amber-500'
                ],
                
                // Demo Stages
                JobApplication::STATUS_DEMO_SCHEDULED => [
                    'description' => "Demo session scheduled",
                    'icon' => 'fi fi-sr-presentation',
                    'class' => 'bg-indigo-100 text-indigo-500'
                ],
                
                // Exam Stages
                JobApplication::STATUS_EXAM_SCHEDULED => [
                    'description' => "Assessment scheduled",
                    'icon' => 'fi fi-sr-clipboard-list',
                    'class' => 'bg-cyan-100 text-cyan-500'
                ],
                
                // Final Interview Stages
                JobApplication::STATUS_FINAL_INTERVIEW_SCHEDULED => [
                    'description' => "Final interview scheduled",
                    'icon' => 'fi fi-sr-calendar-star',
                    'class' => 'bg-violet-100 text-violet-500'
                ],
                
                // Pre-employment
                JobApplication::STATUS_PRE_EMPLOYMENT_DOCUMENTS => [
                    'description' => "Submitted pre-employment documents",
                    'icon' => 'fi fi-sr-folder-upload',
                    'class' => 'bg-emerald-100 text-emerald-500'
                ],
                
                // Final Outcomes
                JobApplication::STATUS_HIRED => [
                    'description' => "Successfully hired!",
                    'icon' => 'fi fi-sr-badge-check',
                    'class' => 'bg-green-100 text-green-500'
                ],
                JobApplication::STATUS_REJECTED => [
                    'description' => "Application not successful",
                    'icon' => 'fi fi-sr-circle-xmark',
                    'class' => 'bg-red-100 text-red-500'
                ],
                
                // Default case
                default => [
                    'description' => "Moved to " . Str::title(str_replace('_', ' ', $application->status)),
                    'icon' => 'fi fi-sr-arrow-up-right',
                    'class' => 'bg-gray-100 text-gray-500'
                ]
            };

            return (object)[
                'firstname' => $application->firstname,
                'lastname' => $application->lastname,
                'description' => $statusInfo['description'],
                'status_icon' => $statusInfo['icon'],
                'status_class' => $statusInfo['class'],
                'created_at' => $application->updated_at
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

    protected function getStatusCounts()
    {
        return [
            'applied' => JobApplication::where('status', JobApplication::STATUS_APPLIED)->count(),
            
            'initial_interview_scheduled' => JobApplication::where('status', JobApplication::STATUS_INITIAL_INTERVIEW_SCHEDULED)->count(),
            'initial_interview_completed' => JobApplication::where('status', JobApplication::STATUS_INITIAL_INTERVIEW_COMPLETED)->count(),
            
            'demo_scheduled' => JobApplication::where('status', JobApplication::STATUS_DEMO_SCHEDULED)->count(),
            'demo_completed' => JobApplication::where('status', JobApplication::STATUS_DEMO_COMPLETED)->count(),
            
            'exam_scheduled' => JobApplication::where('status', JobApplication::STATUS_EXAM_SCHEDULED)->count(),
            'exam_completed' => JobApplication::where('status', JobApplication::STATUS_EXAM_COMPLETED)->count(),
            
            'final_interview_scheduled' => JobApplication::where('status', JobApplication::STATUS_FINAL_INTERVIEW_SCHEDULED)->count(),
            'final_interview_completed' => JobApplication::where('status', JobApplication::STATUS_FINAL_INTERVIEW_COMPLETED)->count(),
            
            'pre_employment' => JobApplication::where('status', JobApplication::STATUS_PRE_EMPLOYMENT)->count(),
            'onboarding' => JobApplication::where('status', JobApplication::STATUS_ONBOARDING)->count(),
            'hired' => JobApplication::where('status', JobApplication::STATUS_HIRED)->count(),
        ];
    }

    protected function getStageView($status)
    {
        $stages = [
            JobApplication::STATUS_APPLIED => 'applied-show',
            JobApplication::STATUS_INITIAL_INTERVIEW_SCHEDULED => 'initial-interview-show',
            JobApplication::STATUS_INITIAL_INTERVIEW_COMPLETED => 'initial-interview-show',
            JobApplication::STATUS_DEMO_SCHEDULED => 'demo-show',
            JobApplication::STATUS_DEMO_COMPLETED => 'demo-show',
            JobApplication::STATUS_EXAM_SCHEDULED => 'exam-show',
            JobApplication::STATUS_EXAM_COMPLETED => 'exam-show',
            JobApplication::STATUS_FINAL_INTERVIEW_SCHEDULED => 'final-interview-show',
            JobApplication::STATUS_FINAL_INTERVIEW_COMPLETED => 'final-interview-show',
            JobApplication::STATUS_PRE_EMPLOYMENT => 'pre-employment-show',
            JobApplication::STATUS_ONBOARDING => 'onboarding-show',
            JobApplication::STATUS_HIRED => 'hired-show',
        ];

        return $stages[$status] ?? 'applied-show';
    }

    public function viewApplications()
    {
        $applicants = JobApplication::paginate(5);
        
        // Get totals for each category
        $totalApplicants = JobApplication::count();
        $totalInitialInterviews = JobApplication::where('current_stage', 'Initial Interview')->count();
        $totalInterviews = JobApplication::where('current_stage', 'Interview')->count();
        $totalOffers = JobApplication::where('current_stage', 'Offer')->count();
        $totalHires = JobApplication::where('current_stage', 'Hired')->count();
    
        return view('admin.recruitment.applicants', compact(
            'applicants', 
            'totalApplicants', 
            'totalInitialInterviews', 
            'totalInterviews', 
            'totalOffers', 
            'totalHires'
        ));
    }

    public function showApplication($id)
    {
        $applicant = JobApplication::findOrFail($id);

        $totalApplications = JobApplication::where('current_stage', 'Application')->count();
        $totalInitialInterviews = JobApplication::where('current_stage', 'Initial Interview')->count();
        $totalInterviews = JobApplication::where('current_stage', 'Interview')->count();
        $totalOffers = JobApplication::where('current_stage', 'Offer')->count();
        $totalHires = JobApplication::where('current_stage', 'Hired')->count();

        return view('admin.recruitment.initial_interviews', compact(
            'applicant',
            'totalApplications',
            'totalInitialInterviews',
            'totalInterviews',
            'totalOffers',
            'totalHires'
        ));
    }

    public function show($id)
    {
        $application = JobApplication::with([
            'job',
            'recruitmentProcesses',
            'preEmploymentDocuments',
            'offerLetter',
            'onboardingDocuments'
        ])->findOrFail($id);

        return view('admin.recruitment.applicants.show', compact('application'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validStatuses = [
            JobApplication::STATUS_APPLIED,
            JobApplication::STATUS_INITIAL_INTERVIEW_SCHEDULED,
            JobApplication::STATUS_INITIAL_INTERVIEW_COMPLETED,
            JobApplication::STATUS_INITIAL_INTERVIEW_PASSED,
            JobApplication::STATUS_INITIAL_INTERVIEW_FAILED,
            JobApplication::STATUS_DEMO_SCHEDULED,
            JobApplication::STATUS_DEMO_COMPLETED,
            JobApplication::STATUS_DEMO_PASSED,
            JobApplication::STATUS_DEMO_FAILED,
            JobApplication::STATUS_EXAM_SCHEDULED,
            JobApplication::STATUS_EXAM_COMPLETED,
            JobApplication::STATUS_EXAM_PASSED,
            JobApplication::STATUS_EXAM_FAILED,
            JobApplication::STATUS_FINAL_INTERVIEW_SCHEDULED,
            JobApplication::STATUS_FINAL_INTERVIEW_COMPLETED,
            JobApplication::STATUS_FINAL_INTERVIEW_PASSED,
            JobApplication::STATUS_FINAL_INTERVIEW_FAILED,
            JobApplication::STATUS_HIRED,
            JobApplication::STATUS_REJECTED
        ];
    
        $validStages = [
            'application',
            'initial_interview',
            'demo',
            'exam',
            'final_interview',
            'pre_employment',
            'onboarding',
            'completed'
        ];
    
        $request->validate([
            'status' => ['required', Rule::in($validStatuses)],
            'current_stage' => ['required', Rule::in($validStages)],
            'rejection_reason' => 'nullable|string|max:1000'
        ]);
    
        $application = JobApplication::findOrFail($id);
        $application->update($request->only('status', 'current_stage', 'rejection_reason'));
    
        // Handle initial interview scheduling
        if ($request->current_stage === 'initial_interview' && 
            $request->status === JobApplication::STATUS_INITIAL_INTERVIEW_SCHEDULED) {
            
            RecruitmentProcess::updateOrCreate(
                [
                    'application_id' => $application->id,
                    'stage' => 'initial_interview'
                ],
                [
                    'status' => 'scheduled',
                    'scheduled_at' => null
                ]
            );
    
            return redirect()
                ->route('admin.recruitment.scheduleInitialInterview', $id)
                ->with('success', 'Please schedule the initial interview');
        }
    
        // For other stages, create/update process records as needed
        if (in_array($request->current_stage, ['demo', 'exam', 'final_interview'])) {
            RecruitmentProcess::updateOrCreate(
                [
                    'application_id' => $application->id,
                    'stage' => $request->current_stage
                ],
                [
                    'status' => str_replace('_', ' ', $request->status),
                    'scheduled_at' => now()
                ]
            );
        }
    
        return back()->with('success', 'Application updated successfully');
    }
}
