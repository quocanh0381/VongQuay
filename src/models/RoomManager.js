/**
 * Room Manager - Quản lý phòng và người chơi
 */
import { Room } from './Room.js';
import { User } from './User.js';
import { Match } from './Match.js';
import { DatabaseService } from '../services/DatabaseService.js';

export class RoomManager {
    constructor() {
        this.databaseService = new DatabaseService();
        this.currentRoom = null;
        this.currentUser = null;
        this.rooms = new Map(); // Cache rooms
    }

    // ==================== ROOM MANAGEMENT ====================
    
    // Tạo phòng mới
    async createRoom(roomData) {
        try {
            // Validation
            const room = new Room(roomData);
            const validation = room.validate();
            if (!validation.isValid) {
                throw new Error(validation.errors.join(', '));
            }

            // Tạo mã phòng nếu chưa có
            if (!room.roomCode) {
                room.roomCode = Room.generateRoomCode();
            }

            // Lưu vào database
            const savedRoom = await this.databaseService.createRoom(room.toDatabase());
            
            // Cập nhật cache
            const newRoom = Room.fromDatabase(savedRoom);
            this.rooms.set(newRoom.id, newRoom);
            this.currentRoom = newRoom;

            return newRoom;
        } catch (error) {
            console.error('Failed to create room:', error);
            throw error;
        }
    }

    // Tham gia phòng
    async joinRoom(roomCode, password, userId) {
        try {
            // Tìm phòng theo mã
            const roomData = await this.databaseService.getRoomByCode(roomCode);
            if (!roomData) {
                throw new Error('Không tìm thấy phòng với mã này');
            }

            const room = Room.fromDatabase(roomData);
            
            // Kiểm tra mật khẩu
            if (room.password !== password) {
                throw new Error('Mật khẩu phòng không đúng');
            }

            // Kiểm tra phòng có thể tham gia
            if (!room.canJoin()) {
                throw new Error('Phòng không thể tham gia');
            }

            // Tham gia phòng
            await this.databaseService.joinRoom(roomCode, password, userId);
            
            // Cập nhật cache
            this.rooms.set(room.id, room);
            this.currentRoom = room;

            return room;
        } catch (error) {
            console.error('Failed to join room:', error);
            throw error;
        }
    }

    // Rời phòng
    async leaveRoom(userId) {
        try {
            if (!this.currentRoom) {
                throw new Error('Bạn không ở trong phòng nào');
            }

            await this.databaseService.leaveRoom(this.currentRoom.id, userId);
            
            // Cập nhật cache
            this.currentRoom.removeParticipant(userId);
            this.rooms.set(this.currentRoom.id, this.currentRoom);

            return true;
        } catch (error) {
            console.error('Failed to leave room:', error);
            throw error;
        }
    }

    // Lấy thông tin phòng hiện tại
    getCurrentRoom() {
        return this.currentRoom;
    }

    // Cập nhật thông tin phòng
    async updateRoom(roomData) {
        try {
            if (!this.currentRoom) {
                throw new Error('Không có phòng để cập nhật');
            }

            // Cập nhật thông tin
            Object.assign(this.currentRoom, roomData);
            
            // Validation
            const validation = this.currentRoom.validate();
            if (!validation.isValid) {
                throw new Error(validation.errors.join(', '));
            }

            // Lưu vào database
            await this.databaseService.updateRoom(this.currentRoom.id, this.currentRoom.toDatabase());
            
            // Cập nhật cache
            this.rooms.set(this.currentRoom.id, this.currentRoom);

            return this.currentRoom;
        } catch (error) {
            console.error('Failed to update room:', error);
            throw error;
        }
    }

    // ==================== MATCH MANAGEMENT ====================
    
