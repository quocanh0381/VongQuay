<?php
$page_title = "Đăng ký - Vòng Quay Ngẫu Nhiên";
ob_start();
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2><i class="fas fa-user-plus"></i> Đăng ký</h2>
            <p>Tạo tài khoản mới để sử dụng hệ thống</p>
        </div>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="username">
                    <i class="fas fa-user"></i> Tên đăng nhập *
                </label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo $_POST['username'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Mật khẩu *
                </label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="display_name">
                    <i class="fas fa-id-card"></i> Tên hiển thị *
                </label>
                <input type="text" id="display_name" name="display_name" required 
                       value="<?php echo $_POST['display_name'] ?? ''; ?>">
            </div>


            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Đăng ký
            </button>
        </form>

        <div class="auth-footer">
            <p>Đã có tài khoản? <a href="index.php?action=login">Đăng nhập ngay</a></p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
