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
        // Modify the existing 'users' table to add 'first_name' and 'last_name' columns
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable();  // Add first_name column
            $table->string('last_name')->nullable();   // Add last_name column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // If we need to roll back the migration, drop the columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
        });
    }
};
