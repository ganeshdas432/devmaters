<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('porter_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('porter_orders')->onDelete('cascade');
            $table->decimal('weight', 8, 2)->default(0); // in kg
            $table->enum('item_type', ['fragile', 'non-fragile', 'perishable', 'electronics', 'documents', 'other'])->default('other');
            $table->text('item_description')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('base_charge', 10, 2)->default(0);
            $table->decimal('weight_charge', 10, 2)->default(0);
            $table->decimal('distance_charge', 10, 2)->default(0);
            $table->decimal('additional_charges', 10, 2)->default(0);
            $table->json('dimensions')->nullable(); // length, width, height
            $table->boolean('requires_insurance')->default(false);
            $table->decimal('insurance_amount', 10, 2)->nullable();
            $table->text('special_instructions')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('porter_order_details');
    }
};
