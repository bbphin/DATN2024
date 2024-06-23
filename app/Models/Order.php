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
        'name',
        'phone',
        'address',
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
    public function getOrderStatusDescriptionAttribute()
    {
        switch ($this->order_status) {
            case 1:
                return 'Chưa thanh toán';
            case 2:
                return 'Đã thanh toán';
            default:
                return 'Không xác định';
        }
    }
    public function getOrderPaymentMethodDescriptionAttribute()
    {
        switch ($this->payment_method) {
            case 1:
                return 'Thanh toán tại cửa hàng';
            case 2:
                return 'Thanh toán online';
            default:
                return 'Không xác định';
        }
    }
}
