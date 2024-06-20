<?php

namespace App\Http\Controllers\Api\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * @tags Admin\Home
 */
class DashboardController extends Controller
{
    /**
     * Thống kê
     */
    public function index()
    {
        $countUser = User::count();
        $countProduct = Product::count();
        $countBlog = Blog::count();
        $countOrder = Order::count();
        $countOrderPaid = Order::where('order_status', 2)->count();
        $topProductView = Product::where('is_published', 1)
            ->orderBy('view', 'desc')
            ->limit(5)
            ->get();
        $topProductRating = Product::with('review')
            ->select('products.*')
            ->addSelect([
                'total_stars' => Review::selectRaw('SUM(rating)')
                    ->whereColumn('reviews.product_id', 'products.id')
            ])
            ->addSelect([
                'reviews_count' => Review::selectRaw('COUNT(*)')
                    ->whereColumn('reviews.product_id', 'products.id')
            ])
            ->orderByRaw('total_stars / reviews_count DESC')
            ->limit(5)
            ->get();
        $topProductBuy = OrderDetail::select('product_id', DB::raw('COUNT(*) as total'))
            ->whereHas('order', function ($query) {
                $query->where('order_status', 2);
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        $data = [
            'countUser' => $countUser,
            'countProduct' => $countProduct,
            'countBlog' => $countBlog,
            'countOrder' => $countOrder,
            'countOrderPaid' => $countOrderPaid,
            'productView' => $topProductView,
            'topProductRating' => $topProductRating,
            'topProductBuy' => $topProductBuy,
        ];
        $response = [
            'success' => true,
            'message' => 'Thống kê thành công',
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
     * Thống kê doanh thu
     */
    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            /**
             * @example this_month
             *
             * loại thời gian (all, this_week, this_month, this_year). Default this_month. Nếu null thì mới dùng lọc theo ngày bắt đầu, kết thúc
             */
            'period' => 'nullable|string',
            /**
             * @example 2024-05-01
             *
             * Lọc theo ngày bắt đầu (2024-05-01) có thể có hoặc không
             */
            'start_date' => 'nullable|sometimes|date_format:Y-m-d',
            /**
             * @example 2024-06-01
             *
             * Lọc theo ngày bắt đầu (2024-06-01) có thể có hoặc không
             */
            'end_date' => 'nullable|sometimes|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $period = $request->period;
        if ($period) {
            switch ($period) {
                case 'this_week':
                    $startDate = Carbon::now()->startOfWeek()->toDateString();
                    $endDate = Carbon::now()->endOfWeek()->toDateString();
                    break;
                case 'this_month':
                    $startDate = Carbon::now()->startOfMonth()->toDateString();
                    $endDate = Carbon::now()->endOfMonth()->toDateString();
                    break;
                case 'this_year':
                    $startDate = Carbon::now()->startOfYear()->toDateString();
                    $endDate = Carbon::now()->endOfYear()->toDateString();
                    break;
                case 'all':
                    $startDate = null;
                    $endDate = null;
                    break;
                default:
                    $startDate = Carbon::now()->startOfMonth()->toDateString();
                    $endDate = Carbon::now()->endOfMonth()->toDateString();
                    break;
            }
        }

        $ordersQuery = Order::select('orders.id', 'orders.created_at', DB::raw('SUM(order_details.total_price) as total_revenue'))
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.order_status', 2)
            ->groupBy('orders.id', 'orders.created_at');
        if ($startDate && $endDate) {
            $ordersQuery->whereDate('orders.created_at', '>=', $startDate)
                ->whereDate('orders.created_at', '<=', $endDate);
        }
        $orders = $ordersQuery->get();
        $data = $orders->map(function ($order) {
            return [
                'date' => Carbon::parse($order->created_at)->format('Y-m-d'),
                'total_revenue' => (float) $order->total_revenue
            ];
        });
        $data = $data->toArray();
        $response = [
            'success' => true,
            'message' => 'Lọc thành công',
            'data' => $data,
            'extra' => [
                'authToken' => request()->bearerToken(),
                'tokenType' => 'Bearer',
                'role' => auth()->guard('api')->user()->role,
            ],
        ];
        return response()->json($response,200);
    }
}
