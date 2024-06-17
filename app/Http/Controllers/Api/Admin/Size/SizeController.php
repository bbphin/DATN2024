<?php

namespace App\Http\Controllers\Api\Admin\Size;

use App\Http\Controllers\Controller;
use App\Http\Requests\SizeRequest;
use App\Http\Resources\SizeResource;
use App\Models\Size;
use Cloudinary\Api\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\FlareClient\Api;

class SizeController extends Controller
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
            $data = Size::all();
            return ApiResponse(true, Response::HTTP_OK, messageResponseData(), SizeResource::collection($data));
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SizeRequest $request)
    {
        try {
            $data = Size::create($request->all());
            return ApiResponse(true, Response::HTTP_CREATED, messageResponseActionSuccess(), new SizeResource($data));
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SizeRequest $request, string $id)
    {
        try {
            $size = Size::find($id);
            if (empty($size)) {
                return ApiResponse(false, Response::HTTP_BAD_REQUEST, messageResponseNotFound(), null);
            }
            $size->update($request->all());
            return ApiResponse(true, Response::HTTP_OK, messageResponseActionSuccess(), new SizeResource($size));
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
            $size = Size::find($id);
            if (empty($size)) {
                return ApiResponse(false, Response::HTTP_BAD_REQUEST, messageResponseNotFound(), null);
            }
            if ($size->Product()->exists()) {
                return ApiResponse(false, Response::HTTP_BAD_REQUEST, messageResponseActionFailed(), null);
            }
            $size->delete();
            return ApiResponse(true, Response::HTTP_OK, messageResponseActionSuccess(), new SizeResource($size));
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }
}
