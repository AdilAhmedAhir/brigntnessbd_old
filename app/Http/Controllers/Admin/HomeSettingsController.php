<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'hero_video_url' => Setting::get('hero_video_url', ''),
            'shop_category_title' => Setting::get('shop_category_title', 'Shop by Category'),
            'shop_category_description' => Setting::get('shop_category_description', 'Discover our amazing product categories'),
            'shop_category_selected' => Setting::get('shop_category_selected', []),
            'category_showcase_selected' => Setting::get('category_showcase_selected', []),
        ];

        // Ensure arrays are properly formatted
        $settings['shop_category_selected'] = is_array($settings['shop_category_selected']) ? $settings['shop_category_selected'] : [];
        $settings['category_showcase_selected'] = is_array($settings['category_showcase_selected']) ? $settings['category_showcase_selected'] : [];

        $categories = Category::select('id', 'name')->get();

        return view('admin.settings.home', compact('settings', 'categories'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_video_url' => 'nullable|url',
            'shop_category_title' => 'required|string|max:255',
            'shop_category_description' => 'nullable|string|max:500',
            'shop_category_selected' => 'nullable|array|max:3',
            'shop_category_selected.*' => 'exists:categories,id',
            'category_showcase_selected' => 'nullable|array',
            'category_showcase_selected.*' => 'exists:categories,id',
        ]);

        // Get category arrays and ensure they are integers
        $shopCategories = collect($request->shop_category_selected ?? [])->map(fn($id) => (int)$id)->values()->toArray();
        $showcaseCategories = collect($request->category_showcase_selected ?? [])->map(fn($id) => (int)$id)->values()->toArray();

        // Save settings
        Setting::set('hero_video_url', $request->hero_video_url ?? '');
        Setting::set('shop_category_title', $request->shop_category_title);
        Setting::set('shop_category_description', $request->shop_category_description ?? '');
        Setting::set('shop_category_selected', $shopCategories, 'json');
        Setting::set('category_showcase_selected', $showcaseCategories, 'json');

        return redirect()->back()->with('success', 'Home page settings updated successfully!');
    }
}
