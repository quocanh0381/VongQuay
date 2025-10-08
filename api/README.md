# Vòng Quay API

API backend cho ứng dụng Vòng Quay sử dụng PHP và MySQL.

## 📁 Cấu trúc thư mục

```
api/
├── index.php              # Entry point chính
├── .htaccess              # URL rewriting
├── config/
│   ├── database.php       # Cấu hình database
│   └── Database.php       # Database class
├── controllers/
│   ├── UserController.php # Xử lý User API
│   ├── RoomController.php # Xử lý Room API
│   ├── MatchController.php# Xử lý Match API
│   ├── PlayerController.php# Xử lý Player API
│   └── TeamController.php # Xử lý Team API
└── README.md              # Tài liệu này
```

## 🚀 Cài đặt

### 1. Cấu hình Database
```sql
-- Import file SQL
mysql -u root -p vongquay_db < ../VongQuay/vongquay_db.sql
```

### 2. Cấu hình PHP
```php
// Trong config/database.php
private static $config = [
    'host' => '127.0.0.1',
    'port' => 3306,
    'username' => 'root',
    'password' => '',
    'database' => 'vongquay_db',
    'charset' => 'utf8mb4'
];
```

### 3. Cấu hình Apache
```apache
# Trong .htaccess
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

## 📡 API Endpoints

### Health Check
```
GET /api/health
```

### Users
```
GET    /api/users              # Lấy danh sách users
GET    /api/users/:id          # Lấy user theo ID
POST   /api/users              # Tạo user mới
PUT    /api/users/:id          # Cập nhật user
DELETE /api/users/:id          # Xóa user
```

### Rooms
```
GET    /api/rooms              # Lấy danh sách rooms
GET    /api/rooms/:id          # Lấy room theo ID
GET    /api/rooms/code/:code   # Lấy room theo mã
POST   /api/rooms              # Tạo room mới
PUT    /api/rooms/:id          # Cập nhật room
DELETE /api/rooms/:id          # Xóa room
POST   /api/rooms/join         # Tham gia room
```

### Matches
```
GET    /api/matches            # Lấy danh sách matches
GET    /api/matches/:id        # Lấy match theo ID
POST   /api/matches            # Tạo match mới
PUT    /api/matches/:id        # Cập nhật match
DELETE /api/matches/:id        # Xóa match
```

### Players
```
GET    /api/players            # Lấy danh sách players
GET    /api/players/:id        # Lấy player theo ID
POST   /api/players            # Tạo player mới
PUT    /api/players/:id        # Cập nhật player
DELETE /api/players/:id        # Xóa player
```

### Teams
```
POST   /api/teams/skill/:matchId    # Tạo team theo kỹ năng
POST   /api/teams/random/:matchId   # Tạo team ngẫu nhiên
GET    /api/teams/:matchId          # Lấy teams của match
```

## 🔧 Sử dụng API

### 1. Health Check
```javascript
fetch('http://localhost/vongquay/api/health')
  .then(response => response.json())
  .then(data => console.log(data));
```

### 2. Tạo Room
```javascript
fetch('http://localhost/vongquay/api/rooms', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    room_name: 'Phòng Test',
    password: '123456',
    created_by: 1,
    max_players: 10
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### 3. Tham gia Room
```javascript
fetch('http://localhost/vongquay/api/rooms/join', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    room_code: 'ABC123',
    password: '123456',
    user_id: 2
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### 4. Tạo Match
```javascript
fetch('http://localhost/vongquay/api/matches', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    match_name: 'Trận Test',
    room_id: 1,
    created_by: 1
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### 5. Thêm Player
```javascript
fetch('http://localhost/vongquay/api/players', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    match_id: 1,
    name: 'Người chơi 1',
    personalSkill: 4,
    mapReading: 3,
    teamwork: 5,
    reaction: 4,
    experience: 4,
    position: 1,
    created_by: 1
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### 6. Tạo Team theo Kỹ năng
```javascript
fetch('http://localhost/vongquay/api/teams/skill/1', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

### 7. Tạo Team Ngẫu nhiên
```javascript
fetch('http://localhost/vongquay/api/teams/random/1', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

## 📊 Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    "id": 1,
    "name": "Example"
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "error": "Detailed error information"
}
```

## 🔍 Query Parameters

### Pagination
```
?limit=10&offset=0
```

### Filtering
```
?is_active=1
?created_by=1
?room_id=1
```

### Sorting
```
?order_by=created_at&order_dir=DESC
?order_by=totalScore&order_dir=ASC
```

## 🛠️ Troubleshooting

### Lỗi kết nối database
1. Kiểm tra cấu hình trong `config/database.php`
2. Đảm bảo MySQL service đang chạy
3. Kiểm tra database `vongquay_db` đã tồn tại

### Lỗi 404
1. Kiểm tra `.htaccess` có hoạt động không
2. Đảm bảo mod_rewrite được enable
3. Kiểm tra URL có đúng format không

### Lỗi CORS
1. Kiểm tra headers trong `.htaccess`
2. Đảm bảo frontend gọi đúng domain
3. Kiểm tra preflight requests

### Lỗi 500
1. Kiểm tra PHP error logs
2. Đảm bảo tất cả files được include đúng
3. Kiểm tra database connection

## 📝 Logs

### Database Logs
```php
// Trong Database.php
error_log("Database connected successfully");
error_log("Query failed: " . $e->getMessage());
```

### API Logs
```php
// Trong controllers
error_log("API call: " . $method . " " . $path);
error_log("Error: " . $e->getMessage());
```

## 🔒 Security

### Input Validation
- Tất cả input được validate
- SQL injection protection với prepared statements
- XSS protection với output encoding

### Authentication
- Password hashing với `password_hash()`
- Session management
- CORS configuration

### Error Handling
- Không expose sensitive information
- Proper HTTP status codes
- Detailed logging cho debugging
