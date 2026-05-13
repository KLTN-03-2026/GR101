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
            \Log::error("AI Generate Suggestions Error: " . $e->getMessage());
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
{
  \"suggestions\": [
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
}

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

            if (isset($suggestions['suggestions']) && is_array($suggestions['suggestions'])) {
                $suggestions = $suggestions['suggestions'];
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

    public function getBusinessAnalysisReport()
    {
        try {
            $dataPrompt = $this->dataCollector->formatForPrompt();

            $prompt = "Bạn là một chuyên gia phân tích dữ liệu kinh doanh và chiến lược gia thương mại điện tử.
Dựa trên dữ liệu thực tế của cửa hàng nông sản sau đây:

{$dataPrompt}

Nhiệm vụ: Phân tích sâu dữ liệu trên và trả về một báo cáo chiến lược NGẮN GỌN dưới định dạng JSON.
Yêu cầu cấu trúc JSON:
{
  \"nhan_dinh\": \"Đánh giá tổng quan về doanh thu và hoạt động tuần qua (2-3 câu)\",
  \"canh_bao\": \"Các rủi ro về tồn kho, tỉ lệ bỏ giỏ hoặc sản phẩm đang đi xuống (2-3 câu)\",
  \"de_xuat\": \"Chiến lược cụ thể để tăng doanh thu ngay lập tức (ví dụ: tạo coupon, flash sale)\",
  \"action_params\": {
      \"coupon_code\": \"AI_PROMO_XXXX\",
      \"discount_value\": 10,
      \"type\": \"percent\"
  }
}

Lưu ý quan trọng:
- Trả về JSON tiếng Việt.
- CHỈ trả về JSON, KHÔNG thêm text hay markdown code block.
- KHÔNG sử dụng ký tự xuống dòng hoặc tab bên trong các giá trị string.
- action_params phải gợi ý mã coupon thực tế dựa trên tình hình.
- TRẢ VỀ JSON TRÊN MỘT DÒNG DUY NHẤT, KHÔNG CÓ KÝ TỰ XUỐNG DÒNG.";

            $response = $this->openai->generateContent($prompt, false);

            if (!$response) {
                return null;
            }

            // --- Robust JSON Cleaning ---
            $rawLength = strlen($response);
            
            // 1. Remove markdown code fences (any case)
            $response = preg_replace('/^```(?:json)?\s*/i', '', $response);
            $response = preg_replace('/\s*```$/', '', $response);

            // 2. Replace all control characters (0-31) and 127 with space
            $response = preg_replace('/[\x00-\x1F\x7F]/', ' ', $response);

            $response = trim($response);
            $cleanLength = strlen($response);

            // Try to parse
            $parsed = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error('Business Analysis JSON parse failed: ' . json_last_error_msg());
                return null;
            }

            if (!$this->validateBusinessReport($parsed)) {
                \Log::warning('Business Analysis returned invalid shape: ' . json_encode(array_keys((array) $parsed)));
                return $this->buildFallbackBusinessReport();
            }

            return $parsed;
        } catch (\Exception $e) {
            \Log::error("Business Analysis Error: " . $e->getMessage());
            return null;
        }
    }

    protected function validateBusinessReport($report): bool
    {
        if (!is_array($report)) {
            return false;
        }

        foreach (['nhan_dinh', 'canh_bao', 'de_xuat', 'action_params'] as $key) {
            if (!array_key_exists($key, $report)) {
                return false;
            }
        }

        return is_array($report['action_params']);
    }

    protected function buildFallbackBusinessReport(): array
    {
        $data = $this->dataCollector->collectForAI();
        $stats = $data['overall_stats'];
        $topProduct = $data['products'][0] ?? null;

        $nhanDinh = "Trong 7 ngày gần nhất, hệ thống ghi nhận {$stats['total_views_7d']} lượt xem, {$stats['total_cart_adds_7d']} lượt thêm giỏ và {$stats['total_purchases_7d']} lượt mua. Dữ liệu hiện tại đủ để đưa ra gợi ý vận hành cơ bản, nhưng nên tiếp tục tăng tracking để AI phân tích chính xác hơn.";

        $canhBao = "Tỷ lệ mua hàng còn phụ thuộc mạnh vào số lượt thêm giỏ và lượng tồn kho. Cần theo dõi các sản phẩm có nhiều lượt xem nhưng ít mua để tránh tồn kho chậm quay vòng.";

        $deXuat = "Tạo mã giảm giá ngắn hạn 10% cho nhóm sản phẩm đang có tương tác, kết hợp đẩy banner trong khung giờ cao điểm để tăng chuyển đổi.";
        if ($topProduct) {
            $deXuat = "Ưu tiên chạy mã giảm giá 10% hoặc combo cho {$topProduct['name']} vì đây là sản phẩm nổi bật trong dữ liệu hoạt động gần đây.";
        }

        return [
            'source' => 'local_fallback',
            'nhan_dinh' => $nhanDinh,
            'canh_bao' => $canhBao,
            'de_xuat' => $deXuat,
            'action_params' => [
                'coupon_code' => 'AI_PROMO_' . now()->format('md'),
                'discount_value' => 10,
                'type' => 'percent',
            ],
        ];
    }
}
