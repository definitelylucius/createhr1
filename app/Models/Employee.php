<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\OnboardingTask;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'employee';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'soft_skills',
        'hard_skills',
        'department',
        'job_type',
        'resume',
        'user_id',
        'hired_date', 
        'active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function job()
{
    return $this->belongsTo(Job::class, 'job_id'); // Ensure the foreign key is correct
}

    public function onboardingTasks()
    {
        return $this->hasMany(OnboardingTask::class);
    }

    public function documents()
    {
        return $this->hasOne(Document::class, 'employee_id', 'id');
    }
    

    public function employee()
{
    return $this->hasOne(Employee::class, 'user_id');
}

// Relationship with Onboarding (One Employee has One Onboarding record)
public function onboarding()
{
    return $this->hasOne(EmployeeOnboarding::class, 'employee_id', 'id');
}

    
}

