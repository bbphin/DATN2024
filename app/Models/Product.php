<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'brand_id',
        'color_id',
        'size_id',
        'product_category_id',
        'name',
        'slug',
        'image',
        'thumbnail_image',
        'sort_description',
        'description',
        'price',
        'quantity',
        'view',
        'is_published',
    ];

    public function Brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function Color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }

    public function Size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }

    public function ProductCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'id');
    }

    public function WishList()
    {
        return $this->hasMany(WishList::class, 'product_id', 'id');
    }


    public function Cart()
    {
        return $this->hasMany(Cart::class, 'product_id', 'id');
    }
}
