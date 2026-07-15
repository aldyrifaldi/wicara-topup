<?php

namespace App\Services;

use App\Enums\OrderEnum;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    /**
     * Process Midtrans payment
     */
    public function processMidtrans(Order $order): string
    {
        $serverKey = config('services.midtrans.server_key');
        $clientId = config('services.midtrans.client_id');

        $params = [
            'transaction_details' => [
                'order_id' => $order->code,
                'gross_amount' => $order->total_amount,
            ],
            'customer_details' => [
                'email' => $order->user->email,
                'name' => $order->user->name,
                'phone' => $order->user->phone,
            ],
            'item_details' => $order->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'name' => $item->product_name,
                ];
            })->toArray(),
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($serverKey . ':'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://app.midtrans.com/snap/v1/transactions', $params);

        if ($response->successful()) {
            $data = $response->json();
            
            // Update order with payment details
            $order->update([
                'payment_id' => $data['token'],
                'payment_url' => $data['redirect_url'],
            ]);

            Log::info("Midtrans payment created for order {$order->code}");
            
            return $data['redirect_url'];
        }

        throw new \Exception('Failed to create Midtrans payment: ' . $response->body());
    }

    /**
     * Process Ipaymu payment
     */
    public function processIpaymu(Order $order): string
    {
        $va = config('services.ipaymu.va');
        $secret = config('services.ipaymu.secret');

        $params = [
            'amount' => $order->total_amount,
            'buyerName' => $order->user->name,
            'buyerEmail' => $order->user->email,
            'buyerPhone' => $order->user->phone,
            'referenceId' => $order->code,
            'notifyUrl' => route('payment.callback.ipaymu'),
        ];

        $signature = $this->generateIpaymuSignature($params);

        $response = Http::withHeaders([
            'va' => $va,
            'signature' => $signature,
        ])->asForm()->post('https://my.ipaymu.com/payment', $params);

        if ($response->successful()) {
            $data = $response->json();
            
            $order->update([
                'payment_id' => $data['id'] ?? null,
                'payment_url' => $data['url'] ?? null,
            ]);

            Log::info("Ipaymu payment created for order {$order->code}");
            
            return $data['url'];
        }

        throw new \Exception('Failed to create Ipaymu payment: ' . $response->body());
    }

    /**
     * Process balance payment
     */
    public function processBalance(Order $order): void
    {
        $user = $order->user;

        if ($user->balance < $order->total_amount) {
            throw new \Exception('Insufficient balance');
        }

        DB::transaction(function () use ($user, $order) {
            $user->decrement('balance', $order->total_amount);

            $order->update([
                'payment_status' => OrderEnum::PAYMENT_STATUS_PAID->value,
                'paid_at' => now(),
            ]);

            // Create balance transaction record
            $user->balanceTransactions()->create([
                'type' => 'debit',
                'amount' => $order->total_amount,
                'description' => "Order payment: {$order->code}",
                'balance_after' => $user->balance,
            ]);

            // Process order
            app(OrderService::class)->updateOrderStatus($order, OrderEnum::STATUS_PROCESSING->value);
        });

        Log::info("Balance payment processed for order {$order->code}");
    }

    /**
     * Handle payment gateway callback
     */
    public function handleCallback(Request $request, string $gateway): array
    {
        try {
            switch ($gateway) {
                case 'midtrans':
                    return $this->handleMidtransCallback($request);

                case 'ipaymu':
                    return $this->handleIpaymuCallback($request);

                default:
                    throw new \InvalidArgumentException("Unsupported gateway: {$gateway}");
            }
        } catch (\Exception $e) {
            Log::error("Payment callback failed for {$gateway}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle Midtrans callback
     */
    private function handleMidtransCallback(Request $request): array
    {
        $signatureKey = $request->signature_key;
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;

        // Verify signature
        $serverKey = config('services.midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            throw new \Exception('Invalid signature');
        }

        $order = Order::where('code', $orderId)->firstOrFail();

        // Update payment status based on transaction status
        $transactionStatus = $request->transaction_status;
        
        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $order->update([
                    'payment_status' => OrderEnum::PAYMENT_STATUS_PAID->value,
                    'paid_at' => now(),
                ]);
                
                // Process order
                app(OrderService::class)->updateOrderStatus($order, OrderEnum::STATUS_PROCESSING->value);
                break;

            case 'deny':
            case 'cancel':
            case 'expire':
                $order->update([
                    'payment_status' => OrderEnum::PAYMENT_STATUS_FAILED->value,
                ]);
                break;

            case 'pending':
                // Keep as pending
                break;
        }

        Log::info("Midtrans callback processed for order {$orderId}");

        return ['status' => 'success', 'order' => $order];
    }

    /**
     * Handle Ipaymu callback
     */
    private function handleIpaymuCallback(Request $request): array
    {
        $orderId = $request->referenceId;
        $status = $request->status;

        $order = Order::where('code', $orderId)->firstOrFail();

        // Update payment status based on status
        switch ($status) {
            case 'successful':
            case 'success':
                $order->update([
                    'payment_status' => OrderEnum::PAYMENT_STATUS_PAID->value,
                    'paid_at' => now(),
                ]);
                
                // Process order
                app(OrderService::class)->updateOrderStatus($order, OrderEnum::STATUS_PROCESSING->value);
                break;

            case 'failed':
                $order->update([
                    'payment_status' => OrderEnum::PAYMENT_STATUS_FAILED->value,
                ]);
                break;

            case 'pending':
                // Keep as pending
                break;
        }

        Log::info("Ipaymu callback processed for order {$orderId}");

        return ['status' => 'success', 'order' => $order];
    }

    /**
     * Generate Ipaymu signature
     */
    private function generateIpaymuSignature(array $params): string
    {
        $secret = config('services.ipaymu.secret');
        $va = config('services.ipaymu.va');

        $data = $va . $secret . $params['amount'] . $params['referenceId'];
        
        return hash('sha256', $data);
    }
}
