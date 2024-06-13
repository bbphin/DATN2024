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
            'id' => $this?->id,
            'brand' => $this?->brand_id,
            'color' => $this?->color_id,
            'size' => $this?->size_id,
            'product_category' => $this?->product_category_id,
            'name' => $this?->name,
            'slug' => $this?->slug,
            'image' => $this?->image,
            'thumbnail_image' => $this?->thumbnail_image,
            'description' => $this?->description,
            'quantity' => $this?->quantity,
            'price' => $this?->price,
            'view' => $this?->view,
            'is_published' => $this?->is_published,
        ];
    }
}
