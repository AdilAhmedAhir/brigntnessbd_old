@extends('website.layouts.app')

@section('title', 'Checkout - Brightness Fashion | Complete Your Order')


@section('content')
<div class="checkout-page">
    <!-- Checkout Header -->
    <div class="checkout-header">
        <div class="container">
            <h1>Checkout</h1>
            <p>Complete your order with secure payment</p>
            
            <div class="checkout-breadcrumb">
                <div class="breadcrumb-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Cart</span>
                </div>
                <div class="breadcrumb-item active">
                    <i class="fas fa-credit-card"></i>
                    <span>Checkout</span>
                </div>
                <div class="breadcrumb-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Complete</span>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Checkout Content -->
    <div class="container">

        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <div id="checkoutErrors" class="alert alert-error" style="display: none;"></div>
        
        <div class="checkout-content">
            <!-- Checkout Form -->
            <div class="checkout-form-section">
                <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}">
                    @csrf
                    
                    <!-- Hidden input for cart items -->
                    <input type="hidden" name="cart_items_json" id="cartItemsJson">
                    
                    <!-- Customer Information -->
                    <div class="section-title">
                        <i class="fas fa-user"></i>
                        Customer Information
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="customer_name" class="form-label">Full Name *</label>
                            <input type="text" id="customer_name" name="customer_name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_phone" class="form-label">Phone Number *</label>
                            <input type="tel" id="customer_phone" name="customer_phone" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="customer_email" class="form-label">Email Address *</label>
                        <input type="email" id="customer_email" name="customer_email" class="form-control" required>
                    </div>

                    <!-- Account Creation for Guest Users -->
                    @guest
                        <input type="hidden" name="create_account" value="1">
                        <div class="password-section">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="password" class="form-label">Password *</label>
                                    <input type="password" id="password" name="password" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                            <small>Create an account for faster checkout next time</small>
                        </div>

                        <div class="login-section">
                            <p>Already have an account?</p>
                            <a href="{{ route('account.login') }}?redirect_to={{ urlencode(route('checkout')) }}" class="btn-login">Login</a>
                        </div>
                    @endguest

                    <!-- Shipping Information -->
                    <div class="section-title" style="margin-top: 40px;">
                        <i class="fas fa-shipping-fast"></i>
                        Shipping Information
                    </div>
                    
                    <div class="form-group">
                        <label for="shipping_address" class="form-label">Shipping Address *</label>
                        <textarea id="shipping_address" name="shipping_address" class="form-control" rows="3" placeholder="House/Flat no, Road, Area" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="shipping_city" class="form-label">City *</label>
                            <input type="text" id="shipping_city" name="shipping_city" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="shipping_postal_code" class="form-label">Postal Code *</label>
                            <input type="text" id="shipping_postal_code" name="shipping_postal_code" class="form-control" required>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="form-group">
                        <label for="order_notes" class="form-label">Order Notes (Optional)</label>
                        <textarea id="order_notes" name="order_notes" class="form-control" rows="3" placeholder="Special instructions for delivery..."></textarea>
                    </div>

                    <!-- Payment Method -->
                    <div class="section-title" style="margin-top: 40px;">
                        <i class="fas fa-credit-card"></i>
                        Payment Method
                    </div>
                    
                    <div class="payment-method">
                        <div class="payment-option">
                            <label class="payment-label">
                                <input type="radio" name="payment_method" value="cash_on_delivery" checked>
                                <div class="payment-info">
                                    <div class="payment-title">
                                        <i class="fas fa-money-bill-wave"></i>
                                        Cash on Delivery
                                    </div>
                                    <div class="payment-desc">Pay when you receive your order</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Discount Coupon -->
                    <div class="section-title" style="margin-top: 40px;">
                        <i class="fas fa-ticket-alt"></i>
                        Discount Coupon
                    </div>
                    
                    <div class="coupon-section">
                        <div class="coupon-form">
                            <div class="form-row" style="align-items: end;">
                                <div class="form-group" style="flex: 1; margin-right: 10px;">
                                    <label for="coupon_code" class="form-label">Enter Coupon Code</label>
                                    <input type="text" id="coupon_code" name="coupon_code" class="form-control" placeholder="Enter coupon code" style="text-transform: uppercase;">
                                    <input type="hidden" id="applied_coupon_id" name="applied_coupon_id">
                                </div>
                                <div class="form-group">
                                    <button type="button" id="applyCouponBtn" class="btn-apply-coupon">Apply</button>
                                </div>
                            </div>
                            <div id="couponMessage" class="coupon-message" style="display: none;"></div>
                        </div>
                        
                        <div id="appliedCoupon" class="applied-coupon" style="display: none;">
                            <div class="coupon-info">
                                <div class="coupon-details">
                                    <i class="fas fa-check-circle"></i>
                                    <span id="appliedCouponName"></span>
                                    <small id="appliedCouponDesc"></small>
                                </div>
                                <button type="button" id="removeCouponBtn" class="btn-remove-coupon">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Place Order Button -->
                    <div class="form-submit-section" style="margin-top: 30px;">
                        <button type="submit" id="placeOrderBtn" class="btn-place-order" disabled>
                            Place Order
                        </button>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <div class="summary-title">Order Summary</div>
                
                <div id="cartSummary">
                    <div class="cart-empty">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Your cart is empty</p>
                        <a href="{{ route('products.index') }}" class="btn-login">Continue Shopping</a>
                    </div>
                </div>

                <div id="orderTotals" style="display: none;">
                    <div class="order-totals">
                        <div class="total-row">
                            <span>Subtotal:</span>
                            <span class="amount" id="subtotalAmount">৳0.00</span>
                        </div>
                        <div class="total-row">
                            <span>Shipping:</span>
                            <span class="amount">Free</span>
                        </div>
                        <div class="total-row discount-row" id="discountRow" style="display: none;">
                            <span>Discount:</span>
                            <span class="amount discount" id="discountAmount">-৳0.00</span>
                        </div>
                        <div class="total-row final">
                            <span>Total:</span>
                            <span class="amount" id="totalAmount">৳0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('styles')
    <link rel="stylesheet" href="{{ asset('site-asset/css/checkout.css') }}">
