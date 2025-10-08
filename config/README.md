# Configuration Directory

Thư mục này chứa tất cả cấu hình cho ứng dụng Vòng Quay, bao gồm cấu hình database, API, và môi trường.

## 📁 Cấu trúc thư mục

```
config/
├── index.js          # Export tất cả cấu hình
├── database.js       # Cấu hình database
├── api.js           # Cấu hình API endpoints
├── environment.js   # Cấu hình môi trường
├── connection.js    # Cấu hình kết nối database
├── env.example      # File mẫu cho biến môi trường
└── README.md        # Tài liệu này
```

## 🔧 Cấu hình Database

### Cấu hình cơ bản
```javascript
import { getDatabaseConfig } from './config/environment.js';

const dbConfig = getDatabaseConfig();
// {
//   host: '127.0.0.1',
//   port: 3306,
//   user: 'root',
//   password: '',
//   database: 'vongquay_db',
//   charset: 'utf8mb4'
// }
```

### Cấu hình kết nối
```javascript
import { getConnectionConfig } from './config/connection.js';

const connectionConfig = getConnectionConfig();
// Bao gồm host, port, user, password, database, charset
// và các cấu hình pool, timeout, reconnect
```

## 🌐 Cấu hình API

### Endpoints
```javascript
import { apiEndpoints } from './config/api.js';

// User endpoints
apiEndpoints.users.list        // GET /users
apiEndpoints.users.create      // POST /users
apiEndpoints.users.get         // GET /users/:id

// Room endpoints
apiEndpoints.rooms.list        // GET /rooms
apiEndpoints.rooms.create     // POST /rooms
apiEndpoints.rooms.join       // POST /rooms/join
```

### HTTP Methods
```javascript
import { httpMethods } from './config/api.js';

httpMethods.GET     // 'GET'
httpMethods.POST    // 'POST'
httpMethods.PUT     // 'PUT'
httpMethods.DELETE  // 'DELETE'
```

## 🌍 Cấu hình Môi trường

### Môi trường Development
```javascript
import { getCurrentConfig } from './config/environment.js';

const config = getCurrentConfig();
// Cấu hình cho development với debug = true
```

### Môi trường Production
```javascript
import { isProduction } from './config/environment.js';

if (isProduction()) {
    // Cấu hình cho production
}
```

## 🔌 Cách sử dụng

### 1. Import cấu hình
```javascript
import { 
    getDatabaseConfig, 
    getApiConfig, 
    getCurrentConfig 
} from './config/index.js';
```

### 2. Sử dụng trong DatabaseService
```javascript
import { getConnectionConfig } from './config/connection.js';

class DatabaseService {
    constructor() {
        this.config = getConnectionConfig();
        this.connect();
    }
}
```

### 3. Sử dụng trong API calls
```javascript
import { apiEndpoints, getFullUrl } from './config/api.js';

const url = getFullUrl(apiEndpoints.users.list);
// http://localhost/vongquay/api/users
```

## 📝 Biến môi trường

### Tạo file .env
```bash
# Sao chép file mẫu
cp config/env.example .env

# Chỉnh sửa các giá trị
nano .env
```

### Các biến môi trường quan trọng
```env
# Database
DB_HOST=127.0.0.1
DB_PORT=3306
DB_USER=root
DB_PASSWORD=
DB_NAME=vongquay_db

# API
API_BASE_URL=http://localhost/vongquay/api

# Environment
NODE_ENV=development
DEBUG=true
LOG_LEVEL=debug
```

## 🚀 Cấu hình theo môi trường

### Development
- Debug: `true`
- Logging: Console + File
- Cache: Enabled
- Pool: Min 0, Max 10

### Production
- Debug: `false`
- Logging: File only
- Cache: Enabled với TTL cao hơn
- Pool: Min 2, Max 20
- SSL: Enabled

### Test
- Debug: `true`
- Logging: Console only
- Cache: Disabled
- Pool: Min 0, Max 5

## 🔍 Validation

### Kiểm tra cấu hình
```javascript
import { validateEnvironment } from './config/environment.js';

const validation = validateEnvironment();
if (!validation.isValid) {
    console.error('Configuration errors:', validation.errors);
}
```

### Kiểm tra kết nối database
```javascript
import { validateConnectionConfig } from './config/connection.js';

const validation = validateConnectionConfig();
if (!validation.isValid) {
    console.error('Connection config errors:', validation.errors);
}
```

## 📊 Monitoring

### Connection Status
```javascript
import { connectionStatus } from './config/connection.js';

// connectionStatus.DISCONNECTED
// connectionStatus.CONNECTING
// connectionStatus.CONNECTED
// connectionStatus.ERROR
```

### Logging
```javascript
import { getLoggingConfig } from './config/environment.js';

const loggingConfig = getLoggingConfig();
// { level: 'debug', console: true, file: true }
```

## 🛠️ Troubleshooting

### Lỗi kết nối database
1. Kiểm tra thông tin database trong `.env`
2. Đảm bảo MySQL service đang chạy
3. Kiểm tra firewall và port

### Lỗi API
1. Kiểm tra `API_BASE_URL` trong `.env`
2. Đảm bảo API server đang chạy
3. Kiểm tra CORS settings

### Lỗi cấu hình
1. Chạy `validateEnvironment()` để kiểm tra
2. Kiểm tra file `.env` có đúng format không
3. Restart ứng dụng sau khi thay đổi cấu hình
