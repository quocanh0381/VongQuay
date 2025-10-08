/**
 * Team Model - Quản lý dữ liệu team
 */
export class Team {
    constructor(name, players = []) {
        this.name = name;
        this.players = players;
        this.stats = this.calculateStats();
    }

    addPlayer(player) {
        this.players.push(player);
        this.stats = this.calculateStats();
    }

    removePlayer(playerId) {
        this.players = this.players.filter(p => p.id !== playerId);
        this.stats = this.calculateStats();
    }

    calculateStats() {
        const stats = {
            totalScore: 0,
            rankDistribution: { A: 0, B: 0, C: 0, D: 0 },
            roleDistribution: { top: 0, jungle: 0, mid: 0, support: 0, ad: 0 },
            averageSkill: 0,
            playerCount: this.players.length
        };

        if (this.players.length === 0) return stats;

        this.players.forEach(player => {
            stats.totalScore += player.totalScore;
            stats.rankDistribution[player.rank]++;
            stats.roleDistribution[player.mainRole]++;
        });

        stats.averageSkill = stats.totalScore / this.players.length;

        return stats;
    }

    getBalanceScore() {
        return this.stats.totalScore;
    }

    hasCompleteRoles() {
        const requiredRoles = ['top', 'jungle', 'mid', 'support', 'ad'];
        return requiredRoles.every(role => this.stats.roleDistribution[role] > 0);
    }

    toJSON() {
        return {
            name: this.name,
            players: this.players.map(p => p.toJSON()),
            stats: this.stats
        };
    }
}
