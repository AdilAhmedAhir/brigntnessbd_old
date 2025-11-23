@extends('admin.layouts.app')

@section('title', 'Categories')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Categories</h1>
    <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addCategoryModal">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add Category
    </button>
</div>

<!-- Categories Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">All Categories</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Parent Category</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Show on Header</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            
                            <td>{{ $category->name }}</td>
                            <td>
                                @if($category->parent)
                                    <span class="badge badge-info">{{ $category->parent->name }}</span>
                                @else
                                    <span class="text-muted">Root Category</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($category->description ?? 'No description', 50) }}</td>
                            <td>
                                @if($category->cover_image)
                                    <img src="{{ asset($category->cover_image) }}" alt="{{ $category->name }}" 
                                         class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px; border-radius: 4px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($category->show_on_header)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Yes
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-times"></i> No
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $category->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($category->status) }}
                                </span>
                            </td>
                            <td>{{ $category->created_at->format('M d, Y') }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" 
                                        onclick="editCategory({{ $category->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" 
                                      class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No categories found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="parent_id">Parent Category</label>
                        <select class="form-control @error('parent_id') is-invalid @enderror" 
                                id="parent_id" name="parent_id">
                            <option value="">---No Parent Category---</option>
                            @foreach($parentCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}" 
                                        {{ old('parent_id') == $parentCategory->id ? 'selected' : '' }}>
                                    {{ $parentCategory->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Leave empty to create a root category</small>
                    </div>

                    <div class="form-group">
                        <label for="name">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="cover_image">Cover Image (Desktop)</label>
                        <input type="file" class="form-control-file @error('cover_image') is-invalid @enderror" 
                               id="cover_image" name="cover_image" accept="image/*">
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Recommended: 1200x600px. Accepted formats: JPG, PNG, GIF. Max size: 2MB</small>
                    </div>

                    <div class="form-group">
                        <label for="mobile_cover_image">Mobile Cover Image</label>
                        <input type="file" class="form-control-file @error('mobile_cover_image') is-invalid @enderror" 
                               id="mobile_cover_image" name="mobile_cover_image" accept="image/*">
                        @error('mobile_cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Recommended: 600x400px. Accepted formats: JPG, PNG, GIF. Max size: 2MB</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="show_on_header" 
                                   name="show_on_header" value="1" {{ old('show_on_header') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="show_on_header">
                                Show on Header Navigation
                            </label>
                        </div>
                        <small class="form-text text-muted">Check this to display category in the header menu</small>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    <div class="form-group">
                        <label for="edit_parent_id">Parent Category</label>
                        <select class="form-control" id="edit_parent_id" name="parent_id">
                            <option value="">--- No Parent Category ---</option>
                            @foreach($parentCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Leave empty to make this a root category</small>
                    </div>


                    <div class="form-group">
                        <label for="edit_name">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    

                    <div class="form-group">
                        <label for="edit_cover_image">Cover Image (Desktop)</label>
                        <input type="file" class="form-control-file" id="edit_cover_image" name="cover_image" accept="image/*">
                        <small class="form-text text-muted">Leave empty to keep current image</small>
                        <div id="current_image_preview" class="mt-2"></div>
                    </div>

                    <div class="form-group">
                        <label for="edit_mobile_cover_image">Mobile Cover Image</label>
                        <input type="file" class="form-control-file" id="edit_mobile_cover_image" name="mobile_cover_image" accept="image/*">
                        <small class="form-text text-muted">Leave empty to keep current mobile image</small>
                        <div id="current_mobile_image_preview" class="mt-2"></div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="edit_show_on_header" 
                                   name="show_on_header" value="1">
                            <label class="custom-control-label" for="edit_show_on_header">
                                Show on Header Navigation
                            </label>
                        </div>
                        <small class="form-text text-muted">Check this to display category in the header menu</small>
                    </div>

                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select class="form-control" id="edit_status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Handle edit category
function editCategory(categoryId) {
    console.log('Fetching category:', categoryId); // Debug log
    
    fetch(`/admin/categories/${categoryId}/edit`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
        .then(response => {
            console.log('Response status:', response.status); // Debug log
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data); // Debug log
            if (data.success) {
                const category = data.category;
                
                // Populate form fields
                document.getElementById('edit_name').value = category.name;
                document.getElementById('edit_description').value = category.description || '';
                document.getElementById('edit_status').value = category.status;
                document.getElementById('edit_parent_id').value = category.parent_id || '';
                document.getElementById('edit_show_on_header').checked = category.show_on_header == 1;
                
                // Set form action
                document.getElementById('editCategoryForm').action = `/admin/categories/${categoryId}`;
                
                // Show current desktop image if exists
                const imagePreview = document.getElementById('current_image_preview');
                if (category.cover_image) {
                    imagePreview.innerHTML = `
                        <small class="text-muted">Current desktop image:</small><br>
                        <img src="/${category.cover_image}" alt="${category.name}" 
                             class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                    `;
                } else {
                    imagePreview.innerHTML = '<small class="text-muted">No current desktop image</small>';
                }

                // Show current mobile image if exists
                const mobileImagePreview = document.getElementById('current_mobile_image_preview');
                if (category.mobile_cover_image) {
                    mobileImagePreview.innerHTML = `
                        <small class="text-muted">Current mobile image:</small><br>
                        <img src="/${category.mobile_cover_image}" alt="${category.name}" 
                             class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                    `;
                } else {
                    mobileImagePreview.innerHTML = '<small class="text-muted">No current mobile image</small>';
                }
                
                // Show modal
                $('#editCategoryModal').modal('show');
            } else {
                alert('Failed to load category data');
            }
        })
        .catch(error => {
            console.error('Error fetching category:', error);
            alert(`Error loading category data: ${error.message}`);
        });
}

// Handle form validation errors
@if($errors->any())
    @if(old('name') && !old('edit_name'))
        // Show add modal if validation errors for add form
        $('#addCategoryModal').modal('show');
    @endif
@endif
</script>
@endpush
