<?php



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
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CandidateTagController;
use App\Http\Controllers\Staff\FinalInterviewController;
use App\Http\Controllers\Admin\HiringDecisionController;
use App\Http\Controllers\TwoFactorController;






Route::get('/', [JobController::class, 'index'])->name('home');

Route::get('/', function () {
    return view('welcome');
})->name('home');





Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


// Protect routes with authentication middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


// Show Registration Form
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');

// Handle Registration Form Submission
Route::post('/register', [RegisteredUserController::class, 'store']);


// Ensure the route is inside the auth middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/applicant/dashboard', [ApplicantController::class, 'dashboard'])
        ->name('applicant.dashboard');
});

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

    Route::get('/staff/dashboard', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');

    Route::get('/employee/dashboard', function () {
        return view('employee.dashboard');
    })->name('employee.dashboard');

    Route::get('/applicant/dashboard', function () {
        return view('applicant.dashboard');
    })->name('applicant.dashboard');
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
    Route::get('/superadmin/users/{id}/edit', [SuperAdminController::class, 'editUser'])->name('superadmin.editUser');

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
    Route::get('/apply', [JobController::class, 'showApplicationForm'])->name('apply.form');
    Route::get('/apply/{job}', [JobController::class, 'showApplicationForm'])->name('apply.form');

    Route::get('/apply/{jobId}', [ApplicantController::class, 'showApplicationForm'])->name('apply.form');
    Route::post('/submit-application/{jobId}', [ApplicantController::class, 'submitApplication'])->name('submit.application');

    Route::post('/apply', [ApplicationController::class, 'store']);
    Route::post('/submit-application', [ApplicationController::class, 'store'])->name('submit.application');
});






    Route::middleware(['auth', RoleMiddleware::class . ':staff'])->group(function () {

        Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

        Route::get('/staff/dashboard', [StaffDashboardController::class, 'trackApplications'])
        ->name('staff.dashboard');
    });
    

     Route::middleware(['auth'])->group(function () {
        Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');
    });





//DOCUMENT GENEERATE
Route::get('/generate-nda/{id}', [DocumentGenerateController::class, 'generateNDA'])->name('generate.nda');

Route::get('/generate/onboarding-letter/{id}', [DocumentGenerateController::class, 'generateOnboardingLetter'])
    ->name('generate.onboarding');
Route::get('/generate/hiring-contract/{id}', [DocumentGenerateController::class, 'generateHiringContract'])->name('generate.hiring');










///CAANDIDATE
Route::post('/candidates/store', [CandidateController::class, 'store'])->name('candidates.store');
Route::get('/application-success/{id}', [CandidateController::class, 'success'])->name('application.success');

