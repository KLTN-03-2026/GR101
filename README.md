# 🍏 Dự án Nông Sản Xanh - Tích hợp Trí tuệ Nhân tạo (AI)

Chào mừng bạn đến với dự án **Nông Sản Xanh**, một nền tảng thương mại điện tử hiện đại dành cho nông sản, được tích hợp các tính năng AI tiên tiến nhất để tối ưu hóa trải nghiệm mua sắm và quản lý kinh doanh.

## 🚀 Tính năng nổi bật

### 🤖 Trí tuệ Nhân tạo (AI)
- **Gợi ý mua sắm thông minh (PB13):** AI phân tích món ăn bạn muốn nấu, cung cấp công thức chi tiết và tự động khớp các nguyên liệu hiện có trong cửa hàng. Hỗ trợ nút "Thêm tất cả vào giỏ" cực kỳ tiện lợi.
- **Phân tích chiến lược kinh doanh (PB21):** Admin có thể yêu cầu AI phân tích dữ liệu bán hàng thực tế để đưa ra nhận định, cảnh báo rủi ro và đề xuất các chiến dịch khuyến mãi (Coupon/Flash Sale) hiệu quả.

### 🛒 Thương mại điện tử
- **Thanh toán trực tuyến:** Tích hợp cổng thanh toán **VNPay** an toàn và nhanh chóng.
- **Quản lý đơn hàng:** Quy trình xử lý đơn hàng chuyên nghiệp từ lúc đặt hàng đến khi hoàn tất.
- **Thống kê chuyên sâu (PB20):** Biểu đồ doanh thu trực quan, thống kê sản phẩm bán chạy và khách hàng tiềm năng bằng ECharts.
- **Quản lý linh hoạt:** Hệ thống quản lý sản phẩm, danh mục, thuộc tính, khuyến mãi và phí vận chuyển đầy đủ.

---

## 🛠 Hướng dẫn cài đặt

### 1. Yêu cầu hệ thống
- **PHP:** >= 8.1
- **Composer:** Phiên bản mới nhất
- **Node.js & NPM:** Để biên dịch asset
- **MySQL/MariaDB:** Cơ sở dữ liệu

### 2. Các bước cài đặt

1. **Clone hoặc giải nén dự án:**
   ```bash
   git clone <url-repository>
   cd nongsanai
   ```

2. **Cài đặt các gói phụ thuộc (Dependencies):**
   ```bash
   composer install
   npm install
   ```

3. **Cấu hình môi trường (.env):**
   - Sao chép file `.env.example` thành `.env`:
     ```bash
     cp .env.example .env
     ```
   - Cấu hình các thông số Database:
     ```env
     DB_DATABASE=nongsan
     DB_USERNAME=root
     DB_PASSWORD=
     ```
   - Cấu hình **Gemini AI Key** (Bắt buộc để chạy tính năng AI):
     ```env
     GEMINI_API_KEY=AIzaSy... (Key của bạn)
     OPENAI_MODEL=gemini-1.5-flash
     ```

4. **Tạo mã định danh ứng dụng:**
   ```bash
   php artisan key:generate
   ```

5. **Di cư dữ liệu (Migration & Seeding):**
   ```bash
   php artisan migrate --seed
   ```

6. **Tạo link lưu trữ:**
   ```bash
   php artisan storage:link
   ```

7. **Biên dịch giao diện:**
   ```bash
   npm run dev
   ```

---

## 💻 Cách sử dụng

### Chạy ứng dụng
Khởi động server Laravel:
```bash
php artisan serve
```
Truy cập trang web tại: `http://127.0.0.1:8000`

### Thông tin đăng nhập mặc định (Môi trường Local)
- **Trang Admin:** `http://127.0.0.1:8000/admin/login`
  - Email: `admin1@gmail.com`
  - Mật khẩu: `123`

### Sử dụng AI
- **Dành cho Người dùng:** Nhấn vào nút **"AI GỢI Ý MUA SẮM"** trên thanh Menu để bắt đầu tìm kiếm món ăn.
- **Dành cho Admin:** Truy cập menu **"AI Dashboard"** trong trang quản trị để xem phân tích chiến lược.

---

## 📂 Cấu trúc thư mục quan trọng
- `app/Services/AIAnalyticsService.php`: Xử lý logic phân tích AI cho Admin.
- `app/Services/OpenAIService.php`: Lớp tích hợp chính với Gemini API.
- `app/Http/Controllers/Web/HomeController.php`: Chứa logic gợi ý mua sắm cho người dùng.
- `resources/views/layouts/master_user.blade.php`: Giao diện chính và Modal AI tích hợp.

---
**Dự án được phát triển bởi Nhóm GR101 - 2026**
