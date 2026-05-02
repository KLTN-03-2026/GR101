# Hướng dẫn Sử dụng Hệ thống AI Phân tích Doanh thu

## 🎯 Tổng quan

Hệ thống AI đã được tích hợp hoàn chỉnh vào dự án Laravel của bạn với các tính năng:

✅ **Thu thập dữ liệu tự động**
- Theo dõi lượt xem sản phẩm
- Ghi nhận thêm/xóa giỏ hàng  
- Lưu lịch sử tìm kiếm
- Tracking đơn hàng

✅ **Phân tích thông minh**
- Tỷ lệ chuyển đổi (Conversion Rate)
- Tỷ lệ bỏ giỏ (Cart Abandonment)
- Phát hiện sản phẩm Trending
- Phân tích Cross-sell

✅ **Gợi ý AI**
- Gợi ý giá bán
- Gợi ý tồn kho
- Gợi ý sản phẩm HOT
- Gợi ý Combo

---

## 📋 Các bước đã hoàn thành

### 1. Database Schema
- ✅ Bảng `activity_logs` - Lưu hành vi khách hàng
- ✅ Bảng `ai_suggestions` - Lưu gợi ý AI
- ✅ Thêm timestamps vào bảng `carts`

### 2. Data Collection
- ✅ `ActivityTracker` service - Thu thập dữ liệu
- ✅ `TrackProductView` middleware - Tracking lượt xem
- ✅ Tracking thêm/xóa giỏ hàng trong `CartController`
- ✅ Tracking tìm kiếm trong `HomeController`
- ✅ Tracking mua hàng trong `OrderController`

### 3. Analytics Engine
- ✅ `AnalyticsService` - 5+ thuật toán phân tích
- ✅ Conversion rate analysis
- ✅ Cart abandonment detection
- ✅ Trending detection
- ✅ Cross-sell analysis

### 4. AI Suggestion System
- ✅ `AISuggestionService` - Tạo gợi ý tự động
- ✅ 4 loại gợi ý (pricing, inventory, trending, combo)
- ✅ Command `ai:generate-suggestions`
- ✅ Scheduled task chạy hàng ngày lúc 2h sáng

### 5. Admin Dashboard
- ✅ `AIDashboardController` - Controller quản lý
- ✅ View `/admin/ai-dashboard` - Giao diện đẹp với Action Cards
- ✅ Tính năng ẩn gợi ý đã xử lý
- ✅ Routes đầy đủ

---

## 🚀 Cách sử dụng

### Bước 1: Tạo gợi ý AI lần đầu

```bash
php artisan ai:generate-suggestions
```

### Bước 2: Truy cập Dashboard

Mở trình duyệt và vào:
```
http://127.0.0.1:8000/admin/ai-dashboard
```

### Bước 3: Xem và xử lý gợi ý

Dashboard hiển thị 4 loại thẻ gợi ý:

#### 🟡 Gợi ý Giá bán
- Sản phẩm có tỷ lệ bỏ giỏ cao
- Giá cao hơn trung bình
- **Hành động:** Giảm giá hoặc tạo voucher

#### 🔴 Gợi ý Tồn kho  
- Hàng tồn lâu (>60 ngày)
- Bán chậm
- **Hành động:** Flash Sale 15-20%

#### 🟢 Sản phẩm HOT
- Tăng trưởng >50% lượt xem
- Đang trending
- **Hành động:** Đẩy lên trang chủ

#### 🔵 Gợi ý Combo
- Sản phẩm hay mua cùng nhau
- **Hành động:** Tạo combo ưu đãi

---

## ⚙️ Cấu hình nâng cao

### Chạy tự động hàng ngày

Hệ thống đã được cấu hình chạy tự động lúc 2h sáng mỗi ngày trong `app/Console/Kernel.php`:

```php
$schedule->command('ai:generate-suggestions')->dailyAt('02:00');
```

Để kích hoạt, bạn cần thêm cron job:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Điều chỉnh độ nhạy

Trong `app/Services/AnalyticsService.php`:

```php
// Thay đổi ngưỡng trending (mặc định 50%)
getTrendingProducts(50) // → 30 để nhạy hơn

// Thay đổi ngày tồn kho (mặc định 60 ngày)
getSlowMovingProducts(60) // → 45 để cảnh báo sớm hơn
```

---

## 📊 Dữ liệu mẫu

Để AI hoạt động tốt, cần có dữ liệu:

1. **Ít nhất 30 ngày** hoạt động
2. **Khách hàng đăng nhập** để tracking chính xác
3. **Nhiều đơn hàng** để phân tích cross-sell

Nếu chưa có dữ liệu thực, bạn có thể:
- Chạy lệnh tạo gợi ý để xem cấu trúc
- Thêm dữ liệu test vào `activity_logs`

---

## 🔍 Kiểm tra hoạt động

### 1. Kiểm tra tracking

Xem sản phẩm trên website, sau đó check database:

```sql
SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 10;
```

### 2. Kiểm tra gợi ý

```sql
SELECT * FROM ai_suggestions WHERE is_active = 1;
```

### 3. Test search tracking

Tìm kiếm trên website, check:

```sql
SELECT * FROM activity_logs WHERE activity_type = 'search';
```

---

## 📁 Các file quan trọng

| File | Mô tả |
|------|-------|
| `app/Services/ActivityTracker.php` | Thu thập dữ liệu |
| `app/Services/AnalyticsService.php` | Phân tích dữ liệu |
| `app/Services/AISuggestionService.php` | Tạo gợi ý AI |
| `app/Http/Controllers/Admin/AIDashboardController.php` | Controller dashboard |
| `resources/views/admin/ai_dashboard/index.blade.php` | Giao diện dashboard |
| `app/Console/Commands/GenerateAISuggestions.php` | Lệnh tạo gợi ý |
| `routes/admin.php` | Routes AI dashboard |

---

## ✅ Hoàn thành

Hệ thống AI đã sẵn sàng sử dụng! Bạn có thể:

1. ✅ Chạy `php artisan ai:generate-suggestions`
2. ✅ Truy cập `/admin/ai-dashboard`
3. ✅ Xem và xử lý gợi ý
4. ✅ Hệ thống tự động chạy hàng ngày

**Lưu ý:** Cần có dữ liệu thực tế để AI phân tích chính xác. Khuyến khích chạy trong môi trường production sau 1-2 tuần để có insights tốt nhất.
