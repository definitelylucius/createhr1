<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE candidates 
            MODIFY COLUMN status ENUM(
                'new',
                'under_review',
                'license_verified',
                'test_scheduled',
                'test_completed',
                'pending_approval',
                'approved',
                'rejected',
                'final_interview_scheduled',
                'final_interview_completed',
                'ready_for_hire',  
                'hired'
            ) DEFAULT 'new'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE candidates 
            MODIFY COLUMN status ENUM(
                'new',
                'under_review',
                'license_verified',
                'test_scheduled',
                'test_completed',
                'pending_approval',
                'approved',
                'rejected',
                'final_interview_scheduled',
                'final_interview_completed',
                'hired'
            ) DEFAULT 'new'");
    }
};