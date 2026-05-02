# Kế hoạch tích hợp AI Phân tích xu hướng và Gợi ý tăng doanh thu

Tài liệu này trình bày giải pháp tích hợp trí tuệ nhân tạo (AI) và phân tích dữ liệu vào hệ thống bán nông sản Laravel để tối ưu hóa hiệu quả kinh doanh.

---

## 1. Kiến trúc luồng dữ liệu (Data Pipeline)

Để AI có thể đưa ra gợi ý chính xác, hệ thống cần thu thập và xử lý dữ liệu qua 3 giai đoạn:

### Giai đoạn 1: Thu thập dữ liệu (Tracking)
Hệ thống hiện tại mới chỉ lưu đơn hàng. Cần bổ sung các bảng theo dõi hành vi:
- **Product Views:** Lưu lại mỗi lần khách xem sản phẩm (User ID, Product ID, Timestamp, Device).
- **Cart Tracking:** Mở rộng bảng `carts` để lưu thời gian thêm vào giỏ. Ghi nhận sự kiện "Xóa khỏi giỏ".
- **Search Logs:** Lưu các từ khóa khách hàng tìm kiếm nhưng không ra kết quả.
### Giai đoạn 2: Xử lý & Phân tích (Analytics Engine)
Sử dụng các thuật toán để phân loại dữ liệu:
- **Chỉ số chuyển đổi (CR):** $Total Purchases / Total Views$.
- **Tỷ lệ bỏ giỏ (Cart Abandonment Rate):** $Items in Cart / Items Purchased$.
- **Thuật toán Apriori:** Tìm kiếm mối liên quan giữa các sản phẩm (khách hay mua X cùng Y).

### Giai đoạn 3: AI Inference (Gợi ý)
Sử dụng các mô hình (hoặc logic mờ) để đưa ra khuyến nghị cụ thể cho Admin.

---

## 2. Các tính năng AI cụ thể

### 2.1. Phân tích phễu chuyển đổi (Conversion Funnel)
AI sẽ theo dõi hành trình: `Xem sản phẩm` -> `Thêm vào giỏ` -> `Thanh toán`.
- **Gợi ý:** Nếu sản phẩm có lượt xem cực cao nhưng lượt mua thấp, AI sẽ cảnh báo: *"Sản phẩm A đang thu hút sự chú ý nhưng giá cao hơn 15% so với trung bình cùng loại, đề xuất giảm giá hoặc tặng kèm voucher"*.

### 2.2. Dự báo xu hướng & Tồn kho (Trend Prediction)
Dựa vào dữ liệu lịch sử và thời gian (Thứ tự trong tuần, mùa vụ):
- **Phát hiện Trending:** Sản phẩm có tốc độ tăng trưởng lượt xem > 50% trong 3 ngày.
- **Gợi ý nhập hàng:** *"Nhu cầu Cam Sành thường tăng 30% vào cuối tuần, bạn nên chuẩn bị thêm hàng vào Thứ 6"*.
- **Xử lý tồn kho:** *"Thanh Long đã tồn kho quá 15 ngày (hàng dễ hỏng), đề xuất tạo chương trình Flash Sale giảm 20% để xả hàng"*.

### 2.3. Gợi ý Combo & Bán chéo (Cross-selling)
Dựa trên dữ liệu từ bảng `order_products`:
- **AI phát hiện:** Khách chọn "Thịt bò" thường chọn thêm "Gia vị ướp" hoặc "Rau thơm".
- **Gợi ý:** Tự động tạo Combo "Bữa tối tiện lợi" gồm các sản phẩm này với giá ưu đãi hơn 5%.

---

## 3. Giao diện gợi ý cho Admin (Dashboard AI)

Thay vì chỉ xem biểu đồ khô khan, Admin sẽ nhận được các **Thẻ hành động (Action Cards)**:

| Loại gợi ý | Nội dung chi tiết | Hành động đề xuất |
| :--- | :--- | :--- |
| **Giá bán** | Sản phẩm "Gạo ST25" có lượt bỏ giỏ cao (70%). | [Tạo mã giảm giá 10k] |
| **Nhập hàng** | "Sầu riêng Ri6" đang tăng trưởng 200% lượt tìm kiếm. | [Liên hệ nhà cung cấp] |
| **Combo** | 40% khách mua "Sữa tươi" cũng mua "Bánh mì". | [Tạo Combo tiết kiệm] |
| **Xả hàng** | "Rau muống" còn 50 bó, sắp hết hạn tươi ngon. | [Đẩy lên đầu trang chủ] |

---

## 4. Lộ trình triển khai kỹ thuật (Laravel)

1. **Database Update:** Tạo bảng `activity_logs` để lưu vết hành vi khách hàng.
2. **Middleware:** Viết Middleware để tự động ghi lại lượt xem sản phẩm mà không làm chậm trang web.
3. **Command/Job:** Chạy các lệnh Artisan định kỳ (Ví dụ: 1 giờ/lần) để AI tính toán lại các chỉ số và lưu vào bảng `ai_suggestions`.
4. **Admin UI:** Sử dụng Chart.js để hiển thị biểu đồ xu hướng và các thông báo thông minh (Smart Notifications).


