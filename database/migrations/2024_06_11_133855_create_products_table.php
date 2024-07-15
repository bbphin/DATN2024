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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('brand_id')->comment('id thuong hieu cua san pham');
            $table->tinyInteger('color_id')->comment('id mau cua san pham');
            $table->tinyInteger('size_id')->comment('id kich thuoc cua san pham');
            $table->tinyInteger('product_category_id')->comment('id danh muc cua san pham');
            $table->string('name')->comment('ten cua san pham');
            $table->string('slug')->nullable();
            $table->string('image')->nullable()->comment('anh cua san pham');
            $table->string('thumbnail_image')->nullable()->comment('anh cua san pham');
            $table->string('sort_description')->nullable()->comment('mo ta ngan cua san pham');
            $table->text('description')->nullable()->comment('mo ta cua san pham');
            $table->integer('price')->comment('gia cua san pham');
            $table->integer('quantity')->comment('so luong cua san pham');
            $table->integer('view')->comment('luot xem cua san pham')->default('0');
            $table->integer('is_published')->comment('trang thai active cua san pham -> 1> active, 2> unactive')->default('1');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
