<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('cash_payment_enabled')->default(true);
            $table->boolean('digital_payment_enabled')->default(false);
            $table->string('razorpay_key')->nullable();
            $table->string('razorpay_secret')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_settings');
    }
};
