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
            $user =  Auth::guard('api')->user();

            $carts = $this->getUserCart($user);
            $order = $this->createOrder($request,$user);

            foreach ($carts as $cart) {
                $this->createOrderDetail($order,$cart);

                $this->createPayment($order,$cart);

//                DB::statement("UPDATE products SET quantity = quantity - $cart->quantity WHERE id = $cart->product_id");
                DB::statement("UPDATE products SET quantity = quantity - ? WHERE id = ?", [$cart->quantity, $cart->product_id]);
                Cart::destroy($cart->id);
            }
            Mail::to(Auth::guard('api')->user()?->email)->send(new InvoiceMail(Auth::guard('api')->user(),$order));
        }catch (\Exception $e) {
            return ApiResponse(false,Response::HTTP_BAD_REQUEST,$e->getMessage(),null);
        }
    }


    private function getUserCart($user)
    {
        return Cart::query()->leftJoin('products',function($join) {
            $join->on('products.id', '=', 'carts.product_id');
        })->leftJoin('users',function($join) {
            $join->on('users.id', '=', 'carts.user_id');
        })->where('user_id',$user?->id)
            ->select('carts.*','products.name as product_name','products.price as product_price',
                'products.quantity as product_quantity','products.image as product_image','products.size_id as product_size',
                'products.brand_id as product_brand','products.color_id as product_color','products.product_category_id as product_category'
                ,'users.name as user_name','users.phone as user_phone','users.address as user_address')->get();
    }


    private function createOrder(OrderRequest $request,$user)
    {
        return Order::create([
            'user_id' => $user->id,
            'order_date' => Carbon::now()->format('Y-m-d'),
            'payment_method' => $request->payment_method,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
    }

    private function createOrderDetail($order,$cart)
    {
        return OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $cart->product_id,
            'price' => $cart->price,
            'quantity' => $cart->quantity,
            'total_price' => $cart->price * $cart->quantity,
        ]);
    }

    private function createPayment($order,$cart)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "https://localhost/vnpay_php/vnpay_return.php";
        $vnp_TmnCode = config('services.payment.payment_terminal'); //Mã website tại VNPAY
        $vnp_HashSecret = config('services.payment.payment_secret'); //Chuỗi bí mật

        $vnp_TxnRef = $order?->id; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Thanh toán hóa đơn";
        $vnp_OrderType = "Thanh toán Online";
        $vnp_Amount = ($cart->price * $cart->quantity) * 100;
        $vnp_Locale = 'VN';
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );
       return $returnData;
    }
}
