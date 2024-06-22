<?php

namespace App\Http\Controllers\Api\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Category::paginate(6);
            return ApiResponse(true,Response::HTTP_OK,messageResponseData(),CategoryResource::collection($data));
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
    public function store(CategoryRequest $request)
    {
        try {
            $data = $request->all();
            $data['slug'] = Str::slug($request?->name);
            $category = Category::create($data);
            return ApiResponse(true,Response::HTTP_CREATED,messageResponseActionSuccess(),new CategoryResource($category));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = Category::find($id);
            if(empty($category)) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseNotFound(),null);
            }
            return ApiResponse(true,Response::HTTP_OK,messageResponseActionSuccess(),new CategoryResource($category));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
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
    public function update(CategoryRequest $request, string $id)
    {
        try {
            $category = Category::find($id);
            if(empty($category)) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseNotFound(),null);
            }
            $data = $request->all();
            $data['slug'] = Str::slug($request?->name);
            $category->update($data);
            return ApiResponse(true,Response::HTTP_OK,messageResponseActionSuccess(),new CategoryResource($category));
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
            $category = Category::find($id);
            if(empty($category)) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseNotFound(),null);
            }
            if($category->ProductCategory()->exists()) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseActionFailed(),null);
            }
            $category->delete();
            return ApiResponse(true,Response::HTTP_OK,messageResponseActionSuccess(),new CategoryResource($category));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }

    public function search(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'keyword' => 'required'
            ], [
                'keyword.required' => 'Vui lòng nhập thông tin để tìm kiếm'
            ]);
            if ($validate->fails()) {
                return ApiResponse(false, Response::HTTP_BAD_REQUEST, $validate->errors(), null);
            }
            $keyword = $request?->keyword;
            $data = Category::query()->where('name', 'LIKE', "{$keyword}%")->get();

            return ApiResponse(true, Response::HTTP_OK, messageResponseData(), ProductResource::collection($data));
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }
}