    // Tạo trận đấu mới
    async createMatch(matchData) {
        try {
            if (!this.currentRoom) {
                throw new Error('Bạn phải ở trong phòng để tạo trận đấu');
            }

            const match = new Match({
                ...matchData,
                roomId: this.currentRoom.id,
                createdBy: this.currentUser?.id
            });

            // Validation
            const validation = match.validate();
            if (!validation.isValid) {
                throw new Error(validation.errors.join(', '));
            }

            // Lưu vào database
            const savedMatch = await this.databaseService.createMatch(match.toDatabase());
            
            return Match.fromDatabase(savedMatch);
        } catch (error) {
            console.error('Failed to create match:', error);
            throw error;
        }
    }

    // Lấy danh sách trận đấu trong phòng
    async getMatchesInRoom() {
        try {
            if (!this.currentRoom) {
                throw new Error('Bạn phải ở trong phòng để xem trận đấu');
            }

            const matches = await this.databaseService.getMatchesByRoom(this.currentRoom.id);
            return matches.map(match => Match.fromDatabase(match));
        } catch (error) {
            console.error('Failed to get matches:', error);
            throw error;
        }
    }

    // ==================== PLAYER MANAGEMENT ====================
    
    // Thêm người chơi vào trận đấu
    async addPlayerToMatch(matchId, playerData) {
        try {
            const player = await this.databaseService.createPlayer({
                ...playerData,
                matchId: matchId,
                createdBy: this.currentUser?.id
            });

            return player;
        } catch (error) {
            console.error('Failed to add player to match:', error);
            throw error;
        }
    }

    // Lấy danh sách người chơi trong trận đấu
    async getPlayersInMatch(matchId) {
        try {
            const players = await this.databaseService.getPlayersByMatch(matchId);
            return players;
        } catch (error) {
            console.error('Failed to get players in match:', error);
            throw error;
        }
    }

    // ==================== TEAM CREATION ====================
    
    // Tạo team theo kỹ năng
    async createSkillTeams(matchId) {
        try {
            const teams = await this.databaseService.createSkillTeams(matchId);
            return teams;
        } catch (error) {
            console.error('Failed to create skill teams:', error);
            throw error;
        }
    }

    // Tạo team ngẫu nhiên
    async createRandomTeams(matchId) {
        try {
            const teams = await this.databaseService.createRandomTeams(matchId);
            return teams;
        } catch (error) {
            console.error('Failed to create random teams:', error);
            throw error;
        }
    }

    // ==================== UTILITY METHODS ====================
    
    // Lưu trạng thái vào localStorage
    saveState() {
        try {
            const state = {
                currentRoom: this.currentRoom ? this.currentRoom.getDetailedInfo() : null,
                currentUser: this.currentUser ? this.currentUser.getDisplayInfo() : null,
                timestamp: new Date().toISOString()
            };
            
            this.databaseService.saveToLocalStorage('roomManagerState', state);
            return true;
        } catch (error) {
            console.error('Failed to save state:', error);
            return false;
        }
    }

    // Khôi phục trạng thái từ localStorage
    restoreState() {
        try {
            const state = this.databaseService.getFromLocalStorage('roomManagerState');
            if (state) {
                if (state.currentRoom) {
                    this.currentRoom = new Room(state.currentRoom);
                }
                if (state.currentUser) {
                    this.currentUser = new User(state.currentUser);
                }
                return true;
            }
            return false;
        } catch (error) {
            console.error('Failed to restore state:', error);
            return false;
        }
    }

    // Xóa trạng thái
    clearState() {
        try {
            this.currentRoom = null;
            this.currentUser = null;
            this.rooms.clear();
            this.databaseService.removeFromLocalStorage('roomManagerState');
            return true;
        } catch (error) {
            console.error('Failed to clear state:', error);
            return false;
        }
    }

    // Kiểm tra kết nối database
    async checkConnection() {
        return await this.databaseService.checkConnection();
    }

    // Lấy thống kê
    async getStats() {
        try {
            if (this.currentRoom) {
                return await this.databaseService.getRoomStats(this.currentRoom.id);
            }
            return await this.databaseService.getOverallStats();
        } catch (error) {
            console.error('Failed to get stats:', error);
            throw error;
        }
    }
}
