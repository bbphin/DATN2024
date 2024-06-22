<?php

namespace App\Http\Controllers\Api\Client\Subscriber;

use App\Http\Requests\Subscriber\AddSubscriberFormRequest;
use App\Mail\SubscriberMail;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

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
        try {
            $data = $request->validated();
            $subscriber = new Subscriber;
            $subscriber->fill($data);
            $subscriber->save();
            Mail::to($subscriber->email)->send(new SubscriberMail());
            $response = [
                'data' => $data,
                'extra' => [
                    'authToken' => request()->bearerToken(),
                    'tokenType' => 'Bearer',
                    'role' => auth()->guard('api')->user()->role,
                ],
            ];
            return success('Đăng ký nhận thông báo thành công',$response);
        } catch (\Exception $e) {
            return errors($e->getMessage());
        }
    }
}
