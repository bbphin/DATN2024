<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    protected $fillable = [
        'product_id',
        'order_id',
        'price',
        'total_price',
        'quantity'
    ];
    public function Order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'order_id', 'order_id');
    }
}
