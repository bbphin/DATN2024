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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('user_id')->comment('id cua nguoi dung');
            $table->tinyInteger('product_id')->comment('id cua san pham');
            $table->integer('quantity')->comment('so luong cua 1 san pham da mua');
            $table->decimal('price')->comment('gia cua tong so luong san pham da mua');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
