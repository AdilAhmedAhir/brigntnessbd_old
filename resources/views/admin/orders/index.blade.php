@extends('admin.layouts.app')

@section('title', 'Orders Management')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Orders Management</h1>
</div>

<!-- Filters -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search orders..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="payment_status" class="form-control">
                    <option value="">All Payment Status</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Orders List ({{ $orders->total() }} total)</h6>
    </div>
    <div class="card-body">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-primary font-weight-bold">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                <div>{{ $order->user->name }}</div>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </td>
                            <td>{{ $order->orderItems->count() }} items</td>
                            <td class="font-weight-bold">৳{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $order->status_badge }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $order->payment_status_badge }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap" style="gap: 10px !important;">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary" title="View Details">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                    <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-sm btn-info" title="View Invoice">
                                        <i class="fas fa-file-invoice me-1"></i> Invoice
                                    </a>
                                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete Order">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($orders->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Orders pagination">
                    {{ $orders->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') }}
                </nav>
            </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No orders found</h5>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'status', 'payment_status']))
                        No orders match your current filters.
                    @else
                        Orders will appear here once customers start purchasing products.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status', 'payment_status']))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
