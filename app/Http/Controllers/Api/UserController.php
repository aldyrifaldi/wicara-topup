<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use App\Services\PointRewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        private PointRewardService $pointRewardService
    ) {
    }

    public function profile()
    {
        $user = Auth::user()->load(['level', 'notifications' => function ($query) {
            $query->where('is_read', false)
                ->orderBy('created_at', 'desc')
                ->limit(10);
        }]);

        return $this->successResponse($user, 'Profile retrieved successfully');
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $user->update($request->validated());

        return $this->successResponse($user->load('level'), 'Profile updated successfully');
    }

    public function balance()
    {
        $user = Auth::user();

        return $this->successResponse([
            'balance' => $user->balance,
            'balance_formatted' => 'Rp ' . number_format($user->balance, 0, ',', '.'),
        ], 'Balance retrieved successfully');
    }

    public function orders(Request $request)
    {
        $orders = $user->orders()
            ->with(['items.product', 'items.subProduct'])
            ->orderBy('created_at', 'desc');

        // Status filter
        if ($request->has('status')) {
            $orders->where('status', $request->status);
        }

        $orders = $orders->paginate($request->per_page ?? 15);

        return $this->successResponse($orders, 'Orders retrieved successfully');
    }

    public function points()
    {
        $user = Auth::user();

        $pointTransactions = $user->pointTransactions()
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        $currentBalance = $this->pointRewardService->getBalance($user);

        return $this->successResponse([
            'current_balance' => $currentBalance,
            'transactions' => $pointTransactions,
        ], 'Points retrieved successfully');
    }

    public function notifications(Request $request)
    {
        $user = Auth::user();

        $query = $user->notifications()->orderBy('created_at', 'desc');

        // Unread only filter
        if ($request->has('unread_only') && $request->unread_only) {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate($request->per_page ?? 15);

        return $this->successResponse($notifications, 'Notifications retrieved successfully');
    }

    public function markNotificationRead($id)
    {
        $user = Auth::user();

        $notification = $user->notifications()->findOrFail($id);
        $notification->update(['is_read' => true]);

        return $this->successResponse([], 'Notification marked as read');
    }

    public function markAllNotificationsRead()
    {
        $user = Auth::user();

        $user->notifications()->update(['is_read' => true]);

        return $this->successResponse([], 'All notifications marked as read');
    }

    public function leaderboard(Request $request)
    {
        $limit = $request->limit ?? 10;

        $topUsers = User::with('level')
            ->orderBy('total_points', 'desc')
            ->limit($limit)
            ->get();

        $currentUserRank = User::where('total_points', '>', Auth::user()->total_points)->count() + 1;

        return $this->successResponse([
            'top_users' => $topUsers,
            'current_user_rank' => $currentUserRank,
        ], 'Leaderboard retrieved successfully');
    }

    public function topupBalance(Request $request)
    {
        $validator = validator($request->all(), [
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required|in:midtrans,ipaymu',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', $validator->errors(), 422);
        }

        // Create balance topup order
        $order = Auth::user()->orders()->create([
            'code' => 'TOPUP-' . strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8)),
            'type' => 'topup',
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $request->payment_method,
            'total_amount' => $request->amount,
        ]);

        // Create order item
        $order->items()->create([
            'product_name' => 'Saldo Dompet',
            'price' => $request->amount,
            'quantity' => 1,
        ]);

        try {
            $paymentUrl = app(PaymentService::class)->processPayment($order, $request->payment_method);
            
            return $this->successResponse([
                'order' => $order,
                'payment_url' => $paymentUrl,
            ], 'Topup order created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Topup failed: ' . $e->getMessage(), [], 500);
        }
    }
}
