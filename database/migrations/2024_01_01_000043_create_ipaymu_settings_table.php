<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ipaymu_settings', function (Blueprint $table) {
            $table->id();
            $table->string('api_key');
            $table->string('virtual_account');
            $table->boolean('is_sandbox')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipaymu_settings');
    }
};