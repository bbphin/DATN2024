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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class OrderController extends Controller
{
    public $hash_secret;
    public $tmncode;
    public function __construct()
    {
        // có thể lên https://sandbox.vnpayment.vn/devreg/ để đăng ký sanbox
        $this->hash_secret = 'TSUHRVGRSQRFWCCTVNUUJBENWNHVTRBB';
        $this->tmncode = '1J7H9XAA';
    }

    public function index()
    {
        try {
            $data = Order::with(['User', 'OrderDetail'])->where('user_id', Auth::guard('api')->id())->paginate(5);
            $result = [
                'data' => OrderResource::collection($data),
                'meta' => [
                    'per_page' => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'total' => $data->total(),
                    'last_page' => $data->lastPage(),
                ],
            ];
            return ApiResponse(true, Response::HTTP_OK, messageResponseData(), $result);
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }


    public function checkout(OrderRequest $request)
    {
        try {
            $user =  Auth::guard('api')->user();
            $carts = $this->getUserCart($user);
            if (count($carts) > 0) {
                $redirectUri = request()->input('redirect_uri_vnpay');
                $cacheKey = 'redirect_uri_vnpay_' . request()->ip();
                if (Cache::has($cacheKey)) {
                    Cache::forget($cacheKey);
                }
                Cache::put($cacheKey, $redirectUri, now()->addHours(1));
                $order = $this->createOrder($request, $user);
                $totalPrice = 0;
                foreach ($carts as $cart) {
                    $this->createOrderDetail($order, $cart);
                    $totalPrice += $cart->price * $cart->quantity;
                    DB::statement("UPDATE products SET quantity = quantity - ? WHERE id = ?", [$cart->quantity, $cart->product_id]);
                    Cart::destroy($cart->id);
                }
                if ($request->payment_method == "VNPAY") {
                    $redirectUri = Cache::get('redirect_uri_vnpay_' . request()->ip());
                    $redirectUrl = $redirectUri;
                    Cache::forget('redirect_uri_vnpay_' . request()->ip());

                    $response = $this->createLinkPayment($totalPrice, $redirectUrl);
                    if ($response['code'] == 00 && $response['orderCode']) {
                        $data = [
                            'payment_id' => $response['orderCode'],
                        ];
                        $orderUpdate = Order::where('id', $order->id)->update($data);
                        if ($orderUpdate) {
                            return success('Thành công', ['url' => $response['data']]);
                        } else {
                            return errors('Lỗi trong quá trình xử lý');
                        }
                    } else {
                        return errors('Xử lý VNPAY thất bại');
                    }
                } else {
                    return success('Tạo hoá đơn thành công');
                }
            } else {

                return errors('Vui lòng thêm sản phẩm vào giỏ hàng.');
            }
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_BAD_REQUEST, $e->getMessage(), null);
        }
    }
    private function createOrderDetail($order, $cart)
    {
        return OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $cart->product_id,
            'price' => $cart->price,
            'quantity' => $cart->quantity,
            'total_price' => $cart->price * $cart->quantity,
        ]);
    }


    private function getUserCart($user)
    {
        return Cart::query()->leftJoin('products', function ($join) {
            $join->on('products.id', '=', 'carts.product_id');
        })->leftJoin('users', function ($join) {
            $join->on('users.id', '=', 'carts.user_id');
        })->where('user_id', $user?->id)
            ->select(
                'carts.*',
                'products.name as product_name',
                'products.price as product_price',
                'products.quantity as product_quantity',
                'products.image as product_image',
                'products.size_id as product_size',
                'products.brand_id as product_brand',
                'products.color_id as product_color',
                'products.product_category_id as product_category',
                'users.name as user_name',
                'users.phone as user_phone',
                'users.address as user_address'
            )->get();
    }


    private function createOrder(OrderRequest $request, $user)
    {
        return Order::create([
            'user_id' => $user->id,
            'order_date' => now(),
            'payment_method' => $request->payment_method,
            'order_status' => 'PENDING',
            'name' => $request->name,
            'phone' => $request->phone,
            'note' => $request->note,
            'address' => $request->address,
            'shipment_status' => 'ORDERPLACE',
        ]);
    }

    public function createLinkPayment($amount, $url)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_TmnCode = $this->tmncode;
        $vnp_HashSecret = $this->hash_secret;
        $vnp_TxnRef = intval(substr(strval(microtime(true) * 10000), -6)); //Random Mã đơn hàng
        $vnp_Returnurl = $url;
        $user = auth()->guard('api')->user();
        $vnp_OrderInfo = "$user->name thanh toán đơn hàng";
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $amount  * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
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
            // $vnp_Url .= '&son';
        }
        if (isset($_POST['redirect'])) {
            return redirect($vnp_Url);
        } else {
            session()->put('vnpay_orderCode', $vnp_TxnRef);
            return ['code' => '00', 'message' => 'success', 'data' => $vnp_Url, 'orderCode' => $vnp_TxnRef];
        }
    }
    public function getPaymentLinkInformation($request, $orderCode)
    {
        $inputData = array();
        $returnData = array();
        foreach ($request->query() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->hash_secret);
        $vnpTranId = $inputData['vnp_TransactionNo'];
        $vnp_BankCode = $inputData['vnp_BankCode'];
        $vnp_Amount = $inputData['vnp_Amount'] / 100; // Số tiền thanh toán VNPAY phản hồi
        $Status = 0;
        $orderId = $inputData['vnp_TxnRef'];

        try {
            //Check Orderid
            if ($orderCode == $inputData['vnp_TxnRef']) {
                //Kiểm tra checksum của dữ liệu
                if ($secureHash == $vnp_SecureHash) {
                    if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
                        $Status = 1; // Trạng thái thanh toán thành công
                        $returnData['status'] = 'PAID';
                    } else {
                        $Status = 2; // Trạng thái thanh toán thất bại / lỗi
                        $returnData['status'] = 'CANCELED';
                    }
                    $returnData['RspCode'] = '00';
                    $returnData['Message'] = 'Confirm Success';
                } else {
                    $returnData['RspCode'] = '97';
                    $returnData['Message'] = 'Invalid signature';
                    $returnData['status'] = 'UNKNOW';
                }
            } else {
                $returnData['RspCode'] = '99';
                $returnData['Message'] = 'Unknow error';
                $returnData['status'] = 'UNKNOW';
            }
        } catch (\Exception $e) {
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknow error';
            $returnData['status'] = 'UNKNOW';
        }
        return response()->json($returnData)->getData();
    }

    public function checkPayVNPAY(Request $request)
    {
        if (session()->has('vnpay_orderCode')) {
            $orderCode = session()->get('vnpay_orderCode');
        } else {
            $orderCode = $request->query('vnp_TxnRef');
        }
        if ($orderCode) {
            $order = Order::where('payment_id', $orderCode)->first();
            $getInfoPayment = $this->getPaymentLinkInformation($request, $orderCode);
            if ($order && $getInfoPayment->RspCode == 00 && $getInfoPayment) {
                if ($getInfoPayment->status != $order->order_status) {
                    $data = [
                        'order_status' => $getInfoPayment->status,
                        'shipment_status' => $getInfoPayment->status == "PAID" ? 'PACKED' : 'ORDERPLACE',
                    ];
                    $update = Order::where('id', $order->id)->where('user_id', auth()->guard('api')->user()->id)->update($data);
                    if ($update) {
                        // Gửi mail cho khách, nhân viên biết,...
                        if (Session::has('vnpay_orderCode')) {
                            Session::forget('vnpay_orderCode');
                        }
                        Mail::to(Auth::guard('api')->user()?->email)->send(new InvoiceMail(Auth::guard('api')->user(), $order));
                        return success('Thanh toán thành công');
                    } else {
                        return errors('Lỗi trong quá trình xử lý');
                    }
                }
                if ($order->order_status == "PAID") {
                    return success('Đã thanh toán');
                }
            } else {
                return errors('Lỗi trong quá trình xử lý hoá đơn');
            }
        } else {
            return errors('Sai thông tin đơn hàng');
        }
    }

    public function trackOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            /**
             * @example 1 hoặc INV1
             */
            'code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return validationErrors($validator->errors());
        }

        if ($request->code != null) {
            $searchCode = preg_replace('/[^0-9]/', '', $request->code);
            $order = Order::where('id', $searchCode)
                ->where('user_id', auth()->guard('api')->user()->id)
                ->first();

            if (!is_null($order)) {
                $statuses = [
                    'ORDERPLACE' => 'đã được tạo',
                    'PACKED' => 'đã nhận và đang đóng gói',
                    'SHIPPED' => 'đã được vận chuyển',
                    'INTRANSIT' => 'đang trên đường đến điểm đến',
                    'OUTFORDELIVERY' => 'đang được giao cho người nhận',
                    'DELIVERED' => 'đã được giao hàng thành công',
                    'DELAYED' => 'đã bị trễ hẹn trong quá trình vận chuyển',
                    'EXCEPTION' => 'đã gặp vấn đề hoặc ngoại lệ trong quá trình vận chuyển',
                    'RETURNED' => 'đã được hoàn trả lại cho người gửi'
                ];

                $statusFlow = [
                    'ORDERPLACE' => ['ORDERPLACE'],
                    'PACKED' => ['ORDERPLACE', 'PACKED'],
                    'SHIPPED' => ['ORDERPLACE', 'PACKED', 'SHIPPED'],
                    'INTRANSIT' => ['ORDERPLACE', 'PACKED', 'SHIPPED', 'INTRANSIT'],
                    'OUTFORDELIVERY' => ['ORDERPLACE', 'PACKED', 'SHIPPED', 'INTRANSIT', 'OUTFORDELIVERY'],
                    'DELIVERED' => ['ORDERPLACE', 'PACKED', 'SHIPPED', 'INTRANSIT', 'OUTFORDELIVERY', 'DELIVERED'],
                    'DELAYED' => ['ORDERPLACE', 'PACKED', 'SHIPPED', 'INTRANSIT', 'OUTFORDELIVERY', 'DELAYED'],
                    'EXCEPTION' => ['ORDERPLACE', 'PACKED', 'SHIPPED', 'INTRANSIT', 'OUTFORDELIVERY', 'EXCEPTION'],
                    'RETURNED' => ['ORDERPLACE', 'PACKED', 'SHIPPED', 'INTRANSIT', 'OUTFORDELIVERY', 'RETURNED']
                ];

                if (isset($statuses[$order->shipment_status])) {
                    $statusHistory = $statusFlow[$order->shipment_status];
                    $statusDescriptions = array_map(function ($status, $index) use ($statuses) {
                        return [
                            'id' => $index + 1,
                            'status' => 'Đơn hàng ' . $statuses[$status]
                        ];
                    }, $statusHistory, array_keys($statusHistory));

                    return success('Tra cứu hoá đơn thành công', $statusDescriptions);
                } else {
                    return errors('Vận chuyển không hợp lệ');
                }
            } else {
                return errors('Không tìm thấy hoá đơn này');
            }
        } else {
            return errors('Vui lòng nhập mã hoá đơn');
        }
    }

    public function downloadOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            /**
             * @example 1 hoặc INV1
             */
            'code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return validationErrors($validator->errors());
        }
        if ($request->code != null) {
            $searchCode = preg_replace('/[^0-9]/', '', $request->code);
            $order = Order::where('id', $searchCode)
                ->where('user_id', auth()->guard('api')->user()->id)
                ->first();
            if ($order) {
                $font_family = "'Roboto','sans-serif'";
                $pdf = Pdf::loadView('order.downloadInvoice', [
                    'order' => $order,
                    'font_family' => $font_family,
                    'direction' => 'ltr',
                    'default_text_align' => 'left',
                    'reverse_text_align' => 'right'
                ]);

                // Trả về dữ liệu PDF dưới dạng phản hồi nhị phân
                return response($pdf->output(), 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="INV' . $order->id . '.pdf"');

            } else {
                return errors('Không tìm thấy hoá đơn này');
            }
        }
    }
}
