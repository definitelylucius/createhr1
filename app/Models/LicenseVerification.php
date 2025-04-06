<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'license_number',
        'license_type',
        'expiration_date',
        'is_verified',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'expiration_date' => 'date'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}