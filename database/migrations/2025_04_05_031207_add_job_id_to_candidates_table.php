<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJobIdToCandidatesTable extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            // Add job_id column
            $table->unsignedBigInteger('job_id');

            // Add foreign key constraint to the job_id
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['job_id']);
            $table->dropColumn('job_id');
        });
    }
}
