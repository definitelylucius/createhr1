<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'task_name', 'task_slug', 'status'];

    // Define relationship with User (each task belongs to a user)
    public function user() {
        return $this->belongsTo(User::class);
    }
}