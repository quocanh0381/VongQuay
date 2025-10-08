/**
 * HomeView - View cho trang chủ
 */
export class HomeView {
    render() {
        return `
            <div class="home-section">
                <h2><i class="fas fa-gamepad"></i> Chọn chế độ chơi</h2>
                <div class="game-modes">
                    <div class="mode-card" id="createRoomCard">
                        <div class="mode-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <h3>Tạo Phòng</h3>
                        <p>Tạo phòng mới và mời bạn bè tham gia. Bạn sẽ là chủ phòng và có quyền điều khiển trò chơi.</p>
                        <div class="mode-badges">
                            <span class="badge">Chủ phòng</span>
                            <span class="badge">Điều khiển</span>
                            <span class="badge">Mời bạn</span>
                        </div>
                        <button class="mode-btn" id="createRoomBtn">
                            <i class="fas fa-plus"></i> Tạo Phòng
                        </button>
                    </div>

                    <div class="mode-card" id="joinRoomCard">
                        <div class="mode-icon">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <h3>Tham Gia Phòng</h3>
                        <p>Tham gia phòng có sẵn bằng mã phòng hoặc link mời. Chơi cùng với bạn bè trong phòng.</p>
                        <div class="mode-badges">
                            <span class="badge">Tham gia</span>
                            <span class="badge">Mã phòng</span>
                            <span class="badge">Cùng chơi</span>
                        </div>
                        <button class="mode-btn" id="joinRoomBtn">
                            <i class="fas fa-sign-in-alt"></i> Tham Gia
                        </button>
                    </div>
                </div>

                <div class="room-info-section">
                    <h3><i class="fas fa-info-circle"></i> Thông tin về hệ thống phòng</h3>
                    <div class="info-grid">
                        <div class="info-card">
                            <i class="fas fa-users"></i>
                            <h4>Chơi cùng bạn bè</h4>
                            <p>Tạo phòng và mời bạn bè tham gia để cùng chơi và chia team.</p>
                        </div>
                        <div class="info-card">
                            <i class="fas fa-key"></i>
                            <h4>Mã phòng bảo mật</h4>
                            <p>Mỗi phòng có mã riêng để đảm bảo chỉ người được mời mới có thể tham gia.</p>
                        </div>
                        <div class="info-card">
                            <i class="fas fa-crown"></i>
                            <h4>Quyền chủ phòng</h4>
                            <p>Chủ phòng có quyền điều khiển trò chơi, thêm/xóa người chơi.</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}
