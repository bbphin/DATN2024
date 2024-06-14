<?php

namespace App\Http\Controllers\Api\Client\ProductCategory;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductCategoryController extends Controller
{
    // lấy danh mục sản phẩm từ danh mục cha
    // ví dụ: quần -> quần nam
    public function productCategory(string $id)
    {
        try {
            $productCategory = Category::query()->leftJoin('product_categories',function($join) {
                $join->on('product_categories.category_id', '=', 'categories.id');
            })->where('category_id',$id)->limit(6)->get();
            return ApiResponse(true,Response::HTTP_OK,messageResponseData(),ProductCategoryResource::collection($productCategory));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }
}
