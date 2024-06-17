<?php

namespace App\Http\Controllers\Api\Client\WishList;

use App\Http\Controllers\Controller;
use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::guard('api')->user();

            $data = Wishlist::query()->leftJoin('users',function($join){
                $join->on('users.id','=','wishlists.user_id');
            })->leftJoin('products',function($join){
                $join->on('products.id','=','wishlists.product_id');
            })->where('user_id',$user?->id)->select(
                'wishlists.*','products.id as productId','products.id as productId',
                'products.name as productName','products.price as productPrice','products.image as productImage',
                'products.color_id as productColor','products.size_id as productSize','products.brand_id as productBrand',
                'products.product_category_id as productCategory','users.id as userId','users.name as userName'
            )->get();
            $data->collect()->each(function($item){
                $item->user_id = $item->User?->name;
                $item->product_id = $item->Product?->name;
            });
            return ApiResponse(true,Response::HTTP_OK,messageResponseData(),WishlistResource::collection($data));
        }catch (Exception $e) {
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
    public function store(Request $request)
    {
        try {
            $wishlist = Wishlist::firstOrCreate ([
                'product_id' => $request->product_id,
                'user_id' => $request->user_id,
            ]);
            return ApiResponse(true, Response::HTTP_CREATED,messageResponseActionSuccess(),new WishlistResource($wishlist));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $wishlist = Wishlist::destroy($id);
            return ApiResponse(true,Response::HTTP_OK,messageResponseActionSuccess(),new WishlistResource($wishlist));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }
}
