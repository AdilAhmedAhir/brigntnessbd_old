@extends('admin.layouts.app')

@section('title', 'Customers Management')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Customers Management</h1>
</div>

<!-- Filters -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search customers..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-5">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Customers Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Customer List ({{ $customers->total() }} total)</h6>
    </div>
    <div class="card-body">
        @if($customers->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Orders</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($customer->avatar)
                                        <img src="{{ asset('storage/' . $customer->avatar) }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover; margin-right: 8px;" alt="{{ $customer->name }}">
                                    @else
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2 text-white" style="width: 32px; height: 32px; font-size: 12px; margin-right: 8px;">
                                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('admin.customers.show', $customer) }}" class="text-primary font-weight-bold">
                                            {{ $customer->name }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?: 'N/A' }}</td>
                            <td>
                                <span class="badge badge-info">{{ $customer->orders_count }} orders</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $customer->status_badge }}">
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </td>
                            <td>{{ $customer->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <!-- Status Toggle Button -->
                                <form action="{{ route('admin.customers.update-status', $customer) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $customer->status === 'active' ? 'inactive' : 'active' }}">
                                    <button type="submit" class="btn btn-sm btn-{{ $customer->status === 'active' ? 'warning' : 'success' }}" 
                                            title="{{ $customer->status === 'active' ? 'Deactivate' : 'Activate' }} Customer"
                                            onclick="return confirm('Are you sure you want to {{ $customer->status === 'active' ? 'deactivate' : 'activate' }} this customer?')">
                                        <i class="fas fa-{{ $customer->status === 'active' ? 'ban' : 'check' }}"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($customers->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Customers pagination">
                    {{ $customers->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') }}
                </nav>
            </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No customers found</h5>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'status']))
                        No customers match your current filters.
                    @else
                        Customer accounts will appear here once they register on your website.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-primary">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
