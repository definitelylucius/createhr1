<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Applicant extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'applicant';

    protected $fillable = [
        'name',
        'email',
        'password',
        'resume',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}