/**
 * WheelView - View cho vòng quay
 */
export class WheelView {
    render() {
        return `
            <div class="wheel-section" id="wheelSection" style="display: none;">
                <div class="wheel-container">
                    <div class="wheel" id="wheel">
                        <div class="wheel-center">
                            <div class="wheel-pointer"></div>
                        </div>
                        <div class="wheel-segments" id="wheelSegments">
                            <!-- Các ô sẽ được tạo bằng JavaScript -->
                        </div>
                    </div>
                </div>
                <button class="btn btn-spin" id="spinBtn">
                    <i class="fas fa-sync-alt"></i> Quay
                </button>
            </div>
        `;
    }

    createWheel(players) {
        const wheelSegments = document.getElementById('wheelSegments');
        if (!wheelSegments) return;

        wheelSegments.innerHTML = '';

        const rankColors = { A: '#ff6b6b', B: '#4ecdc4', C: '#45b7d1', D: '#96ceb4' };

        players.forEach((player, index) => {
            const segment = document.createElement('div');
            segment.className = 'wheel-segment';
            segment.style.transform = `rotate(${index * 36}deg)`;
            segment.style.background = rankColors[player.rank];
            segment.innerHTML = `
                <div style="transform: rotate(${-index * 36}deg); text-align: center;">
                    <div style="font-size: 0.9rem; font-weight: bold;">${player.name}</div>
                    <div style="font-size: 0.7rem; opacity: 0.8;">Rank ${player.rank}</div>
                </div>
            `;
            wheelSegments.appendChild(segment);
        });
    }

    showWheelSection() {
        document.querySelector('.input-section').style.display = 'none';
        document.getElementById('wheelSection').style.display = 'block';
        document.getElementById('resultSection').style.display = 'none';
    }

    spinWheel() {
        const wheel = document.getElementById('wheel');
        const spinBtn = document.getElementById('spinBtn');
        
        if (!wheel || !spinBtn) return;

        spinBtn.disabled = true;
        spinBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang quay...';
        
        // Random rotation (5-10 full rotations + random angle)
        const randomRotations = 5 + Math.random() * 5;
        const randomAngle = Math.random() * 360;
        const totalRotation = randomRotations * 360 + randomAngle;
        
        wheel.style.transform = `rotate(${totalRotation}deg)`;
        wheel.classList.add('spinning');
        
        return new Promise(resolve => {
            setTimeout(() => {
                spinBtn.disabled = false;
                spinBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Quay lại';
                resolve();
            }, 4000);
        });
    }

    resetWheel() {
        const wheel = document.getElementById('wheel');
        const spinBtn = document.getElementById('spinBtn');
        
        if (wheel) {
            wheel.style.transform = 'rotate(0deg)';
            wheel.classList.remove('spinning');
        }
        
        if (spinBtn) {
            spinBtn.disabled = false;
            spinBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Quay';
        }
    }
}
