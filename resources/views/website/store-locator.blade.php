@extends('website.layouts.app')

@section('title', 'Our Stores - Brightness Fashion')

{{-- The styles are now placed directly in the file to ensure they always load correctly. --}}
<style>
    .store-locator-page .main-content {
        padding-top: 0;
        background-color: #fff;
    }
    .store-hero {
        position: relative;
        height: 50vh;
        min-height: 350px;
        /* This is the fallback background that will show if you don't upload an image. */
        background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 100%);
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
    }
    .store-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
    }
    .store-hero-content {
        position: relative;
        z-index: 1;
        padding: 2rem;
    }
    .store-hero-content h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 0;
    }
    .store-locations-container {
        max-width: 1200px;
        margin: -60px auto 0;
        position: relative;
        z-index: 2;
        padding: 0 2rem 4rem;
    }
    .coming-soon-section {
        background: #fff;
        padding: 5rem 2rem;
        text-align: center;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .coming-soon-section h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: var(--primary-color);
    }
    .division-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.5rem;
    }
    .division-section {
        background: #fff;
        padding: 2.5rem;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .division-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }
    .stores-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }
    .store-info-card {
        padding: 1.5rem;
        border: 1px solid #f0f0f0;
        border-radius: 5px;
        transition: all 0.3s ease;
    }
    .store-info-card:hover {
        border-color: var(--golden-color);
        transform: translateY(-3px);
    }
    .store-info-card h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    .store-info-card p {
        font-family: 'Inter', sans-serif;
        color: #555;
        font-size: 0.95rem;
        line-height: 1.7;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: flex-start;
    }
    .store-info-card i {
        color: var(--golden-color);
        margin-right: 12px;
        margin-top: 4px;
        width: 18px;
        text-align: center;
    }
    @media (max-width: 768px) {
        .store-hero-content h1 {
            font-size: 2.5rem;
        }
        .store-locations-container {
            padding: 0 1rem 2rem;
            margin-top: -40px;
        }
        .division-title {
            font-size: 1.8rem;
        }
        .division-section {
            padding: 1.5rem;
        }
        .stores-grid {
             grid-template-columns: 1fr;
        }
    }
</style>

@section('content')
<div class="store-locator-page">
    <main class="main-content">

        {{-- This PHP block checks if an image exists and sets the style accordingly --}}
        @php
            $heroStyle = $heroImage ? "background-image: url(" . asset('uploads/website/' . $heroImage) . ");" : "";
        @endphp

        <section class="store-hero" style="{{ $heroStyle }}">
            <div class="store-hero-content">
                <h1>Our Stores</h1>
            </div>
        </section>

        <div class="store-locations-container">
            @if($stores && $stores->count() > 0)
                <div class="division-grid">
                    @foreach($stores as $division => $storeList)
                        <div class="division-section">
                            <h2 class="division-title">{{ $division }}</h2>
                            <div class="stores-grid">
                                @foreach($storeList as $store)
                                    @if(is_object($store))
                                        <div class="store-info-card">
                                            <h3>{{ $store->name }}</h3>
                                            <p><i class="fas fa-map-marker-alt"></i>{{ $store->address }}</p>
                                            <p><i class="fas fa-phone-alt"></i>{{ $store->phone }}</p>
                                            @if($store->email)
                                                <p><i class="fas fa-envelope"></i>{{ $store->email }}</p>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="coming-soon-section">
                    <h2>Our Stores Are Coming Soon</h2>
                    <p>We are working on expanding our reach. Please check back later for store locations near you.</p>
                </div>
            @endif
        </div>

    </main>
</div>
@endsection