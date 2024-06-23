<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payments.index');
    }
    public function vnpayPayment(Request $request)
    {
        // Thông tin cấu hình từ VNPAY
        $vnp_Url = "https://sandbox.vnpayment.vn/tryitnow/Home/CreateOrder";
        // $vnp_Returnurl = route('payment.index'); // URL trả về sau khi thanh toán
        $vnp_TmnCode = "KSO680MD"; // Mã website tại VNPAY 
        $vnp_HashSecret = "FBQLBF6SCEOZ6CJUXZ3O70VW2JK956BH"; // Chuỗi bí mật
        
        // Các thông tin cần gửi tới VNPAY
        $vnp_TxnRef ="10"; // Mã đơn hàng duy nhất
        $vnp_OrderInfo = "Thanh toan hoa don";
        $vnp_Amount = "9999 * 100";
        $vnp_Locale = "VN";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $request->ip(); // Lấy địa chỉ IP của người dùng
        
        // Tạo mảng dữ liệu gửi đi
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
            // "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );
        
        // Sắp xếp mảng dữ liệu theo khóa từ điển
        ksort($inputData);
        
        // Tạo chuỗi dữ liệu để tính toán hash
        $query = "";
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($query == "") {
                $query .= urlencode($key) . '=' . urlencode($value);
            } else {
                $query .= '&' . urlencode($key) . '=' . urlencode($value);
            }
            $hashdata .= urlencode($key) . '=' . urlencode($value) . '&';
        }
        
        // Tạo mã hash bảo mật
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash('sha256', $vnp_HashSecret . $hashdata);
            $query .= '&vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
        }
        
        // Chuyển hướng tới VNPAY
        $redirectUrl = $vnp_Url . '?' . $query;
        return redirect()->to($redirectUrl);
    }
   

}
