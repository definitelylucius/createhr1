<?php



use App\Http\Controllers\AdminPController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicantController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Staff\OnboardingController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\DocumentGenerateController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CandidateTagController;
use App\Http\Controllers\Staff\FinalInterviewController;
use App\Http\Controllers\Admin\HiringDecisionController;
use App\Http\Controllers\HiringProcessController;
use App\Http\Controllers\OfferLetterController;
use App\Http\Controllers\PreEmploymentController;
use App\Http\Controllers\RecruitmentProcessController;
use App\Http\Controllers\ResumeParserController;
use App\Http\Controllers\ApplicantDocumentController;
use App\Http\Controllers\Admin\AdminApplicantController;
use App\Http\Controllers\AdminDController; 







// Remove any duplicate route definitions
// Remove any duplicate route definitions
Route::get('/', function () {
    return view('welcome');
})->name('home'); // This should be your only 'home' named route

// OR if you're using a controller for the home page:
Route::get('/', [JobController::class, 'index'])->name('home');

// 2FA Routes (should be accessible without auth)
Route::middleware('guest')->group(function () {
    Route::get('/two-factor-challenge', [AuthenticatedSessionController::class, 'showTwoFactorForm'])
        ->name('two-factor.challenge');
    
    Route::post('/two-factor-challenge', [AuthenticatedSessionController::class, 'verifyTwoFactor'])
        ->name('two-factor.verify');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';




// Show Registration Form
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');

// Handle Registration Form Submission
Route::post('/register', [RegisteredUserController::class, 'store']);


// Ensure the route is inside the auth middleware


// Authentication Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Role-Based Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/superadmin/dashboard', function () {
        return view('superadmin.dashboard');
    })->name('superadmin.dashboard');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

  
    Route::get('/employee/dashboard', function () {
        return view('employee.dashboard');
    })->name('employee.dashboard');

 
});


//SUPER ADMIN


Route::middleware(['auth'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');

    // ✅ User & Role Management Routes
    Route::get('/superadmin/users', [SuperAdminController::class, 'userManagement'])->name('superadmin.userManagement');
    
    // ✅ Corrected Route for Create User
    Route::get('/superadmin/users/create', [SuperAdminController::class, 'create'])->name('superadmin.createUser');

    // ✅ Store User (POST)
    Route::post('/superadmin/users/store', [SuperAdminController::class, 'store'])->name('superadmin.storeUser');

    // ✅ Edit User (GET)
    Route::get('/superadmin/users/{id}/edit', [SuperAdminController::class, 'editUser'])
    ->name('superadmin.editUser');
    Route::put('/superadmin/users/{id}', [SuperAdminController::class, 'updateUser'])
    ->name('superadmin.updateUser');

    // ✅ Delete User (DELETE)
    Route::delete('/superadmin/users/{id}', [SuperAdminController::class, 'destroy'])->name('superadmin.destroy');
});


//JOBS



Route::prefix('admin')->group(function () {
    Route::get('/jobs', [JobController::class, 'index'])->name('admin.jobs.index');
    Route::get('/jobs/create', [JobController::class, 'create'])->name('admin.jobs.create'); // Create Job Form
    Route::post('/jobs', [JobController::class, 'store'])->name('admin.jobs.store'); // Store Job
});

Route::get('/', [JobController::class, 'welcome']);


//ADMIN CONTROLLER





Route::prefix('admin')->group(function () {
    Route::get('/jobs', [JobController::class, 'index'])->name('admin.jobs.index');
   

});


Route::prefix('admin/jobs')->name('admin.jobs.')->group(function () {
    Route::get('/', [JobController::class, 'index'])->name('index'); // Show job listings
    Route::get('/create', [JobController::class, 'create'])->name('create'); // Show create job form
    Route::post('/store', [JobController::class, 'store'])->name('store'); // Store job

    Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->name('admin.jobs.edit');
    Route::put('/{job}', [JobController::class, 'update'])->name('admin.jobs.update'); // Update job (PUT request)
    Route::delete('/{job}', [JobController::class, 'destroy'])->name('admin.jobs.destroy'); // Delete job (DELETE request)

    Route::get('{job}/edit', [JobController::class, 'edit'])->name('admin.jobs.edit');
    Route::get('admin/jobs/{job}/edit', [JobController::class, 'edit'])->name('admin.jobs.edit');

});


