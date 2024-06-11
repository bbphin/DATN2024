<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'brand_id',
        'color_id',
        'size_id',
        'category_id',
        'name',
        'slug',
        'image',
        'thumbnail_image',
        'sort_description',
        'description',
        'price',
        'quatity',
        'view',
        'is_published',
    ];
}
