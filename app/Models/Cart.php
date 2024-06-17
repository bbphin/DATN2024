<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'price',
        'total_price',
        'size_id',
        'color_id',
    ];


    public function Product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function User()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
