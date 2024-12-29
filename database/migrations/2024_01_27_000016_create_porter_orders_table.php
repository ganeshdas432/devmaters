<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('porter_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cid')->constrained('users')->onDelete('cascade');
            $table->foreignId('did')->nullable()->constrained('deliveries')->onDelete('set null');
            $table->foreignId('rider_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('order_status')->constrained('statuses')->onDelete('restrict');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->string('pickup_address');
            $table->decimal('pickup_latitude', 10, 8);
            $table->decimal('pickup_longitude', 11, 8);
            $table->string('drop_address');
            $table->decimal('drop_latitude', 10, 8);
            $table->decimal('drop_longitude', 11, 8);
            $table->decimal('distance', 10, 2)->default(0);
            $table->integer('estimated_time')->default(0); // in minutes
            $table->timestamp('pickup_time')->nullable();
            $table->timestamp('drop_time')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->string('payment_method')->default('cash');
            $table->string('payment_status')->default('pending');
            $table->json('meta_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('porter_orders');
    }
};
