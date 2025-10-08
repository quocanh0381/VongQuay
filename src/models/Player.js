/**
 * Player Model - Quản lý dữ liệu người chơi
 */
export class Player {
    constructor(data = {}) {
        this.id = data.id || this.generateId();
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
}
