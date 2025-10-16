<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'WishSwipe') }} - {{ __('messages.landing.meta.title') }}</title>
    <link rel="icon" href="images/wishSwipe_logo.png" type="favicon.ico"/>

    <meta name="description" content="{{ __('messages.landing.meta.description') }}">
    <meta name="keywords" content="{{ __('messages.landing.meta.keywords') }}">
    <meta name="author" content="WishSwipe">
    
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ __('messages.landing.meta.og_title') }}">
    <meta property="og:description" content="{{ __('messages.landing.meta.og_description') }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="{{ __('messages.landing.meta.twitter_title') }}">
    <meta property="twitter:description" content="{{ __('messages.landing.meta.twitter_description') }}">
    <meta property="twitter:image" content="{{ asset('images/og-image.jpg') }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
    
    <link rel="preload" href="{{ asset('css/landing.css') }}" as="style">
    <link rel="preload" href="{{ asset('js/landing.js') }}" as="script">

    <style>
    /* Desktop Language Switch */
    .premium-language-switch {
        position: relative;
        display: flex;
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        padding: 6px;
        margin-right: 1.5rem;
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.12),
            0 2px 8px rgba(0, 0, 0, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }

    .premium-lang-option {
        position: relative;
        display: flex;
        align-items: center;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: -0.01em;
        color: #64748b;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        gap: 8px;
        z-index: 2;
        background: transparent;
    }

    .premium-lang-option.active {
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .premium-lang-option:not(.active):hover {
        color: #3b82f6;
        background: rgba(59, 130, 246, 0.08);
    }

    .premium-language-switch::before {
        content: '';
        position: absolute;
        top: 6px;
        left: 6px;
        width: calc(50% - 6px);
        height: calc(100% - 12px);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 
            0 4px 16px rgba(102, 126, 234, 0.4),
            0 2px 8px rgba(102, 126, 234, 0.2);
        z-index: 1;
    }

    .premium-language-switch.lv-active::before {
        transform: translateX(100%);
    }

    .flag-emoji {
        font-size: 16px;
        line-height: 1;
    }

    /* Mobile Menu */
    .mobile-menu {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(0, 0, 0, 0.95);
        z-index: 1000;
        padding: 2rem;
        overflow-y: auto;
        flex-direction: column;
        animation: slideIn 0.3s ease-out;
    }

    .mobile-menu.active {
        display: flex;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .mobile-menu-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .mobile-menu-close {
        background: none;
        border: none;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        padding: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mobile-menu-links {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .mobile-menu-links a {
        color: white;
        text-decoration: none;
        font-size: 1.5rem;
        font-weight: 600;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        transition: color 0.3s ease;
    }

    .mobile-menu-links a:hover {
        color: #667eea;
    }

    .mobile-menu-links .btn {
        margin-top: 1rem;
        justify-content: center;
        font-size: 1.2rem;
        padding: 1rem 2rem;
    }

    /* Mobile Language Switch */
    .mobile-premium-language-switch {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        padding: 6px;
        display: flex;
        gap: 6px;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.12),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
        max-width: 300px;
        margin: 0 auto;
    }

    .mobile-premium-language-switch .premium-lang-option {
        flex: 1;
        justify-content: center;
        color: #64748b;
        background: transparent;
        padding: 12px 16px;
        font-weight: 600;
    }

    .mobile-premium-language-switch .premium-lang-option.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 
            0 4px 16px rgba(102, 126, 234, 0.4),
            0 2px 8px rgba(102, 126, 234, 0.2);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .mobile-premium-language-switch .premium-lang-option:not(.active):hover {
        background: rgba(59, 130, 246, 0.08);
        color: #3b82f6;
    }

    /* Responsive behavior */
    @media (max-width: 768px) {
        .premium-language-switch {
            display: none;
        }
        
        .nav-links {
            display: none;
        }
        
        .mobile-menu-btn {
            display: block !important;
        }
    }

    @media (min-width: 769px) {
        .mobile-menu-btn {
            display: none;
        }
    }
    </style>
</head>
<body>

    <nav class="nav-container">
        <div class="nav-content">
            <div class="nav-brand">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/wishswipe_logo_black.png') }}"
                        alt="{{ config('app.name', 'WishSwipe') }}"
                        class="brand-logo"
                        style="height: 80px !important; max-width: 350px !important;">
                </a>
            </div>
            <div class="nav-links">
                <a href="#features" class="nav-link">{{ __('messages.landing.nav.features') }}</a>
                <a href="#how-it-works" class="nav-link">{{ __('messages.landing.nav.how_it_works') }}</a>
                <a href="#categories" class="nav-link">{{ __('messages.landing.nav.categories') }}</a>
                
                @auth
                    <a href="{{ url('/app') }}" class="btn btn-primary">{{ __('messages.landing.nav.dashboard') }}</a>
                    @if(auth()->user()->hasRole('super_admin'))
                        <a href="{{ url('/admin') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Admin Panel
                        </a>
                    @endif
                @else
                    <a href="{{ url('/app/login') }}" class="nav-link">{{ __('messages.landing.nav.login') }}</a>
                    <a href="{{ url('/app/register') }}" class="btn btn-primary">{{ __('messages.landing.nav.get_started') }}</a>
                @endauth
                <div class="premium-language-switch {{ app()->getLocale() === 'lv' ? 'lv-active' : '' }}">
                    <a href="{{ route('lang.switch', 'en') }}" class="premium-lang-option {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                        <span class="flag-emoji">🇬🇧</span>
                    </a>
                    <a href="{{ route('lang.switch', 'lv') }}" class="premium-lang-option {{ app()->getLocale() === 'lv' ? 'active' : '' }}">
                        <span class="flag-emoji">🇱🇻</span>
                    </a>
                </div>
            </div>
            <div class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <div class="nav-brand">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/wishswipe_logo_black.png') }}"
                        alt="{{ config('app.name', 'WishSwipe') }}"
                        class="brand-logo"
                        style="height: 60px !important; filter: brightness(0) invert(1);">
                </a>
            </div>
            <button class="mobile-menu-close" onclick="toggleMobileMenu()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mobile-premium-language-switch">
            <a href="{{ route('lang.switch', 'en') }}" class="premium-lang-option {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                <span class="flag-emoji">🇬🇧</span>
                <span>English</span>
            </a>
            <a href="{{ route('lang.switch', 'lv') }}" class="premium-lang-option {{ app()->getLocale() === 'lv' ? 'active' : '' }}">
                <span class="flag-emoji">🇱🇻</span>
                <span>Latviešu</span>
            </a>
        </div>

        <div class="mobile-menu-links">
            <a href="#features" onclick="toggleMobileMenu()">{{ __('messages.landing.nav.features') }}</a>
            <a href="#how-it-works" onclick="toggleMobileMenu()">{{ __('messages.landing.nav.how_it_works') }}</a>
            <a href="#categories" onclick="toggleMobileMenu()">{{ __('messages.landing.nav.categories') }}</a>
            
            @auth
                @if(auth()->user()->hasRole('super_admin'))
                    <a href="{{ url('/admin') }}" class="btn btn-primary">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Admin Panel
                    </a>
                @else
                    <a href="{{ url('/app') }}" class="btn btn-primary">{{ __('messages.landing.nav.dashboard') }}</a>
                @endif
            @else
                <a href="{{ url('/app/login') }}">{{ __('messages.landing.nav.login') }}</a>
                <a href="{{ url('/app/register') }}" class="btn btn-primary">{{ __('messages.landing.nav.get_started') }}</a>
            @endauth
        </div>
    </div>

    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    {{ __('messages.landing.hero.swipe_your_way') }}
                    <span class="text-gradient">{{ __('messages.landing.hero.great_deals') }}</span>
                </h1>
                <p class="hero-description">
                    {{ __('messages.landing.hero.description') }}
                </p>
                <div class="hero-buttons">
                    @guest
                        <a href="{{ url('/app/register') }}" class="btn btn-primary btn-large">
                            <i class="fas fa-mobile-alt mr-2"></i>
                            {{ __('messages.landing.hero.start_swiping') }}
                        </a>
                    @else
                        @if(auth()->user()->hasRole('super_admin'))
                            <a href="{{ url('/admin') }}" class="btn btn-primary btn-large">
                                <i class="fas fa-shield-alt mr-2"></i>
                                Admin Panel
                            </a>
                        @else
                            <a href="{{ url('/app') }}" class="btn btn-primary btn-large">
                                <i class="fas fa-home mr-2"></i>
                                {{ __('messages.landing.hero.go_to_app') }}
                            </a>
                        @endif
                    @endguest
                    <button class="btn btn-secondary btn-large" onclick="playVideo()">
                        <i class="fas fa-play mr-2"></i>
                        {{ __('messages.landing.hero.watch_demo') }}
                    </button>
                </div>
                <div class="hero-stats">
                    <div class="stat">
                        <span class="stat-number">50K+</span>
                        <span class="stat-label">{{ __('messages.landing.hero.stats.happy_users') }}</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">1M+</span>
                        <span class="stat-label">{{ __('messages.landing.hero.stats.items_swiped') }}</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">25K+</span>
                        <span class="stat-label">{{ __('messages.landing.hero.stats.successful_deals') }}</span>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="phone-mockup">
                    <div class="phone-screen">
                        <!-- Card 1 -->
                        <div class="swipe-card active">
                            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=300&h=300&fit=crop" alt="{{ __('messages.landing.hero.product') }}">
                            <div class="card-info">
                                <h4>Apple Watch 11</h4>
                                <p class="price">$1,299</p>
                                <p class="location">📍 {{ __('messages.landing.hero.away', ['distance' => '2km']) }}</p>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="swipe-card">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=300&h=300&fit=crop" alt="{{ __('messages.landing.hero.product') }}">
                            <div class="card-info">
                                <h4>Nike Sneakers</h4>
                                <p class="price">$89</p>
                                <p class="location">📍 {{ __('messages.landing.hero.away', ['distance' => '1km']) }}</p>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="swipe-card">
                            <img src="https://plus.unsplash.com/premium_photo-1669380425564-6e1a281a4d30?w=1000&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8U2Ftc3VuZyUyMHR2fGVufDB8fDB8fHww" alt="Samsung TV">
                            <div class="card-info">
                                <h4>Samsung TV</h4>
                                <p class="price">$399</p>
                                <p class="location">📍 {{ __('messages.landing.hero.away', ['distance' => '3km']) }}</p>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="swipe-card">
                            <img src="https://images.unsplash.com/photo-1608354580875-30bd4168b351?w=1000&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Y29mZmVlJTIwbWFrZXJ8ZW58MHx8MHx8fDA%3D" alt="Coffee Maker">
                            <div class="card-info">
                                <h4>Coffee Maker</h4>
                                <p class="price">$45</p>
                                <p class="location">📍 {{ __('messages.landing.hero.away', ['distance' => '4km']) }}</p>
                            </div>
                        </div>

                        <!-- Card 5 -->
                        <div class="swipe-card">
                            <img src="https://images.unsplash.com/photo-1534150034764-046bf225d3fa?w=1000&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8bW91bnRhaW4lMjBiaWtlfGVufDB8fDB8fHww" alt="Mountain Bike">
                            <div class="card-info">
                                <h4>Mountain Bike</h4>
                                <p class="price">$250</p>
                                <p class="location">📍 {{ __('messages.landing.hero.away', ['distance' => '5km']) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="swipe-buttons">
                        <button class="swipe-btn dislike" onclick="swipeLeft()">
                            <i class="fas fa-times"></i>
                        </button>
                        <button class="swipe-btn like" onclick="swipeRight()">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="features-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">{{ __('messages.landing.features.title') }}</h2>
                <p class="section-description">{{ __('messages.landing.features.description') }}</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>{{ __('messages.landing.features.intuitive_swiping.title') }}</h3>
                    <p>{{ __('messages.landing.features.intuitive_swiping.description') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>{{ __('messages.landing.features.ai_recommendations.title') }}</h3>
                    <p>{{ __('messages.landing.features.ai_recommendations.description') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>{{ __('messages.landing.features.secure_transactions.title') }}</h3>
                    <p>{{ __('messages.landing.features.secure_transactions.description') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>{{ __('messages.landing.features.instant_chat.title') }}</h3>
                    <p>{{ __('messages.landing.features.instant_chat.description') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="how-it-works-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">{{ __('messages.landing.how_it_works.title') }}</h2>
                <p class="section-description">{{ __('messages.landing.how_it_works.description') }}</p>
            </div>
            <div class="steps-container">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>{{ __('messages.landing.how_it_works.step1.title') }}</h3>
                        <p>{{ __('messages.landing.how_it_works.step1.description') }}</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>{{ __('messages.landing.how_it_works.step2.title') }}</h3>
                        <p>{{ __('messages.landing.how_it_works.step2.description') }}</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>{{ __('messages.landing.how_it_works.step3.title') }}</h3>
                        <p>{{ __('messages.landing.how_it_works.step3.description') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="categories" class="categories-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">{{ __('messages.landing.categories.title') }}</h2>
                <p class="section-description">{{ __('messages.landing.categories.description') }}</p>
            </div>
            <div class="categories-grid">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>{{ __('messages.landing.categories.electronics.title') }}</h3>
                    <p>{{ __('messages.landing.categories.electronics.description') }}</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <h3>{{ __('messages.landing.categories.fashion.title') }}</h3>
                    <p>{{ __('messages.landing.categories.fashion.description') }}</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3>{{ __('messages.landing.categories.home_garden.title') }}</h3>
                    <p>{{ __('messages.landing.categories.home_garden.description') }}</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <h3>{{ __('messages.landing.categories.vehicles.title') }}</h3>
                    <p>{{ __('messages.landing.categories.vehicles.description') }}</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>{{ __('messages.landing.categories.books_media.title') }}</h3>
                    <p>{{ __('messages.landing.categories.books_media.description') }}</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <h3>{{ __('messages.landing.categories.sports.title') }}</h3>
                    <p>{{ __('messages.landing.categories.sports.description') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>{{ __('messages.landing.cta.title') }}</h2>
                <p>{{ __('messages.landing.cta.description') }}</p>
                <div class="cta-buttons">
                    @guest
                        <a href="{{ url('/app/register') }}" class="btn btn-primary btn-large">
                            <i class="fas fa-user-plus mr-2"></i>
                            {{ __('messages.landing.cta.join') }}
                        </a>
                        <a href="{{ url('/app/login') }}" class="btn btn-outline btn-large">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            {{ __('messages.landing.cta.sign_in') }}
                        </a>
                    @else
                        @if(auth()->user()->hasRole('super_admin'))
                            <a href="{{ url('/admin') }}" class="btn btn-primary btn-large">
                                <i class="fas fa-shield-alt mr-2"></i>
                                Admin Panel
                            </a>
                        @else
                            <a href="{{ url('/app') }}" class="btn btn-primary btn-large">
                                <i class="fas fa-home mr-2"></i>
                                {{ __('messages.landing.cta.go_to_dashboard') }}
                            </a>
                        @endif
                        <a href="{{ url('/app/logout') }}" class="btn btn-outline btn-large" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            {{ __('messages.landing.cta.logout') }}
                        </a>
                        <form id="logout-form" action="{{ url('/app/logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="brand">
                        <i class="fas fa-shopping-cart brand-icon"></i>
                        <span class="brand-text">{{ config('app.name', 'WishSwipe') }}</span>
                    </div>
                    <p>{{ __('messages.landing.footer.tagline') }}</p>
                </div>
                <div class="footer-links">
                    <div class="link-group">
                        <h4>{{ __('messages.landing.footer.product') }}</h4>
                        <a href="#features">{{ __('messages.landing.footer.features') }}</a>
                        <a href="#how-it-works">{{ __('messages.landing.footer.how_it_works') }}</a>
                        <a href="#categories">{{ __('messages.landing.footer.categories') }}</a>
                    </div>
                    <div class="link-group">
                        <h4>{{ __('messages.landing.footer.company') }}</h4>
                        <a href="/about">{{ __('messages.landing.footer.about') }}</a>
                        <a href="/contact">{{ __('messages.landing.footer.contact') }}</a>
                        <a href="/careers">{{ __('messages.landing.footer.careers') }}</a>
                    </div>
                    <div class="link-group">
                        <h4>{{ __('messages.landing.footer.support') }}</h4>
                        <a href="/help">{{ __('messages.landing.footer.help_center') }}</a>
                        <a href="/safety">{{ __('messages.landing.footer.safety') }}</a>
                        <a href="/terms">{{ __('messages.landing.footer.terms') }}</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'WishSwipe') }}. {{ __('messages.landing.footer.rights') }}</p>
                <div class="social-links">
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/landing.js') }}" defer></script>

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('active');
            
            // Prevent body scroll when menu is open
            if (mobileMenu.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        // Close mobile menu when clicking outside
        document.getElementById('mobileMenu')?.addEventListener('click', function(e) {
            if (e.target === this) {
                toggleMobileMenu();
            }
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const mobileMenu = document.getElementById('mobileMenu');
                if (mobileMenu.classList.contains('active')) {
                    toggleMobileMenu();
                }
            }
        });
    </script>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebApplication",
        "name": "{{ config('app.name', 'WishSwipe') }}",
        "description": "{{ __('messages.landing.meta.schema_description') }}",
        "url": "{{ url('/') }}",
        "applicationCategory": "Shopping",
        "operatingSystem": "Web",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "USD"
        }
    }
    </script>
</body>
</html>