<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vòng Quay Chọn Tướng - Liên Quân Mobile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #fff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .player-input-section {
            background: rgba(255,255,255,0.1);
            padding: 25px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .player-input-section h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #ffd700;
        }

        .player-form {
            display: grid;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, .form-group select {
            padding: 10px;
            border: none;
            border-radius: 8px;
            background: rgba(255,255,255,0.9);
            color: #333;
            font-size: 14px;
        }

        .skill-rating {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 10px;
        }

        .skill-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255,255,255,0.1);
            padding: 8px 12px;
            border-radius: 6px;
        }

        .skill-item label {
            font-size: 12px;
            margin: 0;
        }

        .skill-item select {
            width: 60px;
            padding: 4px;
            font-size: 12px;
        }

        .add-player-btn {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        .add-player-btn:hover {
            transform: translateY(-2px);
        }

        .players-list {
            background: rgba(255,255,255,0.1);
            padding: 25px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .players-list h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #ffd700;
        }

        .player-card {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            border-left: 4px solid #ffd700;
            position: relative;
            transition: all 0.3s ease;
        }

        .player-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .player-card.editing {
            border-left-color: #4ecdc4;
            background: rgba(78, 205, 196, 0.2);
        }

        .player-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .player-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            font-size: 12px;
        }

        .stat-item {
            background: rgba(255,255,255,0.1);
            padding: 5px 8px;
            border-radius: 4px;
            text-align: center;
        }

        .total-score {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
            color: #ffd700;
        }

        .player-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 5px;
        }

        .action-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 5px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }

        .edit-btn {
            background: rgba(78, 205, 196, 0.8);
        }

        .delete-btn {
            background: rgba(255, 107, 107, 0.8);
        }

        .edit-form {
            background: rgba(78, 205, 196, 0.1);
            padding: 15px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid rgba(78, 205, 196, 0.3);
        }

        .edit-form h4 {
            color: #4ecdc4;
            margin-bottom: 10px;
            text-align: center;
        }

        .edit-form .skill-rating {
            margin-top: 10px;
        }

        .form-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
        }

        .save-btn {
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .cancel-btn {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .save-btn:hover, .cancel-btn:hover {
            transform: translateY(-2px);
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }

        .btn-primary {
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(45deg, #ff9a9e, #fecfef);
            color: #333;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .wheel-container {
            text-align: center;
            margin: 30px 0;
        }

        .wheel {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: conic-gradient(
                #ff6b6b 0deg 36deg,
                #4ecdc4 36deg 72deg,
                #45b7d1 72deg 108deg,
                #96ceb4 108deg 144deg,
                #feca57 144deg 180deg,
                #ff9ff3 180deg 216deg,
                #54a0ff 216deg 252deg,
                #5f27cd 252deg 288deg,
                #00d2d3 288deg 324deg,
                #ff9f43 324deg 360deg
            );
            margin: 0 auto 20px;
            position: relative;
            transition: transform 3s cubic-bezier(0.17, 0.67, 0.12, 0.99);
            border: 8px solid #fff;
            box-shadow: 0 0 30px rgba(0,0,0,0.3);
        }

        .wheel-pointer {
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-top: 30px solid #fff;
            z-index: 10;
        }

        .wheel.spinning {
            animation: spin 3s cubic-bezier(0.17, 0.67, 0.12, 0.99);
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(var(--spin-rotation, 1800deg)); }
        }

        .results-section {
            display: none;
            margin-top: 30px;
        }

        .results-section.show {
            display: block;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .teams-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }

        .team {
            background: rgba(255,255,255,0.1);
            padding: 25px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .team.team-a {
            border-left: 5px solid #ff6b6b;
        }

        .team.team-b {
            border-left: 5px solid #4ecdc4;
        }

        .team h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        .team-a h3 {
            color: #ff6b6b;
        }

        .team-b h3 {
            color: #4ecdc4;
        }

        .team-player {
            background: rgba(255,255,255,0.1);
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .team-balance {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }

        .balance-score {
            font-size: 1.2em;
            font-weight: bold;
            color: #ffd700;
        }

        .reset-btn {
            background: linear-gradient(45deg, #ff4757, #c44569);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 15px;
        }

        .reset-btn:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .teams-container {
                grid-template-columns: 1fr;
            }
            
            .wheel {
                width: 250px;
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎮 Vòng Quay Chọn Tướng</h1>
            <p>Liên Quân Mobile - Phân Chia Đội Cân Bằng</p>
        </div>

        <div class="main-content">
            <div class="player-input-section">
                <h2>📝 Thêm Người Chơi</h2>
                <form class="player-form" id="playerForm">
                    <div class="form-group">
                        <label for="playerName">Tên Người Chơi:</label>
                        <input type="text" id="playerName" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Đánh Giá Kỹ Năng:</label>
                        <div class="skill-rating">
                            <div class="skill-item">
                                <label>Kỹ Năng Cá Nhân:</label>
                                <select name="personalSkill">
                                    <option value="1">1 - Yếu</option>
                                    <option value="2">2 - Trung Bình</option>
                                    <option value="3" selected>3 - Khá</option>
                                    <option value="4">4 - Tốt</option>
                                    <option value="5">5 - Xuất Sắc</option>
                                </select>
                            </div>
                            <div class="skill-item">
                                <label>Đọc Map:</label>
                                <select name="mapReading">
                                    <option value="1">1 - Yếu</option>
                                    <option value="2">2 - Trung Bình</option>
                                    <option value="3" selected>3 - Khá</option>
                                    <option value="4">4 - Tốt</option>
                                    <option value="5">5 - Xuất Sắc</option>
                                </select>
                            </div>
                            <div class="skill-item">
                                <label>Teamwork:</label>
                                <select name="teamwork">
                                    <option value="1">1 - Yếu</option>
                                    <option value="2">2 - Trung Bình</option>
                                    <option value="3" selected>3 - Khá</option>
                                    <option value="4">4 - Tốt</option>
                                    <option value="5">5 - Xuất Sắc</option>
                                </select>
                            </div>
                            <div class="skill-item">
                                <label>Phản Ứng:</label>
                                <select name="reaction">
                                    <option value="1">1 - Yếu</option>
                                    <option value="2">2 - Trung Bình</option>
                                    <option value="3" selected>3 - Khá</option>
                                    <option value="4">4 - Tốt</option>
                                    <option value="5">5 - Xuất Sắc</option>
                                </select>
                            </div>
                            <div class="skill-item">
                                <label>Kinh Nghiệm:</label>
                                <select name="experience">
                                    <option value="1">1 - Yếu</option>
                                    <option value="2">2 - Trung Bình</option>
                                    <option value="3" selected>3 - Khá</option>
                                    <option value="4">4 - Tốt</option>
                                    <option value="5">5 - Xuất Sắc</option>
                                </select>
                            </div>
                            <div class="skill-item">
                                <label>Vị Trí Ưa Thích:</label>
                                <select name="position">
                                    <option value="1">1 - Đường Giữa</option>
                                    <option value="2">2 - Đường Trên</option>
                                    <option value="3">3 - Đường Dưới</option>
                                    <option value="4">4 - Rừng</option>
                                    <option value="5">5 - Hỗ Trợ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="add-player-btn">➕ Thêm Người Chơi</button>
                </form>
            </div>

            <div class="players-list">
                <h2>👥 Danh Sách Người Chơi (<span id="playerCount">0</span>/10)</h2>
                <div id="playersList"></div>
            </div>
        </div>

        <div class="action-buttons">
            <button class="btn btn-primary" id="spinWheelBtn" disabled>🎯 Quay Chọn Tướng</button>
            <button class="btn btn-secondary" id="balanceTeamsBtn" disabled>⚖️ Phân Chia Đội Cân Bằng</button>
        </div>

        <div class="wheel-container" id="wheelContainer" style="display: none;">
            <div class="wheel" id="wheel">
                <div class="wheel-pointer"></div>
            </div>
            <p>Đang quay để chọn tướng...</p>
        </div>

        <div class="results-section" id="resultsSection">
            <h2 style="text-align: center; margin-bottom: 20px;">🏆 Kết Quả Phân Chia Đội</h2>
            <div class="teams-container">
                <div class="team team-a">
                    <h3>🔴 Đội A</h3>
                    <div id="teamA"></div>
                </div>
                <div class="team team-b">
                    <h3>🔵 Đội B</h3>
                    <div id="teamB"></div>
                </div>
            </div>
            <div class="team-balance">
                <div class="balance-score" id="balanceScore"></div>
                <button class="reset-btn" onclick="resetGame()">🔄 Chơi Lại</button>
            </div>
        </div>
    </div>

    <script>
        let players = [];
        let selectedPlayer = null;

        // Thêm người chơi
        document.getElementById('playerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (players.length >= 10) {
                alert('Đã đủ 10 người chơi!');
                return;
            }

            const formData = new FormData(this);
            const playerName = document.getElementById('playerName').value;
            
            const player = {
                id: Date.now(),
                name: playerName,
                personalSkill: parseInt(formData.get('personalSkill')),
                mapReading: parseInt(formData.get('mapReading')),
                teamwork: parseInt(formData.get('teamwork')),
                reaction: parseInt(formData.get('reaction')),
                experience: parseInt(formData.get('experience')),
                position: parseInt(formData.get('position'))
            };

            // Tính tổng điểm
            player.totalScore = player.personalSkill + player.mapReading + player.teamwork + 
                              player.reaction + player.experience;

            players.push(player);
            updatePlayersList();
            this.reset();
            
            // Cập nhật trạng thái nút
            updateButtonStates();
        });

        function updatePlayersList() {
            const playersList = document.getElementById('playersList');
            const playerCount = document.getElementById('playerCount');
            
            playerCount.textContent = players.length;
            
            if (players.length === 0) {
                playersList.innerHTML = '<p style="text-align: center; opacity: 0.7;">Chưa có người chơi nào</p>';
                return;
            }

            playersList.innerHTML = players.map(player => `
                <div class="player-card" id="player-${player.id}">
                    <div class="player-actions">
                        <button class="action-btn edit-btn" onclick="editPlayer(${player.id})" title="Chỉnh sửa">✏️</button>
                        <button class="action-btn delete-btn" onclick="deletePlayer(${player.id})" title="Xóa">🗑️</button>
                    </div>
                    <div class="player-name">${player.name}</div>
                    <div class="player-stats">
                        <div class="stat-item">Kỹ năng: ${player.personalSkill}</div>
                        <div class="stat-item">Đọc map: ${player.mapReading}</div>
                        <div class="stat-item">Teamwork: ${player.teamwork}</div>
                        <div class="stat-item">Phản ứng: ${player.reaction}</div>
                        <div class="stat-item">Kinh nghiệm: ${player.experience}</div>
                        <div class="stat-item">Vị trí: ${getPositionName(player.position)}</div>
                    </div>
                    <div class="total-score">Tổng điểm: ${player.totalScore}</div>
                </div>
            `).join('');
        }

        function getPositionName(position) {
            const positions = ['', 'Đường Giữa', 'Đường Trên', 'Đường Dưới', 'Rừng', 'Hỗ Trợ'];
            return positions[position];
        }

        function updateButtonStates() {
            const spinBtn = document.getElementById('spinWheelBtn');
            const balanceBtn = document.getElementById('balanceTeamsBtn');
            
            if (players.length >= 2) {
                spinBtn.disabled = false;
            }
            
            if (players.length >= 4) {
                balanceBtn.disabled = false;
            }
        }

        // Quay vòng quay
        document.getElementById('spinWheelBtn').addEventListener('click', function() {
            if (players.length < 2) return;
            
            const wheelContainer = document.getElementById('wheelContainer');
            const wheel = document.getElementById('wheel');
            
            wheelContainer.style.display = 'block';
            
            // Chọn ngẫu nhiên một người chơi
            const randomIndex = Math.floor(Math.random() * players.length);
            selectedPlayer = players[randomIndex];
            
            // Tính góc quay
            const baseRotation = 1800; // 5 vòng
            const additionalRotation = Math.random() * 360;
            const totalRotation = baseRotation + additionalRotation;
            
            wheel.style.setProperty('--spin-rotation', totalRotation + 'deg');
            wheel.classList.add('spinning');
            
            setTimeout(() => {
                wheel.classList.remove('spinning');
                alert(`🎉 Tướng được chọn: ${selectedPlayer.name}!`);
                wheelContainer.style.display = 'none';
            }, 3000);
        });

        // Phân chia đội cân bằng
        document.getElementById('balanceTeamsBtn').addEventListener('click', function() {
            if (players.length < 4) return;
            
            const teams = balanceTeams(players);
            displayResults(teams);
        });

        function balanceTeams(players) {
            // Sắp xếp người chơi theo điểm số giảm dần
            const sortedPlayers = [...players].sort((a, b) => b.totalScore - a.totalScore);
            
            let teamA = [];
            let teamB = [];
            let teamAScore = 0;
            let teamBScore = 0;
            
            // Phân chia người chơi
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
            
            return {
                teamA: teamA,
                teamB: teamB,
                teamAScore: teamAScore,
                teamBScore: teamBScore
            };
        }

        function displayResults(teams) {
            const resultsSection = document.getElementById('resultsSection');
            const teamA = document.getElementById('teamA');
            const teamB = document.getElementById('teamB');
            const balanceScore = document.getElementById('balanceScore');
            
            // Hiển thị đội A
            teamA.innerHTML = teams.teamA.map(player => `
                <div class="team-player">
                    <span>${player.name}</span>
                    <span>${player.totalScore} điểm</span>
                </div>
            `).join('');
            
            // Hiển thị đội B
            teamB.innerHTML = teams.teamB.map(player => `
                <div class="team-player">
                    <span>${player.name}</span>
                    <span>${player.totalScore} điểm</span>
                </div>
            `).join('');
            
            // Tính độ cân bằng
            const totalScore = teams.teamAScore + teams.teamBScore;
            const balancePercentage = Math.abs(teams.teamAScore - teams.teamBScore) / totalScore * 100;
            const balanceLevel = balancePercentage < 10 ? 'Cân bằng tốt' : 
                               balancePercentage < 20 ? 'Cân bằng khá' : 'Cần điều chỉnh';
            
            balanceScore.innerHTML = `
                <div>Đội A: ${teams.teamAScore} điểm</div>
                <div>Đội B: ${teams.teamBScore} điểm</div>
                <div>Mức độ cân bằng: ${balanceLevel} (${balancePercentage.toFixed(1)}%)</div>
            `;
            
            resultsSection.classList.add('show');
            resultsSection.scrollIntoView({ behavior: 'smooth' });
        }

        function editPlayer(playerId) {
            const player = players.find(p => p.id === playerId);
            if (!player) return;

            const playerCard = document.getElementById(`player-${playerId}`);
            playerCard.classList.add('editing');

            const editForm = `
                <div class="edit-form">
                    <h4>✏️ Chỉnh sửa thông tin: ${player.name}</h4>
                    <div class="form-group">
                        <label for="editName-${playerId}">Tên Người Chơi:</label>
                        <input type="text" id="editName-${playerId}" value="${player.name}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Đánh Giá Kỹ Năng:</label>
                        <div class="skill-rating">
                            <div class="skill-item">
                                <label>Kỹ Năng Cá Nhân:</label>
                                <select id="editPersonalSkill-${playerId}">
                                    <option value="1" ${player.personalSkill === 1 ? 'selected' : ''}>1 - Yếu</option>
                                    <option value="2" ${player.personalSkill === 2 ? 'selected' : ''}>2 - Trung Bình</option>
                                    <option value="3" ${player.personalSkill === 3 ? 'selected' : ''}>3 - Khá</option>
                                    <option value="4" ${player.personalSkill === 4 ? 'selected' : ''}>4 - Tốt</option>
                                    <option value="5" ${player.personalSkill === 5 ? 'selected' : ''}>5 - Xuất Sắc</option>
                                </select>
                            </div>
                            <div class="skill-item">
                                <label>Đọc Map:</label>
                                <select id="editMapReading-${playerId}">
                                    <option value="1" ${player.mapReading === 1 ? 'selected' : ''}>1 - Yếu</option>
                                    <option value="2" ${player.mapReading === 2 ? 'selected' : ''}>2 - Trung Bình</option>
                                    <option value="3" ${player.mapReading === 3 ? 'selected' : ''}>3 - Khá</option>
                                    <option value="4" ${player.mapReading === 4 ? 'selected' : ''}>4 - Tốt</option>
                                    <option value="5" ${player.mapReading === 5 ? 'selected' : ''}>5 - Xuất Sắc</option>
                                </select>
                            </div>
                            <div class="skill-item">
                                <label>Teamwork:</label>
                                <select id="editTeamwork-${playerId}">
                                    <option value="1" ${player.teamwork === 1 ? 'selected' : ''}>1 - Yếu</option>
                                    <option value="2" ${player.teamwork === 2 ? 'selected' : ''}>2 - Trung Bình</option>
                                    <option value="3" ${player.teamwork === 3 ? 'selected' : ''}>3 - Khá</option>
                                    <option value="4" ${player.teamwork === 4 ? 'selected' : ''}>4 - Tốt</option>
                                    <option value="5" ${player.teamwork === 5 ? 'selected' : ''}>5 - Xuất Sắc</option>
                                </select>
                            </div>
                            <div class="skill-item">
                                <label>Phản Ứng:</label>
                                <select id="editReaction-${playerId}">
                                    <option value="1" ${player.reaction === 1 ? 'selected' : ''}>1 - Yếu</option>
                                    <option value="2" ${player.reaction === 2 ? 'selected' : ''}>2 - Trung Bình</option>
                                    <option value="3" ${player.reaction === 3 ? 'selected' : ''}>3 - Khá</option>
                                    <option value="4" ${player.reaction === 4 ? 'selected' : ''}>4 - Tốt</option>
                                    <option value="5" ${player.reaction === 5 ? 'selected' : ''}>5 - Xuất Sắc</option>
                                </select>
                            </div>
                            <div class="skill-item">
                                <label>Kinh Nghiệm:</label>
                                <select id="editExperience-${playerId}">
                                    <option value="1" ${player.experience === 1 ? 'selected' : ''}>1 - Yếu</option>
                                    <option value="2" ${player.experience === 2 ? 'selected' : ''}>2 - Trung Bình</option>
                                    <option value="3" ${player.experience === 3 ? 'selected' : ''}>3 - Khá</option>
                                    <option value="4" ${player.experience === 4 ? 'selected' : ''}>4 - Tốt</option>
                                    <option value="5" ${player.experience === 5 ? 'selected' : ''}>5 - Xuất Sắc</option>
                                </select>
                            </div>
                            <div class="skill-item">
                                <label>Vị Trí Ưa Thích:</label>
                                <select id="editPosition-${playerId}">
                                    <option value="1" ${player.position === 1 ? 'selected' : ''}>1 - Đường Giữa</option>
                                    <option value="2" ${player.position === 2 ? 'selected' : ''}>2 - Đường Trên</option>
                                    <option value="3" ${player.position === 3 ? 'selected' : ''}>3 - Đường Dưới</option>
                                    <option value="4" ${player.position === 4 ? 'selected' : ''}>4 - Rừng</option>
                                    <option value="5" ${player.position === 5 ? 'selected' : ''}>5 - Hỗ Trợ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-buttons">
                        <button class="save-btn" onclick="savePlayer(${playerId})">💾 Lưu</button>
                        <button class="cancel-btn" onclick="cancelEdit(${playerId})">❌ Hủy</button>
                    </div>
                </div>
            `;

            playerCard.insertAdjacentHTML('beforeend', editForm);
        }

        function savePlayer(playerId) {
            const player = players.find(p => p.id === playerId);
            if (!player) return;

            const newName = document.getElementById(`editName-${playerId}`).value;
            const newPersonalSkill = parseInt(document.getElementById(`editPersonalSkill-${playerId}`).value);
            const newMapReading = parseInt(document.getElementById(`editMapReading-${playerId}`).value);
            const newTeamwork = parseInt(document.getElementById(`editTeamwork-${playerId}`).value);
            const newReaction = parseInt(document.getElementById(`editReaction-${playerId}`).value);
            const newExperience = parseInt(document.getElementById(`editExperience-${playerId}`).value);
            const newPosition = parseInt(document.getElementById(`editPosition-${playerId}`).value);

            if (!newName.trim()) {
                alert('Vui lòng nhập tên người chơi!');
                return;
            }

            // Cập nhật thông tin người chơi
            player.name = newName.trim();
            player.personalSkill = newPersonalSkill;
            player.mapReading = newMapReading;
            player.teamwork = newTeamwork;
            player.reaction = newReaction;
            player.experience = newExperience;
            player.position = newPosition;

            // Tính lại tổng điểm
            player.totalScore = player.personalSkill + player.mapReading + player.teamwork + 
                              player.reaction + player.experience;

            // Cập nhật danh sách
            updatePlayersList();
        }

        function cancelEdit(playerId) {
            const playerCard = document.getElementById(`player-${playerId}`);
            playerCard.classList.remove('editing');
            
            // Xóa form chỉnh sửa
            const editForm = playerCard.querySelector('.edit-form');
            if (editForm) {
                editForm.remove();
            }
        }

        function deletePlayer(playerId) {
            if (confirm('Bạn có chắc chắn muốn xóa người chơi này?')) {
                players = players.filter(p => p.id !== playerId);
                updatePlayersList();
                updateButtonStates();
            }
        }

        function resetGame() {
            players = [];
            selectedPlayer = null;
            updatePlayersList();
            updateButtonStates();
            
            document.getElementById('resultsSection').classList.remove('show');
            document.getElementById('wheelContainer').style.display = 'none';
        }

        // Khởi tạo
        updatePlayersList();
    </script>
</body>
</html>
