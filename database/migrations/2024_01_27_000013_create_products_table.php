<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('cat_id')->constrained('categories')->onDelete('cascade');
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->integer('time')->default(0); // preparation/delivery time in minutes
            $table->enum('type', ['veg', 'non-veg', 'other'])->default('other');
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive', 'out_of_stock'])->default('active');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_available')->default(true);
            $table->json('variations')->nullable();
            $table->json('addons')->nullable();
            $table->integer('stock_quantity')->nullable();
            $table->string('unit')->nullable();
            $table->string('sku')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
