<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'WishSwipe') }} - Swipe Your Way to Great Deals</title>

    <meta name="description" content="Discover amazing products in your area with WishSwipe's innovative swiping marketplace.">
    <meta name="keywords" content="marketplace, shopping, swipe, local deals, buy sell">
    <meta name="author" content="WishSwipe">
    

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="WishSwipe - Swipe Your Way to Great Deals">
    <meta property="og:description" content="Discover amazing products in your area with our innovative swiping marketplace.">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">


    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="WishSwipe - Swipe Your Way to Great Deals">
    <meta property="twitter:description" content="Discover amazing products in your area with our innovative swiping marketplace.">
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
</head>
<body>

    <nav class="nav-container">
        <div class="nav-content">
            <div class="nav-brand">
                <i class="fas fa-shopping-cart brand-icon"></i>
                <span class="brand-text">{{ config('app.name', 'WishSwipe') }}</span>
            </div>
            <div class="nav-links">
                <a href="#features" class="nav-link">{{ __('Features') }}</a>
                <a href="#how-it-works" class="nav-link">{{ __('How it Works') }}</a>
                <a href="#categories" class="nav-link">{{ __('Categories') }}</a>
                
                @auth
                    <a href="{{ url('/app') }}" class="btn btn-primary">{{ __('Dashboard') }}</a>
                @else
                    <a href="{{ url('/app/login') }}" class="nav-link">{{ __('Login') }}</a>
                    <a href="{{ url('/app/register') }}" class="btn btn-primary">{{ __('Get Started') }}</a>
                @endauth
            </div>
            <div class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    {{ __('Swipe Your Way to') }}
                    <span class="text-gradient">{{ __('Great Deals') }}</span>
                </h1>
                <p class="hero-description">
                    {{ __('Discover amazing products in your area with our innovative swiping marketplace. Swipe right on items you love, left on ones you don\'t.') }}
                </p>
                <div class="hero-buttons">
                    @guest
                        <a href="{{ url('/app/register') }}" class="btn btn-primary btn-large">
                            <i class="fas fa-mobile-alt mr-2"></i>
                            {{ __('Start Swiping') }}
                        </a>
                    @else
                        <a href="{{ url('/app') }}" class="btn btn-primary btn-large">
                            <i class="fas fa-home mr-2"></i>
                            {{ __('Go to App') }}
                        </a>
                    @endguest
                    <button class="btn btn-secondary btn-large" onclick="playVideo()">
                        <i class="fas fa-play mr-2"></i>
                        {{ __('Watch Demo') }}
                    </button>
                </div>
                <div class="hero-stats">
                    <div class="stat">
                        <span class="stat-number">50K+</span>
                        <span class="stat-label">{{ __('Happy Users') }}</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">1M+</span>
                        <span class="stat-label">{{ __('Items Swiped') }}</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">25K+</span>
                        <span class="stat-label">{{ __('Successful Deals') }}</span>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="phone-mockup">
                    <div class="phone-screen">
                        <div class="swipe-card active">
                            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=300&h=300&fit=crop" alt="{{ __('Product') }}">
                            <div class="card-info">
                                <h4>MacBook Pro</h4>
                                <p class="price">$1,299</p>
                                <p class="location">📍 {{ __('2km away') }}</p>
                            </div>
                        </div>
                        <div class="swipe-card">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=300&h=300&fit=crop" alt="{{ __('Product') }}">
                            <div class="card-info">
                                <h4>Nike Sneakers</h4>
                                <p class="price">$89</p>
                                <p class="location">📍 {{ __('1km away') }}</p>
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
                <h2 class="section-title">{{ __('Why Choose WishSwipe?') }}</h2>
                <p class="section-description">{{ __('Experience the future of online marketplace shopping') }}</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>{{ __('Intuitive Swiping') }}</h3>
                    <p>{{ __('Browse products. Quick decisions, better discoveries.') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>{{ __('AI-Powered Recommendations') }}</h3>
                    <p>{{ __('Our smart algorithm learns your preferences and shows you items you\'ll love.') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>{{ __('Location-Based') }}</h3>
                    <p>{{ __('Find items near you. No more waiting for shipping or paying expensive delivery fees.') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>{{ __('Secure Transactions') }}</h3>
                    <p>{{ __('Built-in security features and verification system to ensure safe buying and selling.') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3>{{ __('Smart Categories') }}</h3>
                    <p>{{ __('Easily browse through organized categories or let AI suggest the perfect items.') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>{{ __('Instant Chat') }}</h3>
                    <p>{{ __('Connect instantly with sellers through our built-in messaging system.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="how-it-works-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">{{ __('How It Works') }}</h2>
                <p class="section-description">{{ __('Get started in just 3 simple steps') }}</p>
            </div>
            <div class="steps-container">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>{{ __('Sign Up & Set Preferences') }}</h3>
                        <p>{{ __('Create your account and tell us what you\'re interested in. Our AI will learn your style.') }}</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>{{ __('Start Swiping') }}</h3>
                        <p>{{ __('Browse through curated items in your area. Swipe right on items you love, left on ones you don\'t.') }}</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>{{ __('Connect & Buy') }}</h3>
                        <p>{{ __('When you match with an item, instantly chat with the seller and arrange your purchase.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="categories" class="categories-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">{{ __('Popular Categories') }}</h2>
                <p class="section-description">{{ __('Discover amazing deals across all categories') }}</p>
            </div>
            <div class="categories-grid">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>{{ __('Electronics') }}</h3>
                    <p>{{ __('Phones, laptops, gadgets') }}</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <h3>{{ __('Fashion') }}</h3>
                    <p>{{ __('Clothing, shoes, accessories') }}</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3>{{ __('Home & Garden') }}</h3>
                    <p>{{ __('Furniture, decor, tools') }}</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <h3>{{ __('Vehicles') }}</h3>
                    <p>{{ __('Cars, bikes, parts') }}</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>{{ __('Books & Media') }}</h3>
                    <p>{{ __('Books, games, movies') }}</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <h3>{{ __('Sports') }}</h3>
                    <p>{{ __('Equipment, gear, fitness') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>{{ __('Ready to Start Swiping?') }}</h2>
                <p>{{ __('Join thousands of users who are already discovering amazing deals in their neighborhood.') }}</p>
                <div class="cta-buttons">
                    @guest
                        <a href="{{ url('/app/register') }}" class="btn btn-primary btn-large">
                            <i class="fas fa-user-plus mr-2"></i>
                            {{ __('Join WishSwipe') }}
                        </a>
                        <a href="{{ url('/app/login') }}" class="btn btn-outline btn-large">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            {{ __('Sign In') }}
                        </a>
                    @else
                        <a href="{{ url('/app') }}" class="btn btn-primary btn-large">
                            <i class="fas fa-home mr-2"></i>
                            {{ __('Go to Dashboard') }}
                        </a>
                        <a href="{{ url('/app/logout') }}" class="btn btn-outline btn-large" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            {{ __('Logout') }}
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
                    <p>{{ __('The future of marketplace shopping') }}</p>
                </div>
                <div class="footer-links">
                    <div class="link-group">
                        <h4>{{ __('Product') }}</h4>
                        <a href="#features">{{ __('Features') }}</a>
                        <a href="#how-it-works">{{ __('How it Works') }}</a>
                        <a href="#categories">{{ __('Categories') }}</a>
                    </div>
                    <div class="link-group">
                        <h4>{{ __('Company') }}</h4>
                        <a href="/about">{{ __('About') }}</a>
                        <a href="/contact">{{ __('Contact') }}</a>
                        <a href="/careers">{{ __('Careers') }}</a>
                    </div>
                    <div class="link-group">
                        <h4>{{ __('Support') }}</h4>
                        <a href="/help">{{ __('Help Center') }}</a>
                        <a href="/safety">{{ __('Safety') }}</a>
                        <a href="/terms">{{ __('Terms') }}</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'WishSwipe') }}. {{ __('All rights reserved.') }}</p>
                <div class="social-links">
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    @if(config('app.locale_switching_enabled', false))
        <div class="language-switcher">
            <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
            <a href="{{ route('lang.switch', 'lv') }}" class="{{ app()->getLocale() == 'lv' ? 'active' : '' }}">LV</a>
        </div>
    @endif

    <script src="{{ asset('js/landing.js') }}" defer></script>

    @if(config('app.env') === 'production')
    @endif

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebApplication",
        "name": "{{ config('app.name', 'WishSwipe') }}",
        "description": "{{ __('Discover amazing products in your area with our innovative swiping marketplace. Revolutionary shopping experience.') }}",
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