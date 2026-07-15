<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'code' => $this->code,
            'user' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
                'email' => $this->user->email ?? null,
                'username' => $this->user->username ?? null,
                'phone' => $this->user->phone ?? null,
            ],
            'type' => $this->type,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'payment_gateway' => $this->payment_gateway,
            'amount' => $this->amount,
            'bank' => $this->bank ? [
                'id' => $this->bank->id,
                'name' => $this->bank->name,
                'account_number' => $this->bank->account_number,
                'account_name' => $this->bank->account_name,
            ] : null,
            'order_products' => $this->orderProducts->map(function ($orderProduct) {
                return [
                    'id' => $orderProduct->id,
                    'product' => [
                        'id' => $orderProduct->product->id ?? null,
                        'name' => $orderProduct->product->name ?? null,
                        'slug' => $orderProduct->product->slug ?? null,
                        'image' => $orderProduct->product && $orderProduct->product->image
                            ? asset('storage/' . $orderProduct->product->image)
                            : null,
                    ],
                    'sub_product' => [
                        'id' => $orderProduct->subProduct->id ?? null,
                        'name' => $orderProduct->subProduct->name ?? null,
                        'code' => $orderProduct->subProduct->code ?? null,
                    ],
                    'account_id' => $orderProduct->account_id,
                    'input_data' => $orderProduct->input_data,
                    'price' => $orderProduct->price,
                    'qty' => $orderProduct->qty,
                    'total' => $orderProduct->total,
                ];
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}