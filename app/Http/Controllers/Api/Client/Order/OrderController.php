<?php

namespace App\Http\Controllers\Api\Client\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Mail\InvoiceMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        try {
            $data = Order::with(['User','OrderDetail'])->where('user_id', Auth::guard()->id())->paginate(5);
            $result = [
                'data' => OrderResource::collection($data),
                'meta' => [
                    'per_page' => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'total' => $data->total(),
                    'last_page' => $data->lastPage(),
                ],
            ];
            return ApiResponse(true,Response::HTTP_OK,messageResponseData(),$result);
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }


    public function checkout(OrderRequest $request)
    {
        try {
//            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
//            $res = $stripe->tokens->create([
//                'card' => [
//                    'number' => $request->number,
//                    'exp_month' => $request->month,
//                    'exp_year' => $request->year,
//                    'cvc' => $request->cvc,
//                ],
//            ]);
//            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
//            $response = $stripe->charges->create([
//                'amount' => $request->amount,
//                'currency' => 'usd',
//                'source' => $res?->id,
//            ]);
//            return ApiResponse(true,Response::HTTP_CREATED,messageResponseActionSuccess(),$response);\
            $user =  Auth::guard('api')->user();

            $carts = Cart::query()->leftJoin('products',function($join) {
                $join->on('products.id', '=', 'carts.product_id');
            })->leftJoin('users',function($join) {
                $join->on('users.id', '=', 'carts.user_id');
            })->where('user_id',$user?->id)
                ->select('carts.*','products.name as product_name','products.price as product_price',
                    'products.quantity as product_quantity','products.image as product_image','products.size_id as product_size',
                    'products.brand_id as product_brand','products.color_id as product_color','products.product_category_id as product_category'
                    ,'users.name as user_name','users.phone as user_phone','users.address as user_address')->get();

            $order = new Order();

            $order->user_id = $user?->id;
            $order->order_date = Carbon::now()->format('m/d/Y');
            $order->payment_method = $request->payment_method;
            $order->name = $request?->name;
            $order->phone = $request?->phone;
            $order->address = $request?->address;
            $order->save();
            foreach ($carts as $cart) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'price' => $cart->price,
                    'quantity' => $cart->quantity,
                    'total_price' => $cart->price * $cart->quantity,
                ]);
                DB::statement("UPDATE products SET quantity = quantity - $cart->quantity WHERE id = $cart->product_id");
                Cart::destroy($cart->id);
            }
            Mail::to(Auth::guard('api')->user()?->email)->send(new InvoiceMail(Auth::guard('api')->user(),$order));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }
}
