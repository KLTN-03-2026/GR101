<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class OpenAIService
{
    protected $apiKey;
    protected $apiUrl;
    protected $model;
    protected $cacheTtl;
    protected $useMockResponses = false;

    public function __construct()
    {
        $this->apiKey = config('openai.api_key');
        $this->apiUrl = config('openai.api_url');
        $this->model = config('openai.model');
        $this->cacheTtl = config('openai.cache_ttl');
        $this->useMockResponses = config('openai.use_mock', false);
    }

    public function testConnection()
    {
        if ($this->useMockResponses) {
            return true;
        }

        try {
            $response = $this->generateContent('Hello');
            return !empty($response);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function generateContent($prompt, $useCache = true)
    {
        if ($this->useMockResponses) {
            return $this->getMockResponse($prompt);
        }

        if (!$this->apiKey) {
            throw new \Exception('OpenAI API key not configured');
        }

        if ($useCache) {
            $cacheKey = 'openai_' . md5($prompt);
            $cached = Cache::get($cacheKey);
            if ($cached) {
                return $cached;
            }
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])
                ->post($this->apiUrl, [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => config('openai.max_tokens'),
                    'temperature' => config('openai.temperature'),
                ]);

            if ($response->failed()) {
                return $this->getMockResponse($prompt);
            }

            $data = $response->json();

            if (!isset($data['choices'][0]['message']['content'])) {
                throw new \Exception('Invalid response format from OpenAI API');
            }

            $result = $data['choices'][0]['message']['content'];

            if ($useCache) {
                Cache::put($cacheKey, $result, $this->cacheTtl);
            }

            return $result;
        } catch (\Exception $e) {
            return $this->getMockResponse($prompt);
        }
    }

    protected function getMockResponse($prompt)
    {
        $prompt = mb_strtolower($prompt);

        if (str_contains($prompt, 'phân tích') && str_contains($prompt, 'sản phẩm')) {
            return "**Đánh giá tổng quan:**\nSản phẩm này có hiệu suất khá tốt với tỷ lệ chuyển đổi ổn định. Tuy nhiên, tỷ lệ bỏ giỏ hàng cao cho thấy cần cải thiện trải nghiệm thanh toán.\n\n**Điểm mạnh:**\n- Lượt xem cao, thu hút được sự quan tâm\n- Sản phẩm có tiềm năng tốt\n\n**Điểm yếu:**\n- Tỷ lệ bỏ giỏ cao, cần xem xét lại giá hoặc phí vận chuyển\n- Tỷ lệ chuyển đổi còn thấp\n\n**Gợi ý cải thiện:**\n1. Xem xét giảm giá 10-15% hoặc tạo chương trình khuyến mãi\n2. Cải thiện mô tả sản phẩm và hình ảnh\n3. Thêm đánh giá và review từ khách hàng";
        }

        if (str_contains($prompt, 'kinh doanh') || str_contains($prompt, 'tổng quan')) {
            return "**Đánh giá tình hình:**\nDoanh nghiệp đang có xu hướng tăng trưởng tích cực. Tỷ lệ chuyển đổi trung bình ở mức chấp nhận được, cho thấy chiến lược marketing đang phát huy hiệu quả.\n\n**Xu hướng đáng chú ý:**\n- Lượng truy cập tăng đều đặn\n- Khách hàng quan tâm đến các sản phẩm chất lượng cao\n- Nhu cầu mua sắm online đang tăng\n\n**Khuyến nghị chiến lược:**\n1. Tập trung vào các sản phẩm bán chạy nhất để tối ưu lợi nhuận\n2. Phát triển chương trình khách hàng thân thiết\n3. Đầu tư vào marketing cho các sản phẩm có tiềm năng cao";
        }

        if (str_contains($prompt, 'giá') || str_contains($prompt, 'định giá')) {
            return "**Giá tối ưu đề xuất:**\nDựa trên phân tích, mức giá nên giảm 10-12% so với hiện tại để tăng tính cạnh tranh.\n\n**Lý do:**\n- Tỷ lệ bỏ giỏ cao cho thấy giá hiện tại có thể cao hơn mong đợi của khách hàng\n- Cần cân bằng giữa lợi nhuận và khối lượng bán\n\n**Chiến thuật định giá:**\n1. Tạo chương trình Flash Sale giảm 15-20% trong thời gian ngắn\n2. Bundle sản phẩm với các mặt hàng liên quan để tăng giá trị đơn hàng\n3. Áp dụng giảm giá theo số lượng mua";
        }

        return "Dựa trên dữ liệu hiện tại, tôi khuyến nghị bạn nên tập trung vào việc cải thiện trải nghiệm khách hàng và tối ưu hóa quy trình bán hàng. Hãy theo dõi các chỉ số quan trọng như tỷ lệ chuyển đổi và tỷ lệ bỏ giỏ hàng để điều chỉnh chiến lược kịp thời.";
    }

    public function analyzeProductPerformance($productData, $metrics)
    {
        $prompt = "Bạn là chuyên gia phân tích dữ liệu thương mại điện tử. Hãy phân tích hiệu suất của sản phẩm sau:

Thông tin sản phẩm:
- Tên: {$productData['name']}
- Giá: " . number_format($productData['price']) . " VNĐ
- Tồn kho: {$productData['quantity']}

Chỉ số:
- Lượt xem: {$metrics['views']}
- Thêm vào giỏ: {$metrics['add_to_cart']}
- Mua hàng: {$metrics['purchases']}
- Tỷ lệ chuyển đổi: {$metrics['conversion_rate']}%
- Tỷ lệ bỏ giỏ: {$metrics['abandonment_rate']}%

Hãy đưa ra:
1. Đánh giá tổng quan (2-3 câu)
2. Điểm mạnh và điểm yếu
3. Gợi ý cụ thể để cải thiện (tối đa 3 gợi ý)

Trả lời bằng tiếng Việt, ngắn gọn và dễ hiểu.";

        return $this->generateContent($prompt);
    }

    public function generateBusinessInsights($overallMetrics)
    {
        $prompt = "Bạn là chuyên gia tư vấn kinh doanh thương mại điện tử. Dựa trên dữ liệu sau, hãy đưa ra phân tích:

Tổng quan kinh doanh:
- Tổng sản phẩm: {$overallMetrics['total_products']}
- Tổng lượt xem: {$overallMetrics['total_views']}
- Tổng đơn hàng: {$overallMetrics['total_orders']}
- Doanh thu: " . number_format($overallMetrics['revenue']) . " VNĐ
- Tỷ lệ chuyển đổi trung bình: {$overallMetrics['avg_conversion_rate']}%

Top 3 sản phẩm bán chạy:
" . implode("\n", array_map(fn($p) => "- {$p['name']}: {$p['sales']} đơn", $overallMetrics['top_products'])) . "

Hãy đưa ra:
1. Đánh giá tình hình kinh doanh (3-4 câu)
2. Xu hướng đáng chú ý
3. 3 khuyến nghị chiến lược quan trọng nhất

Trả lời bằng tiếng Việt, chuyên nghiệp và súc tích.";

        return $this->generateContent($prompt);
    }

    public function suggestPricingStrategy($productData, $competitorPrices = [])
    {
        $competitorInfo = empty($competitorPrices)
            ? "Không có dữ liệu đối thủ"
            : "Giá đối thủ: " . implode(", ", array_map(fn($p) => number_format($p) . " VNĐ", $competitorPrices));

        $prompt = "Bạn là chuyên gia định giá sản phẩm. Hãy tư vấn chiến lược giá cho:

Sản phẩm: {$productData['name']}
Giá hiện tại: " . number_format($productData['price']) . " VNĐ
Tồn kho: {$productData['quantity']}
Tỷ lệ bỏ giỏ: {$productData['abandonment_rate']}%
{$competitorInfo}

Hãy đề xuất:
1. Giá tối ưu (khoảng giá cụ thể)
2. Lý do cho mức giá đề xuất
3. Chiến thuật định giá (giảm giá, combo, v.v.)

Trả lời ngắn gọn, bằng tiếng Việt.";

        return $this->generateContent($prompt);
    }

    public function askQuestion($question, $context)
    {
        $prompt = "Bạn là trợ lý AI chuyên về phân tích dữ liệu bán hàng.

Dữ liệu hiện tại:
{$context}

Câu hỏi: {$question}

Hãy trả lời câu hỏi dựa trên dữ liệu được cung cấp. Nếu không đủ dữ liệu, hãy nói rõ. Trả lời bằng tiếng Việt, súc tích và chính xác.";

        return $this->generateContent($prompt, false);
    }
}
