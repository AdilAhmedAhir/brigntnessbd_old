@extends('website.layouts.app')

@section('title', isset($category) ? $category->name . ' - Products' : 'Products - Brightness Fashion')

@section('content')
<div class="products-archive-page">
    <!-- Category Banner Section (only show if category exists) -->
    @if(isset($category))
    <div class="category-banner">
        @if($category->cover_image)
            <div class="banner-image desktop-banner" style="background-image: url('{{ asset($category->cover_image) }}');"></div>
        @endif
        @if($category->mobile_cover_image)
            <div class="banner-image mobile-banner" style="background-image: url('{{ asset($category->mobile_cover_image) }}');"></div>
        @endif
        <div class="category-banner-overlay"></div>
        <div class="container">
            <div class="category-banner-content" data-aos="fade-up">
                <div class="category-banner-text">
                    <h1 class="category-title">{{ $category->name }}</h1>
                    @if($category->description)
                        <p class="category-description">{{ $category->description }}</p>
                    @endif
                    <div class="category-meta">
                        <span class="product-count">{{ $products->total() }} Products Available</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Page Header for All Products -->
    <div class="page-header">
        <div class="container">
            <h1>Our Products</h1>
            <p>Discover our exclusive collection of luxury fashion</p>
        </div>
    </div>
    @endif



    <!-- Products Section #################################################### -->
    <div class="products-section">
        <div class="container">

            <!-- Products Grid -->
            <div class="products-grid grid-4" id="productsGrid">
                @foreach($products as $product)
                <div class="product-card" data-aos="fade-up">
                    <div class="product-image">
                        <a href="{{ !empty($product->slug) ? route('products.show', $product->slug) : route('products.show', $product->id) }}">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy">
                            @else
                                <img src="{{ asset('site-asset/img/no-image.jpg') }}" alt="{{ $product->name }}" loading="lazy">
                            @endif
                        </a>
                        
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <span class="discount-badge">
                                {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                            </span>
                        @endif
                    </div>
                    
                    <div class="product-details">
                        <h3 class="product-name">
                            <a href="{{ !empty($product->slug) ? route('products.show', $product->slug) : route('products.show', $product->id) }}">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        @if($product->description)
                            <p class="product-excerpt">{{ Str::limit($product->description, 80) }}</p>
                        @endif
                        
                        <div class="product-price">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="sale-price">৳{{ number_format($product->sale_price, 2) }}</span>
                                <span class="regular-price">৳{{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="current-price">৳{{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Loading Spinner -->
            <div class="loading-spinner" id="loadingSpinner" style="display: none;">
                <div class="spinner">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
                <p>Loading more products...</p>
            </div>

            <!-- No More Products Message -->
            <div class="no-more-products" id="noMoreProducts" style="display: none;">
                <p>You've reached the end of our collection!</p>
            </div>
        </div>
    </div>
</div>
@endsection


@push('styles')
    <link rel="stylesheet" href="{{ asset('site-asset/css/products_archive.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <style>
    /* Mobile Banner Responsive Styles */
    .desktop-banner {
        display: block;
    }

    .mobile-banner {
        display: none;
    }

    @media (max-width: 768px) {
        .desktop-banner {
            display: none;
        }
        
        .mobile-banner {
            display: block;
        }
    }

    /* Ensure banner images maintain proper positioning */
    .banner-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    /* Category banner should be positioned relative to contain absolute images */
    .category-banner {
        position: relative;
    }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    // Initialize AOS
    AOS.init({
        duration: 600,
        easing: 'ease-in-out',
        once: true
    });

    // Infinite Scroll Configuration
    let currentPage = 1;
    let totalPages = {{ $products->lastPage() }};
    let isLoading = false;
    let currentView = 'grid-4';


    // Infinite Scroll Implementation
    function loadMoreProducts() {
        if (isLoading || currentPage >= totalPages) return;
        
        isLoading = true;
        document.getElementById('loadingSpinner').style.display = 'block';
        
        // Determine the current URL for AJAX requests
        const currentUrl = '{{ isset($category) ? route("categories.show", $category->slug) : route("products.index") }}';
        
        fetch(`${currentUrl}?page=${currentPage + 1}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.products.length > 0) {
                const grid = document.getElementById('productsGrid');
                
                data.products.forEach(product => {
                    const productCard = createProductCard(product);
                    grid.appendChild(productCard);
                });
                
                currentPage++;
                updateProductCount(data.currentCount, data.totalCount);
                
                // Re-initialize AOS for new elements
                AOS.refresh();
            } else {
                document.getElementById('noMoreProducts').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error loading products:', error);
        })
        .finally(() => {
            isLoading = false;
            document.getElementById('loadingSpinner').style.display = 'none';
        });
    }

    // Create Product Card HTML
    function createProductCard(product) {
        const card = document.createElement('div');
        card.className = 'product-card';
        card.setAttribute('data-aos', 'fade-up');
        
        const discountBadge = product.sale_price && product.sale_price < product.price 
            ? `<span class="discount-badge">${Math.round(((product.price - product.sale_price) / product.price) * 100)}% OFF</span>`
            : '';
        
        const productExcerpt = product.description 
            ? `<p class="product-excerpt">${product.description.substring(0, 80)}${product.description.length > 80 ? '...' : ''}</p>`
            : '';
        
        const priceHTML = product.sale_price && product.sale_price < product.price
            ? `<span class="sale-price">৳${parseFloat(product.sale_price).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
            <span class="regular-price">৳${parseFloat(product.price).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>`
            : `<span class="current-price">৳${parseFloat(product.price).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>`;
        
        card.innerHTML = `
            <div class="product-image">
                <a href="/products/${product.slug || product.id}">
                    <img src="${product.image || '/site-asset/img/no-image.jpg'}" alt="${product.name}" loading="lazy">
                </a>
                ${discountBadge}
            </div>
            <div class="product-details">
                <h3 class="product-name">
                    <a href="/products/${product.slug || product.id}">${product.name}</a>
                </h3>
                ${productExcerpt}
                <div class="product-price">${priceHTML}</div>
            </div>
        `;
        
        return card;
    }

    // Update Product Count
    function updateProductCount(current, total) {
        document.getElementById('currentCount').textContent = current;
        document.getElementById('totalCount').textContent = total;
    }

    // Scroll Event Listener
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000) {
                loadMoreProducts();
            }
        }, 100);
    });

    // Search functionality (if needed)
    function handleSearch(query) {
        // Implement search functionality
        window.location.href = `{{ route('products.index') }}?search=${query}`;
    }
    </script>
@endpush

