<?php

namespace App\Http\Controllers\Api\Client\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
/**
 * @tags Clients
 */
class ClientAccountController extends Controller
{
    /**
     * Lấy thông tin người dùng
     */
    public function index()
    {
        $user = Auth::guard('api')->user();
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'balance' => $user->balance,
            'is_banned' => $user->is_banned,
        ];
        $response = [
            'success' => true,
            'message' => 'Lấy thông tin thành công',
            'data' => $data,
            'extra' => [
                'authToken' => request()->bearerToken(),
                'tokenType' => 'Bearer',
                'role' => auth()->guard('api')->user()->role,
            ],
        ];
        return response()->json($response);
    }

    /**
     * Thống kê hoá đơn
     */
    public function statistical()
    {
        $userId = auth()->guard('api')->user()->id;
        $baseQuery = function () use ($userId) {
            return Order::with(['orderItems'])->where('user_id', $userId);
        };
        $countOrderPaid = ($baseQuery())->where('order_status', 2)->count();
        $countOrderPending = ($baseQuery())->where('order_status', 1)->count();
        $data = [
            'countOrderPaid' => $countOrderPaid,
            'countOrderPending' => $countOrderPending,
        ];
        $response = [
            'success' => true,
            'message' => 'Lấy thông tin thống kê thành công',
            'data' => $data,
            'extra' => [
                'authToken' => request()->bearerToken(),
                'tokenType' => 'Bearer',
                'role' => auth()->guard('api')->user()->role,
            ],
        ];
        return response()->json($response);
    }

    /**
     * Thống kê đơn hàng đã mua
     */
    public function invoice()
    {
        $userId = auth()->guard('api')->user()->id;
        $orders = Order::with(['orderItems'])->where('user_id', $userId)->get();
        $orders->each(function ($order) {
            $order->order_status = $order->orderStatusDescription;
            $order->payment_method =  $order->orderPaymentMethodDescription;
            $order->created_at = \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i:s');
            $order->order_date = \Carbon\Carbon::parse($order->order_date)->format('d-m-Y H:i:s');
            // $order->orderItems->each(function ($item) {
            //     $item->name = $item->product->name ?? NULL;
            //     $item->slug = $item->product->slug ?? NULL;
            // });
            // $order->orderItems->each(function ($item) {
            //     $item->product->brand_name = $item->product->brand->name ?? NULL;
            //     $item->product->size_name = $item->product->size->name ?? NULL;
            //     $item->product->color_name = $item->product->color->name ?? NULL;
            // });
        });
        $data = [
            'order' => $orders,
        ];
        $response = [
            'success' => true,
            'message' => 'Lấy đơn hàng đã mua thành công',
            'data' => $data,
            'extra' => [
                'authToken' => request()->bearerToken(),
                'tokenType' => 'Bearer',
                'role' => auth()->guard('api')->user()->role,
            ],
        ];
        return response()->json($response);
    }
    /**
     * Đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            /**
             * @example thanhson
             */
            'password' => 'required|string|confirmed|min:5',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::where('id', auth()->guard('api')->user()->id)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            if (Hash::check($request->input('password'), $user->password)) {
                return $this->createUserApiToken($user, trans('change pass'));
            }
        }
    }
    protected function createUserApiToken($user, $deviceName = null, $message = null): \Illuminate\Http\JsonResponse
    {
        $user->tokens()->delete();
        $deviceName = $deviceName ?? $user->email;
        $token = $user->createToken($deviceName);

        $data = [
            'success' => true,
            'message' => $message,
            'result'  => new UserResource($user),
            'extra'   => [
                'authToken' => $token->plainTextToken,
                'tokenType' => 'Bearer',
            ],
        ];

        return response()->json($data);
    }
}
