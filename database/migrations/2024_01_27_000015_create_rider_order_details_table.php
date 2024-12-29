<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rider_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('ride_orders')->onDelete('cascade');
            $table->integer('person_count')->default(1);
            $table->enum('vehicle_type', ['bike', 'car', 'auto'])->default('bike');
            $table->decimal('base_fare', 10, 2)->default(0);
            $table->decimal('per_km_charge', 10, 2)->default(0);
            $table->decimal('per_minute_charge', 10, 2)->default(0);
            $table->decimal('waiting_charge', 10, 2)->default(0);
            $table->decimal('cancellation_charge', 10, 2)->nullable();
            $table->json('additional_charges')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rider_order_details');
    }
};
