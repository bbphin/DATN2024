<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use ZaloPay\ZaloPayClient;

// class ZaloPayController extends Controller
// {
//     public function checkout(Request $request)
//     {
//         // Thực hiện tạo yêu cầu thanh toán tới ZaloPay
//         $orderInfo = [
//             'app_id' => 'your_app_id', // Thay thế bằng app_id của bạn
//             'app_trans_id' => uniqid(), // Mã giao dịch duy nhất
//             'app_user' => 'user_id', // ID người dùng trong hệ thống của bạn
//             'app_time' => time(),
//             'amount' => 10000, // Số tiền thanh toán
//             'description' => 'Thanh toan qua ZaloPay',
//             'bank_code' => 'zalopayapp', // Mã ngân hàng, để trống hoặc là 'zalopayapp'
//             'callback_url' => route('zalopay.callback'), // URL nhận callback từ ZaloPay
//         ];

//         $zaloPay = new ZaloPayClient('your_app_id', 'your_key1', 'your_key2', 'path_to_your_public_key.pem');
//         $response = $zaloPay->createOrder($orderInfo);

//         // Redirect người dùng đến URL thanh toán của ZaloPay
//         if ($response['return_code'] == 1) {
//             return redirect()->to($response['order_url']);
//         } else {
//             // Xử lý lỗi khi tạo đơn hàng
//             return back()->withErrors(['message' => $response['return_message']]);
//         }
//     }

//     public function callback(Request $request)
//     {
//         // Xử lý callback từ ZaloPay
//         $data = $request->all();
//         $zaloPay = new ZaloPayClient('your_app_id', 'your_key1', 'your_key2', 'path_to_your_public_key.pem');
//         $result = $zaloPay->verifyCallback($data);

//         // Xử lý kết quả callback ở đây
//         if ($result) {
//             // Xác thực thành công, xử lý dữ liệu đơn hàng
//             // Lưu vào cơ sở dữ liệu, cập nhật trạng thái đơn hàng,...
//             // Ví dụ:
//             $orderId = $data['app_trans_id'];
//             $amount = $data['amount'];
//             $message = 'Giao dịch thành công. Mã đơn hàng: ' . $orderId . ', Số tiền: ' . $amount;
//             return response()->json(['code' => '00', 'message' => $message]);
//         } else {
//             // Xử lý khi xác thực không thành công
//             return response()->json(['code' => '-1', 'message' => 'Xác thực không thành công']);
//         }
//     }
// } -->
