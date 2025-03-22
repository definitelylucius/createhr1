<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOnboardingProgress extends Model
{
    use HasFactory;

    protected $table = 'employee_onboarding_progress';

    protected $fillable = [
        'employee_id',
        'task_id',
        'is_completed',
    ];

    // Define relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function task()
    {
        return $this->belongsTo(OnboardingTask::class, 'task_id');
    }
}