document.addEventListener('DOMContentLoaded', function() {
    initNavigation();
    initScrollAnimations();
    initSwipeDemo();
    initSmoothScrolling();
    initParallax();
});
function initNavigation() {
    const nav = document.querySelector('.nav-container');
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');

    // Scroll effect
    let lastScrollTop = 0;
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 100) {
            nav.style.background = 'rgba(255, 255, 255, 0.98)';
            nav.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
        } else {
            nav.style.background = 'rgba(255, 255, 255, 0.95)';
            nav.style.boxShadow = 'none';
        }

        // Hide/show nav on scroll
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            nav.style.transform = 'translateY(-100%)';
        } else {
            nav.style.transform = 'translateY(0)';
        }
        lastScrollTop = scrollTop;
    });

    // Mobile menu toggle
    if (mobileMenuBtn && navLinks) {
        mobileMenuBtn.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            const icon = mobileMenuBtn.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        });
    }
}

// Scroll animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, observerOptions);

    // Observe
    const animatedElements = document.querySelectorAll('.feature-card, .step, .category-card, .section-header');
    animatedElements.forEach(el => {
        observer.observe(el);
    });
}

// Swipe demo functionality
function initSwipeDemo() {
    const cards = document.querySelectorAll('.swipe-card');
    const likeBtn = document.querySelector('.swipe-btn.like');
    const dislikeBtn = document.querySelector('.swipe-btn.dislike');
    
    let currentCardIndex = 0;
    
    // // Auto-cycle through cards
    // setInterval(() => {
    //     if (cards.length > 0) {
    //         // Hide current card
    //         cards[currentCardIndex].classList.remove('active');
            
    //         // Show next card
    //         currentCardIndex = (currentCardIndex + 1) % cards.length;
    //         cards[currentCardIndex].classList.add('active');
    //     }
    // }, 4000);

    // Manual swipe controls
    if (likeBtn) {
        likeBtn.addEventListener('click', swipeRight);
    }
    
    if (dislikeBtn) {
        dislikeBtn.addEventListener('click', swipeLeft);
    }
}

// Swipe right
function swipeRight() {
    const activeCard = document.querySelector('.swipe-card.active');
    if (activeCard) {
        //swipe animation
        activeCard.style.transform = 'translateX(100%) rotate(20deg)';
        activeCard.style.opacity = '0';
        
        //Create cart effect
        createcartEffect();
        
        // reset
        setTimeout(() => {
            activeCard.style.transform = '';
            activeCard.style.opacity = '';
            nextCard();
        }, 300);
    }
}

// Swipeleft animation
function swipeLeft() {
    const activeCard = document.querySelector('.swipe-card.active');
    if (activeCard) {
        // swipe animation
        activeCard.style.transform = 'translateX(-100%) rotate(-20deg)';
        activeCard.style.opacity = '0';
        
        // Create X effect
        createXEffect();
        
        // Reset
        setTimeout(() => {
            activeCard.style.transform = '';
            activeCard.style.opacity = '';
            nextCard();
        }, 300);
    }
}

// Next card func
function nextCard() {
    const cards = document.querySelectorAll('.swipe-card');
    const activeCard = document.querySelector('.swipe-card.active');
    
    if (cards.length > 1) {
        activeCard.classList.remove('active');
        const currentIndex = Array.from(cards).indexOf(activeCard);
        const nextIndex = (currentIndex + 1) % cards.length;
        cards[nextIndex].classList.add('active');
    }
}

// cart like
function createcartEffect() {
    const phoneScreen = document.querySelector('.phone-screen');
    const cart = document.createElement('div');
    cart.innerHTML = '🛒';
    cart.style.position = 'absolute';
    cart.style.fontSize = '2rem';
    cart.style.top = '50%';
    cart.style.left = '50%';
    cart.style.transform = 'translate(-50%, -50%)';
    cart.style.animation = 'cartPop 0.6s ease-out';
    cart.style.pointerEvents = 'none';
    cart.style.zIndex = '10';
    
    phoneScreen.appendChild(cart);
    
    setTimeout(() => {
        phoneScreen.removeChild(cart);
    }, 600);
}

// X
function createXEffect() {
    const phoneScreen = document.querySelector('.phone-screen');
    const x = document.createElement('div');
    x.innerHTML = '❌';
    x.style.position = 'absolute';
    x.style.fontSize = '2rem';
    x.style.top = '50%';
    x.style.left = '50%';
    x.style.transform = 'translate(-50%, -50%)';
    x.style.animation = 'xPop 0.6s ease-out';
    x.style.pointerEvents = 'none';
    x.style.zIndex = '10';
    
    phoneScreen.appendChild(x);
    
    setTimeout(() => {
        phoneScreen.removeChild(x);
    }, 600);
}

// Smooth scrolling for nav
function initSmoothScrolling() {
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const navHeight = document.querySelector('.nav-container').offsetHeight;
                const targetPosition = targetElement.offsetTop - navHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// effct for hero section
function initParallax() {
    const heroSection = document.querySelector('.hero-section');
    
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.5;
        
        if (heroSection) {
            heroSection.style.transform = `translateY(${rate}px)`;
        }
    });
}

