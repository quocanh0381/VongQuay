<?php
$page_title = "Chi tiết phòng - " . htmlspecialchars($room['room_name']);

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

<div class="room-detail-section">
    <div class="section-header">
        <h2><i class="fas fa-door-open"></i> <?php echo htmlspecialchars($room['room_name']); ?></h2>
        <div class="room-info-header">
            <span class="room-code">Mã phòng: <?php echo htmlspecialchars($room['room_code']); ?></span>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="room-stats">
        <div class="stat-card">
            <i class="fas fa-user"></i>
            <h3>Chủ phòng</h3>
            <p><?php echo htmlspecialchars($room['creator_name']); ?></p>
        </div>
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <h3>Số người tối đa</h3>
            <p><?php echo $room['max_players']; ?> người</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-clock"></i>
            <h3>Tạo lúc</h3>
            <p><?php echo date('d/m/Y H:i', strtotime($room['created_at'])); ?></p>
        </div>
    </div>

    <div class="room-actions">
        <a href="index.php?action=add_member&room_id=<?php echo $room['id']; ?>" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Thêm thành viên
        </a>
        <a href="index.php?action=room&id=<?php echo $room['id']; ?>" class="btn btn-info">
            <i class="fas fa-sync-alt"></i> Load lại
        </a>
        <?php if (count($players) >= $room['max_players']): ?>
            <a href="index.php?action=balance_teams&room_id=<?php echo $room['id']; ?>" class="btn btn-success">
                <i class="fas fa-balance-scale"></i> Chia team
            </a>
        <?php else: ?>
            <button class="btn btn-secondary" disabled>
                <i class="fas fa-balance-scale"></i> Chia team (Cần <?php echo $room['max_players']; ?> người)
            </button>
        <?php endif; ?>
    </div>

    <?php if (!empty($players)): ?>
    <div class="players-section">
        <h3><i class="fas fa-users"></i> Danh sách người chơi (<?php echo count($players); ?>/<?php echo $room['max_players']; ?>)</h3>
        <div class="players-container">
            <!-- Hàng 1 -->
            <div class="players-row">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="player-column">
                        <?php if (isset($players[$i])): ?>
                            <?php $player = $players[$i]; ?>
                                <div class="player-info">
                                    <div class="player-name"><?php echo htmlspecialchars($player['name']); ?></div>
                                    <div class="player-skills">
                                        <div class="skill-row">
                                            <span class="skill-label">Kỹ năng:</span>
                                            <span class="skill-value"><?php echo $player['personalSkill']; ?>/10</span>
                                        </div>
                                        <div class="skill-row">
                                            <span class="skill-label">Bản đồ:</span>
                                            <span class="skill-value"><?php echo $player['mapReading']; ?>/10</span>
                                        </div>
                                        <div class="skill-row">
                                            <span class="skill-label">Nhóm:</span>
                                            <span class="skill-value"><?php echo $player['teamwork']; ?>/10</span>
                                        </div>
                                        <div class="skill-row">
                                            <span class="skill-label">Phản xạ:</span>
                                            <span class="skill-value"><?php echo $player['reaction']; ?>/10</span>
                                        </div>
                                        <div class="skill-row">
                                            <span class="skill-label">Kinh nghiệm:</span>
                                            <span class="skill-value"><?php echo $player['experience']; ?>/10</span>
                                        </div>
                                    </div>
                                    <div class="player-total">Tổng: <?php echo $player['totalScore']; ?> điểm</div>
                                    <div class="player-position">Vị trí: <?php echo getPositionName($player['position']); ?></div>
                                    <div class="player-actions">
                                        <a href="index.php?action=edit_player&player_id=<?php echo $player['id']; ?>&room_id=<?php echo $room['id']; ?>" class="btn-edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?action=delete_player&player_id=<?php echo $player['id']; ?>&room_id=<?php echo $room['id']; ?>" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa người chơi này?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                        <?php else: ?>
                            <div class="empty-player">
                                <div class="empty-text">Trống</div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
            
            <!-- Hàng 2 -->
            <div class="players-row">
                <?php for ($i = 5; $i < 10; $i++): ?>
                    <div class="player-column">
                        <?php if (isset($players[$i])): ?>
                            <?php $player = $players[$i]; ?>
                                <div class="player-info">
                                    <div class="player-name"><?php echo htmlspecialchars($player['name']); ?></div>
                                    <div class="player-skills">
                                        <div class="skill-row">
                                            <span class="skill-label">Kỹ năng:</span>
                                            <span class="skill-value"><?php echo $player['personalSkill']; ?>/10</span>
                                        </div>
                                        <div class="skill-row">
                                            <span class="skill-label">Bản đồ:</span>
                                            <span class="skill-value"><?php echo $player['mapReading']; ?>/10</span>
                                        </div>
                                        <div class="skill-row">
                                            <span class="skill-label">Nhóm:</span>
                                            <span class="skill-value"><?php echo $player['teamwork']; ?>/10</span>
                                        </div>
                                        <div class="skill-row">
                                            <span class="skill-label">Phản xạ:</span>
                                            <span class="skill-value"><?php echo $player['reaction']; ?>/10</span>
                                        </div>
                                        <div class="skill-row">
                                            <span class="skill-label">Kinh nghiệm:</span>
                                            <span class="skill-value"><?php echo $player['experience']; ?>/10</span>
                                        </div>
                                    </div>
                                    <div class="player-total">Tổng: <?php echo $player['totalScore']; ?> điểm</div>
                                    <div class="player-position">Vị trí: <?php echo getPositionName($player['position']); ?></div>
                                    <div class="player-actions">
                                        <a href="index.php?action=edit_player&player_id=<?php echo $player['id']; ?>&room_id=<?php echo $room['id']; ?>" class="btn-edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?action=delete_player&player_id=<?php echo $player['id']; ?>&room_id=<?php echo $room['id']; ?>" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa người chơi này?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                        <?php else: ?>
                            <div class="empty-player">
                                <div class="empty-text">Trống</div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-users"></i>
        <h3>Chưa có người chơi nào</h3>
        <p>Hãy thêm thành viên đầu tiên để bắt đầu!</p>
        <a href="index.php?action=add_member&room_id=<?php echo $room['id']; ?>" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Thêm thành viên
        </a>
    </div>
    <?php endif; ?>

    <?php if (!empty($player_name)): ?>
    <div class="player-welcome">
        <div class="welcome-card">
            <i class="fas fa-hand-wave"></i>
            <h3>Chào mừng <?php echo htmlspecialchars($player_name); ?>!</h3>
            <p>Bạn đã tham gia phòng thành công. Hãy tạo match hoặc tham gia match có sẵn.</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
