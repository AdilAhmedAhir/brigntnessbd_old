@extends('website.layouts.app')

@section('title', $product->name . ' - Brightness Fashion')

@section('content')
<div class="product-page">
    <!-- Product Main Section -->
    <div class="product-main-section">
        <div class="container">
            <div class="row">
                <!-- Left Side - Product Images -->
                <div class="col-lg-6">
                    <div class="product-images">
                        <div class="images-layout">
                            <!-- Thumbnail Gallery -->
                            <div class="thumbnail-gallery">
                                <div class="thumbnail-wrapper">
                                    <!-- Main Image Thumbnail -->
                                    @if($product->image)
                                        <div class="thumbnail-item active" data-image="{{ asset($product->image) }}">
                                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                                        </div>
                                    @endif

                                    <!-- Gallery Images Thumbnails -->
                                    @if($product->gallery && is_array($product->gallery))
                                        @foreach($product->gallery as $galleryImage)
                                            <div class="thumbnail-item" data-image="{{ asset($galleryImage) }}">
                                                <img src="{{ asset($galleryImage) }}" alt="{{ $product->name }}">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <!-- Main Image Viewer -->
                            <div class="main-image-viewer">
                                <div class="main-image-container">
                                    @if($product->image)
                                        <img id="mainProductImage" src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="main-product-image">
                                    @else
                                        <img id="mainProductImage" src="{{ asset('site-asset/img/no-image.jpg') }}" alt="{{ $product->name }}" class="main-product-image">
                                    @endif
                                    
                                    <!-- Image Zoom Overlay -->
                                    <div class="image-zoom-overlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Product Information -->
                <div class="col-lg-6">
                    <div class="product-info">
                        <!-- Product Title -->
                        <h1 class="product-title">{{ $product->name }}</h1>

                        <!-- Product SKU -->
                        @if($product->sku)
                            <div class="product-sku">
                                <span class="sku-label">SKU:</span>
                                <span class="sku-value">{{ $product->sku }}</span>
                            </div>
                        @endif


                       

                        <!-- Product Price -->
                        <div class="product-price">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="sale-price">৳{{ number_format($product->sale_price, 2) }}</span>
                                <span class="regular-price">৳{{ number_format($product->price, 2) }}</span>
                                <span class="discount-badge">
                                    {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                                </span>
                            @else
                                <span class="current-price">৳{{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>



        <!-- Product Description -->
        @if($product->description)
            <div class="product-description">
                {!! nl2br(e($product->description)) !!}
            </div>
        @endif                        

                        <!-- Size Options -->
                        @if($product->product_size && is_array($product->product_size) && count($product->product_size) > 0)
                            <div class="size-selection">
                                <h3>Select Size</h3>
                                <div class="size-options">
                                    @foreach($product->product_size as $sizeData)
                                        @php
                                            $sizeName = is_array($sizeData) ? $sizeData['size_name'] : $sizeData;
                                            $quantity = is_array($sizeData) ? ($sizeData['quantity'] ?? 0) : 0;
                                        @endphp
                                        <label class="size-option">
                                            <input type="radio" name="product_size" value="{{ $sizeName }}" 
                                                   data-quantity="{{ $quantity }}" required>
                                            <span class="size-label">
                                                {{ $sizeName }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Quantity Selection -->
                        <div class="quantity-selection">
                            <h3>Quantity</h3>
                            <div class="quantity-controls">
                                <button type="button" class="quantity-btn decrease" onclick="decreaseQuantity()">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="productQuantity" value="1" min="1" max="1" readonly>
                                <button type="button" class="quantity-btn increase" onclick="increaseQuantity()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <small class="stock-info" id="stockInfo">
                                @if($product->product_size && is_array($product->product_size))
                                    Please select a size to see availability
                                @else
                                    {{ $product->stock_quantity ?? 0 }} pieces available
                                @endif
                            </small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="product-actions">
                            <button type="button" class="btn btn-primary add-to-cart" id="addToCartBtn" onclick="addToCart({{ $product->id }})" disabled>
                                <i class="fas fa-shopping-cart"></i>
                                Add to Cart
                            </button>
                            <button type="button" class="btn btn-secondary buy-now" id="buyNowBtn" onclick="buyNow({{ $product->id }})" disabled>
                                <i class="fas fa-bolt"></i>
                                Buy Now
                            </button>
                        </div>

                        <!-- Enquiry Button -->
                        {{-- <div class="enquiry-section">
                            <h3>Need Help?</h3>
                            <div class="enquiry-buttons">
                                <a href="https://wa.me/8801234567890?text=Hi, I'm interested in {{ $product->name }}" 
                                   target="_blank" class="enquiry-btn whatsapp">
                                    <i class="fab fa-whatsapp"></i>
                                    WhatsApp
                                </a>
                                <a href="tel:+8801234567890" class="enquiry-btn phone">
                                    <i class="fas fa-phone"></i>
                                    Call Us
                                </a>
                            </div>
                        </div> --}}

                        <!-- Size Chart -->
                        @if($product->product_meta && is_array($product->product_meta) && isset($product->product_meta['size_image']))
                            <div class="size-chart">
                                <div class="size-chart-image">
                                    <img src="{{ asset($product->product_meta['size_image']) }}" alt="Size Chart" class="size-chart-img">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products Section -->
    <div class="related-products-section">
        <div class="container">
            <div class="section-header">
                <h2>You May Also Like</h2>
                <p>Discover more products from our collection</p>
            </div>

            <div class="related-products-carousel">
                <div class="carousel-wrapper">
                    <div class="carousel-container" id="relatedProductsCarousel">
                        @foreach($relatedProducts ?? [] as $relatedProduct)
                            <div class="product-card">
                                <div class="product-image">
                                    <a href="{{ !empty($relatedProduct->slug) ? route('products.show', $relatedProduct->slug) : route('products.show', $relatedProduct->id) }}">
                                        @if($relatedProduct->image)
                                            <img src="{{ asset($relatedProduct->image) }}" alt="{{ $relatedProduct->name }}">
                                        @else
                                            <img src="{{ asset('site-asset/img/no-image.jpg') }}" alt="{{ $relatedProduct->name }}">
                                        @endif
                                    </a>
                                    
                                    @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                        <span class="discount-badge">
                                            {{ round((($relatedProduct->price - $relatedProduct->sale_price) / $relatedProduct->price) * 100) }}% OFF
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="product-details">
                                    <h3 class="product-name">
                                        <a href="{{ !empty($relatedProduct->slug) ? route('products.show', $relatedProduct->slug) : route('products.show', $relatedProduct->id) }}">
                                            {{ $relatedProduct->name }}
                                        </a>
                                    </h3>
                                    
                                    <div class="product-price">
                                        @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                            <span class="sale-price">৳{{ number_format($relatedProduct->sale_price, 2) }}</span>
                                            <span class="regular-price">৳{{ number_format($relatedProduct->price, 2) }}</span>
                                        @else
                                            <span class="current-price">৳{{ number_format($relatedProduct->price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Carousel Navigation -->
                <button class="carousel-nav prev" onclick="slideCarousel('prev')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-nav next" onclick="slideCarousel('next')">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeImageModal()">&times;</span>
            <img id="modalImage" src="" alt="">
        </div>
    </div>

    <!-- Size Chart Modal -->
    <div id="sizeChartModal" class="size-chart-modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeSizeChartModal()">&times;</span>
            <img id="sizeChartModalImage" src="{{ $product->product_meta['size_image'] ?? '' }}" alt="Size Chart">
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('site-asset/css/product_page.css') }}">

<style>
    .related-products-carousel{
        overflow: visible !important;
    }
    
    /* Disabled button styles */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    /* Stock info styles */
    .stock-info .text-success {
        color: #28a745 !important;
    }
    
    .stock-info .text-danger {
        color: #dc3545 !important;
    }
</style>

<script>
// Image Gallery Functionality
document.addEventListener('DOMContentLoaded', function() {
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    const mainImage = document.getElementById('mainProductImage');
    
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            
            // Add active class to clicked thumbnail
            this.classList.add('active');
            
            // Change main image
            const newImageSrc = this.getAttribute('data-image');
            mainImage.src = newImageSrc;
        });
    });
    
    // Image zoom functionality
    const imageContainer = document.querySelector('.main-image-container');
    imageContainer.addEventListener('click', function() {
        openImageModal(mainImage.src);
    });

    // Size selection functionality
    const sizeInputs = document.querySelectorAll('input[name="product_size"]');
    sizeInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateStockInfo();
        });
    });

    // Initial check for products without sizes
    if (sizeInputs.length === 0) {
        enableCartButtons();
    }
});

