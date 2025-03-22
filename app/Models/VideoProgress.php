<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoProgress extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'completed', 'watch_time'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
