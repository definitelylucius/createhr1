<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'title',
        'department',
        'type',
        'location',
        'min_salary',
        'max_salary',
        'description',
        'responsibilities',
        'qualifications',
        'experience_level',
        'application_deadline',
        'status',
        'posted_by',
    ];

    // Add casting for application_deadline to convert it to a Carbon instance
    protected $casts = [
        'application_deadline' => 'datetime', // This ensures it's treated as a Carbon instance
    ];

    /**
     * Relationship: Job belongs to an Admin (who posted the job)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

      public function department()
    {
        return $this->belongsTo(Department::class); // Assuming the foreign key is 'department_id'
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
