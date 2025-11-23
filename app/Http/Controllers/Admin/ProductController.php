<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::where('status', 'active')->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|unique:products',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'product_images' => 'nullable|array',
            'product_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'size_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'product_sizes' => 'nullable|array',
            'product_sizes.*.size_name' => 'required|string|max:50',
            'product_sizes.*.quantity' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
        ]);

        $data = $request->all();
        
        // Generate unique slug
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;
        
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $data['slug'] = $slug;
        
        // Handle category IDs
        if ($request->has('category_ids') && is_array($request->category_ids)) {
            $data['category_ids'] = array_filter($request->category_ids, function($id) {
                return !empty($id) && is_numeric($id);
            });
        } else {
            $data['category_ids'] = [];
        }
        
        // Handle product sizes with quantities
        if ($request->has('product_sizes') && is_array($request->product_sizes)) {
            $sizes = [];
            foreach ($request->product_sizes as $sizeData) {
                if (!empty($sizeData['size_name']) && isset($sizeData['quantity'])) {
                    $sizes[] = [
                        'size_name' => trim($sizeData['size_name']),
                        'quantity' => (int)$sizeData['quantity']
                    ];
                }
            }
            $data['product_size'] = $sizes;
        } else {
            $data['product_size'] = [];
        }
        
        // Handle multiple images upload
        $imageNames = [];
        
        if ($request->hasFile('product_images')) {
            $uploadPath = public_path('uploads/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            foreach ($request->file('product_images') as $image) {
                if ($image && $image->isValid()) {
                    $extension = $image->getClientOriginalExtension();
                    $imageName = time() . '_' . Str::random(10) . '.' . $extension;
                    $image->move($uploadPath, $imageName);
                    $imageNames[] = 'uploads/products/' . $imageName;
                }
            }
            
            // Store first image as main image, rest as gallery
            if (!empty($imageNames)) {
                $data['image'] = $imageNames[0];
                $data['gallery'] = array_slice($imageNames, 1);
            }
        }
        
        // Handle size image upload
        if ($request->hasFile('size_image')) {
            $uploadPath = public_path('uploads/products/sizes');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $sizeImage = $request->file('size_image');
            $sizeImageName = 'size_' . time() . '_' . Str::random(10) . '.' . $sizeImage->getClientOriginalExtension();
            $sizeImage->move($uploadPath, $sizeImageName);
            
            $data['product_meta'] = [
                'size_image' => 'uploads/products/sizes/' . $sizeImageName
            ];
        }
        
        $product = Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = \App\Models\Category::where('status', 'active')->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'product_images' => 'nullable|array',
            'product_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'deleted_images' => 'nullable|array',
            'deleted_images.*' => 'string',
            'size_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'delete_size_image' => 'nullable|boolean',
            'product_sizes' => 'nullable|array',
            'product_sizes.*.size_name' => 'required|string|max:50',
            'product_sizes.*.quantity' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
        ]);

        $data = $request->all();
        
        // Generate unique slug (exclude current product)
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;
        
        while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $data['slug'] = $slug;
        
        // Handle category IDs
        if ($request->has('category_ids') && is_array($request->category_ids)) {
            $data['category_ids'] = array_filter($request->category_ids, function($id) {
                return !empty($id) && is_numeric($id);
            });
        } else {
            $data['category_ids'] = [];
        }
        
        // Handle product sizes with quantities
        if ($request->has('product_sizes') && is_array($request->product_sizes)) {
            $sizes = [];
            foreach ($request->product_sizes as $sizeData) {
                if (!empty($sizeData['size_name']) && isset($sizeData['quantity'])) {
                    $sizes[] = [
                        'size_name' => trim($sizeData['size_name']),
                        'quantity' => (int)$sizeData['quantity']
                    ];
                }
            }
            $data['product_size'] = $sizes;
        } else {
            $data['product_size'] = [];
        }
        
        // Handle deleted images first
        $deletedImages = $request->input('deleted_images', []);
        if (!empty($deletedImages)) {
            foreach ($deletedImages as $imagePath) {
                if (file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
            }
        }

        // Collect existing images that weren't deleted
        $existingImages = [];
        if ($product->image && !in_array($product->image, $deletedImages)) {
            $existingImages[] = $product->image;
        }
        if ($product->gallery) {
            foreach ($product->gallery as $galleryImage) {
                if (!in_array($galleryImage, $deletedImages)) {
                    $existingImages[] = $galleryImage;
                }
            }
        }
        
        // Add new uploaded images
        if ($request->hasFile('product_images')) {
            $uploadPath = public_path('uploads/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            foreach ($request->file('product_images') as $image) {
                if ($image && $image->isValid()) {
                    $extension = $image->getClientOriginalExtension();
                    $imageName = time() . '_' . Str::random(10) . '.' . $extension;
                    $image->move($uploadPath, $imageName);
                    $existingImages[] = 'uploads/products/' . $imageName;
                }
            }
        }
        
        // Update image fields
        if (!empty($existingImages)) {
            $data['image'] = $existingImages[0];
            $data['gallery'] = array_slice($existingImages, 1);
        } else {
            $data['image'] = null;
            $data['gallery'] = null;
        }
        
        // Handle size image deletion
        if ($request->delete_size_image == '1') {
            // Delete existing size image file if exists
            if ($product->product_meta && isset($product->product_meta['size_image'])) {
                $oldSizeImage = public_path($product->product_meta['size_image']);
                if (file_exists($oldSizeImage)) {
                    unlink($oldSizeImage);
                }
                
                // Remove size_image from product_meta
                $productMeta = $product->product_meta;
                unset($productMeta['size_image']);
                $data['product_meta'] = $productMeta;
            }
        }
        
        // Handle size image upload
        if ($request->hasFile('size_image')) {
            // Delete old size image if exists (when uploading new one)
            if ($product->product_meta && isset($product->product_meta['size_image'])) {
                $oldSizeImage = public_path($product->product_meta['size_image']);
                if (file_exists($oldSizeImage)) {
                    unlink($oldSizeImage);
                }
            }
            
            $uploadPath = public_path('uploads/products/sizes');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $sizeImage = $request->file('size_image');
            $sizeImageName = 'size_' . time() . '_' . Str::random(10) . '.' . $sizeImage->getClientOriginalExtension();
            $sizeImage->move($uploadPath, $sizeImageName);
            
            $productMeta = $product->product_meta ?? [];
            $productMeta['size_image'] = 'uploads/products/sizes/' . $sizeImageName;
            $data['product_meta'] = $productMeta;
        }
        
        $product->update($data);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete main image file if exists
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }
        
        // Delete gallery images if exist
        if ($product->gallery) {
            foreach ($product->gallery as $galleryImage) {
                if (file_exists(public_path($galleryImage))) {
                    unlink(public_path($galleryImage));
                }
            }
        }
        
        // Delete size image if exists
        if ($product->product_meta && isset($product->product_meta['size_image'])) {
            $sizeImagePath = public_path($product->product_meta['size_image']);
            if (file_exists($sizeImagePath)) {
                unlink($sizeImagePath);
            }
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
