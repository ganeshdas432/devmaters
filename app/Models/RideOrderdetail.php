<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RideOrderdetail extends Model
{
    use HasFactory;

      protected $table = 'rider_order_details';


    protected $fillable = [
        'order_id', 'person_count', 'vehicle_type'
    ];

    public function order()
    {
        return $this->belongsTo(RideOrder::class,'order_id');
    }

}