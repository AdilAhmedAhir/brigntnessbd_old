@extends('admin.layouts.app')

@section('title', isset($product) ? 'Edit Product' : 'Add Product')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h1>
        <div class="d-flex" style="gap: 8px;">
            @if(isset($product) && $product->slug)
                <a href="{{ route('products.show', $product->slug) }}" target="_blank" 
                   class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                    <i class="fas fa-eye fa-sm text-white-50"></i> View Product
                </a>
            @endif
            <a href="{{ route('admin.products.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Products
            </a>
        </div>
    </div>

    <!-- Product Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Product Information</h6>
                </div>
                <div class="card-body">
                    <form id="product-form" 
                        action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" 
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($product))
                            @method('PUT')
                        @endif
                        
                        <!-- Product Name -->
                        <div class="form-group">
                            <label for="name">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" name="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Price and Sale Price -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Regular Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">৳</span>
                                        </div>
                                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                            id="price" name="price" value="{{ old('price', $product->price ?? '') }}" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sale_price">Sale Price</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">৳</span>
                                        </div>
                                        <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" 
                                            id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? '') }}">
                                        @error('sale_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Leave empty if no discount</small>
                                </div>
                            </div>
                        </div>

                        <!-- SKU and Stock -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sku">SKU <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                        id="sku" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Unique product identifier</small>
                                </div>
                            </div>
                            <div class="col-md-6 d-none">
                                <div class="form-group">
                                    <label for="stock_quantity">Stock Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                        id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Weight -->
                            <div class="col-md-6 d-none">
                                <div class="form-group">
                                    <label for="weight">Weight (kg)</label>
                                    <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror" 
                                        id="weight" name="weight" value="{{ old('weight', $product->weight ?? '') }}">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
    
                            <!-- Category -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Category</label>
                                    <input type="text" class="form-control @error('category_id') is-invalid @enderror" 
                                           id="category_id" name="category_display" 
                                           value="{{ old('category_display', isset($product->category_ids) && is_array($product->category_ids) ? implode(', ', collect($categories ?? [])->whereIn('id', $product->category_ids)->pluck('name')->toArray()) : '') }}" 
                                           placeholder="Select categories...">
                                    <!-- Hidden input to store selected category IDs -->
                                    <input type="hidden" id="category_ids_hidden" name="category_ids" 
                                           value="{{ old('category_ids', isset($product->category_ids) && is_array($product->category_ids) ? implode(',', $product->category_ids) : '') }}">
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('category_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Select one or more categories for this product</small>
                                </div>
                            </div>
                        </div>



                        <!-- Product Sizes with Quantity -->
                        <div class="form-group">
                            <div class="d-flex align-items-center mb-2">
                                <label for="product_sizes" class="mb-0 mr-3">Product Sizes</label>
                                <button type="button" id="add-size-btn" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Add Size
                                </button>
                            </div>
                            
                            <div id="product-sizes-container">
                                <!-- Size rows will be added here dynamically -->
                            </div>
                            
                            @error('product_sizes')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                            @error('product_sizes.*')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                            @error('product_sizes.*.size_name')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                            @error('product_sizes.*.quantity')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                            
                            <small class="form-text text-muted">Add product sizes with their available quantities</small>
                        </div>

                        
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Settings -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Product Settings</h6>
                </div>
                <div class="card-body">
                    <!-- Status -->
                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required form="product-form">
                            <option value="active" {{ old('status', $product->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $product->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Featured -->
                    <div class="form-group d-none">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="featured" name="featured" value="1" 
                                form="product-form" {{ old('featured', $product->featured ?? false) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="featured">Featured Product</label>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="form-group text-center mt-4">
                        <button type="submit" form="product-form" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> {{ isset($product) ? 'Update Product' : 'Save Product' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Images -->
            <div class="card shadow mb-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Product Images</h6>
                </div>
                <div class="card-body">
                    <!-- Main file input (hidden) -->
                    <input type="file" id="product-images" name="product_images[]" multiple accept="image/*" form="product-form" style="display: none;">
                    
                    @error('product_images')
                        <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror
                    @error('product_images.*')
                        <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror
                    
                    <!-- Image Cards Container -->
                    <div id="image-cards-container" class="row">
                        <!-- Existing Images (for edit mode) -->
                        @if(isset($product) && $product->image)
                            <div class="col-md-6 col-lg-4 mb-3" data-type="existing" data-path="{{ $product->image }}" data-index="0">
                                <div class="card border-primary position-relative">
                                    <img src="{{ asset($product->image) }}" class="card-img-top" style="height: 120px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <span class="badge badge-primary">Main Image</span>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm position-absolute delete-image-btn" 
                                            style="top: 5px; right: 5px; width: 25px; height: 25px; padding: 0; border-radius: 50%;">
                                        <i class="fas fa-times" style="font-size: 12px;"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                        
                        @if(isset($product) && $product->gallery && is_array($product->gallery))
                            @foreach($product->gallery as $index => $galleryImage)
                                <div class="col-md-6 col-lg-4 mb-3" data-type="existing" data-path="{{ $galleryImage }}" data-index="{{ $index + 1 }}">
                                    <div class="card border-secondary position-relative">
                                        <img src="{{ asset($galleryImage) }}" class="card-img-top" style="height: 120px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <span class="badge badge-secondary">Gallery {{ $index + 1 }}</span>
                                        </div>
                                        <button type="button" class="btn btn-danger btn-sm position-absolute delete-image-btn" 
                                                style="top: 5px; right: 5px; width: 25px; height: 25px; padding: 0; border-radius: 50%;">
                                            <i class="fas fa-times" style="font-size: 12px;"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        
                        <!-- Add New Image Card -->
                        <div class="col-md-6 col-lg-4 mb-3" id="add-image-card">
                            <div class="card border-dashed border-success position-relative add-image-trigger" 
                                 style="border: 2px dashed #28a745; cursor: pointer; min-height: 160px;">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center text-success">
                                    <i class="fas fa-plus fa-2x mb-2"></i>
                                    <span class="font-weight-bold">Add Images</span>
                                    <small class="text-muted mt-1">Click to upload</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <small class="form-text text-muted">
                        Supported formats: JPG, PNG, GIF (Max: 2MB each). First image will be the main product image.
                    </small>
                    
                    <!-- Hidden inputs for tracking deleted images -->
                    <div id="deleted-images-container"></div>
                </div>
            </div>

            <!-- Size Image -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Size Chart Image</h6>
                </div>
                <div class="card-body">
                    @if(isset($product) && $product->product_meta && is_array($product->product_meta) && isset($product->product_meta['size_image']))
                        <div class="mb-3" id="current-size-image">
                            <h6 class="text-muted mb-2">Current Size Chart:</h6>
                            <div class="text-center position-relative d-inline-block">
                                <img src="{{ asset($product->product_meta['size_image']) }}" alt="Current Size Chart" 
                                    class="img-thumbnail" style="max-width: 150px; max-height: 100px;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute" 
                                        id="delete-size-image-btn"
                                        style="top: 5px; right: 5px; width: 25px; height: 25px; padding: 0; border-radius: 50%;"
                                        title="Delete Size Chart">
                                    <i class="fas fa-times" style="font-size: 12px;"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    <div class="form-group">
                        <label for="size_image">
                            {{ isset($product) ? 'Upload New Size Chart' : 'Upload Size Chart' }}
                        </label>
                        <input type="file" class="form-control-file @error('size_image') is-invalid @enderror" 
                            id="size_image" name="size_image" accept="image/*" form="product-form">
                        @error('size_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            {{ isset($product) ? 'Upload a new size chart image to replace the current one' : 'Upload a size chart image to help customers choose the right size' }}
                        </small>
                    </div>
                    
                    <!-- Size Image Preview -->
                    <div id="size-image-preview" class="mt-3" style="display: none;">
                        <h6 class="text-muted mb-2">{{ isset($product) ? 'New Size Chart Preview:' : 'Size Chart Preview:' }}</h6>
                        <div class="text-center">
                            <img id="size-preview-img" src="" alt="Size Chart Preview" 
                                class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                        </div>
                    </div>
                    
                    <!-- Hidden input for tracking size image deletion -->
                    <input type="hidden" id="delete_size_image" name="delete_size_image" value="0" form="product-form">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- Tagify CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />

    <style>
    /* Custom styles for better appearance */
    .tagify {
        border: 1px solid #d1d3e2 !important;
        border-radius: 0.375rem !important;
    }

    .tagify__input {
        margin: 5px !important;
    }

    .tagify__tag {
        background: linear-gradient(45deg, #3498db, #2c3e50) !important;
        border-color: #2c3e50 !important;
        color: white !important;
        border-radius: 4px !important;
    }
    
    .tagify__tag>div{
        padding: 0 5px;
        color: white !important;
    }

    .tagify__tag__removeBtn {
        color: white !important;
    }

    /* Category-specific styles */
    #category_id + .tagify .tagify__tag {
        background: linear-gradient(45deg, #28a745, #1e7e34) !important;
        border-color: #1e7e34 !important;
    }

    /* Error state for tagify */
    .tagify.is-invalid {
        border-color: #dc3545 !important;
    }
    
    .tagify__tag>div::before{
        display: none;
    }

    /* Image card styles */
    .add-image-card {
        transition: all 0.3s ease;
    }

    .add-image-card:hover {
        background-color: #f8f9fa;
        border-color: #1e7e34 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .border-dashed {
        border-style: dashed !important;
    }

    .delete-image-btn {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .card:hover .delete-image-btn {
        opacity: 1;
    }

    .delete-image-btn:hover {
        transform: scale(1.1);
    }

    /* Size image delete button styles */
    #delete-size-image-btn {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    #current-size-image:hover #delete-size-image-btn {
        opacity: 1;
    }

    #delete-size-image-btn:hover {
        transform: scale(1.1);
    }

    .image-card-preview {
        position: relative;
        overflow: hidden;
    }

    .image-card-preview img {
        transition: transform 0.3s ease;
    }

    .image-card-preview:hover img {
        transform: scale(1.05);
    }
    </style>
@endpush

@push('scripts')
    <!-- Tagify JS -->
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.min.js"></script>

    {{-- Tagify Script --}}
    <script>
        $(document).ready(function() {
            // Categories data from Laravel
            var categories = [
                @foreach($categories ?? [] as $category)
                    {
                        id: {{ $category->id }},
                        value: "{{ $category->name }}",
                        name: "{{ $category->name }}"
                    },
                @endforeach
            ];
            
            // Initialize Tagify for categories
            var categoryInput = document.querySelector('#category_id');
            if (categoryInput) {
                var categoryTagify = new Tagify(categoryInput, {
                    placeholder: 'Select categories...',
                    whitelist: categories.map(cat => cat.value),
                    enforceWhitelist: true, // Only allow items from whitelist
                    dropdown: {
                        maxItems: 20,
                        classname: 'tags-look',
                        enabled: 0,
                        closeOnSelect: false,
                        searchKeys: ['value']
                    },
                    editTags: false, // Don't allow editing of tags
                    maxTags: 10,
                    duplicates: false
                });
                
                // Handle tag addition - store category IDs
                categoryTagify.on('add', function(e) {
                    updateCategoryIds();
                });
                
                // Handle tag removal - update category IDs
                categoryTagify.on('remove', function(e) {
                    updateCategoryIds();
                });
                
                // Function to update hidden input with category IDs
                function updateCategoryIds() {
                    var selectedTags = categoryTagify.value;
                    var categoryIds = selectedTags.map(function(tag) {
                        var category = categories.find(cat => cat.value === tag.value);
                        return category ? category.id : null;
                    }).filter(id => id !== null);
                    
                    // Update hidden input
                    var hiddenInput = document.getElementById('category_ids_hidden');
                    hiddenInput.value = categoryIds.join(',');
                    
                    // Remove existing hidden inputs and create new ones
                    var existingHiddenInputs = document.querySelectorAll('input[name="category_ids[]"]:not(#category_ids_hidden)');
                    existingHiddenInputs.forEach(input => input.remove());
                    
                    categoryIds.forEach(function(id) {
                        var hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'category_ids[]';
                        hiddenInput.value = id;
                        categoryInput.parentNode.appendChild(hiddenInput);
                    });
                }
                
                // Initialize with existing values if editing
                @if(isset($product->category_ids) && is_array($product->category_ids))
                    var existingCategories = [
                        @foreach($product->category_ids as $categoryId)
                            @php
                                $category = collect($categories ?? [])->firstWhere('id', $categoryId);
                            @endphp
                            @if($category)
                                "{{ $category->name }}",
                            @endif
                        @endforeach
                    ];
                    categoryTagify.addTags(existingCategories);
                @endif
            }
            
            
        });
    </script>


    {{-- Image Upload Script --}}
    <script>
        $(document).ready(function() {
            let deletedImages = [];
            let selectedFiles = [];
            let maxFileSize = 2 * 1024 * 1024; // 2MB in bytes
            let allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            
            // Handle add image click
            $(document).on('click', '.add-image-trigger', function() {
                $('#product-images').click();
            });
            
            // Handle file selection
            $('#product-images').on('change', function(e) {
                const files = Array.from(e.target.files);
                
                if (files.length === 0) return;
                
                // Validate files
                let validFiles = [];
                let errors = [];
                
                files.forEach((file, index) => {
                    // Check file type
                    if (!allowedTypes.includes(file.type)) {
                        errors.push(`File "${file.name}" is not a valid image format.`);
                        return;
                    }
                    
                    // Check file size
                    if (file.size > maxFileSize) {
                        errors.push(`File "${file.name}" is too large. Maximum size is 2MB.`);
                        return;
                    }
                    
                    validFiles.push(file);
                });
                
                // Show errors if any
                if (errors.length > 0) {
                    alert('File validation errors:\n' + errors.join('\n'));
                }
                
                // Process valid files
                if (validFiles.length > 0) {
                    selectedFiles = validFiles;
                    displayImagePreviews(validFiles);
                }
            });
            
            // Display image previews
            function displayImagePreviews(files) {
                const container = $('#image-cards-container');
                const addCard = $('#add-image-card');
                
                // Remove existing new image previews
                container.find('[data-type="new"]').remove();
                
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const currentImages = container.find('[data-type="existing"], [data-type="new"]').length;
                        const isFirst = currentImages === 0;
                        const badgeClass = isFirst ? 'badge-primary' : 'badge-secondary';
                        const badgeText = isFirst ? 'Main Image' : `Gallery ${currentImages}`;
                        
                        const imageHtml = `
                            <div class="col-md-6 col-lg-4 mb-3" data-type="new" data-file-index="${index}">
                                <div class="card border-success position-relative image-card-preview">
                                    <img src="${e.target.result}" class="card-img-top" style="height: 120px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <span class="badge ${badgeClass}">${badgeText}</span>
                                        <small class="text-muted d-block" title="${file.name}">${truncateFileName(file.name, 15)}</small>
                                        <small class="text-info d-block">${formatFileSize(file.size)}</small>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm position-absolute delete-image-btn" 
                                            style="top: 5px; right: 5px; width: 25px; height: 25px; padding: 0; border-radius: 50%;"
                                            data-type="new" data-index="${index}">
                                        <i class="fas fa-times" style="font-size: 12px;"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        
                        addCard.before(imageHtml);
                        updateImageBadges();
                    };
                    reader.readAsDataURL(file);
                });
            }
            
            // Handle delete image button
            $(document).on('click', '.delete-image-btn', function(e) {
                e.stopPropagation();
                e.preventDefault();
                
                const button = $(this);
                const imageCard = button.closest('[data-type]');
                const imageType = imageCard.data('type');
                
                if (imageType === 'existing') {
                    const imagePath = imageCard.data('path');
                    deletedImages.push(imagePath);
                    updateDeletedImagesInput();
                } else if (imageType === 'new') {
                    const fileIndex = parseInt(button.data('index'));
                    // Remove file from selectedFiles array
                    selectedFiles.splice(fileIndex, 1);
                    // Update file input with remaining files
                    updateFileInput();
                    // Remove all new previews and redisplay
                    $('#image-cards-container [data-type="new"]').remove();
                    if (selectedFiles.length > 0) {
                        displayImagePreviews(selectedFiles);
                    }
                }
                
                imageCard.remove();
                updateImageBadges();
            });
            
            // Update file input with current selectedFiles
            function updateFileInput() {
                const dt = new DataTransfer();
                selectedFiles.forEach(file => {
                    dt.items.add(file);
                });
                $('#product-images')[0].files = dt.files;
            }
            
            // Update image badges
            function updateImageBadges() {
                const imageCards = $('#image-cards-container [data-type="existing"], #image-cards-container [data-type="new"]');
                imageCards.each(function(index) {
                    const badge = $(this).find('.badge');
                    if (index === 0) {
                        badge.removeClass('badge-secondary').addClass('badge-primary').text('Main Image');
                    } else {
                        badge.removeClass('badge-primary').addClass('badge-secondary').text(`Gallery ${index}`);
                    }
                });
            }
            
            // Update deleted images hidden inputs
            function updateDeletedImagesInput() {
                $('#deleted-images-container').empty();
                deletedImages.forEach(function(imagePath) {
                    $('#deleted-images-container').append(
                        `<input type="hidden" name="deleted_images[]" value="${imagePath}" form="product-form">`
                    );
                });
            }
            
            // Utility functions
            function truncateFileName(fileName, maxLength) {
                if (fileName.length <= maxLength) return fileName;
                const extension = fileName.split('.').pop();
                const nameWithoutExt = fileName.substring(0, fileName.lastIndexOf('.'));
                const truncatedName = nameWithoutExt.substring(0, maxLength - extension.length - 4) + '...';
                return truncatedName + '.' + extension;
            }
            
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
            
            // Form validation
            $('#product-form').on('submit', function(e) {
                var isValid = true;
                var errors = [];
                
                // Validate required fields
                if (!$('#name').val().trim()) {
                    errors.push('Product name is required');
                    isValid = false;
                }
                
                if (!$('#price').val() || parseFloat($('#price').val()) <= 0) {
                    errors.push('Valid price is required');
                    isValid = false;
                }
                
                if (!$('#sku').val().trim()) {
                    errors.push('SKU is required');
                    isValid = false;
                }
                
                // Validate sale price is less than regular price
                var price = parseFloat($('#price').val());
                var salePrice = parseFloat($('#sale_price').val());
                if (salePrice && salePrice >= price) {
                    errors.push('Sale price must be less than regular price');
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Please fix the following errors:\n- ' + errors.join('\n- '));
                    return false;
                }
                
                // Show loading state
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
                
                // Re-enable button after a delay (in case of errors)
                setTimeout(() => {
                    submitBtn.html(originalText).prop('disabled', false);
                }, 10000);
            });
        });
    </script>


    {{-- Size image --}}
    <script>
        // Size image preview functionality
        $(document).ready(function() {
            // Handle size image file change
            $('#size_image').on('change', function() {
                const file = this.files[0];
                const sizePreview = $('#size-image-preview');
                const previewImg = $('#size-preview-img');
                
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.attr('src', e.target.result);
                        sizePreview.show();
                        // Reset delete flag when new image is selected
                        $('#delete_size_image').val('0');
                    };
                    reader.readAsDataURL(file);
                } else {
                    sizePreview.hide();
                }
            });
            
            // Handle delete size image button
            $('#delete-size-image-btn').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (confirm('Are you sure you want to delete the current size chart image?')) {
                    // Hide the current size image container
                    $('#current-size-image').hide();
                    
                    // Set delete flag
                    $('#delete_size_image').val('1');
                    
                    // Clear file input
                    $('#size_image').val('');
                    
                    // Hide preview if visible
                    $('#size-image-preview').hide();
                    
                    // Update label text
                    $('label[for="size_image"]').text('Upload Size Chart');
                    
                    // Update help text
                    $('.form-text.text-muted').text('Upload a size chart image to help customers choose the right size');
                }
            });
        });
    </script>


    {{-- Product Size with Quantity --}}
    <script>
        $(document).ready(function() {
            let sizeIndex = 0;
            
            // Common size options for suggestions
            const sizeOptions = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'Small', 'Medium', 'Large', 'Extra Large', '28', '30', '32', '34', '36', '38', '40', '42'];
            
            // Add new size row
            function addSizeRow(sizeName = '', quantity = 0) {
                const row = `
                    <div class="row align-items-center mb-2 size-row" data-index="${sizeIndex}">
                        <div class="col-md-5">
                            <input type="text" name="product_sizes[${sizeIndex}][size_name]" 
                                   class="form-control size-name-input" 
                                   placeholder="Size name (e.g., M, Large, 32)" 
                                   value="${sizeName}" 
                                   list="size-options" required>
                        </div>
                        <div class="col-md-5">
                            <input type="number" name="product_sizes[${sizeIndex}][quantity]" 
                                   class="form-control quantity-input" 
                                   placeholder="Quantity" 
                                   value="${quantity}" 
                                   min="0" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-size-btn" title="Remove Size">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                
                $('#product-sizes-container').append(row);
                sizeIndex++;
            }
            
            // Add size button click
            $('#add-size-btn').on('click', function() {
                addSizeRow();
            });
            
            // Remove size row
            $(document).on('click', '.remove-size-btn', function() {
                $(this).closest('.size-row').remove();
                updateIndices();
            });
            
            // Update indices after removal
            function updateIndices() {
                $('.size-row').each(function(index) {
                    $(this).attr('data-index', index);
                    $(this).find('.size-name-input').attr('name', `product_sizes[${index}][size_name]`);
                    $(this).find('.quantity-input').attr('name', `product_sizes[${index}][quantity]`);
                });
                sizeIndex = $('.size-row').length;
            }
            
            // Create datalist for size suggestions
            const datalist = $('<datalist id="size-options"></datalist>');
            sizeOptions.forEach(size => {
                datalist.append(`<option value="${size}">`);
            });
            $('body').append(datalist);
            
            // Initialize with existing data if editing
            @if(isset($product->product_size) && is_array($product->product_size))
                @foreach($product->product_size as $size)
                    @if(is_array($size) && isset($size['size_name']))
                        addSizeRow('{{ $size['size_name'] ?? '' }}', {{ $size['quantity'] ?? 0 }});
                    @elseif(is_string($size))
                        addSizeRow('{{ $size }}', 0);
                    @endif
                @endforeach
            @endif
            
            // If no sizes exist, add one empty row
            if ($('.size-row').length === 0) {
                addSizeRow();
            }
            
            // Form validation
            $('#product-form').on('submit', function(e) {
                let hasValidSizes = false;
                let hasErrors = false;
                let sizeNames = [];
                let hasDuplicates = false;
                
                // Check for duplicate size names and validation errors
                $('.size-row').each(function() {
                    const sizeName = $(this).find('.size-name-input').val().trim().toLowerCase();
                    const quantity = $(this).find('.quantity-input').val();
                    
                    if (sizeName && quantity >= 0) {
                        hasValidSizes = true;
                        // Check for duplicates
                        if (sizeNames.includes(sizeName)) {
                            hasDuplicates = true;
                        } else {
                            sizeNames.push(sizeName);
                        }
                    } else if (sizeName || quantity) {
                        hasErrors = true;
                    }
                });
                
                if (hasDuplicates) {
                    e.preventDefault();
                    // Find and focus on the first duplicate input
                    $('.size-name-input.is-invalid').first().focus();
                    return false;
                }
                
                if (hasErrors) {
                    e.preventDefault();
                    alert('Please complete all size fields or remove empty rows.');
                    return false;
                }
                
                // Remove empty rows before submission
                $('.size-row').each(function() {
                    const sizeName = $(this).find('.size-name-input').val().trim();
                    const quantity = $(this).find('.quantity-input').val();
                    
                    if (!sizeName && !quantity) {
                        $(this).remove();
                    }
                });
                
                updateIndices();
            });
            
            // Prevent duplicate size names
            $(document).on('blur', '.size-name-input', function() {
                const currentValue = $(this).val().trim().toLowerCase();
                const currentRow = $(this).closest('.size-row');
                let isDuplicate = false;
                
                // Only check for duplicates if the current value is not empty
                if (currentValue !== '') {
                    $('.size-name-input').not(this).each(function() {
                        const otherValue = $(this).val().trim().toLowerCase();
                        if (otherValue === currentValue) {
                            isDuplicate = true;
                            return false;
                        }
                    });
                    
                    if (isDuplicate) {
                        $(this).val(''); // Clear the input
                        $(this).focus(); // Focus back to the input
                    }
                }
            });
            
            // Real-time duplicate checking as user types
            $(document).on('input', '.size-name-input', function() {
                const currentValue = $(this).val().trim().toLowerCase();
                const currentInput = $(this);
                let isDuplicate = false;
                
                // Only check for duplicates if the current value is not empty
                if (currentValue !== '') {
                    $('.size-name-input').not(this).each(function() {
                        const otherValue = $(this).val().trim().toLowerCase();
                        if (otherValue === currentValue) {
                            isDuplicate = true;
                            return false;
                        }
                    });
                    
                    // Add visual feedback
                    if (isDuplicate) {
                        currentInput.addClass('is-invalid');
                        // Remove existing error message
                        currentInput.siblings('.duplicate-error').remove();
                        // Add error message
                        currentInput.after('<small class="duplicate-error text-danger">This size name already exists</small>');
                    } else {
                        currentInput.removeClass('is-invalid');
                        currentInput.siblings('.duplicate-error').remove();
                    }
                } else {
                    // Clear validation state when input is empty
                    currentInput.removeClass('is-invalid');
                    currentInput.siblings('.duplicate-error').remove();
                }
            });
        });
    </script>

@endpush
