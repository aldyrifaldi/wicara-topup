<?php

namespace App\Services;

use App\Enums\OrderEnum;
use App\Events\OrderCancelled;
use App\Events\OrderCompleted;
use App\Events\OrderCreated;
use App\Events\OrderFailed;
use App\Events\OrderProcessing;
use App\Models\Order;
use App\Models\SubProduct;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Create a new order
     */
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $user = auth()->user();
            $product = null;
            $subProduct = null;
            $totalAmount = 0;

            // Determine order type and set product
            if (isset($data['product_id'])) {
                $product = \App\Models\Product::findOrFail($data['product_id']);
                $orderType = $product->type;

                if (isset($data['sub_product_id'])) {
                    $subProduct = SubProduct::findOrFail($data['sub_product_id']);
                    $totalAmount = $subProduct->price;
                }
            } elseif (isset($data['joki_service_id'])) {
                $orderType = OrderEnum::TYPE_JOKI->value;
                $totalAmount = $data['amount'] ?? 0;
            }

            // Generate unique order code
            $prefix = $orderType === 'joki' ? 'JK' : 'TRX';
            $orderCode = $this->generateOrderCode($prefix);

            // Create order
            $order = Order::create([
                'code' => $orderCode,
                'user_id' => $user->id,
                'type' => $orderType,
                'status' => OrderEnum::STATUS_PENDING->value,
                'payment_status' => OrderEnum::PAYMENT_STATUS_PENDING->value,
                'payment_method' => $data['payment_method'] ?? 'balance',
                'total_amount' => $totalAmount,
                'account_id' => $data['account_id'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // Create order item
            if ($product && $subProduct) {
                $order->items()->create([
                    'product_id' => $product->id,
                    'sub_product_id' => $subProduct->id,
                    'product_name' => $product->name,
                    'product_code' => $subProduct->code,
                    'price' => $subProduct->price,
                    'quantity' => 1,
                ]);
            }

            // Dispatch order created event
            event(new OrderCreated($order));

            Log::info("Order created: {$orderCode}");

            return $order;
        });
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Order $order, string $status): Order
    {
        $previousStatus = $order->status;
        $order->update(['status' => $status]);

        // Dispatch appropriate event based on status
        switch ($status) {
            case OrderEnum::STATUS_PROCESSING->value:
                event(new OrderProcessing($order));
                break;

            case OrderEnum::STATUS_COMPLETED->value:
                event(new OrderCompleted($order));
                break;

            case OrderEnum::STATUS_FAILED->value:
                event(new OrderFailed($order));
                break;

            case OrderEnum::STATUS_CANCELLED->value:
                event(new OrderCancelled($order));
                break;
        }

        Log::info("Order {$order->code} status updated: {$previousStatus} -> {$status}");

        return $order->fresh();
    }

    /**
     * Process payment for order
     */
    public function processPayment(Order $order, string $method): ?string
    {
        $paymentService = app(PaymentService::class);

        switch ($method) {
            case OrderEnum::PAYMENT_METHOD_BALANCE->value:
                $paymentService->processBalance($order);
                return null;

            case OrderEnum::PAYMENT_METHOD_MIDTRANS->value:
                return $paymentService->processMidtrans($order);

            case OrderEnum::PAYMENT_METHOD_IPAYMU->value:
                return $paymentService->processIpaymu($order);

            default:
                throw new \InvalidArgumentException("Unsupported payment method: {$method}");
        }
    }

    /**
     * Cancel order and handle refunds if applicable
     */
    public function cancelOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            // Refund balance if paid with balance
            if ($order->payment_method === OrderEnum::PAYMENT_METHOD_BALANCE->value 
                && $order->payment_status === OrderEnum::PAYMENT_STATUS_PAID->value
            ) {
                $user = $order->user;
                $user->increment('balance', $order->total_amount);
                
                Log::info("Refunded Rp{$order->total_amount} to user {$user->id} for order {$order->code}");
            }

            $this->updateOrderStatus($order, OrderEnum::STATUS_CANCELLED->value);
        });
    }

    /**
     * Generate unique order code
     */
    private function generateOrderCode(string $prefix): string
    {
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4));

        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * Get order statistics
     */
    public function getOrderStatistics(array $filters = []): array
    {
        $query = Order::query();

        if (isset($filters['from_date']) && isset($filters['to_date'])) {
            $query->whereBetween('created_at', [$filters['from_date'], $filters['to_date']]);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $orders = $query->get();

        return [
            'total_orders' => $orders->count(),
            'total_amount' => $orders->sum('total_amount'),
            'paid_orders' => $orders->where('payment_status', 'paid')->count(),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'completed_orders' => $orders->where('status', 'completed')->count(),
            'failed_orders' => $orders->where('status', 'failed')->count(),
        ];
    }
}
