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
use Illuminate\Support\Str;


class RecruitmentProcessController extends Controller
{

    public function showScheduleForm(JobApplication $application)
    {

        
        // Interviewers query for 'staff' or 'admin' roles
        $interviewers = User::whereIn('role', ['staff', 'admin'])
                              ->select(['id', 'first_name', 'last_name'])
                              ->orderBy('first_name') // Order by first_name
                              ->orderBy('last_name')  // Secondary order by last_name
                              ->get();
    
        // Pass both application and interviewers to the view
        return view('staff.recruitment.schedule_initial_interview', [
            'application' => $application,  // Pass the application from route model binding
            'interviewers' => $interviewers
        ]);
    }
    
    public function showApplications()
{
    // Fetch all applications (or filter as needed)
    $applications = JobApplication::all(); // or use a specific query to fetch applications
    
    return view('staff.recruitment.applications', [
        'applications' => $applications,
        
        
    ]);
}

    

    


public function initialInterview(Request $request, $applicationId)
{
   

    try {
        // Validation
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

        // ✅ Allow scheduling only for specific statuses
        $allowedStatuses = [
            JobApplication::STATUS_APPLIED,
            JobApplication::STATUS_INITIAL_INTERVIEW_SCHEDULED,
            JobApplication::STATUS_INITIAL_INTERVIEW_COMPLETED,
            JobApplication::STATUS_INITIAL_INTERVIEW_PASSED,
            JobApplication::STATUS_INITIAL_INTERVIEW_FAILED
        ];

        if (!in_array($application->status, $allowedStatuses)) {
            return back()->with('error', 'Only applications with valid interview-related statuses can be scheduled for an initial interview.');
        }

        $interviewer = User::findOrFail($validated['interviewer_id']);
        $scheduledAt = Carbon::createFromFormat('Y-m-d H:i', $validated['interview_date'].' '.$validated['interview_time']);

        // Update application status and stage
        $application->update([
            'status' => JobApplication::STATUS_INITIAL_INTERVIEW_SCHEDULED,
            'current_stage' => 'initial_interview'
        ]);

        // Update or create the process record
        RecruitmentProcess::updateOrCreate(
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

        // Send email if enabled
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

        return redirect()->route('staff.recruitment.initial_interviews')
            ->with('success', 'Interview scheduled!');
            
    } catch (\Exception $e) {
        DB::rollBack();
       
        return back()->withInput()->with('error', 'Error: '.$e->getMessage());
    }
}


    
    public function completeInitialInterview(Request $request, $applicationId)
    {
        $request->validate([
            'passed' => 'required|boolean',
            'notes' => 'nullable|string'
        ]);

        RecruitmentProcess::updateOrCreate(
            ['application_id' => $applicationId, 'stage' => 'initial_interview'],
            [
                'completed_at' => now(),
                'passed' => $request->passed,
                'notes' => $request->notes
            ]
        );

        if ($request->passed) {
            return redirect()->route('admin.recruitment.scheduleDemo', $applicationId)
                ->with('success', 'Initial interview marked as completed. Please schedule the demo.');
        }

        return back()->with('success', 'Initial interview marked as completed.');
    }public function markPassed(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'passed' => 'required|boolean',
            'notes' => 'nullable|string|max:500'
        ]);
    
        // Update or create recruitment process record
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
    
        // Update application status using model constants
        $newStatus = $validated['passed'] 
            ? JobApplication::STATUS_INITIAL_INTERVIEW_PASSED
            : JobApplication::STATUS_INITIAL_INTERVIEW_FAILED;
    
        $application->update(['status' => $newStatus]);
    
        // If passed, automatically schedule the demo stage
        if ($validated['passed']) {
            RecruitmentProcess::updateOrCreate(
                [
                    'application_id' => $application->id,
                    'stage' => 'demo'
                ],
                [
                    'status' => 'scheduled',
                    'scheduled_at' => now()->addDays(3) // Schedule demo 3 days from now
                ]
            );
        }
    
        return redirect()
               ->route('staff.recruitment.initial_interviews')
               ->with('success', $validated['passed'] 
                   ? 'Candidate passed initial interview - Demo stage scheduled' 
                   : 'Candidate did not pass initial interview');
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
    // Handle GET request (show form)
    if ($request->isMethod('get')) {
        return view('staff.recruitment.complete-demo', compact('application'));
    }

    // Handle POST request (process form)
    $request->validate([
        'passed' => 'required|boolean',
        'notes' => 'nullable|string'
    ]);

