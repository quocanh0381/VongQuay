<?php
$page_title = "Thêm người chơi - " . htmlspecialchars($match['match_name']);
ob_start();
?>

<div class="add-player-section">
    <div class="section-header">
        <h2><i class="fas fa-user-plus"></i> Thêm người chơi</h2>
        <div class="match-info-header">
            <span class="match-name">Match: <?php echo htmlspecialchars($match['match_name']); ?></span>
            <a href="index.php?action=match&id=<?php echo $match['id']; ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="form-container">
        <form method="POST" class="add-player-form">
            <input type="hidden" name="match_id" value="<?php echo $match['id']; ?>">
            
            <div class="form-section">
                <h3><i class="fas fa-user"></i> Thông tin cá nhân</h3>
                
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
                        <div class="skill-input">
                            <input type="range" id="personalSkill" name="personalSkill" 
                                   min="1" max="10" value="<?php echo $_POST['personalSkill'] ?? 5; ?>" 
                                   oninput="updateSkillValue('personalSkill', this.value)">
                            <span class="skill-value" id="personalSkill-value"><?php echo $_POST['personalSkill'] ?? 5; ?></span>
                        </div>
                    </div>

                    <div class="skill-item">
                        <label for="mapReading">
                            <i class="fas fa-map"></i> Đọc bản đồ
                        </label>
                        <div class="skill-input">
                            <input type="range" id="mapReading" name="mapReading" 
                                   min="1" max="10" value="<?php echo $_POST['mapReading'] ?? 5; ?>" 
                                   oninput="updateSkillValue('mapReading', this.value)">
                            <span class="skill-value" id="mapReading-value"><?php echo $_POST['mapReading'] ?? 5; ?></span>
                        </div>
                    </div>

                    <div class="skill-item">
                        <label for="teamwork">
                            <i class="fas fa-users"></i> Làm việc nhóm
                        </label>
                        <div class="skill-input">
                            <input type="range" id="teamwork" name="teamwork" 
                                   min="1" max="10" value="<?php echo $_POST['teamwork'] ?? 5; ?>" 
                                   oninput="updateSkillValue('teamwork', this.value)">
                            <span class="skill-value" id="teamwork-value"><?php echo $_POST['teamwork'] ?? 5; ?></span>
                        </div>
                    </div>

                    <div class="skill-item">
                        <label for="reaction">
                            <i class="fas fa-bolt"></i> Phản xạ
                        </label>
                        <div class="skill-input">
                            <input type="range" id="reaction" name="reaction" 
                                   min="1" max="10" value="<?php echo $_POST['reaction'] ?? 5; ?>" 
                                   oninput="updateSkillValue('reaction', this.value)">
                            <span class="skill-value" id="reaction-value"><?php echo $_POST['reaction'] ?? 5; ?></span>
                        </div>
                    </div>

                    <div class="skill-item">
                        <label for="experience">
                            <i class="fas fa-graduation-cap"></i> Kinh nghiệm
                        </label>
                        <div class="skill-input">
                            <input type="range" id="experience" name="experience" 
                                   min="1" max="10" value="<?php echo $_POST['experience'] ?? 5; ?>" 
                                   oninput="updateSkillValue('experience', this.value)">
                            <span class="skill-value" id="experience-value"><?php echo $_POST['experience'] ?? 5; ?></span>
                        </div>
                    </div>

                    <div class="skill-item">
                        <label for="position">
                            <i class="fas fa-crosshairs"></i> Vị trí ưa thích
                        </label>
                        <div class="position-select">
                            <select id="position" name="position" required>
                                <option value="">Chọn vị trí ưa thích</option>
                                <option value="1" <?php echo (($_POST['position'] ?? '') == '1') ? 'selected' : ''; ?>>🏹 Xạ Thủ (ADC)</option>
                                <option value="2" <?php echo (($_POST['position'] ?? '') == '2') ? 'selected' : ''; ?>>⚔️ Đấu Sĩ (Fighter)</option>
                                <option value="3" <?php echo (($_POST['position'] ?? '') == '3') ? 'selected' : ''; ?>>🛡️ Đỡ Đòn (Tank)</option>
                                <option value="4" <?php echo (($_POST['position'] ?? '') == '4') ? 'selected' : ''; ?>>🔮 Pháp Sư (Mage)</option>
                                <option value="5" <?php echo (($_POST['position'] ?? '') == '5') ? 'selected' : ''; ?>>🗡️ Sát Thủ (Assassin)</option>
                                <option value="6" <?php echo (($_POST['position'] ?? '') == '6') ? 'selected' : ''; ?>>💚 Hỗ Trợ (Support)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Thêm người chơi
                </button>
                <a href="index.php?action=match&id=<?php echo $match['id']; ?>" class="btn btn-secondary">
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
        
        <div class="position-guide">
            <h5><i class="fas fa-gamepad"></i> Vị trí trong Liên Quân:</h5>
            <div class="position-list">
                <span class="position-tag">🏹 Xạ Thủ (ADC)</span>
                <span class="position-tag">⚔️ Đấu Sĩ (Fighter)</span>
                <span class="position-tag">🛡️ Đỡ Đòn (Tank)</span>
                <span class="position-tag">🔮 Pháp Sư (Mage)</span>
                <span class="position-tag">🗡️ Sát Thủ (Assassin)</span>
                <span class="position-tag">💚 Hỗ Trợ (Support)</span>
            </div>
        </div>
    </div>
</div>

<script>
function updateSkillValue(skillId, value) {
    document.getElementById(skillId + '-value').textContent = value;
}
</script>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
