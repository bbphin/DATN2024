<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subscriber\AddSubscriberFormRequest;
use App\Mail\SubscriberMail;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * @tags Clients
 */
class ClientSubscriberController extends Controller
{
    /**
     * Đăng ký nhận thông báo
     */
    public function add(AddSubscriberFormRequest $request)
    {
        $data = $request->validated();
        $subscriber = new Subscriber;
        $subscriber->fill($data);
        $subscriber->save();
        Mail::to($subscriber->email)->send(new SubscriberMail());
        $response = [
            'success' => true,
            'message' => 'Đăng ký thành công',
            'data' => $data,
            'extra' => [
                'authToken' => request()->bearerToken(),
                'tokenType' => 'Bearer',
                'role' => auth()->guard('api')->user()->role,
            ],
        ];
        return response()->json($response, 200);
    }
}
