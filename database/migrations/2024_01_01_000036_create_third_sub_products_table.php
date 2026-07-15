<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('third_sub_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->decimal('provider_price', 15, 2);
            $table->string('provider_name');
            $table->enum('provider_status', ['active', 'inactive']);
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('third_sub_products');
    }
};