<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'discoutn_type',
        'min_spend',
        'max_discount_amount',
        'start_date',
        'end_date',
        'total_usage_count',
        'user_usage_count',
    ];
}
