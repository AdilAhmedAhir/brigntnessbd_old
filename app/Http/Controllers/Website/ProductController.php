<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products with infinite scroll.
     */
    public function index(Request $request)
    {
        $query = Product::where('status', 'active')
                       ->orderBy('created_at', 'desc');

        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Apply category filter if provided
        if ($request->filled('category')) {
            $categoryId = $request->get('category');
            $query->whereJsonContains('category_ids', (int)$categoryId);
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Paginate with 16 products per page
        $products = $query->paginate(16);

        // If it's an AJAX request (for infinite scroll)
        if ($request->ajax()) {
            $productData = $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'description' => $product->description,
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                    'image' => $product->image ? asset($product->image) : null,
                ];
            });

            return response()->json([
                'success' => true,
                'products' => $productData,
                'currentCount' => $products->count() + (($products->currentPage() - 1) * $products->perPage()),
                'totalCount' => $products->total(),
                'hasMore' => $products->hasMorePages()
            ]);
        }

        return view('website.products.archive', compact('products'));
    }

    /**
     * Display products by category.
     */
    public function category(Category $category, Request $request)
    {
        $query = Product::where('status', 'active')
                       ->where(function($q) use ($category) {
                           $q->whereJsonContains('category_ids', $category->id)
                             ->orWhereJsonContains('category_ids', (string)$category->id);
                       })
                       ->orderBy('created_at', 'desc');

        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Paginate with 16 products per page
        $products = $query->paginate(16);

        // If it's an AJAX request (for infinite scroll)
        if ($request->ajax()) {
            $productData = $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'description' => $product->description,
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                    'image' => $product->image ? asset($product->image) : null,
                ];
            });

            return response()->json([
                'success' => true,
                'products' => $productData,
                'currentCount' => $products->count() + (($products->currentPage() - 1) * $products->perPage()),
                'totalCount' => $products->total(),
                'hasMore' => $products->hasMorePages()
            ]);
        }

        return view('website.products.archive', compact('products', 'category'));
    }

    /**
     * Display the specified product.
     */
    public function show($product)
    {
        // Try to find by slug first, then by id
        $product = Product::where('slug', $product)
                         ->orWhere('id', $product)
                         ->where('status', 'active')
                         ->firstOrFail();

        // Get related products from the same categories
        $relatedProducts = collect();
        
        if ($product->category_ids && is_array($product->category_ids)) {
            $relatedProducts = Product::where('status', 'active')
                ->where('id', '!=', $product->id)
                ->where(function($query) use ($product) {
                    foreach ($product->category_ids as $categoryId) {
                        $query->orWhereJsonContains('category_ids', $categoryId);
                    }
                })
                ->limit(12)
                ->get();
        }

        // If not enough related products, get latest products
        if ($relatedProducts->count() < 6) {
            $additionalProducts = Product::where('status', 'active')
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->latest()
                ->limit(12 - $relatedProducts->count())
                ->get();
                
            $relatedProducts = $relatedProducts->merge($additionalProducts);
        }

        return view('website.products.single', compact('product', 'relatedProducts'));
    }
}