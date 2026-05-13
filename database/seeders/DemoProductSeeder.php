<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 8: Trái Cây
        // 9: Rau Củ
        // 10: Thịt Tươi & Hải Sản
        // 11: Trứng
        // 12: Sữa tươi
        // 13: Thực Phẩm Khô & Gia Vị
        // 14: Thực Phẩm Đông Lạnh & Chế Biến Sẵn

        $products = [
            // Category 8: Trái Cây
            ['name' => 'Táo Fuji Nam Phi', 'price' => 55000, 'category_id' => 8, 'quantity' => 100],
            ['name' => 'Nho Mẫu Đơn Hàn Quốc', 'price' => 350000, 'category_id' => 8, 'quantity' => 50],
            ['name' => 'Cam Sành Mọng Nước', 'price' => 25000, 'category_id' => 8, 'quantity' => 200],
            ['name' => 'Chuối Tiêu Hồng', 'price' => 15000, 'category_id' => 8, 'quantity' => 150],
            ['name' => 'Dưa Hấu Không Hạt', 'price' => 18000, 'category_id' => 8, 'quantity' => 80],

            // Category 9: Rau Củ
            ['name' => 'Cải Ngọt Hữu Cơ', 'price' => 15000, 'category_id' => 9, 'quantity' => 200],
            ['name' => 'Cà Rốt Đà Lạt', 'price' => 22000, 'category_id' => 9, 'quantity' => 300],
            ['name' => 'Khoai Tây Vàng', 'price' => 25000, 'category_id' => 9, 'quantity' => 150],
            ['name' => 'Rau Muống Nước', 'price' => 10000, 'category_id' => 9, 'quantity' => 500],
            ['name' => 'Bắp Cải Trái Tim', 'price' => 30000, 'category_id' => 9, 'quantity' => 100],

            // Category 10: Thịt Tươi & Hải Sản
            ['name' => 'Thịt Heo Ba Chỉ', 'price' => 120000, 'category_id' => 10, 'quantity' => 100],
            ['name' => 'Thịt Bò Mỹ Nhập Khẩu', 'price' => 280000, 'category_id' => 10, 'quantity' => 80],
            ['name' => 'Cá Hồi Nauy Phi Lê', 'price' => 450000, 'category_id' => 10, 'quantity' => 50],
            ['name' => 'Tôm Sú Sinh Thái', 'price' => 320000, 'category_id' => 10, 'quantity' => 40],
            ['name' => 'Thịt Gà Ta Thả Vườn', 'price' => 140000, 'category_id' => 10, 'quantity' => 60],

            // Category 11: Trứng
            ['name' => 'Trứng Gà Ta', 'price' => 35000, 'category_id' => 11, 'quantity' => 500],
            ['name' => 'Trứng Vịt Lộn', 'price' => 45000, 'category_id' => 11, 'quantity' => 300],
            ['name' => 'Trứng Cút Bóc Vỏ', 'price' => 25000, 'category_id' => 11, 'quantity' => 200],
            ['name' => 'Trứng Vịt Muối', 'price' => 50000, 'category_id' => 11, 'quantity' => 150],
            ['name' => 'Trứng Gà Omega-3', 'price' => 55000, 'category_id' => 11, 'quantity' => 250],

            // Category 12: Sữa tươi
            ['name' => 'Sữa Tươi TH True Milk (Lốc 4)', 'price' => 32000, 'category_id' => 12, 'quantity' => 500],
            ['name' => 'Sữa Chua Vinamilk Có Đường', 'price' => 24000, 'category_id' => 12, 'quantity' => 400],
            ['name' => 'Sữa Hạt Óc Chó Hàn Quốc', 'price' => 250000, 'category_id' => 12, 'quantity' => 100],
            ['name' => 'Sữa Đậu Nành Fami', 'price' => 22000, 'category_id' => 12, 'quantity' => 600],
            ['name' => 'Phô Mai Con Bò Cười', 'price' => 38000, 'category_id' => 12, 'quantity' => 300],

            // Category 13: Thực Phẩm Khô & Gia Vị
            ['name' => 'Nước Mắm Nam Ngư', 'price' => 42000, 'category_id' => 13, 'quantity' => 200],
            ['name' => 'Gạo ST25 Ông Cua (5kg)', 'price' => 180000, 'category_id' => 13, 'quantity' => 150],
            ['name' => 'Đường Tinh Luyện Biên Hòa', 'price' => 22000, 'category_id' => 13, 'quantity' => 300],
            ['name' => 'Mì Hảo Hảo (Thùng 30 Gói)', 'price' => 115000, 'category_id' => 13, 'quantity' => 250],
            ['name' => 'Bột Ngọt Ajinomoto', 'price' => 35000, 'category_id' => 13, 'quantity' => 400],

            // Category 14: Thực Phẩm Đông Lạnh & Chế Biến Sẵn
            ['name' => 'Xúc Xích Vissan', 'price' => 45000, 'category_id' => 14, 'quantity' => 300],
            ['name' => 'Chả Giò Rế Vissan', 'price' => 55000, 'category_id' => 14, 'quantity' => 200],
            ['name' => 'Há Cảo Tôm Thịt', 'price' => 65000, 'category_id' => 14, 'quantity' => 150],
            ['name' => 'Khoai Tây Chiên Cắt Sẵn', 'price' => 85000, 'category_id' => 14, 'quantity' => 100],
            ['name' => 'Pizza Phô Mai', 'price' => 120000, 'category_id' => 14, 'quantity' => 80],
        ];

        foreach ($products as $item) {
            Product::create([
                'name' => $item['name'],
                'price' => $item['price'],
                'category_id' => $item['category_id'],
                'quantity' => $item['quantity'],
                'status' => 1,
                'type' => 'simple',
                'description' => 'Mô tả cho ' . $item['name'],
                // Thêm hình ảnh demo Placeholder
                'image' => 'https://ui-avatars.com/api/?name=' . urlencode($item['name']) . '&background=random&size=300',
            ]);
        }
    }
}
