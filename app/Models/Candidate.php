<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'status',
        'staff_notes',
        'admin_notes',
        'user_id',
        'job_id',
    ];

    protected $appends = ['full_name', 'status_badge'];

    

    protected static function booted()
    {
        static::updated(function ($candidate) {
            if ($candidate->isDirty('status') && $candidate->status === 'hired') {
                // Trigger any post-hire actions
            }
        });
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'new' => 'info',
            'under_review' => 'primary',
            'license_verified' => 'success',
            'test_scheduled' => 'warning',
            'test_completed' => 'secondary',
            'pending_approval' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            'final_interview_scheduled' => 'primary',
            'final_interview_completed' => 'success',
            'hired' => 'success'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    // Relationships
    public function tags()
    {
        return $this->belongsToMany(CandidateTag::class, 'candidate_tag');
    }

    public function licenseVerification()
    {
        return $this->hasOne(LicenseVerification::class);
    }

    public function tests()
    {
        return $this->hasMany(CandidateTest::class);
    }

    public function documents()
    {
        return $this->hasMany(CandidateDocument::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parsedResumes()
    {
        return $this->hasMany(ParsedResume::class, 'document_id', 'id');
    }

    public function finalInterview()
    {
        return $this->hasOne(FinalInterview::class);
    }

    public function hiringDecision()
    {
        return $this->hasOne(HiringDecision::class);
    }

    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeReadyForFinalInterview($query)
    {
        return $query->whereIn('status', ['approved', 'final_interview_scheduled']);
    }

    public function scopeAppliedForJob($query, $jobId)
    {
        return $query->where('job_id', $jobId);
    }

    // Status Methods
    public function markAsApproved()
    {
        $this->update(['status' => 'approved']);
    }

    public function markAsHired()
    {
        $this->update(['status' => 'hired']);
    }

    public function isHirable()
    {
        return in_array($this->status, ['approved', 'final_interview_completed']);
    }




}