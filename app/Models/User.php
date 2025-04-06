<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'employee_status',
    ];
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setGoogle2faSecretAttribute($value)
    {
        $this->attributes['google2fa_secret'] = Crypt::encrypt($value);
    }

    public function getGoogle2faSecretAttribute($value)
    {
        return Crypt::decrypt($value);
    }


    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    // Method to check if the user is an Admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Method to check if the user is a Staff member
    public function isStaff()
    {
        return $this->role === 'staff';
    }

    // Method to check if the user is an Employee
    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    // Method to check if the user is an Applicant
    public function isApplicant()
    {
        return $this->role === 'applicant';
    }
    public function reviewedBy()
{
    return $this->belongsTo(User::class, 'reviewed_by'); 
}

public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id'); // Ensure the correct foreign key is used
    }
  

    public function employeeOnboarding()
{
    return $this->hasOne(EmployeeOnboarding::class);
}

public function onboardingStatus()
{
    return $this->hasOne(EmployeeOnboarding::class, 'user_id');
}
public function tasks()
{
    return $this->hasMany(Task::class, 'user_id');
}
public function jobApplications()
{
    return $this->hasMany(JobApplication::class);
}

public function candidates()
{
    return $this->hasMany(Candidate::class);
}

public function onboardingTasks()
{
    return $this->belongsToMany(OnboardingTask::class, 'employee_onboarding')
        ->withPivot(['status', 'due_date', 'completed_at', 'notes'])
        ->withTimestamps();
}

public function documents()
{
    return $this->hasMany(EmployeeDocument::class);
}

public function manager()
{
    return $this->belongsTo(User::class, 'manager_id');
}

public function teamMembers()
{
    return $this->hasMany(User::class, 'manager_id');
}
}


