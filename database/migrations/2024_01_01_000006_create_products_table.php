<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['product', 'joki'])->default('product');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->enum('digiflazz_status', ['active', 'inactive'])->default('inactive');
            $table->string('digiflazz_name')->nullable();
            $table->enum('vipayment_status', ['active', 'inactive'])->default('inactive');
            $table->string('vipayment_name')->nullable();
            $table->enum('third_product_status', ['active', 'inactive'])->default('inactive');
            $table->string('third_product_name')->nullable();
            $table->string('provider_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};