Route::get('/admin/jobs/manage', [JobController::class, 'manage'])->name('admin.jobs.manage');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('jobs', JobController::class);

});
Route::prefix('admin/jobs')->name('admin.jobs.')->group(function () {
    Route::get('/manage', [JobController::class, 'manage'])->name('manage');
    Route::get('/{job}/edit', [JobController::class, 'edit'])->name('edit');
    Route::post('/admin/jobs/{id}', [JobController::class, 'update']);  // Use POST instead of PUT for FormData
Route::delete('/admin/jobs/{id}', [JobController::class, 'destroy']);
});

Route::put('/admin/job/{id}', [JobController::class, 'update'])->name('admin.job.update');




Route::middleware(['auth'])->group(function () {
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])
    ->name('applications.store');

    Route::get('/apply', [JobController::class, 'showApplicationForm'])->name('apply.form');
    Route::get('/apply/{job}', [JobController::class, 'showApplicationForm'])->name('apply.form');

    Route::get('/apply/{jobId}', [ApplicantController::class, 'showApplicationForm'])->name('apply.form');
    Route::post('/submit-application/{jobId}', [ApplicantController::class, 'submitApplication'])->name('submit.application');
    Route::get('/application-success/{applicationId}', function ($applicationId) {
        return view('welcome'); // or the view you want to show
    })->name('application.success');
    
   
    
});

Route::middleware(['auth', 'role:applicant'])->group(function () {
    // Show the upload form for a specific application
   // Show the upload form for a specific application
   Route::get('/applicant/documents/{applicationId}/upload', [ApplicantDocumentController::class, 'showUploadForm'])
   ->name('applicant.documents.upload');

// Handle the document upload for a specific application
Route::post('/applicant/documents/{applicationId}/upload', [ApplicantDocumentController::class, 'uploadDocuments'])
   ->name('applicant.documents.upload.submit');

   Route::get('/applicant/documents/{applicationId}/list', [ApplicantDocumentController::class, 'listDocuments'])
     ->name('applicant.documents.list');
});







  




//DOCUMENT GENEERATE
Route::get('/generate-nda/{id}', [DocumentGenerateController::class, 'generateNDA'])->name('generate.nda');

Route::get('/generate/onboarding-letter/{id}', [DocumentGenerateController::class, 'generateOnboardingLetter'])
    ->name('generate.onboarding');
Route::get('/generate/hiring-contract/{id}', [DocumentGenerateController::class, 'generateHiringContract'])->name('generate.hiring');











Route::get('/test-resume-path', function() {
    $testFile = 'job_1/test.pdf';
    return [
        'absolute_path' => rtrim(config('filesystems.disks.candidate_documents.root'), DIRECTORY_SEPARATOR) 
                          . DIRECTORY_SEPARATOR 
                          . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim($testFile, '/')),
        'storage_root' => config('filesystems.disks.candidate_documents.root'),
        'system_separator' => DIRECTORY_SEPARATOR
    ];
});



Route::get('/staff/recruitment/dashboard', [RecruitmentProcessController::class, 'index'])
    ->middleware(['auth', 'role:staff'])
    ->name('staff.recruitment.dashboard');