    try {
        DB::beginTransaction();

        // Update the demo process record
        RecruitmentProcess::updateOrCreate(
            ['application_id' => $application->id, 'stage' => 'demo'],
            [
                'completed_at' => now(),
                'passed' => $request->passed,
                'notes' => $request->notes,
                'status' => 'completed'
            ]
        );

        // Update application status
        $status = $request->passed 
            ? JobApplication::STATUS_DEMO_PASSED 
            : JobApplication::STATUS_DEMO_FAILED;
        
        $application->update(['status' => $status]);

        DB::commit();

        if ($request->passed) {
            return redirect()->route('staff.recruitment.scheduleExam', $application->id)
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

    // Get the application with job relationship
    $application = JobApplication::with('job')->findOrFail($validated['application_id']);

    // Create the exam process
    $exam = RecruitmentProcess::create([
        'application_id' => $validated['application_id'],
        'stage' => 'exam',
        'exam_type' => $validated['exam_type'],
        'scheduled_at' => $validated['scheduled_at'],
        'duration' => $validated['duration'],
        'location' => $validated['location'],
        'notes' => $validated['instructions'],
    ]);

    // Update both status and current_stage
    $application->update([
        'status' => JobApplication::STATUS_EXAM_SCHEDULED,
        'current_stage' => 'exam' // Add this line
    ]);

    // Prepare data for email
    $emailData = [
        'date' => $exam->scheduled_at,
        'location' => $exam->location,
        'duration' => $exam->duration . ' minutes',
        'exam_content' => $validated['exam_content'] ?? null,
        'map_link' => $validated['map_link'] ?? null,
    ];

    // Send email notification
    Mail::to($application->email)->send(new ExamInstructions($application, $emailData));

    return redirect()->route('staff.recruitment.exams')
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
        // 1. Create exam evaluation record
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

        // 2. Update recruitment process
        RecruitmentProcess::updateOrCreate(
            ['application_id' => $applicationId, 'stage' => 'exam'],
            [
                'completed_at' => now(),
                'passed' => $validated['passed'],
                'score' => $validated['score'],
                'notes' => $validated['feedback'] // Or combine strengths/weaknesses
            ]
        );

        // 3. Update application status
        $application = JobApplication::findOrFail($applicationId);
        $status = $validated['passed'] 
            ? JobApplication::STATUS_EXAM_PASSED 
            : JobApplication::STATUS_EXAM_FAILED;
        $application->update(['status' => $status]);
    });

    return redirect()->back()
        ->with('success', 'Exam evaluation submitted successfully');
}

    
    public function initialInterviews()
    {
        // Get all initial interviews from recruitment_process table
        $interviews = RecruitmentProcess::with(['application.user', 'application.job'])
            ->where('stage', 'initial_interview')
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);
    
        // Get applications that are at initial interview stage but not yet scheduled
        $applications = JobApplication::with('job')
            ->where('current_stage', 'initial_interview')
            ->whereDoesntHave('recruitmentProcess', function($query) {
                $query->where('stage', 'initial_interview')
                      ->whereColumn('job_applications.id', 'recruitment_process.application_id');
            })
            ->paginate(10);
    
        // Count from recruitment_process
        $recruitmentCounts = [
            'total' => RecruitmentProcess::where('stage', 'initial_interview')->count(),
            'pending' => RecruitmentProcess::where('stage', 'initial_interview')->whereNull('completed_at')->count(),
            'completed' => RecruitmentProcess::where('stage', 'initial_interview')->whereNotNull('completed_at')->count(),
            'rescheduled' => RecruitmentProcess::where('stage', 'initial_interview')->where('rescheduled', true)->count()
        ];
    
        return view('staff.recruitment.initial_interviews', [
            'interviews' => $interviews,
            'applications' => $applications,
            'counts' => $recruitmentCounts
        ]);
    }

    public function rescheduleInterview(Request $request, JobApplication $application)
{
    $validated = $request->validate([
        'reschedule_date' => 'required|date',
        'reschedule_time' => 'required',
        'reschedule_reason' => 'nullable|string|max:500'
    ]);

    // Update interview record
    $application->interview()->update([
        'scheduled_at' => Carbon::parse($validated['reschedule_date'].' '.$validated['reschedule_time']),
        'status' => 'rescheduled',
        'notes' => $request->reschedule_reason
    ]);

    return back()->with('success', 'Interview rescheduled successfully');
}
    
public function demos()
{
    // Get applications with demo scheduled status
    $scheduledDemos = JobApplication::with(['user', 'job'])
        ->where('status', JobApplication::STATUS_DEMO_SCHEDULED)
        ->orderBy('updated_at', 'desc')
        ->paginate(10);

    // Get applications ready for demo scheduling
    $unscheduledDemos = JobApplication::with(['user', 'job'])
        ->where('status', JobApplication::STATUS_INITIAL_INTERVIEW_PASSED)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Simplified stats without date checks
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

    return view('staff.recruitment.demos', [
        'scheduledDemos' => $scheduledDemos,
        'unscheduledDemos' => $unscheduledDemos,
        'demoStats' => $demoStats
    ]);
}
    
