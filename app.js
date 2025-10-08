/**
 * Vòng Quay Ngẫu Nhiên - Ứng dụng MVC
 * File chính để khởi chạy ứng dụng
 */

// Import các module cần thiết
import { Router } from './src/router/Router.js';
import { HomeController } from './src/controllers/HomeController.js';
import { SkillTeamController } from './src/controllers/SkillTeamController.js';
import { RandomTeamController } from './src/controllers/RandomTeamController.js';
import { PlayerController } from './src/controllers/PlayerController.js';

class App {
    constructor() {
        this.router = new Router();
        this.initializeRoutes();
        this.start();
    }

    initializeRoutes() {
        // Đăng ký các routes
        this.router.addRoute('/', () => new HomeController().render());
        this.router.addRoute('/skill-team', () => new SkillTeamController().render());
        this.router.addRoute('/random-team', () => new RandomTeamController().render());
        this.router.addRoute('/players', () => new PlayerController().render());
    }

    start() {
        // Khởi tạo ứng dụng
        this.router.init();
        
        // Thêm event listeners cho navigation
        this.setupNavigation();
        
        console.log('🎮 Vòng Quay Ngẫu Nhiên - Ứng dụng đã khởi động!');
    }

    setupNavigation() {
        // Xử lý navigation cho các link
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-route]')) {
                e.preventDefault();
                const route = e.target.getAttribute('data-route');
                this.router.navigate(route);
            }
        });

        // Xử lý browser back/forward
        window.addEventListener('popstate', (e) => {
            this.router.handlePopState(e);
        });
    }
}

// Khởi chạy ứng dụng khi DOM đã load
document.addEventListener('DOMContentLoaded', () => {
    new App();
});

// Export cho việc testing
export { App };
