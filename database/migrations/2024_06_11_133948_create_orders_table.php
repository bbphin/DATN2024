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
            $table->string('order_status')->comment('Pending, Success, Cancel')->default('1');
            $table->string('shipment_status')->comment('ORDERPLACE, PACKED, SHIPPED, INTRANSIT, OUTFORDELIVERY, DELIVERED, DELAYED, EXCEPTION, RETURNED')->default('1');
            $table->string('payment_method')->comment('COD, VNPAY')->default('1');
            $table->integer('payment_id')->comment('ID thanh toán khi thanh toán online')->nullable();
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
