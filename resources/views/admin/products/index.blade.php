@extends('admin.layouts.app')

@section('title', 'All Products')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">All Products</h1>
    <a href="{{ route('admin.products.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add Product
    </a>
</div>

<!-- Products Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Products List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                                         class="rounded mr-2" style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #e3e6f0;">
                                @else
                                    <div class="bg-light rounded mr-2 d-flex align-items-center justify-content-center border" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <strong class="text-dark">{{ $product->name }}</strong>
                                    @if($product->featured)
                                        <span class="badge badge-warning badge-sm ml-1">Featured</span>
                                    @endif
                                    @if($product->product_size && count($product->product_size) > 0)
                                        @php
                                            $sizeDisplay = collect($product->product_size)->take(3)->map(function($size) {
                                                if (is_array($size) && isset($size['size_name'])) {
                                                    return $size['size_name'] . ' (' . ($size['quantity'] ?? 0) . ')';
                                                }
                                                return is_string($size) ? $size : '';
                                            })->filter()->implode(', ');
                                        @endphp
                                        <br><small class="text-muted">Sizes: {{ $sizeDisplay }}{{ count($product->product_size) > 3 ? '...' : '' }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td><code>{{ $product->sku }}</code></td>
                        <td>
                            @if($product->hasDiscount())
                                <span class="text-muted"><s>৳{{ $product->price }}</s></span><br>
                                <strong class="text-success">৳{{ $product->sale_price }}</strong>
                            @else
                                <strong>৳{{ $product->price }}</strong>
                            @endif
                        </td>
                        <td>
                            @php
                                $totalStock = 0;
                                if ($product->product_size && is_array($product->product_size)) {
                                    $totalStock = collect($product->product_size)->sum(function($size) {
                                        return is_array($size) && isset($size['quantity']) ? (int)$size['quantity'] : 0;
                                    });
                                } else {
                                    $totalStock = $product->stock_quantity ?? 0;
                                }
                            @endphp
                            <span class="badge badge-{{ $totalStock > 10 ? 'success' : ($totalStock > 0 ? 'warning' : 'danger') }}">
                                {{ $totalStock }} units
                            </span>
                        </td>
                        <td>{{ $product->category_names ?: 'Uncategorized' }}</td>
                        <td>
                            <span class="badge badge-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group" style="gap: 5px;">
                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                   class="btn btn-primary btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" 
                                      style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-box-open fa-2x mb-2"></i>
                                <p>No products found.</p>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add Your First Product
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($products->hasPages())
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Product pagination">
                {{ $products->onEachSide(1)->links('pagination::bootstrap-4') }}
            </nav>
        </div>
        @endif
    </div>
</div>
@endsection
