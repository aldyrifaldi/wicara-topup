<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->text('note');
            $table->enum('type', ['product', 'joki'])->default('product');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_notes');
    }
};