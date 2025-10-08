<?php
$page_title = "Kết quả chia team - " . htmlspecialchars($room['room_name']);

function getPositionName($position) {
    $positions = [
        1 => 'ADC',
        2 => 'Fighter', 
        3 => 'Tank',
        4 => 'Mage',
        5 => 'Assassin',
        6 => 'Support'
    ];
    return $positions[$position] ?? '-';
}

ob_start();
?>

<div class="team-balance-section">
    <div class="section-header">
        <h2><i class="fas fa-balance-scale"></i> Kết quả chia team</h2>
        <div class="room-info-header">
            <span class="room-name">Phòng: <?php echo htmlspecialchars($room['room_name']); ?></span>
            <a href="index.php?action=room&id=<?php echo $room['id']; ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="teams-container">
        <div class="team team-1">
            <div class="team-header">
                <h3><i class="fas fa-shield-alt"></i> Team 1</h3>
                <div class="team-score">
                    <span class="score-label">Tổng điểm:</span>
                    <span class="score-value"><?php echo $teams['team1Score']; ?></span>
                </div>
            </div>
            
            <div class="team-players">
                <?php foreach ($teams['team1'] as $player): ?>
                    <div class="player-item">
                        <div class="player-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="player-details">
                            <div class="player-name"><?php echo htmlspecialchars($player['name']); ?></div>
                            <div class="player-info">
                                <span class="player-score"><?php echo $player['totalScore']; ?> điểm</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="vs-divider">
            <div class="vs-circle">
                <span>VS</span>
            </div>
            <div class="score-diff">
                Chênh lệch: <?php echo abs($teams['team1Score'] - $teams['team2Score']); ?> điểm
            </div>
        </div>

        <div class="team team-2">
            <div class="team-header">
                <h3><i class="fas fa-sword"></i> Team 2</h3>
                <div class="team-score">
                    <span class="score-label">Tổng điểm:</span>
                    <span class="score-value"><?php echo $teams['team2Score']; ?></span>
                </div>
            </div>
            
            <div class="team-players">
                <?php foreach ($teams['team2'] as $player): ?>
                    <div class="player-item">
                        <div class="player-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="player-details">
                            <div class="player-name"><?php echo htmlspecialchars($player['name']); ?></div>
                            <div class="player-info">
                                <span class="player-score"><?php echo $player['totalScore']; ?> điểm</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="balance-actions">
        <a href="index.php?action=balance_teams&room_id=<?php echo $room['id']; ?>" class="btn btn-primary">
            <i class="fas fa-redo"></i> Chia lại team
        </a>
        <a href="index.php?action=room&id=<?php echo $room['id']; ?>" class="btn btn-success">
            <i class="fas fa-check"></i> Xác nhận
        </a>
    </div>

    <div class="balance-info">
        <h4><i class="fas fa-info-circle"></i> Thông tin chia team</h4>
        <ul>
            <li><strong>Thuật toán:</strong> Chia team dựa trên tổng điểm kỹ năng</li>
            <li><strong>Tối ưu hóa:</strong> Tự động điều chỉnh để cân bằng nhất</li>
            <li><strong>Chênh lệch:</strong> <?php echo abs($teams['team1Score'] - $teams['team2Score']); ?> điểm (càng thấp càng cân bằng)</li>
            <li><strong>Đánh giá:</strong> 
                <?php 
                $diff = abs($teams['team1Score'] - $teams['team2Score']);
                if ($diff <= 5) echo "Rất cân bằng ⭐⭐⭐⭐⭐";
                elseif ($diff <= 10) echo "Cân bằng ⭐⭐⭐⭐";
                elseif ($diff <= 15) echo "Khá cân bằng ⭐⭐⭐";
                elseif ($diff <= 20) echo "Tạm ổn ⭐⭐";
                else echo "Cần cải thiện ⭐";
                ?>
            </li>
        </ul>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
