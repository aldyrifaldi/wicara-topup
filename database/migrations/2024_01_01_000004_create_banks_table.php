<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('account_number');
            $table->string('account_holder');
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('payment_method')->nullable();
            $table->decimal('admin_fee', 15, 2)->default(0);
            $table->enum('admin_fee_type', ['fixed', 'percentage'])->default('fixed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};