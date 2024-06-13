<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Product\ProductController;
use App\Http\Controllers\Api\Client\Product\ProductController as ClientProductController;

Route::apiResource('products',ProductController::class);

Route::controller(ProductController::class)->name('products.')->group(function () {
   // xoa mem
   Route::delete('products/soft-delete/{id}','softDelete')->name('softDelete');
   // danh sach san pham sau khi xoa mem
    Route::post('products/trash-list','trash')->name('trash');
    // restore san pham da xoa mem
    Route::get('products/restore/{id}','restore')->name('restore');
});

// hien thi san pham theo product category
Route::controller(ClientProductController::class)->name('product.')->group(function () {
    Route::get('product-category/product/{id}','showProductsByproductCategory')->name('product_category');
});
