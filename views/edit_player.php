<?php
$page_title = "Sửa thông tin người chơi";

ob_start();
?>

<div class="edit-player-section">
    <div class="section-header">
        <h2><i class="fas fa-user-edit"></i> Sửa thông tin người chơi</h2>
        <a href="index.php?action=room&id=<?php echo $room_id; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="form-container">
        <form method="POST" class="edit-player-form">
            <div class="form-section">
                <h3><i class="fas fa-user"></i> Thông tin người chơi</h3>

                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i> Tên người chơi *
                    </label>
                    <input type="text" id="name" name="name" required
                           placeholder="Nhập tên người chơi..."
                           value="<?php echo htmlspecialchars($player['name']); ?>">
                </div>

                <div class="skills-grid">
                    <div class="skill-item">
                        <label for="personalSkill">
                            <i class="fas fa-star"></i> Kỹ năng cá nhân
                        </label>
                        <select id="personalSkill" name="personalSkill" required>
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($player['personalSkill'] == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?>/10
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="skill-item">
                        <label for="mapReading">
                            <i class="fas fa-map"></i> Đọc bản đồ
                        </label>
                        <select id="mapReading" name="mapReading" required>
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($player['mapReading'] == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?>/10
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="skill-item">
                        <label for="teamwork">
                            <i class="fas fa-users"></i> Làm việc nhóm
                        </label>
                        <select id="teamwork" name="teamwork" required>
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($player['teamwork'] == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?>/10
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="skill-item">
                        <label for="reaction">
                            <i class="fas fa-bolt"></i> Phản xạ
                        </label>
                        <select id="reaction" name="reaction" required>
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($player['reaction'] == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?>/10
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="skill-item">
                        <label for="experience">
                            <i class="fas fa-trophy"></i> Kinh nghiệm
                        </label>
                        <select id="experience" name="experience" required>
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($player['experience'] == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?>/10
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="skill-item">
                        <label for="position">
                            <i class="fas fa-crosshairs"></i> Vị trí
                        </label>
                        <select id="position" name="position" required>
                            <option value="1" <?php echo ($player['position'] == '1') ? 'selected' : ''; ?>>ADC</option>
                            <option value="2" <?php echo ($player['position'] == '2') ? 'selected' : ''; ?>>Fighter</option>
                            <option value="3" <?php echo ($player['position'] == '3') ? 'selected' : ''; ?>>Tank</option>
                            <option value="4" <?php echo ($player['position'] == '4') ? 'selected' : ''; ?>>Mage</option>
                            <option value="5" <?php echo ($player['position'] == '5') ? 'selected' : ''; ?>>Assassin</option>
                            <option value="6" <?php echo ($player['position'] == '6') ? 'selected' : ''; ?>>Support</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="index.php?action=room&id=<?php echo $room_id; ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
