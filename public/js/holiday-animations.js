// Holiday Animations System
class HolidayAnimations {
    constructor() {
        this.currentAnimation = null;
        this.container = document.getElementById('holiday-animations');
        this.init();
    }

    async init() {
        try {
            // Fetch active holiday settings
            const response = await fetch('/api/holidays/active');
            if (response.ok) {
                const holidays = await response.json();
                if (holidays && holidays.length > 0) {
                    const holiday = holidays[0];
                    this.startAnimation(holiday.animation_type, holiday.animation_config || {});
                }
            }
        } catch (error) {
            console.log('Holiday animations not available');
        }
    }

    startAnimation(type, config) {
        this.stopAnimation();

        switch(type) {
            case 'snow':
                this.startSnowAnimation(config);
                break;
            case 'santa':
                this.startSantaAnimation(config);
                break;
            case 'easter':
                this.startEasterAnimation(config);
                break;
            case 'halloween':
                this.startHalloweenAnimation(config);
                break;
            case 'christmas':
                this.startChristmasAnimation(config);
                break;
            case 'new_year':
                this.startNewYearAnimation(config);
                break;
        }
    }

    stopAnimation() {
        if (this.container) {
            this.container.innerHTML = '';
        }
        if (this.currentAnimation) {
            clearInterval(this.currentAnimation);
            this.currentAnimation = null;
        }
    }

    startSnowAnimation(config) {
        const snowflakes = config.count || 50;
        const speed = config.speed || 2;

        for (let i = 0; i < snowflakes; i++) {
            this.createSnowflake(speed);
        }
    }

    createSnowflake(speed) {
        const snowflake = document.createElement('div');
        snowflake.className = 'snowflake';
        snowflake.style.cssText = `
            position: fixed;
            top: -10px;
            left: ${Math.random() * 100}%;
            width: ${Math.random() * 5 + 5}px;
            height: ${Math.random() * 5 + 5}px;
            background: white;
            border-radius: 50%;
            pointer-events: none;
            opacity: ${Math.random() * 0.5 + 0.5};
            z-index: 9999;
            animation: fall ${Math.random() * 3 + 2}s linear infinite;
        `;
        this.container.appendChild(snowflake);

        setTimeout(() => {
            if (snowflake.parentNode) {
                snowflake.remove();
                this.createSnowflake(speed);
            }
        }, (Math.random() * 3 + 2) * 1000);
    }

    startSantaAnimation(config) {
        const santa = document.createElement('div');
        santa.innerHTML = '🎅';
        santa.style.cssText = `
            position: fixed;
            top: 20px;
            right: -100px;
            font-size: 60px;
            z-index: 9999;
            pointer-events: none;
            animation: santa-fly 15s linear infinite;
        `;
        this.container.appendChild(santa);

        const style = document.createElement('style');
        style.textContent = `
            @keyframes santa-fly {
                0% { right: -100px; }
                100% { right: 100%; }
            }
        `;
        document.head.appendChild(style);
    }

    startEasterAnimation(config) {
        const eggs = config.count || 20;
        for (let i = 0; i < eggs; i++) {
            setTimeout(() => {
                this.createEasterEgg();
            }, i * 500);
        }
    }

    createEasterEgg() {
        const egg = document.createElement('div');
        egg.innerHTML = '🥚';
        egg.style.cssText = `
            position: fixed;
            top: -50px;
            left: ${Math.random() * 100}%;
            font-size: 30px;
            z-index: 9999;
            pointer-events: none;
            animation: egg-fall ${Math.random() * 2 + 1}s linear;
        `;
        this.container.appendChild(egg);

        setTimeout(() => egg.remove(), 3000);
    }

    startHalloweenAnimation(config) {
        const pumpkins = config.count || 10;
        for (let i = 0; i < pumpkins; i++) {
            setTimeout(() => {
                this.createPumpkin();
            }, i * 1000);
        }
    }

    createPumpkin() {
        const pumpkin = document.createElement('div');
        pumpkin.innerHTML = '🎃';
        pumpkin.style.cssText = `
            position: fixed;
            top: ${Math.random() * 50}%;
            left: ${Math.random() * 100}%;
            font-size: 40px;
            z-index: 9999;
            pointer-events: none;
            animation: pumpkin-float 3s ease-in-out infinite;
        `;
        this.container.appendChild(pumpkin);
    }

    startChristmasAnimation(config) {
        this.startSnowAnimation({ count: 30, speed: 2 });
        this.startSantaAnimation({});
    }

    startNewYearAnimation(config) {
        const fireworks = config.count || 5;
        for (let i = 0; i < fireworks; i++) {
            setTimeout(() => {
                this.createFirework();
            }, i * 2000);
        }
    }

    createFirework() {
        const firework = document.createElement('div');
        firework.innerHTML = '🎆';
        firework.style.cssText = `
            position: fixed;
            top: ${Math.random() * 50}%;
            left: ${Math.random() * 100}%;
            font-size: 50px;
            z-index: 9999;
            pointer-events: none;
            animation: firework-explode 2s ease-out;
        `;
        this.container.appendChild(firework);

        setTimeout(() => firework.remove(), 2000);
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fall {
        to {
            transform: translateY(100vh) rotate(360deg);
        }
    }
    @keyframes egg-fall {
        to {
            transform: translateY(100vh) rotate(180deg);
        }
    }
    @keyframes pumpkin-float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(10deg); }
    }
    @keyframes firework-explode {
        0% { transform: scale(0); opacity: 1; }
        100% { transform: scale(2); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    new HolidayAnimations();
});