Route::middleware(['auth', 'role:staff'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
   
  // Applicants
  Route::get('/recruitment/applicants', [ApplicationController::class, 'viewApplications'])
  ->name('recruitment.applicants');
  Route::get('/recruitment/applicants/{id}', [ApplicationController::class, 'showApplication'])->name('applicants.show');
  Route::post('/applicants/{id}/status', [ApplicationController::class, 'updateStatus'])
  ->name('applicants.status'); 


 // For showing the scheduling form
 Route::get('/recruitment/applications/{application}/schedule_initial_interview', 
 [RecruitmentProcessController::class, 'showScheduleForm'])
 ->name('recruitment.scheduleInitialInterview');
 
// For processing the schedule submission
Route::post('/recruitment/applications/{application}/schedule_initial_interview', 
 [RecruitmentProcessController::class, 'initialInterview'])
 ->name('recruitment.storeInitialInterview');
 Route::get('/recruitment/completed-interviews', [RecruitmentProcessController::class, 'completedInterviews'])
 ->name('staff.recruitment.completed_interviews');

 Route::post('/recruitment/applications/{application}/mark-passed', 
 [RecruitmentProcessController::class, 'markPassed'])
 ->name('recruitment.mark_passed');

 Route::put('/recruitment/applications/{application}/reschedule', 
    [RecruitmentProcessController::class, 'rescheduleInterview'])
    ->name('recruitment.reschedule_interview');

    

    
    
   


  
        // Recruitment Process Views
        Route::get('/recruitment/initial_interviews', [RecruitmentProcessController::class, 'initialInterviews'])
    ->name('recruitment.initial_interviews');
        Route::get('/recruitment/demos', [RecruitmentProcessController::class, 'demos'])
             ->name('recruitment.demos');
        Route::get('/recruitment/exams', [RecruitmentProcessController::class, 'exams'])
             ->name('recruitment.exams');
        Route::get('/recruitment/final_interviews', [RecruitmentProcessController::class, 'finalInterviews'])
             ->name('recruitment.final_interviews');
        Route::get('/recruitment/pre_employment', [PreEmploymentController::class, 'index'])
             ->name('recruitment.pre_employment');
        Route::get('/recruitment/onboarding', [OnboardingController::class, 'index'])
             ->name('recruitment.onboarding');

             
        
      
    });
    Route::middleware(['auth', 'role:staff'])
    ->prefix('staff')
    ->group(function () {
   
        // Recruitment routes
        Route::prefix('recruitment')->group(function() {
            // ... your existing routes ...

            // Demo routes - corrected naming
            Route::get('/demos/schedule/{application}', [RecruitmentProcessController::class, 'showScheduleDemo'])
                ->name('staff.recruitment.scheduleDemo'); // This matches what you're calling in views

            Route::post('/demos/schedule/{application}', [RecruitmentProcessController::class, 'storeDemo'])
                ->name('staff.recruitment.storeDemo');

            
                Route::post('/recruitment/demos/complete/{application}', 
    [RecruitmentProcessController::class, 'completeDemo'])
    ->name('staff.recruitment.completeDemo');
        });
    });

    // Add these routes to your web.php file
Route::prefix('staff/recruitment')->group(function() {
    // Exams routes
    Route::get('/exams', [RecruitmentProcessController::class, 'exams'])->name('staff.recruitment.exams');
    Route::get('/exams/schedule', [RecruitmentProcessController::class, 'scheduleExam'])->name('staff.recruitment.scheduleExam');
    Route::post('/exams', [RecruitmentProcessController::class, 'storeExam'])->name('staff.recruitment.storeExam');
    Route::put('/exams/{exam}/complete', [RecruitmentProcessController::class, 'completeExam'])->name('staff.recruitment.completeExam');
});

    Route::match(['get', 'post'], '/recruitment/demos/complete/{application}', [RecruitmentProcessController::class, 'completeDemo'])
    ->name('staff.recruitment.completeDemo');
