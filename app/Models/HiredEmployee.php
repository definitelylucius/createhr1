<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class HiredEmployee extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'profile_picture', 'bio', 'department', 'position', 'employee_id'];
}

