<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AISuggestionService;
use App\Services\AnalyticsService;
use App\Models\AISuggestion;
use Illuminate\Http\Request;

class AIDashboardController extends Controller
{
    protected $aiService;
    protected $analytics;

    public function __construct()
    {
        $this->aiService = new AISuggestionService();
        $this->analytics = new AnalyticsService();
    }

    /**
     * Display AI Dashboard
     */
    public function index()
    {
        $suggestions = $this->aiService->getActiveSuggestions(20);

        // Group suggestions by type
        $groupedSuggestions = $suggestions->groupBy('suggestion_type');

        // Get trending products
        $trending = $this->analytics->getTrendingProducts(30);

        return view('admin.ai_dashboard.index', compact('groupedSuggestions', 'trending'));
    }

    /**
     * Dismiss a suggestion
     */
    public function dismissSuggestion(Request $request)
    {
        $suggestionId = $request->get('suggestion_id');
        $this->aiService->dismissSuggestion($suggestionId);

        return response()->json([
            'success' => true,
            'message' => 'Đã ẩn gợi ý này'
        ]);
    }

    /**
     * Get analytics data for charts (AJAX)
     */
    public function getAnalyticsData(Request $request)
    {
        $productId = $request->get('product_id');

        if (!$productId) {
            return response()->json(['error' => 'Product ID required'], 400);
        }

        $data = [
            'conversion_rate' => $this->analytics->getConversionRate($productId),
            'abandonment_rate' => $this->analytics->getCartAbandonmentRate($productId),
            'pricing_analysis' => $this->analytics->analyzePricing($productId),
        ];

        return response()->json($data);
    }
}
