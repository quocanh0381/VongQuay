<?php
$page_title = "Tham gia phòng - Vòng Quay Ngẫu Nhiên";
ob_start();
?>

<div class="join-room-section">
    <div class="section-header">
        <h2><i class="fas fa-sign-in-alt"></i> Tham gia phòng</h2>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="form-container">
        <form method="POST" class="join-form">
            <div class="form-section">
                <h3><i class="fas fa-door-open"></i> Tham gia phòng</h3>
                
                <div class="form-group">
                    <label for="room_code">
                        <i class="fas fa-key"></i> Mã phòng *
                    </label>
                    <input type="text" id="room_code" name="room_code" required 
                           placeholder="Nhập mã phòng..." 
                           value="<?php echo $_POST['room_code'] ?? ''; ?>"
                           style="text-transform: uppercase;">
                    <small class="form-help">Mã phòng do chủ phòng cung cấp</small>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Mật khẩu phòng *
                    </label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Nhập mật khẩu phòng...">
                    <small class="form-help">Mật khẩu do chủ phòng cung cấp</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Tham gia phòng
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>

    <div class="info-box">
        <h4><i class="fas fa-info-circle"></i> Hướng dẫn</h4>
        <ol>
            <li>Xin mã phòng và mật khẩu từ chủ phòng</li>
            <li>Nhập mã phòng (không phân biệt hoa thường)</li>
            <li>Nhập mật khẩu chính xác</li>
            <li>Nhấn "Tham gia phòng" để vào phòng</li>
            <li>Sau khi vào phòng, bạn có thể thêm thông tin cá nhân</li>
        </ol>
    </div>
</div>

<script>
    // Auto uppercase room code
    document.getElementById('room_code').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });
</script>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
