<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DataCollectorService
{
    public function collectForAI()
    {
        return [
            'overall_stats' => $this->getOverallStats(),
            'products' => $this->getProductsWithMetrics(),
            'recent_trends' => $this->getRecentTrends(),
            'customer_behavior' => $this->getCustomerBehavior(),
            'time_patterns' => $this->getTimePatterns(),
        ];
    }

    protected function getOverallStats()
    {
        $last7Days = Carbon::now()->subDays(7);

        return [
            'total_products' => Product::where('status', 1)->count(),
            'total_views_7d' => ActivityLog::where('activity_type', 'view')
                ->where('created_at', '>=', $last7Days)
                ->count(),
            'total_cart_adds_7d' => ActivityLog::where('activity_type', 'add_to_cart')
                ->where('created_at', '>=', $last7Days)
                ->count(),
            'total_purchases_7d' => ActivityLog::where('activity_type', 'purchase')
                ->where('created_at', '>=', $last7Days)
                ->count(),
            'total_orders' => DB::table('order_products')->distinct('order_id')->count('order_id'),
        ];
    }

    protected function getProductsWithMetrics($limit = 20)
    {
        $products = Product::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        $result = [];
        $last7Days = Carbon::now()->subDays(7);

        foreach ($products as $product) {
            $views = ActivityLog::where('activity_type', 'view')
                ->where('product_id', $product->id)
                ->where('created_at', '>=', $last7Days)
                ->count();

            $addToCart = ActivityLog::where('activity_type', 'add_to_cart')
                ->where('product_id', $product->id)
                ->where('created_at', '>=', $last7Days)
                ->count();

            $purchases = ActivityLog::where('activity_type', 'purchase')
                ->where('product_id', $product->id)
                ->where('created_at', '>=', $last7Days)
                ->count();

            $conversionRate = $views > 0 ? round(($purchases / $views) * 100, 2) : 0;
            $abandonmentRate = $addToCart > 0 ? round((($addToCart - $purchases) / $addToCart) * 100, 2) : 0;

            $last3Days = Carbon::now()->subDays(3);
            $previous3Days = Carbon::now()->subDays(6);

            $recentViews = ActivityLog::where('activity_type', 'view')
                ->where('product_id', $product->id)
                ->whereBetween('created_at', [$last3Days, Carbon::now()])
                ->count();

            $previousViews = ActivityLog::where('activity_type', 'view')
                ->where('product_id', $product->id)
                ->whereBetween('created_at', [$previous3Days, $last3Days])
                ->count();

            $growthRate = $previousViews > 0 ? round((($recentViews - $previousViews) / $previousViews) * 100, 0) : 0;

            $result[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->quantity ?? 0,
                'category' => $product->category->name ?? 'N/A',
                'metrics' => [
                    'views_7d' => $views,
                    'add_to_cart_7d' => $addToCart,
                    'purchases_7d' => $purchases,
                    'conversion_rate' => $conversionRate,
                    'abandonment_rate' => $abandonmentRate,
                    'growth_rate_3d' => $growthRate,
                    'recent_views_3d' => $recentViews,
                    'previous_views_3d' => $previousViews,
                ],
            ];
        }

        usort($result, function ($a, $b) {
            $activityA = $a['metrics']['views_7d'] + $a['metrics']['add_to_cart_7d'] + $a['metrics']['purchases_7d'];
            $activityB = $b['metrics']['views_7d'] + $b['metrics']['add_to_cart_7d'] + $b['metrics']['purchases_7d'];
            return $activityB - $activityA;
        });

        return array_slice($result, 0, 10);
    }

    protected function getRecentTrends()
    {
        $last7Days = Carbon::now()->subDays(7);

        return [
            'top_searched_terms' => ActivityLog::where('activity_type', 'search')
                ->where('created_at', '>=', $last7Days)
                ->select('search_query', DB::raw('count(*) as count'))
                ->groupBy('search_query')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->pluck('count', 'search_query')
                ->toArray(),

            'most_viewed_products' => ActivityLog::where('activity_type', 'view')
                ->where('created_at', '>=', $last7Days)
                ->select('product_id', DB::raw('count(*) as count'))
                ->groupBy('product_id')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    $product = Product::find($item->product_id);
                    return [
                        'product_name' => $product->name ?? 'Unknown',
                        'views' => $item->count
                    ];
                })
                ->toArray(),
        ];
    }

    protected function getCustomerBehavior()
    {
        $associations = DB::table('order_products as op1')
            ->join('order_products as op2', 'op1.order_id', '=', 'op2.order_id')
            ->where('op1.product_id', '<', DB::raw('op2.product_id'))
            ->select('op1.product_id as product_a', 'op2.product_id as product_b', DB::raw('count(*) as frequency'))
            ->groupBy('op1.product_id', 'op2.product_id')
            ->having('frequency', '>=', 2)
            ->orderBy('frequency', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $productA = Product::find($item->product_a);
                $productB = Product::find($item->product_b);
                return [
                    'product_a' => $productA->name ?? 'Unknown',
                    'product_b' => $productB->name ?? 'Unknown',
                    'frequency' => $item->frequency
                ];
            })
            ->toArray();

        return [
            'frequently_bought_together' => $associations,
        ];
    }

    public function getTimePatterns()
    {
        $last30Days = Carbon::now()->subDays(30);

        $dayOfWeekData = ActivityLog::where('created_at', '>=', $last30Days)
            ->selectRaw('DAYNAME(created_at) as day_name, activity_type, COUNT(*) as count')
            ->groupBy('day_name', 'activity_type')
            ->get();

        $hourlyData = ActivityLog::where('created_at', '>=', $last30Days)
            ->selectRaw('HOUR(created_at) as hour, activity_type, COUNT(*) as count')
            ->groupBy('hour', 'activity_type')
            ->get();

        return [
            'day_of_week' => $this->processDayOfWeek($dayOfWeekData),
            'hourly' => $this->processHourly($hourlyData),
        ];
    }

    protected function processDayOfWeek($data)
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $result = [];
        $peakDay = '';
        $maxPurchases = 0;

        foreach ($days as $day) {
            $result[$day] = [
                'views' => 0,
                'purchases' => 0,
                'add_to_cart' => 0,
            ];
        }

        foreach ($data as $item) {
            if (isset($result[$item->day_name])) {
                $result[$item->day_name][$item->activity_type] = $item->count;

                if ($item->activity_type === 'purchase' && $item->count > $maxPurchases) {
                    $maxPurchases = $item->count;
                    $peakDay = $item->day_name;
                }
            }
        }

        $result['peak_day'] = $peakDay ?: 'Saturday';
        $result['peak_day_purchases'] = $maxPurchases;

        return $result;
    }

    protected function processHourly($data)
    {
        $result = [];
        $peakHours = [];
        $maxPurchases = 0;
        $timeSlots = [
            'morning' => 0,    // 6-12
            'afternoon' => 0,  // 12-18
            'evening' => 0,    // 18-24
            'night' => 0       // 0-6
        ];

        for ($h = 0; $h < 24; $h++) {
            $result[$h] = [
                'views' => 0,
                'purchases' => 0,
                'add_to_cart' => 0,
            ];
        }

        foreach ($data as $item) {
            $hour = (int) $item->hour;
            $result[$hour][$item->activity_type] = $item->count;

            if ($item->activity_type === 'purchase') {
                if ($hour >= 6 && $hour < 12)
                    $timeSlots['morning'] += $item->count;
                elseif ($hour >= 12 && $hour < 18)
                    $timeSlots['afternoon'] += $item->count;
                elseif ($hour >= 18 && $hour < 24)
                    $timeSlots['evening'] += $item->count;
                else
                    $timeSlots['night'] += $item->count;

                if ($item->count > $maxPurchases) {
                    $maxPurchases = $item->count;
                }
            }
        }

        foreach ($result as $hour => $stats) {
            if ($stats['purchases'] >= $maxPurchases * 0.7 && $stats['purchases'] > 0) {
                $peakHours[] = sprintf('%02d:00', $hour);
            }
        }

        $result['peak_hours'] = !empty($peakHours) ? $peakHours : ['20:00', '21:00'];
        $result['time_slots'] = $timeSlots;

        return $result;
    }

    public function formatForPrompt()
    {
        $data = $this->collectForAI();

        $prompt = "=== TỔNG QUAN HỆ THỐNG ===\n";
        $prompt .= "Tổng sản phẩm: {$data['overall_stats']['total_products']}\n";
        $prompt .= "Lượt xem (7 ngày): {$data['overall_stats']['total_views_7d']}\n";
        $prompt .= "Thêm giỏ hàng (7 ngày): {$data['overall_stats']['total_cart_adds_7d']}\n";
        $prompt .= "Mua hàng (7 ngày): {$data['overall_stats']['total_purchases_7d']}\n";
        $prompt .= "Tổng đơn hàng: {$data['overall_stats']['total_orders']}\n\n";

        $prompt .= "=== TOP 10 SẢN PHẨM THEO HOẠT ĐỘNG ===\n";
        foreach ($data['products'] as $index => $product) {
            $prompt .= ($index + 1) . ". {$product['name']} (ID: {$product['id']})\n";
            $prompt .= "   - Giá: " . number_format($product['price']) . " VNĐ\n";
            $prompt .= "   - Tồn kho: {$product['quantity']}\n";
            $prompt .= "   - Danh mục: {$product['category']}\n";
            $prompt .= "   - Lượt xem (7d): {$product['metrics']['views_7d']}\n";
            $prompt .= "   - Thêm giỏ (7d): {$product['metrics']['add_to_cart_7d']}\n";
            $prompt .= "   - Mua hàng (7d): {$product['metrics']['purchases_7d']}\n";
            $prompt .= "   - Tỷ lệ chuyển đổi: {$product['metrics']['conversion_rate']}%\n";
            $prompt .= "   - Tỷ lệ bỏ giỏ: {$product['metrics']['abandonment_rate']}%\n";
            $prompt .= "   - Tăng trưởng lượt xem (3d): {$product['metrics']['growth_rate_3d']}%\n\n";
        }

        if (!empty($data['recent_trends']['top_searched_terms'])) {
            $prompt .= "=== TỪ KHÓA TÌM KIẾM PHỔ BIẾN ===\n";
            foreach ($data['recent_trends']['top_searched_terms'] as $term => $count) {
                $prompt .= "- \"{$term}\": {$count} lần\n";
            }
            $prompt .= "\n";
        }

        if (!empty($data['customer_behavior']['frequently_bought_together'])) {
            $prompt .= "=== SẢN PHẨM HAY MUA CÙNG NHAU ===\n";
            foreach ($data['customer_behavior']['frequently_bought_together'] as $combo) {
                $prompt .= "- {$combo['product_a']} + {$combo['product_b']}: {$combo['frequency']} lần\n";
            }
            $prompt .= "\n";
        }

        if (!empty($data['time_patterns'])) {
            $prompt .= "=== PHÂN TÍCH THỜI GIAN (30 NGÀY GẦN NHẤT) ===\n";

            $peakDay = $data['time_patterns']['day_of_week']['peak_day'] ?? 'N/A';
            $peakDayPurchases = $data['time_patterns']['day_of_week']['peak_day_purchases'] ?? 0;
            $prompt .= "Ngày bán chạy nhất: {$peakDay} ({$peakDayPurchases} đơn hàng)\n";

            $peakHours = $data['time_patterns']['hourly']['peak_hours'] ?? [];
            if (!empty($peakHours)) {
                $prompt .= "Giờ cao điểm: " . implode(', ', $peakHours) . "\n";
            }

            $timeSlots = $data['time_patterns']['hourly']['time_slots'] ?? [];
            if (!empty($timeSlots)) {
                $bestSlot = array_keys($timeSlots, max($timeSlots))[0] ?? 'evening';
                $prompt .= "Khung giờ bán chạy: " . ucfirst($bestSlot) . " ({$timeSlots[$bestSlot]} đơn)\n";
            }

            $prompt .= "\nPattern theo ngày:\n";
            $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            $weekend = ['Saturday', 'Sunday'];

            $weekdayPurchases = 0;
            $weekendPurchases = 0;

            foreach ($weekdays as $day) {
                if (isset($data['time_patterns']['day_of_week'][$day])) {
                    $weekdayPurchases += $data['time_patterns']['day_of_week'][$day]['purchases'];
                }
            }

            foreach ($weekend as $day) {
                if (isset($data['time_patterns']['day_of_week'][$day])) {
                    $weekendPurchases += $data['time_patterns']['day_of_week'][$day]['purchases'];
                }
            }

            $prompt .= "- Thứ 2-6: {$weekdayPurchases} đơn (trung bình " . round($weekdayPurchases / 5, 1) . " đơn/ngày)\n";
            $prompt .= "- Thứ 7-CN: {$weekendPurchases} đơn (trung bình " . round($weekendPurchases / 2, 1) . " đơn/ngày)\n";
        }

        return $prompt;
    }
}
