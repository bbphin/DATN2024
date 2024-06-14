<?php

namespace App\Http\Controllers\Api\Admin\Color;

use App\Http\Controllers\Controller;
use App\Http\Requests\ColorRequest;
use App\Http\Resources\ColorResource;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Color::all();
            return ApiResponse(true,Response::HTTP_OK,messageResponseData(),ColorResource::collection($data));
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
    public function store(ColorRequest $request)
    {
        try {
            $color = Color::create($request->all());
            return ApiResponse(true,Response::HTTP_CREATED,messageResponseActionSuccess(),new ColorResource($color));
        }catch (\Exception $e){
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
    public function update(ColorRequest $request, string $id)
    {
        try {
            $color = Color::find($id);
            if(empty($color)) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseNotFound(),null);
            }
            $color->update($request->all());
            return ApiResponse(true,Response::HTTP_OK,messageResponseActionSuccess(),new ColorResource($color));
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
            $color = Color::find($id);
            if(empty($color)) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseNotFound(),null);
            }
            if($color->Product()->exists()) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseActionFailed(),null);
            }
            $color->delete();
            return ApiResponse(true,Response::HTTP_OK,messageResponseActionSuccess(),new ColorResource($color));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }
}
