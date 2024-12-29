<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('fcm_configs');

        Schema::create('fcm_configs', function (Blueprint $table) {
            $table->id();
            $table->boolean('firebase_enabled')->default(false);
            $table->string('api_key')->nullable();
            $table->string('project_id')->nullable();
            $table->string('auth_domain')->nullable();
            $table->string('storage_bucket')->nullable();
            $table->string('messaging_sender_id')->nullable();
            $table->string('app_id')->nullable();
            $table->string('measurement_id')->nullable();
            $table->string('server_key')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fcm_configs');
    }
};
