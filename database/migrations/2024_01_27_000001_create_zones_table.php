<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('delivery_charge_per_km', 10, 2)->default(0);
            $table->decimal('base_delivery_charge', 10, 2)->default(0);
            $table->decimal('minimum_delivery_charge', 10, 2)->default(0);
            $table->decimal('maximum_delivery_charge', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->json('coordinates');
            $table->boolean('is_active')->default(true);
            $table->integer('max_delivery_time')->nullable();
            $table->boolean('is_serviceable')->default(true);
            $table->json('business_hours')->nullable();
            $table->json('delivery_types')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('zones');
    }
};
