<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\PointRewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(
        private PointRewardService $pointRewardService
    ) {
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users,username',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', $validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'status' => 'active',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Registration successful', 201);
    }

    public function login(LoginRequest $request)
    {
        $field = $request->email;

        $credentials = [
            filter_var($field, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $field,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            return $this->errorResponse('Invalid credentials', [], 401);
        }

        $user = Auth::user();

        if ($user->status !== 'active') {
            return $this->errorResponse('Account is inactive or suspended', [], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful');
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return $this->successResponse([], 'Logout successful');
    }

    public function me()
    {
        $user = Auth::user()->load('level');

        return $this->successResponse($user, 'User retrieved successfully');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', $validator->errors(), 422);
        }

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return $this->errorResponse('Current password is incorrect', [], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return $this->successResponse([], 'Password updated successfully');
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', $validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        $otp = rand(100000, 999999);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(15),
        ]);

        // Send OTP via email/SMS - implement your notification logic here
        // Mail::to($user)->send(new OtpMail($otp));

        return $this->successResponse([], 'OTP sent successfully');
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', $validator->errors(), 422);
        }

        $user = User::where('otp', $request->token)
            ->where('otp_expires_at', '>', now())
            ->first();

        if (!$user) {
            return $this->errorResponse('Invalid or expired token', [], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return $this->successResponse([], 'Password reset successful');
    }
}
