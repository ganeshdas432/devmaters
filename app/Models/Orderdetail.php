<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderdetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'quantity','product_price','product_name','selected_attribute'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

   public function product()
   {
    return $this->belongsTo(Product::class,'product_id');
    }

    public function attribute(){

        return $this->belongsTo(Attribute::class,'selected_attribute');
    }
}