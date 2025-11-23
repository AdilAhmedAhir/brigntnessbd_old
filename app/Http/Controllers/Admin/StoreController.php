<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Setting; // <-- Add this line
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::latest()->get();
        $heroImage = Setting::get('store_locator_hero_image'); // <-- Add this line
        return view('admin.stores.index', compact('stores', 'heroImage')); // <-- Update this line
    }

    // The create(), store(), edit(), and destroy() functions remain the same...

    public function create()
    {
        return view('admin.stores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
        ]);

        Store::create($request->all());
        return redirect()->route('admin.stores.index')->with('success', 'Store created successfully.');
    }

    public function edit(Store $store)
    {
        return view('admin.stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
        ]);

        $store->update($request->all());
        return redirect()->route('admin.stores.index')->with('success', 'Store updated successfully.');
    }
    
    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()->route('admin.stores.index')->with('success', 'Store deleted successfully.');
    }

    // ADD THIS NEW FUNCTION to handle the hero image upload
    public function updateHero(Request $request)
    {
        $request->validate([
            'store_locator_hero_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('store_locator_hero_image')) {
            $file = $request->file('store_locator_hero_image');
            $imageName = 'store-hero-' . time() . '.' . $file->extension();
            
            // Move the new image to the website uploads folder
            $file->move(public_path('uploads/website'), $imageName);
            
            // Delete the old image if it exists
            $oldImage = Setting::get('store_locator_hero_image');
            if ($oldImage && file_exists(public_path('uploads/website/' . $oldImage))) {
                unlink(public_path('uploads/website/' . $oldImage));
            }

            // Save the new image name in settings
            Setting::set('store_locator_hero_image', $imageName);
        }

        return back()->with('success', 'Hero image updated successfully.');
    }
}