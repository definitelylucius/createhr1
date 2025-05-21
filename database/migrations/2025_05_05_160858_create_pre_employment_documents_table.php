<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pre_employment_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained()->cascadeOnDelete();
            
            // Scheduling information
            $table->dateTime('scheduled_date')->nullable();
            $table->string('activity_type')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            
            // Document status fields
            $table->string('nbi_clearance')->nullable();
            $table->boolean('nbi_clearance_verified')->default(false);
            $table->date('nbi_clearance_expiry')->nullable();
            $table->string('nbi_clearance_path')->nullable(); // New

            $table->string('police_clearance')->nullable();
            $table->boolean('police_clearance_verified')->default(false);
            $table->date('police_clearance_expiry')->nullable();
            $table->string('police_clearance_path')->nullable(); // New

            $table->string('barangay_clearance')->nullable();
            $table->boolean('barangay_clearance_verified')->default(false);
            $table->date('barangay_clearance_expiry')->nullable();
            $table->string('barangay_clearance_path')->nullable(); // New

            $table->string('coe')->nullable();
            $table->boolean('coe_verified')->default(false);
            $table->string('coe_path')->nullable(); // New

            $table->string('drivers_license')->nullable();
            $table->boolean('drivers_license_verified')->default(false);
            $table->date('drivers_license_expiry')->nullable();
            $table->string('drivers_license_path')->nullable(); // New

            // Other checks
            $table->text('reference_check_notes')->nullable();
            $table->boolean('reference_check_verified')->default(false);

            $table->string('drug_test_result')->nullable();
            $table->date('drug_test_date')->nullable();
            $table->boolean('drug_test_verified')->default(false);
            $table->string('drug_test_path')->nullable(); // New

            // Medical examination
            $table->string('medical_exam_result')->nullable();
            $table->date('medical_exam_date')->nullable();
            $table->boolean('medical_exam_verified')->default(false);
            $table->string('medical_exam_path')->nullable(); // New

            // Document request fields
            $table->text('document_request_message')->nullable();
            $table->date('document_request_deadline')->nullable();
            $table->json('requested_documents')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pre_employment_documents');
    }
};
