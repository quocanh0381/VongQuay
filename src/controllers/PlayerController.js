/**
 * PlayerController - Controller cho quản lý người chơi
 */
import { Game } from '../models/Game.js';

export class PlayerController {
    constructor() {
        this.game = new Game();
    }

    render() {
        const mainContent = document.getElementById('main-content');
        if (mainContent) {
            mainContent.innerHTML = `
                <header>
                    <h1><i class="fas fa-users"></i> Quản lý Người chơi</h1>
                    <p>Thêm, sửa, xóa thông tin người chơi</p>
                </header>
                <div class="player-management">
                    <div class="add-player-section">
                        <h2>Thêm người chơi mới</h2>
                        <form id="addPlayerForm" class="player-form">
                            <div class="form-group">
                                <label for="playerName">Tên người chơi:</label>
                                <input type="text" id="playerName" required>
                            </div>
                            <div class="form-group">
                                <label for="playerRank">Rank:</label>
                                <select id="playerRank">
                                    <option value="A">A (40%)</option>
                                    <option value="B">B (30%)</option>
                                    <option value="C">C (20%)</option>
                                    <option value="D">D (10%)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="playerSkill">Mức độ hiểu game (%):</label>
                                <input type="number" id="playerSkill" min="0" max="100" value="50">
                            </div>
                            <div class="form-group">
                                <label for="playerMainRole">Đường chính:</label>
                                <select id="playerMainRole">
                                    <option value="top">Top</option>
                                    <option value="jungle">Rừng</option>
                                    <option value="mid">Mid</option>
                                    <option value="support">Support</option>
                                    <option value="ad">AD</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="playerSubRole">Đường phụ:</label>
                                <select id="playerSubRole">
                                    <option value="top">Top</option>
                                    <option value="jungle">Rừng</option>
                                    <option value="mid">Mid</option>
                                    <option value="support">Support</option>
                                    <option value="ad">AD</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Thêm người chơi</button>
                        </form>
                    </div>
                    
                    <div class="players-list-section">
                        <h2>Danh sách người chơi (<span id="playerCount">0</span>)</h2>
                        <div id="playersList" class="players-list"></div>
                    </div>
                </div>
            `;
            
            this.bindEvents();
            this.updatePlayersList();
        }
    }

    bindEvents() {
        // Add player form
        document.getElementById('addPlayerForm')?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.addPlayer();
        });

        // Navigation back to home
        document.querySelector('[data-route="/"]')?.addEventListener('click', () => {
            // Navigation will be handled by router
        });
    }

    addPlayer() {
        const name = document.getElementById('playerName').value.trim();
        const rank = document.getElementById('playerRank').value;
        const skill = parseInt(document.getElementById('playerSkill').value) || 50;
        const mainRole = document.getElementById('playerMainRole').value;
        const subRole = document.getElementById('playerSubRole').value;

        if (!name) {
            alert('Vui lòng nhập tên người chơi!');
            return;
        }

        try {
            const player = this.game.addPlayer({
                name,
                rank,
                skill,
                mainRole,
                subRole
            });

            this.updatePlayersList();
            document.getElementById('addPlayerForm').reset();
            
            console.log('Added player:', player);
        } catch (error) {
            alert(error.message);
        }
    }

    updatePlayer(playerId, data) {
        try {
            const player = this.game.updatePlayer(playerId, data);
            if (player) {
                this.updatePlayersList();
                console.log('Updated player:', player);
            }
        } catch (error) {
            console.error('Error updating player:', error);
        }
    }

    deletePlayer(playerId) {
        if (confirm('Bạn có chắc chắn muốn xóa người chơi này?')) {
            this.game.removePlayer(playerId);
            this.updatePlayersList();
        }
    }

    updatePlayersList() {
        const playersList = document.getElementById('playersList');
        const playerCount = document.getElementById('playerCount');
        
        if (!playersList || !playerCount) return;

        playerCount.textContent = this.game.players.length;
        
        if (this.game.players.length === 0) {
            playersList.innerHTML = '<p style="text-align: center; opacity: 0.7;">Chưa có người chơi nào</p>';
            return;
        }

        playersList.innerHTML = this.game.players.map(player => `
            <div class="player-card" id="player-${player.id}">
                <div class="player-info">
                    <h3>${player.name}</h3>
                    <div class="player-stats">
                        <span class="rank-badge rank-${player.rank.toLowerCase()}">Rank ${player.rank}</span>
                        <span class="role-badge role-${player.mainRole}">${this.getRoleDisplayName(player.mainRole)}</span>
                        <span class="skill-badge">Skill: ${player.skill}%</span>
                    </div>
                </div>
                <div class="player-actions">
                    <button class="btn btn-secondary" onclick="editPlayer(${player.id})">Sửa</button>
                    <button class="btn btn-danger" onclick="deletePlayer(${player.id})">Xóa</button>
                </div>
            </div>
        `).join('');
    }

    getRoleDisplayName(role) {
        const roleNames = {
            'top': 'Top',
            'jungle': 'Rừng',
            'mid': 'Mid',
            'support': 'Support',
            'ad': 'AD'
        };
        return roleNames[role] || role;
    }

    getPlayers() {
        return this.game.players;
    }

    resetPlayers() {
        this.game.reset();
        this.updatePlayersList();
    }
}

// Global functions for inline event handlers
window.editPlayer = function(playerId) {
    // Implementation for editing player
    console.log('Edit player:', playerId);
};

window.deletePlayer = function(playerId) {
    // Implementation for deleting player
    console.log('Delete player:', playerId);
};
