# VongQuay - Ứng dụng Chia Team Liên Quân

Ứng dụng web PHP MVC để tạo phòng, tham gia phòng và chia team cân bằng cho game Liên Quân Mobile.

## 🚀 Tính năng chính

- **Tạo phòng**: Tạo phòng với tên, mật khẩu và số lượng người chơi tối đa
- **Tham gia phòng**: Tham gia phòng bằng mã phòng và mật khẩu
- **Quản lý người chơi**: Thêm, sửa, xóa thông tin người chơi
- **Đánh giá kỹ năng**: Đánh giá 5 kỹ năng (1-10 điểm) + vị trí ưa thích
- **Chia team cân bằng**: Thuật toán chia team dựa trên tổng điểm kỹ năng
- **Giao diện responsive**: Tương thích với mọi thiết bị

## 📁 Cấu trúc dự án

```
VongQuay/
├── config/
│   ├── config.php          # Cấu hình ứng dụng
│   └── database.php        # Kết nối database (Singleton)
├── controllers/
│   ├── HomeController.php  # Controller chính
│   ├── MatchController.php # Controller trận đấu
│   └── RoomController.php  # Controller phòng
├── models/
│   ├── User.php           # Model người dùng
│   ├── Room.php           # Model phòng
│   ├── Match.php          # Model trận đấu
│   └── Player.php         # Model người chơi
├── views/
│   ├── layout.php         # Layout chính
│   ├── home.php           # Trang chủ
│   ├── create_room.php    # Tạo phòng
│   ├── join_room.php      # Tham gia phòng
│   ├── room_detail.php    # Chi tiết phòng
│   ├── add_member.php     # Thêm thành viên
│   ├── edit_player.php    # Sửa thông tin người chơi
│   ├── team_balance.php   # Kết quả chia team
│   └── ...
├── index.php              # Router chính
├── style.css              # CSS styles
├── .htaccess              # Apache configuration
└── README.md              # Tài liệu này
```

## 🛠️ Cài đặt

### Yêu cầu hệ thống
- PHP 7.4+ hoặc PHP 8.0+
- MySQL 5.7+ hoặc MariaDB 10.3+
- Apache với mod_rewrite
- PDO MySQL extension

### Cài đặt database
1. Tạo database MySQL
2. Import file SQL (nếu có)
3. Cập nhật thông tin kết nối trong `config/database.php`

### Cấu hình hosting
1. Upload tất cả files lên hosting
2. Cấu hình domain trỏ về thư mục dự án
3. Đảm bảo `.htaccess` được hỗ trợ

## 🔧 Cấu hình

### Database Connection
Chỉnh sửa `config/database.php`:
```php
private $host = 'your-host';
private $db_name = 'your-database';
private $username = 'your-username';
private $password = 'your-password';
```

### Application Settings
Chỉnh sửa `config/config.php`:
```php
define('APP_URL', 'https://your-domain.com');
```

## 🎮 Cách sử dụng

1. **Tạo phòng**: Nhập thông tin cá nhân và thông tin phòng
2. **Tham gia phòng**: Nhập mã phòng và mật khẩu
3. **Thêm người chơi**: Đánh giá kỹ năng và chọn vị trí
4. **Chia team**: Khi đủ người, hệ thống sẽ chia team cân bằng

## 🏗️ Kiến trúc

### MVC Pattern
- **Model**: Xử lý dữ liệu và business logic
- **View**: Giao diện người dùng
- **Controller**: Điều khiển luồng ứng dụng

### Database Design
- **users**: Thông tin người dùng
- **rooms**: Thông tin phòng
- **matches**: Thông tin trận đấu
- **players**: Thông tin người chơi

## 🔒 Bảo mật

- **SQL Injection**: Sử dụng Prepared Statements
- **XSS Protection**: Input sanitization
- **CSRF Protection**: Token validation
- **Session Security**: Secure session settings
- **File Protection**: .htaccess security rules

## ⚡ Tối ưu hóa

- **Singleton Pattern**: Database connection
- **Caching**: Static file caching
- **Compression**: Gzip compression
- **Error Handling**: Structured logging
- **Performance**: Query optimization

## 🐛 Troubleshooting

### Lỗi kết nối database
- Kiểm tra thông tin kết nối trong `config/database.php`
- Đảm bảo database server đang chạy
- Kiểm tra firewall và port

### Lỗi 404
- Đảm bảo mod_rewrite được bật
- Kiểm tra file `.htaccess`
- Kiểm tra quyền truy cập file

### Lỗi permission
- Đặt quyền 755 cho thư mục
- Đặt quyền 644 cho file
- Kiểm tra owner của file

## 📝 Changelog

### Version 1.0.0
- ✅ Tạo phòng và tham gia phòng
- ✅ Quản lý người chơi
- ✅ Chia team cân bằng
- ✅ Giao diện responsive
- ✅ Tối ưu hóa hiệu suất
- ✅ Bảo mật toàn diện

## 📞 Hỗ trợ

Nếu gặp vấn đề, vui lòng:
1. Kiểm tra log lỗi
2. Xem phần Troubleshooting
3. Liên hệ developer

## 📄 License

Dự án này được phát hành dưới giấy phép MIT.

---

**VongQuay** - Ứng dụng chia team Liên Quân chuyên nghiệp! 🎯
