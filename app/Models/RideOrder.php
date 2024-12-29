<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RideOrder extends Model
{
    protected $table = 'ride_orders';


    use HasFactory;
    protected $fillable=['cid','did','subtotal','order_status',];
    
     public function orderDetails()
    {
        return $this->hasMany(RideOrderdetail::class,'order_id','id');
    }
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class,'order_status','id');
    }

         public function delivery()
    {
        return $this->belongsTo(Delivery::class,'did','id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'cid', 'id');
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class, 'rider_id');
    }


}