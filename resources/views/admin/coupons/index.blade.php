@extends('admin.layouts.app')

@section('title', 'Discount Coupons')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Discount Coupons</h1>
    <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addCouponModal">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add Coupon
    </button>
</div>

<!-- Coupons Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">All Coupons</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Usage</th>
                        <th>Valid Period</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td>
                                <code class="bg-light px-2 py-1 rounded">{{ $coupon->code }}</code>
                            </td>
                            <td>{{ $coupon->name }}</td>
                            <td>
                                <span class="badge badge-{{ $coupon->type === 'percentage' ? 'primary' : 'success' }}">
                                    {{ $coupon->type_text }}
                                </span>
                            </td>
                            <td>
                                @if($coupon->type === 'percentage')
                                    {{ $coupon->value }}%
                                @else
                                    ${{ number_format($coupon->value, 2) }}
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}
                                </small>
                                @if($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit)
                                    <br><span class="badge badge-warning">Limit Reached</span>
                                @endif
                            </td>
                            <td>
                                <small>
                                    {{ $coupon->start_date->format('M d, Y') }}<br>
                                    <span class="text-muted">to</span><br>
                                    {{ $coupon->end_date->format('M d, Y') }}
                                </small>
                            </td>
                            <td>
                                <span class="badge badge-{{ $coupon->status_badge }}">
                                    {{ $coupon->status_text }}
                                </span>
                                @if(!$coupon->isValid())
                                    <br><small class="text-muted">Invalid</small>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" 
                                        onclick="editCoupon({{ $coupon->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" 
                                      class="d-inline" onsubmit="return confirm('Are you sure you want to delete this coupon?')">
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
                            <td colspan="8" class="text-center">No coupons found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Coupon Modal -->
<div class="modal fade" id="addCouponModal" tabindex="-1" role="dialog" aria-labelledby="addCouponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCouponModalLabel">Add New Coupon</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code">Coupon Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code') }}" required 
                                       style="text-transform: uppercase;" placeholder="e.g., SAVE20">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Must be unique. Will be converted to uppercase.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Coupon Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required
                                       placeholder="e.g., 20% Off Summer Sale">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="2" 
                                  placeholder="Optional description for internal use">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Discount Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('type') is-invalid @enderror" 
                                        id="type" name="type" required onchange="toggleValueFields()">
                                    <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>
                                        Percentage (%)
                                    </option>
                                    <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>
                                        Fixed Amount ($)
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="value">Discount Value <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend" id="value-prefix">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                           id="value" name="value" value="{{ old('value') }}" 
                                           step="0.01" min="0" required>
                                </div>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted" id="value-help">
                                    Enter percentage value (0-100)
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="minimum_amount">Minimum Order Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control @error('minimum_amount') is-invalid @enderror" 
                                           id="minimum_amount" name="minimum_amount" value="{{ old('minimum_amount', 0) }}" 
                                           step="0.01" min="0">
                                </div>
                                @error('minimum_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Minimum order value to apply coupon</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maximum_discount">Maximum Discount Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control @error('maximum_discount') is-invalid @enderror" 
                                           id="maximum_discount" name="maximum_discount" value="{{ old('maximum_discount') }}" 
                                           step="0.01" min="0">
                                </div>
                                @error('maximum_discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Optional cap on discount amount</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usage_limit">Total Usage Limit</label>
                                <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                                       id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" 
                                       min="1" placeholder="Unlimited if empty">
                                @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Total number of times coupon can be used</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usage_limit_per_user">Usage Limit Per User <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('usage_limit_per_user') is-invalid @enderror" 
                                       id="usage_limit_per_user" name="usage_limit_per_user" 
                                       value="{{ old('usage_limit_per_user', 1) }}" min="1" required>
                                @error('usage_limit_per_user')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">How many times one user can use this coupon</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="1" {{ old('status', '1') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Coupon</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Coupon Modal -->
<div class="modal fade" id="editCouponModal" tabindex="-1" role="dialog" aria-labelledby="editCouponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCouponModalLabel">Edit Coupon</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCouponForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_code">Coupon Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_code" name="code" required 
                                       style="text-transform: uppercase;">
                                <small class="form-text text-muted">Must be unique. Will be converted to uppercase.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_name">Coupon Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_type">Discount Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_type" name="type" required onchange="toggleEditValueFields()">
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount ($)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_value">Discount Value <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend" id="edit-value-prefix">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="number" class="form-control" id="edit_value" name="value" 
                                           step="0.01" min="0" required>
                                </div>
                                <small class="form-text text-muted" id="edit-value-help">
                                    Enter percentage value (0-100)
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_minimum_amount">Minimum Order Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control" id="edit_minimum_amount" 
                                           name="minimum_amount" step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_maximum_discount">Maximum Discount Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control" id="edit_maximum_discount" 
                                           name="maximum_discount" step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_usage_limit">Total Usage Limit</label>
                                <input type="number" class="form-control" id="edit_usage_limit" 
                                       name="usage_limit" min="1" placeholder="Unlimited if empty">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_usage_limit_per_user">Usage Limit Per User <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_usage_limit_per_user" 
                                       name="usage_limit_per_user" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_start_date">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_end_date">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select class="form-control" id="edit_status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Coupon</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Custom DataTables CSS -->
<link href="{{ asset('admin-asset/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<!-- Page level plugins -->
<script src="{{ asset('admin-asset/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-asset/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#dataTable').DataTable({
        "order": [[ 6, "desc" ]], // Sort by status column
        "pageLength": 25,
        "responsive": true
    });

    // Toggle value fields based on discount type (Add modal)
    toggleValueFields();
    
    // Auto-uppercase coupon codes
    $('#code, #edit_code').on('input', function() {
        this.value = this.value.toUpperCase();
    });
});

