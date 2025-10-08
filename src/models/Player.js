/**
 * Player Model - Quản lý dữ liệu người chơi
 */
export class Player {
    constructor(data = {}) {
        this.id = data.id || this.generateId();
        this.matchId = data.match_id || data.matchId || null;
        this.name = data.name || '';
        this.rank = data.rank || 'A';
        this.skill = data.skill || 50;
        this.mainRole = data.mainRole || 'top';
        this.subRole = data.subRole || 'top';
        this.personalSkill = data.personalSkill || 3;
        this.mapReading = data.mapReading || 3;
        this.teamwork = data.teamwork || 3;
        this.reaction = data.reaction || 3;
        this.experience = data.experience || 3;
        this.position = data.position || 1;
        this.createdBy = data.created_by || data.createdBy || null;
        this.createdAt = data.created_at || data.createdAt || new Date().toISOString();
        this.totalScore = this.calculateTotalScore();
    }

    generateId() {
        return Date.now() + Math.random().toString(36).substr(2, 9);
    }

    calculateTotalScore() {
        return this.personalSkill + this.mapReading + this.teamwork + 
               this.reaction + this.experience;
    }

    update(data) {
        Object.assign(this, data);
        this.totalScore = this.calculateTotalScore();
        return this;
    }

    toJSON() {
        return {
            id: this.id,
            name: this.name,
            rank: this.rank,
            skill: this.skill,
            mainRole: this.mainRole,
            subRole: this.subRole,
            personalSkill: this.personalSkill,
            mapReading: this.mapReading,
            teamwork: this.teamwork,
            reaction: this.reaction,
            experience: this.experience,
            position: this.position,
            totalScore: this.totalScore
        };
    }

    static fromJSON(data) {
        return new Player(data);
    }

    // Chuyển đổi thành object để lưu database
    toDatabase() {
        return {
            id: this.id,
            match_id: this.matchId,
            name: this.name,
            personalSkill: this.personalSkill,
            mapReading: this.mapReading,
            teamwork: this.teamwork,
            reaction: this.reaction,
            experience: this.experience,
            position: this.position,
            totalScore: this.totalScore,
            created_by: this.createdBy,
            created_at: this.createdAt
        };
    }

    // Tạo từ dữ liệu database
    static fromDatabase(data) {
        return new Player(data);
    }

    // Cập nhật thông tin người chơi
    update(data) {
        Object.assign(this, data);
        this.totalScore = this.calculateTotalScore();
        return this;
    }

    // Lấy thông tin hiển thị
    getDisplayInfo() {
        return {
            id: this.id,
            name: this.name,
            rank: this.rank,
            skill: this.skill,
            mainRole: this.mainRole,
            subRole: this.subRole,
            personalSkill: this.personalSkill,
            mapReading: this.mapReading,
            teamwork: this.teamwork,
            reaction: this.reaction,
            experience: this.experience,
            position: this.position,
            totalScore: this.totalScore
        };
    }

    // Validation
    validate() {
        const errors = [];
        
        if (!this.name || this.name.trim().length < 2) {
            errors.push('Tên người chơi phải có ít nhất 2 ký tự');
        }
        
        if (this.personalSkill < 1 || this.personalSkill > 5) {
            errors.push('Kỹ năng cá nhân phải từ 1 đến 5');
        }
        
        if (this.mapReading < 1 || this.mapReading > 5) {
            errors.push('Kỹ năng đọc bản đồ phải từ 1 đến 5');
        }
        
        if (this.teamwork < 1 || this.teamwork > 5) {
            errors.push('Kỹ năng teamwork phải từ 1 đến 5');
        }
        
        if (this.reaction < 1 || this.reaction > 5) {
            errors.push('Kỹ năng phản xạ phải từ 1 đến 5');
        }
        
        if (this.experience < 1 || this.experience > 5) {
            errors.push('Kinh nghiệm phải từ 1 đến 5');
        }
        
        if (this.position < 1 || this.position > 5) {
            errors.push('Vị trí phải từ 1 đến 5');
        }
        
        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }
}
