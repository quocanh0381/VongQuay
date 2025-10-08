<?php
$page_title = "Danh sách phòng - Vòng Quay Ngẫu Nhiên";
ob_start();
?>

<div class="rooms-section">
    <div class="section-header">
        <h2><i class="fas fa-door-open"></i> Danh sách phòng</h2>
        <div class="header-actions">
            <a href="index.php?action=create_room" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo phòng mới
            </a>
            <a href="index.php?action=join_room" class="btn btn-secondary">
                <i class="fas fa-sign-in-alt"></i> Tham gia phòng
            </a>
        </div>
    </div>

    <?php if (empty($rooms)): ?>
        <div class="empty-state">
            <i class="fas fa-door-open"></i>
            <h3>Chưa có phòng nào</h3>
            <p>Hãy tạo phòng đầu tiên hoặc tham gia phòng khác!</p>
            <a href="index.php?action=create_room" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo phòng mới
            </a>
        </div>
    <?php else: ?>
        <div class="rooms-grid">
            <?php foreach ($rooms as $room): ?>
                <div class="room-card">
                    <div class="room-header">
                        <h3><?php echo htmlspecialchars($room['room_name']); ?></h3>
                        <span class="room-code"><?php echo htmlspecialchars($room['room_code']); ?></span>
                    </div>
                    
                    <div class="room-info">
                        <div class="info-item">
                            <i class="fas fa-user"></i>
                            <span>Chủ phòng: <?php echo htmlspecialchars($room['creator_name']); ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-users"></i>
                            <span><?php echo $room['participant_count']; ?>/<?php echo $room['max_players']; ?> người</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <span><?php echo date('d/m/Y H:i', strtotime($room['created_at'])); ?></span>
                        </div>
                    </div>
                    
                    <div class="room-actions">
                        <a href="index.php?action=room&id=<?php echo $room['id']; ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> Xem
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
