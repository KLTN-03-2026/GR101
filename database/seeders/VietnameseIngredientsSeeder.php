<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Carbon;

class VietnameseIngredientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        $categories = [
            'Gạo & Lương thực',
            'Gia vị & Khô',
            'Rau củ',
            'Thịt & Hải sản',
            'Sữa & Trứng',
            'Đồ khô/khác',
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insertOrIgnore(['name' => $cat, 'created_at' => $now, 'updated_at' => $now]);
        }

        $categoryMap = DB::table('categories')->whereIn('name', $categories)->pluck('id', 'name')->toArray();

        $items = [
            ['name' => 'Gạo tẻ', 'description' => 'Gạo thơm dùng cho cơm hàng ngày', 'price' => 30000, 'quantity' => 100, 'unit' => 'kg', 'category' => 'Gạo & Lương thực', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nước mắm', 'description' => 'Nước mắm truyền thống', 'price' => 60000, 'quantity' => 50, 'unit' => 'l', 'category' => 'Gia vị & Khô', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Đường trắng', 'description' => 'Đường tinh luyện', 'price' => 18000, 'quantity' => 200, 'unit' => 'kg', 'category' => 'Gia vị & Khô', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Muối i-ốt', 'description' => 'Muối ăn hàng ngày', 'price' => 8000, 'quantity' => 200, 'unit' => 'kg', 'category' => 'Gia vị & Khô', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tiêu nguyên hạt', 'description' => 'Tiêu đen', 'price' => 120000, 'quantity' => 30, 'unit' => 'kg', 'category' => 'Gia vị & Khô', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tỏi', 'description' => 'Tỏi ta', 'price' => 30000, 'quantity' => 80, 'unit' => 'kg', 'category' => 'Gia vị & Khô', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hành tím', 'description' => 'Hành tím dùng nấu ăn', 'price' => 20000, 'quantity' => 80, 'unit' => 'kg', 'category' => 'Gia vị & Khô', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ớt tươi', 'description' => 'Ớt đỏ/ xanh', 'price' => 25000, 'quantity' => 60, 'unit' => 'kg', 'category' => 'Gia vị & Khô', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Dầu ăn', 'description' => 'Dầu thực vật', 'price' => 35000, 'quantity' => 100, 'unit' => 'l', 'category' => 'Gia vị & Khô', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nước tương', 'description' => 'Nước tương truyền thống', 'price' => 30000, 'quantity' => 60, 'unit' => 'l', 'category' => 'Gia vị & Khô', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Thịt heo', 'description' => 'Thịt heo tươi', 'price' => 120000, 'quantity' => 40, 'unit' => 'kg', 'category' => 'Thịt & Hải sản', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Thịt gà', 'description' => 'Gà ta', 'price' => 90000, 'quantity' => 40, 'unit' => 'kg', 'category' => 'Thịt & Hải sản', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Thịt bò', 'description' => 'Thịt bò tươi', 'price' => 220000, 'quantity' => 30, 'unit' => 'kg', 'category' => 'Thịt & Hải sản', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tôm tươi', 'description' => 'Tôm biển', 'price' => 180000, 'quantity' => 30, 'unit' => 'kg', 'category' => 'Thịt & Hải sản', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cá (cá biển)', 'description' => 'Cá tươi', 'price' => 120000, 'quantity' => 50, 'unit' => 'kg', 'category' => 'Thịt & Hải sản', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Đậu hũ', 'description' => 'Đậu phụ', 'price' => 25000, 'quantity' => 60, 'unit' => 'bịch', 'category' => 'Đồ khô/khác', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Rau muống', 'description' => 'Rau xanh', 'price' => 15000, 'quantity' => 100, 'unit' => 'bó', 'category' => 'Rau củ', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Rau xà lách', 'description' => 'Rau salad/ ăn kèm', 'price' => 12000, 'quantity' => 100, 'unit' => 'bó', 'category' => 'Rau củ', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bún/ Hủ tiếu/ Phở (gói)', 'description' => 'Bún phở khô', 'price' => 20000, 'quantity' => 100, 'unit' => 'gói', 'category' => 'Gạo & Lương thực', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bánh phở', 'description' => 'Bánh phở tươi', 'price' => 25000, 'quantity' => 80, 'unit' => 'gói', 'category' => 'Gạo & Lương thực', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bột năng', 'description' => 'Bột làm bánh/ kết dính', 'price' => 18000, 'quantity' => 60, 'unit' => 'kg', 'category' => 'Đồ khô/khác', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bánh tráng', 'description' => 'Bánh tráng cuốn/ nướng', 'price' => 15000, 'quantity' => 80, 'unit' => 'gói', 'category' => 'Đồ khô/khác', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nước cốt dừa', 'description' => 'Nước cốt dừa đóng hộp', 'price' => 45000, 'quantity' => 40, 'unit' => 'hộp', 'category' => 'Đồ khô/khác', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Trứng gà', 'description' => 'Trứng gà ta', 'price' => 5000, 'quantity' => 200, 'unit' => 'quả', 'category' => 'Sữa & Trứng', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Chanh/ Quất', 'description' => 'Chanh tươi', 'price' => 15000, 'quantity' => 120, 'unit' => 'kg', 'category' => 'Rau củ', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gừng', 'description' => 'Gừng tươi', 'price' => 30000, 'quantity' => 60, 'unit' => 'kg', 'category' => 'Rau củ', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sả', 'description' => 'Sả tươi', 'price' => 20000, 'quantity' => 80, 'unit' => 'bó', 'category' => 'Rau củ', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($items as &$it) {
            if (isset($it['category']) && isset($categoryMap[$it['category']])) {
                $it['category_id'] = $categoryMap[$it['category']];
            } else {
                $it['category_id'] = null;
            }
            unset($it['category']);
        }

        DB::table('products')->insertOrIgnore($items);
    }
}
