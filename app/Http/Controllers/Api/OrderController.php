<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderProductRequest;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private PaymentService $paymentService
    ) {
    }

    public function store(OrderProductRequest $request)
    {
        try {
            $order = $this->orderService->createOrder($request->validated());

            // Process payment if method provided
            if ($request->has('payment_method') && $request->payment_method !== 'balance') {
                $paymentUrl = $this->paymentService->processPayment($order, $request->payment_method);
                
                return $this->successResponse([
                    'order' => $order->load('items'),
                    'payment_url' => $paymentUrl,
                ], 'Order created successfully', 201);
            }

            return $this->successResponse($order->load('items'), 'Order created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Order creation failed: ' . $e->getMessage(), [], 500);
        }
    }

    public function index(Request $request)
    {
        $orders = Order::with(['items.product', 'items.subProduct'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Status filter
        if ($request->has('status')) {
            $orders->where('status', $request->status);
        }

        // Payment status filter
        if ($request->has('payment_status')) {
            $orders->where('payment_status', $request->payment_status);
        }

        // Date range filter
        if ($request->has('from_date') && $request->has('to_date')) {
            $orders->whereBetween('created_at', [
                $request->from_date,
                $request->to_date
            ]);
        }

        $orders = $orders->paginate($request->per_page ?? 15);

        return $this->successResponse($orders, 'Orders retrieved successfully');
    }

    public function show($code)
    {
        $order = Order::with(['items.product', 'items.subProduct', 'user'])
            ->where('code', $code)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return $this->successResponse($order, 'Order retrieved successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        $this->authorize('isAdmin');

        $validator = validator($request->all(), [
            'status' => 'required|in:pending,processing,completed,failed,cancelled',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', $validator->errors(), 422);
        }

        $order = Order::findOrFail($id);

        try {
            $order = $this->orderService->updateOrderStatus($order, $request->status);
            
            if ($request->has('admin_note')) {
                $order->update(['admin_note' => $request->admin_note]);
            }

            return $this->successResponse($order->load('items'), 'Order status updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Status update failed: ' . $e->getMessage(), [], 500);
        }
    }

    public function cancel(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'processing'])
            ->firstOrFail();

        try {
            $this->orderService->cancelOrder($order);
            return $this->successResponse([], 'Order cancelled successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Order cancellation failed: ' . $e->getMessage(), [], 500);
        }
    }

    public function processPayment(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('payment_status', 'pending')
            ->firstOrFail();

        $validator = validator($request->all(), [
            'payment_method' => 'required|in:midtrans,ipaymu,balance',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', $validator->errors(), 422);
        }

        try {
            $paymentUrl = $this->paymentService->processPayment($order, $request->payment_method);
            
            return $this->successResponse([
                'payment_url' => $paymentUrl,
            ], 'Payment processed successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Payment processing failed: ' . $e->getMessage(), [], 500);
        }
    }
}
