/**
 * PlayerFormView - View cho form nhập thông tin người chơi
 */
export class PlayerFormView {
    render() {
        return `
            <div class="input-section">
                <h2><i class="fas fa-users"></i> Nhập thông tin người chơi</h2>
                <div class="players-grid" id="playersGrid">
                    <!-- 10 ô nhập thông tin người chơi sẽ được tạo bằng JavaScript -->
                </div>
                <button class="btn btn-primary" id="startSpinBtn">
                    <i class="fas fa-play"></i> Bắt đầu quay
                </button>
            </div>
        `;
    }

    createPlayerInputs() {
        const playersGrid = document.getElementById('playersGrid');
        if (!playersGrid) return;

        playersGrid.innerHTML = '';

        for (let i = 1; i <= 10; i++) {
            const playerDiv = document.createElement('div');
            playerDiv.className = 'player-input';
            playerDiv.innerHTML = `
                <h3>Người chơi ${i}</h3>
                <div class="input-group">
                    <label for="player${i}Name">Tên:</label>
                    <input type="text" id="player${i}Name" placeholder="Nhập tên người chơi ${i}">
                </div>
                <div class="input-group">
                    <label for="player${i}Rank">Rank:</label>
                    <select id="player${i}Rank">
                        <option value="A">A (40%)</option>
                        <option value="B">B (30%)</option>
                        <option value="C">C (20%)</option>
                        <option value="D">D (10%)</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="player${i}Skill">Mức độ hiểu game (%):</label>
                    <input type="number" id="player${i}Skill" min="0" max="100" value="50" placeholder="0-100">
                </div>
                <div class="input-group">
                    <label for="player${i}MainRole">Đường chính:</label>
                    <select id="player${i}MainRole">
                        <option value="top">Top</option>
                        <option value="jungle">Rừng</option>
                        <option value="mid">Mid</option>
                        <option value="support">Support</option>
                        <option value="ad">AD</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="player${i}SubRole">Đường phụ:</label>
                    <select id="player${i}SubRole">
                        <option value="top">Top</option>
                        <option value="jungle">Rừng</option>
                        <option value="mid">Mid</option>
                        <option value="support">Support</option>
                        <option value="ad">AD</option>
                    </select>
                </div>
            `;
            playersGrid.appendChild(playerDiv);
        }
    }

    updateRankBadges() {
        const rankSelects = document.querySelectorAll('select[id*="Rank"]');
        rankSelects.forEach(select => {
            select.addEventListener('change', () => {
                const rank = select.value;
                const playerDiv = select.closest('.player-input');
                let badge = playerDiv.querySelector('.rank-badge');
                
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'rank-badge';
                    playerDiv.appendChild(badge);
                }
                
                badge.className = `rank-badge rank-${rank.toLowerCase()}`;
                badge.textContent = `Rank ${rank}`;
            });
        });
    }

    collectPlayers() {
        const players = [];
        for (let i = 1; i <= 10; i++) {
            const name = document.getElementById(`player${i}Name`)?.value?.trim();
            if (!name) continue;

            const rank = document.getElementById(`player${i}Rank`)?.value || 'A';
            const skill = parseInt(document.getElementById(`player${i}Skill`)?.value) || 50;
            const mainRole = document.getElementById(`player${i}MainRole`)?.value || 'top';
            const subRole = document.getElementById(`player${i}SubRole`)?.value || 'top';

            players.push({
                name,
                rank,
                skill,
                mainRole,
                subRole,
                id: i
            });
        }
        return players;
    }

    validateInputs() {
        const nameInputs = document.querySelectorAll('input[id*="Name"]');
        return Array.from(nameInputs).some(input => input.value.trim() !== '');
    }

    resetForm() {
        document.querySelectorAll('input[id*="Name"]').forEach(input => input.value = '');
        document.querySelectorAll('select[id*="Rank"]').forEach(select => select.value = 'A');
        document.querySelectorAll('input[id*="Skill"]').forEach(input => input.value = '50');
        document.querySelectorAll('select[id*="MainRole"]').forEach(select => select.value = 'top');
        document.querySelectorAll('select[id*="SubRole"]').forEach(select => select.value = 'top');
        document.querySelectorAll('.rank-badge').forEach(badge => badge.remove());
    }
}
