<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vipayment_sub_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->decimal('vipayment_price', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('special_discount', 15, 2)->default(0);
            $table->enum('vipayment_status', ['available', 'unavailable']);
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vipayment_sub_products');
    }
};