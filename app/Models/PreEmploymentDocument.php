<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreEmploymentDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'scheduled_date',
        'activity_type',
        'location',
        'notes',
        'nbi_clearance',
        'nbi_clearance_verified',
        'nbi_clearance_expiry',
        'police_clearance',
        'police_clearance_verified',
        'police_clearance_expiry',
        'barangay_clearance',
/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Get the application that owns the pre-employment document.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

/*******  f05e794a-25c4-4509-b842-87ec6d5a2689  *******/        'barangay_clearance_verified',
        'barangay_clearance_expiry',
        'coe',
        'coe_verified',
        'drivers_license',
        'drivers_license_verified',
        'drivers_license_expiry',
        'reference_check_notes',
        'reference_check_verified',
        'drug_test_result',
        'drug_test_date',
        'drug_test_verified',
        'medical_exam_result',
        'medical_exam_date',
        'medical_exam_verified',
        'document_request_message',
        'document_request_deadline',
        'requested_documents',
    ];

    protected $casts = [
        'requested_documents' => 'array',
        'scheduled_date' => 'datetime',
        'document_request_deadline' => 'date',
        'nbi_clearance_expiry' => 'date',
        'police_clearance_expiry' => 'date',
        'barangay_clearance_expiry' => 'date',
        'drivers_license_expiry' => 'date',
        'drug_test_date' => 'date',
        'medical_exam_date' => 'date',
    ];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function allDocumentsVerified()
    {
        return $this->nbi_clearance_verified &&
               $this->police_clearance_verified &&
               $this->barangay_clearance_verified &&
               $this->coe_verified &&
               $this->drivers_license_verified;
    }

    public function allChecksVerified()
    {
        return $this->reference_check_verified &&
               $this->drug_test_verified &&
               $this->medical_exam_verified;
    }

    public function getReferenceCheckStatusAttribute()
{
    if ($this->reference_check_verified) {
        return ['status' => 'verified', 'class' => 'bg-green-100 text-green-800'];
    } elseif ($this->reference_check_notes) {
        return ['status' => 'submitted', 'class' => 'bg-blue-100 text-blue-800'];
    }
    return ['status' => 'pending', 'class' => 'bg-yellow-100 text-yellow-800'];
}

public function getDrugTestStatusAttribute()
{
    if ($this->drug_test_verified) {
        return ['status' => 'passed', 'class' => 'bg-green-100 text-green-800'];
    } elseif ($this->drug_test_result) {
        return ['status' => 'submitted', 'class' => 'bg-blue-100 text-blue-800'];
    }
    return ['status' => 'pending', 'class' => 'bg-yellow-100 text-yellow-800'];
}

public function getMedicalExamStatusAttribute()
{
    if ($this->medical_exam_verified) {
        return ['status' => 'cleared', 'class' => 'bg-green-100 text-green-800'];
    } elseif ($this->medical_exam_result) {
        return ['status' => 'submitted', 'class' => 'bg-blue-100 text-blue-800'];
    }
    return ['status' => 'pending', 'class' => 'bg-yellow-100 text-yellow-800'];
}
}