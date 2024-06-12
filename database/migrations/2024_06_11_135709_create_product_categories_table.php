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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('category_id')->comment('id của danh mục');
            $table->string('name')->comment('ten cua danh muc sản phẩm');
            $table->string('slug')->nullable();
            $table->text('description')->nullable()->comment('mo ta cua danh muc sản phẩm');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
