/**
 * Database Service - Quản lý kết nối và xử lý database
 */
import { getApiConfig, getCurrentConfig } from '../../config/index.js';

export class DatabaseService {
    constructor() {
        this.config = getApiConfig();
        this.baseUrl = this.config.baseUrl;
        this.isConnected = false;
        this.retryAttempts = 0;
        this.maxRetryAttempts = this.config.retry.attempts;
    }

    // Kiểm tra kết nối database
    async checkConnection() {
        try {
            const response = await fetch(`${this.baseUrl}/health`);
            this.isConnected = response.ok;
            return this.isConnected;
        } catch (error) {
            console.error('Database connection failed:', error);
            this.isConnected = false;
            return false;
        }
    }

    // Generic method để gọi API
    async apiCall(endpoint, method = 'GET', data = null) {
        try {
            const config = {
                method: method,
                headers: {
                    ...this.config.headers
                },
                timeout: this.config.timeout
            };

            if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
                config.body = JSON.stringify(data);
            }

            const response = await fetch(`${this.baseUrl}${endpoint}`, config);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error(`API call failed for ${endpoint}:`, error);
            
            // Retry logic
            if (this.retryAttempts < this.maxRetryAttempts) {
                this.retryAttempts++;
                console.log(`Retrying API call (${this.retryAttempts}/${this.maxRetryAttempts})...`);
                await this.delay(this.config.retry.delay * this.retryAttempts);
                return await this.apiCall(endpoint, method, data);
            }
            
            throw error;
        }
    }

    // Helper method for delay
    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    // ==================== USER OPERATIONS ====================
    
    // Lấy danh sách người dùng
    async getUsers() {
        return await this.apiCall('/users');
    }

    // Lấy thông tin người dùng theo ID
    async getUserById(id) {
        return await this.apiCall(`/users/${id}`);
    }

    // Tạo người dùng mới
    async createUser(userData) {
        return await this.apiCall('/users', 'POST', userData);
    }

    // Cập nhật thông tin người dùng
    async updateUser(id, userData) {
        return await this.apiCall(`/users/${id}`, 'PUT', userData);
    }

    // Xóa người dùng
    async deleteUser(id) {
        return await this.apiCall(`/users/${id}`, 'DELETE');
    }

    // Đăng nhập
    async login(username, password) {
        return await this.apiCall('/auth/login', 'POST', { username, password });
    }

    // ==================== ROOM OPERATIONS ====================
    
    // Lấy danh sách phòng
    async getRooms() {
        return await this.apiCall('/rooms');
    }

    // Lấy thông tin phòng theo ID
    async getRoomById(id) {
        return await this.apiCall(`/rooms/${id}`);
    }

    // Lấy thông tin phòng theo mã phòng
    async getRoomByCode(roomCode) {
        return await this.apiCall(`/rooms/code/${roomCode}`);
    }

    // Tạo phòng mới
    async createRoom(roomData) {
        return await this.apiCall('/rooms', 'POST', roomData);
    }

    // Cập nhật thông tin phòng
    async updateRoom(id, roomData) {
        return await this.apiCall(`/rooms/${id}`, 'PUT', roomData);
    }

    // Xóa phòng
    async deleteRoom(id) {
        return await this.apiCall(`/rooms/${id}`, 'DELETE');
    }

    // Tham gia phòng
    async joinRoom(roomCode, password, userId) {
        return await this.apiCall('/rooms/join', 'POST', {
            roomCode,
            password,
            userId
        });
    }

    // Rời phòng
    async leaveRoom(roomId, userId) {
        return await this.apiCall(`/rooms/${roomId}/leave`, 'POST', { userId });
    }

    // ==================== MATCH OPERATIONS ====================
    
    // Lấy danh sách trận đấu
    async getMatches() {
        return await this.apiCall('/matches');
    }

    // Lấy trận đấu theo ID
    async getMatchById(id) {
        return await this.apiCall(`/matches/${id}`);
    }

    // Lấy trận đấu theo phòng
    async getMatchesByRoom(roomId) {
        return await this.apiCall(`/matches/room/${roomId}`);
    }

    // Tạo trận đấu mới
    async createMatch(matchData) {
        return await this.apiCall('/matches', 'POST', matchData);
    }

    // Cập nhật trận đấu
    async updateMatch(id, matchData) {
        return await this.apiCall(`/matches/${id}`, 'PUT', matchData);
    }

    // Xóa trận đấu
    async deleteMatch(id) {
        return await this.apiCall(`/matches/${id}`, 'DELETE');
    }

    // ==================== PLAYER OPERATIONS ====================
    
    // Lấy danh sách người chơi
    async getPlayers() {
        return await this.apiCall('/players');
    }

    // Lấy người chơi theo ID
    async getPlayerById(id) {
        return await this.apiCall(`/players/${id}`);
    }

    // Lấy người chơi theo trận đấu
    async getPlayersByMatch(matchId) {
        return await this.apiCall(`/players/match/${matchId}`);
    }

    // Tạo người chơi mới
    async createPlayer(playerData) {
        return await this.apiCall('/players', 'POST', playerData);
    }

    // Cập nhật thông tin người chơi
    async updatePlayer(id, playerData) {
        return await this.apiCall(`/players/${id}`, 'PUT', playerData);
    }

    // Xóa người chơi
    async deletePlayer(id) {
        return await this.apiCall(`/players/${id}`, 'DELETE');
    }

    // ==================== TEAM OPERATIONS ====================
    
    // Tạo team theo kỹ năng
    async createSkillTeams(matchId) {
        return await this.apiCall(`/teams/skill/${matchId}`, 'POST');
    }

    // Tạo team ngẫu nhiên
    async createRandomTeams(matchId) {
        return await this.apiCall(`/teams/random/${matchId}`, 'POST');
    }

    // Lấy thông tin team
    async getTeams(matchId) {
        return await this.apiCall(`/teams/${matchId}`);
    }

    // ==================== STATISTICS ====================
    
    // Lấy thống kê người dùng
    async getUserStats(userId) {
        return await this.apiCall(`/stats/user/${userId}`);
    }

    // Lấy thống kê phòng
    async getRoomStats(roomId) {
        return await this.apiCall(`/stats/room/${roomId}`);
    }

    // Lấy thống kê tổng quan
    async getOverallStats() {
        return await this.apiCall('/stats/overall');
    }

    // ==================== UTILITY METHODS ====================
    
    // Lưu dữ liệu vào localStorage (fallback khi không có database)
    saveToLocalStorage(key, data) {
        try {
            localStorage.setItem(key, JSON.stringify(data));
            return true;
        } catch (error) {
            console.error('Failed to save to localStorage:', error);
            return false;
        }
    }

    // Lấy dữ liệu từ localStorage
    getFromLocalStorage(key) {
        try {
            const data = localStorage.getItem(key);
            return data ? JSON.parse(data) : null;
        } catch (error) {
            console.error('Failed to get from localStorage:', error);
            return null;
        }
    }

    // Xóa dữ liệu từ localStorage
    removeFromLocalStorage(key) {
        try {
            localStorage.removeItem(key);
            return true;
        } catch (error) {
            console.error('Failed to remove from localStorage:', error);
            return false;
        }
    }

    // Kiểm tra xem có đang offline không
    isOffline() {
        return !navigator.onLine;
    }

    // Sync dữ liệu khi online trở lại
    async syncWhenOnline() {
        if (navigator.onLine && this.isConnected) {
            // Implement sync logic here
            console.log('Syncing data when online...');
        }
    }
}
