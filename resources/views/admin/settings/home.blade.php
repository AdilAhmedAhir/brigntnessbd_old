@extends('admin.layouts.app')

@section('title', 'Home Page Settings')


@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Home Page Settings</h1>
    </div>

    <form action="{{ route('admin.edit_home.update') }}" method="POST">
        @csrf
        
        <!-- Hero Section Settings -->
        <div class="setting-card">
            <div class="setting-card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-video mr-2"></i>Hero Section Settings
                </h6>
            </div>
            <div class="setting-card-body">
                <div class="form-group">
                    <label for="hero_video_url">Video URL</label>
                    <input type="url" 
                        class="form-control @error('hero_video_url') is-invalid @enderror" 
                        id="hero_video_url" 
                        name="hero_video_url" 
                        value="{{ old('hero_video_url', $settings['hero_video_url']) }}"
                        placeholder="https://www.youtube.com/watch?v=example">
                    @error('hero_video_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Enter a YouTube, Vimeo, or direct video URL for the hero section.</small>
                </div>
            </div>
        </div>

        <!-- Shop by Category Settings -->
        <div class="setting-card">
            <div class="setting-card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-tags mr-2"></i>Shop by Category Section
                </h6>
            </div>
            <div class="setting-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="shop_category_title">Section Title</label>
                            <input type="text" 
                                class="form-control @error('shop_category_title') is-invalid @enderror" 
                                id="shop_category_title" 
                                name="shop_category_title" 
                                value="{{ old('shop_category_title', $settings['shop_category_title']) }}"
                                required>
                            @error('shop_category_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="shop_category_description">Description</label>
                            <input type="text" 
                                class="form-control @error('shop_category_description') is-invalid @enderror" 
                                id="shop_category_description" 
                                name="shop_category_description" 
                                value="{{ old('shop_category_description', $settings['shop_category_description']) }}"
                                maxlength="500">
                            @error('shop_category_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="shop_category_selected">Select Categories (Maximum 3)</label>
                    <select name="shop_category_selected[]" 
                            id="shop_category_selected" 
                            class="form-control @error('shop_category_selected') is-invalid @enderror"
                            multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ in_array($category->id, $settings['shop_category_selected']) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('shop_category_selected')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Select maximum 3 categories to display in the shop section.</small>
                </div>
            </div>
        </div>

        <!-- Category Showcase Settings -->
        <div class="setting-card">
            <div class="setting-card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-th-large mr-2"></i>Category Showcase Section
                </h6>
            </div>
            <div class="setting-card-body">
                <div class="form-group">
                    <label for="category_showcase_selected">Select Categories for Showcase</label>
                    <select name="category_showcase_selected[]" 
                            id="category_showcase_selected" 
                            class="form-control @error('category_showcase_selected') is-invalid @enderror"
                            multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ in_array($category->id, $settings['category_showcase_selected']) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_showcase_selected')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Select categories to display in the showcase section (no limit).</small>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save mr-2"></i>Save Settings
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-lg ml-2">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </div>
    </form>
@endsection



@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .setting-card {
            background: #fff;
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .setting-card-header {
            padding: 1.25rem 1.25rem 0 1.25rem;
            border-bottom: 1px solid #e3e6f0;
            background-color: #f8f9fc;
            border-radius: 0.35rem 0.35rem 0 0;
        }

        .setting-card-body {
            padding: 1.25rem;
        }

        .form-group label {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }

        /* Select2 Custom Styling */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d3e2;
            border-radius: 0.375rem;
            min-height: 38px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #5a5c69;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(90, 92, 105, 0.25);
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #4e73df;
            border: 1px solid #4e73df;
            border-radius: 4px;
            color: white;
            padding: 2px 8px;
            margin: 2px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            margin-right: 5px;
            padding-right: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .select2-dropdown {
            border: 1px solid #d1d3e2;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #4e73df;
            color: white;
        }

        .select2-container--default .select2-search--inline .select2-search__field {
            border: none;
            outline: none;
            box-shadow: none;
        }

        .select2-container .select2-selection--multiple .select2-selection__rendered {
            padding: 4px;
        }

        /* Error state */
        .is-invalid + .select2-container .select2-selection {
            border-color: #dc3545;
        }

        .is-invalid + .select2-container.select2-container--focus .select2-selection {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display{
            padding-left: 16px;
        }
    </style>
@endpush


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 for shop categories (max 3)
            $('#shop_category_selected').select2({
                placeholder: 'Select up to 3 categories',
                allowClear: true,
                maximumSelectionLength: 3
            });

            // Initialize Select2 for showcase categories (no limit)
            $('#category_showcase_selected').select2({
                placeholder: 'Select categories for showcase',
                allowClear: true
            });
        });
    </script>
@endpush
