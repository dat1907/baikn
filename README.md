# Đạt Táo - Hệ thống Điện thoại Di động

## Giới thiệu
Đạt Táo là một trang web bán điện thoại di động trực tuyến với đầy đủ các tính năng như đăng ký, đăng nhập, quản lý tài khoản và quản trị viên.

## Tính năng chính

### Dành cho người dùng
- Đăng ký tài khoản mới
- Đăng nhập/Đăng xuất
- Xem và cập nhật thông tin cá nhân
- Đổi mật khẩu
- Xem lịch sử đơn hàng (sẽ phát triển)
- Xem danh sách sản phẩm

### Dành cho quản trị viên
- Quản lý người dùng
- Quản lý sản phẩm (sẽ phát triển)
- Quản lý đơn hàng (sẽ phát triển)
- Thống kê doanh thu (sẽ phát triển)

## Yêu cầu hệ thống
- PHP 7.4 hoặc cao hơn
- MySQL 5.7 hoặc cao hơn
- Web server (Apache/Nginx)
- Trình duyệt web hiện đại (Chrome, Firefox, Safari, Edge)

## Cài đặt

1. **Cài đặt cơ sở dữ liệu**
   - Tạo một cơ sở dữ liệu MySQL mới với tên `techstore_db` 
   - Import file SQL (nếu có) hoặc chạy script tạo bảng tự động

2. **Cấu hình kết nối cơ sở dữ liệu**
   - Mở file `config/database.php`
   - Cập nhật thông tin kết nối phù hợp với cấu hình máy chủ của bạn:
     ```php
     $host = 'localhost';      // Địa chỉ máy chủ MySQL
     $dbname = 'techstore_db'; // Tên cơ sở dữ liệu
     $username = 'root';       // Tên đăng nhập MySQL
     $password = '';           // Mật khẩu MySQL
     ```

3. **Thiết lập ban đầu**
   - Truy cập `setup_database.php` trên trình duyệt để tạo bảng và tài khoản admin mặc định
   - Đăng nhập với tài khoản admin mặc định:
     - Tên đăng nhập: `admin`
     - Mật khẩu: `admin123`

4. **Cấu hình web server**
   - Đảm bảo thư mục gốc của web server trỏ đến thư mục `public`
   - Cấu hình URL rewrite nếu cần thiết

## Cấu trúc thư mục
```
knthu2/
├── admin/              # Khu vực quản trị
├── config/             # Cấu hình ứng dụng
│   └── database.php    # Cấu hình kết nối CSDL
├── css/                # File CSS
│   ├── style.css       # CSS chính
│   └── admin.css       # CSS cho trang quản trị
├── js/                 # File JavaScript
│   └── script.js       # JavaScript chính
├── auth.php            # Xử lý đăng nhập/đăng ký
├── index.php           # Trang chủ
├── logout.php          # Xử lý đăng xuất
├── profile.php         # Trang hồ sơ người dùng
├── setup_database.php  # Script thiết lập cơ sở dữ liệu
└── README.md           # Tài liệu hướng dẫn
```

## Bảo mật
- Mật khẩu được mã hóa bằng thuật toán bcrypt
- Bảo vệ chống tấn công SQL Injection bằng PDO Prepared Statements
- Bảo vệ chống tấn công XSS bằng hàm `htmlspecialchars()`
- Sử dụng session để quản lý đăng nhập

## Phát triển trong tương lai
- Thêm chức năng giỏ hàng và thanh toán
- Tích hợp thanh toán trực tuyến
- Thêm đánh giá sản phẩm
- Tích tính năng tìm kiếm và lọc sản phẩm
- Thêm chức năng quên mật khẩu
- Gửi email xác nhận đăng ký

## Tác giả
[Your Name]

## Giấy phép
Dự án này được phát hành theo giấy phép MIT.
