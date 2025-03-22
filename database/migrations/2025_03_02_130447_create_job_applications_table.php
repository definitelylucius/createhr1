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
    
    Schema::create('job_applications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ✅ Ensures user_id is required
        $table->unsignedBigInteger('job_id');
        $table->string('name');
        $table->string('email');
        $table->string('resume');
        $table->string('status')->default('Pending'); // Existing column
        $table->string('application_status')->default('new_application'); // Add this line
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
