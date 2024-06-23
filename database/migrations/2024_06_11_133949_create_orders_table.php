<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('user_id')->comment('id của người dùng');
            $table->string('name')->comment('ten cua user order');
            $table->string('phone')->comment('so dien thoai cua user order');
            $table->string('address')->comment('dia chi cua user order');
            $table->string('order_date')->comment('thời gian order')->nullable();
            $table->tinyInteger('order_status')->comment('trạng thái order --> 1. chờ xác nhận khi thanh toán off, 2. Xác nhận đơn hàng khi tt onl')->default('1');
            $table->tinyInteger('payment_method')->comment('phương thức thanh toán --> 1. off, 2. onl')->default('1');
            $table->string('note')->comment('ghi chú khi đặt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
