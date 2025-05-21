<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentProcessTable extends Migration
{
    public function up()
    {
        Schema::create('recruitment_process', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('job_applications');
            $table->enum('stage', [
                'initial_interview',
                'demo',
                'exam',
                'final_interview',
                'pre_employment',
                'hired',
                'onboarding'
            ]);
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('interviewer')->nullable();
            $table->string('location')->nullable();
            $table->string('meeting_link')->nullable();
            $table->boolean('passed')->nullable();
            $table->timestamps();
        });

     

        Schema::create('offer_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('job_applications');
            $table->string('file_path');
            $table->string('signature_path')->nullable();
            $table->dateTime('sent_at');
            $table->dateTime('signed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('onboarding_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('job_applications');
            $table->string('employment_contract')->nullable();
            $table->string('tax_forms')->nullable();
            $table->string('company_policies')->nullable();
            $table->string('training_materials')->nullable();
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('onboarding_documents');
        Schema::dropIfExists('offer_letters');
      
        Schema::dropIfExists('recruitment_process');
    }
}