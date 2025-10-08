<?php
$page_title = "Tạo phòng mới - Vòng Quay Ngẫu Nhiên";
ob_start();
?>

<div class="create-room-section">
    <div class="section-header">
        <h2><i class="fas fa-plus"></i> Tạo phòng mới</h2>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="form-container">
        <form method="POST" class="create-form">
            <div class="form-section">
                <h3><i class="fas fa-user"></i> Thông tin cá nhân</h3>
                
                <div class="form-group">
                    <label for="display_name">
                        <i class="fas fa-id-card"></i> Tên hiển thị *
                    </label>
                    <input type="text" id="display_name" name="display_name" required 
                           placeholder="Nhập tên hiển thị..." 
                           value="<?php echo $_POST['display_name'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="player_name">
                        <i class="fas fa-gamepad"></i> Tên trong game *
                    </label>
                    <input type="text" id="player_name" name="player_name" required 
                           placeholder="Nhập tên trong game..." 
                           value="<?php echo $_POST['player_name'] ?? ''; ?>">
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-door-open"></i> Thông tin phòng</h3>
                
                <div class="form-group">
                    <label for="room_name">
                        <i class="fas fa-home"></i> Tên phòng *
                    </label>
                    <input type="text" id="room_name" name="room_name" required 
                           placeholder="Nhập tên phòng..." 
                           value="<?php echo $_POST['room_name'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Mật khẩu phòng *
                    </label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Nhập mật khẩu phòng...">
                    <small class="form-help">Mật khẩu để bảo vệ phòng, người khác cần mật khẩu này để tham gia.</small>
                </div>

                <div class="form-group">
                    <label for="max_players">
                        <i class="fas fa-users"></i> Số người chơi tối đa
                    </label>
                    <select id="max_players" name="max_players">
                        <option value="5" <?php echo ($_POST['max_players'] ?? '10') == '5' ? 'selected' : ''; ?>>5 người</option>
                        <option value="10" <?php echo ($_POST['max_players'] ?? '10') == '10' ? 'selected' : ''; ?>>10 người</option>
                        <option value="15" <?php echo ($_POST['max_players'] ?? '10') == '15' ? 'selected' : ''; ?>>15 người</option>
                        <option value="20" <?php echo ($_POST['max_players'] ?? '10') == '20' ? 'selected' : ''; ?>>20 người</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tạo phòng
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>

    <div class="info-box">
        <h4><i class="fas fa-info-circle"></i> Thông tin</h4>
        <ul>
            <li>Mã phòng sẽ được tạo tự động</li>
            <li>Bạn sẽ trở thành chủ phòng</li>
            <li>Chia sẻ mã phòng và mật khẩu để mời bạn bè</li>
            <li>Có thể tạo nhiều match trong một phòng</li>
        </ul>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
