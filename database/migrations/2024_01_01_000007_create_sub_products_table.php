<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->enum('type', ['product', 'joki'])->default('product');
            $table->decimal('price', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('special_discount', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_unlimited')->default(true);
            $table->integer('stock')->default(0);
            $table->decimal('cashback', 15, 2)->default(0);
            $table->text('bonus')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_products');
    }
};