// For scheduling demos
Route::get('/staff/recruitment/demos/schedule/{application}', 
    [RecruitmentProcessController::class, 'showScheduleDemo'])
    ->name('staff.recruitment.scheduleDemo');
    
  
    Route::prefix('staff/recruitment')->middleware(['auth', 'role:staff'])->group(function () {
        // Final Interviews
        Route::get('/final-interviews', [RecruitmentProcessController::class, 'finalInterviews'])->name('staff.recruitment.finalInterviews');
        Route::get('/schedule-final-interview', [RecruitmentProcessController::class, 'showScheduleFinalInterview'])->name('staff.recruitment.scheduleFinalInterview');
        Route::post('/schedule-final-interview/{application}', [RecruitmentProcessController::class, 'scheduleFinalInterview'])->name('staff.recruitment.storeFinalInterview');
        
        Route::post('/recruitment/complete-final-interview/{process}', [RecruitmentProcessController::class, 'completeFinalInterview'])->name('staff.recruitment.completeFinalInterview');
        Route::put('/staff/recruitment/final-interviews/{interview}/result', 
    [RecruitmentProcessController::class, 'updateResult'])
    ->name('staff.recruitment.final-interviews.update-result');


    });

   // Pre-employment main routes group
Route::prefix('staff/recruitment/pre-employment')->name('staff.recruitment.pre-employment.')->group(function() {
    Route::get('/', [PreEmploymentController::class, 'index'])->name('index');
    Route::post('/{application}/request-documents', [PreEmploymentController::class, 'requestDocuments'])->name('request-documents');
    Route::post('/{application}/verify/{documentType}', [PreEmploymentController::class, 'verifyDocument'])->name('verify-document');
    
    // Add the schedule routes inside the group to maintain consistency
    Route::get('/schedule', [PreEmploymentController::class, 'showScheduleForm'])
        ->name('schedule');
    Route::post('/schedule', [PreEmploymentController::class, 'schedule'])
        ->name('schedule.store');
});



// Use consistent parameter naming (preferably {application} since it's more descriptive)
Route::prefix('staff/recruitment/pre-employment')->name('staff.recruitment.pre-employment.')->group(function () {
    // Document routes
    Route::get('{application}/documents', [PreEmploymentController::class, 'showDocuments'])
        ->name('documents');
        
    Route::post('{application}/request-documents', [PreEmploymentController::class, 'requestDocuments'])
        ->name('request-documents');

    // Update routes
    Route::put('{application}/update-drug-test', [PreEmploymentController::class, 'updateDrugTest'])
        ->name('update-drug-test');
        
    Route::put('{application}/medical-exam', [PreEmploymentController::class, 'updateMedicalExam'])
        ->name('update-medical-exam');

    Route::put('{application}/update-reference-check', [PreEmploymentController::class, 'updateReferenceCheck'])
        ->name('update-reference-check');
});




// Onboarding Completion - Admin only
Route::prefix('onboarding')->middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/{applicationId}/complete', [OnboardingController::class, 'completeOnboarding'])->name('onboarding.complete');
});

