<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailConfig extends Model
{
    protected $fillable = [
        'smtp_enabled',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'from_name',
        'from_email',
        'encryption',
    ];

    protected $casts = [
        'smtp_enabled' => 'boolean',
        'smtp_port' => 'integer',
    ];
}
