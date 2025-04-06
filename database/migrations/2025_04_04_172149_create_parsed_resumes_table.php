<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parsed_resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('candidate_documents')->onDelete('cascade');
            $table->json('skills')->nullable();
            $table->integer('experience_years')->nullable();
            $table->text('education')->nullable();
            $table->json('job_history')->nullable();
            $table->text('raw_data')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parsed_resumes');
    }
};
