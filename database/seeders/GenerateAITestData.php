<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\Product;
use Carbon\Carbon;

class GenerateAITestData extends Seeder
{
    public function run()
    {
        $this->command->info('🤖 Generating AI test data...');

        $products = Product::limit(10)->get();

        if ($products->isEmpty()) {
            $this->command->error('❌ No products found! Please add products first.');
            return;
        }

        $this->command->info("Found {$products->count()} products");

        // 1. Generate product views (last 7 days)
        $this->command->info('📊 Creating product views...');
        $viewsCreated = 0;
        foreach ($products as $product) {
            $viewCount = rand(15, 60);
            for ($i = 0; $i < $viewCount; $i++) {
                ActivityLog::create([
                    'activity_type' => 'view',
                    'product_id' => $product->id,
                    'user_id' => rand(1, 10) > 7 ? rand(1, 5) : null,
                    'ip_address' => '127.0.0.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0',
                    'created_at' => Carbon::now()->subDays(rand(0, 6))->subHours(rand(0, 23)),
                ]);
                $viewsCreated++;
            }
        }

        // 2. Generate add to cart (~30% of views)
        $this->command->info('🛒 Creating add to cart events...');
        $cartsCreated = 0;
        foreach ($products as $product) {
            $cartCount = rand(5, 15);
            for ($i = 0; $i < $cartCount; $i++) {
                ActivityLog::create([
                    'activity_type' => 'add_to_cart',
                    'product_id' => $product->id,
                    'user_id' => rand(1, 10) > 5 ? rand(1, 5) : null,
                    'quantity' => rand(1, 3),
                    'ip_address' => '127.0.0.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0',
                    'created_at' => Carbon::now()->subDays(rand(0, 5))->subHours(rand(0, 23)),
                ]);
                $cartsCreated++;
            }
        }

        // 3. Generate purchases (~10% of add_to_cart)
        $this->command->info('💰 Creating purchase events...');
        $purchasesCreated = 0;
        foreach ($products as $product) {
            $purchaseCount = rand(1, 3);
            for ($i = 0; $i < $purchaseCount; $i++) {
                ActivityLog::create([
                    'activity_type' => 'purchase',
                    'product_id' => $product->id,
                    'user_id' => rand(1, 5),
                    'quantity' => rand(1, 2),
                    'ip_address' => '127.0.0.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0',
                    'created_at' => Carbon::now()->subDays(rand(0, 4))->subHours(rand(0, 23)),
                ]);
                $purchasesCreated++;
            }
        }

        // 4. Generate search queries
        $this->command->info('🔍 Creating search logs...');
        $searchTerms = ['gạo', 'rau', 'thịt', 'cá', 'trái cây', 'sữa', 'bánh', 'nông sản'];
        $searchesCreated = 0;
        foreach ($searchTerms as $term) {
            for ($i = 0; $i < rand(5, 12); $i++) {
                ActivityLog::create([
                    'activity_type' => 'search',
                    'search_query' => $term,
                    'user_id' => rand(1, 10) > 5 ? rand(1, 5) : null,
                    'ip_address' => '127.0.0.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0',
                    'created_at' => Carbon::now()->subDays(rand(0, 6))->subHours(rand(0, 23)),
                ]);
                $searchesCreated++;
            }
        }

        $this->command->info('');
        $this->command->info('✅ Test data generated successfully!');
        $this->command->table(
            ['Type', 'Count'],
            [
                ['Views', $viewsCreated],
                ['Add to Cart', $cartsCreated],
                ['Purchases', $purchasesCreated],
                ['Searches', $searchesCreated],
                ['Total Activity Logs', ActivityLog::count()],
            ]
        );
        $this->command->info('');
        $this->command->warn('⚠️  Note: Combo suggestions require order data.');
        $this->command->info('💡 Next step: Run "php artisan ai:generate-suggestions"');
    }
}
