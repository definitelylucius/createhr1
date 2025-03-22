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
            $table->string('application_status')->default('Pending')->after('status');
        });
    }
    
    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('application_status');
            //
        });
    }
};