// Staff dashboard routes (protected by auth middleware)
// Staff dashboard routes (protected by auth middleware)
Route::middleware(['auth'])->prefix('staff')->name('staff.')->group(function() {
    // Candidate management
    Route::prefix('candidates')->name('candidates.')->group(function() {
        Route::get('/', [CandidateController::class, 'index'])->name('index');
        Route::get('/{candidate}', [CandidateController::class, 'show'])->name('show');
        
        // Tags
        Route::post('/{candidate}/add-tag', [CandidateController::class, 'addTag'])->name('add-tag');
        Route::delete('/{candidate}/remove-tag/{tag}', [CandidateController::class, 'removeTag'])->name('remove-tag');
        
        // License verification
        Route::post('/{candidate}/verify-license', [CandidateController::class, 'verifyLicense'])->name('verify-license');
        
        // Tests
        Route::post('/{candidate}/schedule-test', [CandidateController::class, 'scheduleTest'])->name('schedule-test');
        Route::post('/tests/{test}/record', [CandidateController::class, 'recordTestResult'])->name('tests.record');
        
        // Status updates
        Route::patch('/{candidate}/update-status/{status}', [CandidateController::class, 'updateStatus'])->name('update-status');
        
        // Notes
        Route::patch('/{candidate}/update-notes', [CandidateController::class, 'updateNotes'])->name('update-notes');
    });
    
  

    
    // Tags management - using resource controller with staff prefix
    Route::resource('tags', CandidateTagController::class)->names([
        'index' => 'tags.index',
        'create' => 'tags.create',
        'store' => 'tags.store',
        'edit' => 'tags.edit',
        'update' => 'tags.update',
        'destroy' => 'tags.destroy'
    ]);



});
Route::prefix('staff')->name('staff.')->group(function () {
    Route::post('/candidates/{candidate}/documents', [CandidateController::class, 'storeDocument'])
        ->name('candidates.documents.store');
        
    Route::get('/candidates/{candidate}/documents/{document}/download', 
        [CandidateController::class, 'downloadDocument'])
        ->name('candidates.documents.download');
        
    Route::delete('/candidates/{candidate}/documents/{document}', 
        [CandidateController::class, 'deleteDocument'])
        ->name('candidates.documents.destroy');

          Route::post('/tests/{test}/record', [CandidateController::class, 'recordTestResult'])
        ->name('tests.record');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function() {
    Route::prefix('candidates')->name('candidates.')->group(function() {
        // Approval queue listing
        Route::get('/', [AdminController::class, 'candidates'])->name('index');
        Route::get('/approval-queue', [AdminController::class, 'candidates'])->name('approvalQueue');
        
        // Single candidate operations
        Route::get('/{candidate}', [AdminController::class, 'reviewCandidate'])->name('show');
        Route::get('/{candidate}/review', [AdminController::class, 'reviewCandidate'])->name('review');
        
        // Candidate status changes
        Route::post('/{candidate}/approve', [AdminController::class, 'approveCandidate'])->name('approve');
        Route::post('/{candidate}/reject', [AdminController::class, 'rejectCandidate'])->name('reject');
    });
});


// Staff Routes
Route::middleware(['auth' ])->prefix('staff')->name('staff.')->group(function() {
    // Dashboard
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])
    ->name('dashboard');
    
   // Final Interviews Routes
   Route::prefix('final-interviews')->name('final-interviews.')->group(function () {
    Route::get('/', [FinalInterviewController::class, 'index'])->name('index');
    Route::get('/select-candidate', [FinalInterviewController::class, 'selectCandidate'])->name('select-candidate');
    Route::get('/create/{candidate}', [FinalInterviewController::class, 'create'])->name('create');
    Route::post('/store/{candidate}', [FinalInterviewController::class, 'store'])->name('store');
    Route::get('/{interview}', [FinalInterviewController::class, 'show'])->name('show');
    Route::put('/{interview}/complete', [FinalInterviewController::class, 'complete'])->name('complete');
});
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function() {
    // Dashboard
  Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // Hiring Decisions
    Route::get('/hiring-decisions', [HiringDecisionController::class, 'index'])->name('hiring-decisions.index');
    Route::get('/hiring-decisions/create', [HiringDecisionController::class, 'create'])->name('hiring-decisions.create');
    Route::post('/hiring-decisions', [HiringDecisionController::class, 'store'])->name('hiring-decisions.store');
   
    Route::get('/hiring-decisions/{decision}/edit', [HiringDecisionController::class, 'edit'])->name('hiring-decisions.edit');
    Route::put('/hiring-decisions/{decision}', [HiringDecisionController::class, 'update'])->name('hiring-decisions.update');
    Route::delete('/hiring-decisions/{decision}', [HiringDecisionController::class, 'destroy'])->name('hiring-decisions.destroy');
    
        
    
        Route::get('/hiring-decisions/ready', [HiringDecisionController::class, 'readyForHire'])
        ->name('hiring-decisions.ready');
   
        
        Route::get('hiring-decisions/{decision}', [HiringDecisionController::class, 'show'])
        ->name('hiring-decisions.show');
});

// Employee Routes
Route::middleware(['auth'])->prefix('employee')->name('employee.')->group(function() {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('employee.dashboard');
    })->name('dashboard');
    
    // Onboarding
    Route::get('/onboarding', [OnboardingController::class, 'dashboard'])
        ->name('onboarding.dashboard');
    
    Route::post('/onboarding/documents', [OnboardingController::class, 'uploadDocument'])
        ->name('onboarding.upload-document');
    
    Route::delete('/onboarding/documents/{document}', [OnboardingController::class, 'deleteDocument'])
        ->name('onboarding.delete-document');
    
    Route::post('/onboarding/profile', [OnboardingController::class, 'updateProfile'])
        ->name('onboarding.update-profile');
    
    Route::post('/onboarding/tasks/{task}/complete', [OnboardingController::class, 'completeTask'])
        ->name('onboarding.complete-task');
});


