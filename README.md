# Vòng Quay Ngẫu Nhiên - MVC Architecture

Ứng dụng chia team cân bằng với kiến trúc MVC (Model-View-Controller).

## 🏗️ Cấu trúc dự án

```
VongQuay/
├── index.html              # File HTML chính
├── app.js                  # File khởi chạy ứng dụng
├── style.css               # CSS chung
├── README.md               # Hướng dẫn sử dụng
└── src/
    ├── models/             # Models (Dữ liệu)
    │   ├── Player.js       # Model người chơi
    │   ├── Team.js         # Model team
    │   └── Game.js         # Model game chính
    ├── views/              # Views (Giao diện)
    │   ├── HomeView.js     # View trang chủ
    │   ├── PlayerFormView.js # View form nhập thông tin
    │   ├── WheelView.js    # View vòng quay
    │   └── ResultView.js   # View kết quả
    ├── controllers/        # Controllers (Logic)
    │   ├── HomeController.js      # Controller trang chủ
    │   ├── SkillTeamController.js # Controller team kỹ năng
    │   ├── RandomTeamController.js # Controller team random
    │   └── PlayerController.js   # Controller quản lý người chơi
    └── router/             # Router (Điều hướng)
        └── Router.js       # Router chính
```

## 🚀 Cách sử dụng

1. **Mở `index.html`** trong trình duyệt
2. **Chọn chức năng** từ menu navigation:
   - **Trang chủ**: Menu chọn chức năng
   - **Team Kỹ năng**: Chia team cân bằng thông minh
   - **Team Random**: Chia team hoàn toàn ngẫu nhiên
   - **Quản lý**: Thêm/sửa/xóa người chơi

## 🎯 Các chức năng chính

### 1. **Lập Team Theo Kỹ Năng**
- Thuật toán cân bằng thông minh
- Đảm bảo mỗi team có đủ 5 đường
- Phân tích chi tiết độ cân bằng

### 2. **Lập Team Random**
- Chia team hoàn toàn ngẫu nhiên
- Phù hợp cho trận đấu vui vẻ
- Vẫn đảm bảo đủ 5 đường

### 3. **Quản lý Người chơi**
- Thêm người chơi mới
- Chỉnh sửa thông tin
- Xóa người chơi

## 🏛️ Kiến trúc MVC

### **Models (src/models/)**
- **Player.js**: Quản lý dữ liệu người chơi
- **Team.js**: Quản lý dữ liệu team
- **Game.js**: Logic game chính

### **Views (src/views/)**
- **HomeView.js**: Giao diện trang chủ
- **PlayerFormView.js**: Form nhập thông tin
- **WheelView.js**: Vòng quay
- **ResultView.js**: Hiển thị kết quả

### **Controllers (src/controllers/)**
- **HomeController.js**: Logic trang chủ
- **SkillTeamController.js**: Logic team kỹ năng
- **RandomTeamController.js**: Logic team random
- **PlayerController.js**: Logic quản lý người chơi

### **Router (src/router/)**
- **Router.js**: Điều hướng giữa các trang

## 🔧 Cài đặt và chạy

1. **Clone hoặc tải** dự án về máy
2. **Mở file `index.html`** trong trình duyệt
3. **Hoặc chạy local server**:
   ```bash
   # Sử dụng Python
   python -m http.server 8000
   
   # Sử dụng Node.js
   npx serve .
   ```

## 📱 Responsive Design

- Hỗ trợ đầy đủ trên mobile, tablet, desktop
- Giao diện thích ứng với mọi kích thước màn hình
- Navigation menu responsive

## 🎨 Tính năng UI/UX

- **Giao diện đẹp**: Gradient background, glassmorphism effects
- **Animations**: Smooth transitions, hover effects
- **Icons**: Font Awesome icons
- **Typography**: Google Fonts (Roboto)

## 🔄 Navigation

- **Single Page Application (SPA)**
- **Browser History**: Hỗ trợ back/forward
- **Active States**: Highlight trang hiện tại
- **Smooth Transitions**: Chuyển trang mượt mà

## 🧪 Testing

Để test các chức năng:

1. **Thêm người chơi** trong trang Quản lý
2. **Chọn Team Kỹ năng** để test thuật toán cân bằng
3. **Chọn Team Random** để test chia ngẫu nhiên
4. **Kiểm tra kết quả** và thống kê

## 📊 Thuật toán

### **Team Kỹ năng**
- Sắp xếp theo điểm số
- Phân chia cân bằng
- Đảm bảo đủ 5 đường
- Trao đổi thông minh nếu cần

### **Team Random**
- Xáo trộn ngẫu nhiên
- Chia đều cho 2 team
- Đảm bảo đủ 5 đường
- Phân bổ roles ngẫu nhiên

## 🚀 Mở rộng

Để thêm tính năng mới:

1. **Tạo Model** trong `src/models/`
2. **Tạo View** trong `src/views/`
3. **Tạo Controller** trong `src/controllers/`
4. **Thêm route** trong `app.js`

## 📝 Ghi chú

- Sử dụng ES6 Modules
- Không cần build process
- Chạy trực tiếp trong trình duyệt
- Hỗ trợ tất cả trình duyệt hiện đại
