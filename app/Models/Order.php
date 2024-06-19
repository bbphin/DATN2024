<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_status',
        'payment_method',
        'order_date',
        'note'
    ];
    public function review()
    {
        return $this->hasOne(Review::class, 'order_id', 'id')->where('user_id', auth()->user()->id);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderDetail::class, 'order_id')
            ->with(['product']);
    }
    public function orderDetails() {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}
