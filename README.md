# Vòng Quay Ngẫu Nhiên - PHP MVC

Hệ thống chia team cân bằng và công bằng cho game, được xây dựng bằng PHP MVC.

## Tính năng chính

### 🔐 Quản lý người dùng
- Đăng ký/Đăng nhập
- Thông tin cá nhân đầy đủ
- Cấp độ và thống kê

### 🏠 Quản lý phòng
- Tạo phòng với tên và mật khẩu
- Mã phòng tự động
- Tham gia phòng bằng mã + mật khẩu
- Quản lý người tham gia

### 🎮 Quản lý Match
- Tạo match trong phòng
- Thêm player với đánh giá kỹ năng
- Chia team cân bằng thông minh
- Vòng quay ngẫu nhiên

## Cấu trúc thư mục

```
VongQuay/
├── config/
│   └── database.php          # Cấu hình kết nối database
├── models/
│   ├── User.php             # Model quản lý người dùng
│   ├── Room.php             # Model quản lý phòng
│   ├── Match.php            # Model quản lý match
│   └── Player.php           # Model quản lý player
├── controllers/
│   ├── HomeController.php   # Controller trang chủ & auth
│   ├── RoomController.php   # Controller quản lý phòng
│   └── MatchController.php  # Controller quản lý match
├── views/
│   ├── layout.php           # Layout chính
│   ├── home.php             # Trang chủ
│   ├── login.php            # Đăng nhập
│   ├── register.php         # Đăng ký
│   ├── rooms.php            # Danh sách phòng
│   ├── create_room.php      # Tạo phòng
│   └── join_room.php        # Tham gia phòng
├── index.php                # File chính điều hướng
├── style.css                # CSS styles
└── vongquay_db.sql          # Database schema
```

## Cài đặt

### 1. Yêu cầu hệ thống
- XAMPP (Apache + MySQL + PHP 7.4+)
- MySQL 5.7+ hoặc MariaDB 10.3+

### 2. Cài đặt database
1. Khởi động XAMPP
2. Truy cập phpMyAdmin (http://localhost/phpmyadmin)
3. Tạo database mới tên `vongquay_db`
4. Import file `vongquay_db.sql`

### 3. Cấu hình
Chỉnh sửa file `config/database.php` nếu cần:
```php
private $host = 'localhost';
private $db_name = 'vongquay_db';
private $username = 'root';
private $password = '';
```

### 4. Chạy ứng dụng
1. Copy thư mục project vào `htdocs`
2. Truy cập: `http://localhost/VongQuay`

## Sử dụng

### Đăng ký/Đăng nhập
1. Truy cập trang web
2. Đăng ký tài khoản mới hoặc đăng nhập
3. Điền thông tin cá nhân

### Tạo phòng
1. Vào "Phòng" → "Tạo phòng mới"
2. Nhập tên phòng và mật khẩu
3. Chọn số người chơi tối đa
4. Hệ thống tạo mã phòng tự động

### Tham gia phòng
1. Vào "Phòng" → "Tham gia phòng"
2. Nhập mã phòng và mật khẩu
3. Nhấn "Tham gia phòng"

### Tạo Match
1. Vào phòng đã tham gia
2. Tạo match mới
3. Thêm players với đánh giá kỹ năng
4. Chia team cân bằng

## Database Schema

### Bảng `users`
- Thông tin người dùng
- Email, phone, level, thống kê

### Bảng `rooms`
- Thông tin phòng
- Tên phòng, mã phòng, mật khẩu
- Số người tối đa, trạng thái

### Bảng `room_participants`
- Người tham gia phòng
- Liên kết user và room
- Phân quyền chủ phòng

### Bảng `matches`
- Thông tin match
- Liên kết với phòng
- Người tạo match

### Bảng `players`
- Thông tin player trong match
- Đánh giá kỹ năng (1-5)
- Tổng điểm, vị trí

## API Routes

| Route | Method | Mô tả |
|-------|--------|-------|
| `/` | GET | Trang chủ |
| `?action=login` | GET/POST | Đăng nhập |
| `?action=register` | GET/POST | Đăng ký |
| `?action=rooms` | GET | Danh sách phòng |
| `?action=create_room` | GET/POST | Tạo phòng |
| `?action=join_room` | GET/POST | Tham gia phòng |
| `?action=room&id=X` | GET | Chi tiết phòng |
| `?action=matches` | GET | Danh sách match |
| `?action=create_match` | GET/POST | Tạo match |
| `?action=match&id=X` | GET | Chi tiết match |

## Tính năng nâng cao

### Chia team cân bằng
- Phân tích điểm số từng player
- Thuật toán chia team tối ưu
- Hiển thị độ cân bằng

### Bảo mật
- Mật khẩu phòng
- Session management
- Input validation

### Responsive Design
- Tương thích mobile
- UI/UX hiện đại
- Animation mượt mà

## Troubleshooting

### Lỗi kết nối database
- Kiểm tra XAMPP đã khởi động
- Kiểm tra cấu hình trong `config/database.php`
- Kiểm tra database `vongquay_db` đã tồn tại

### Lỗi 404
- Kiểm tra file `index.php` có tồn tại
- Kiểm tra cấu hình Apache
- Kiểm tra quyền truy cập thư mục

### Lỗi session
- Kiểm tra session_start() được gọi
- Kiểm tra quyền ghi session
- Xóa cache browser

## Đóng góp

1. Fork project
2. Tạo feature branch
3. Commit changes
4. Push to branch
5. Tạo Pull Request

## License

MIT License - Xem file LICENSE để biết thêm chi tiết.