@extends('website.layouts.app')

@section('title', 'Order #' . $order->order_number . ' - Brightness Fashion | Order Details')

@push('styles')
<link rel="stylesheet" href="{{ asset('site-asset/css/account.css') }}">
@endpush

@section('content')
<div class="account-page">
    <!-- Account Header -->
    <div class="account-header">
        <div class="container">
            <h1>Order Details</h1>
            <p>View your order information and track status</p>
        </div>
    </div>

    <!-- Account Content -->
    <div class="container">
        <div class="account-content">
            <!-- Sidebar -->
            <div class="account-sidebar">
                <div class="sidebar-profile">
                    <div class="profile-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="profile-name">{{ auth()->user()->name }}</div>
                    <div class="profile-email">{{ auth()->user()->email }}</div>
                </div>

                <nav>
                    <ul class="account-nav">
                        <li><a href="{{ route('account.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a></li>
                        <li><a href="{{ route('account.orders') }}" class="active">
                            <i class="fas fa-shopping-bag"></i>
                            Orders
                        </a></li>
                        <li><a href="{{ route('account.profile') }}">
                            <i class="fas fa-user-edit"></i>
                            Profile
                        </a></li>
                        <li>
                            <form action="{{ route('account.logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="logout-button" style="width: 100%; background: none; border: none; padding: 0; text-align: left;">
                                    <a href="#" style="pointer-events: none;">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Logout
                                    </a>
                                </button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="account-main">
                <div class="section-title">
                    Order #{{ $order->order_number }}
                    <a href="{{ route('account.orders') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i>
                        Back to Orders
                    </a>
                </div>

                @if($isNewOrder)
                    <div class="thank-you-notice">
                        <h3>
                            <i class="fas fa-check-circle"></i>
                            Thank You for Your Order!
                        </h3>
                        <p>Your order has been successfully placed. We'll send you an email confirmation shortly with tracking details.</p>
                    </div>
                @endif

                <!-- Order Overview -->
                <div class="order-overview">
                    <div class="overview-card">
                        <div class="overview-label">Order Date</div>
                        <div class="overview-value">{{ $order->created_at->format('M d, Y') }}</div>
                    </div>
                    
                    <div class="overview-card status">
                        <div class="overview-label">Status</div>
                        <div class="overview-value">{{ ucfirst($order->status) }}</div>
                    </div>
                    
                    <div class="overview-card">
                        <div class="overview-label">Payment</div>
                        <div class="overview-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</div>
                    </div>
                    
                    <div class="overview-card">
                        <div class="overview-label">Total Amount</div>
                        <div class="overview-value">৳{{ number_format($order->total_amount, 2) }}</div>
                    </div>
                </div>

                <!-- Order Sections -->
                <div class="order-sections">
                    <!-- Order Items -->
                    <div class="order-items-section">
                        <div class="section-header">Order Items ({{ $order->orderItems->count() }} items)</div>
                        
                        @foreach($order->orderItems as $item)
                            <div class="order-item">
                                <div class="item-image">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset($item->product->image) }}" alt="{{ $item->product_name }}">
                                    @else
                                        <img src="{{ asset('site-asset/img/no-image.jpg') }}" alt="{{ $item->product_name }}">
                                    @endif
                                </div>
                                
                                <div class="item-details">
                                    <div class="item-name">{{ $item->product_name }}</div>
                                    @if($item->product_sku)
                                        <div class="item-sku">SKU: {{ $item->product_sku }}</div>
                                    @endif
                                    
                                    <div class="item-meta">
                                        <span>Quantity: {{ $item->quantity }}</span>
                                        @if($item->size)
                                            <span>Size: {{ $item->size }}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="item-pricing">
                                        <span class="item-unit-price">৳{{ number_format($item->price, 2) }} each</span>
                                        <span class="item-total-price">৳{{ number_format($item->total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary-section">
                        <div class="section-header">Order Summary</div>
                        
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span class="amount">৳{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        
                        @if($order->discount_amount > 0)
                        <div class="summary-row discount">
                            <span>
                                Discount ({{ $order->coupon_code }}):
                            </span>
                            <span class="amount discount-amount">-৳{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                        @endif
                        
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span class="amount">
                                @if($order->shipping_cost > 0)
                                    ৳{{ number_format($order->shipping_cost, 2) }}
                                @else
                                    Free
                                @endif
                            </span>
                        </div>
                        
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span class="amount">৳{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="shipping-info">
                    <div class="section-header">Shipping & Contact Information</div>
                    
                    <div class="shipping-grid">
                        <div class="info-section">
                            <h4>Shipping Address</h4>
                            <p><strong>{{ $order->shipping_address['name'] ?? 'N/A' }}</strong></p>
                            <p>{{ $order->shipping_address['address'] ?? 'N/A' }}</p>
                            <p>{{ $order->shipping_address['city'] ?? 'N/A' }}, {{ $order->shipping_address['postal_code'] ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="info-section">
                            <h4>Contact Details</h4>
                            <p><strong>Email:</strong> {{ $order->shipping_address['email'] ?? 'N/A' }}</p>
                            <p><strong>Phone:</strong> {{ $order->shipping_address['phone'] ?? 'N/A' }}</p>
                            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        </div>
                    </div>
                    
                    @if($order->notes)
                        <div class="info-section" style="margin-top: 20px;">
                            <h4>Order Notes</h4>
                            <p>{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($isNewOrder ?? false)
<script>
// Clear cart when order is successfully placed
$(document).ready(function() {
    // Check if cart manager exists and clear cart
    if (window.cartManager) {
        window.cartManager.clearCart();
        console.log('Cart cleared after successful order placement');
    }
    
    // Show success message
    const successMessage = 'Your order has been placed successfully! Order #{{ $order->order_number }}';
    
    // Create success alert
    const alertHTML = `
        <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; color: #155724;">
            <i class="fas fa-check-circle" style="margin-right: 10px;"></i>
            ${successMessage}
        </div>
    `;
    
    // Insert alert at the top of main content
    $('.main-content').prepend(alertHTML);
    
    // Auto-hide after 10 seconds
    setTimeout(function() {
        $('.alert-success').fadeOut();
    }, 10000);
});
</script>

<style>
/* Discount styling for order views */
.order-pricing {
    text-align: right;
}

.order-subtotal,
.order-discount {
    font-size: 13px;
    color: #666;
    margin-bottom: 2px;
}

.order-discount {
    color: #28a745 !important;
}

.order-discount i {
    margin-right: 5px;
}

.summary-row.discount {
    color: #28a745;
    font-weight: 500;
    white-space: nowrap;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.summary-row.discount span:first-child {
    display: flex;
    align-items: center;
    flex-shrink: 1;
    min-width: 0;
}

.summary-row.discount i {
    margin-right: 6px;
    color: #28a745;
    flex-shrink: 0;
}

.discount-amount {
    color: #28a745 !important;
    font-weight: 600;
}
</style>
@endif
@endpush