<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->enum('status', [
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
            ])->default('new')->change();
        });
    }

    public function down()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->enum('status', [
                'new', 
                'under_review', 
                'license_verified', 
                'test_scheduled', 
                'test_completed', 
                'pending_approval', 
                'approved', 
                'rejected'
            ])->default('new')->change();
        });
    }
};