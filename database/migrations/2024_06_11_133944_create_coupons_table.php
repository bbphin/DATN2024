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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->comment('mã giảm giá của sản phẩm');
            $table->string('discount_type')->comment('loại mã giảm giá')->nullable();
            $table->string('start_date')->comment('ngày bắt đầu sử dụng')->nullable();
            $table->string('end_date')->comment('ngày kết thúc')->nullable();
            $table->string('min_spend')->comment('số tiền tối thiểu đơn hàng có thể sử dụng mã -> ví dụ: 100k');
            $table->string('max_discount_amount')->comment('số tiền giảm tối đa');
            $table->integer('total_usage_count')->comment('số lượng có thể sử dụng');
            $table->integer('user_usage_count')->comment('số lượng người có thể sử dụng');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
