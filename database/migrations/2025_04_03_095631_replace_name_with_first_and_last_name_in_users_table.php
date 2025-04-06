<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Add this line

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, check if the columns already exist, if not, add them
        if (!Schema::hasColumn('users', 'first_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('first_name')->nullable();
            });
        }

        if (!Schema::hasColumn('users', 'last_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('last_name')->nullable();
            });
        }

        // Migrate the data from 'name' column to 'first_name' and 'last_name'
        DB::table('users')->get()->each(function($user) {
            $nameParts = explode(' ', $user->name, 2); // Split into first and last name
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? ''; // If there's no space, last_name will be empty

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ]);
        });

        // Drop the old 'name' column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // If we need to rollback, restore the 'name' column and remove 'first_name' and 'last_name'
        Schema::table('users', function (Blueprint $table) {
            $table->string('name');  // Add the 'name' column back
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
        });

        // Optional: You can re-merge the 'first_name' and 'last_name' into the 'name' column if necessary
        DB::table('users')->get()->each(function($user) {
            $fullName = $user->first_name . ' ' . $user->last_name;
            DB::table('users')
                ->where('id', $user->id)
                ->update(['name' => $fullName]);
        });
    }
};
