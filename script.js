class WheelGame {
    constructor() {
        this.players = [];
        this.teams = { teamA: [], teamB: [] };
        this.rankDistribution = { A: 40, B: 30, C: 20, D: 10 };
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
            this.distributeTeams();
            this.showResults();
            spinBtn.disabled = false;
            spinBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Quay lại';
        }, 4000);
    }

    distributeTeams() {
        // Thuật toán phân chia team cân bằng với đảm bảo đủ 5 đường
        this.teams = { teamA: [], teamB: [] };
        
        // Sắp xếp players theo rank (A -> D)
        const sortedPlayers = [...this.players].sort((a, b) => {
            const rankOrder = { A: 4, B: 3, C: 2, D: 1 };
            return rankOrder[b.rank] - rankOrder[a.rank];
        });
        
        // Khởi tạo 2 team rỗng
        this.teams.teamA = [];
        this.teams.teamB = [];
        
        // Phân chia cân bằng với đảm bảo đủ 5 đường
        this.balancedDistributionWithRoles(sortedPlayers);
    }

    balancedDistributionWithRoles(players) {
        // Thuật toán phân chia cân bằng với đảm bảo đủ 5 đường
        const maxDifference = 20;
        
        // Phân loại players theo rank
        const rankAPlayers = players.filter(p => p.rank === 'A');
        const otherPlayers = players.filter(p => p.rank !== 'A');
        
        // Xử lý logic đặc biệt cho rank A
        const { teamA, teamB } = this.distributeRankA(rankAPlayers, otherPlayers);
        
        // Đảm bảo mỗi team có đủ 5 đường
        this.ensureCompleteRoles(teamA, teamB);
        
        // Kiểm tra kết quả
        const teamAStats = this.calculateTeamStats(teamA);
        const teamBStats = this.calculateTeamStats(teamB);
        const difference = Math.abs(teamAStats.combinedTotal - teamBStats.combinedTotal);
        
        console.log(`Phân chia rank A: ${rankAPlayers.length} người`);
        console.log(`Chênh lệch sau phân chia: ${Math.round(difference)}%`);
        
        // Nếu chênh lệch > 20%, thực hiện trao đổi thông minh với roles
        if (difference > 20) {
            this.smartSwapWithRoles();
        }
        
        // Kiểm tra lại sau khi trao đổi
        const finalTeamAStats = this.calculateTeamStats(this.teams.teamA);
        const finalTeamBStats = this.calculateTeamStats(this.teams.teamB);
        const finalDifference = Math.abs(finalTeamAStats.combinedTotal - finalTeamBStats.combinedTotal);
        
        if (finalDifference > maxDifference) {
            console.warn(`Sau khi trao đổi, chênh lệch vẫn là ${Math.round(finalDifference)}%, vượt quá giới hạn ${maxDifference}%`);
        }
    }

    balancedDistribution(players) {
        // Thuật toán phân chia cân bằng với logic đặc biệt cho rank A (giữ lại để tương thích)
        const maxDifference = 20; // Giới hạn chênh lệch tối đa 20%
        
        // Phân loại players theo rank
        const rankAPlayers = players.filter(p => p.rank === 'A');
        const otherPlayers = players.filter(p => p.rank !== 'A');
        
        // Xử lý logic đặc biệt cho rank A
        const { teamA, teamB } = this.distributeRankA(rankAPlayers, otherPlayers);
        
        // Kiểm tra kết quả
        const teamAStats = this.calculateTeamStats(teamA);
        const teamBStats = this.calculateTeamStats(teamB);
        const difference = Math.abs(teamAStats.combinedTotal - teamBStats.combinedTotal);
        
        console.log(`Phân chia rank A: ${rankAPlayers.length} người`);
        console.log(`Chênh lệch sau phân chia: ${Math.round(difference)}%`);
        
        // Nếu chênh lệch > 20%, thực hiện trao đổi thông minh
        if (difference > 20) {
            this.smartSwap();
        }
        
        // Kiểm tra lại sau khi trao đổi
        const finalTeamAStats = this.calculateTeamStats(this.teams.teamA);
        const finalTeamBStats = this.calculateTeamStats(this.teams.teamB);
        const finalDifference = Math.abs(finalTeamAStats.combinedTotal - finalTeamBStats.combinedTotal);
        
        if (finalDifference > maxDifference) {
            console.warn(`Sau khi trao đổi, chênh lệch vẫn là ${Math.round(finalDifference)}%, vượt quá giới hạn ${maxDifference}%`);
        }
    }
    
    distributeRankA(rankAPlayers, otherPlayers) {
        // Khởi tạo 2 team
        let teamA = [];
        let teamB = [];
        
        // Xử lý logic đặc biệt cho rank A
        if (rankAPlayers.length % 2 === 0) {
            // Số lượng rank A chẵn: chia đều cho 2 team
            const halfA = rankAPlayers.length / 2;
            teamA = [...rankAPlayers.slice(0, halfA)];
            teamB = [...rankAPlayers.slice(halfA)];
            console.log(`Rank A chẵn (${rankAPlayers.length}): chia đều cho 2 team`);
        } else {
            // Số lượng rank A lẻ: chia đều + thêm rank B skill cao nhất
            const halfA = Math.floor(rankAPlayers.length / 2);
            teamA = [...rankAPlayers.slice(0, halfA)];
            teamB = [...rankAPlayers.slice(halfA, halfA * 2)];
            
            // Tìm rank B có skill cao nhất
            const rankBPlayers = otherPlayers.filter(p => p.rank === 'B');
            if (rankBPlayers.length > 0) {
                const highestSkillB = rankBPlayers.reduce((max, current) => 
                    current.skill > max.skill ? current : max
                );
                
                // Thêm rank B skill cao nhất vào team có ít rank A hơn
                if (teamA.length < teamB.length) {
                    teamA.push(highestSkillB);
                } else {
                    teamB.push(highestSkillB);
                }
                
                // Xóa rank B đã chọn khỏi danh sách otherPlayers
                const index = otherPlayers.indexOf(highestSkillB);
                otherPlayers.splice(index, 1);
                
                console.log(`Rank A lẻ (${rankAPlayers.length}): chia đều + thêm rank B skill cao nhất (${highestSkillB.name} - ${highestSkillB.skill}%)`);
            }
        }
        
        // Phân chia các rank còn lại (B, C, D) bằng thuật toán cân bằng
        this.distributeRemainingPlayers(otherPlayers, teamA, teamB);
        
        // Cập nhật teams
        this.teams.teamA = teamA;
        this.teams.teamB = teamB;
        
        return { teamA, teamB };
    }
    
    distributeRemainingPlayers(remainingPlayers, teamA, teamB) {
        // Sắp xếp remaining players theo tổng điểm (rank + skill)
        const sortedPlayers = remainingPlayers.sort((a, b) => {
            const scoreA = this.rankDistribution[a.rank] + a.skill;
            const scoreB = this.rankDistribution[b.rank] + b.skill;
            return scoreB - scoreA;
        });
        
        // Phân chia luân phiên để cân bằng
        for (let i = 0; i < sortedPlayers.length; i++) {
            if (i % 2 === 0) {
                teamA.push(sortedPlayers[i]);
            } else {
                teamB.push(sortedPlayers[i]);
            }
        }
    }

    ensureCompleteRoles(teamA, teamB) {
        // Đảm bảo mỗi team có đủ 5 đường (mỗi đường 1 người)
        const requiredRoles = ['top', 'jungle', 'mid', 'support', 'ad'];
        
        // Phân bổ lại roles cho cả 2 team
        this.redistributeRoles(teamA, teamB, requiredRoles);
    }

    redistributeRoles(teamA, teamB, requiredRoles) {
        // Thuật toán phân bổ lại roles để mỗi team có đủ 5 đường
        console.log('Bắt đầu phân bổ lại roles...');
        
        // Bước 1: Thống kê tất cả players và khả năng role của họ
        const allPlayers = [...teamA, ...teamB];
        
        // Bước 2: Phân bổ ưu tiên theo main role trước
        this.assignRolesByPriority(allPlayers, requiredRoles);
        
        // Bước 3: Kiểm tra và hiển thị kết quả
        this.validateTeamRoles(teamA, 'A');
        this.validateTeamRoles(teamB, 'B');
    }

    assignRolesByPriority(allPlayers, requiredRoles) {
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
        
        // Sắp xếp theo độ ưu tiên (main role trước)
        requiredRoles.forEach(role => {
            roleCandidates[role].sort((a, b) => {
                if (a.priority !== b.priority) {
                    return a.priority - b.priority;
                }
                // Nếu cùng priority, sắp xếp theo skill
                return b.player.skill - a.player.skill;
            });
        });
        
        // Phân bổ roles cho 2 team
        const teamA = [];
        const teamB = [];
        const assignedPlayers = new Set();
        
        // Vòng 1: Phân bổ main roles
        this.assignMainRoles(roleCandidates, teamA, teamB, assignedPlayers);
        
        // Vòng 2: Phân bổ sub roles cho các vị trí còn thiếu
        this.assignSubRoles(roleCandidates, teamA, teamB, assignedPlayers);
        
        // Cập nhật lại teams
        this.teams.teamA = teamA;
        this.teams.teamB = teamB;
    }

    assignMainRoles(roleCandidates, teamA, teamB, assignedPlayers) {
        const requiredRoles = ['top', 'jungle', 'mid', 'support', 'ad'];
        
        requiredRoles.forEach(role => {
            // Tìm player chưa được assign và có main role là role này
            const candidates = roleCandidates[role].filter(candidate => 
                !assignedPlayers.has(candidate.player.id) && candidate.isMainRole
            );
            
            if (candidates.length > 0) {
                // Chọn player tốt nhất
                const bestCandidate = candidates[0];
                const player = bestCandidate.player;
                
                // Assign vào team có ít người hơn
                if (teamA.length < teamB.length) {
                    teamA.push(player);
                } else {
                    teamB.push(player);
                }
                
                assignedPlayers.add(player.id);
                console.log(`${player.name} được assign vào team ${teamA.length <= teamB.length ? 'A' : 'B'} với role ${role} (main)`);
            }
        });
    }

    assignSubRoles(roleCandidates, teamA, teamB, assignedPlayers) {
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
                const bestCandidate = candidates[0];
                const player = bestCandidate.player;
                
                // Hoán đổi role nếu cần
                if (!bestCandidate.isMainRole) {
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
                const bestCandidate = candidates[0];
                const player = bestCandidate.player;
                
                // Hoán đổi role nếu cần
                if (!bestCandidate.isMainRole) {
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

    smartSwapWithRoles() {
        // Thuật toán trao đổi thông minh với xem xét roles (mỗi team phải có đủ 5 đường)
        const teamA = this.teams.teamA;
        const teamB = this.teams.teamB;
        const maxDifference = 20;
        let improved = true;
        let maxSwaps = 50; // Giảm số lần trao đổi vì logic phức tạp hơn
        
        // Đếm số rank A trong mỗi team để không làm mất cân bằng
        const teamARankACount = teamA.filter(p => p.rank === 'A').length;
        const teamBRankACount = teamB.filter(p => p.rank === 'A').length;
        
        while (improved && maxSwaps > 0) {
            improved = false;
            const currentDiff = Math.abs(this.calculateTeamStats(teamA).combinedTotal - this.calculateTeamStats(teamB).combinedTotal);
            
            if (currentDiff <= maxDifference) {
                break;
            }
            
            // Thử trao đổi từng cặp (chỉ trao đổi rank B, C, D)
            for (let i = 0; i < teamA.length; i++) {
                for (let j = 0; j < teamB.length; j++) {
                    const playerA = teamA[i];
                    const playerB = teamB[j];
                    
                    // Không trao đổi rank A
                    if (playerA.rank === 'A' || playerB.rank === 'A') {
                        continue;
                    }
                    
                    // Thử trao đổi
                    teamA[i] = playerB;
                    teamB[j] = playerA;
                    
                    // Kiểm tra rank A không bị ảnh hưởng
                    const newTeamARankACount = teamA.filter(p => p.rank === 'A').length;
                    const newTeamBRankACount = teamB.filter(p => p.rank === 'A').length;
                    
                    if (newTeamARankACount === teamARankACount && newTeamBRankACount === teamBRankACount) {
                        // Kiểm tra roles vẫn đúng sau khi trao đổi (mỗi team có đủ 5 đường)
                        const rolesValid = this.checkTeamRolesValid(teamA) && this.checkTeamRolesValid(teamB);
                        
                        if (rolesValid) {
                            const newStatsA = this.calculateTeamStats(teamA);
                            const newStatsB = this.calculateTeamStats(teamB);
                            const newDiff = Math.abs(newStatsA.combinedTotal - newStatsB.combinedTotal);
                            
                            if (newDiff < currentDiff || (newDiff <= maxDifference && newDiff <= currentDiff + 5)) {
                                improved = true;
                                maxSwaps--;
                                console.log(`Trao đổi ${playerA.name} và ${playerB.name}, chênh lệch: ${currentDiff}% -> ${Math.round(newDiff)}%`);
                                break;
                            }
                        }
                        
                        // Hoàn tác nếu không cải thiện hoặc roles không hợp lệ
                        teamA[i] = playerA;
                        teamB[j] = playerB;
                    } else {
                        // Hoàn tác nếu rank A bị ảnh hưởng
                        teamA[i] = playerA;
                        teamB[j] = playerB;
                    }
                }
                if (improved) break;
            }
        }
        
        // Kiểm tra kết quả cuối cùng
        const finalDiff = Math.abs(this.calculateTeamStats(teamA).combinedTotal - this.calculateTeamStats(teamB).combinedTotal);
        if (finalDiff > maxDifference) {
            console.warn(`Sau khi trao đổi thông minh với roles, chênh lệch vẫn là ${Math.round(finalDiff)}%, vượt quá giới hạn ${maxDifference}%`);
        }
        
        // Validate lại roles sau khi trao đổi
        this.validateTeamRoles(teamA, 'A');
        this.validateTeamRoles(teamB, 'B');
    }

    checkTeamRolesValid(team) {
        // Kiểm tra team có đủ 5 đường không (mỗi đường 1 người)
        const requiredRoles = ['top', 'jungle', 'mid', 'support', 'ad'];
        const currentRoles = {};
        requiredRoles.forEach(role => currentRoles[role] = 0);
        
        team.forEach(player => {
            currentRoles[player.mainRole]++;
        });
        
        // Kiểm tra mỗi role có đúng 1 người
        return requiredRoles.every(role => currentRoles[role] === 1);
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
        
        team.forEach(player => {
            stats[player.rank]++;
            stats.rankTotal += this.rankDistribution[player.rank];
            stats.skillTotal += player.skill;
        });
        
        // Tính tổng điểm kết hợp: 50% rank + 50% skill
        stats.combinedTotal = (stats.rankTotal * 0.5) + (stats.skillTotal * 0.5);
        stats.total = stats.combinedTotal; // Giữ lại thuộc tính total để tương thích với code cũ
        
        return stats;
    }

    smartSwap() {
        // Thuật toán trao đổi thông minh với giới hạn chênh lệch tối đa 20%
        const teamA = this.teams.teamA;
        const teamB = this.teams.teamB;
        const maxDifference = 20; // Giới hạn chênh lệch tối đa
        let improved = true;
        let maxSwaps = 100; // Tăng số lần trao đổi để tìm kết quả tốt hơn
        
        // Đếm số rank A trong mỗi team để không làm mất cân bằng
        const teamARankACount = teamA.filter(p => p.rank === 'A').length;
        const teamBRankACount = teamB.filter(p => p.rank === 'A').length;
        
        while (improved && maxSwaps > 0) {
            improved = false;
            const currentDiff = Math.abs(this.calculateTeamStats(teamA).combinedTotal - this.calculateTeamStats(teamB).combinedTotal);
            
            // Nếu đã đạt yêu cầu (chênh lệch <= 20%), dừng lại
            if (currentDiff <= maxDifference) {
                break;
            }
            
            // Thử trao đổi từng cặp (chỉ trao đổi rank B, C, D)
            for (let i = 0; i < teamA.length; i++) {
                for (let j = 0; j < teamB.length; j++) {
                    const playerA = teamA[i];
                    const playerB = teamB[j];
                    
                    // Không trao đổi rank A để giữ nguyên phân chia đã thiết lập
                    if (playerA.rank === 'A' || playerB.rank === 'A') {
                        continue;
                    }
                    
                    // Thử trao đổi
                    teamA[i] = playerB;
                    teamB[j] = playerA;
                    
                    // Kiểm tra xem phân chia rank A có bị ảnh hưởng không
                    const newTeamARankACount = teamA.filter(p => p.rank === 'A').length;
                    const newTeamBRankACount = teamB.filter(p => p.rank === 'A').length;
                    
                    if (newTeamARankACount === teamARankACount && newTeamBRankACount === teamBRankACount) {
                        const newStatsA = this.calculateTeamStats(teamA);
                        const newStatsB = this.calculateTeamStats(teamB);
                        const newDiff = Math.abs(newStatsA.combinedTotal - newStatsB.combinedTotal);
                        
                        // Chấp nhận trao đổi nếu:
                        // 1. Chênh lệch giảm, HOẶC
                        // 2. Chênh lệch vẫn <= 20% và không tăng quá nhiều
                        if (newDiff < currentDiff || (newDiff <= maxDifference && newDiff <= currentDiff + 5)) {
                            improved = true;
                            maxSwaps--;
                            break; // Trao đổi thành công, thử lại từ đầu
                        } else {
                            // Hoàn tác
                            teamA[i] = playerA;
                            teamB[j] = playerB;
                        }
                    } else {
                        // Hoàn tác nếu phân chia rank A bị ảnh hưởng
                        teamA[i] = playerA;
                        teamB[j] = playerB;
                    }
                }
                if (improved) break;
            }
        }
        
        // Kiểm tra kết quả cuối cùng
        const finalDiff = Math.abs(this.calculateTeamStats(teamA).combinedTotal - this.calculateTeamStats(teamB).combinedTotal);
        if (finalDiff > maxDifference) {
            console.warn(`Sau khi trao đổi thông minh, chênh lệch vẫn là ${Math.round(finalDiff)}%, vượt quá giới hạn ${maxDifference}%`);
        }
    }

    showResults() {
        this.displayTeamResults();
        this.displayBalanceInfo();
        document.getElementById('resultSection').style.display = 'block';
        document.getElementById('resultSection').classList.add('fade-in');
    }

    displayTeamResults() {
        this.displayTeam('teamA', this.teams.teamA);
        this.displayTeam('teamB', this.teams.teamB);
    }

    displayBalanceInfo() {
        const teamAStats = this.calculateTeamStats(this.teams.teamA);
        const teamBStats = this.calculateTeamStats(this.teams.teamB);
        const difference = Math.abs(teamAStats.combinedTotal - teamBStats.combinedTotal);
        
        // Tính số lượng rank A trong mỗi team
        const teamARankACount = this.teams.teamA.filter(p => p.rank === 'A').length;
        const teamBRankACount = this.teams.teamB.filter(p => p.rank === 'A').length;
        const totalRankA = teamARankACount + teamBRankACount;
        
        // Tạo hoặc cập nhật thông tin cân bằng
        let balanceInfo = document.getElementById('balanceInfo');
        if (!balanceInfo) {
            balanceInfo = document.createElement('div');
            balanceInfo.id = 'balanceInfo';
            balanceInfo.className = 'balance-info';
            document.querySelector('.teams-container').parentNode.insertBefore(balanceInfo, document.querySelector('.teams-container'));
        }
        
        const balanceClass = difference <= 5 ? 'perfect' : difference <= 10 ? 'good' : difference <= 20 ? 'fair' : 'poor';
        const balanceText = difference <= 5 ? 'Cân bằng hoàn hảo!' : 
                           difference <= 10 ? 'Cân bằng tốt' : 
                           difference <= 20 ? 'Cân bằng chấp nhận được' : 'Cân bằng kém';
        
        // Logic phân chia rank A
        let rankALogic = '';
        if (totalRankA % 2 === 0) {
            rankALogic = `Rank A chẵn (${totalRankA}): chia đều cho 2 team (${teamARankACount} - ${teamBRankACount})`;
        } else {
            rankALogic = `Rank A lẻ (${totalRankA}): chia đều + thêm rank B skill cao nhất (${teamARankACount} - ${teamBRankACount})`;
        }
        
        balanceInfo.innerHTML = `
            <div class="balance-card ${balanceClass}">
                <h3><i class="fas fa-balance-scale"></i> Thông tin cân bằng</h3>
                <div class="rank-a-logic">
                    <div class="logic-item">
                        <i class="fas fa-crown"></i>
                        <span>${rankALogic}</span>
                    </div>
                </div>
                <div class="balance-stats">
                    <div class="balance-item">
                        <span>Team A (Rank):</span>
                        <span class="stat-value">${teamAStats.rankTotal}%</span>
                    </div>
                    <div class="balance-item">
                        <span>Team B (Rank):</span>
                        <span class="stat-value">${teamBStats.rankTotal}%</span>
                    </div>
                    <div class="balance-item">
                        <span>Team A (Skill):</span>
                        <span class="stat-value">${Math.round(teamAStats.skillTotal / 5)}%</span>
                    </div>
                    <div class="balance-item">
                        <span>Team B (Skill):</span>
                        <span class="stat-value">${Math.round(teamBStats.skillTotal / 5)}%</span>
                    </div>
                    <div class="balance-item">
                        <span>Team A (Tổng):</span>
                        <span class="stat-value">${Math.round(teamAStats.combinedTotal)}%</span>
                    </div>
                    <div class="balance-item">
                        <span>Team B (Tổng):</span>
                        <span class="stat-value">${Math.round(teamBStats.combinedTotal)}%</span>
                    </div>
                    <div class="balance-item">
                        <span>Chênh lệch:</span>
                        <span class="stat-value">${Math.round(difference)}%</span>
                    </div>
                    <div class="balance-item">
                        <span>Đánh giá:</span>
                        <span class="stat-value">${balanceText}</span>
                    </div>
                    ${difference > 20 ? `
                    <div class="balance-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Cảnh báo: Chênh lệch vượt quá giới hạn cho phép (20%)</span>
                    </div>
                    ` : ''}
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
        spinBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Quay';
        
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
        
        // Xóa thông tin cân bằng
        const balanceInfo = document.getElementById('balanceInfo');
        if (balanceInfo) {
            balanceInfo.remove();
        }
    }
}

// Khởi tạo game khi trang load
document.addEventListener('DOMContentLoaded', () => {
    new WheelGame();
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
