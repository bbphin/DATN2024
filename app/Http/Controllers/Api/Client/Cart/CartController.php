<?php

namespace App\Http\Controllers\Api\Client\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
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
            $data = Cart::query()->leftJoin('products',function($join) {
                $join->on('products.id', '=', 'carts.product_id');
            })->leftJoin('users',function($join) {
                $join->on('users.id', '=', 'carts.user_id');
            })->where('user_id',$user?->id)
            ->select('carts.*','products.name as product_name','products.price as product_price',
            'products.quantity as product_quantity','products.image as product_image','products.size_id as product_size',
                'products.brand_id as product_brand','products.color_id as product_color','products.product_category_id as product_category'
                ,'users.name as user_name')->get();
            return ApiResponse(true, Response::HTTP_OK,messageResponseData(),CartResource::collection($data));
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
    public function store(CartRequest $request)
    {
        try {
            $data = $request->all();
            $user = Auth::guard('api')->user();
            $product = Product::find($request?->product_id);
            if($product?->quantity < $request->quantity) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,'Số lượng sản phẩm không đúng',null);
            }
            $data['user_id'] = $user?->id;
            $data['product_id'] = $request->product_id;
            $data['size_id'] = $request->size_id;
            $data['color_id'] = $request->color_id;
            $data['price'] = $product?->price;
            $data['total_price'] = $request->quantity * $product?->price;

            $cart = Cart::query()->where([['product_id',$product?->id],['user_id',$user?->id]])->exists();
            if($cart) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,'Sản phẩm đã tồn tại trong giỏ hàng');
            }
            $createdCart = Cart::create($data);
            return ApiResponse(true,Response::HTTP_CREATED,messageResponseActionSuccess(), new CartResource($createdCart));

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
    public function update(CartRequest $request, string $id)
    {
        try {
            $cart = Cart::find($id);
            $product = Product::find($request?->product_id);

            if($request->quantity > $product?->quantity) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,'Cập nhật không thành công, do không đủ số lương');
            }
            if($request->quantity <= 0) {
                return $cart->delete();
            }
            $cart->update([
                'quantity' => $request->quantity,
                'total_price' => $request->quantity * $product?->price
            ]);
            return ApiResponse(true,Response::HTTP_OK,'Cập nhật giỏ hàng thành công',new CartResource($cart));
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
            $cart = Cart::find($id);
            if(empty($cart)) {
                return ApiResponse(false,Response::HTTP_BAD_REQUEST,messageResponseNotFound(),null);
            }
            $cart->delete();
            return ApiResponse(true,Response::HTTP_OK,messageResponseActionSuccess(),new CartResource($cart));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }
}
