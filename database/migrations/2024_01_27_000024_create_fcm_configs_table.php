<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fcm_configs', function (Blueprint $table) {
            $table->id();
            $table->string('api_key');
            $table->string('project_id');
            $table->string('auth_domain');
            $table->string('storage_bucket');
            $table->string('messaging_sender_id');
            $table->string('app_id');
            $table->string('measurement_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('environment')->default('production');
            $table->text('server_key')->nullable();
            $table->json('additional_config')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fcm_configs');
    }
};
