<?php
$page_title = "Tạo match mới - Vòng Quay Ngẫu Nhiên";
ob_start();
?>

<div class="create-match-section">
    <div class="section-header">
        <h2><i class="fas fa-plus"></i> Tạo match mới</h2>
        <a href="index.php?action=matches" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="form-container">
        <form method="POST" class="create-form">
            <?php if (isset($_GET['room_id'])): ?>
                <input type="hidden" name="room_id" value="<?php echo $_GET['room_id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="match_name">
                    <i class="fas fa-gamepad"></i> Tên match *
                </label>
                <input type="text" id="match_name" name="match_name" required 
                       placeholder="Nhập tên match..." 
                       value="<?php echo $_POST['match_name'] ?? ''; ?>">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tạo match
                </button>
                <a href="index.php?action=matches" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>

    <div class="info-box">
        <h4><i class="fas fa-info-circle"></i> Thông tin</h4>
        <ul>
            <li>Match sẽ được tạo và bạn có thể thêm players</li>
            <li>Bạn sẽ trở thành người tạo match</li>
            <li>Có thể thêm nhiều players với đánh giá kỹ năng</li>
            <li>Sử dụng tính năng chia team để cân bằng</li>
        </ul>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
