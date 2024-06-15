<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlists';

    protected $fillable = [
        'product_id',
        'user_id',
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
