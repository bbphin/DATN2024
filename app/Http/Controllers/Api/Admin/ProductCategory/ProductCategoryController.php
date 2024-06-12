<?php

namespace App\Http\Controllers\Api\Admin\ProductCategory;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Models\Category;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = ProductCategory::with('Category')->get();
            $data->collect()->each(function ($item) {
                $item->category_id = $item->Category?->name;
            });
            return ApiResponse(true,Response::HTTP_OK,messageResponseData(),ProductCategoryResource::collection($data));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCategoryRequest $request)
    {
        try {
//            $data = Category::with('ProductCategory')->pluck('id','name');
            $data = $request->all();
            $data['slug'] = Str::slug($request?->name);
            $productCategory = ProductCategory::create($data);
            return ApiResponse(true,Response::HTTP_CREATED,messageResponseActionSuccess(),new ProductCategoryResource($productCategory));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCategoryRequest $request, string $id)
    {
        try {
            $productCategory = ProductCategory::find($id);
            if(empty($productCategory)) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseNotFound(),null);
            }
            $data = $request->all();
            $data['slug'] = Str::slug($request?->name);
            $productCategory->update($data);
            return ApiResponse(true,Response::HTTP_OK,messageResponseActionSuccess(),new ProductCategoryResource($productCategory));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $productCategory = ProductCategory::find($id);
            if(empty($productCategory)) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseNotFound(),null);
            }
            $productCategory->delete();
            return ApiResponse(true,Response::HTTP_OK,messageResponseActionSuccess(),new ProductCategoryResource($productCategory));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }
}
