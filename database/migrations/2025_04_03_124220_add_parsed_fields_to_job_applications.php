<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->text('skills')->nullable()->after('resume');
            $table->boolean('cdl_mentioned')->default(false)->after('skills');
            $table->json('experience_summary')->nullable()->after('cdl_mentioned');
            $table->json('parsed_data')->nullable()->after('experience_summary');
            $table->string('parser_used', 20)->nullable()->after('parsed_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            //
        });
    }
};
