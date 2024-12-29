<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('value');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('mrp', 10, 2)->default(0);
            $table->string('unit')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('stock')->nullable();
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->json('additional_info')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attributes');
    }
};
