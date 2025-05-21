<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('candidates', function (Blueprint $table) {
            // Add new fields
            
            $table->string('resume_path')->after('phone');
            $table->text('resume_text')->nullable()->after('resume_path');
            $table->json('resume_data')->nullable()->after('resume_text');
            $table->string('status')->default('applied')->change();// applied, initial_interview, demo, exam, 
            // final_interview, pre_employment, hired, rejected

            // Drop unwanted columns if needed
            $table->dropColumn(['staff_notes', 'admin_notes']); // optional
        });
    }

    public function down(): void {
        Schema::table('candidates', function (Blueprint $table) {
            // Reverse changes here
            $table->dropForeign(['job_id']);
            $table->dropColumn(['job_id', 'resume_path', 'resume_text', 'resume_data']);
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
            $table->text('staff_notes')->nullable();
            $table->text('admin_notes')->nullable();
        });
    }
};