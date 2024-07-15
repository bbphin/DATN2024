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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id'); // id người dùng
            $table->string('name', 100); // tên người dùng
            $table->string('email', 255)->unique(); // địa chỉ email
            $table->string('phone', 255)->nullable()->unique(); // số điện thoại
            $table->integer('role')->default(0); // quyền
            $table->string('password', 255); // mật khẩu
            $table->text('token')->nullable(); // token user
            $table->text('token_google')->nullable(); // token google
            $table->string('avatar')->index()->default('https://img.icons8.com/?size=100&id=43964&format=png&color=000000')->default(1); // ảnh đại diện
            $table->integer('balance')->default(0); // tiền (ví)
            $table->integer('is_banned')->default(0); // khoá tài khoản
            $table->timestamp('deleted_at')->nullable(); // thùng rác
            $table->timestamps(); // created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
