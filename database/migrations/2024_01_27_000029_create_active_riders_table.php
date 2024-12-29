<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('active_riders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained('users')->onDelete('cascade');
            $table->boolean('food')->default(false);
            $table->boolean('grocery')->default(false);
            $table->boolean('rider')->default(false);
            $table->boolean('porter')->default(false);
            $table->boolean('is_online')->default(true);
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->string('current_location')->nullable();
            $table->enum('status', ['available', 'busy', 'offline'])->default('available');
            $table->integer('completed_orders')->default(0);
            $table->decimal('total_earnings', 10, 2)->default(0);
            $table->json('current_order_ids')->nullable();
            $table->timestamp('last_location_update')->nullable();
            $table->foreignId('current_zone_id')->nullable()->constrained('zones')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('active_riders');
    }
};