// Employee Routes
Route::prefix('employee')->middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'employee'])->name('employee.dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('employee.profile');
    Route::get('/documents', [DashboardController::class, 'documents'])->name('employee.documents');
    Route::get('/schedule', [DashboardController::class, 'schedule'])->name('employee.schedule');
    Route::get('/training', [DashboardController::class, 'training'])->name('employee.training');
    Route::get('/policies', [DashboardController::class, 'policies'])->name('employee.policies');
    Route::get('/requests', [DashboardController::class, 'requests'])->name('employee.requests');
}); 

    // Tools
    Route::prefix('tools')->middleware(['auth', 'role:staff,admin'])->group(function () {
       
        Route::get('/resume-parser', [ResumeParserController::class, 'showParser'])
        ->name('tools.resume-parser');
    Route::post('/resume-parser', [ResumeParserController::class, 'parseResume'])
        ->name('tools.parse-resume');
    Route::post('/applicants/store', [ResumeParserController::class, 'store'])
        ->name('applicants.store');
    });


    



    Route::prefix('onboarding')->group(function() {
        Route::get('/{application}', [OnboardingController::class, 'show'])->name('staff.onboarding.show');
        Route::post('/{application}/complete', [OnboardingController::class, 'complete'])->name('staff.onboarding.complete');
        Route::post('/{application}/upload/{documentType}', [OnboardingController::class, 'uploadDocument'])->name('staff.onboarding.upload');
        
        // Document collection routes
        Route::get('/documents', [OnboardingController::class, 'documents'])->name('staff.onboarding.documents');
        Route::post('/tasks/assign', [OnboardingController::class, 'assignTask'])->name('staff.onboarding.assign-task');
        
        // Video routes
        Route::get('/videos/upload', [OnboardingController::class, 'showUploadForm'])->name('staff.onboarding.videos.upload');
        Route::post('/videos/upload', [OnboardingController::class, 'uploadVideo'])->name('staff.onboarding.videos.store');
    });



    Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDController::class, 'index'])->name('dashboard');
    
    
    });
    Route::get('/admin/applications/{id}/view-resume', [AdminDController::class, 'viewResume'])
    ->name('admin.applications.view-resume');



    // Admin Recruitment Dashboard
    Route::get('/admin/recruitment/dashboard', [AdminDController::class, 'index'])
        ->middleware(['auth', 'role:admin'])
        ->name('admin.recruitment.dashboard');
    
    // Grouped Admin Routes
    Route::middleware(['auth', 'role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
    
        // Applicants
        Route::get('/recruitment/applicants', [AdminDController::class, 'viewApplications'])->name('recruitment.applicants');
        Route::get('/recruitment/applicants/{id}', [AdminDController::class, 'showApplication'])->name('applicants.show');
        Route::post('/applicants/{id}/status', [AdminDController::class, 'updateStatus'])->name('applicants.status');
    
        // Initial Interview
        Route::get('/recruitment/applications/{application}/schedule_initial_interview', [AdminDController::class, 'showScheduleForm'])->name('recruitment.scheduleInitialInterview');
        Route::post('/recruitment/applications/{application}/schedule_initial_interview', [AdminDController::class, 'initialInterview'])->name('recruitment.storeInitialInterview');
        Route::get('/recruitment/completed-interviews', [AdminDController::class, 'completedInterviews'])->name('recruitment.completed_interviews');
        Route::post('/recruitment/applications/{application}/mark-passed', [AdminDController::class, 'markPassed'])->name('recruitment.mark_passed');
        Route::put('/recruitment/applications/{application}/reschedule', [AdminDController::class, 'rescheduleInterview'])->name('recruitment.reschedule_interview');
    
        // Recruitment Phases
        Route::get('/recruitment/initial_interviews', [AdminDController::class, 'initialInterviews'])->name('recruitment.initial_interviews');
        Route::get('/recruitment/demos', [AdminDController::class, 'demos'])->name('recruitment.demos');
        Route::get('/recruitment/exams', [AdminDController::class, 'exams'])->name('recruitment.exams');
        Route::get('/recruitment/final_interviews', [AdminDController::class, 'finalInterviews'])->name('recruitment.final_interviews');
        Route::get('/recruitment/pre_employment', [AdminPController::class, 'index'])->name('recruitment.pre_employment');
        Route::get('/recruitment/onboarding', [AdminDController::class, 'onboarding'])->name('recruitment.onboarding');
    
        // Demos
        Route::get('/recruitment/demos/schedule/{application}', [AdminDController::class, 'showScheduleDemo'])->name('recruitment.scheduleDemo');
        Route::post('/recruitment/demos/schedule/{application}', [AdminDController::class, 'storeDemo'])->name('recruitment.storeDemo');
        Route::post('/recruitment/demos/complete/{application}', [AdminDController::class, 'completeDemo'])->name('recruitment.completeDemo');
    
        // Exams
        Route::get('/recruitment/exams/schedule', [AdminDController::class, 'scheduleExam'])->name('recruitment.scheduleExam');
        Route::post('/recruitment/exams', [AdminDController::class, 'storeExam'])->name('recruitment.storeExam');
        Route::put('/recruitment/exams/{exam}/complete', [AdminDController::class, 'completeExam'])->name('recruitment.completeExam');
    
        // Final Interviews
        Route::get('/recruitment/final-interviews', [AdminDController::class, 'finalInterviews'])->name('recruitment.finalInterviews');
        Route::get('/recruitment/schedule-final-interview', [AdminDController::class, 'showScheduleFinalInterview'])->name('recruitment.scheduleFinalInterview');
        Route::post('/recruitment/schedule-final-interview/{application}', [AdminDController::class, 'scheduleFinalInterview'])->name('recruitment.storeFinalInterview');
        Route::post('/recruitment/complete-final-interview/{process}', [AdminDController::class, 'completeFinalInterview'])->name('recruitment.completeFinalInterview');
        Route::put('/recruitment/final-interviews/{interview}/result', [AdminDController::class, 'updateResult'])->name('recruitment.final-interviews.update-result');
    
    });


  // Pre-employment main routes group
