<?php

namespace App\Services;

class AIAnalyticsService
{
    protected $openai;
    protected $dataCollector;

    public function __construct()
    {
        $this->openai = new OpenAIService();
        $this->dataCollector = new DataCollectorService();
    }

    public function analyzeAndGenerateSuggestions()
    {
        try {
            $dataPrompt = $this->dataCollector->formatForPrompt();
            $prompt = $this->buildAnalysisPrompt($dataPrompt);
            $response = $this->openai->generateContent($prompt, true);
            $suggestions = $this->parseAISuggestions($response);
            return $suggestions;
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function buildAnalysisPrompt($dataPrompt)
    {
        return "Bạn là chuyên gia phân tích thương mại điện tử với 10 năm kinh nghiệm tại Việt Nam.

{$dataPrompt}

HÃY PHÂN TÍCH DỮ LIỆU TRÊN VÀ ĐƯA RA 5-10 GỢI Ý QUAN TRỌNG NHẤT để cải thiện doanh thu.

YÊU CẦU:
1. Phân tích toàn diện, không chỉ dựa vào 1 chỉ số
2. Ưu tiên các vấn đề nghiêm trọng nhất
3. Đưa ra hành động cụ thể, khả thi
4. Giải thích rõ lý do tại sao gợi ý này quan trọng

PHÂN LOẠI GỢI Ý:
- pricing: Vấn đề về giá bán (giá cao, tỷ lệ bỏ giỏ cao, conversion thấp)
- inventory: Vấn đề tồn kho (hàng tồn lâu, cần xả hàng)
- trending: Sản phẩm đang HOT (tăng trưởng mạnh, cần tăng stock)
- combo: Gợi ý bán kèm (sản phẩm hay mua cùng nhau)

**ĐẶC BIỆT - PHÂN TÍCH THỜI GIAN:**
- Nếu có dữ liệu thời gian (ngày cao điểm, giờ cao điểm), hãy đưa ra gợi ý dựa trên pattern
- Ví dụ: \"Chạy Flash Sale vào [ngày cao điểm] lúc [giờ cao điểm]\"
- Ví dụ: \"Chuẩn bị stock trước [ngày bán chạy]\"
- Đề cập cụ thể ngày/giờ trong description hoặc action nếu có dữ liệu

FORMAT JSON (CHỈ TRẢ VỀ JSON HỢP LỆ, KHÔNG THÊM TEXT KHÁC):
[
  {
    \"type\": \"pricing\",
    \"product_id\": 11,
    \"title\": \"Tiêu đề ngắn gọn (tối đa 50 ký tự)\",
    \"description\": \"Phân tích chi tiết 2-3 câu\",
    \"action\": \"Hành động cụ thể\",
    \"priority\": 3,
    \"reasoning\": \"Lý do quan trọng\"
  }
]

LƯU Ý QUAN TRỌNG: 
- KHÔNG để trailing comma sau phần tử cuối
- JSON phải hợp lệ 100%
- Kiểm tra kỹ syntax trước khi trả về

PRIORITY:
- 3: Rất quan trọng, cần xử lý ngay
- 2: Quan trọng, nên xử lý sớm
- 1: Có thể cải thiện

LƯU Ý: Chỉ trả về JSON array, không thêm markdown, không thêm text giải thích.";
    }

    protected function parseAISuggestions($response)
    {
        try {
            $response = preg_replace('/```json\s*/', '', $response);
            $response = preg_replace('/```\s*$/', '', $response);
            $response = trim($response);
            $response = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $response);
            $response = preg_replace('/,\s*([}\]])/', '$1', $response);

            $suggestions = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $response = str_replace(["\n", "\r", "\t"], [' ', ' ', ' '], $response);
                $suggestions = json_decode($response, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    return [];
                }
            }

            if (!is_array($suggestions)) {
                return [];
            }

            $validSuggestions = [];
            foreach ($suggestions as $suggestion) {
                if ($this->validateSuggestion($suggestion)) {
                    $validSuggestions[] = $suggestion;
                }
            }

            return $validSuggestions;
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function validateSuggestion($suggestion)
    {
        $required = ['type', 'product_id', 'title', 'description', 'action', 'priority'];

        foreach ($required as $field) {
            if (!isset($suggestion[$field])) {
                return false;
            }
        }

        $validTypes = ['pricing', 'inventory', 'trending', 'combo'];
        if (!in_array($suggestion['type'], $validTypes)) {
            return false;
        }

        if (!in_array($suggestion['priority'], [1, 2, 3])) {
            return false;
        }

        return true;
    }

    public function getBusinessInsights()
    {
        try {
            $data = $this->dataCollector->collectForAI();

            $prompt = "Bạn là chuyên gia tư vấn kinh doanh. Dựa trên dữ liệu sau:

Tổng sản phẩm: {$data['overall_stats']['total_products']}
Lượt xem (7d): {$data['overall_stats']['total_views_7d']}
Thêm giỏ (7d): {$data['overall_stats']['total_cart_adds_7d']}
Mua hàng (7d): {$data['overall_stats']['total_purchases_7d']}

Hãy đưa ra:
1. Đánh giá tổng quan tình hình kinh doanh (3-4 câu)
2. 3 xu hướng đáng chú ý
3. 3 khuyến nghị chiến lược quan trọng nhất

Trả lời bằng tiếng Việt, chuyên nghiệp và súc tích.";

            return $this->openai->generateContent($prompt);
        } catch (\Exception $e) {
            return null;
        }
    }
}
