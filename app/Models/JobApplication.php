<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_id',
        'firstname',
        'lastname',
        'email',
        'phone',
        'address',
        'resume_path',
        'status',
        'current_stage'
    ];

    // Application Stages
    const STATUS_SUBMITTED = 'submitted';
    public const STATUS_APPLIED = 'applied';
    
    // Interview Stages
    public const STATUS_INITIAL_INTERVIEW_SCHEDULED = 'initial_interview_scheduled';
    public const STATUS_INITIAL_INTERVIEW_COMPLETED = 'initial_interview_completed';
    public const STATUS_INITIAL_INTERVIEW_PASSED = 'initial_interview_passed';
    public const STATUS_INITIAL_INTERVIEW_FAILED = 'initial_interview_failed';
    
    // Demo Stages
    public const STATUS_DEMO_SCHEDULED = 'demo_scheduled';
    public const STATUS_DEMO_COMPLETED = 'demo_completed';
    public const STATUS_DEMO_PASSED = 'demo_passed';
    public const STATUS_DEMO_FAILED = 'demo_failed';
    
    // Exam Stages
    public const STATUS_EXAM_SCHEDULED = 'exam_scheduled';
    public const STATUS_EXAM_COMPLETED = 'exam_completed';
    public const STATUS_EXAM_PASSED = 'exam_passed';
    public const STATUS_EXAM_FAILED = 'exam_failed';
    
    // Final Interview Stages
    public const STATUS_FINAL_INTERVIEW_SCHEDULED = 'final_interview_scheduled';
    public const STATUS_FINAL_INTERVIEW_COMPLETED = 'final_interview_completed';
    public const STATUS_FINAL_INTERVIEW_PASSED = 'final_interview_passed';
    public const STATUS_FINAL_INTERVIEW_FAILED = 'final_interview_failed';

    // Offer Stages
    public const STATUS_OFFER_PENDING = 'offer_pending';
    public const STATUS_OFFER_SENT = 'offer_sent';
    public const STATUS_OFFER_ACCEPTED = 'offer_accepted';
    public const STATUS_OFFER_DECLINED = 'offer_declined';
    public const STATUS_OFFER_EXPIRED = 'offer_expired';
    public const STATUS_OFFER_RETRACTED = 'offer_retracted';

    // Pre-employment Stages
    public const STATUS_PRE_EMPLOYMENT = 'pre_employment';
    public const STATUS_PRE_EMPLOYMENT_DOCUMENTS = 'pre_employment_documents';
    public const STATUS_PRE_EMPLOYMENT_INITIATED = 'pre_employment_initiated';
    public const STATUS_PRE_EMPLOYMENT_DOCS_REQUESTED = 'pre_employment_docs_requested';
    public const STATUS_PRE_EMPLOYMENT_DOCS_SUBMITTED = 'pre_employment_docs_submitted';
    public const STATUS_PRE_EMPLOYMENT_VERIFICATION = 'pre_employment_verification';
    public const STATUS_PRE_EMPLOYMENT_COMPLETED = 'pre_employment_completed';

    // Onboarding & Final Stages
    public const STATUS_ONBOARDING = 'onboarding';
    public const STATUS_ONBOARDING_INITIATED = 'onboarding_initiated';
    public const STATUS_ONBOARDING_COMPLETED = 'onboarding_completed';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_HIRED = 'hired';
    public const STATUS_REJECTED = 'rejected';

    // Status groups for easy reference
    public static function statusGroups(): array
    {
        return [
            'application' => [
                self::STATUS_SUBMITTED,
                self::STATUS_APPLIED,
            ],
            'interview' => [
                self::STATUS_INITIAL_INTERVIEW_SCHEDULED,
                self::STATUS_INITIAL_INTERVIEW_COMPLETED,
                self::STATUS_INITIAL_INTERVIEW_PASSED,
                self::STATUS_INITIAL_INTERVIEW_FAILED,
            ],
            'demo' => [
                self::STATUS_DEMO_SCHEDULED,
                self::STATUS_DEMO_COMPLETED,
                self::STATUS_DEMO_PASSED,
                self::STATUS_DEMO_FAILED,
            ],
            'exam' => [
                self::STATUS_EXAM_SCHEDULED,
                self::STATUS_EXAM_COMPLETED,
                self::STATUS_EXAM_PASSED,
                self::STATUS_EXAM_FAILED,
            ],
            'final_interview' => [
                self::STATUS_FINAL_INTERVIEW_SCHEDULED,
                self::STATUS_FINAL_INTERVIEW_COMPLETED,
                self::STATUS_FINAL_INTERVIEW_PASSED,
                self::STATUS_FINAL_INTERVIEW_FAILED,
            ],
            'offer' => [
                self::STATUS_OFFER_PENDING,
                self::STATUS_OFFER_SENT,
                self::STATUS_OFFER_ACCEPTED,
                self::STATUS_OFFER_DECLINED,
                self::STATUS_OFFER_EXPIRED,
                self::STATUS_OFFER_RETRACTED,
            ],
            'pre_employment' => [
                self::STATUS_PRE_EMPLOYMENT,
                self::STATUS_PRE_EMPLOYMENT_DOCUMENTS,
                self::STATUS_PRE_EMPLOYMENT_INITIATED,
                self::STATUS_PRE_EMPLOYMENT_DOCS_REQUESTED,
                self::STATUS_PRE_EMPLOYMENT_DOCS_SUBMITTED,
                self::STATUS_PRE_EMPLOYMENT_VERIFICATION,
                self::STATUS_PRE_EMPLOYMENT_COMPLETED,
            ],
            'onboarding' => [
                self::STATUS_ONBOARDING,
                self::STATUS_ONBOARDING_INITIATED,
                self::STATUS_ONBOARDING_COMPLETED,
            ],
            'final' => [
                self::STATUS_HIRED,
                self::STATUS_REJECTED,
            ]
        ];
    }

    // Status transitions
    public static function allowedTransitions(): array
    {
        return [
            self::STATUS_APPLIED => [
                self::STATUS_INITIAL_INTERVIEW_SCHEDULED,
                self::STATUS_REJECTED,
            ],
            self::STATUS_INITIAL_INTERVIEW_PASSED => [
                self::STATUS_DEMO_SCHEDULED,
                self::STATUS_EXAM_SCHEDULED,
            ],
            self::STATUS_FINAL_INTERVIEW_PASSED => [
                self::STATUS_OFFER_PENDING,
                self::STATUS_OFFER_SENT,
            ],
            self::STATUS_OFFER_ACCEPTED => [
                self::STATUS_PRE_EMPLOYMENT_INITIATED,
            ],
            // Add more transitions as needed
        ];
    }

    // Check if a status transition is allowed
    public function canTransitionTo(string $newStatus): bool
    {
        $allowed = self::allowedTransitions()[$this->status] ?? [];
        return in_array($newStatus, $allowed);
    }

    // Status Validation
    public function validateStatus()
    {
        $validStatuses = array_merge(...array_values(self::statusGroups()));

        if (!in_array($this->status, $validStatuses)) {
            throw new \InvalidArgumentException(
                "Invalid status: {$this->status}. Valid statuses are: " . implode(', ', $validStatuses)
            );
        }
    }

    // Relationships

    // Scope queries
    public function scopePendingOffers($query)
    {
        return $query->where('status', self::STATUS_OFFER_PENDING);
    }

    public function scopeAcceptedOffers($query)
    {
        return $query->where('status', self::STATUS_OFFER_ACCEPTED);
    }

    // Helper methods
    public function hasPendingOffer(): bool
    {
        return $this->status === self::STATUS_OFFER_PENDING;
    }

    public function hasAcceptedOffer(): bool
    {
        return $this->status === self::STATUS_OFFER_ACCEPTED;
    }

    public function isInPreEmployment(): bool
    {
        return in_array($this->status, [
            self::STATUS_PRE_EMPLOYMENT,
            self::STATUS_PRE_EMPLOYMENT_INITIATED,
            self::STATUS_PRE_EMPLOYMENT_DOCS_REQUESTED,
            self::STATUS_PRE_EMPLOYMENT_DOCS_SUBMITTED,
            self::STATUS_PRE_EMPLOYMENT_VERIFICATION,
        ]);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
   
    

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function recruitmentProcess()
{
    return $this->hasMany(RecruitmentProcess::class, 'application_id', 'id');
}
public function examEvaluation()
{
    return $this->hasOne(ExamEvaluation::class, 'application_id');
}

    public function reviewer() {
        return $this->belongsTo(User::class, 'reviewed_by');
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
public function applicant() {
    return $this->belongsTo(User::class, 'applicant_id');
}
// In JobApplication model
public function examEvaluations()
{
    return $this->hasMany(ExamEvaluation::class);
}


public static function getStatuses()
{
    return [
        
        self::STATUS_INTERVIEW_SCHEDULED,
        self::STATUS_REJECTED,
    ];
}



/**
 * Relationship with PreEmploymentDocument
 */


/**
 * Check if all documents are verified
 */
public function preEmploymentDocument()
{
    return $this->hasOne(PreEmploymentDocument::class);
}

public function preEmploymentStatus()
{
    if (!$this->preEmploymentDocument) {
        return 'not-started';
    }

    $documents = [
        'nbi_clearance_verified',
        'police_clearance_verified',
        'barangay_clearance_verified',
        'coe_verified',
        'drivers_license_verified',
    ];

    $requiredChecks = [
        'reference_check_verified',
        'drug_test_verified',
        'medical_exam_verified',
    ];

    $allDocumentsVerified = collect($documents)->every(function ($doc) {
        return $this->preEmploymentDocument->$doc;
    });

    $allChecksVerified = collect($requiredChecks)->every(function ($check) {
        return $this->preEmploymentDocument->$check;
    });

    if ($allDocumentsVerified && $allChecksVerified) {
        return 'completed';
    }

    if ($allDocumentsVerified) {
        return 'documents-completed';
    }

    if ($this->preEmploymentDocument->requested_documents) {
        return 'pending';
    }

    return 'in-progress';
}

public function hasRequestedDocuments()
{
    return $this->preEmploymentDocument && 
           !empty(json_decode($this->preEmploymentDocument->requested_documents ?? '[]', true));
}


public function onboarding() {
    return $this->hasOne(OnboardingProgress::class, 'job_application_id');
}

}

