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
        Schema::create('fingerprint_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('fingerprint');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('language')->nullable();
            $table->json('screen_resolution')->nullable();
            $table->string('timezone')->nullable();
            $table->string('platform')->nullable();
            $table->string('webgl_vendor')->nullable();
            $table->string('webgl_renderer')->nullable();
            $table->string('device_memory')->nullable();
            $table->unsignedTinyInteger('hardware_concurrency')->nullable();
            $table->boolean('ad_block')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fingerprint_logs');
    }
};
