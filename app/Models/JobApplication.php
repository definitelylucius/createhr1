<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;



class JobApplication extends Model
{
    use HasFactory;

    protected $table = 'job_applications';
    protected $fillable = [
        'user_id', 'job_id', 'name', 'email', 'resume', 'status', 'interview_date',
        'interview_status', 'application_status', 'reviewed_by', 'employee_status',
        'skills', 'cdl_mentioned', 'experience_summary' // Added these
    ];

    protected $casts = [
        'interview_date' => 'datetime',
        'application_status' => 'string',
        'status' => 'string',
        'experience_summary' => 'array', // Add this for JSON casting
        'cdl_mentioned' => 'boolean' // Add this for boolean casting
    ];

    // Define application process statuses
    public const APPLICATION_STATUS_PENDING = 'pending_review';
    public const APPLICATION_STATUS_ADMIN_REVIEW = 'for_admin_review';
    public const APPLICATION_STATUS_REJECTED = 'rejected';

    // Define hiring stages statuses
    public const STATUS_QUALIFIED = 'qualified';
    public const STATUS_INTERVIEWED = 'interviewed';
    public const STATUS_HIRED = 'hired';
    public const STATUS_REJECTED = 'rejected';

    const STATUS_INTERVIEW_SCHEDULED = 'interview_scheduled';
   
    protected $primaryKey = 'id'; // default

    
    public function getMlPredictionAttribute()
    {
        return $this->experience_summary['ml_results']['prediction'] ?? null;
    }

    public function getMlConfidenceAttribute()
    {
        return $this->experience_summary['ml_results']['confidence'] ?? null;
    }

    public function getEnhancedSkillsAttribute()
    {
        return $this->experience_summary['ml_results']['enhanced_skills'] ?? [];
    }

    public function getMatchScoreAttribute()
    {
        return $this->experience_summary['match_score'] ?? 0;
    }

    public function getSkillsArrayAttribute()
    {
        return explode(', ', $this->skills);
    }

    public function hasMlData()
    {
        return isset($this->experience_summary['ml_results']);
    }

   

    public function getResumeUrlAttribute()
{
    if (!$this->resume) return null;
    return Storage::disk('resumes')->url($this->resume);
}

    // Relationships
    public function job() {
        return $this->belongsTo(Job::class);
    }

    public function reviewer() {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
    public static function boot()
{
    parent::boot();

    static::deleting(function ($application) {
        if ($application->resume) {
            // Convert the resume URL to the correct storage path
            $filePath = str_replace('/storage/', 'public/', $application->resume);

            // Delete the file from storage
            Storage::delete($filePath);
        }
    });
}

public static function getStatuses()
{
    return [
        
        self::STATUS_INTERVIEW_SCHEDULED,
        self::STATUS_REJECTED,
    ];
}
}
