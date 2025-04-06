<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('hire_date')->nullable();
            $table->string('employee_id')->unique()->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('work_location')->nullable();
            $table->enum('employment_status', ['active', 'on_leave', 'terminated'])->default('active');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'hire_date',
                'employee_id',
                'department',
                'position',
                'salary',
                'work_location',
                'employment_status',
                'manager_id',
                'phone',
                'address',
                'emergency_contact_name',
                'emergency_contact_phone'
            ]);
        });
    }
};