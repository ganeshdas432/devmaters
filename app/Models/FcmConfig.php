<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FcmConfig extends Model
{
    protected $fillable = [
        'firebase_enabled',
        'api_key',
        'project_id',
        'auth_domain',
        'storage_bucket',
        'messaging_sender_id',
        'app_id',
        'measurement_id',
        'server_key',
    ];

    protected $casts = [
        'firebase_enabled' => 'boolean',
    ];
}
