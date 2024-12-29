<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = [
        'name',
        'delivery_charge_per_km',
        'base_delivery_charge',
        'minimum_delivery_charge',
        'coordinates',
        'status',
        'description'
    ];

    protected $casts = [
        'coordinates' => 'array',
        'delivery_charge_per_km' => 'decimal:2',
        'base_delivery_charge' => 'decimal:2',
        'minimum_delivery_charge' => 'decimal:2'
    ];

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'active' => 'success',
            'inactive' => 'danger',
            default => 'warning'
        };
    }
}