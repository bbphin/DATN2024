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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('user_id')->comment('id của người dùng');
            $table->tinyInteger('product_id')->comment('id của sản phẩm');
            $table->tinyInteger('order_id')->comment('id của đơn hàng');
            $table->tinyInteger('order_payment_status')->comment('trạng thái đơn hàng --> ví dụ: đơn hàng chưa thanh toán hoặc đã tt');
            $table->string('refund_reason')->comment('lý do hoàn trả');
            $table->tinyInteger('refund_status')->comment('trạng thái hoàn trả --> 1. chờ xác nhận, 2. đang xử lý, 3. hoàn tất ( chưa hoàn tất)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
