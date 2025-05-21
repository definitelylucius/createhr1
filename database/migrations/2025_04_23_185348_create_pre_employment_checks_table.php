<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pre_employment_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->enum('type', [
                'nbi_clearance',
                'barangay_clearance',
                'police_clearance',
                'license_verification',
                'reference_check',
                'employment_verification',
                'drug_test',
                'medical_exam',
                'sss_verification',
                'philhealth_verification',
                'pagibig_verification',
                'tin_verification',
                'education_verification'
            ]);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed'])->default('pending');
            $table->date('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_employment_checks');
    }
};