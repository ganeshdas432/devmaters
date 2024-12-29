<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name');
            $table->string('image')->nullable();
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            $table->text('address');
            $table->string('mobile');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->enum('shop_type', ['food', 'grocery', 'pharmacy', 'other'])->default('other');
            $table->enum('status', ['active', 'inactive', 'pending', 'blocked'])->default('pending');
            $table->foreignId('zone_id')->nullable()->constrained('zones')->onDelete('set null');
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->decimal('minimum_order', 10, 2)->default(0);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('delivery_radius', 8, 2)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->text('description')->nullable();
            $table->json('business_hours')->nullable();
            $table->json('delivery_time_slots')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->string('tax_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shops');
    }
};
