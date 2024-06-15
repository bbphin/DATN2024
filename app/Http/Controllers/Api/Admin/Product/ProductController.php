<?php

namespace App\Http\Controllers\Api\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Product::with([
                'Brand',
                'Color',
                'Size',
                'ProductCategory',
            ])->paginate();
            $data->collect()->each(function ($item) {
                $item->brand_id = $item->Brand?->name;
                $item->color_id = $item->Color?->name;
                $item->size_id = $item->Size?->name;
                $item->product_category_id = $item->ProductCategory?->name;
            });
            $result = [
                'data' => ProductResource::collection($data),
                'meta' => [
                    'per_page' => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'total' => $data->total(),
                    'last_page' => $data->lastPage(),
                ],
            ];
            return ApiResponse(true, Response::HTTP_OK, messageResponseData(), $result);
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
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
    public function store(ProductRequest $request)
    {
        try {
            $data = $request->all();
            $data['slug'] = Str::slug($request?->name);
            $images = $request?->image;
            $thumbnail_images = $request?->thumbnail_image;
            if($request->hasFile('image') || $request->hasFile('thumbnail_image')) {
                if (is_array($images) && is_array($thumbnail_images)) {
                    foreach ($images as $image) {
                        $data['image'] = Cloudinary::upload($image->getRealPath(), array(
                            'folder' => "DATN2024/Product",
                            'overwrite' => false,
                            'resource_type' => "image"
                        ))->getSecurePath();
                    }
                    foreach ($thumbnail_images as $thumbnail_image) {
                        $data['thumbnail_image'] = Cloudinary::upload($thumbnail_image->getRealPath(), array(
                            'folder' => "DATN2024/Product",
                            'overwrite' => false,
                            'resource_type' => "image"
                        ))->getSecurePath();
                    }
                } else {
                    $data['image'] = Cloudinary::upload($images->getRealPath(), array(
                        'folder' => "DATN2024/Product",
                        'overwrite' => false,
                        'resource_type' => "image"
                    ))->getSecurePath();
                    $data['thumbnail_image'] = Cloudinary::upload($thumbnail_images->getRealPath(), array(
                        'folder' => "DATN2024/Product",
                        'overwrite' => false,
                        'resource_type' => "image"
                    ))->getSecurePath();
                }
            }
            $product = Product::create($data);

            !empty($product->brand_id) && $product->brand_id = $product->Brand?->name;
            !empty($product->color_id) && $product->color_id = $product->Color?->name;
            !empty($product->size_id) && $product->size_id = $product->Size?->name;
            !empty($product->product_category_id) && $product->product_category_id = $product->ProductCategory?->name;

            return ApiResponse(true, Response::HTTP_CREATED, messageResponseActionSuccess(), new ProductResource($product));
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::find($id);
            if (empty($product)) {
                return ApiResponse(false, Response::HTTP_BAD_REQUEST, messageResponseNotFound(), null);
            }
            $product->update([
                'view' => $product->view + 1,
            ]);

            !empty($product->brand_id) && $product->brand_id = $product->Brand?->name;
            !empty($product->color_id) && $product->color_id = $product->Color?->name;
            !empty($product->size_id) && $product->size_id = $product->Size?->name;
            !empty($product->product_category_id) && $product->product_category_id = $product->ProductCategory?->name;

            return ApiResponse(true, Response::HTTP_OK, messageResponseActionSuccess(), new ProductResource($product));
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
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
    public function update(ProductRequest $request, string $id)
    {
        try {
            $data = $request->all();
            $product = Product::find($id);
            if (empty($product)) {
                return ApiResponse(false, Response::HTTP_BAD_REQUEST, messageResponseNotFound(), null);
            }
            $data['slug'] = Str::slug($request->name);
            $images = $request?->image;
            $thumbnail_images = $request?->thumbnail_image;

            if($request->hasFile('image') || $request->hasFile('thumbnail_image')) {
                if (is_array($images) && is_array($thumbnail_images)) {
                    foreach ($images as $image) {
                        $data['image'] = Cloudinary::upload($image->getRealPath(), array(
                            'folder' => "DATN2024/Product",
                            'overwrite' => false,
                            'resource_type' => "image"
                        ))->getSecurePath();
                    }
                    foreach ($thumbnail_images as $thumbnail_image) {
                        $data['thumbnail_image'] = Cloudinary::upload($thumbnail_image->getRealPath(), array(
                            'folder' => "DATN2024/Product",
                            'overwrite' => false,
                            'resource_type' => "image"
                        ))->getSecurePath();
                    }
                } else {
                    $data['image'] = Cloudinary::upload($images->getRealPath(), array(
                        'folder' => "DATN2024/Product",
                        'overwrite' => false,
                        'resource_type' => "image"
                    ))->getSecurePath();
                    $data['thumbnail_image'] = Cloudinary::upload($thumbnail_images->getRealPath(), array(
                        'folder' => "DATN2024/Product",
                        'overwrite' => false,
                        'resource_type' => "image"
                    ))->getSecurePath();
                }
            }

            $product->update($data);

            !empty($product->brand_id) && $product->brand_id = $product->Brand?->name;
            !empty($product->color_id) && $product->color_id = $product->Color?->name;
            !empty($product->size_id) && $product->size_id = $product->Size?->name;
            !empty($product->product_category_id) && $product->product_category_id = $product->ProductCategory?->name;

            return ApiResponse(true, Response::HTTP_OK, messageResponseActionSuccess(), new ProductResource($product));
        }catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::withTrashed()->find($id);
            if (empty($product)) {
                return ApiResponse(false, Response::HTTP_BAD_REQUEST, messageResponseNotFound(), null);
            }

            if($product->WishList()->exists()) {
                return ApiResponse(false, Response::HTTP_BAD_REQUEST, messageResponseActionFailed(), null);
            }
            $product->forceDelete();

            !empty($product->brand_id) && $product->brand_id = $product->Brand?->name;
            !empty($product->color_id) && $product->color_id = $product->Color?->name;
            !empty($product->size_id) && $product->size_id = $product->Size?->name;
            !empty($product->product_category_id) && $product->product_category_id = $product->ProductCategory?->name;

            return ApiResponse(true, Response::HTTP_OK, messageResponseActionSuccess(), new ProductResource($product));
        }catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }


    // xoa mem
    public function softDelete(string $id) {
        try {
            $product = Product::find($id);
            if (empty($product)) {
                return ApiResponse(false, Response::HTTP_BAD_REQUEST, messageResponseNotFound(), null);
            }
            $product->delete();

            !empty($product->brand_id) && $product->brand_id = $product->Brand?->name;
            !empty($product->color_id) && $product->color_id = $product->Color?->name;
            !empty($product->size_id) && $product->size_id = $product->Size?->name;
            !empty($product->product_category_id) && $product->product_category_id = $product->ProductCategory?->name;

            return ApiResponse(true, Response::HTTP_OK, messageResponseActionSuccess(), new ProductResource($product));

        }catch (Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }


    public function trash()
    {
        try {
            $product = Product::onlyTrashed()->with([
                'Brand',
                'Color',
                'Size',
                'ProductCategory',
            ])->paginate(15);

            $data = [
                'data' => ProductResource::collection($product),
                'meta' => [
                    'per_page' => $product->perPage(),
                    'current_page' => $product->currentPage(),
                    'last_page' => $product->lastPage(),
                    'total' => $product->total(),
                ]
            ];
            $product->collect()->each(function ($item) {
                $item->brand_id = $item->Brand?->name;
                $item->color_id = $item->Color?->name;
                $item->size_id = $item->Size?->name;
                $item->product_category_id = $item->ProductCategory?->name;
            });
            return ApiResponse(true, Response::HTTP_OK, messageResponseActionSuccess(), $data);
        }catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }

    public function restore(string $id)
    {
        try {
            $product = Product::onlyTrashed()->find($id);
            if(empty($product)) {
                return ApiResponse(false, Response::HTTP_BAD_REQUEST, messageResponseNotFound(), null);
            }
            $product->restore();

            !empty($product->brand_id) && $product->brand_id = $product->Brand?->name;
            !empty($product->color_id) && $product->color_id = $product->Color?->name;
            !empty($product->size_id) && $product->size_id = $product->Size?->name;
            !empty($product->product_category_id) && $product->product_category_id = $product->ProductCategory?->name;

            return ApiResponse(true, Response::HTTP_OK, messageResponseActionSuccess(), new ProductResource($product));
        }catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }

    public function search(Request $request)
    {
        try {
            $validate = Validator::make($request->all(),[
                'keyword' => 'required'
            ],[
                'keyword.required' => 'Vui lòng nhập thông tin để tìm kiếm'
            ]);
            if($validate->fails()) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,$validate->errors(),null);
            }
            $keyword = $request?->keyword;
            $data = Product::query()->where('name','LIKE',"{$keyword}%")->get();

            if($data->count() < 0) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseNotFound(),null);
            }
            return ApiResponse(true, Response::HTTP_OK,messageResponseData(),ProductResource::collection($data));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }
}
