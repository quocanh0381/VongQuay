/**
 * Game Model - Quản lý logic game chính
 */
import { Player } from './Player.js';
import { Team } from './Team.js';

export class Game {
    constructor() {
        this.players = [];
        this.teams = { teamA: null, teamB: null };
        this.gameMode = 'skill'; // 'skill' hoặc 'random'
        this.maxPlayers = 10;
    }

    addPlayer(playerData) {
        if (this.players.length >= this.maxPlayers) {
            throw new Error('Đã đủ số lượng người chơi tối đa');
        }

        const player = new Player(playerData);
        this.players.push(player);
        return player;
    }

    removePlayer(playerId) {
        this.players = this.players.filter(p => p.id !== playerId);
    }

    updatePlayer(playerId, data) {
        const player = this.players.find(p => p.id === playerId);
        if (player) {
            player.update(data);
            return player;
        }
        return null;
    }

    setGameMode(mode) {
        this.gameMode = mode;
    }

    createTeams() {
        if (this.players.length < 2) {
            throw new Error('Cần ít nhất 2 người chơi');
        }

        if (this.gameMode === 'skill') {
            return this.createBalancedTeams();
        } else {
            return this.createRandomTeams();
        }
    }

    createBalancedTeams() {
        // Thuật toán cân bằng dựa trên skill
        const sortedPlayers = [...this.players].sort((a, b) => b.totalScore - a.totalScore);
        
        let teamA = [];
        let teamB = [];
        let teamAScore = 0;
        let teamBScore = 0;

        for (let i = 0; i < sortedPlayers.length; i++) {
            const player = sortedPlayers[i];
            
            if (teamAScore <= teamBScore) {
                teamA.push(player);
                teamAScore += player.totalScore;
            } else {
                teamB.push(player);
                teamBScore += player.totalScore;
            }
        }

        this.teams.teamA = new Team('Team A', teamA);
        this.teams.teamB = new Team('Team B', teamB);

        return this.teams;
    }

    createRandomTeams() {
        // Thuật toán random
        const shuffledPlayers = [...this.players];
        this.shuffleArray(shuffledPlayers);
        
        const midPoint = Math.floor(shuffledPlayers.length / 2);
        const teamA = shuffledPlayers.slice(0, midPoint);
        const teamB = shuffledPlayers.slice(midPoint);

        this.teams.teamA = new Team('Team A', teamA);
        this.teams.teamB = new Team('Team B', teamB);

        return this.teams;
    }

    shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }

    getBalanceInfo() {
        if (!this.teams.teamA || !this.teams.teamB) {
            return null;
        }

        const teamAScore = this.teams.teamA.getBalanceScore();
        const teamBScore = this.teams.teamB.getBalanceScore();
        const difference = Math.abs(teamAScore - teamBScore);
        const totalScore = teamAScore + teamBScore;
        const balancePercentage = totalScore > 0 ? (difference / totalScore) * 100 : 0;

        return {
            teamAScore,
            teamBScore,
            difference,
            balancePercentage,
            isBalanced: balancePercentage < 20
        };
    }

    reset() {
        this.players = [];
        this.teams = { teamA: null, teamB: null };
    }

    toJSON() {
        return {
            players: this.players.map(p => p.toJSON()),
            teams: {
                teamA: this.teams.teamA?.toJSON(),
                teamB: this.teams.teamB?.toJSON()
            },
            gameMode: this.gameMode
        };
    }
}
