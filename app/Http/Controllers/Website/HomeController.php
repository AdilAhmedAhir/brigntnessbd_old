<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Store;

class HomeController extends Controller
{
    public function index()
    {
        // Get home page settings
        $heroVideoUrl = Setting::get('hero_video_url', '');
        $shopCategoryTitle = Setting::get('shop_category_title', 'Shop by Category');
        $shopCategoryDescription = Setting::get('shop_category_description', 'Explore our curated collections designed to elevate your style for every occasion');
        $shopCategorySelected = Setting::get('shop_category_selected', []);
        $categoryShowcaseSelected = Setting::get('category_showcase_selected', []);
        
        // Get shop by category data (max 3 categories)
        $shopCategories = collect();
        if (!empty($shopCategorySelected)) {
            $shopCategories = Category::whereIn('id', $shopCategorySelected)
                ->where('status', 'active')
                ->orderByRaw("FIELD(id, " . implode(',', array_map('intval', $shopCategorySelected)) . ")")
                ->get();
        }

        // Get category showcase data with products
        $categoryShowcase = collect();
        if (!empty($categoryShowcaseSelected)) {
            $categoryShowcase = Category::whereIn('id', $categoryShowcaseSelected)
                ->where('status', 'active')
                ->orderByRaw("FIELD(id, " . implode(',', array_map('intval', $categoryShowcaseSelected)) . ")")
                ->get()
                ->map(function ($category) {
                    $products = Product::where('status', 'active')
                        ->where(function($query) use ($category) {
                            $query->whereJsonContains('category_ids', $category->id)
                                  ->orWhereJsonContains('category_ids', (string)$category->id);
                        })
                        ->limit(12)
                        ->get();
                    
                    $category->showcase_products = $products;
                    return $category;
                });
        }

        return view('website.home', compact(
            'heroVideoUrl',
            'shopCategoryTitle', 
            'shopCategoryDescription',
            'shopCategories', 
            'categoryShowcase'
        ));
    }

    /**
     * Display the store locator page.
     */
    public function storeLocator()
    {
        $stores = Store::all()->groupBy('division');
        $heroImage = Setting::get('store_locator_hero_image'); 
        
        return view('website.store-locator', compact('stores', 'heroImage'));
    }
}