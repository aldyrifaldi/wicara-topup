<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->enum('type', ['product', 'joki', 'balance', 'upgrade_account', 'upgrade_level', 'file_access', 'invite_user']);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled']);
            $table->enum('payment_status', ['paid', 'unpaid']);
            $table->enum('payment_method', ['balance', 'midtrans', 'ipaymu', 'vipayment', 'digiflazz']);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('final_amount', 15, 2);
            $table->foreignId('bank_id')->nullable()->constrained('banks')->nullOnDelete();
            $table->string('payment_gateway')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_url')->nullable();
            $table->datetime('payment_expiry')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->boolean('is_refund')->default(false);
            $table->text('refund_reason')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};