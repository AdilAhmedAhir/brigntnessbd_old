<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\HomeSettingsController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\ProductController as WebsiteProductController;
use App\Http\Controllers\Website\CartController;
use App\Http\Controllers\Website\CheckoutController;
use App\Http\Controllers\Website\AccountController;
use App\Http\Controllers\Website\ContactController;
// ADD THIS LINE FOR THE NEW STORE CONTROLLER
use App\Http\Controllers\Admin\StoreController;



// Website Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Login route for Laravel's default auth middleware
Route::get('/login', function () {
    return redirect()->route('account.login');
})->name('login');

// Website product and category routes
Route::get('/products', [WebsiteProductController::class, 'index'])->name('products.index');
Route::get('/categories/{category:slug}', [WebsiteProductController::class, 'category'])->name('categories.show');
Route::get('/products/{product}', [WebsiteProductController::class, 'show'])->name('products.show');

// Website checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');

// Cart routes
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
Route::post('/cart/validate', [CartController::class, 'validateCart'])->name('cart.validate');

// Coupon validation route (for website)
Route::post('/coupon/validate', [CouponController::class, 'validateCoupon'])->name('coupon.validate');

// Contact routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'sendMessage'])->name('contact.send');

// Static pages routes
Route::view('/shipping-returns', 'website.pages.shipping-returns')->name('shipping-returns');
Route::view('/privacy-policy', 'website.pages.privacy-policy')->name('privacy-policy');
Route::view('/terms-of-service', 'website.pages.terms-of-service')->name('terms-of-service');

// =======================================================================
// ADD THE PUBLIC STORE LOCATOR ROUTE HERE
// =======================================================================
Route::get('/store-locator', [HomeController::class, 'storeLocator'])->name('store-locator');


// Account routes
Route::prefix('account')->name('account.')->group(function () {
    // Guest routes
    Route::get('/login', [AccountController::class, 'showLogin'])->name('login');
    Route::post('/login', [AccountController::class, 'login'])->name('login.post');
    Route::post('/register', [AccountController::class, 'register'])->name('register');
    
    // Authenticated routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AccountController::class, 'logout'])->name('logout');
        Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [AccountController::class, 'orderView'])->name('order.view');
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::post('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    });
});

// Legacy my-account route redirect
Route::get('/my-account', function () {
    return redirect()->route('account.dashboard');
})->name('my-account');


// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Authentication Routes
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('password/reset', [AuthController::class, 'showPasswordResetForm'])->name('password.request');

    // Protected Admin Routes
    Route::middleware(['admin.auth'])->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('profile', [AdminController::class, 'profile'])->name('profile');
        Route::get('activity-log', [AdminController::class, 'activityLog'])->name('activity-log');

        // Product and Category Routes
        Route::resource('products', ProductController::class);
        
        // =======================================================================
        // ADD THE ADMIN STORE MANAGEMENT ROUTE HERE
        // =======================================================================
        // Inside routes/web.php, within the admin group

        Route::resource('stores', StoreController::class);
        Route::post('stores/hero', [StoreController::class, 'updateHero'])->name('stores.updateHero'); // <-- ADD THIS LINE


        // Simple category routes
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{id}/edit', [CategoryController::class, 'show'])->name('categories.edit');
        Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        
        // Coupon routes
        Route::get('coupons', [CouponController::class, 'index'])->name('coupons.index');
        Route::post('coupons', [CouponController::class, 'store'])->name('coupons.store');
        Route::get('coupons/{id}/edit', [CouponController::class, 'show'])->name('coupons.edit');
        Route::put('coupons/{id}', [CouponController::class, 'update'])->name('coupons.update');
        Route::delete('coupons/{id}', [CouponController::class, 'destroy'])->name('coupons.destroy');
        
        // Main Page Route
        Route::get('edit-home', [HomeSettingsController::class, 'index'])->name('edit_home');
        Route::post('edit-home', [HomeSettingsController::class, 'update'])->name('edit_home.update');

        // Orders Routes
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
        Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::patch('orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
        
        // Customers Routes
        Route::resource('customers', CustomerController::class)->only(['index', 'show']);
        Route::patch('customers/{customer}/status', [CustomerController::class, 'updateStatus'])->name('customers.update-status');

        // Settings Routes
        Route::get('settings', [SiteSettingsController::class, 'index'])->name('settings.site');
        Route::post('settings', [SiteSettingsController::class, 'update'])->name('settings.site.update');
        
        // Admin Settings Routes
        Route::get('admin-settings', [AdminSettingsController::class, 'index'])->name('settings.admin');
        Route::post('admin-settings', [AdminSettingsController::class, 'update'])->name('settings.admin.update');
    });
});


// Redirect /admin to /admin/dashboard
Route::redirect('/admin', '/admin/dashboard');