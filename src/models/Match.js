/**
 * Match Model - Quản lý dữ liệu trận đấu
 */
export class Match {
    constructor(data = {}) {
        this.id = data.id || null;
        this.matchName = data.match_name || data.matchName || '';
        this.roomId = data.room_id || data.roomId || null;
        this.createdBy = data.created_by || data.createdBy || null;
        this.createdAt = data.created_at || data.createdAt || new Date().toISOString();
        
        // Thông tin bổ sung
        this.status = data.status || 'waiting'; // waiting, playing, finished
        this.players = data.players || [];
        this.teams = data.teams || [];
        this.gameMode = data.gameMode || 'skill'; // skill, random
        this.settings = data.settings || {};
    }

    // Thêm người chơi vào trận đấu
    addPlayer(playerData) {
        if (this.status !== 'waiting') {
            throw new Error('Trận đấu đã bắt đầu hoặc kết thúc');
        }

        const player = {
            id: playerData.id || Date.now(),
            name: playerData.name,
            personalSkill: playerData.personalSkill || 0,
            mapReading: playerData.mapReading || 0,
            teamwork: playerData.teamwork || 0,
            reaction: playerData.reaction || 0,
            experience: playerData.experience || 0,
            position: playerData.position || 0,
            totalScore: playerData.totalScore || 0,
            createdBy: playerData.createdBy || this.createdBy,
            createdAt: new Date().toISOString()
        };

        this.players.push(player);
        return player;
    }

    // Xóa người chơi khỏi trận đấu
    removePlayer(playerId) {
        const index = this.players.findIndex(p => p.id === playerId);
        if (index > -1) {
            this.players.splice(index, 1);
            return true;
        }
        return false;
    }

    // Cập nhật thông tin người chơi
    updatePlayer(playerId, data) {
        const player = this.players.find(p => p.id === playerId);
        if (player) {
            Object.assign(player, data);
            // Tính lại totalScore nếu có thay đổi skill
            if (data.personalSkill || data.mapReading || data.teamwork || 
                data.reaction || data.experience || data.position) {
                player.totalScore = this.calculateTotalScore(player);
            }
            return player;
        }
        return null;
    }

    // Tính tổng điểm của người chơi
    calculateTotalScore(player) {
        return (player.personalSkill || 0) + 
               (player.mapReading || 0) + 
               (player.teamwork || 0) + 
               (player.reaction || 0) + 
               (player.experience || 0) + 
               (player.position || 0);
    }

    // Bắt đầu trận đấu
    startMatch() {
        if (this.status !== 'waiting') {
            throw new Error('Trận đấu không ở trạng thái chờ');
        }
        if (this.players.length < 2) {
            throw new Error('Cần ít nhất 2 người chơi để bắt đầu');
        }

        this.status = 'playing';
        this.createdAt = new Date().toISOString();
    }

    // Kết thúc trận đấu
    endMatch() {
        this.status = 'finished';
    }

    // Tạo team dựa trên kỹ năng
    createSkillTeams() {
        if (this.players.length < 2) {
            throw new Error('Cần ít nhất 2 người chơi để tạo team');
        }

        // Sắp xếp người chơi theo totalScore giảm dần
        const sortedPlayers = [...this.players].sort((a, b) => b.totalScore - a.totalScore);
        
        // Chia thành 2 team
        const team1 = [];
        const team2 = [];
        
        for (let i = 0; i < sortedPlayers.length; i++) {
            if (i % 2 === 0) {
                team1.push(sortedPlayers[i]);
            } else {
                team2.push(sortedPlayers[i]);
            }
        }

        this.teams = [
            { id: 1, name: 'Team 1', players: team1 },
            { id: 2, name: 'Team 2', players: team2 }
        ];

        return this.teams;
    }

    // Tạo team ngẫu nhiên
    createRandomTeams() {
        if (this.players.length < 2) {
            throw new Error('Cần ít nhất 2 người chơi để tạo team');
        }

        // Trộn ngẫu nhiên danh sách người chơi
        const shuffledPlayers = [...this.players].sort(() => Math.random() - 0.5);
        
        // Chia thành 2 team
        const team1 = [];
        const team2 = [];
        
        for (let i = 0; i < shuffledPlayers.length; i++) {
            if (i % 2 === 0) {
                team1.push(shuffledPlayers[i]);
            } else {
                team2.push(shuffledPlayers[i]);
            }
        }

        this.teams = [
            { id: 1, name: 'Team 1', players: team1 },
            { id: 2, name: 'Team 2', players: team2 }
        ];

        return this.teams;
    }

    // Lấy thông tin hiển thị
    getDisplayInfo() {
        return {
            id: this.id,
            matchName: this.matchName,
            roomId: this.roomId,
            status: this.status,
            playerCount: this.players.length,
            teamCount: this.teams.length,
            gameMode: this.gameMode,
            createdAt: this.createdAt
        };
    }

    // Lấy thông tin chi tiết
    getDetailedInfo() {
        return {
            ...this.getDisplayInfo(),
            players: this.players,
            teams: this.teams,
            settings: this.settings,
            createdBy: this.createdBy
        };
    }

    // Chuyển đổi thành object để lưu database
    toDatabase() {
        return {
            id: this.id,
            match_name: this.matchName,
            room_id: this.roomId,
            created_by: this.createdBy,
            created_at: this.createdAt
        };
    }

    // Tạo từ dữ liệu database
    static fromDatabase(data) {
        return new Match(data);
    }

    // Validation
    validate() {
        const errors = [];
        
        if (!this.matchName || this.matchName.trim().length < 3) {
            errors.push('Tên trận đấu phải có ít nhất 3 ký tự');
        }
        
        if (!this.roomId) {
            errors.push('Trận đấu phải thuộc về một phòng');
        }
        
        if (!this.createdBy) {
            errors.push('Trận đấu phải có người tạo');
        }
        
        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }
}
