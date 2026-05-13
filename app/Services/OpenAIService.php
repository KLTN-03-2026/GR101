<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected string $provider;
    protected ?string $openAiKey;
    protected ?string $geminiKey;
    protected string $model;
    protected int $cacheTtl;

    public function __construct()
    {
        $this->provider = env('AI_PROVIDER', 'openai');
        $this->openAiKey = env('OPENAI_API_KEY');
        $this->geminiKey = env('GEMINI_API_KEY');
        $this->model = env('OPENAI_MODEL', config('openai.model', 'gpt-4.1-mini'));
        $this->cacheTtl = (int) config('openai.cache_ttl', 3600);
    }

    public function generateContent($prompt, $useCache = true)
    {
        $cacheKey = 'ai_' . $this->provider . '_' . $this->model . '_' . md5($prompt);
        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $result = null;
        if ($this->provider === 'openai' && $this->openAiKey) {
            $result = $this->callOpenAI($prompt);
        }

        if (!$result && $this->geminiKey) {
            $result = $this->callGemini($prompt);
        }

        if (!$result) {
            $result = $this->buildSmartFallback($prompt);
        }

        if ($useCache && $result) {
            Cache::put($cacheKey, $result, $this->cacheTtl);
        }

        return $result;
    }

    protected function callOpenAI(string $prompt): ?string
    {
        try {
            $response = Http::withToken($this->openAiKey)
                ->timeout(30)
                ->acceptJson()
                ->post(config('openai.api_url', 'https://api.openai.com/v1/responses'), [
                    'model' => $this->model,
                    'input' => [
                        [
                            'role' => 'system',
                            'content' => 'Return only valid JSON. Do not include markdown or explanations.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'text' => [
                        'format' => ['type' => 'json_object'],
                    ],
                    'temperature' => (float) config('openai.temperature', 0.2),
                    'max_output_tokens' => (int) config('openai.max_tokens', 2000),
                ]);

            if (!$response->successful()) {
                Log::warning('OpenAI API failed: ' . $response->status() . ' - ' . $response->body());
                return null;
            }

            $data = $response->json();
            if (!empty($data['output_text'])) {
                return $this->tagJsonSource($data['output_text'], 'openai');
            }

            $parts = [];
            foreach (($data['output'] ?? []) as $output) {
                foreach (($output['content'] ?? []) as $content) {
                    if (isset($content['text'])) {
                        $parts[] = $content['text'];
                    }
                }
            }

            $text = trim(implode("\n", $parts));
            return $text !== '' ? $this->tagJsonSource($text, 'openai') : null;
        } catch (\Throwable $e) {
            Log::warning('OpenAI API exception: ' . $e->getMessage());
            return null;
        }
    }

    protected function callGemini(string $prompt): ?string
    {
        try {
            $model = env('GEMINI_MODEL', 'gemini-2.0-flash-lite');
            $url = "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$this->geminiKey}";

            $response = Http::timeout(20)->post($url, [
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 4000,
                ],
            ]);

            if (!$response->successful()) {
                Log::warning('Gemini API failed: ' . $response->status() . ' - ' . $response->body());
                return null;
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            return $text ? $this->tagJsonSource($text, 'gemini') : null;
        } catch (\Throwable $e) {
            Log::warning('Gemini API exception: ' . $e->getMessage());
            return null;
        }
    }

    protected function buildSmartFallback(string $prompt): string
    {
        $dishName = $this->extractDishNameFromPrompt($prompt);
        $localDish = DB::table('cong_thuc')
            ->where('tenmonan', 'LIKE', "%{$dishName}%")
            ->first();

        $ingredients = [];
        $steps = [];
        $description = "Gợi ý nguyên liệu và cách nấu cho món {$dishName}.";

        if ($localDish) {
            $dishName = $localDish->tenmonan;
            $description = "Công thức tham khảo cho món {$dishName} từ dữ liệu nội bộ.";
            $ingredients = $this->parseIngredients($localDish->congthuc ?? '');
            $steps = $this->parseSteps($localDish->congthuc ?? '');
        }

        if (!$ingredients) {
            $ingredients = $this->guessIngredientsFromDishName($dishName);
        }

        if (!$steps) {
            $steps = [
                'Sơ chế sạch nguyên liệu.',
                'Chế biến theo khẩu vị gia đình.',
                'Nêm nếm vừa ăn và dùng khi còn nóng.',
            ];
        }

        return json_encode([
            'source' => 'fallback',
            'ten_mon' => $dishName,
            'mo_ta' => $description,
            'nguyen_lieu' => $ingredients,
            'cach_lam' => $steps,
            // If we have a local recipe, avoid matching the dish name itself to products
            // (user requested: don't show the dish already present in DB). Keep ingredient matches.
            'san_pham_ids' => $this->findMatchingProducts($ingredients, $dishName, $localDish ? false : true),
        ], JSON_UNESCAPED_UNICODE);
    }

    protected function tagJsonSource(string $text, string $source): string
    {
        $start = strpos($text, '{');
        $end = strrpos($text, '}');
        if ($start === false || $end === false || $end <= $start) {
            return $text;
        }

        $json = substr($text, $start, $end - $start + 1);
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return $text;
        }

        $data['source'] = $source;
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    protected function extractDishNameFromPrompt(string $prompt): string
    {
        if (preg_match("/món[:\s]*['\"]?([^'\".\n]+)/iu", $prompt, $matches)) {
            return trim($matches[1]);
        }

        if (preg_match("/mon[:\s]*['\"]?([^'\".\n]+)/iu", $prompt, $matches)) {
            return trim($matches[1]);
        }

        return 'Món ăn';
    }

    protected function parseIngredients(?string $text): array
    {
        if (!$text) {
            return [];
        }

        $block = $text;
        if (preg_match('/nguyên liệu[:\s]*(.*?)(cách làm|cách nấu|hướng dẫn|bước \d|$)/isu', $text, $matches)) {
            $block = $matches[1];
        }

        $items = [];
        foreach (preg_split('/[\n\r;,]+/', $block) as $line) {
            $line = trim($line, " \t\n\r\0\x0B-*•");
            $line = preg_replace('/^\d+[\.\)]\s*/', '', $line);
            if ($line && mb_strlen($line) > 1 && mb_strlen($line) < 90) {
                $items[] = $line;
            }
        }

        return array_slice(array_values(array_unique($items)), 0, 10);
    }

    protected function parseSteps(?string $text): array
    {
        if (!$text) {
            return [];
        }

        $block = $text;
        if (preg_match('/cách (làm|nấu)[:\s]*(.*)/isu', $text, $matches)) {
            $block = $matches[2];
        }

        $steps = [];
        foreach (preg_split('/[\n\r]+/', $block) as $line) {
            $line = trim($line, " \t\n\r\0\x0B-*•");
            $line = preg_replace('/^\d+[\.\)]\s*/', '', $line);
            if ($line && mb_strlen($line) > 8) {
                $steps[] = $line;
            }
        }

        return array_slice(array_values(array_unique($steps)), 0, 8);
    }

    protected function guessIngredientsFromDishName(string $dishName): array
    {
        $lower = mb_strtolower($dishName);
        $mapping = [
            'cá' => ['Cá', 'Nước mắm', 'Hành', 'Tỏi', 'Ớt'],
            'thịt' => ['Thịt', 'Nước mắm', 'Hành', 'Tỏi', 'Tiêu'],
            'gà' => ['Gà', 'Hành', 'Tỏi', 'Gừng', 'Sả'],
            'tôm' => ['Tôm', 'Hành', 'Tỏi', 'Ớt', 'Tiêu'],
            'bò' => ['Thịt bò', 'Hành tây', 'Tỏi', 'Tiêu', 'Dầu ăn'],
            'heo' => ['Thịt heo', 'Hành', 'Tỏi', 'Nước mắm'],
            'đậu' => ['Đậu hũ', 'Hành', 'Nước tương', 'Dầu ăn'],
            'rau' => ['Rau', 'Tỏi', 'Dầu ăn', 'Nước mắm'],
            'trứng' => ['Trứng', 'Hành', 'Nước mắm', 'Tiêu'],
            'cơm' => ['Cơm', 'Trứng', 'Hành', 'Dầu ăn'],
            'canh' => ['Rau', 'Nước mắm', 'Hành', 'Tỏi'],
        ];

        $ingredients = [];
        foreach ($mapping as $keyword => $items) {
            if (mb_strpos($lower, $keyword) !== false) {
                $ingredients = array_merge($ingredients, $items);
            }
        }

        return $ingredients ? array_values(array_unique($ingredients)) : [$dishName, 'Gia vị'];
    }

    protected function findMatchingProducts(array $ingredientNames, string $dishName, bool $includeDishName = true): array
    {
        $ids = [];
        $searchList = $ingredientNames;
        if ($includeDishName) {
            $searchList = array_merge($searchList, [$dishName]);
        }

        foreach ($searchList as $name) {
            $keyword = $this->cleanSearchKeyword($name);
            if (!$keyword) {
                continue;
            }

            $found = Product::where('status', 1)
                ->where('name', 'LIKE', "%{$keyword}%")
                ->limit(3)
                ->pluck('id')
                ->toArray();

            $ids = array_merge($ids, $found);
        }

        return array_values(array_unique($ids));
    }

    protected function cleanSearchKeyword(string $value): ?string
    {
        $value = mb_strtolower(trim($value));
        $value = preg_replace('/\b\d+([.,]\d+)?\s*(kg|g|gram|gr|ml|l|cái|quả|trái|muỗng|thìa|gói|gam|viên|tép|cây|miếng)\b/iu', '', $value);
        $value = trim(preg_replace('/\s+/', ' ', $value));

        $stopwords = ['gia vị', 'nước mắm', 'muối', 'đường', 'tiêu', 'hành', 'tỏi', 'ớt', 'dầu ăn'];
        if ($value === '' || in_array($value, $stopwords, true) || mb_strlen($value) < 2) {
            return null;
        }

        return $value;
    }
}