@endpush


@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('site-asset/js/cart.js') }}"></script>
    <script>
    $(document).ready(function() {
        // Wait for cart manager to be ready
        setTimeout(function() {
            initCheckout();
        }, 500);
        
        // Form submission handler
        $('#checkoutForm').on('submit', function(e) {
            console.log('Form submission started');
            
            // Update cart items in hidden input before submission
            const cartItems = window.cartManager ? window.cartManager.getCart() : [];
            console.log('Cart items for submission:', cartItems);
            
            $('#cartItemsJson').val(JSON.stringify(cartItems));
            
            if (cartItems.length === 0) {
                e.preventDefault();
                showAlert('Your cart is empty. Please add some products before checkout.', 'error');
                return false;
            }
            
            // Show loading state
            const submitBtn = $('#placeOrderBtn');
            submitBtn.prop('disabled', true);
            submitBtn.addClass('loading');
            submitBtn.text('Processing...');
            
            console.log('Form will submit normally');
            // Form will submit normally
            return true;
        });

        // Manual button enabler after a delay (for debugging)
        setTimeout(function() {
            const savedCart = localStorage.getItem('brightness_cart');
            if (savedCart) {
                try {
                    const cartItems = JSON.parse(savedCart);
                    if (cartItems && cartItems.length > 0) {
                        console.log('Manually enabling button - cart has items:', cartItems.length);
                        $('#placeOrderBtn').prop('disabled', false);
                    }
                } catch (e) {
                    console.error('Error checking cart for manual enable:', e);
                }
            }
        }, 1000);
    });

    function initCheckout() {
        console.log('Initializing checkout...');
        console.log('CartManager instance:', window.cartManager);
        console.log('Current cart:', window.cartManager ? window.cartManager.getCart() : 'No cart manager');
        
        // Fallback: Check localStorage directly if cart manager fails
        let cartItems = [];
        if (window.cartManager) {
            cartItems = window.cartManager.getCart();
        } else {
            // Try to get cart from localStorage directly
            try {
                const savedCart = localStorage.getItem('brightness_cart');
                if (savedCart) {
                    cartItems = JSON.parse(savedCart);
                    console.log('Cart loaded directly from localStorage:', cartItems);
                }
            } catch (e) {
                console.error('Error loading cart from localStorage:', e);
            }
        }
        
        // Update summary with current cart
        updateCartSummary(cartItems);
        
        // Check if cartManager exists for validation
        if (!window.cartManager) {
            console.error('CartManager not found! Using fallback cart data.');
            return;
        }
        
        // Then validate cart and update summary
        window.cartManager.validateCart().then(function(data) {
            console.log('Cart validation response:', data);
            if (data.success) {
                updateCartSummary(data.cart_items);
                
                if (data.has_changes) {
                    showAlert('Some items in your cart have been updated due to price or stock changes.', 'warning');
                }
            } else {
                console.error('Cart validation failed:', data);
                updateCartSummary(cartItems); // Fallback to current cart
            }
        }).catch(function(error) {
            console.error('Cart validation error:', error);
            updateCartSummary(cartItems); // Fallback to current cart
        });
    }

    function updateCartSummary(cartItems) {
        console.log('Updating cart summary with items:', cartItems);
        
        const $cartSummary = $('#cartSummary');
        const $orderTotals = $('#orderTotals');
        const $placeOrderBtn = $('#placeOrderBtn');
        
        console.log('Cart summary element:', $cartSummary);
        console.log('Order totals element:', $orderTotals);
        console.log('Place order button:', $placeOrderBtn);
        
        if (!cartItems || cartItems.length === 0) {
            console.log('No cart items, showing empty cart message');
            $cartSummary.html(`
                <div class="cart-empty">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Your cart is empty</p>
                    <a href="{{ route('products.index') }}" class="btn-login">Continue Shopping</a>
                </div>
            `);
            $orderTotals.hide();
            $placeOrderBtn.prop('disabled', true);
            return;
        }
        
        console.log('Building cart HTML for', cartItems.length, 'items');
        let cartHTML = '<div class="cart-items">';
        let subtotal = 0;
        
        $.each(cartItems, function(index, item) {
            console.log('Processing item', index, ':', item);
            const itemTotal = parseFloat(item.price) * parseInt(item.quantity);
            subtotal += itemTotal;
            
            cartHTML += `
                <div class="cart-item">
                    <div class="item-image">
                        <img src="${item.image ? '/' + item.image : '/site-asset/img/no-image.jpg'}" alt="${item.name}">
                    </div>
                    <div class="item-details">
                        <div class="item-name">${item.name}</div>
                        <div class="item-meta">
                            <span>Qty: ${item.quantity}${item.size ? ' | Size: ' + item.size : ''}</span>
                            <span class="item-price">৳${parseFloat(item.price).toFixed(2)}</span>
                        </div>
                    </div>
                </div>
            `;
        });
        
        cartHTML += '</div>';
        console.log('Final cart HTML:', cartHTML);
        
        $cartSummary.html(cartHTML);
        
        // Update totals (this will handle coupon calculations too)
        console.log('Subtotal calculated:', subtotal);
        updateOrderTotals();
        
        $orderTotals.show();
        $placeOrderBtn.prop('disabled', false);
        
        console.log('Place Order button enabled');
    }

    function showAlert(message, type = 'info') {
        const $alertContainer = $('#checkoutErrors');
        $alertContainer.attr('class', `alert alert-${type}`);
        $alertContainer.text(message);
        $alertContainer.show();
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $alertContainer.hide();
        }, 5000);
        
        // Scroll to alert
        $alertContainer[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Helper function to calculate subtotal from cart items
    function calculateSubtotal(cartItems) {
        let subtotal = 0;
        if (cartItems && Array.isArray(cartItems)) {
            cartItems.forEach(function(item) {
                const price = parseFloat(item.price) || 0;
                const quantity = parseInt(item.quantity) || 0;
                subtotal += price * quantity;
            });
        }
        return subtotal;
    }

    // Coupon functionality
    let appliedCoupon = null;
    let originalSubtotal = 0;

    // Apply coupon button click
    $('#applyCouponBtn').on('click', function() {
        const couponCode = $('#coupon_code').val().trim();
        if (!couponCode) {
            showCouponMessage('Please enter a coupon code.', 'error');
            return;
        }

        const cartItems = window.cartManager ? window.cartManager.getCart() : [];
        if (cartItems.length === 0) {
            showCouponMessage('Please add items to cart before applying coupon.', 'error');
            return;
        }

        const subtotal = calculateSubtotal(cartItems);
        validateCoupon(couponCode, subtotal);
    });

    // Remove coupon button click
    $('#removeCouponBtn').on('click', function() {
        removeCoupon();
    });

    // Enter key support for coupon input
    $('#coupon_code').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#applyCouponBtn').click();
        }
    });

    function validateCoupon(code, amount) {
        const $applyBtn = $('#applyCouponBtn');
        $applyBtn.prop('disabled', true).text('Validating...');

        $.ajax({
            url: '{{ route("coupon.validate") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                code: code,
                amount: amount,
                @auth
                user_id: {{ auth()->id() }}
                @endauth
            },
            success: function(response) {
                if (response.success) {
                    applyCoupon(response.coupon, response.discount_amount);
                    showCouponMessage('Coupon applied successfully!', 'success');
                } else {
                    showCouponMessage(response.message, 'error');
                }
            },
            error: function(xhr) {
                let message = 'Error validating coupon. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showCouponMessage(message, 'error');
            },
            complete: function() {
                $applyBtn.prop('disabled', false).text('Apply');
            }
        });
    }

    function applyCoupon(coupon, discountAmount) {
        appliedCoupon = {
            id: coupon.id,
            code: coupon.code,
            name: coupon.name,
            discount: discountAmount
        };

        // Store original subtotal if not already stored
        if (originalSubtotal === 0) {
            const cartItems = window.cartManager ? window.cartManager.getCart() : [];
            originalSubtotal = calculateSubtotal(cartItems);
        }

        // Update UI
        $('#applied_coupon_id').val(coupon.id);
        $('#appliedCouponName').text(coupon.name + ' (' + coupon.code + ')');
        
        let description = '';
        if (coupon.type === 'percentage') {
            description = coupon.value + '% off';
        } else {
            description = '৳' + coupon.value + ' off';
        }
        $('#appliedCouponDesc').text(description);

        $('#appliedCoupon').show();
        $('.coupon-form').hide();

        // Update totals
        updateOrderTotals();
    }

    function removeCoupon() {
        appliedCoupon = null;
        originalSubtotal = 0;
        
        // Clear form
        $('#coupon_code').val('');
        $('#applied_coupon_id').val('');
        
        // Update UI
        $('#appliedCoupon').hide();
        $('.coupon-form').show();
        $('#couponMessage').hide();

        // Update totals
        updateOrderTotals();
    }

    function updateOrderTotals() {
        const cartItems = window.cartManager ? window.cartManager.getCart() : [];
        const subtotal = calculateSubtotal(cartItems);

        let finalTotal = subtotal;
        
        if (appliedCoupon) {
            $('#discountRow').show();
            $('#discountAmount').text('-৳' + appliedCoupon.discount.toFixed(2));
            finalTotal = subtotal - appliedCoupon.discount;
        } else {
            $('#discountRow').hide();
        }

        $('#subtotalAmount').text('৳' + subtotal.toFixed(2));
        $('#totalAmount').text('৳' + finalTotal.toFixed(2));
    }

    function showCouponMessage(message, type) {
        const $messageDiv = $('#couponMessage');
        $messageDiv.attr('class', 'coupon-message ' + type);
        $messageDiv.text(message);
        $messageDiv.show();

        if (type === 'success') {
            setTimeout(function() {
                $messageDiv.hide();
            }, 3000);
        }
    }
    </script>

    <style>
    .payment-method {
        margin-bottom: 25px;
    }

    .payment-option {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .payment-option:hover {
        border-color: var(--golden-color);
        background: #f8f9ff;
    }

    .payment-label {
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        margin: 0;
    }

    .payment-label input[type="radio"] {
        width: 20px;
        height: 20px;
        accent-color: #667eea;
    }

    .payment-info {
        flex: 1;
    }

    .payment-title {
        color: #333;
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .payment-title i {
        color: var(--golden-color);
    }

    .payment-desc {
        color: #666;
        font-size: 14px;
    }

    .form-submit-section {
        padding: 20px 0;
        border-top: 1px solid #e9ecef;
    }

    .btn-place-order {
        width: 100%;
        padding: 15px 30px;
        background: var(--golden-gradient);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-place-order:hover:not(:disabled) {
        background: var(--golden-dark);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-place-order:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .btn-place-order.loading {
        background: #999;
        cursor: not-allowed;
    }

    .password-section {
        margin: 20px 0;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .password-section small {
        color: #666;
        font-style: italic;
        margin-top: 10px;
        display: block;
    }

    /* Coupon Styles */
    .coupon-section {
        margin-bottom: 25px;
    }

    .coupon-form .form-row {
        display: flex;
        gap: 10px;
        align-items: end;
    }

    .btn-apply-coupon {
        padding: 17px 30px;
        background: var(--golden-gradient);
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn-apply-coupon:hover:not(:disabled) {
        background: var(--golden-dark);
        transform: translateY(-2px);
    }

    .btn-apply-coupon:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    .coupon-message {
        margin-top: 10px;
        padding: 10px 15px;
        border-radius: 6px;
        font-size: 14px;
    }

    .coupon-message.success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .coupon-message.error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .applied-coupon {
        margin-top: 15px;
    }

    .coupon-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        background: #d4edda;
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        color: #155724;
    }

    .coupon-details {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .coupon-details i {
        color: #28a745;
        font-size: 18px;
    }

    .coupon-details small {
        display: block;
        color: #6c757d;
        font-size: 12px;
        margin-top: 2px;
    }

    .btn-remove-coupon {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        transition: background 0.3s ease;
    }

    .btn-remove-coupon:hover {
        background: rgba(220, 53, 69, 0.1);
    }

    .total-row.discount-row {
        color: #28a745;
        font-weight: 600;
    }

    .amount.discount {
        color: #28a745 !important;
    }
    </style>
@endpush