function toggleValueFields() {
    const type = $('#type').val();
    const prefix = $('#value-prefix span');
    const help = $('#value-help');
    
    if (type === 'percentage') {
        prefix.text('%');
        help.text('Enter percentage value (0-100)');
    } else {
        prefix.text('$');
        help.text('Enter fixed discount amount');
    }
}

function toggleEditValueFields() {
    const type = $('#edit_type').val();
    const prefix = $('#edit-value-prefix span');
    const help = $('#edit-value-help');
    
    if (type === 'percentage') {
        prefix.text('%');
        help.text('Enter percentage value (0-100)');
    } else {
        prefix.text('$');
        help.text('Enter fixed discount amount');
    }
}

function editCoupon(id) {
    // Fetch coupon data
    $.get('/admin/coupons/' + id + '/edit', function(response) {
        if (response.success) {
            const coupon = response.coupon;
            
            // Populate edit form
            $('#edit_code').val(coupon.code);
            $('#edit_name').val(coupon.name);
            $('#edit_description').val(coupon.description || '');
            $('#edit_type').val(coupon.type);
            $('#edit_value').val(coupon.value);
            $('#edit_minimum_amount').val(coupon.minimum_amount || '');
            $('#edit_maximum_discount').val(coupon.maximum_discount || '');
            $('#edit_usage_limit').val(coupon.usage_limit || '');
            $('#edit_usage_limit_per_user').val(coupon.usage_limit_per_user);
            $('#edit_start_date').val(coupon.start_date);
            $('#edit_end_date').val(coupon.end_date);
            $('#edit_status').val(coupon.status ? '1' : '0');
            
            // Update form action
            $('#editCouponForm').attr('action', '/admin/coupons/' + id);
            
            // Toggle value fields
            toggleEditValueFields();
            
            // Show modal
            $('#editCouponModal').modal('show');
        }
    }).fail(function() {
        alert('Error loading coupon data');
    });
}

// Show validation errors in modals
@if($errors->any())
    @if(old('_method') === 'PUT')
        $('#editCouponModal').modal('show');
    @else
        $('#addCouponModal').modal('show');
    @endif
@endif
</script>
@endpush