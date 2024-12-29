<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KycDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'kyc_type',
        'document_type',
        'document_number',
        'front_image',
        'back_image',
        'expiry_date',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'verified_at' => 'datetime'
    ];

    // Relationship with user who owns the document
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with user who verified the document
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
