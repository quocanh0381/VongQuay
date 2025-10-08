/**
 * HomeView - View cho trang chủ
 */
export class HomeView {
    render() {
        return `
            <div class="home-section">
                <h2><i class="fas fa-home"></i> Chọn chức năng</h2>
                <div class="features-grid">
                    <div class="feature-card" data-route="/skill-team">
                        <div class="feature-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h3>Lập Team Theo Kỹ Năng</h3>
                        <p>Chia team cân bằng dựa trên rank và mức độ hiểu game. Thuật toán thông minh đảm bảo mỗi team có đủ 5 đường.</p>
                        <div class="feature-badges">
                            <span class="badge">Cân bằng</span>
                            <span class="badge">Thông minh</span>
                            <span class="badge">5 đường</span>
                        </div>
                    </div>

                    <div class="feature-card" data-route="/random-team">
                        <div class="feature-icon">
                            <i class="fas fa-random"></i>
                        </div>
                        <h3>Lập Team Random</h3>
                        <p>Chia team hoàn toàn ngẫu nhiên. Phù hợp cho những trận đấu vui vẻ và thử thách.</p>
                        <div class="feature-badges">
                            <span class="badge">Ngẫu nhiên</span>
                            <span class="badge">Vui vẻ</span>
                            <span class="badge">Thử thách</span>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <h3><i class="fas fa-info-circle"></i> Thông tin về hệ thống</h3>
                    <div class="info-grid">
                        <div class="info-card">
                            <i class="fas fa-cogs"></i>
                            <h4>Thuật toán thông minh</h4>
                            <p>Hệ thống sử dụng thuật toán cân bằng phức tạp để đảm bảo độ công bằng tối đa.</p>
                        </div>
                        <div class="info-card">
                            <i class="fas fa-shield-alt"></i>
                            <h4>Đảm bảo 5 đường</h4>
                            <p>Mỗi team sẽ có đủ 5 vị trí: Top, Jungle, Mid, Support, AD.</p>
                        </div>
                        <div class="info-card">
                            <i class="fas fa-chart-line"></i>
                            <h4>Phân tích chi tiết</h4>
                            <p>Hiển thị thống kê chi tiết về độ cân bằng và phân bố kỹ năng.</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}
