<?php
$page_title = "Vòng Quay Ngẫu Nhiên";
ob_start();
?>

<div class="main-interface">
    <div class="welcome-section">
        <h1><i class="fas fa-dice"></i> Vòng Quay Ngẫu Nhiên</h1>
        <p>Chia team cân bằng và công bằng</p>
    </div>

    <div class="main-actions">
        <div class="action-card create-room">
            <div class="card-header">
                <i class="fas fa-plus-circle"></i>
                <h2>Tạo Phòng</h2>
            </div>
            <div class="card-content">
                <p>Tạo phòng mới với mã phòng và mật khẩu riêng</p>
                <a href="index.php?action=create_room" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tạo phòng mới
                </a>
            </div>
        </div>

        <div class="action-card join-room">
            <div class="card-header">
                <i class="fas fa-sign-in-alt"></i>
                <h2>Tham Gia Phòng</h2>
            </div>
            <div class="card-content">
                <p>Tham gia phòng bằng mã phòng và mật khẩu</p>
                <a href="index.php?action=join_room" class="btn btn-secondary">
                    <i class="fas fa-sign-in-alt"></i> Tham gia phòng
                </a>
            </div>
        </div>
    </div>

    <?php if (!empty($rooms)): ?>
    <div class="recent-rooms">
        <h3><i class="fas fa-history"></i> Phòng gần đây</h3>
        <div class="rooms-list">
            <?php foreach (array_slice($rooms, 0, 3) as $room): ?>
                <div class="room-item">
                    <div class="room-info">
                        <h4><?php echo htmlspecialchars($room['room_name']); ?></h4>
                        <p>Mã: <?php echo htmlspecialchars($room['room_code']); ?> | Tạo bởi: <?php echo htmlspecialchars($room['creator_name']); ?></p>
                    </div>
                    <div class="room-time">
                        <?php echo date('d/m H:i', strtotime($room['created_at'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
