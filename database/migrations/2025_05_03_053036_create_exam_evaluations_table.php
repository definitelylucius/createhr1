<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('exam_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('job_applications')->cascadeOnDelete();
            $table->foreignId('evaluator_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('score', 5, 2);
            $table->boolean('passed');
            $table->json('criteria_scores')->nullable(); // For detailed breakdown
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('completed_at');
            $table->timestamps();
            
            $table->index(['application_id', 'evaluator_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_evaluations');
    }
};
