<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'user_id',
        'product_id',
        'content',
        'rating'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    

    public function User()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
