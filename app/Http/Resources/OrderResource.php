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
            'name' => $this?->name,
            'phone' => $this?->phone,
            'address' => $this?->address,
            'order_date' => $this?->order_date,
            'payment_method' => $this?->payment_method,
            'order_status' => $this?->order_status,
            'product' => $this->getInfoProduct('name'),
            'color' => $this->getInfoProduct('color'),
            'size' => $this->getInfoProduct('size'),
            'image' => $this->getInfoProduct('image'),
            'price' => $this->getInfoOrderDetail('price'),
            'quantity' => $this->getInfoOrderDetail('quantity'),
            'total_price' => $this->getInfoOrderDetail('total_price'),
            'note' => $this?->note,
        ];
    }

    private function getInfoProduct($name)
    {
        $data = [];
        foreach ($this->OrderDetail as $item) {
            if ($item->Product) {

                if($name === 'size') {
                    $data[] = $item->Product->Size->name;
                }else if ($name === 'color'){
                    $data[] = $item->Product->Color->name;
                }else {
                    $data[] = $item->Product->$name;
                }
            }
        }

        return $data;
    }

    private function getInfoOrderDetail($name)
    {
        $data = [];
        foreach ($this->OrderDetail as $item) {
            $data[] = $item->$name;
        }
        return $data;
    }
}
