<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Calculate conversion rate for a product
     * CR = (Purchases / Views) * 100
     */
    public function getConversionRate($productId, $days = 30)
    {
        $startDate = Carbon::now()->subDays($days);

        $views = ActivityLog::where('product_id', $productId)
            ->where('activity_type', 'view')
            ->where('created_at', '>=', $startDate)
            ->count();

        $purchases = ActivityLog::where('product_id', $productId)
            ->where('activity_type', 'purchase')
            ->where('created_at', '>=', $startDate)
            ->count();

        if ($views == 0)
            return 0;

        return round(($purchases / $views) * 100, 2);
    }

    /**
     * Calculate cart abandonment rate
     * CAR = (Add to Cart - Purchases) / Add to Cart * 100
     */
    public function getCartAbandonmentRate($productId, $days = 30)
    {
        $startDate = Carbon::now()->subDays($days);

        $addedToCart = ActivityLog::where('product_id', $productId)
            ->where('activity_type', 'add_to_cart')
            ->where('created_at', '>=', $startDate)
            ->count();

        $purchases = ActivityLog::where('product_id', $productId)
            ->where('activity_type', 'purchase')
            ->where('created_at', '>=', $startDate)
            ->count();

        if ($addedToCart == 0)
            return 0;

        return round((($addedToCart - $purchases) / $addedToCart) * 100, 2);
    }

    /**
     * Detect trending products (growth in views)
     */
    public function getTrendingProducts($threshold = 50)
    {
        $now = Carbon::now();
        $last3Days = $now->copy()->subDays(3);
        $previous3Days = $now->copy()->subDays(6);

        // Get views for last 3 days
        $recentViews = ActivityLog::select('product_id', DB::raw('COUNT(*) as view_count'))
            ->where('activity_type', 'view')
            ->whereBetween('created_at', [$last3Days, $now])
            ->groupBy('product_id')
            ->pluck('view_count', 'product_id');

        // Get views for previous 3 days
        $previousViews = ActivityLog::select('product_id', DB::raw('COUNT(*) as view_count'))
            ->where('activity_type', 'view')
            ->whereBetween('created_at', [$previous3Days, $last3Days])
            ->groupBy('product_id')
            ->pluck('view_count', 'product_id');

        $trending = [];

        foreach ($recentViews as $productId => $recentCount) {
            $previousCount = $previousViews[$productId] ?? 0;

            if ($previousCount > 0) {
                $growthRate = (($recentCount - $previousCount) / $previousCount) * 100;

                if ($growthRate >= $threshold) {
                    $trending[] = [
                        'product_id' => $productId,
                        'growth_rate' => round($growthRate, 2),
                        'recent_views' => $recentCount,
                        'previous_views' => $previousCount,
                    ];
                }
            }
        }

        // Sort by growth rate descending
        usort($trending, function ($a, $b) {
            return $b['growth_rate'] <=> $a['growth_rate'];
        });

        return $trending;
    }

    /**
     * Find products frequently bought together (Cross-sell analysis)
     */
    public function getFrequentlyBoughtTogether($minSupport = 2)
    {
        // Get orders with multiple products
        $orderProducts = DB::table('order_products')
            ->select('order_id', 'product_id')
            ->get()
            ->groupBy('order_id');

        $associations = [];

        foreach ($orderProducts as $orderId => $products) {
            if ($products->count() < 2)
                continue;

            $productIds = $products->pluck('product_id')->toArray();

            // Create pairs
            for ($i = 0; $i < count($productIds); $i++) {
                for ($j = $i + 1; $j < count($productIds); $j++) {
                    $pair = [$productIds[$i], $productIds[$j]];
                    sort($pair);
                    $key = implode('-', $pair);

                    if (!isset($associations[$key])) {
                        $associations[$key] = [
                            'product_a' => $pair[0],
                            'product_b' => $pair[1],
                            'count' => 0,
                        ];
                    }

                    $associations[$key]['count']++;
                }
            }
        }

        // Filter by minimum support
        $associations = array_filter($associations, function ($item) use ($minSupport) {
            return $item['count'] >= $minSupport;
        });

        // Sort by count descending
        usort($associations, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        return $associations;
    }

    /**
     * Get slow-moving inventory (products with low sales)
     */
    public function getSlowMovingProducts($days = 60)
    {
        $startDate = Carbon::now()->subDays($days);

        $productSales = ActivityLog::select('product_id', DB::raw('COUNT(*) as purchase_count'))
            ->where('activity_type', 'purchase')
            ->where('created_at', '>=', $startDate)
            ->groupBy('product_id')
            ->pluck('purchase_count', 'product_id');

        // Get all products with inventory
        $products = Product::where('quantity', '>', 0)
            ->get();

        $slowMoving = [];

        foreach ($products as $product) {
            $sales = $productSales[$product->id] ?? 0;

            // If no sales or very low sales
            if ($sales < 3) {
                $slowMoving[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $product->quantity,
                    'sales_count' => $sales,
                    'days_in_stock' => $days,
                ];
            }
        }

        return $slowMoving;
    }

    /**
     * Analyze pricing effectiveness
     */
    public function analyzePricing($productId, $days = 30)
    {
        $product = Product::find($productId);
        if (!$product)
            return null;

        $conversionRate = $this->getConversionRate($productId, $days);
        $abandonmentRate = $this->getCartAbandonmentRate($productId, $days);

        // Get average price in category
        $avgCategoryPrice = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $productId)
            ->avg('price');

        $priceDifference = $avgCategoryPrice > 0
            ? (($product->price - $avgCategoryPrice) / $avgCategoryPrice) * 100
            : 0;

        return [
            'product_id' => $productId,
            'current_price' => $product->price,
            'category_avg_price' => round($avgCategoryPrice, 2),
            'price_difference_percent' => round($priceDifference, 2),
            'conversion_rate' => $conversionRate,
            'abandonment_rate' => $abandonmentRate,
            'is_overpriced' => $priceDifference > 15 && $conversionRate < 5,
        ];
    }
    /**
     * Get revenue for the last 7 days vs previous 7 days
     */
    public function getWeeklyRevenueTrend()
    {
        $now = Carbon::now();
        $thisWeekStart = $now->copy()->subDays(7);
        $lastWeekStart = $now->copy()->subDays(14);

        $thisWeekRevenue = DB::table('orders')
            ->where('status', 'SUCCESS')
            ->where('created_at', '>=', $thisWeekStart)
            ->sum(DB::raw('total_money'));

        $lastWeekRevenue = DB::table('orders')
            ->where('status', 'SUCCESS')
            ->whereBetween('created_at', [$lastWeekStart, $thisWeekStart])
            ->sum(DB::raw('total_money'));

        $growth = $lastWeekRevenue > 0 
            ? (($thisWeekRevenue - $lastWeekRevenue) / $lastWeekRevenue) * 100 
            : ($thisWeekRevenue > 0 ? 100 : 0);

        return [
            'this_week' => $thisWeekRevenue,
            'last_week' => $lastWeekRevenue,
            'growth_percent' => round($growth, 2)
        ];
    }

    /**
     * Get Top selling products (Top 5)
     */
    public function getTopSellingProducts($limit = 5, $days = 7)
    {
        $startDate = Carbon::now()->subDays($days);

        return DB::table('order_products')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_products.quantity) as total_sold'))
            ->where('order_products.created_at', '>=', $startDate)
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }

    /**
     * Get products with lowest sales but high inventory (Slow moving - Top 3)
     */
    public function getCriticalSlowProducts($limit = 3)
    {
        $slowProducts = $this->getSlowMovingProducts(30);
        
        // Sort by inventory quantity descending (most critical)
        usort($slowProducts, function($a, $b) {
            return $b['quantity'] <=> $a['quantity'];
        });

        return array_slice($slowProducts, 0, $limit);
    }

    /**
     * Get cancellation rate
     */
    public function getCancellationRate($days = 7)
    {
        $startDate = Carbon::now()->subDays($days);

        $totalOrders = DB::table('orders')
            ->where('created_at', '>=', $startDate)
            ->count();

        $cancelledOrders = DB::table('orders')
            ->where('status', 'CANCEL')
            ->where('created_at', '>=', $startDate)
            ->count();

        if ($totalOrders == 0) return 0;

        return round(($cancelledOrders / $totalOrders) * 100, 2);
    }
}
