<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityTracker
{
    /**
     * Track product view
     */
    public static function trackView($productId)
    {
        self::log('view', $productId);
    }

    /**
     * Track add to cart
     */
    public static function trackAddToCart($productId, $quantity = 1)
    {
        self::log('add_to_cart', $productId, $quantity);
    }

    /**
     * Track remove from cart
     */
    public static function trackRemoveFromCart($productId, $quantity = 1)
    {
        self::log('remove_from_cart', $productId, $quantity);
    }

    /**
     * Track search query
     */
    public static function trackSearch($query)
    {
        ActivityLog::create([
            'activity_type' => 'search',
            'user_id' => Auth::id(),
            'search_query' => $query,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Track purchase
     */
    public static function trackPurchase($productId, $quantity)
    {
        self::log('purchase', $productId, $quantity);
    }

    /**
     * Generic logging method
     */
    private static function log($activityType, $productId = null, $quantity = null)
    {
        ActivityLog::create([
            'activity_type' => $activityType,
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'quantity' => $quantity,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
