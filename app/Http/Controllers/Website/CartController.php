<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product is active and in stock
        if ($product->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available.'
            ], 400);
        }

         if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available.'
            ], 400);
        }

        // Prepare cart item data
        $cartItem = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'image' => $product->image,
            'price' => $product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price,
            'original_price' => $product->price,
            'sale_price' => $product->sale_price,
            'quantity' => $request->quantity,
            'size' => $request->size,
            'stock_quantity' => $product->stock_quantity,
            'sku' => $product->sku,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'product' => $cartItem
        ]);
    }

    /**
     * Get cart items count
     */
    public function getCartCount()
    {
        // Since we're using localStorage, this is just a placeholder
        // The actual count will be managed by JavaScript
        return response()->json([
            'success' => true,
            'count' => 0
        ]);
    }

    /**
     * Validate cart items (check stock, prices, etc.)
     */
    public function validateCart(Request $request)
    {
        $cartItems = $request->input('cart_items', []);
        $validatedItems = [];
        $hasChanges = false;

        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);
            
            if (!$product || $product->status !== 'active') {
                $hasChanges = true;
                continue; // Skip invalid products
            }

            $currentPrice = $product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price;
            $maxQuantity = min($item['quantity'], $product->stock_quantity);

            $validatedItem = $item;
            
            // Update price if changed
            if ($validatedItem['price'] != $currentPrice) {
                $validatedItem['price'] = $currentPrice;
                $validatedItem['original_price'] = $product->price;
                $validatedItem['sale_price'] = $product->sale_price;
                $hasChanges = true;
            }

            // Update quantity if exceeds stock
            if ($validatedItem['quantity'] > $product->stock_quantity) {
                $validatedItem['quantity'] = $product->stock_quantity;
                $hasChanges = true;
            }

            // Update stock quantity
            $validatedItem['stock_quantity'] = $product->stock_quantity;

            $validatedItems[] = $validatedItem;
        }

        return response()->json([
            'success' => true,
            'cart_items' => $validatedItems,
            'has_changes' => $hasChanges
        ]);
    }
}
