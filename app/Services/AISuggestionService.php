<?php

namespace App\Services;

use App\Models\AISuggestion;
use App\Models\Product;

class AISuggestionService
{
    protected $aiAnalytics;

    public function __construct()
    {
        $this->aiAnalytics = new AIAnalyticsService();
    }

    public function generateAllSuggestions()
    {
        try {
            AISuggestion::truncate();
            $aiSuggestions = $this->aiAnalytics->analyzeAndGenerateSuggestions();

            if (empty($aiSuggestions)) {
                return;
            }

            foreach ($aiSuggestions as $suggestion) {
                $this->saveSuggestion($suggestion);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function saveSuggestion($aiSuggestion)
    {
        try {
            $product = Product::find($aiSuggestion['product_id']);
            if (!$product) {
                return;
            }

            AISuggestion::create([
                'suggestion_type' => $aiSuggestion['type'],
                'product_id' => $aiSuggestion['product_id'],
                'title' => $aiSuggestion['title'],
                'description' => $aiSuggestion['description'],
                'action_recommendation' => $aiSuggestion['action'],
                'metadata' => [
                    'ai_generated' => true,
                    'reasoning' => $aiSuggestion['reasoning'] ?? '',
                    'generated_at' => now()->toDateTimeString(),
                    'model' => 'ChatGPT',
                ],
                'priority' => $aiSuggestion['priority'],
                'is_active' => true,
                'is_dismissed' => false,
            ]);
        } catch (\Exception $e) {
            //
        }
    }

    public function getActiveSuggestions($limit = 20)
    {
        return AISuggestion::with('product')
            ->where('is_active', true)
            ->where('is_dismissed', false)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function dismissSuggestion($suggestionId)
    {
        $suggestion = AISuggestion::find($suggestionId);
        if ($suggestion) {
            $suggestion->update([
                'is_dismissed' => true,
                'dismissed_at' => now(),
            ]);
        }
    }

    public function getBusinessInsights()
    {
        return $this->aiAnalytics->getBusinessInsights();
    }
}
