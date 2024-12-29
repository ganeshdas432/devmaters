<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_name',
        'image',
        'vendor_id',
        'address',
        'mobile',
        'latitude',
        'longitude', // Corrected spelling
        'shop_type',
        'status',
        'zone_id',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}