<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscriber\SendSubscriberFormRequest;
use App\Jobs\SendSubscriberMailJob;
use App\Models\EmailContent;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @tags Admin\Subscriber
 */
class SubscriberController extends Controller
{
    /**
     * Danh sách người đăng ký thông báo
     */
    public function index()
    {
        $data = Subscriber::all();
        $response = [
            'success' => true,
            'message' => 'In danh sách người đăng ký thông báo thành công',
            'data' => $data,
            'extra' => [
                'authToken' => request()->bearerToken(),
                'tokenType' => 'Bearer',
                'role' => auth()->guard('api')->user()->role,
            ],
        ];
        return response()->json($response, 200);
    }
    /**
     * Lưu giao diện mail - subcriber
     */
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            /**
             * @example <h1>Hello cậu bé</h1>
             */
            'content' => 'required|string',
        ]);

        // Kiểm tra xem xác thực có thất bại không
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Xác thực không thành công',
                'errors' => $validator->errors(),
            ], 422);
        }
        $content = $request->input('content');
        EmailContent::where('email_type', 'subscriber_mail')->update([
            'content' => $content,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật nội dung email thành công',
        ], 200);
    }
    /**
     * Gửi thông báo tất cả subscriber
     *
     * Sử dụng thư viện select2
     */
    public function send(SendSubscriberFormRequest $request)
    {
        $tor = $request->to;
        $to = array_filter($tor, function ($value) {
            return $value !== 'select-all';
        });
        $to = json_encode(array_values($to));
        $subject = $request->subject;
        $content = $request->content;
        $to = json_decode($to);
        $subscribers = Subscriber::whereIn('email', $to)->where('status', 1)->get(['email', 'fullname']);
        if ($subscribers->count() > 0) {
            foreach ($subscribers as $subscriber) {
                $fullname = $subscriber->fullname;
                $email = $subscriber->email;
                $phone = $subscriber->phone;
                // Chuyển thông tin sang view của email template
                $mailContent = compact('content', 'subject', 'fullname', 'email');
                // Thay thế biến {{fullname}}, {{email}}, {{phone}} trong nội dung bằng giá trị thực tế
                $mailContent['content'] = str_replace('{{fullname}}', $fullname, $mailContent['content']);
                $mailContent['content'] = str_replace('{{email}}', $email, $mailContent['content']);
                // Gửi email
                SendSubscriberMailJob::dispatch($subject, $mailContent['content'], $email);
            }
        }
        $response = [
            'success' => true,
            'message' => 'Gửi thành công',
            'extra' => [
                'authToken' => request()->bearerToken(),
                'tokenType' => 'Bearer',
                'role' => auth()->guard('api')->user()->role,
            ],
        ];
        return response()->json($response, 200);
    }
}