Route::prefix('admin/recruitment/pre-employment')->name('admin.recruitment.pre-employment.')->group(function() {
    Route::get('/', [AdminPController::class, 'index'])->name('index');
    Route::post('/{application}/request-documents', [AdminPController::class, 'requestDocuments'])->name('request-documents');
    Route::post('/{application}/verify/{documentType}', [AdminPController::class, 'verifyDocument'])->name('verify-document');
    
    // Add the schedule routes inside the group to maintain consistency
    Route::get('/schedule', [AdminPController::class, 'showScheduleForm'])
        ->name('schedule');
    Route::post('/schedule', [AdminPController::class, 'schedule'])
        ->name('schedule.store');
});



// Use consistent parameter naming (preferably {application} since it's more descriptive)
Route::prefix('admin/recruitment/pre-employment')->name('admin.recruitment.pre-employment.')->group(function () {
    // Document routes
    Route::get('{application}/documents', [AdminPController::class, 'showDocuments'])
        ->name('documents');
        
    Route::post('{application}/request-documents', [AdminPController::class, 'requestDocuments'])
        ->name('request-documents');

    // Update routes
    Route::put('{application}/update-drug-test', [AdminPController::class, 'updateDrugTest'])
        ->name('update-drug-test');
        
    Route::put('{application}/medical-exam', [AdminPController::class, 'updateMedicalExam'])
        ->name('update-medical-exam');

    Route::put('{application}/update-reference-check', [AdminPController::class, 'updateReferenceCheck'])
        ->name('update-reference-check');

    
});

// Offer Letter Routes
Route::prefix('admin/recruitment')->group(function () {

    Route::get('/offer-letter/prepare/{applicationId}', [OfferLetterController::class, 'prepare'])
    ->name('offer-letter.prepare');
    // Prepare offer letter form
   // Offer Letter routes
Route::prefix('offer-letter')->group(function () {
    // Preparation and generation
    
    
    Route::post('/generate/{application}', [OfferLetterController::class, 'generate'])
        ->name('offer-letter.generate');
    
    // Sending and viewing
    Route::post('/send/{application}', [OfferLetterController::class, 'send'])
        ->name('offer-letter.send');
    
    Route::get('/view/{application}', [OfferLetterController::class, 'view'])
        ->name('offer-letter.view');
    
    // Signature handling
    Route::get('/sign/{application}', [OfferLetterController::class, 'showSignaturePage'])
        ->name('offer-letter.sign');
    
    Route::post('/collect-signature/{application}', [OfferLetterController::class, 'collectSignature'])
        ->name('offer-letter.collectSignature');
    
    // Alternative signature route
    Route::post('/process-signature/{application}', [OfferLetterController::class, 'processSignature'])
        ->name('offer-letter.processSignature');
    
    // Status tracking
    Route::get('/track', [OfferLetterController::class, 'track'])
        ->name('offer-letter.track');
});
});