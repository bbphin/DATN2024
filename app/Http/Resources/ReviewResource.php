<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'content' => $this?->content,
            'rating' => $this?->rating,
            'user' => $this?->User?->name,
            'product' => $this?->Product?->name,
            'product_category' => $this?->Product->ProductCategory?->name,
            'category' => $this?->Product->ProductCategory->Category?->name,
        ];
    }
}
