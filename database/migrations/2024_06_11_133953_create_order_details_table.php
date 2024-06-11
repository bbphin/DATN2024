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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('product_id')->comment('id của sản phẩm');
            $table->tinyInteger('order_id')->comment('id của đơn hàng');
            $table->decimal('price')->comment('giả của tổng đơn hàng');
            $table->integer('quantity')->comment('số lượng mua');
            $table->decimal('total_price')->comment('tổng giá của sản phẩm');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
