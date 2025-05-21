<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Job;
use Illuminate\Support\Facades\View;


use Illuminate\Support\Facades\Auth;
use App\Models\JobApplication;


use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Illuminate\Foundation\Application;

use Illuminate\Routing\Middleware\SubstituteBindings;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Middleware Here
    
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        View::composer('*', function ($view) {
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
    
            $view->with('stats', $stats);
        });

        

      
        View::share('jobs', Job::all());

       
    }
}
