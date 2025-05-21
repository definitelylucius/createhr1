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
        // Increase status column length
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('status', 30)->change();
        });

        // Also increase current_stage length if needed
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('current_stage', 50)->change();
        });
    }

    public function down()
    {
        // Revert changes if needed
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('status', 20)->change();
        });
        
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('current_stage', 20)->change();
        });
    }
};
