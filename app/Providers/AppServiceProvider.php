<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share header categories with all views
        view()->composer('*', function ($view) {
            $headerCategories = \App\Models\Category::where('status', 'active')
                ->where('show_on_header', true)
                ->with(['children' => function($query) {
                    $query->where('status', 'active')
                          ->where('show_on_header', true)
                          ->orderBy('sort_order')
                          ->orderBy('name');
                }])
                ->whereNull('parent_id') // Only get parent categories
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
                
            // Share site settings with all views
            $siteSettings = [
                'site_name' => \App\Models\Setting::get('site_name', 'Brightness Fashion'),
                'site_description' => \App\Models\Setting::get('site_description', 'Luxury Fashion'),
                'site_icon' => \App\Models\Setting::get('site_icon', ''),
                'site_icon_url' => \App\Models\Setting::getFileUrl('site_icon', 'website', ''),
                // Footer Settings
                'footer_description' => \App\Models\Setting::get('footer_description', 'Brightness Fashion represents the pinnacle of luxury fashion, offering exclusive collections that embody timeless elegance and sophisticated style.'),
                'footer_facebook' => \App\Models\Setting::get('footer_facebook', ''),
                'footer_instagram' => \App\Models\Setting::get('footer_instagram', ''),
                'footer_twitter' => \App\Models\Setting::get('footer_twitter', ''),
                'footer_pinterest' => \App\Models\Setting::get('footer_pinterest', ''),
                'footer_address' => \App\Models\Setting::get('footer_address', ''),
                'footer_phone' => \App\Models\Setting::get('footer_phone', ''),
                'footer_email' => \App\Models\Setting::get('footer_email', ''),
                'footer_hours' => \App\Models\Setting::get('footer_hours', ''),
                'footer_copyright' => \App\Models\Setting::get('footer_copyright', '2025 Brightness Fashion. All rights reserved.'),
            ];
                
            $view->with([
                'headerCategories' => $headerCategories,
                'siteSettings' => $siteSettings
            ]);
        });
    }
}
