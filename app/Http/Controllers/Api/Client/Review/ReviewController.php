<?php

namespace App\Http\Controllers\Api\Client\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class ReviewController extends Controller
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
            $user = Auth::guard('api')->user();
            $data = Review::query()->leftJoin('users',function($join){
                $join->on('reviews.user_id','=','users.id');
            })
            ->leftJoin('products',function($join){
                $join->on('reviews.product_id','=','products.id');
            })->where('user_id',$user?->id)
            ->select('reviews.*','products.name as product_name','users.name as user_name')
            ->get();
            return ApiResponse(true, Response::HTTP_OK,messageResponseData(),ReviewResource::collection($data));
        }catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
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
    public function store(ReviewRequest $request)
    {
        try {
            $user = Auth::guard('api')->user();
            $data = $request->all();
            $data['user_id'] = $user?->id;
            $data['product_id'] = $request->product_id;
            Review::create($data);
            return ApiResponse(true,Response::HTTP_CREATED,messageResponseActionSuccess(),null);
        }catch (Exception $e) {
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
    public function update(ReviewRequest $request, string $id)
    {
        try {
            $user = Auth::guard('api')->user();
            $data = $request->all();
            $review = Review::find($id);
            if(empty($review)) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseNotFound(),null);
            }
            $data['user_id'] = $user?->id;
            $data['product_id'] = $request->product_id;
            $review->update($data);
            return ApiResponse(true, Response::HTTP_OK,messageResponseActionSuccess(),new ReviewResource($review));
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
            $user = Auth::guard('api')->user();
            $review = Review::find($id);
            if(empty($review)){
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseNotFound(),null);
            }
            if($review?->user_id !== $user?->id) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseActionFailed(),null);
            }
            $review->delete();
            return ApiResponse(true,Response::HTTP_OK,messageResponseActionSuccess(),new ReviewResource($review));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }
}
