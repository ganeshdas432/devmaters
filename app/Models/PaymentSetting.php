<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'cash_payment_enabled',
        'digital_payment_enabled',
        'razorpay_key',
        'razorpay_secret'
    ];

    protected $casts = [
        'cash_payment_enabled' => 'boolean',
        'digital_payment_enabled' => 'boolean'
    ];
}
