<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    use HasFactory;
    protected $fillable = [
        'zone_id',
        'fs_per_km',
        'gs_per_km',
        'p_per_km',
        'r_per_km',
        'free_delivery_on',
        'r_per_person',
        'p_per_weight',
        'p_weight',
        'tax_per',
    ];
}