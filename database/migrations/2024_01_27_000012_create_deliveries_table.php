<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->decimal('pickup_latitude', 10, 8);
            $table->decimal('pickup_longitude', 11, 8);
            $table->decimal('drop_latitude', 10, 8);
            $table->decimal('drop_longitude', 11, 8);
            $table->text('pickup_address');
            $table->text('drop_address');
            $table->enum('delivery_type', ['food', 'grocery', 'pharmacy', 'parcel', 'ride'])->default('parcel');
            $table->json('delivery_details')->nullable();
            $table->string('pickup_contact');
            $table->string('drop_contact');
            $table->string('drop_to');
            $table->decimal('order_value', 10, 2)->default(0);
            $table->decimal('order_weight', 8, 2)->nullable();
            $table->enum('order_payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('order_payment_type', ['cash', 'online', 'wallet'])->default('cash');
            $table->decimal('distance', 10, 2)->default(0);
            $table->decimal('delivery_cost', 10, 2)->default(0);
            $table->decimal('tips', 10, 2)->default(0);
            $table->decimal('rider_earning', 10, 2)->default(0);
            $table->foreignId('rider_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'picked', 'delivered', 'cancelled'])->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('picked_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->json('meta_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
};
