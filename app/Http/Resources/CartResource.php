<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this?->id,
            'user' => $this?->User?->name,
            'phone' => $this?->User?->phone,
            'address' => $this?->User?->address,
            'product' => $this?->Product?->name,
            'image' => $this?->Product?->image,
            'quantity' => $this?->quantity,
            'price' => $this?->price,
            'total_price' => $this?->total_price,
            'brand' => $this?->Product->Brand?->name,
            'color' => $this?->Product->Color?->name,
            'size' => $this?->Product->Size?->name,
            'product_category' => $this?->Product->ProductCategory?->name,
            'category' => $this?->Product->ProductCategory->Category?->name,
        ];
    }
}
