<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. First make the column nullable and string type
        Schema::table('job_applications', function ($table) {
            $table->string('status', 50)->nullable()->change();
        });

        // 2. Define all valid statuses
        $validStatuses = [
            'applied',
            'initial_interview_scheduled',
            'initial_interview_completed',
            'initial_interview_passed',
            'initial_interview_failed',
            'demo_scheduled',
            'demo_completed',
            'demo_passed',
            'demo_failed',
            'exam_scheduled',
            'exam_completed',
            'exam_passed',
            'exam_failed',
            'final_interview_scheduled',
            'final_interview_completed',
            'final_interview_passed',
            'final_interview_failed',
            'pre_employment',
            'hired',
            'rejected'
        ];

        // 3. Fix any invalid statuses
        DB::table('job_applications')
            ->whereNotIn('status', $validStatuses)
            ->update(['status' => 'applied']);

        // 4. Apply final constraints
        Schema::table('job_applications', function ($table) {
            $table->enum('status', [
                'applied',
                'initial_interview_scheduled',
                'initial_interview_completed',
                'initial_interview_passed',
                'initial_interview_failed',
                'demo_scheduled',
                'demo_completed',
                'demo_passed',
                'demo_failed',
                'exam_scheduled',
                'exam_completed',
                'exam_passed',
                'exam_failed',
                'final_interview_scheduled',
                'final_interview_completed',
                'final_interview_passed',
                'final_interview_failed',
                'hired',
                'rejected'
            ])->default('applied')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('job_applications', function ($table) {
            $table->string('status', 50)->nullable()->change();
        });
    }
};