public function exams()
{
    // Get applications with exam-related statuses
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
    
    // ✅ Get completed exam evaluations from the correct model
    $evaluations = ExamEvaluation::with(['application', 'application.job'])
        ->whereNotNull('completed_at')
        ->latest('completed_at')
        ->paginate(10, ['*'], 'evaluations_page');
    // Count totals
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

    return view('staff.recruitment.exams', [
        'applications' => $applications,
        'evaluations' => $evaluations,
        'totalExams' => $totalExams,
        'completedExams' => $completedExams,
        'pendingExams' => $pendingExams,
    ]);
}

public function scheduleExam()
{
    // Get candidates who have passed the demo stage
    $candidates = JobApplication::with('job')
        ->where('status', JobApplication::STATUS_DEMO_PASSED)
        ->whereDoesntHave('recruitmentProcess', function($query) {
            $query->where('stage', 'exam');
        })
        ->get();

    return view('staff.recruitment.schedule_exam', compact('candidates'));
}


    public function storeDemo(Request $request, JobApplication $application)
{
    $validated = $request->validate([
        'interview_date' => 'required|date|after_or_equal:today',
        'interview_time' => 'required',
        'location' => 'required|string',
        'preparation_instructions' => 'required|string',
        'notes' => 'nullable|string',
        'send_email' => 'nullable|boolean'
    ]);

    try {
        DB::beginTransaction();

        $scheduledAt = Carbon::createFromFormat('Y-m-d H:i', $validated['interview_date'].' '.$validated['interview_time']);

        // Only update status and stage
        $application->update([
            'status' => JobApplication::STATUS_DEMO_SCHEDULED,
            'current_stage' => 'demo'
        ]);

        // Send email if requested
        if ($request->boolean('send_email', true)) {
            $mailData = [
                'date' => $scheduledAt,
                'location' => $validated['location'],
                'preparation_instructions' => $validated['preparation_instructions'],
                'notes' => $validated['notes'] ?? 'None provided',
            ];

            Mail::to($application->user->email)
                ->send(new DemoInstructions($application, $mailData));
        }

        DB::commit();

        return redirect()->route('staff.recruitment.demos')
            ->with('success', 'Demo scheduled successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Failed to schedule demo: '.$e->getMessage());
    }
}
public function showScheduleDemo(JobApplication $application)
{
    // Verify the applicant has passed initial interview
    if ($application->status !== JobApplication::STATUS_INITIAL_INTERVIEW_PASSED) {
        return redirect()->route('staff.recruitment.demos')
            ->with('error', 'Candidate must pass initial interview first');
    }

    $evaluators = User::where('role', 'evaluator')->get(); // Or whatever logic you use to get evaluators

    return view('staff.recruitment.schedule_demo', [
        'application' => $application,
        'evaluators' => $evaluators
    ]);
}
    
public function finalInterviews()
{
    // Get all final interviews with their related application data
    $allFinalInterviews = RecruitmentProcess::with(['application', 'application.job'])
        ->where('stage', 'final_interview')
        ->orderBy('scheduled_at', 'desc');

    // Get counts
    $finalInterviewCount = $allFinalInterviews->count();
    $completedCount = (clone $allFinalInterviews)->whereNotNull('completed_at')->count();
    $pendingCount = $finalInterviewCount - $completedCount;
    
    // Get passed/failed counts from the related applications
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

    // Paginate results
    $interviews = $allFinalInterviews->paginate(10);

    return view('staff.recruitment.final_interviews', compact(
        'interviews',
        'finalInterviewCount',
        'completedCount',
        'pendingCount',
        'passedCount',
        'failedCount'
    ));
}


public function showScheduleFinalInterview()
{
    $applications = JobApplication::where('status', JobApplication::STATUS_EXAM_PASSED)
        ->whereDoesntHave('recruitmentProcess', function($query) {
            $query->where('stage', 'final_interview');
        })
        ->with(['job', 'recruitmentProcess' => function($query) {
            $query->where('stage', 'exam');
        }, 'examEvaluation']) // Include the examEvaluation relationship
        ->paginate(10);

    return view('staff.recruitment.schedule_final_interview', compact('applications'));
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

    // Create the recruitment process record
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

    // Update application status
    $application->update([
        'status' => JobApplication::STATUS_FINAL_INTERVIEW_SCHEDULED,
        'current_stage' => 'final_interview'
    ]);

    // TODO: Send notification to candidate

    return redirect()->route('staff.recruitment.finalInterviews')
        ->with('success', 'Final interview scheduled successfully!');
}

public function complete(RecruitmentProcess $interview)
{
    $interview->update([
        'completed_at' => now()
    ]);

    $interview->application()->update([
        'status' => JobApplication::STATUS_FINAL_INTERVIEW_COMPLETED
    ]);

    return response()->json(['success' => true]);
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
            JobApplication::STATUS_EXAM_COMPLETED,  // Fixed: Changed from "STATUS" to "STATUS"
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
    return view('staff.recruitment.dashboard', [
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

// Show single application details

// Helper to determine which detail view to show based on status
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
}
