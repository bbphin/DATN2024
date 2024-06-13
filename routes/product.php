<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Product\ProductController;

Route::apiResource('products',ProductController::class);

Route::controller(ProductController::class)->name('products.')->group(function () {
   // xoa mem
   Route::delete('products/soft-delete/{id}','softDelete')->name('softDelete');
   // danh sach san pham sau khi xoa mem
    Route::post('products/trash-list','trash')->name('trash');
    // restore san pham da xoa mem
    Route::get('products/restore/{id}','restore')->name('restore');
});

