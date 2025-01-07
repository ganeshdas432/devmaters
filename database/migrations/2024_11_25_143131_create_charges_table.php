<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->id();
            $table->string('zone_id');
            $table->decimal('fs_per_km', 8, 2);
            $table->decimal('gs_per_km', 8, 2);
            $table->decimal('p_per_km', 8, 2);
            $table->decimal('r_per_km', 8, 2);
            $table->boolean('free_delivery_on')->default(false);
            $table->decimal('r_per_person', 8, 2);
            $table->decimal('p_per_weight', 8, 2);
            $table->decimal('p_weight', 8, 2);
            $table->decimal('tax_per', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charges');
    }
};