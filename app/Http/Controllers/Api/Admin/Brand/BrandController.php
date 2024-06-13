<?php

namespace App\Http\Controllers\Api\Admin\Brand;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Brand::paginate(5);
            return ApiResponse(true, Response::HTTP_OK, messageResponseData(), BrandResource::collection($data));
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request)
    {
        try {
            $data = $request->all();
            $data['slug'] = Str::slug($request?->name);
            $brand = Brand::create($data);
            return ApiResponse(true, Response::HTTP_CREATED, messageResponseActionSuccess(), new BrandResource($brand));
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, string $id)
    {
        try {
            $brand = Brand::find($id);
            if (empty($brand)) {
                return ApiResponse(false, Response::HTTP_BAD_REQUEST, messageResponseNotFound(), null);
            }
            $data = $request->all();
            $data['slug'] = Str::slug($request->name);
            $brand->update($data);
            return ApiResponse(true, Response::HTTP_OK, messageResponseActionSuccess(), new BrandResource($brand));
        } catch (\Exception $e) {
            return ApiResponse(true, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $brand = Brand::find($id);
            if (empty($brand)) {
                return ApiResponse(false, Response::HTTP_BAD_REQUEST, messageResponseNotFound(), null);
            }
            if ($brand->Product()->exists()) {
                return ApiResponse(false, Response::HTTP_BAD_REQUEST, messageResponseActionFailed(), null);
            }
            $brand->delete();
            return ApiResponse(true, Response::HTTP_OK, messageResponseActionSuccess(), new BrandResource($brand));
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }
}
