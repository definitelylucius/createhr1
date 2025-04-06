<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'days_before_due',
        'is_required'
    ];

    public function employees()
    {
        return $this->belongsToMany(User::class, 'employee_onboarding')
            ->withPivot(['status', 'due_date', 'completed_at', 'notes'])
            ->withTimestamps();
    }
}