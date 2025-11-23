// HOA Luxury Fashion Website JavaScript - Minimal

document.addEventListener('DOMContentLoaded', function() {
    // ===== SEARCH FUNCTIONALITY =====
    const searchButton = document.querySelector('.search-link');
    const searchOverlay = document.querySelector('.search-overlay');
    const closeSearchBtn = document.querySelector('.search-close');
    if (searchButton && searchOverlay) {
        searchButton.addEventListener('click', function(e) {
            e.preventDefault();
            searchOverlay.classList.add('active');
            document.body.classList.add('search-active');
            setTimeout(() => {
                const searchInput = document.querySelector('.search-input');
                if (searchInput) searchInput.focus();
            }, 300);
        });
    }
    if (closeSearchBtn && searchOverlay) {
        closeSearchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            searchOverlay.classList.remove('active');
            document.body.classList.remove('search-active');
        });
    }
    // Close search on escape or background click
    if (searchOverlay) {
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                searchOverlay.classList.remove('active');
                document.body.classList.remove('search-active');
            }
        });
        searchOverlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.classList.remove('search-active');
            }
        });
    }
    // ===== HEADER FUNCTIONALITY =====
    const header = document.querySelector('.luxury-header');
    // Mega menu positioning
    const dropdownItems = document.querySelectorAll('.dropdown');
    dropdownItems.forEach(item => {
        const megaMenu = item.querySelector('.mega-menu');
        if (megaMenu) {
            item.addEventListener('mouseenter', function() {
                const headerHeight = header.offsetHeight;
                megaMenu.style.top = headerHeight + 'px';
            });
        }
    });
    // ===== SCROLL ANIMATIONS =====
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
            }
        });
    }, observerOptions);
    const animateElements = document.querySelectorAll('.animate-on-scroll');
    animateElements.forEach(element => {
        observer.observe(element);
    });
    
    // ===== HERO VIDEO HANDLING =====
    const heroVideo = document.querySelector('.hero-video');
    const heroFallback = document.querySelector('.hero-fallback-bg');
    
    if (heroVideo) {
        // Show fallback immediately
        if (heroFallback) {
            heroFallback.style.opacity = '1';
        }
        
        // Try to load video after a delay
        setTimeout(() => {
            heroVideo.style.opacity = '0.8';
            heroVideo.classList.add('loaded');
        }, 2000);
        
        // Add load handler
        heroVideo.onload = function() {
            console.log('Hero video loaded successfully');
            if (heroFallback) {
                heroFallback.style.opacity = '0.3'; // Dim fallback when video loads
            }
            heroVideo.style.opacity = '1';
        };
        
        // Error handler - keep fallback visible
        heroVideo.onerror = function() {
            console.log('Hero video failed to load, using fallback background');
            heroVideo.style.display = 'none';
            if (heroFallback) {
                heroFallback.style.opacity = '1';
            }
        };
    }
    // No custom product grid slider JS needed (handled by Swiper.js)
    console.log('HOA Luxury Fashion Website Loaded Successfully');
});
