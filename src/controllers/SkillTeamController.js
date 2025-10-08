/**
 * SkillTeamController - Controller cho chức năng chia team theo kỹ năng
 */
import { Game } from '../models/Game.js';
import { PlayerFormView } from '../views/PlayerFormView.js';
import { WheelView } from '../views/WheelView.js';
import { ResultView } from '../views/ResultView.js';

export class SkillTeamController {
    constructor() {
        this.game = new Game();
        this.game.setGameMode('skill');
        this.playerFormView = new PlayerFormView();
        this.wheelView = new WheelView();
        this.resultView = new ResultView();
        this.currentStep = 'input'; // 'input', 'wheel', 'result'
    }

    render() {
        const mainContent = document.getElementById('main-content');
        if (mainContent) {
            mainContent.innerHTML = `
                <header>
                    <h1><i class="fas fa-brain"></i> Lập Team Theo Kỹ Năng</h1>
                    <p>Chia team cân bằng dựa trên rank và mức độ hiểu game</p>
                </header>
                ${this.playerFormView.render()}
                ${this.wheelView.render()}
                ${this.resultView.render()}
            `;
            
            this.initializeGame();
            this.bindEvents();
        }
    }

    initializeGame() {
        this.playerFormView.createPlayerInputs();
        this.playerFormView.updateRankBadges();
    }

    bindEvents() {
        // Start game button
        document.getElementById('startSpinBtn')?.addEventListener('click', () => {
            this.startGame();
        });

        // Spin wheel button
        document.getElementById('spinBtn')?.addEventListener('click', () => {
            this.spinWheel();
        });

        // Reset button
        document.getElementById('resetBtn')?.addEventListener('click', () => {
            this.resetGame();
        });
    }

    startGame() {
        if (!this.playerFormView.validateInputs()) {
            alert('Vui lòng nhập đầy đủ tên cho ít nhất một người chơi!');
            return;
        }

        const playersData = this.playerFormView.collectPlayers();
        
        // Clear previous game data
        this.game.reset();
        
        // Add players to game
        playersData.forEach(playerData => {
            this.game.addPlayer(playerData);
        });

        // Create wheel
        this.wheelView.createWheel(this.game.players);
        this.wheelView.showWheelSection();
        this.currentStep = 'wheel';
    }

    async spinWheel() {
        await this.wheelView.spinWheel();
        this.distributeTeams();
        this.showResults();
    }

    distributeTeams() {
        try {
            const teams = this.game.createTeams();
            this.game.teams = teams;
        } catch (error) {
            console.error('Error creating teams:', error);
            alert('Có lỗi xảy ra khi tạo team: ' + error.message);
        }
    }

    showResults() {
        if (!this.game.teams.teamA || !this.game.teams.teamB) {
            console.error('Teams not created properly');
            return;
        }

        this.resultView.displayTeamResults(this.game.teams);
        
        const balanceInfo = this.game.getBalanceInfo();
        if (balanceInfo) {
            this.resultView.displayBalanceInfo(balanceInfo, 'skill');
        }
        
        this.resultView.showResults();
        this.currentStep = 'result';
    }

    resetGame() {
        this.game.reset();
        this.playerFormView.resetForm();
        this.wheelView.resetWheel();
        this.resultView.hideResults();
        
        // Show input section
        document.querySelector('.input-section').style.display = 'block';
        document.getElementById('wheelSection').style.display = 'none';
        document.getElementById('resultSection').style.display = 'none';
        
        this.currentStep = 'input';
    }
}
