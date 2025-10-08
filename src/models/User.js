/**
 * User Model - Quản lý dữ liệu người dùng
 */
export class User {
    constructor(data = {}) {
        this.id = data.id || null;
        this.username = data.username || '';
        this.password = data.password || '';
        this.displayName = data.display_name || data.displayName || '';
        this.email = data.email || '';
        this.phone = data.phone || '';
        this.avatar = data.avatar || '';
        this.level = data.level || 1;
        this.totalMatches = data.total_matches || data.totalMatches || 0;
        this.winMatches = data.win_matches || data.winMatches || 0;
        this.createdAt = data.created_at || data.createdAt || new Date().toISOString();
        this.updatedAt = data.updated_at || data.updatedAt || new Date().toISOString();
    }

    // Tính tỷ lệ thắng
    getWinRate() {
        if (this.totalMatches === 0) return 0;
        return Math.round((this.winMatches / this.totalMatches) * 100);
    }

    // Cập nhật thông tin người dùng
    update(data) {
        Object.assign(this, data);
        this.updatedAt = new Date().toISOString();
        return this;
    }

    // Tăng số trận đấu
    incrementMatches(won = false) {
        this.totalMatches++;
        if (won) {
            this.winMatches++;
        }
        this.updatedAt = new Date().toISOString();
    }

    // Lấy thông tin hiển thị
    getDisplayInfo() {
        return {
            id: this.id,
            username: this.username,
            displayName: this.displayName,
            email: this.email,
            phone: this.phone,
            avatar: this.avatar,
            level: this.level,
            totalMatches: this.totalMatches,
            winMatches: this.winMatches,
            winRate: this.getWinRate()
        };
    }

    // Chuyển đổi thành object để lưu database
    toDatabase() {
        return {
            id: this.id,
            username: this.username,
            password: this.password,
            display_name: this.displayName,
            email: this.email,
            phone: this.phone,
            avatar: this.avatar,
            level: this.level,
            total_matches: this.totalMatches,
            win_matches: this.winMatches,
            created_at: this.createdAt,
            updated_at: this.updatedAt
        };
    }

    // Tạo từ dữ liệu database
    static fromDatabase(data) {
        return new User(data);
    }

    // Validation
    validate() {
        const errors = [];
        
        if (!this.username || this.username.trim().length < 3) {
            errors.push('Username phải có ít nhất 3 ký tự');
        }
        
        if (!this.password || this.password.length < 6) {
            errors.push('Password phải có ít nhất 6 ký tự');
        }
        
        if (this.email && !this.isValidEmail(this.email)) {
            errors.push('Email không hợp lệ');
        }
        
        if (this.phone && !this.isValidPhone(this.phone)) {
            errors.push('Số điện thoại không hợp lệ');
        }
        
        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isValidPhone(phone) {
        const phoneRegex = /^[0-9]{10,11}$/;
        return phoneRegex.test(phone);
    }
}
