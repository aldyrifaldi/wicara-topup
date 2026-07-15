<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_integration_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_integration_id')->constrained('api_integrations')->cascadeOnDelete();
            $table->string('endpoint');
            $table->enum('method', ['GET', 'POST', 'PUT', 'DELETE']);
            $table->json('request')->nullable();
            $table->json('response')->nullable();
            $table->integer('status_code');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_integration_logs');
    }
};