<?php

namespace App\Http\Controllers\Api\Client\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    // hien thi danh sach san pham theo product category
    public function showProductsByproductCategory(string $id)
    {
        try {
            $productByProductCategory = ProductCategory::query()->leftJoin('products',function ($join) {
                $join->on('product_categories.id', '=', 'products.product_category_id');
            })->where('product_category_id','=',$id)->get();
            return ApiResponse(true,Response::HTTP_OK,messageResponseData(),ProductResource::collection($productByProductCategory));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }
}
