<?php

namespace App\Http\Controllers\Api\Admin\Ordermanagement;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrdermanagementResource;

class OrdermanagementController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return response()->json(OrdermanagementResource::collection($orders), 200);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json(new OrdermanagementResource($order), 200);
    }

    public function cancel($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['order_status' => 'cancelled']);
        return response()->json(new OrdermanagementResource($order), 200);
    }

    public function Success($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['order_status' => 'success']);
        return response()->json(new OrdermanagementResource($order), 200);
    }
}
