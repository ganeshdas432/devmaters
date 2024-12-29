<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PorterOrderdetail extends Model
{
    use HasFactory;


    protected $table = 'porter_order_details';


    protected $fillable = [
        'order_id', 'weight', 'item_type'
    ];

    public function order()
    {
        return $this->belongsTo(PorterOrder::class, 'order_id');
    }

   
}