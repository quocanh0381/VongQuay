/**
 * HomeController - Controller cho trang chủ
 */
import { HomeView } from '../views/HomeView.js';

export class HomeController {
    constructor() {
        this.view = new HomeView();
    }

    render() {
        const mainContent = document.getElementById('main-content');
        if (mainContent) {
            mainContent.innerHTML = this.view.render();
            this.setupEventListeners();
        }
    }

    setupEventListeners() {
        // Thêm hiệu ứng hover cho các card
        const cards = document.querySelectorAll('.mode-card, .info-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Xử lý nút tạo phòng
        document.getElementById('createRoomBtn')?.addEventListener('click', () => {
            this.showCreateRoomModal();
        });

        // Xử lý nút tham gia phòng
        document.getElementById('joinRoomBtn')?.addEventListener('click', () => {
            this.showJoinRoomModal();
        });
    }

    showCreateRoomModal() {
        const modal = this.createModal('Tạo Phòng Mới', `
            <div class="form-group">
                <label for="roomName">Tên phòng:</label>
                <input type="text" id="roomName" placeholder="Nhập tên phòng" required>
            </div>
            <div class="form-group">
                <label for="roomPassword">Mật khẩu phòng:</label>
                <input type="password" id="roomPassword" placeholder="Nhập mật khẩu phòng" required>
            </div>
            <div class="form-group">
                <label for="maxPlayers">Số người chơi tối đa:</label>
                <select id="maxPlayers">
                    <option value="6">6 người</option>
                    <option value="8">8 người</option>
                    <option value="10" selected>10 người</option>
                </select>
            </div>
            <div class="form-group">
                <label for="gameMode">Chế độ chơi:</label>
                <select id="gameMode">
                    <option value="skill">Theo kỹ năng</option>
                    <option value="random">Random</option>
                </select>
            </div>
        `, [
            { text: 'Hủy', class: 'btn-secondary', action: 'cancel' },
            { text: 'Tạo Phòng', class: 'btn-primary', action: 'create' }
        ]);

        document.body.appendChild(modal);
    }

    showJoinRoomModal() {
        const modal = this.createModal('Tham Gia Phòng', `
            <div class="form-group">
                <label for="roomCode">Mã phòng:</label>
                <input type="text" id="roomCode" placeholder="Nhập mã phòng" required>
            </div>
            <div class="form-group">
                <label for="roomPassword">Mật khẩu phòng:</label>
                <input type="password" id="roomPassword" placeholder="Nhập mật khẩu phòng" required>
            </div>
        `, [
            { text: 'Hủy', class: 'btn-secondary', action: 'cancel' },
            { text: 'Tham Gia', class: 'btn-primary', action: 'join' }
        ]);

        document.body.appendChild(modal);
    }

    createModal(title, content, buttons) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal">
                <div class="modal-header">
                    <h3>${title}</h3>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    ${content}
                </div>
                <div class="modal-footer">
                    ${buttons.map(btn => `
                        <button class="btn ${btn.class}" data-action="${btn.action}">
                            ${btn.text}
                        </button>
                    `).join('')}
                </div>
            </div>
        `;

        // Xử lý sự kiện
        modal.querySelector('.modal-close').addEventListener('click', () => {
            document.body.removeChild(modal);
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                document.body.removeChild(modal);
            }
        });

        buttons.forEach(btn => {
            modal.querySelector(`[data-action="${btn.action}"]`).addEventListener('click', () => {
                this.handleModalAction(btn.action, modal);
            });
        });

        return modal;
    }

    handleModalAction(action, modal) {
        if (action === 'create') {
            this.createRoom(modal);
        } else if (action === 'join') {
            this.joinRoom(modal);
        } else if (action === 'cancel') {
            this.closeModal(modal);
        }
    }

    closeModal(modal) {
        modal.style.animation = 'modalFadeOut 0.3s ease forwards';
        setTimeout(() => {
            if (document.body.contains(modal)) {
                document.body.removeChild(modal);
            }
        }, 300);
    }

    showLoading(button, text = 'Đang xử lý...') {
        button.classList.add('loading');
        button.disabled = true;
        const originalText = button.textContent;
        button.setAttribute('data-original-text', originalText);
        button.textContent = text;
    }

    hideLoading(button) {
        button.classList.remove('loading');
        button.disabled = false;
        const originalText = button.getAttribute('data-original-text');
        if (originalText) {
            button.textContent = originalText;
        }
    }

    async createRoom(modal) {
        const roomName = modal.querySelector('#roomName').value.trim();
        const roomPassword = modal.querySelector('#roomPassword').value.trim();
        const maxPlayers = modal.querySelector('#maxPlayers').value;
        const gameMode = modal.querySelector('#gameMode').value;

        if (!roomName || !roomPassword) {
            this.showNotification('Vui lòng nhập đầy đủ tên phòng và mật khẩu!', 'error');
            return;
        }

        const createBtn = modal.querySelector('[data-action="create"]');
        this.showLoading(createBtn, 'Đang tạo phòng...');

        try {
            // Simulate API call delay
            await new Promise(resolve => setTimeout(resolve, 1500));

            // Tạo mã phòng ngẫu nhiên
            const roomCode = this.generateRoomCode();
            
            // Tạo thông tin chủ phòng
            const hostPlayer = {
                id: Date.now(),
                name: 'Host_' + Date.now(), // Tạm thời tạo tên chủ phòng
                isHost: true,
                joinedAt: new Date().toISOString()
            };

            // Tạo phòng mới
            const room = {
                id: Date.now(),
                name: roomName,
                code: roomCode,
                password: roomPassword,
                maxPlayers: parseInt(maxPlayers),
                gameMode: gameMode,
                host: hostPlayer,
                players: [hostPlayer],
                status: 'waiting',
                createdAt: new Date().toISOString()
            };

            // Lưu phòng và thông tin chủ phòng vào localStorage
            localStorage.setItem('currentRoom', JSON.stringify(room));
            localStorage.setItem('currentPlayer', JSON.stringify(hostPlayer));
            
            this.showNotification(`Phòng "${roomName}" đã được tạo thành công!`, 'success');
            
            // Hiển thị thông tin phòng
            setTimeout(() => {
                this.showRoomInfo(roomCode, roomPassword, roomName);
            }, 1000);
            
        } catch (error) {
            this.showNotification('Có lỗi xảy ra khi tạo phòng. Vui lòng thử lại!', 'error');
        } finally {
            this.hideLoading(createBtn);
        }
    }

    async joinRoom(modal) {
        const roomCode = modal.querySelector('#roomCode').value.trim();
        const roomPassword = modal.querySelector('#roomPassword').value.trim();

        if (!roomCode || !roomPassword) {
            this.showNotification('Vui lòng nhập đầy đủ mã phòng và mật khẩu!', 'error');
            return;
        }

        const joinBtn = modal.querySelector('[data-action="join"]');
        this.showLoading(joinBtn, 'Đang tham gia...');

        try {
            // Simulate API call delay
            await new Promise(resolve => setTimeout(resolve, 1200));

            // Kiểm tra phòng có tồn tại không
            const currentRoom = localStorage.getItem('currentRoom');
            if (!currentRoom) {
                this.showNotification('Không tìm thấy phòng với mã này!', 'error');
                return;
            }

            const room = JSON.parse(currentRoom);
            
            // Kiểm tra mã phòng
            if (room.code !== roomCode) {
                this.showNotification('Mã phòng không đúng!', 'error');
                return;
            }

            // Kiểm tra mật khẩu phòng
            if (room.password !== roomPassword) {
                this.showNotification('Mật khẩu phòng không đúng!', 'error');
                return;
            }

            // Kiểm tra phòng đã đầy chưa
            if (room.players.length >= room.maxPlayers) {
                this.showNotification('Phòng đã đầy!', 'error');
                return;
            }

            // Kiểm tra trạng thái phòng
            if (room.status === 'playing') {
                this.showNotification('Phòng đang trong trận đấu, không thể tham gia!', 'error');
                return;
            }

            // Lưu thông tin người chơi hiện tại
            const currentPlayer = {
                id: Date.now(),
                name: 'Player_' + Date.now(), // Tạm thời tạo tên tự động
                isHost: false,
                joinedAt: new Date().toISOString()
            };

            // Thêm người chơi vào phòng
            room.players.push(currentPlayer);
            localStorage.setItem('currentRoom', JSON.stringify(room));
            localStorage.setItem('currentPlayer', JSON.stringify(currentPlayer));
            
            this.showNotification(`Chào mừng bạn đến với phòng "${room.name}"!`, 'success');
            
            // Đóng modal và chuyển hướng
            setTimeout(() => {
                this.closeModal(modal);
                window.location.hash = '#/room';
            }, 1000);
            
        } catch (error) {
            this.showNotification('Có lỗi xảy ra khi tham gia phòng. Vui lòng thử lại!', 'error');
        } finally {
            this.hideLoading(joinBtn);
        }
    }

    generateRoomCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        for (let i = 0; i < 6; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }

    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notif => notif.remove());

        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <div class="notification-icon">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                </div>
                <div class="notification-message">${message}</div>
                <button class="notification-close">&times;</button>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (document.body.contains(notification)) {
                notification.style.animation = 'notificationSlideOut 0.3s ease forwards';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }
        }, 5000);

        // Close button
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.style.animation = 'notificationSlideOut 0.3s ease forwards';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        });
    }

    showRoomInfo(roomCode, password, roomName) {
        const modal = this.createModal('Thông tin phòng', `
            <div class="room-info">
                <div class="room-info-item">
                    <label>Tên phòng:</label>
                    <span class="room-value">${roomName}</span>
                </div>
                <div class="room-info-item">
                    <label>Mã phòng:</label>
                    <span class="room-value room-code">${roomCode}</span>
                    <button class="copy-btn" onclick="navigator.clipboard.writeText('${roomCode}')">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <div class="room-info-item">
                    <label>Mật khẩu:</label>
                    <span class="room-value room-password">${password}</span>
                    <button class="copy-btn" onclick="navigator.clipboard.writeText('${password}')">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <div class="room-info-note">
                    <i class="fas fa-info-circle"></i>
                    <span>Hãy chia sẻ thông tin này với bạn bè để họ có thể tham gia phòng!</span>
                </div>
            </div>
        `, [
            { text: 'Đóng', class: 'btn-secondary', action: 'cancel' },
            { text: 'Vào phòng', class: 'btn-primary', action: 'enter' }
        ]);

        // Handle enter room action
        modal.querySelector('[data-action="enter"]').addEventListener('click', () => {
            this.closeModal(modal);
            window.location.hash = '#/room';
        });

        document.body.appendChild(modal);
    }
}
