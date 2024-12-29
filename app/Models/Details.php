<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Details extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_mobile',
        'company_email',
        'company_address',
        'company_logo_square',
        'company_logo_rectangular',
        'company_about',
        'company_privacy',
        'company_contact',
    ];
}