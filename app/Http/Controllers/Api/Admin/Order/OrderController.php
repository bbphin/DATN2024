<?php

namespace App\Http\Controllers\Api\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'product')->get();
        return OrderResource::collection($orders);
    }

    public function store(OrderRequest $request)
    {
        $validated = $request->validated();
        $order = Order::create($validated);

        return new OrderResource($order);
    }

    public function show($id)
    {
        $order = Order::with('user', 'product')->findOrFail($id);
        return new OrderResource($order);
    }

    public function update(OrderRequest $request, $id)
    {
        $validated = $request->validated();
        $order = Order::findOrFail($id);
        $order->update($validated);

        return new OrderResource($order);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(null, 204);
    }
}
