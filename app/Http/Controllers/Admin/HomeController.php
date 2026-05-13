<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index() {
        return view('admin.home.index');
    }

    public function indexAjax() {
        $sumOrderSuccess = DB::table('orders')->where('status', '=', 'SUCCESS')->count();

        $total = 0;
        $listOrder = Order::with(['Products'])->where('status', '=', 'SUCCESS')->get();
        foreach ($listOrder as $order){
            $total += $order->total();
        }

        $totalUser = User::all()->count();
        $totalProductActive = Product::where('status', '=', 1)->count();
        $totalCategory = Category::all()->count();

        // 1. Thống kê doanh thu/đơn hàng theo tháng (giữ nguyên logic cũ)
        $listOrderOfMonthData = [];
        $listOrderMoneyOfMonthData = [];
        $listOrderOfMonth = Order::with(['Products'])
            ->whereYear('created_at', '=', Carbon::now()->year)
            ->get();

        foreach ($listOrderOfMonth as $item) {
            $month = Carbon::parse($item->created_at)->format('m');
            if (empty($listOrderOfMonthData[$month])) {
                $listOrderOfMonthData[$month] = 1;
            } else {
                $listOrderOfMonthData[$month]++;
            }

            if ($item->status == 'SUCCESS') {
                if (empty($listOrderMoneyOfMonthData[$month])) {
                    $listOrderMoneyOfMonthData[$month] = $item->total();
                } else {
                    $listOrderMoneyOfMonthData[$month] += $item->total();
                }
            }
        }

        // 2. Thống kê doanh thu theo ngày (7 ngày gần nhất)
        $dailyRevenueData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dailyRevenueData[$date] = 0;
        }

        foreach ($listOrder as $order) {
            $date = Carbon::parse($order->created_at)->format('Y-m-d');
            if (isset($dailyRevenueData[$date])) {
                $dailyRevenueData[$date] += $order->total();
            }
        }

        // 3. Sản phẩm bán chạy nhất (Top 5)
        $topSellingProducts = DB::table('order_products')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->where('orders.status', 'SUCCESS')
            ->select('products.name', 'products.image', DB::raw('SUM(order_products.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // 4. Khách hàng mua nhiều nhất (Top 5)
        $topCustomers = [];
        $customerRevenue = [];
        foreach ($listOrder as $order) {
            if ($order->user_id) {
                if (!isset($customerRevenue[$order->user_id])) {
                    $customerRevenue[$order->user_id] = [
                        'name' => $order->name ?? ($order->User->name ?? 'Guest'),
                        'email' => $order->email ?? ($order->User->email ?? ''),
                        'total_spent' => 0,
                        'order_count' => 0
                    ];
                }
                $customerRevenue[$order->user_id]['total_spent'] += $order->total();
                $customerRevenue[$order->user_id]['order_count']++;
            }
        }
        
        uasort($customerRevenue, function($a, $b) {
            return $b['total_spent'] <=> $a['total_spent'];
        });
        $topCustomers = array_slice($customerRevenue, 0, 5, true);

        return view('admin.home._ajax', compact(
            'sumOrderSuccess', 'total', 'totalUser', 'totalProductActive', 'totalCategory', 
            'listOrderOfMonthData', 'listOrderMoneyOfMonthData', 'dailyRevenueData',
            'topSellingProducts', 'topCustomers'
        ));
    }
}