<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\RecruitmentProcess;
use App\Models\PreEmploymentDocument;
use App\Models\OfferLetter;
use App\Models\OnboardingDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    const STATUS_PRE_EMPLOYMENT_COMPLETED = 'pre_employment_completed';

    protected $preEmploymentDocument;
    protected $status;

    public function __construct()
    {
        $this->preEmploymentDocument = null;
    }

    // Store new application
    public function store(Request $request, $jobId)
    {
     

        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'address' => 'nullable|string|max:255', // Added address validation
        ]);

        

        try {
          
            $job = Job::findOrFail($jobId);
         

            // Store resume
            try {
                $originalName = $request->resume->getClientOriginalName();
                $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
                $extension = $request->resume->getClientOriginalExtension();
                $resumeName = $safeName.'_'.time().'.'.$extension;
                
              

                $resumePath = $request->file('resume')->storeAs(
                    'resumes', 
                    $resumeName, 
                    'public'
                );
                
             
            } catch (\Exception $e) {
             
                throw $e;
            }

            // Prepare application data
            $applicationData = [
                'job_id' => $jobId,
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null, // Fixed undefined variable
                'resume_path' => $resumePath,
                'status' => JobApplication::STATUS_APPLIED, // Changed from 'submitted' to use constant
                'current_stage' => 'application'
            ];

            if (auth()->check()) {
                $applicationData['user_id'] = auth()->id();
             
            }

           

            $application = JobApplication::create($applicationData);
     
            return redirect()->route('application.success', $application->id)
                ->with('success', 'Application submitted successfully!');

        } catch (\Exception $e) {
           

            return back()
                ->withInput()
                ->with('error', 'Application failed. Please check the logs for details.');
        }
    }

    // View all applicants
    public function viewApplications()
    {
        $applicants = JobApplication::paginate(5);
        
        // Get totals for each category
        $totalApplicants = JobApplication::count();
        $totalInitialInterviews = JobApplication::where('current_stage', 'Initial Interview')->count();
        $totalInterviews = JobApplication::where('current_stage', 'Interview')->count();
        $totalOffers = JobApplication::where('current_stage', 'Offer')->count();
        $totalHires = JobApplication::where('current_stage', 'Hired')->count();
    
        return view('staff.recruitment.applicants', compact(
            'applicants', 
            'totalApplicants', 
            'totalInitialInterviews', 
            'totalInterviews', 
            'totalOffers', 
            'totalHires'
        ));
    }

    // Show details of a single applicant
    public function showApplication($id)
    {
        $applicant = JobApplication::findOrFail($id);

        $totalApplications = JobApplication::where('current_stage', 'Application')->count();
        $totalInitialInterviews = JobApplication::where('current_stage', 'Initial Interview')->count();
        $totalInterviews = JobApplication::where('current_stage', 'Interview')->count();
        $totalOffers = JobApplication::where('current_stage', 'Offer')->count();
        $totalHires = JobApplication::where('current_stage', 'Hired')->count();

        return view('staff.recruitment.initial_interviews', compact(
            'applicant',
            'totalApplications',
            'totalInitialInterviews',
            'totalInterviews',
            'totalOffers',
            'totalHires'
        ));
    }

    // View single application
    public function show($id)
    {
        $application = JobApplication::with([
            'job',
            'recruitmentProcesses',
            'preEmploymentDocuments',
            'offerLetter',
            'onboardingDocuments'
        ])->findOrFail($id);

        return view('staff.recruitment.applicants.show', compact('application'));
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
                ->route('staff.recruitment.scheduleInitialInterview', $id)
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

    // Download resume
    public function downloadResume($id)
    {
        $application = JobApplication::findOrFail($id);
        return Storage::download($application->resume_path);
    }

    // View all applications
    public function index()
    {
        $applications = JobApplication::with('job')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('applications.index', compact('applications'));
    }

    // Application success page
    public function success($id)
    {
        $application = JobApplication::findOrFail($id);
        return view('applications.success', compact('application'));
    }

    // Store resume file with unique name
    private function storeResume($file, $jobId, $userId)
    {
        $fileName = 'resume_' . $jobId . '_' . $userId . '_' . time() . '.' . $file->extension();
        return $file->storeAs('resumes', $fileName, 'public');
    }

    public function preEmploymentStatus()
    {
        if (!$this->preEmploymentDocument) {
            return 'not-started';
        }
        
        if ($this->status === self::STATUS_PRE_EMPLOYMENT_COMPLETED) {
            return 'completed';
        }
        
        if ($this->preEmploymentDocument->allVerified()) {
            return 'documents-completed';
        }
        
        if ($this->preEmploymentDocument->scheduled_date) {
            return 'in-progress';
        }
        
        return 'pending';
    }
}
