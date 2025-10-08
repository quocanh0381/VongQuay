# API Setup Guide

Hướng dẫn setup và kiểm tra API backend cho Vòng Quay.

## 🚀 Quick Setup

### 1. Import Database
```sql
-- Tạo database
CREATE DATABASE vongquay_db;

-- Import file SQL
mysql -u root -p vongquay_db < ../VongQuay/vongquay_db.sql
```

### 2. Cấu hình Database
Chỉnh sửa file `config/database.php`:
```php
private static $config = [
    'host' => '127.0.0.1',
    'port' => 3306,
    'username' => 'root',
    'password' => '',  // Điền password MySQL
    'database' => 'vongquay_db',
    'charset' => 'utf8mb4'
];
```

### 3. Kiểm tra Kết nối
Truy cập: `http://localhost/vongquay/api/test.php`

### 4. Kiểm tra API
Truy cập: `http://localhost/vongquay/api/test_api.php`

### 5. Setup Complete
Truy cập: `http://localhost/vongquay/api/setup.php`

## 🔧 Troubleshooting

### Lỗi Database Connection
1. Kiểm tra MySQL service đang chạy
2. Kiểm tra username/password trong config
3. Kiểm tra database `vongquay_db` đã tồn tại
4. Kiểm tra file SQL đã import đúng

### Lỗi API 404
1. Kiểm tra `.htaccess` có hoạt động
2. Kiểm tra mod_rewrite được enable
3. Kiểm tra Apache config

### Lỗi CORS
1. Kiểm tra headers trong `.htaccess`
2. Kiểm tra frontend gọi đúng URL
3. Kiểm tra preflight requests

## 📊 Test Endpoints

### Health Check
```
GET http://localhost/vongquay/api/health
```

### Users
```
GET    http://localhost/vongquay/api/users
POST   http://localhost/vongquay/api/users
GET    http://localhost/vongquay/api/users/1
PUT    http://localhost/vongquay/api/users/1
DELETE http://localhost/vongquay/api/users/1
```

### Rooms
```
GET    http://localhost/vongquay/api/rooms
POST   http://localhost/vongquay/api/rooms
GET    http://localhost/vongquay/api/rooms/1
GET    http://localhost/vongquay/api/rooms/code/RANK001
POST   http://localhost/vongquay/api/rooms/join
```

### Matches
```
GET    http://localhost/vongquay/api/matches
POST   http://localhost/vongquay/api/matches
GET    http://localhost/vongquay/api/matches/1
```

### Players
```
GET    http://localhost/vongquay/api/players
POST   http://localhost/vongquay/api/players
GET    http://localhost/vongquay/api/players/1
```

### Teams
```
POST   http://localhost/vongquay/api/teams/skill/1
POST   http://localhost/vongquay/api/teams/random/1
GET    http://localhost/vongquay/api/teams/1
```

## 🧪 Test Data

### Create User
```json
POST /api/users
{
  "username": "testuser",
  "password": "123456",
  "display_name": "Test User",
  "email": "test@example.com"
}
```

### Create Room
```json
POST /api/rooms
{
  "room_name": "Test Room",
  "password": "123456",
  "created_by": 1,
  "max_players": 10
}
```

### Join Room
```json
POST /api/rooms/join
{
  "room_code": "RANK001",
  "password": "rank123",
  "user_id": 2
}
```

### Create Match
```json
POST /api/matches
{
  "match_name": "Test Match",
  "room_id": 1,
  "created_by": 1
}
```

### Add Player
```json
POST /api/players
{
  "match_id": 1,
  "name": "Test Player",
  "personalSkill": 4,
  "mapReading": 3,
  "teamwork": 5,
  "reaction": 4,
  "experience": 4,
  "position": 1,
  "created_by": 1
}
```

### Create Teams
```
POST /api/teams/skill/1    # Skill-based teams
POST /api/teams/random/1   # Random teams
```

## 🔍 Debugging

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

### Frontend Integration
```javascript
// Test API call
fetch('http://localhost/vongquay/api/health')
  .then(response => response.json())
  .then(data => console.log(data));
```

## ✅ Checklist

- [ ] MySQL service running
- [ ] Database `vongquay_db` created
- [ ] SQL file imported
- [ ] Database config updated
- [ ] Apache mod_rewrite enabled
- [ ] .htaccess working
- [ ] API endpoints responding
- [ ] CORS headers set
- [ ] Frontend can connect

## 🆘 Support

Nếu gặp vấn đề:
1. Kiểm tra error logs trong browser console
2. Kiểm tra Apache error logs
3. Kiểm tra PHP error logs
4. Test từng endpoint riêng biệt
5. Kiểm tra database connection
