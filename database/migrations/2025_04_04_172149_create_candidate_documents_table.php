<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('candidate_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->enum('type', [
                'resume',
                'nbi_clearance',
                'barangay_clearance',
                'police_clearance',
                'driver_license',
                'medical_certificate',
                'drug_test',
                'sss',
                'philhealth',
                'pagibig',
                'tin',
                'certificate_of_employment',
                'diploma',
                'transcript',
                'other'
            ]);
            $table->string('name');
            $table->string('file_path');
            $table->text('metadata')->nullable();
            $table->text('parsed_data')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('candidate_documents');
    }
};