<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_mobile');
            $table->string('company_email');
            $table->text('company_address');
            $table->string('company_logo_square')->nullable();
            $table->string('company_logo_rectangular')->nullable();
            $table->text('company_about')->nullable();
            $table->text('company_privacy')->nullable();
            $table->text('company_contact')->nullable();
            $table->string('currency')->default('INR');
            $table->string('currency_symbol')->default('₹');
            $table->string('timezone')->default('Asia/Kolkata');
            $table->string('language')->default('en');
            $table->json('social_links')->nullable();
            $table->json('payment_methods')->nullable();
            $table->json('business_hours')->nullable();
            $table->string('google_maps_key')->nullable();
            $table->boolean('maintenance_mode')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('details');
    }
};
