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
        Schema::table('job_applications', function (Blueprint $table) {
            // Drop existing foreign key if it exists
            $table->dropForeign(['user_id']);
            $table->dropForeign(['job_id']);
            
            // Recreate them with proper names
            $table->foreign('user_id', 'fk_job_applications_user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('job_id', 'fk_job_applications_job_id')
                  ->references('id')->on('jobs')
                  ->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign('fk_job_applications_user_id');
            $table->dropForeign('fk_job_applications_job_id');
        });
    }
};
