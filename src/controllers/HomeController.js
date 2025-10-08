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
        const cards = document.querySelectorAll('.feature-card, .info-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    }
}
