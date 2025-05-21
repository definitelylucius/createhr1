<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'employment_contract',
        'tax_forms',
        'company_policies',
        'training_materials',
        'completed'
    ];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class);
    }
}