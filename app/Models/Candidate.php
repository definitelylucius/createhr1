<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'resume_path',
        'resume_text',
        'resume_data',
        'status'
    ];

    protected $casts = [
        'resume_data' => 'array',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function stages()
    {
        return $this->hasMany(HiringProcessStage::class);
    }

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function offerLetter()
    {
        return $this->hasOne(OfferLetter::class);
    }

    // Helper methods for status checks
    public function isAtStage($stage)
    {
        return $this->status === $stage;
    }

    public function preEmploymentChecks()
{
    return $this->hasMany(PreEmploymentCheck::class);
}

    public function JobApplication()
    {
        return $this->hasOne(JobApplication::class);
    }

    public function moveToNextStage()
    {
        $stages = [
            'applied',
            'initial_interview',
            'demo',
            'exam',
            'final_interview',
            'pre_employment',
            'hired',
            'onboarding'
        ];

        $currentIndex = array_search($this->status, $stages);
        if ($currentIndex !== false && isset($stages[$currentIndex + 1])) {
            $this->status = $stages[$currentIndex + 1];
            $this->save();
            return true;
        }

        return false;
    }
}