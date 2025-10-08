class RandomWheelGame {
    constructor() {
        this.players = [];
        this.teams = { teamA: [], teamB: [] };
        this.rankColors = { A: '#ff6b6b', B: '#4ecdc4', C: '#45b7d1', D: '#96ceb4' };
        
        this.initializeGame();
        this.bindEvents();
    }

    initializeGame() {
        this.createPlayerInputs();
        this.updateRankBadges();
    }

    createPlayerInputs() {
        const playersGrid = document.getElementById('playersGrid');
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

    bindEvents() {
        document.getElementById('startSpinBtn').addEventListener('click', () => {
            this.startGame();
        });

        document.getElementById('spinBtn').addEventListener('click', () => {
            this.spinWheel();
        });

        document.getElementById('resetBtn').addEventListener('click', () => {
            this.resetGame();
        });
    }

    startGame() {
        if (!this.validateInputs()) {
            alert('Vui lòng nhập đầy đủ tên cho tất cả người chơi!');
            return;
        }

        this.collectPlayers();
        this.createWheel();
        this.showWheelSection();
    }

    validateInputs() {
        const nameInputs = document.querySelectorAll('input[id*="Name"]');
        return Array.from(nameInputs).every(input => input.value.trim() !== '');
    }

    collectPlayers() {
        this.players = [];
        for (let i = 1; i <= 10; i++) {
            const name = document.getElementById(`player${i}Name`).value.trim();
            const rank = document.getElementById(`player${i}Rank`).value;
            const skill = parseInt(document.getElementById(`player${i}Skill`).value) || 50;
            const mainRole = document.getElementById(`player${i}MainRole`).value;
            const subRole = document.getElementById(`player${i}SubRole`).value;
            this.players.push({ name, rank, skill, mainRole, subRole, id: i });
        }
    }

    createWheel() {
        const wheelSegments = document.getElementById('wheelSegments');
        wheelSegments.innerHTML = '';

        this.players.forEach((player, index) => {
            const segment = document.createElement('div');
            segment.className = 'wheel-segment';
            segment.style.transform = `rotate(${index * 36}deg)`;
            segment.style.background = this.rankColors[player.rank];
            segment.innerHTML = `
                <div style="transform: rotate(${-index * 36}deg); text-align: center;">
                    <div style="font-size: 0.9rem; font-weight: bold;">${player.name}</div>
                    <div style="font-size: 0.7rem; opacity: 0.8;">Rank ${player.rank}</div>
                </div>
            `;
            wheelSegments.appendChild(segment);
        });
    }

    showWheelSection() {
        document.querySelector('.input-section').style.display = 'none';
        document.getElementById('wheelSection').style.display = 'block';
        document.getElementById('resultSection').style.display = 'none';
    }

    spinWheel() {
        const wheel = document.getElementById('wheel');
        const spinBtn = document.getElementById('spinBtn');
        
        spinBtn.disabled = true;
        spinBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang quay...';
        
        // Random rotation (5-10 full rotations + random angle)
        const randomRotations = 5 + Math.random() * 5;
        const randomAngle = Math.random() * 360;
        const totalRotation = randomRotations * 360 + randomAngle;
        
        wheel.style.transform = `rotate(${totalRotation}deg)`;
        wheel.classList.add('spinning');
        
        setTimeout(() => {
            this.distributeTeamsRandom();
            this.showResults();
            spinBtn.disabled = false;
            spinBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Quay lại';
        }, 4000);
    }

    distributeTeamsRandom() {
        // Thuật toán phân chia hoàn toàn ngẫu nhiên
        this.teams = { teamA: [], teamB: [] };
        
        // Tạo bản sao của players và xáo trộn ngẫu nhiên
        const shuffledPlayers = [...this.players];
        this.shuffleArray(shuffledPlayers);
        
        // Chia đều cho 2 team
        const midPoint = Math.floor(shuffledPlayers.length / 2);
        this.teams.teamA = shuffledPlayers.slice(0, midPoint);
        this.teams.teamB = shuffledPlayers.slice(midPoint);
        
        // Đảm bảo mỗi team có đủ 5 đường (role)
        this.ensureCompleteRolesRandom();
        
        console.log('Phân chia random hoàn tất');
        console.log('Team A:', this.teams.teamA.map(p => p.name));
        console.log('Team B:', this.teams.teamB.map(p => p.name));
    }

    shuffleArray(array) {
        // Thuật toán Fisher-Yates shuffle
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }

    ensureCompleteRolesRandom() {
        // Đảm bảo mỗi team có đủ 5 đường (mỗi đường 1 người)
        const requiredRoles = ['top', 'jungle', 'mid', 'support', 'ad'];
        
        // Phân bổ lại roles cho cả 2 team một cách ngẫu nhiên
        this.redistributeRolesRandom(requiredRoles);
    }

    redistributeRolesRandom(requiredRoles) {
        console.log('Bắt đầu phân bổ lại roles random...');
        
        // Tạo danh sách tất cả players
        const allPlayers = [...this.teams.teamA, ...this.teams.teamB];
        
        // Xáo trộn ngẫu nhiên danh sách players
        this.shuffleArray(allPlayers);
        
        // Phân bổ roles ngẫu nhiên
        this.assignRolesRandom(allPlayers, requiredRoles);
        
        // Kiểm tra và hiển thị kết quả
        this.validateTeamRoles(this.teams.teamA, 'A');
        this.validateTeamRoles(this.teams.teamB, 'B');
    }

    assignRolesRandom(allPlayers, requiredRoles) {
        // Tạo danh sách players có thể chơi mỗi role
        const roleCandidates = {};
        requiredRoles.forEach(role => {
            roleCandidates[role] = [];
        });
        
        // Phân loại players theo khả năng role
        allPlayers.forEach(player => {
            // Thêm vào danh sách main role
            if (roleCandidates[player.mainRole]) {
                roleCandidates[player.mainRole].push({
                    player: player,
                    isMainRole: true,
                    priority: 1
                });
            }
            
            // Thêm vào danh sách sub role (nếu khác main role)
            if (player.subRole !== player.mainRole && roleCandidates[player.subRole]) {
                roleCandidates[player.subRole].push({
                    player: player,
                    isMainRole: false,
                    priority: 2
                });
            }
        });
        
        // Xáo trộn ngẫu nhiên danh sách candidates cho mỗi role
        requiredRoles.forEach(role => {
            this.shuffleArray(roleCandidates[role]);
        });
        
        // Phân bổ roles cho 2 team một cách ngẫu nhiên
        const teamA = [];
        const teamB = [];
        const assignedPlayers = new Set();
        
        // Vòng 1: Phân bổ main roles ngẫu nhiên
        this.assignMainRolesRandom(roleCandidates, teamA, teamB, assignedPlayers);
        
        // Vòng 2: Phân bổ sub roles cho các vị trí còn thiếu
        this.assignSubRolesRandom(roleCandidates, teamA, teamB, assignedPlayers);
        
        // Cập nhật lại teams
        this.teams.teamA = teamA;
        this.teams.teamB = teamB;
    }

    assignMainRolesRandom(roleCandidates, teamA, teamB, assignedPlayers) {
        const requiredRoles = ['top', 'jungle', 'mid', 'support', 'ad'];
        
        requiredRoles.forEach(role => {
            // Tìm player chưa được assign và có main role là role này
            const candidates = roleCandidates[role].filter(candidate => 
                !assignedPlayers.has(candidate.player.id) && candidate.isMainRole
            );
            
            if (candidates.length > 0) {
                // Chọn player ngẫu nhiên từ danh sách candidates
                const randomIndex = Math.floor(Math.random() * candidates.length);
                const selectedCandidate = candidates[randomIndex];
                const player = selectedCandidate.player;
                
                // Assign vào team ngẫu nhiên
                if (Math.random() < 0.5) {
                    teamA.push(player);
                } else {
                    teamB.push(player);
                }
                
                assignedPlayers.add(player.id);
                console.log(`${player.name} được assign random vào team ${teamA.length <= teamB.length ? 'A' : 'B'} với role ${role} (main)`);
            }
        });
    }

    assignSubRolesRandom(roleCandidates, teamA, teamB, assignedPlayers) {
        const requiredRoles = ['top', 'jungle', 'mid', 'support', 'ad'];
        
        // Kiểm tra team nào còn thiếu role
        const teamAMissingRoles = this.getMissingRoles(teamA, requiredRoles);
        const teamBMissingRoles = this.getMissingRoles(teamB, requiredRoles);
        
        // Phân bổ cho team A
        teamAMissingRoles.forEach(role => {
            const candidates = roleCandidates[role].filter(candidate => 
                !assignedPlayers.has(candidate.player.id)
            );
            
            if (candidates.length > 0) {
                const randomIndex = Math.floor(Math.random() * candidates.length);
                const selectedCandidate = candidates[randomIndex];
                const player = selectedCandidate.player;
                
                // Hoán đổi role nếu cần
                if (!selectedCandidate.isMainRole) {
                    const temp = player.mainRole;
                    player.mainRole = player.subRole;
                    player.subRole = temp;
                    console.log(`${player.name} chuyển từ ${player.subRole} sang ${player.mainRole} (sub role)`);
                }
                
                teamA.push(player);
                assignedPlayers.add(player.id);
            }
        });
        
        // Phân bổ cho team B
        teamBMissingRoles.forEach(role => {
            const candidates = roleCandidates[role].filter(candidate => 
                !assignedPlayers.has(candidate.player.id)
            );
            
            if (candidates.length > 0) {
                const randomIndex = Math.floor(Math.random() * candidates.length);
                const selectedCandidate = candidates[randomIndex];
                const player = selectedCandidate.player;
                
                // Hoán đổi role nếu cần
                if (!selectedCandidate.isMainRole) {
                    const temp = player.mainRole;
                    player.mainRole = player.subRole;
                    player.subRole = temp;
                    console.log(`${player.name} chuyển từ ${player.subRole} sang ${player.mainRole} (sub role)`);
                }
                
                teamB.push(player);
                assignedPlayers.add(player.id);
            }
        });
    }

    getMissingRoles(team, requiredRoles) {
        const currentRoles = {};
        requiredRoles.forEach(role => currentRoles[role] = 0);
        
        team.forEach(player => {
            currentRoles[player.mainRole]++;
        });
        
        return requiredRoles.filter(role => currentRoles[role] === 0);
    }

    validateTeamRoles(team, teamName) {
        const requiredRoles = ['top', 'jungle', 'mid', 'support', 'ad'];
        const currentRoles = {};
        requiredRoles.forEach(role => currentRoles[role] = 0);
        
        team.forEach(player => {
            currentRoles[player.mainRole]++;
        });
        
        const missingRoles = requiredRoles.filter(role => currentRoles[role] === 0);
        const duplicateRoles = requiredRoles.filter(role => currentRoles[role] > 1);
        
        if (missingRoles.length === 0 && duplicateRoles.length === 0) {
            console.log(`✅ Team ${teamName} đã có đủ 5 đường: ${requiredRoles.join(', ')}`);
        } else {
            if (missingRoles.length > 0) {
                console.warn(`❌ Team ${teamName} thiếu roles: ${missingRoles.join(', ')}`);
            }
            if (duplicateRoles.length > 0) {
                console.warn(`❌ Team ${teamName} có roles trùng lặp: ${duplicateRoles.join(', ')}`);
            }
        }
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

    calculateRoleStats(team) {
        const roleStats = { top: 0, jungle: 0, mid: 0, support: 0, ad: 0 };
        
        team.forEach(player => {
            roleStats[player.mainRole]++;
        });
        
        return roleStats;
    }

    calculateTeamStats(team) {
        const stats = { A: 0, B: 0, C: 0, D: 0, rankTotal: 0, skillTotal: 0, combinedTotal: 0 };
        const rankDistribution = { A: 40, B: 30, C: 20, D: 10 };
        
        team.forEach(player => {
            stats[player.rank]++;
            stats.rankTotal += rankDistribution[player.rank];
            stats.skillTotal += player.skill;
        });
        
        // Tính tổng điểm kết hợp: 50% rank + 50% skill
        stats.combinedTotal = (stats.rankTotal * 0.5) + (stats.skillTotal * 0.5);
        stats.total = stats.combinedTotal;
        
        return stats;
    }

    showResults() {
        this.displayTeamResults();
        this.displayRandomInfo();
        document.getElementById('resultSection').style.display = 'block';
        document.getElementById('resultSection').classList.add('fade-in');
    }

    displayTeamResults() {
        this.displayTeam('teamA', this.teams.teamA);
        this.displayTeam('teamB', this.teams.teamB);
    }

    displayRandomInfo() {
        const teamAStats = this.calculateTeamStats(this.teams.teamA);
        const teamBStats = this.calculateTeamStats(this.teams.teamB);
        const difference = Math.abs(teamAStats.combinedTotal - teamBStats.combinedTotal);
        
        // Tạo hoặc cập nhật thông tin random
        let randomInfo = document.getElementById('randomInfo');
        if (!randomInfo) {
            randomInfo = document.createElement('div');
            randomInfo.id = 'randomInfo';
            randomInfo.className = 'random-info';
            document.querySelector('.teams-container').parentNode.insertBefore(randomInfo, document.querySelector('.teams-container'));
        }
        
        const randomClass = 'random';
        const randomText = 'Phân chia hoàn toàn ngẫu nhiên!';
        
        randomInfo.innerHTML = `
            <div class="random-card ${randomClass}">
                <h3><i class="fas fa-random"></i> Thông tin phân chia random</h3>
                <div class="random-stats">
                    <div class="random-item">
                        <span>Phương thức:</span>
                        <span class="stat-value">${randomText}</span>
                    </div>
                    <div class="random-item">
                        <span>Team A (Tổng):</span>
                        <span class="stat-value">${Math.round(teamAStats.combinedTotal)}%</span>
                    </div>
                    <div class="random-item">
                        <span>Team B (Tổng):</span>
                        <span class="stat-value">${Math.round(teamBStats.combinedTotal)}%</span>
                    </div>
                    <div class="random-item">
                        <span>Chênh lệch:</span>
                        <span class="stat-value">${Math.round(difference)}%</span>
                    </div>
                    <div class="random-item">
                        <span>Đặc điểm:</span>
                        <span class="stat-value">Vui vẻ & Thử thách</span>
                    </div>
                </div>
            </div>
        `;
    }

    displayTeam(teamId, team) {
        const teamElement = document.getElementById(teamId);
        const statsElement = document.getElementById(teamId + 'Stats');
        
        // Hiển thị thành viên
        teamElement.innerHTML = '';
        team.forEach(player => {
            const memberCard = document.createElement('div');
            memberCard.className = 'member-card';
            memberCard.innerHTML = `
                <div class="member-info">
                    <span class="member-name">${player.name}</span>
                    <div class="member-badges">
                        <span class="rank-badge rank-${player.rank.toLowerCase()}">Rank ${player.rank}</span>
                        <span class="role-badge role-${player.mainRole}">${this.getRoleDisplayName(player.mainRole)}</span>
                    </div>
                </div>
                <span class="skill-badge">Skill: ${player.skill}%</span>
            `;
            teamElement.appendChild(memberCard);
        });
        
        // Hiển thị thống kê
        const stats = this.calculateTeamStats(team);
        const roleStats = this.calculateRoleStats(team);
        statsElement.innerHTML = `
            <h4>Thống kê team</h4>
            <div class="role-section">
                <h5>Phân bố đường:</h5>
                <div class="role-stats">
                    <div class="role-item">
                        <span class="role-badge role-top">Top</span>
                        <span class="stat-value">${roleStats.top}</span>
                    </div>
                    <div class="role-item">
                        <span class="role-badge role-jungle">Rừng</span>
                        <span class="stat-value">${roleStats.jungle}</span>
                    </div>
                    <div class="role-item">
                        <span class="role-badge role-mid">Mid</span>
                        <span class="stat-value">${roleStats.mid}</span>
                    </div>
                    <div class="role-item">
                        <span class="role-badge role-support">Support</span>
                        <span class="stat-value">${roleStats.support}</span>
                    </div>
                    <div class="role-item">
                        <span class="role-badge role-ad">AD</span>
                        <span class="stat-value">${roleStats.ad}</span>
                    </div>
                </div>
            </div>
            <div class="rank-section">
                <h5>Phân bố rank:</h5>
                <div class="stat-item">
                    <span>Rank A:</span>
                    <span class="stat-value">${stats.A} người</span>
                </div>
                <div class="stat-item">
                    <span>Rank B:</span>
                    <span class="stat-value">${stats.B} người</span>
                </div>
                <div class="stat-item">
                    <span>Rank C:</span>
                    <span class="stat-value">${stats.C} người</span>
                </div>
                <div class="stat-item">
                    <span>Rank D:</span>
                    <span class="stat-value">${stats.D} người</span>
                </div>
            </div>
            <div class="score-section" style="border-top: 1px solid #e9ecef; padding-top: 10px; margin-top: 10px;">
                <h5>Điểm số:</h5>
                <div class="stat-item">
                    <span><strong>Tổng điểm Rank:</strong></span>
                    <span class="stat-value"><strong>${stats.rankTotal}%</strong></span>
                </div>
                <div class="stat-item">
                    <span><strong>Tổng điểm Skill:</strong></span>
                    <span class="stat-value"><strong>${stats.skillTotal}%</strong></span>
                </div>
                <div class="stat-item">
                    <span><strong>Trung bình Skill:</strong></span>
                    <span class="stat-value"><strong>${Math.round(stats.skillTotal / 5)}%</strong></span>
                </div>
                <div class="stat-item">
                    <span><strong>Tổng điểm kết hợp:</strong></span>
                    <span class="stat-value"><strong>${Math.round(stats.combinedTotal)}%</strong></span>
                </div>
            </div>
        `;
    }

    resetGame() {
        // Reset wheel rotation
        const wheel = document.getElementById('wheel');
        wheel.style.transform = 'rotate(0deg)';
        wheel.classList.remove('spinning');
        
        // Reset spin button
        const spinBtn = document.getElementById('spinBtn');
        spinBtn.disabled = false;
        spinBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Quay Random';
        
        // Show input section
        document.querySelector('.input-section').style.display = 'block';
        document.getElementById('wheelSection').style.display = 'none';
        document.getElementById('resultSection').style.display = 'none';
        
        // Reset form
        document.querySelectorAll('input[id*="Name"]').forEach(input => input.value = '');
        document.querySelectorAll('select[id*="Rank"]').forEach(select => select.value = 'A');
        document.querySelectorAll('input[id*="Skill"]').forEach(input => input.value = '50');
        document.querySelectorAll('select[id*="MainRole"]').forEach(select => select.value = 'top');
        document.querySelectorAll('select[id*="SubRole"]').forEach(select => select.value = 'top');
        document.querySelectorAll('.rank-badge').forEach(badge => badge.remove());
        
        // Reset data
        this.players = [];
        this.teams = { teamA: [], teamB: [] };
        
        // Xóa thông tin random
        const randomInfo = document.getElementById('randomInfo');
        if (randomInfo) {
            randomInfo.remove();
        }
    }
}

// Khởi tạo game khi trang load
document.addEventListener('DOMContentLoaded', () => {
    new RandomWheelGame();
});

// Thêm hiệu ứng loading cho các button
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            if (!this.disabled) {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            }
        });
    });
});
