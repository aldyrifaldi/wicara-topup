<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'phone' => $this->phone,
            'role' => $this->role,
            'level' => [
                'id' => $this->level->id ?? null,
                'name' => $this->level->name ?? null,
                'slug' => $this->level->slug ?? null,
                'discount_percent' => $this->level->discount_percent ?? 0,
                'cashback_percent' => $this->level->cashback_percent ?? 0,
            ],
            'balance' => [
                'amount' => $this->balanceCustomer->sum('amount') ?? 0,
            ],
            'points' => $this->pointRewards->sum('points') ?? 0,
            'is_active' => $this->is_active,
            'otp_verified' => $this->otp_verified,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}