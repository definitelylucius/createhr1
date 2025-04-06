<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOnboarding extends Model
{
    use HasFactory;

    protected $table = 'employee_onboarding';

    protected $fillable = [
        'employee_id',
        'task_id',
        'status',
        'due_date',
        'completed_at',
        'notes',
        'completed_by'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(OnboardingTask::class);
    }

    public function completer()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}