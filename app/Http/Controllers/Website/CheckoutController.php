<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function index()
    {
        return view('website.checkout');
    }

    /**
     * Process checkout and create order
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:1000',
            'shipping_city' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:20',
            'order_notes' => 'nullable|string|max:1000',
            'create_account' => 'nullable|boolean',
            'password' => 'required_if:create_account,true|string|min:8|confirmed',
            'applied_coupon_id' => 'nullable|exists:coupons,id',
        ]);

        // Get cart items from the hidden input
        $cartItemsJson = $request->input('cart_items_json');
        if (!$cartItemsJson) {
            return back()->withErrors(['error' => 'Your cart is empty. Please add some products before checkout.']);
        }

        $cartItems = json_decode($cartItemsJson, true);
        if (!$cartItems || !is_array($cartItems) || count($cartItems) === 0) {
            return back()->withErrors(['error' => 'Your cart is empty. Please add some products before checkout.']);
        }

        DB::beginTransaction();

        try {
            $user = null;

            // Handle user creation or login
            if ($request->create_account) {
                // Check if user already exists
                $existingUser = User::where('email', $request->customer_email)->first();
                
                if ($existingUser) {
                    return back()->withErrors(['error' => 'An account with this email already exists. Please login instead.']);
                }

                // Create new user account
                $user = User::create([
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone' => $request->customer_phone,
                    'password' => Hash::make($request->password),
                    'role' => 'customer',
                    'status' => 'active',
                ]);

                // Log the user in
                Auth::login($user);
            } else {
                // Check if user is already logged in
                $user = Auth::user();
                
                // If not logged in, check if user exists with this email
                if (!$user) {
                    $user = User::where('email', $request->customer_email)->first();
                }
            }

            // Validate cart items and calculate total
            $orderItems = [];
            $subtotal = 0;
            $productsToUpdate = []; // Store products for stock update

            foreach ($cartItems as $item) {
                // Use lockForUpdate to prevent race conditions on stock
                $product = Product::where('id', $item['id'])->lockForUpdate()->first();
                
                if (!$product || $product->status !== 'active') {
                    return back()->withErrors(['error' => "Product '{$item['name']}' is not available."]);
                }

                if ($product->stock_quantity < $item['quantity']) {
                    return back()->withErrors(['error' => "Not enough stock for '{$product->name}'. Available: {$product->stock_quantity}"]);
                }

                $price = $product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price;
                $itemTotal = $price * $item['quantity'];
                $subtotal += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'] ?? null,
                    'total' => $itemTotal,
                ];

                // Store product and quantity for stock update
                $productsToUpdate[] = [
                    'product' => $product,
                    'quantity' => $item['quantity']
                ];
            }

            // Handle coupon validation and discount calculation
            $coupon = null;
            $discountAmount = 0;
            $totalAmount = $subtotal;

            if ($request->applied_coupon_id) {
                $coupon = Coupon::find($request->applied_coupon_id);
                
                if ($coupon && $coupon->isValid()) {
                    // Double check if user can use this coupon
                    if (!$user || $coupon->canBeUsedByUser($user->id)) {
                        $discountAmount = $coupon->calculateDiscount($subtotal);
                        $totalAmount = $subtotal - $discountAmount;
                        
                        // Ensure total is not negative
                        if ($totalAmount < 0) {
                            $totalAmount = 0;
                            $discountAmount = $subtotal;
                        }
                    } else {
                        return back()->withErrors(['error' => 'You have reached the usage limit for this coupon.']);
                    }
                } else {
                    return back()->withErrors(['error' => 'The applied coupon is no longer valid.']);
                }
            }

            // Generate unique order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            while (Order::where('order_number', $orderNumber)->exists()) {
                $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }

            // Prepare shipping and billing address arrays
            $shippingAddress = [
                'name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
                'address' => $request->shipping_address,
                'city' => $request->shipping_city,
                'postal_code' => $request->shipping_postal_code,
            ];
            
            // Use shipping address as billing address for now (same as shipping)
            $billingAddress = $shippingAddress;

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user ? $user->id : null,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'shipping_fee' => 0, // Free shipping for now
                'tax_amount' => 0, // No tax for now
                'coupon_id' => $coupon ? $coupon->id : null,
                'coupon_code' => $coupon ? $coupon->code : null,
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'pending',
                'status' => 'pending',
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress,
                'notes' => $request->order_notes,
            ]);

            // Create order items
            foreach ($orderItems as $itemData) {
                // Prepare product meta with size information
                $productMeta = [];
                if (!empty($itemData['size'])) {
                    $productMeta['size'] = $itemData['size'];
                }
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'product_name' => $itemData['product_name'],
                    'product_sku' => $itemData['product_sku'],
                    'price' => $itemData['price'],
                    'quantity' => $itemData['quantity'],
                    'total' => $itemData['total'],
                    'product_meta' => $productMeta,
                ]);
            }

            // Update product stock quantities
            foreach ($productsToUpdate as $productData) {
                $product = $productData['product'];
                $quantity = $productData['quantity'];
                
                // Use decrement to safely update stock
                if ($product) {
                    $product->decrement('stock_quantity', $quantity);
                    
                    // Log the stock update for debugging
                    \Log::info("Stock updated for product #{$product->id}: {$product->name}, decremented by {$quantity}, new stock: " . $product->fresh()->stock_quantity);
                }
            }

            // Increment coupon usage count if coupon was used
            if ($coupon) {
                $coupon->incrementUsage();
            }

            DB::commit();

            // Clear cart items from session/localStorage (will be handled by JavaScript)
            session()->flash('order_success', [
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

            return redirect()->route('account.order.view', ['id' => $order->id, 'new_order' => 'true']);

        } catch (\Exception $e) {
            DB::rollback();
            
            // Log the actual error for debugging
            \Log::error('Checkout Error: ' . $e->getMessage());
            \Log::error('Checkout Error Trace: ' . $e->getTraceAsString());
            
            // In debug mode, show the actual error
            if (config('app.debug')) {
                return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
            }
            
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }
}
