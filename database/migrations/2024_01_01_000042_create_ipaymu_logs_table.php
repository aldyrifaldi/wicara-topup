<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ipaymu_logs', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id');
            $table->string('session_id');
            $table->string('status');
            $table->string('payment_method');
            $table->string('payment_no');
            $table->decimal('amount', 15, 2);
            $table->string('qr_string')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipaymu_logs');
    }
};