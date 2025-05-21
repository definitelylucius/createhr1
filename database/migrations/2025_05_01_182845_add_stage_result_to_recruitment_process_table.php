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
        Schema::table('recruitment_process', function (Blueprint $table) {
            $table->enum('stage_result', ['pending', 'passed', 'failed'])->default('pending')->after('passed');
        });
    }
    
    public function down(): void
    {
        Schema::table('recruitment_process', function (Blueprint $table) {
            $table->dropColumn('stage_result');
        });
    }
};
