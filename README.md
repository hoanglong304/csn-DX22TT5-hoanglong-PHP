# csn-DX22TT5-hoanglong-PHP  
# 🛍️ THỰC TẬP ĐỒ ÁN CHUYÊN NGÀNH - WEBSITE MUA BÁN GIÀY DÉP

## 👩‍💻 Sinh viên thực hiện
- **Họ và tên:** Hoàng Long  
- **Mã lớp:** DX22TT5  
- **Email:** longhoang.30042000@gmail.com  
- **Số điện thoại:** 0385362302

## 👨‍🏫 Giảng viên hướng dẫn
- **Họ tên:** GV. Nguyễn Nhứt Lam  
- **Email:** lamnn@tvu.edu.vn

---

## 📌 Mô tả đề tài

Đề tài nhằm xây dựng một **website bán giày** sử dụng **ngôn ngữ PHP** với **MySQL** làm hệ quản trị cơ sở dữ liệu, hỗ trợ:
- Hiển thị danh sách sản phẩm giày
- Tìm kiếm, lọc sản phẩm theo loại, giá
- Xem chi tiết sản phẩm
- Chức năng giỏ hàng và đặt hàng trực tuyến
- Quản lý sản phẩm, đơn hàng, thống kê doanh thu & kho dành cho Admin

---

## ⚙️ Công nghệ sử dụng

| Công nghệ | Mô tả |
|----------|-------|
| PHP 8 | Ngôn ngữ lập trình chính |
| MySQL | Cơ sở dữ liệu lưu trữ sản phẩm và đơn hàng |
| Apache / XAMPP | Webserver cục bộ |
| HTML, CSS, JavaScript | Giao diện người dùng |
| Bootstrap 5 | Framework giao diện front-end |
| GitHub | Quản lý mã nguồn & tiến độ |

---

## 🗂️ Tiến độ thực hiện

| Tuần   | Nội dung đã làm | Link báo cáo |
|--------|------------------|--------------|
| Tuần 1 | Tìm hiểu ngôn ngữ PHP, MySQL và công cụ webserver cục bộ như XAMPP để phát triển website trên localhost.<br>Khảo sát yêu cầu người dùng, mô tả bài toán về hệ thống bán giày trực tuyến.<br>Phân tích hệ thống và đề xuất mô hình ở mức khái niệm và xử lý: Use Case, ERD.<br>Xác định các chức năng cơ bản: quản lý sản phẩm, giỏ hàng, đặt hàng/thanh toán, thống kê.<br>Thiết kế giao diện bằng HTML, CSS, JavaScript (kết hợp Bootstrap).<br>Cấu hình môi trường lập trình PHP + MySQL, khởi tạo cấu trúc thư mục dự án. | [Xem tại đây](progress-report/week01.txt) |
| Tuần 2 | Xây dựng **chức năng quản lý sản phẩm (CRUD)** dành cho admin:<br>➤ Giao diện danh sách sản phẩm: hiển thị dữ liệu từ database với các thao tác Sửa/Xóa.<br>➤ Thêm mới sản phẩm: form nhập dữ liệu (tên, giá, ảnh, mô tả...).<br>➤ Cập nhật và xoá sản phẩm theo ID.<br>➤ Xử lý upload hình ảnh và lưu trữ đường dẫn vào CSDL.<br>➤ Bổ sung xác thực quyền admin (role) khi truy cập trang quản trị.<br>➤ Cài đặt và tích hợp thư viện **SweetAlert2** để hiển thị thông báo thao tác CRUD.<br>➤ Tối ưu giao diện với Bootstrap 5, phân trang danh sách sản phẩm (nếu cần).<br>➤ Hiển thị danh sách sản phẩm ở trang chính (index) theo lưới.<br>➤ Thêm nút “Thêm vào giỏ hàng” và “Mua ngay” bằng Ajax/post form. | [Xem tại đây](progress-report/week02.txt) |
| Tuần 3 | Hoàn thiện các chức năng **giỏ hàng, đặt hàng, tìm kiếm và phân quyền người dùng**:<br>➤ Cập nhật giao diện giỏ hàng: hiển thị danh sách sản phẩm đã thêm, sửa số lượng, xoá từng sản phẩm.<br>➤ Cập nhật dữ liệu giỏ hàng lưu trong **CSDL (bảng order_items)** gắn với `user_id`, không dùng session.<br>➤ Thiết kế và xử lý **đặt hàng**: khi người dùng nhấn "Đặt hàng", tạo bản ghi trong bảng `orders` và `order_items`, ghi thời gian và tổng tiền.<br>➤ Thêm trang thông báo **“Đặt hàng thành công”**, liên kết trở về trang chủ hoặc xem đơn hàng.<br>➤ Tạo trang `orders.php` để hiển thị danh sách đơn hàng theo `user_id`, hiển thị ngày đặt và các sản phẩm kèm số lượng.<br>➤ Xây dựng chức năng **phân quyền người dùng**: đăng nhập phân biệt user thông thường và admin qua trường `role` trong bảng `users`.<br>➤ Hoàn thiện **tìm kiếm sản phẩm** bằng từ khóa theo tên sản phẩm (`LIKE '%keyword%'`).<br>➤ Tăng trải nghiệm người dùng: cập nhật **icon số lượng giỏ hàng** trên header bằng PHP.<br>➤ Xử lý lỗi liên quan đến bảng `order_items` chưa tồn tại, thêm bảng và tạo các ràng buộc liên quan. | [Xem tại đây](progress-report/week03.txt) |
| Tuần 4 | Tiến hành **kiểm thử toàn bộ hệ thống** và **hoàn thiện đồ án**:<br>➤ Kiểm thử chức năng **mua hàng, đặt hàng**: đảm bảo quy trình đặt hàng đầy đủ từ thêm sản phẩm vào giỏ → thanh toán → lưu đơn hàng và hiển thị lịch sử mua hàng.<br>➤ Kiểm thử **giao diện web** trên nhiều thiết bị (PC, tablet, mobile), khắc phục lỗi vỡ layout, kiểm tra tính thân thiện người dùng.<br>➤ Rà soát và kiểm thử chức năng **quản lý sản phẩm**: thêm, sửa, xoá; đảm bảo dữ liệu hiển thị đúng, xử lý ảnh lỗi, tên trùng.<br>➤ Sửa lỗi liên quan đến **cập nhật giỏ hàng**, **lưu đơn hàng**, **trạng thái hiển thị sản phẩm** và lỗi không đăng nhập vẫn truy cập được một số trang.<br>➤ Tối ưu mã nguồn: loại bỏ đoạn mã dư thừa, chuẩn hóa code PHP/MySQL, tổ chức lại thư mục dễ bảo trì.<br>➤ Hoàn thiện các file đồ án: README, hướng dẫn sử dụng, chuẩn bị video demo và báo cáo thuyết trình.<br>➤ Đảm bảo hệ thống phân quyền hoạt động đúng: admin quản lý sản phẩm và đơn hàng, user chỉ được thao tác giỏ hàng và xem lịch sử mua hàng. | [Xem tại đây](progress-report/week04.txt) |

## 🧪 Hướng dẫn cài đặt và chạy thử

### 1. Yêu cầu hệ thống
- XAMPP
- PHP >= 8.0
- MySQL >= 8.0
- Trình duyệt web (Chrome, Firefox...)

### 2. Cài đặt project
```bash
git clone https://github.com/hoanglong304/csn-DX22TT5-hoanglong-PHP.git
cd csn-DX22TT5-hoanglong-PHP