// Vid modal functionality
function playVideo() {
    // modal overlay
    const modal = document.createElement('div');
    modal.className = 'video-modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        animation: fadeIn 0.3s ease-out;
    `;
    
    // video container
    const videoContainer = document.createElement('div');
    videoContainer.style.cssText = `
        position: relative;
        width: 80%;
        max-width: 800px;
        aspect-ratio: 16/9;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
    `;
    
    // placeholder video, kad bus done projekts tad video!!!
    const video = document.createElement('div');
    video.innerHTML = `
        <div style="
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 2rem;
            text-align: center;
        ">
            <div>
                <i class="fas fa-play-circle" style="font-size: 4rem; margin-bottom: 1rem; display: block;"></i>
                Demo Video Coming Soon!
            </div>
        </div>
    `;
    
    // Create close button
    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '<i class="fas fa-times"></i>';
    closeBtn.style.cssText = `
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.2rem;
        z-index: 1;
    `;
    
    // Assemble modal
    videoContainer.appendChild(video);
    videoContainer.appendChild(closeBtn);
    modal.appendChild(videoContainer);
    document.body.appendChild(modal);
    
    // Close modal
    const closeModal = () => {
        modal.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => {
            document.body.removeChild(modal);
        }, 300);
    };
    
    closeBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Close on esc
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
}

// Counter for Statistics animation
function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');
    
    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            const suffix = counter.textContent.includes('K') ? 'K+' : 
                          counter.textContent.includes('M') ? 'M+' : '+';
            counter.textContent = Math.floor(current) + suffix;
        }, 16);
    });
}

// Trigger counter
const statsObserver = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animateCounters();
            statsObserver.unobserve(entry.target);
        }
    });
});

const heroStats = document.querySelector('.hero-stats');
if (heroStats) {
    statsObserver.observe(heroStats);
}

// CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes cartPop {
        0% {
            transform: translate(-50%, -50%) scale(0);
        }
        50% {
            transform: translate(-50%, -50%) scale(1.2);
        }
        100% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 0;
        }
    }
    
    @keyframes xPop {
        0% {
            transform: translate(-50%, -50%) scale(0);
        }
        50% {
            transform: translate(-50%, -50%) scale(1.2);
        }
        100% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 0;
        }
    }
    
    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
    
    .nav-links.active {
        display: flex !important;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        flex-direction: column;
        padding: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    @media (max-width: 768px) {
        .nav-links {
            display: none;
        }
    }
`;
document.head.appendChild(style);

// easter ;)
let konamiCode = [];
const konamiSequence = [
    'ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown',
    'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight',
    'KeyB', 'KeyA'
];

document.addEventListener('keydown', function(e) {
    konamiCode.push(e.code);
    if (konamiCode.length > konamiSequence.length) {
        konamiCode.shift();
    }
    
    if (JSON.stringify(konamiCode) === JSON.stringify(konamiSequence)) {
        // activate
        const icons = ['<i class="fas fa-star"></i>', '<i class="fas fa-gem"></i>', '<i class="fas fa-crown"></i>', '<i class="fas fa-trophy"></i>', '<i class="fas fa-medal"></i>'];
        for (let i = 0; i < 20; i++) {
            setTimeout(() => {
                const icon = document.createElement('div');
                icon.innerHTML = icons[Math.floor(Math.random() * icons.length)];
                icon.style.cssText = `
                    position: fixed;
                    font-size: 2rem;
                    color: gold;
                    pointer-events: none;
                    z-index: 10000;
                    animation: fall 3s linear forwards;
                    left: ${Math.random() * 100}%;
                    top: -50px;
                `;
                document.body.appendChild(icon);
                
                setTimeout(() => {
                    document.body.removeChild(icon);
                }, 3000);
            }, i * 100);
        }
        
        // fall animation
        if (!document.querySelector('#fall-animation')) {
            const fallStyle = document.createElement('style');
            fallStyle.id = 'fall-animation';
            fallStyle.textContent = `
                @keyframes fall {
                    to {
                        transform: translateY(100vh) rotate(360deg);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(fallStyle);
        }
        
        konamiCode = [];
    }
});

// loading state
window.addEventListener('load', function() {
    document.body.classList.add('loaded');
    
    // Trigger anim
    const heroTitle = document.querySelector('.hero-title');
    const heroDescription = document.querySelector('.hero-description');
    
    if (heroTitle) {
        heroTitle.style.animation = 'slideUp 0.8s ease-out';
    }
    
    if (heroDescription) {
        heroDescription.style.animation = 'slideUp 0.8s ease-out 0.2s both';
    }
});

// performance optimization
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

//debouncing to scroll events
const debouncedParallax = debounce(function() {
    const heroSection = document.querySelector('.hero-section');
    const scrolled = window.pageYOffset;
    const rate = scrolled * -0.5;
    
    if (heroSection) {
        heroSection.style.transform = `translateY(${rate}px)`;
    }
}, 10);

window.addEventListener('scroll', debouncedParallax);