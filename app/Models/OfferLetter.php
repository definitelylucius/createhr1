<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'file_path',
        'signature_path',
        'sent_at',
        'signed_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'signed_at' => 'datetime'
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class);
    }
}