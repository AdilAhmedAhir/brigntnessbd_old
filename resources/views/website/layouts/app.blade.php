<!DOCTYPE html>
<html lang="en">
<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-T9KVRKBX');</script>
<!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $siteSettings['site_name'] . ' - ' . $siteSettings['site_description'])</title>
    
    @if($siteSettings['site_icon_url'])
        <link rel="icon" type="image/x-icon" href="{{ $siteSettings['site_icon_url'] }}">
        <link rel="shortcut icon" href="{{ $siteSettings['site_icon_url'] }}">
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('styles')

    <style>
        :root {
            /* Primary Color Variables */
            --primary-color: #1a1a1a;
            --primary-color-light: #2c2c2c;
            --primary-color-dark: #0f0f0f;
            --primary-gradient: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color-light) 100%);
            
            /* Golden Accent Color */
            --golden-color: #d4af37;
            --golden-light: #f4d467;
            --golden-dark: #b8941f;
            --golden-gradient: linear-gradient(135deg, var(--golden-color) 0%, var(--golden-light) 100%);
            
            /* Text Colors */
            --text-primary: var(--primary-color);
            --text-light: #ffffff;
            --text-golden: var(--golden-color);
        }
        .luxury-header {
            display: none !important;
        }

        .mobile-header {
            display: block !important;
        }
        
        body.homepage .mobile-header{
            background: unset;
            margin-bottom: -101px;
            z-index: 9;
            position: relative;
        }

        @media (min-width: 992px) {
            body.homepage .mobile-header {
                display: none !important;
            }

            body.homepage .luxury-header {
                display: block !important;
            }
        }

    </style>

    <link rel="stylesheet" href="{{ asset('site-asset/css/style.css?v=1') }}">
    
    <link rel="stylesheet" href="{{ asset('site-asset/css/cart-popup.css') }}">
    

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @stack('styles')
</head>
<body @if(request()->is('/')) class="homepage" @endif>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T9KVRKBX"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager(noscript)-->
    @if(request()->is('/'))
        <header class="luxury-header">
            <div class="header-container">
                <div class="logo-section">
                    <div class="logo-icon">
                        @if($siteSettings['site_icon_url'])
                            <img src="{{ $siteSettings['site_icon_url'] }}" alt="{{ $siteSettings['site_name'] }}" style="width: 50px; height: 50px; object-fit: contain;">
                        @else
                             <i class="fas fa-spa"></i>
                        @endif
                    </div>
                    <a href="{{ url('/') }}" style="text-decoration: none; color: inherit;">
                        <h1 class="brand-name">{{ strtoupper($siteSettings['site_name']) }}</h1>
                        <p class="brand-tagline">{{ strtoupper($siteSettings['site_description']) }}</p>
                    </a>
                </div>
                
                <nav class="main-navigation">
                    <ul class="nav-menu">

                        @foreach($headerCategories as $category)
                            @if($category->children->count() > 0)
                                {{-- CATEGORIES - IF HAVE CHILD --}}
                                <li class="nav-item dropdown">
                                    <a href="{{ route('categories.show', $category->slug) }}" class="nav-link">{{ $category->name }}</a>
                                    <div class="mega-menu">
                                        <div class="mega-menu-content">
                                            <div class="menu-column links-column">
                                                <h3>{{ $category->name }}</h3>
                                                <ul class="menu-links">
                                                    @foreach($category->children as $childCategory)
                                                        <li><a href="{{ route('categories.show', $childCategory->slug) }}">{{ $childCategory->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="menu-column gallery-column">
                                                <div class="collection-gallery">
                                                    @foreach($category->children->take(5) as $childCategory)
                                                        <div class="collection-item">
                                                            <a href="{{ route('categories.show', $childCategory->slug) }}" style="text-decoration: none; color: inherit;">
                                                                <div class="collection-image" style="background-image: url('{{ $childCategory->mobile_cover_image ? asset($childCategory->mobile_cover_image) : ($childCategory->cover_image ? asset($childCategory->cover_image) : 'https://images.unsplash.com/photo-1522529599102-193c0d76b5b6?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80') }}');"></div>
                                                                <div class="collection-caption">
                                                                    <h4 class="collection-title">{{ $childCategory->name }}</h4>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @else
                                {{-- CATEGORIES - IF HAVE NO CHILD --}}
                                <li class="nav-item">
                                    <a href="{{ route('categories.show', $category->slug) }}" class="nav-link">{{ $category->name }}</a>
                                </li>
                            @endif
                        @endforeach

                        <li class="nav-item">
                            <a href="{{ route('my-account') }}" class="nav-link">
                                @auth
                                    My Account
                                @else
                                    Login
                                @endauth
                            </a>
                        </li>
                        <li class="nav-item search-item">
                            <a href="#" class="nav-link search-link">
                                <i class="fas fa-search"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>
    @endif

    <header class="mobile-header">
        <div class="mobile-header-container">
            <div class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
            
            <div class="mobile-logo">
                <a href="{{ url('/') }}">
                    <div class="mobile-logo-icon">
                        @if($siteSettings['site_icon_url'])
                            <img src="{{ $siteSettings['site_icon_url'] }}" alt="{{ $siteSettings['site_name'] }}" style="width: 40px; height: 40px; object-fit: contain;">
                        @else
                            <i class="fas fa-spa"></i>
                        @endif
                    </div>
                    <div class="mobile-brand-text">{{ strtoupper($siteSettings['site_name']) }}</div>
                </a>
            </div>
            
            <div class="mobile-cart">
                <a href="{{ route('checkout') }}" class="mobile-cart-icon">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-badge cart-count">0</span>
                </a>
            </div>
        </div>
    </header>

    {{-- Mobile Sidebar Menu --}}
    <div class="mobile-sidebar-overlay">
        <div class="mobile-sidebar">
            <div class="mobile-sidebar-header">
                <div class="mobile-sidebar-logo">
                    @if($siteSettings['site_icon_url'])
                        <img src="{{ $siteSettings['site_icon_url'] }}" alt="{{ $siteSettings['site_name'] }}" style="width: 40px; height: 40px; object-fit: contain;">
                    @else
                        <i class="fas fa-spa"></i>
                    @endif
                    <span class="mobile-sidebar-brand">{{ strtoupper($siteSettings['site_name']) }}</span>
                </div>
                <button class="mobile-sidebar-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mobile-sidebar-content">
                <nav class="mobile-navigation">
                    <ul class="mobile-nav-menu">
                        @foreach($headerCategories as $category)
                            @if($category->children->count() > 0)
                                {{-- CATEGORIES - IF HAVE CHILD --}}
                                <li class="mobile-nav-item mobile-dropdown">
                                    <a href="{{ route('categories.show', $category->slug) }}" class="mobile-nav-link">
                                        {{ $category->name }}
                                        <i class="fas fa-chevron-right mobile-nav-arrow"></i>
                                    </a>
                                    <ul class="mobile-submenu">
                                        @foreach($category->children as $childCategory)
                                            <li class="mobile-submenu-item">
                                                <a href="{{ route('categories.show', $childCategory->slug) }}" class="mobile-submenu-link">
                                                    {{ $childCategory->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                {{-- CATEGORIES - IF HAVE NO CHILD --}}
                                <li class="mobile-nav-item">
                                    <a href="{{ route('categories.show', $category->slug) }}" class="mobile-nav-link">{{ $category->name }}</a>
                                </li>
                            @endif
                        @endforeach

                        <li class="mobile-nav-item">
                            <a href="{{ route('account.dashboard') }}" class="mobile-nav-link">
                                <span>
                                    <i class="fas fa-user"></i>
                                    @auth
                                        My Account
                                    @else
                                        Login
                                    @endauth
                                </span>
                            </a>
                        </li>
                        <li class="mobile-nav-item">
                            <a href="#" class="mobile-nav-link mobile-search-link">
                                <span>
                                    <i class="fas fa-search"></i>
                                    Search
                                </span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <div class="search-overlay">
        <div class="search-container">
            <form action="{{ route('products.index') }}" method="GET" class="search-form">
                <input type="text" name="search" class="search-input" placeholder="Search for luxury fashion...">
                <button type="submit" class="search-submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <button class="search-close">&times;</button>
        </div>
    </div>

    
    {{-- Main Content Section --}}
    @yield('content')


    <footer class="luxury-footer">
        <div class="footer-container">
            <div class="footer-content">

                <div class="footer-section">
                     @if($siteSettings['site_icon_url'])
                          <img src="{{ $siteSettings['site_icon_url'] }}" alt="{{ $siteSettings['site_name'] }}" style="width: 50px; height: 50px; object-fit: contain;">
                        @else
                             <i class="fas fa-spa"></i>
                        @endif
                    <h3>{{ $siteSettings['site_name'] }}</h3>

                    {{-- Footer description --}}
                    <p>{{ $siteSettings['footer_description'] }}</p>

                    {{-- social-links --}}
                    <div class="social-links">
                        @if($siteSettings['footer_facebook'])
                            <a href="{{ $siteSettings['footer_facebook'] }}" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if($siteSettings['footer_instagram'])
                            <a href="{{ $siteSettings['footer_instagram'] }}" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if($siteSettings['footer_twitter'])
                            <a href="{{ $siteSettings['footer_twitter'] }}" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if($siteSettings['footer_pinterest'])
                            <a href="{{ $siteSettings['footer_pinterest'] }}" target="_blank" aria-label="Pinterest"><i class="fab fa-pinterest"></i></a>
                        @endif
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ route('store-locator') }}">Store Locator</a></li>
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        <li><a href="{{ route('shipping-returns') }}">Shipping & Returns</a></li>
                        <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('terms-of-service') }}">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Top Categories</h3>
                    <ul class="footer-links">
                        @php
                            $footerCategories = \App\Models\Category::where('id', '>=', 0)->take(5)->get();
                        @endphp
                        @forelse($footerCategories as $category)
                            <li><a href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a></li>
                        @empty
                            <li><a href="{{ route('products.index') }}">All Products</a></li>
                        @endforelse
                    </ul>
                </div>
                
                {{-- Contact Info --}}
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <ul class="footer-links contact-info">
                        @if($siteSettings['footer_address'])
                            <li><i class="fas fa-map-marker-alt"></i> {{ $siteSettings['footer_address'] }}</li>
                        @endif
                        @if($siteSettings['footer_phone'])
                            <li><i class="fas fa-phone"></i> {{ $siteSettings['footer_phone'] }}</li>
                        @endif
                        @if($siteSettings['footer_email'])
                            <li><i class="fas fa-envelope"></i> {{ $siteSettings['footer_email'] }}</li>
                        @endif
                        @if($siteSettings['footer_hours'])
                            <li><i class="fas fa-clock"></i> {{ $siteSettings['footer_hours'] }}</li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Copyright Text --}}
            <div class="footer-bottom">
                <p>{{ $siteSettings['footer_copyright'] }}</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('site-asset/js/min.js') }}"></script>
    <script src="{{ asset('site-asset/js/cart.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
      var bestSellersSwiper = new Swiper('.best-sellers-swiper', {
        slidesPerView: 6,
        spaceBetween: 10,
        grid: {
          rows: 1,
          fill: 'row',
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        breakpoints: {
          1200: { slidesPerView: 6 },
          992: { slidesPerView: 4 },
          768: { slidesPerView: 2 },
          0:   { slidesPerView: 1 }
        }
      });

      // Mobile Menu - jQuery
      $(document).ready(function() {
        // Open mobile menu
        $('.mobile-menu-toggle').click(function() {
          $('.mobile-sidebar-overlay').addClass('active');
          $('body').css('overflow', 'hidden');
        });

        // Close mobile menu function
        function closeMobileMenu() {
          $('.mobile-sidebar-overlay').removeClass('active');
          $('body').css('overflow', '');
        }

        // Close button click
        $('.mobile-sidebar-close').click(function() {
          closeMobileMenu();
        });

        // Overlay click (outside sidebar)
        $('.mobile-sidebar-overlay').click(function(e) {
          if (e.target === this) {
            closeMobileMenu();
          }
        });

        // Dropdown toggles
        $('.mobile-dropdown .mobile-nav-link').click(function(e) {
          e.preventDefault();
          $(this).parent().toggleClass('active').siblings().removeClass('active');
        });

        // Search link
        $('.mobile-search-link').click(function(e) {
          e.preventDefault();
          closeMobileMenu();
          $('.search-overlay').addClass('active');
        });

        // Escape key
        $(document).keydown(function(e) {
          if (e.key === 'Escape') closeMobileMenu();
        });
      });
    </script>
    @stack('scripts')
</body>
</html>