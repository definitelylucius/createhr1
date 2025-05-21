<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('candidate_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_id')->unique();
            $table->date('hire_date');
            $table->date('regularization_date')->nullable();
            $table->foreignId('job_position_id')->constrained('job_positions');
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('reports_to')->nullable()->constrained('employees')->nullOnDelete();
            $table->decimal('salary', 12, 2);
            $table->string('salary_type'); // monthly, hourly, etc.
            $table->string('payment_method');
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('sss_number')->nullable();
            $table->string('philhealth_number')->nullable();
            $table->string('pagibig_number')->nullable();
            $table->string('tin_number')->nullable();
            $table->text('emergency_contact_name')->nullable();
            $table->text('emergency_contact_relation')->nullable();
            $table->text('emergency_contact_phone')->nullable();
            $table->text('work_location')->nullable();
            $table->enum('employment_status', ['probationary', 'regular', 'contractual', 'part-time'])->default('probationary');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}


