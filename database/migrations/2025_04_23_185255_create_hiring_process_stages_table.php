<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hiring_process_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
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
            $table->json('feedback')->nullable();
            $table->string('interviewer')->nullable();
            $table->string('result')->nullable(); // pass, fail, pending
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hiring_process_stages');
    }
};