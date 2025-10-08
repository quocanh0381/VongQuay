<?php
$page_title = "Thêm thành viên - " . htmlspecialchars($room['room_name']);

function getPositionName($position) {
    $positions = [
        1 => '🏹 Xạ Thủ (ADC)',
        2 => '⚔️ Đấu Sĩ (Fighter)', 
        3 => '🛡️ Đỡ Đòn (Tank)',
        4 => '🔮 Pháp Sư (Mage)',
        5 => '🗡️ Sát Thủ (Assassin)',
        6 => '💚 Hỗ Trợ (Support)'
    ];
    return $positions[$position] ?? 'Chưa chọn';
}

ob_start();
?>

<div class="add-member-section">
    <div class="section-header">
        <h2><i class="fas fa-user-plus"></i> Thêm thành viên</h2>
        <div class="room-info-header">
            <span class="room-name">Phòng: <?php echo htmlspecialchars($room['room_name']); ?></span>
            <a href="index.php?action=room&id=<?php echo $room['id']; ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="form-container">
        <form method="POST" class="add-member-form">
            <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
            
            <div class="form-section">
                <h3><i class="fas fa-user"></i> Thông tin người chơi</h3>
                
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-id-card"></i> Tên người chơi *
                    </label>
                    <input type="text" id="name" name="name" required 
                           placeholder="Nhập tên người chơi..." 
                           value="<?php echo $_POST['name'] ?? ''; ?>">
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-star"></i> Đánh giá kỹ năng (1-10)</h3>
                
                <div class="skills-grid">
                    <div class="skill-item">
                        <label for="personalSkill">
                            <i class="fas fa-user-ninja"></i> Kỹ năng cá nhân
                        </label>
                        <select id="personalSkill" name="personalSkill" required>
                            <?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (($_POST['personalSkill'] ?? 5) == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?> - <?php echo $i <= 3 ? 'Yếu' : ($i <= 6 ? 'Trung bình' : ($i <= 8 ? 'Khá' : 'Mạnh')); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="skill-item">
                        <label for="mapReading">
                            <i class="fas fa-map"></i> Đọc bản đồ
                        </label>
                        <select id="mapReading" name="mapReading" required>
                            <?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (($_POST['mapReading'] ?? 5) == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?> - <?php echo $i <= 3 ? 'Yếu' : ($i <= 6 ? 'Trung bình' : ($i <= 8 ? 'Khá' : 'Mạnh')); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="skill-item">
                        <label for="teamwork">
                            <i class="fas fa-users"></i> Làm việc nhóm
                        </label>
                        <select id="teamwork" name="teamwork" required>
                            <?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (($_POST['teamwork'] ?? 5) == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?> - <?php echo $i <= 3 ? 'Yếu' : ($i <= 6 ? 'Trung bình' : ($i <= 8 ? 'Khá' : 'Mạnh')); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="skill-item">
                        <label for="reaction">
                            <i class="fas fa-bolt"></i> Phản xạ
                        </label>
                        <select id="reaction" name="reaction" required>
                            <?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (($_POST['reaction'] ?? 5) == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?> - <?php echo $i <= 3 ? 'Yếu' : ($i <= 6 ? 'Trung bình' : ($i <= 8 ? 'Khá' : 'Mạnh')); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="skill-item">
                        <label for="experience">
                            <i class="fas fa-graduation-cap"></i> Kinh nghiệm
                        </label>
                        <select id="experience" name="experience" required>
                            <?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (($_POST['experience'] ?? 5) == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?> - <?php echo $i <= 3 ? 'Yếu' : ($i <= 6 ? 'Trung bình' : ($i <= 8 ? 'Khá' : 'Mạnh')); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="skill-item">
                        <label for="position">
                            <i class="fas fa-crosshairs"></i> Vị trí
                        </label>
                        <select id="position" name="position" required>
                            <option value="">Chọn vị trí</option>
                            <option value="1" <?php echo (($_POST['position'] ?? '') == '1') ? 'selected' : ''; ?>>ADC</option>
                            <option value="2" <?php echo (($_POST['position'] ?? '') == '2') ? 'selected' : ''; ?>>Fighter</option>
                            <option value="3" <?php echo (($_POST['position'] ?? '') == '3') ? 'selected' : ''; ?>>Tank</option>
                            <option value="4" <?php echo (($_POST['position'] ?? '') == '4') ? 'selected' : ''; ?>>Mage</option>
                            <option value="5" <?php echo (($_POST['position'] ?? '') == '5') ? 'selected' : ''; ?>>Assassin</option>
                            <option value="6" <?php echo (($_POST['position'] ?? '') == '6') ? 'selected' : ''; ?>>Support</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Thêm thành viên
                </button>
                <a href="index.php?action=room&id=<?php echo $room['id']; ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>

    <div class="info-box">
        <h4><i class="fas fa-info-circle"></i> Hướng dẫn đánh giá kỹ năng Liên Quân</h4>
        <ul>
            <li><strong>Đánh giá kỹ năng từ 1 (yếu) đến 10 (mạnh)</strong></li>
            <li><strong>Kỹ năng cá nhân:</strong> Khả năng xử lý tình huống 1v1, farm lane, solo carry</li>
            <li><strong>Đọc bản đồ:</strong> Khả năng phân tích minimap, gank, rotate, vision control</li>
            <li><strong>Làm việc nhóm:</strong> Khả năng teamfight, combo skill, phối hợp với đồng đội</li>
            <li><strong>Phản xạ:</strong> Tốc độ phản ứng với skill shot, dodge, counter gank</li>
            <li><strong>Kinh nghiệm:</strong> Mức độ am hiểu meta, build đồ, timing, macro game</li>
            <li><strong>Vị trí ưa thích:</strong> Chọn vị trí bạn chơi tốt nhất trong Liên Quân</li>
        </ul>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
