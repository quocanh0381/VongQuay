<?php
$page_title = "Danh sách Matches - Vòng Quay Ngẫu Nhiên";
ob_start();
?>

<div class="matches-section">
    <div class="section-header">
        <h2><i class="fas fa-gamepad"></i> Danh sách Matches</h2>
        <div class="header-actions">
            <a href="index.php?action=create_match" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo match mới
            </a>
        </div>
    </div>

    <?php if (empty($matches)): ?>
        <div class="empty-state">
            <i class="fas fa-gamepad"></i>
            <h3>Chưa có match nào</h3>
            <p>Hãy tạo match đầu tiên để bắt đầu chơi!</p>
            <a href="index.php?action=create_match" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo match mới
            </a>
        </div>
    <?php else: ?>
        <div class="matches-grid">
            <?php foreach ($matches as $match): ?>
                <div class="match-card">
                    <div class="match-header">
                        <h3><?php echo htmlspecialchars($match['match_name']); ?></h3>
                        <span class="match-id">#<?php echo $match['id']; ?></span>
                    </div>
                    
                    <div class="match-info">
                        <div class="info-item">
                            <i class="fas fa-user"></i>
                            <span>Tạo bởi: <?php echo htmlspecialchars($match['creator_name']); ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <span><?php echo date('d/m/Y H:i', strtotime($match['created_at'])); ?></span>
                        </div>
                    </div>
                    
                    <div class="match-actions">
                        <a href="index.php?action=match&id=<?php echo $match['id']; ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> Xem
                        </a>
                        <a href="index.php?action=add_player&match_id=<?php echo $match['id']; ?>" class="btn btn-sm btn-secondary">
                            <i class="fas fa-user-plus"></i> Thêm player
                        </a>
                        <a href="index.php?action=balance_teams&match_id=<?php echo $match['id']; ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-balance-scale"></i> Chia team
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
