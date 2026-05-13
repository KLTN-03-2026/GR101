<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AISuggestionService;
use App\Services\AIAnalyticsService;
use Illuminate\Http\Request;

class AIDashboardController extends Controller
{
    protected $aiService;

    public function __construct()
    {
        $this->aiService = new AISuggestionService();
    }

    /**
     * Display AI Dashboard
     */
    public function index()
    {
        if (!auth('admin')->user()->isSuperAdmin()) {
            return redirect()->route('admin.index')->with('error', 'Bạn không có quyền truy cập chức năng này.');
        }

        $suggestions = $this->aiService->getActiveSuggestions(20);

        // Group suggestions by type
        $groupedSuggestions = $suggestions->groupBy('suggestion_type');

        return view('admin.ai_dashboard.index', compact('groupedSuggestions'));
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
     * Get AI Business Analysis Report (PB21)
     */
    public function getBusinessAnalysis(Request $request)
    {
        if (!auth('admin')->user()->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $report = $this->aiService->getBusinessAnalysisReport();

            if (!$report) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI không thể tạo báo cáo lúc này. Vui lòng thử lại sau.'
                ], 500);
            }

            try {
                $this->aiService->generateAllSuggestions();
            } catch (\Exception $suggestionException) {
                \Log::warning('AI suggestions refresh failed: ' . $suggestionException->getMessage());
            }

            return response()->json([
                'success' => true,
                'data' => $report
            ]);
        } catch (\Exception $e) {
            \Log::error("AI Business Analysis Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'AI đang bận, vui lòng thử lại sau.'
            ], 500);
        }
    }

    /**
     * Return active AI product suggestions as JSON
     */
    public function getSuggestions(Request $request)
    {
        if (!auth('admin')->user()->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $suggestions = $this->aiService->getActiveSuggestions(50);
            $suggestions->load('product');

            return response()->json([
                'success' => true,
                'data' => $suggestions->map(function($s){
                    return [
                        'id' => $s->id,
                        'type' => $s->suggestion_type,
                        'title' => $s->title,
                        'description' => $s->description,
                        'action' => $s->action_recommendation,
                        'product' => $s->product ? [
                            'id' => $s->product->id,
                            'name' => $s->product->name,
                            'edit_url' => route('admin.products.edit', $s->product->id)
                        ] : null,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            \Log::error('AI Suggestions fetch error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Không thể lấy gợi ý'], 500);
        }
    }
}
