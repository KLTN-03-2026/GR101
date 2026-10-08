# AgriAI - Website thương mại điện tử nông sản tích hợp AI

AgriAI là website bán nông sản xây dựng bằng Laravel 9, có khu vực khách hàng, khu vực quản trị, thanh toán VNPay, quản lý sản phẩm, đơn hàng, đánh giá, liên hệ, phí vận chuyển và các chức năng AI hỗ trợ mua sắm/phân tích kinh doanh.

## Tính năng chính

- Trang bán hàng: danh mục, tìm kiếm, chi tiết sản phẩm, giỏ hàng, đặt hàng và theo dõi đơn hàng.
- Quản trị: dashboard doanh thu, sản phẩm, danh mục, thuộc tính, khách hàng, đơn hàng, mã khuyến mãi, phí vận chuyển, đánh giá và liên hệ.
- Thanh toán: hỗ trợ VNPay và thanh toán khi nhận hàng.
- AI gợi ý mua sắm: nhập tên món ăn để AI đề xuất công thức, nguyên liệu và sản phẩm phù hợp trong database.
- AI phân tích chiến lược: admin có thể yêu cầu AI phân tích dữ liệu bán hàng và gợi ý hành động kinh doanh.
- Hệ thống đánh giá sản phẩm, phản hồi đánh giá và form liên hệ.

## Yêu cầu môi trường

- PHP 8.0 trở lên
- Composer
- MySQL hoặc MariaDB
- Node.js và npm
- OpenSSL PHP extension
- PDO MySQL PHP extension

## Cài đặt

1. Clone hoặc giải nén dự án:

```bash
git clone <repository-url>
cd nongsanai
```

2. Cài đặt thư viện PHP:

```bash
composer install
```

3. Cài đặt thư viện frontend:

```bash
npm install
```

4. Tạo file môi trường:

```bash
cp .env.example .env
```

Trên Windows PowerShell có thể dùng:

```powershell
Copy-Item .env.example .env
```

5. Tạo application key:

```bash
php artisan key:generate
```

6. Cấu hình database trong `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nongsanai
DB_USERNAME=root
DB_PASSWORD=
```

7. Cấu hình OpenAI trong `.env`:

```env
OPENAI_API_KEY=sk-proj-...
OPENAI_MODEL=gpt-4.1-mini
AI_PROVIDER=openai
```

Không commit `.env` hoặc API key lên Git.

8. Chạy migration và seed dữ liệu:

```bash
php artisan migrate --seed
```

Nếu bạn dùng file SQL có sẵn, có thể import `nongsan.sql` vào MySQL trước hoặc thay cho bước seed tùy dữ liệu local.

9. Tạo storage link:

```bash
php artisan storage:link
```

10. Build asset:

```bash
npm run dev
```

## Chạy dự án

Khởi động server Laravel:

```bash
php artisan serve
```

Truy cập website:

```text
http://127.0.0.1:8000
```

Trang quản trị:

```text
http://127.0.0.1:8000/admin/login
```

Tài khoản admin mẫu tùy theo dữ liệu seed/import. Nếu dùng dữ liệu demo của dự án, kiểm tra `database/seeders/AdminSeeder.php` hoặc bảng `admins`.

## Sử dụng chức năng AI

### AI gợi ý mua sắm

1. Vào website khách hàng.
2. Chọn mục `AI Gợi ý mua sắm`.
3. Nhập tên món ăn, ví dụ: `mì quảng`, `canh chua cá lóc`, `cơm chiên trứng`.
4. Hệ thống trả về công thức, nguyên liệu và danh sách sản phẩm phù hợp để thêm vào giỏ hàng.

### AI phân tích chiến lược

1. Đăng nhập trang admin.
2. Mở menu `AI Dashboard`.
3. Nhấn `Bắt đầu phân tích ngay`.
4. AI sẽ phân tích doanh thu, sản phẩm, đơn hàng và đưa ra gợi ý kinh doanh.

## Cấu trúc quan trọng

- `app/Services/OpenAIService.php`: tích hợp OpenAI và xử lý gợi ý món ăn.
- `app/Services/AIAnalyticsService.php`: phân tích dữ liệu kinh doanh bằng AI.
- `app/Services/AnalyticsService.php`: tổng hợp số liệu bán hàng.
- `app/Http/Controllers/Web/HomeController.php`: xử lý trang chủ, tìm kiếm và AI gợi ý mua sắm.
- `app/Http/Controllers/Admin/AIDashboardController.php`: xử lý AI Dashboard admin.
- `resources/views/web/ai/recommend.blade.php`: giao diện AI gợi ý mua sắm.
- `resources/views/admin/ai_dashboard/index.blade.php`: giao diện AI Dashboard.
- `resources/views/layouts/master_user.blade.php`: layout chính phía khách hàng.
- `routes/web.php`: route phía khách hàng.
- `routes/admin.php`: route phía quản trị.

## Lệnh hữu ích

Xóa cache cấu hình/view/route:

```bash
php artisan optimize:clear
```

Chạy lại autoload sau khi thêm class mới:

```bash
composer dump-autoload
```

Build asset production:

```bash
npm run prod
```

## Ghi chú bảo mật

- Không chia sẻ `OPENAI_API_KEY`, thông tin VNPay hoặc mật khẩu database.
- Sau khi lộ API key, cần vào trang quản lý OpenAI để revoke key cũ và tạo key mới.
- Khi deploy production, đặt `APP_DEBUG=false`.
