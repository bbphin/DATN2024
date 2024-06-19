<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
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
            'user' => $this?->user_id,
            'product' => $this?->product_id,
            'size' => $this->Product?->Size?->name,
            'color' => $this->Product?->Color?->name,
            'brand' => $this->Product?->Brand?->name,
            'product_category' => $this->Product?->ProductCategory?->name,
            'image' => $this->Product?->image,
            'thumbnail_image' => $this->Product?->thumbnail_image,
            'price' => $this->Product?->price,
            'view' => $this->Product?->view,
        ];
    }
}
