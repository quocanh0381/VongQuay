/**
 * Room Model - Quản lý dữ liệu phòng
 */
export class Room {
    constructor(data = {}) {
        this.id = data.id || null;
        this.roomName = data.room_name || data.roomName || '';
        this.roomCode = data.room_code || data.roomCode || '';
        this.password = data.password || '';
        this.createdBy = data.created_by || data.createdBy || null;
        this.maxPlayers = data.max_players || data.maxPlayers || 10;
        this.isActive = data.is_active !== undefined ? data.is_active : true;
        this.createdAt = data.created_at || data.createdAt || new Date().toISOString();
        this.updatedAt = data.updated_at || data.updatedAt || new Date().toISOString();
        
        // Thông tin bổ sung
        this.participants = data.participants || [];
        this.currentPlayers = data.currentPlayers || 0;
        this.status = data.status || 'waiting'; // waiting, playing, finished
    }

    // Thêm người chơi vào phòng
    addParticipant(userId, isOwner = false) {
        if (this.currentPlayers >= this.maxPlayers) {
            throw new Error('Phòng đã đầy');
        }
        
        if (this.status !== 'waiting') {
            throw new Error('Phòng không ở trạng thái chờ');
        }

        const participant = {
            userId: userId,
            joinedAt: new Date().toISOString(),
            isOwner: isOwner
        };
        
        this.participants.push(participant);
        this.currentPlayers = this.participants.length;
        this.updatedAt = new Date().toISOString();
        
        return participant;
    }

    // Xóa người chơi khỏi phòng
    removeParticipant(userId) {
        const index = this.participants.findIndex(p => p.userId === userId);
        if (index > -1) {
            this.participants.splice(index, 1);
            this.currentPlayers = this.participants.length;
            this.updatedAt = new Date().toISOString();
            return true;
        }
        return false;
    }

    // Kiểm tra người chơi có trong phòng không
    hasParticipant(userId) {
        return this.participants.some(p => p.userId === userId);
    }

    // Lấy chủ phòng
    getOwner() {
        return this.participants.find(p => p.isOwner);
    }

    // Kiểm tra phòng có đầy không
    isFull() {
        return this.currentPlayers >= this.maxPlayers;
    }

    // Kiểm tra phòng có thể tham gia không
    canJoin() {
        return this.isActive && !this.isFull() && this.status === 'waiting';
    }

    // Bắt đầu trận đấu
    startMatch() {
        if (this.status !== 'waiting') {
            throw new Error('Phòng không ở trạng thái chờ');
        }
        if (this.currentPlayers < 2) {
            throw new Error('Cần ít nhất 2 người chơi để bắt đầu');
        }
        
        this.status = 'playing';
        this.updatedAt = new Date().toISOString();
    }

    // Kết thúc trận đấu
    endMatch() {
        this.status = 'finished';
        this.updatedAt = new Date().toISOString();
    }

    // Reset phòng
    reset() {
        this.status = 'waiting';
        this.participants = [];
        this.currentPlayers = 0;
        this.updatedAt = new Date().toISOString();
    }

    // Tạo mã phòng ngẫu nhiên
    static generateRoomCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        for (let i = 0; i < 6; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }

    // Lấy thông tin hiển thị
    getDisplayInfo() {
        return {
            id: this.id,
            roomName: this.roomName,
            roomCode: this.roomCode,
            maxPlayers: this.maxPlayers,
            currentPlayers: this.currentPlayers,
            isActive: this.isActive,
            status: this.status,
            canJoin: this.canJoin(),
            isFull: this.isFull(),
            createdAt: this.createdAt
        };
    }

    // Lấy thông tin chi tiết
    getDetailedInfo() {
        return {
            ...this.getDisplayInfo(),
            participants: this.participants,
            owner: this.getOwner(),
            createdAt: this.createdAt,
            updatedAt: this.updatedAt
        };
    }

    // Chuyển đổi thành object để lưu database
    toDatabase() {
        return {
            id: this.id,
            room_name: this.roomName,
            room_code: this.roomCode,
            password: this.password,
            created_by: this.createdBy,
            max_players: this.maxPlayers,
            is_active: this.isActive ? 1 : 0,
            created_at: this.createdAt,
            updated_at: this.updatedAt
        };
    }

    // Tạo từ dữ liệu database
    static fromDatabase(data) {
        return new Room(data);
    }

    // Validation
    validate() {
        const errors = [];
        
        if (!this.roomName || this.roomName.trim().length < 3) {
            errors.push('Tên phòng phải có ít nhất 3 ký tự');
        }
        
        if (!this.roomCode || this.roomCode.length < 4) {
            errors.push('Mã phòng phải có ít nhất 4 ký tự');
        }
        
        if (!this.password || this.password.length < 4) {
            errors.push('Mật khẩu phải có ít nhất 4 ký tự');
        }
        
        if (this.maxPlayers < 2 || this.maxPlayers > 20) {
            errors.push('Số người chơi tối đa phải từ 2 đến 20');
        }
        
        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }
}
