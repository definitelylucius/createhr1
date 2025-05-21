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
            // Option 1: Allow NULL values
            $table->string('address')->nullable()->change();
    
            // Option 2: Set a default value (empty string)
            // $table->string('address')->default('')->change();
        });
    }
    
    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Reverse the change if rolling back
            $table->string('address')->nullable(false)->change();
            // Or if setting default, revert to no default
            // $table->string('address')->default(null)->change();
        });
    }
};
