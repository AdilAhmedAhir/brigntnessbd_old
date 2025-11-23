<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'Brightness Fashion'),
            'site_description' => Setting::get('site_description', 'Your premium fashion destination'),
            'site_icon' => Setting::get('site_icon', ''),
            // Footer Settings
            'footer_description' => Setting::get('footer_description', ''),
            'footer_facebook' => Setting::get('footer_facebook', ''),
            'footer_instagram' => Setting::get('footer_instagram', ''),
            'footer_twitter' => Setting::get('footer_twitter', ''),
            'footer_pinterest' => Setting::get('footer_pinterest', ''),
            'footer_address' => Setting::get('footer_address', ''),
            'footer_phone' => Setting::get('footer_phone', ''),
            'footer_email' => Setting::get('footer_email', ''),
            'footer_hours' => Setting::get('footer_hours', ''),
            'footer_copyright' => Setting::get('footer_copyright', ''),
            // Contact Settings (unique settings only)
            'contact_map_url' => Setting::get('contact_map_url', ''),
            'contact_description' => Setting::get('contact_description', ''),
            'contact_admin_email' => Setting::get('contact_admin_email', ''),
        ];

        return view('admin.settings.site', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string|max:1000',
            'site_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico,webp|max:2048',
            // Footer validation
            'footer_description' => 'required|string|max:1000',
            'footer_facebook' => 'nullable|url|max:255',
            'footer_instagram' => 'nullable|url|max:255',
            'footer_twitter' => 'nullable|url|max:255',
            'footer_pinterest' => 'nullable|url|max:255',
            'footer_address' => 'required|string|max:500',
            'footer_phone' => 'required|string|max:50',
            'footer_email' => 'required|email|max:255',
            'footer_hours' => 'required|string|max:255',
            'footer_copyright' => 'required|string|max:255',
            // Contact validation (unique settings only)
            'contact_map_url' => 'nullable|url|max:1000',
            'contact_description' => 'nullable|string|max:1000',
            'contact_admin_email' => 'nullable|email|max:255',
        ]);

        // Update site settings
        Setting::set('site_name', $request->site_name);
        Setting::set('site_description', $request->site_description);

        // Update footer settings
        Setting::set('footer_description', $request->footer_description);
        Setting::set('footer_facebook', $request->footer_facebook ?? '');
        Setting::set('footer_instagram', $request->footer_instagram ?? '');
        Setting::set('footer_twitter', $request->footer_twitter ?? '');
        Setting::set('footer_pinterest', $request->footer_pinterest ?? '');
        Setting::set('footer_address', $request->footer_address);
        Setting::set('footer_phone', $request->footer_phone);
        Setting::set('footer_email', $request->footer_email);
        Setting::set('footer_hours', $request->footer_hours);
        Setting::set('footer_copyright', $request->footer_copyright);

        // Update contact settings (unique settings only)
        Setting::set('contact_map_url', $request->contact_map_url ?? '');
        Setting::set('contact_description', $request->contact_description ?? '');
        Setting::set('contact_admin_email', $request->contact_admin_email ?? '');

        // Handle site icon upload
        if ($request->hasFile('site_icon')) {
            $file = $request->file('site_icon');
            $filename = 'site-icon.' . $file->getClientOriginalExtension();
            
            // Delete old icon if exists
            $oldIcon = Setting::get('site_icon');
            if ($oldIcon && file_exists(public_path('uploads/website/' . $oldIcon))) {
                unlink(public_path('uploads/website/' . $oldIcon));
            }

            // Store new icon
            $file->move(public_path('uploads/website'), $filename);
            Setting::set('site_icon', $filename);
        }

        return redirect()->route('admin.settings.site')
            ->with('success', 'Site settings updated successfully!');
    }
}
