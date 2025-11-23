// Cart Management System with Local Storage

class CartManager {
    constructor() {
        this.cart = [];
        this.loadCart();
        this.bindEvents();
        this.updateCartCount();
    }

    // Load cart from localStorage
    loadCart() {
        const savedCart = localStorage.getItem('brightness_cart');
        console.log('Loading cart from localStorage:', savedCart);
        
        if (savedCart) {
            try {
                this.cart = JSON.parse(savedCart);
                console.log('Cart loaded successfully:', this.cart);
            } catch (e) {
                console.error('Error loading cart:', e);
                this.cart = [];
            }
        } else {
            console.log('No saved cart found in localStorage');
        }
    }

    // Save cart to localStorage
    saveCart() {
        localStorage.setItem('brightness_cart', JSON.stringify(this.cart));
        this.updateCartCount();
    }

    // Add item to cart
    addToCart(productId, quantity = 1, size = null) {
        return new Promise((resolve, reject) => {
            // Make AJAX request to validate and get product data
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                    size: size
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Check if item already exists in cart
                    const existingItemIndex = this.cart.findIndex(item => 
                        item.id === productId && item.size === size
                    );

                    if (existingItemIndex !== -1) {
                        // Update quantity
                        const newQuantity = this.cart[existingItemIndex].quantity + quantity;
                        const maxQuantity = data.product.stock_quantity;
                        
                        if (newQuantity <= maxQuantity) {
                            this.cart[existingItemIndex].quantity = newQuantity;
                        } else {
                            this.cart[existingItemIndex].quantity = maxQuantity;
                            data.message = `Added to cart. Maximum quantity (${maxQuantity}) reached.`;
                        }
                    } else {
                        // Add new item
                        this.cart.push(data.product);
                    }

                    this.saveCart();
                    this.showAddToCartPopup(data.product, data.message);
                    resolve(data);
                } else {
                    reject(data);
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                reject({ success: false, message: 'Something went wrong. Please try again.' });
            });
        });
    }

    // Remove item from cart
    removeFromCart(productId, size = null) {
        this.cart = this.cart.filter(item => 
            !(item.id === productId && item.size === size)
        );
        this.saveCart();
    }

    // Update item quantity
    updateQuantity(productId, quantity, size = null) {
        const itemIndex = this.cart.findIndex(item => 
            item.id === productId && item.size === size
        );

        if (itemIndex !== -1) {
            if (quantity <= 0) {
                this.removeFromCart(productId, size);
            } else {
                this.cart[itemIndex].quantity = Math.min(quantity, this.cart[itemIndex].stock_quantity);
                this.saveCart();
            }
        }
    }

    // Get cart items
    getCart() {
        return this.cart;
    }

    // Get cart count
    getCartCount() {
        return this.cart.reduce((total, item) => total + item.quantity, 0);
    }

    // Get cart total
    getCartTotal() {
        return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    }

    // Clear cart
    clearCart() {
        this.cart = [];
        this.saveCart();
    }

    // Update cart count in UI
    updateCartCount() {
        const count = this.getCartCount();
        const cartCountElements = document.querySelectorAll('.cart-count, .cart-counter');
        
        cartCountElements.forEach(element => {
            element.textContent = count;
        });
    }

    // Show add to cart popup
    showAddToCartPopup(product, message) {
        // Remove existing popup
        const existingPopup = document.querySelector('.cart-popup-overlay');
        if (existingPopup) {
            existingPopup.remove();
        }

        // Create popup HTML
        const popupHTML = `
            <div class="cart-popup-overlay">
                <div class="cart-popup">
                    <div class="cart-popup-header">
                        <i class="fas fa-check-circle"></i>
                        <h3>Added to Cart</h3>
                    </div>
                    <div class="cart-popup-content">
                        <div class="popup-product">
                            <img src="${product.image ? '/' + product.image : '/site-asset/img/no-image.jpg'}" alt="${product.name}">
                            <div class="popup-product-info">
                                <h4>${product.name}</h4>
                                ${product.size ? `<p>Size: ${product.size}</p>` : ''}
                                <p>Quantity: ${product.quantity}</p>
                                <p>Price: ৳${product.price}</p>
                            </div>
                        </div>
                        <p class="popup-message">${message}</p>
                    </div>
                    <div class="cart-popup-actions">
                        <button type="button" class="btn btn-secondary" onclick="cartManager.closePopup()">
                            Continue Shopping
                        </button>
                        <a href="/checkout" class="btn btn-primary">
                            Go to Checkout
                        </a>
                    </div>
                </div>
            </div>
        `;

        // Add popup to DOM
        document.body.insertAdjacentHTML('beforeend', popupHTML);

        // Auto-close after 10 seconds
        setTimeout(() => {
            this.closePopup();
        }, 10000);
    }

    // Close popup
    closePopup() {
        const popup = document.querySelector('.cart-popup-overlay');
        if (popup) {
            popup.remove();
        }
    }

    // Validate cart with server
    validateCart() {
        if (this.cart.length === 0) return Promise.resolve({ success: true, cart_items: [], has_changes: false });

        return fetch('/cart/validate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                cart_items: this.cart
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.has_changes) {
                    this.cart = data.cart_items;
                    this.saveCart();
                }
            }
            return data;
        });
    }

    // Bind events
    bindEvents() {
        // Close popup when clicking overlay
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('cart-popup-overlay')) {
                this.closePopup();
            }
        });

        // Close popup on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closePopup();
            }
        });
    }
}

