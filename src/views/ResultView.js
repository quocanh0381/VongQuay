/**
 * ResultView - View cho kết quả chia team
 */
export class ResultView {
    render() {
        return `
            <div class="result-section" id="resultSection" style="display: none;">
                <h2><i class="fas fa-trophy"></i> Kết quả chia team</h2>
                <div class="teams-container">
                    <div class="team team-a">
                        <h3><i class="fas fa-shield-alt"></i> Team A</h3>
                        <div class="team-members" id="teamA"></div>
                        <div class="team-stats" id="teamAStats"></div>
                    </div>
                    <div class="team team-b">
                        <h3><i class="fas fa-shield-alt"></i> Team B</h3>
                        <div class="team-members" id="teamB"></div>
                        <div class="team-stats" id="teamBStats"></div>
                    </div>
                </div>
                <button class="btn btn-secondary" id="resetBtn">
                    <i class="fas fa-redo"></i> Chơi lại
                </button>
                <button class="btn btn-secondary" data-route="/">
                    <i class="fas fa-home"></i> Về trang chủ
                </button>
            </div>
        `;
    }

    showResults() {
        const resultSection = document.getElementById('resultSection');
        if (resultSection) {
            resultSection.style.display = 'block';
            resultSection.classList.add('fade-in');
        }
    }

    displayTeamResults(teams) {
        this.displayTeam('teamA', teams.teamA);
        this.displayTeam('teamB', teams.teamB);
    }

    displayTeam(teamId, team) {
        const teamElement = document.getElementById(teamId);
        const statsElement = document.getElementById(teamId + 'Stats');
        
        if (!teamElement || !statsElement) return;

        // Hiển thị thành viên
        teamElement.innerHTML = '';
        team.players.forEach(player => {
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
        const stats = team.stats;
        const roleStats = this.calculateRoleStats(team.players);
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
                    <span class="stat-value">${stats.rankDistribution.A} người</span>
                </div>
                <div class="stat-item">
                    <span>Rank B:</span>
                    <span class="stat-value">${stats.rankDistribution.B} người</span>
                </div>
                <div class="stat-item">
                    <span>Rank C:</span>
                    <span class="stat-value">${stats.rankDistribution.C} người</span>
                </div>
                <div class="stat-item">
                    <span>Rank D:</span>
                    <span class="stat-value">${stats.rankDistribution.D} người</span>
                </div>
            </div>
            <div class="score-section" style="border-top: 1px solid #e9ecef; padding-top: 10px; margin-top: 10px;">
                <h5>Điểm số:</h5>
                <div class="stat-item">
                    <span><strong>Tổng điểm:</strong></span>
                    <span class="stat-value"><strong>${stats.totalScore}</strong></span>
                </div>
                <div class="stat-item">
                    <span><strong>Trung bình:</strong></span>
                    <span class="stat-value"><strong>${Math.round(stats.averageSkill)}</strong></span>
                </div>
            </div>
        `;
    }

    displayBalanceInfo(balanceInfo, gameMode) {
        let balanceInfoElement = document.getElementById('balanceInfo');
        if (!balanceInfoElement) {
            balanceInfoElement = document.createElement('div');
            balanceInfoElement.id = 'balanceInfo';
            balanceInfoElement.className = 'balance-info';
            document.querySelector('.teams-container').parentNode.insertBefore(balanceInfoElement, document.querySelector('.teams-container'));
        }
        
        const balanceClass = gameMode === 'random' ? 'random' : 
                           balanceInfo.balancePercentage <= 5 ? 'perfect' : 
                           balanceInfo.balancePercentage <= 10 ? 'good' : 
                           balanceInfo.balancePercentage <= 20 ? 'fair' : 'poor';
        
        const balanceText = gameMode === 'random' ? 'Phân chia hoàn toàn ngẫu nhiên!' :
                           balanceInfo.balancePercentage <= 5 ? 'Cân bằng hoàn hảo!' : 
                           balanceInfo.balancePercentage <= 10 ? 'Cân bằng tốt' : 
                           balanceInfo.balancePercentage <= 20 ? 'Cân bằng chấp nhận được' : 'Cân bằng kém';
        
        balanceInfoElement.innerHTML = `
            <div class="balance-card ${balanceClass}">
                <h3><i class="fas fa-${gameMode === 'random' ? 'random' : 'balance-scale'}"></i> Thông tin ${gameMode === 'random' ? 'phân chia random' : 'cân bằng'}</h3>
                <div class="balance-stats">
                    <div class="balance-item">
                        <span>Phương thức:</span>
                        <span class="stat-value">${balanceText}</span>
                    </div>
                    <div class="balance-item">
                        <span>Team A (Tổng):</span>
                        <span class="stat-value">${balanceInfo.teamAScore}</span>
                    </div>
                    <div class="balance-item">
                        <span>Team B (Tổng):</span>
                        <span class="stat-value">${balanceInfo.teamBScore}</span>
                    </div>
                    <div class="balance-item">
                        <span>Chênh lệch:</span>
                        <span class="stat-value">${Math.round(balanceInfo.balancePercentage)}%</span>
                    </div>
                    <div class="balance-item">
                        <span>Đánh giá:</span>
                        <span class="stat-value">${balanceText}</span>
                    </div>
                </div>
            </div>
        `;
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

    calculateRoleStats(players) {
        const roleStats = { top: 0, jungle: 0, mid: 0, support: 0, ad: 0 };
        
        players.forEach(player => {
            roleStats[player.mainRole]++;
        });
        
        return roleStats;
    }

    hideResults() {
        const resultSection = document.getElementById('resultSection');
        if (resultSection) {
            resultSection.style.display = 'none';
        }
    }
}