// Update stock information based on selected size
function updateStockInfo() {
    const selectedSize = document.querySelector('input[name="product_size"]:checked');
    const quantityInput = document.getElementById('productQuantity');
    const stockInfo = document.getElementById('stockInfo');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const buyNowBtn = document.getElementById('buyNowBtn');
    
    if (!selectedSize) {
        // No size selected
        disableCartButtons();
        stockInfo.textContent = 'Please select a size to see availability';
        return;
    }
    
    const quantityAttr = selectedSize.getAttribute('data-quantity');
    const quantity = parseInt(quantityAttr, 10);
    
    if (!quantityAttr || isNaN(quantity) || quantity <= 0) {
        // Out of stock
        disableCartButtons();
        stockInfo.innerHTML = '<span class="text-danger">Out of stock</span>';
        quantityInput.value = 1;
        quantityInput.max = 0;
    } else {
        // In stock
        enableCartButtons();
        stockInfo.innerHTML = `<span class="text-success">${quantity} pieces available</span>`;
        quantityInput.max = quantity;
        quantityInput.value = 1; // Reset to 1 when size changes
        
        // Adjust current quantity if it exceeds available stock
        if (parseInt(quantityInput.value) > quantity) {
            quantityInput.value = quantity;
        }
    }
}

// Enable cart buttons
function enableCartButtons() {
    const addToCartBtn = document.getElementById('addToCartBtn');
    const buyNowBtn = document.getElementById('buyNowBtn');
    
    if (addToCartBtn) {
        addToCartBtn.disabled = false;
        addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
    }
    
    if (buyNowBtn) {
        buyNowBtn.disabled = false;
        buyNowBtn.innerHTML = '<i class="fas fa-bolt"></i> Buy Now';
    }
}

