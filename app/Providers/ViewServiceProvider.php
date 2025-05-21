<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\JobApplication;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $view->with('stats', [
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
            ]);
        });
    }
}
