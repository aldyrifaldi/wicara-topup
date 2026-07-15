<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vipayment_logs', function (Blueprint $table) {
            $table->id();
            $table->string('order_code');
            $table->string('ref_id');
            $table->string('status');
            $table->text('message')->nullable();
            $table->json('data')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vipayment_logs');
    }
};