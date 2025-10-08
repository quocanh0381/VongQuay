<?php
$page_title = "Đăng nhập - Vòng Quay Ngẫu Nhiên";
ob_start();
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2><i class="fas fa-sign-in-alt"></i> Đăng nhập</h2>
            <p>Đăng nhập để sử dụng hệ thống</p>
        </div>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="username">
                    <i class="fas fa-user"></i> Tên đăng nhập
                </label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo $_POST['username'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Mật khẩu
                </label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Đăng nhập
            </button>
        </form>

        <div class="auth-footer">
            <p>Chưa có tài khoản? <a href="index.php?action=register">Đăng ký ngay</a></p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
