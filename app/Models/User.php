<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Support\Carbon;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'employee_status',
        'two_factor_secret', // Add this for Fortify compatibility
        'two_factor_code', 
        'two_factor_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret', // Hide sensitive 2FA data
        'two_factor_code',
        'two_factor_recovery_codes'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_expires_at' => 'datetime' // Cast as datetime
    ];
   // Secure 2FA Code Generation
   public function generateTwoFactorCode()
   {
       $this->forceFill([
           'two_factor_code' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
           'two_factor_expires_at' => now()->addMinutes(10),
       ])->save();
   }
   
   public function verifyTwoFactorCode($code): bool
   {
       return $this->two_factor_code === (string)$code && 
              now()->lt($this->two_factor_expires_at);
   }
   
   public function resetTwoFactorCode()
   {
       $this->forceFill([
           'two_factor_code' => null,
           'two_factor_expires_at' => null,
       ])->save();
   }
   
   public function hasTwoFactorEnabled(): bool
   {
       return !empty($this->two_factor_secret);
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
  




public function tasks()
{
    return $this->hasMany(Task::class, 'user_id');
}
public function jobApplications()
{
    return $this->hasMany(JobApplication::class);
}

/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Get the candidates that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
/*******  aba06dc8-a953-4860-b715-8ea0bfad4131  *******/public function candidates()
{
    return $this->hasMany(Candidate::class);
}

public function onboardingTasks()
{
    return $this->belongsToMany(OnboardingTask::class, 'employee_onboarding')
        ->withPivot(['status', 'due_date', 'completed_at', 'notes'])
        ->withTimestamps();
}



public function manager()
{
    return $this->belongsTo(User::class, 'manager_id');
}

public function teamMembers()
{
    return $this->hasMany(User::class, 'manager_id');
}

public function applications()
{
    return $this->hasMany(JobApplication::class);
}

// Accessor for firstname (makes $user->first_name work)


}