// Disable cart buttons
function disableCartButtons() {
    const addToCartBtn = document.getElementById('addToCartBtn');
    const buyNowBtn = document.getElementById('buyNowBtn');
    
    if (addToCartBtn) {
        addToCartBtn.disabled = true;
        addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
    }
    
    if (buyNowBtn) {
        buyNowBtn.disabled = true;
        buyNowBtn.innerHTML = '<i class="fas fa-bolt"></i> Buy Now';
    }
}

// Quantity Controls
function increaseQuantity() {
    const quantityInput = document.getElementById('productQuantity');
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.getAttribute('max'));
    
    if (currentValue < maxValue && maxValue > 0) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('productQuantity');
    const currentValue = parseInt(quantityInput.value);
    
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

// Image Modal
function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    modalImage.src = imageSrc;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Size Chart Modal
function openSizeChart() {
    const modal = document.getElementById('sizeChartModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeSizeChartModal() {
    const modal = document.getElementById('sizeChartModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Related Products Carousel
let currentSlide = 0;

function getSlidesToShow() {
    const width = window.innerWidth;
    if (width <= 480) return 1;
    if (width <= 768) return 2;
    if (width <= 992) return 3;
    if (width <= 1200) return 5;
    return 6;
}

function slideCarousel(direction) {
    const carousel = document.getElementById('relatedProductsCarousel');
    const totalProducts = carousel.children.length;
    const slidesToShow = getSlidesToShow();
    const maxSlides = Math.max(0, totalProducts - slidesToShow);
    
    if (direction === 'next') {
        currentSlide = Math.min(currentSlide + 1, maxSlides);
    } else {
        currentSlide = Math.max(currentSlide - 1, 0);
    }
    
    const translateX = -(currentSlide * (100 / slidesToShow));
    carousel.style.transform = `translateX(${translateX}%)`;
}

// Reset carousel position on window resize
window.addEventListener('resize', function() {
    currentSlide = 0;
    slideCarousel('next'); // This will recalculate and set to position 0
    currentSlide = 0; // Reset to ensure we're at the start
    const carousel = document.getElementById('relatedProductsCarousel');
    carousel.style.transform = 'translateX(0%)';
});

// Add to Cart Functionality
function addToCart(productId) {
    const selectedSize = document.querySelector('input[name="product_size"]:checked');
    const quantity = document.getElementById('productQuantity').value;
    const addToCartBtn = document.getElementById('addToCartBtn');
    
    // Check if button is disabled
    if (addToCartBtn && addToCartBtn.disabled) {
        alert('This item is currently out of stock.');
        return;
    }
    
    // Check if size is required and selected
    const sizeOptions = document.querySelectorAll('input[name="product_size"]');
    if (sizeOptions.length > 0 && !selectedSize) {
        alert('Please select a size before adding to cart.');
        return;
    }
    
    // Check stock availability
    if (selectedSize) {
        const availableQuantity = parseInt(selectedSize.getAttribute('data-quantity'));
        if (availableQuantity <= 0) {
            alert('Selected size is out of stock.');
            return;
        }
        
        if (parseInt(quantity) > availableQuantity) {
            alert(`Only ${availableQuantity} pieces available for this size.`);
            return;
        }
    }
    
    // Here you would implement the actual add to cart functionality
    console.log('Adding to cart:', {
        productId: productId,
        size: selectedSize ? selectedSize.value : null,
        quantity: quantity
    });
    
    // Show success message
    alert('Product added to cart successfully!');
}

// Buy Now Functionality
function buyNow(productId) {
    const selectedSize = document.querySelector('input[name="product_size"]:checked');
    const quantity = document.getElementById('productQuantity').value;
    const buyNowBtn = document.getElementById('buyNowBtn');
    
    // Check if button is disabled
    if (buyNowBtn && buyNowBtn.disabled) {
        alert('This item is currently out of stock.');
        return;
    }
    
    // Check if size is required and selected
    const sizeOptions = document.querySelectorAll('input[name="product_size"]');
    if (sizeOptions.length > 0 && !selectedSize) {
        alert('Please select a size before proceeding.');
        return;
    }
    
    // Check stock availability
    if (selectedSize) {
        const availableQuantity = parseInt(selectedSize.getAttribute('data-quantity'));
        if (availableQuantity <= 0) {
            alert('Selected size is out of stock.');
            return;
        }
        
        if (parseInt(quantity) > availableQuantity) {
            alert(`Only ${availableQuantity} pieces available for this size.`);
            return;
        }
    }
    
    // Here you would implement the buy now functionality
    console.log('Buy now:', {
        productId: productId,
        size: selectedSize ? selectedSize.value : null,
        quantity: quantity
    });
    
    // Redirect to checkout
    window.location.href = '/checkout';
}

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    const imageModal = document.getElementById('imageModal');
    const sizeChartModal = document.getElementById('sizeChartModal');
    
    if (event.target === imageModal) {
        closeImageModal();
    }
    
    if (event.target === sizeChartModal) {
        closeSizeChartModal();
    }
});
</script>
@endsection