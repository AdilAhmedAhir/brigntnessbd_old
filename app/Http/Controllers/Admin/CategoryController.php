<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('parent')->orderBy('name')->get();
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    /**
     * Display the specified resource for AJAX requests.
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'mobile_cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'show_on_header' => 'boolean',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $data = $request->all();
            $data['slug'] = Category::generateUniqueSlug($request->name);
            $data['show_on_header'] = $request->has('show_on_header');
            
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $image = $request->file('cover_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                
                // Create uploads/categories directory if it doesn't exist
                $uploadPath = public_path('uploads/categories');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Move image to uploads/categories folder
                $image->move($uploadPath, $imageName);
                $data['cover_image'] = 'uploads/categories/' . $imageName;
            }

            // Handle mobile cover image upload
            if ($request->hasFile('mobile_cover_image')) {
                $image = $request->file('mobile_cover_image');
                $imageName = time() . '_mobile_' . $image->getClientOriginalName();
                
                // Create uploads/categories directory if it doesn't exist
                $uploadPath = public_path('uploads/categories');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Move image to uploads/categories folder
                $image->move($uploadPath, $imageName);
                $data['mobile_cover_image'] = 'uploads/categories/' . $imageName;
            }

            Category::create($data);

            return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors gracefully
            if ($e->getCode() == 23000) { // Integrity constraint violation
                return redirect()->back()->withInput()->with('error', 'A category with this name or similar name already exists. Please choose a different name.');
            }
            
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the category. Please try again.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id|not_in:' . $id, // Can't be parent of itself
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'mobile_cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'show_on_header' => 'boolean',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $data = $request->all();
            $data['slug'] = Category::generateUniqueSlug($request->name, $id);
            $data['show_on_header'] = $request->has('show_on_header');
            
            if ($request->hasFile('cover_image')) {
                // Delete old image if exists
                if ($category->cover_image && file_exists(public_path($category->cover_image))) {
                    unlink(public_path($category->cover_image));
                }
                
                $image = $request->file('cover_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                
                // Create uploads/categories directory if it doesn't exist
                $uploadPath = public_path('uploads/categories');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Move image to uploads/categories folder
                $image->move($uploadPath, $imageName);
                $data['cover_image'] = 'uploads/categories/' . $imageName;
            }

            if ($request->hasFile('mobile_cover_image')) {
                // Delete old mobile image if exists
                if ($category->mobile_cover_image && file_exists(public_path($category->mobile_cover_image))) {
                    unlink(public_path($category->mobile_cover_image));
                }
                
                $image = $request->file('mobile_cover_image');
                $imageName = time() . '_mobile_' . $image->getClientOriginalName();
                
                // Create uploads/categories directory if it doesn't exist
                $uploadPath = public_path('uploads/categories');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Move image to uploads/categories folder
                $image->move($uploadPath, $imageName);
                $data['mobile_cover_image'] = 'uploads/categories/' . $imageName;
            }

            $category->update($data);

            return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors gracefully
            if ($e->getCode() == 23000) { // Integrity constraint violation
                return redirect()->back()->withInput()->with('error', 'A category with this name or similar name already exists. Please choose a different name.');
            }
            
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the category. Please try again.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Cannot delete category with existing products!');
        }

        // Check if category has child categories
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Cannot delete category with child categories!');
        }

        // Delete cover image file if exists
        if ($category->cover_image && file_exists(public_path($category->cover_image))) {
            unlink(public_path($category->cover_image));
        }

        // Delete mobile cover image file if exists
        if ($category->mobile_cover_image && file_exists(public_path($category->mobile_cover_image))) {
            unlink(public_path($category->mobile_cover_image));
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
    }
}
