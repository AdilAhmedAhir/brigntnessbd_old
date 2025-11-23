@extends('website.layouts.app')


@push('styles')
    <link rel="stylesheet" href="{{ asset('site-asset/css/home-page.css') }}">
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
    .banner-image, .category-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    </style>
@endpush

@section('content')

<!-- Hero Section -->
@if($heroVideoUrl)
<section class="hero-section">
    <div class="hero-background">
        <iframe class="hero-video" 
            src="{{ $heroVideoUrl }}" 
            frameborder="0" 
            allow="autoplay; encrypted-media" 
            allowfullscreen>
        </iframe>
        <div class="hero-overlay"></div>
    </div>
</section>
@endif

<main class="main-content">

    <!-- Shop by Category Section -->
    @if($shopCategories->isNotEmpty())
    <section class="categories-showcase full-width">
        <div class="content-wrapper">
            <div class="section-header">
                <h2 class="section-title">{{ $shopCategoryTitle }}</h2>
                <p class="section-description">{{ $shopCategoryDescription }}</p>
            </div>
            
            <div class="categories-grid">
                @foreach($shopCategories as $category)
                <div class="category-card">
                    <div class="category-image" style="background-image: url('{{ asset($category->cover_image) }}');"></div>
                    <div class="category-overlay"></div>
                    <div class="category-content">
                        <h3 class="category-title">{{ $category->name }}</h3>
                        <p class="category-description">{{ $category->description }}</p>
                        <a href="{{ route('categories.show', $category->slug) }}" class="category-link">Shop Now</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    {{-- End of - Shop by Category Section --}}

    {{-- Category Showcase Section using - foreach --}}
    @foreach ($categoryShowcase as $category)

        <!-- Category Banner-->
        <section class="featured-banners full-width">
            <div class="banner-section">
                <div class="banner-image desktop-banner" style="background-image: url('{{ asset($category->cover_image)}}');"></div>
                @if($category->mobile_cover_image)
                    <div class="banner-image mobile-banner" style="background-image: url('{{ asset($category->mobile_cover_image)}}');"></div>
                @endif
                <div class="banner-overlay"></div>
                <div class="banner-content">
                    <h2 class="banner-title">{{ $category->name }}</h2>
                    <p class="banner-subtitle">{{ $category->description }}</p>
                    <a href="{{ route('categories.show', $category->slug) }}" class="explore-button">EXPLORE</a>
                </div>
            </div>
        </section>

        <!-- Products in the category -->
        @if($category->showcase_products->isNotEmpty())
        <section class="best-sellers-section full-width">
            <div class="content-wrapper">
                <div class="swiper best-sellers-swiper">
                    <div class="swiper-wrapper">
                        @foreach($category->showcase_products as $product)
                        <div class="swiper-slide">
                            <a href="{{ (!empty($product->slug)) ? route('products.show', $product->slug) : route('products.show', $product->id) }}" class="product-link">
                                <div class="product-card">
                                    @if($product->featured)
                                        <div class="product-badge">Featured</div>
                                    @elseif($product->hasDiscount())
                                        <div class="product-badge">Sale</div>
                                    @endif
                                    <div class="product-image-container">
                                        <img src="{{ asset($product->image)}}"
                                             alt="{{ $product->name }}"
                                             class="product-image">
                                    </div>
                                    <div class="product-info">
                                        <h3 class="product-name">{{ $product->name }}</h3>
                                        <p class="product-price">
                                            @if($product->hasDiscount())
                                                <span class="original-price" style="text-decoration: line-through; color: #888;">৳{{ number_format($product->price) }}</span>
                                                <span class="sale-price">৳{{ number_format($product->sale_price) }}</span>
                                            @else
                                                ৳{{ number_format($product->display_price) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        </section>
        @endif

    @endforeach
    {{-- End of Category Showcase Section --}}

</main>
@endsection