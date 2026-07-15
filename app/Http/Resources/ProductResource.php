<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'slug' => $this->slug,
            'category' => [
                'id' => $this->category->id ?? null,
                'name' => $this->category->name ?? null,
                'slug' => $this->category->slug ?? null,
            ],
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'description' => $this->description,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'sub_products' => $this->sub_products->map(function ($subProduct) {
                return [
                    'id' => $subProduct->id,
                    'name' => $subProduct->name,
                    'code' => $subProduct->code,
                    'type' => $subProduct->type,
                    'price' => $subProduct->price,
                    'discount' => $subProduct->discount,
                    'special_discount' => $subProduct->special_discount,
                    'stock' => $subProduct->stock,
                    'final_price' => $subProduct->final_price,
                ];
            }),
        ];
    }
}