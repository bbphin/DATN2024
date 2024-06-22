<?php

namespace App\Http\Controllers\Api\Admin\Coupon;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CouponController extends Controller
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
            $data = Coupon::paginate(6);
            $result = [
                'data' => CouponResource::collection($data),
                'meta' => [
                    'per_page' => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'total' => $data->total(),
                ]
            ];
            return ApiResponse(true,Response::HTTP_OK,messageResponseData(),$result);
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
    public function store(CouponRequest $request)
    {
        try {
            $data = $request->all();
            $data['code'] = strtoupper(($request?->code));
            $coupon = Coupon::create($data);
            return ApiResponse(true,Response::HTTP_OK,messageResponseActionSuccess(),new CouponResource($coupon));
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
    public function update(CouponRequest $request, string $id)
    {
        try {
            $data = $request->all();
            $coupon = Coupon::find($id);
            if(empty($coupon)) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseNotFound(),null);
            }

            $data['code'] = strtoupper(($request?->code));
            $coupon->update($data);
            return ApiResponse(true,Response::HTTP_OK,messageResponseActionSuccess(),new CouponResource($coupon));
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
            $coupon = Coupon::find($id);
            if(empty($coupon)) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseNotFound(),null);
            }
            if($coupon->UserCoupon()->exists()) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseActionFailed(),null);
            }
            $coupon->delete();
            return ApiResponse(true,Response::HTTP_OK,messageResponseActionSuccess(),new CouponResource($coupon));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }
}
