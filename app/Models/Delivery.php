<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

     protected $fillable = [
        'pickup_latitude',
        'pickup_longitude',
        'drop_latitude',
        'drop_longitude',
        'pickup_address',
        'drop_address',
        'delivery_type',
        'delivery_details',
        'pickup_contact',
        'drop_contact',
        'drop_to',
        'order_value',
        'order_weight',
        'order_payment_status',
        'order_payment_type',
        'distance',
        'delivery_cost',
        'tips',
        'rider_earning',
        'rider_id',
        'customer_id'
    ];

        public function order()
    {
        return $this->belongsTo(Order::class);
    }

     public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    // Define the relationship with User for the customer
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function rideOrders()
    {
        return $this->hasOne(RideOrder::class, 'did', 'id');
    }
}