// Initialize cart manager
const cartManager = new CartManager();

// Debug cart manager initialization
console.log('Cart manager initialized:', cartManager);
console.log('Current cart items:', cartManager.getCart());

// Make cartManager available globally
window.cartManager = cartManager;
function addToCart(productId) {
    const quantityInput = document.getElementById('productQuantity');
    const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;
    
    // Get selected size
    const sizeInputs = document.querySelectorAll('input[name="product_size"]:checked');
    const size = sizeInputs.length > 0 ? sizeInputs[0].value : null;
    
    // Check if size is required
    const sizeOptions = document.querySelectorAll('input[name="product_size"]');
    if (sizeOptions.length > 0 && !size) {
        alert('Please select a size');
        return;
    }

    // Add loading state to button
    const addToCartBtn = document.querySelector('.add-to-cart');
    if (addToCartBtn) {
        addToCartBtn.disabled = true;
        addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    }

    cartManager.addToCart(productId, quantity, size)
        .then(data => {
            console.log('Product added to cart:', data);
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Failed to add product to cart');
        })
        .finally(() => {
            // Reset button state
            if (addToCartBtn) {
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
            }
        });
}

function buyNow(productId) {
    const quantityInput = document.getElementById('productQuantity');
    const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;
    
    // Get selected size
    const sizeInputs = document.querySelectorAll('input[name="product_size"]:checked');
    const size = sizeInputs.length > 0 ? sizeInputs[0].value : null;
    
    // Check if size is required
    const sizeOptions = document.querySelectorAll('input[name="product_size"]');
    if (sizeOptions.length > 0 && !size) {
        alert('Please select a size');
        return;
    }

    cartManager.addToCart(productId, quantity, size)
        .then(data => {
            // Redirect to checkout
            window.location.href = '/checkout';
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Failed to add product to cart');
        });
}

// Quantity control functions
function increaseQuantity() {
    const quantityInput = document.getElementById('productQuantity');
    if (quantityInput) {
        const currentValue = parseInt(quantityInput.value) || 1;
        const maxValue = parseInt(quantityInput.getAttribute('max')) || 999;
        if (currentValue < maxValue) {
            quantityInput.value = currentValue + 1;
        }
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('productQuantity');
    if (quantityInput) {
        const currentValue = parseInt(quantityInput.value) || 1;
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    }
}

// Make cartManager available globally
window.cartManager = cartManager;

// Debug function to manually add test items to cart
window.addTestItemToCart = function() {
    const testItem = {
        id: 1,
        name: 'Test Product',
        slug: 'test-product',
        image: 'uploads/products/test.jpg',
        price: 1000,
        original_price: 1200,
        sale_price: 1000,
        quantity: 1,
        size: 'M',
        stock_quantity: 10,
        sku: 'TEST001'
    };
    
    cartManager.cart.push(testItem);
    cartManager.saveCart();
    console.log('Test item added to cart:', testItem);
    console.log('Current cart:', cartManager.getCart());
};
