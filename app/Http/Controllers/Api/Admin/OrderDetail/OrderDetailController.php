<?php

namespace App\Http\Controllers\Api\Admin\OrderDetail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;

class OrderDetailController extends Controller
{
    public function index()
    {
        $orderDetails = OrderDetail::all();
        return response()->json($orderDetails);
    }

    public function store(Request $request)
    {
        $orderDetail = OrderDetail::create($request->all());
        return response()->json($orderDetail, 201);
    }

    public function show($id)
    {
        $orderDetail = OrderDetail::findOrFail($id);
        return response()->json($orderDetail);
    }

    public function update(Request $request, $id)
    {
        $orderDetail = OrderDetail::findOrFail($id);
        $orderDetail->update($request->all());
        return response()->json($orderDetail, 200);
    }

    public function destroy($id)
    {
        OrderDetail::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
