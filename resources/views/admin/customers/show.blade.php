@extends('admin.layouts.app')

@section('title', 'Customer Details')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Customer Details - {{ $customer->name }}</h1>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Customers
    </a>
</div>

<div class="row">
    <!-- Customer Info -->
    <div class="col-lg-8">
        <!-- Customer Orders -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Order History ({{ $customer->orders->count() }} orders)</h6>
            </div>
            <div class="card-body">
                @if($customer->orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-primary font-weight-bold">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->orderItems->count() }} items</td>
                                    <td class="font-weight-bold">${{ number_format($order->total_amount, 2) }}</td>
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
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary" title="View Order">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-bag fa-2x text-muted mb-2"></i>
                        <p class="text-muted">This customer hasn't placed any orders yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Customer Address -->
        @if($customer->address || $customer->city || $customer->state)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Address Information</h6>
            </div>
            <div class="card-body">
                <address class="mb-0">
                    @if($customer->address)
                        {{ $customer->address }}<br>
                    @endif
                    @if($customer->city || $customer->state || $customer->zip_code)
                        {{ $customer->city }}@if($customer->city && $customer->state), @endif{{ $customer->state }} {{ $customer->zip_code }}<br>
                    @endif
                    @if($customer->country)
                        {{ $customer->country }}
                    @endif
                </address>
            </div>
        </div>
        @endif
    </div>

    <!-- Customer Details Sidebar -->
    <div class="col-lg-4">
        <!-- Customer Profile -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Customer Profile</h6>
            </div>
            <div class="card-body text-center">
                @if($customer->avatar)
                    <img src="{{ asset('storage/' . $customer->avatar) }}" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                @else
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 text-white" style="width: 80px; height: 80px; font-size: 24px;">
                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                    </div>
                @endif
                <h5 class="card-title">{{ $customer->name }}</h5>
                <p class="card-text text-muted">{{ $customer->email }}</p>
                
                <div class="mb-3">
                    <span class="badge badge-{{ $customer->status_badge }} badge-lg">
                        {{ ucfirst($customer->status) }}
                    </span>
                </div>

                <!-- Status Change Form -->
                <form action="{{ route('admin.customers.update-status', $customer) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="{{ $customer->status === 'active' ? 'inactive' : 'active' }}">
                    <button type="submit" class="btn btn-{{ $customer->status === 'active' ? 'warning' : 'success' }} btn-sm"
                            onclick="return confirm('Are you sure you want to {{ $customer->status === 'active' ? 'deactivate' : 'activate' }} this customer?')">
                        <i class="fas fa-{{ $customer->status === 'active' ? 'ban' : 'check' }}"></i>
                        {{ $customer->status === 'active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Customer Stats -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Customer Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-7"><strong>Total Orders:</strong></div>
                    <div class="col-5">{{ $customer->orders->count() }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-7"><strong>Total Spent:</strong></div>
                    <div class="col-5">${{ number_format($customer->orders->sum('total_amount'), 2) }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-7"><strong>Avg Order Value:</strong></div>
                    <div class="col-5">
                        @if($customer->orders->count() > 0)
                            ${{ number_format($customer->orders->avg('total_amount'), 2) }}
                        @else
                            $0.00
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-7"><strong>Member Since:</strong></div>
                    <div class="col-5">{{ $customer->created_at->format('M Y') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-7"><strong>Last Order:</strong></div>
                    <div class="col-5">
                        @if($customer->orders->count() > 0)
                            {{ $customer->orders->first()->created_at->format('M d, Y') }}
                        @else
                            Never
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-4"><strong>Email:</strong></div>
                    <div class="col-8">
                        <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                    </div>
                </div>
                @if($customer->phone)
                <div class="row mb-2">
                    <div class="col-4"><strong>Phone:</strong></div>
                    <div class="col-8">
                        <a href="tel:{{ $customer->phone }}">{{ $customer->phone }}</a>
                    </div>
                </div>
                @endif
                @if($customer->date_of_birth)
                <div class="row mb-2">
                    <div class="col-4"><strong>Birthday:</strong></div>
                    <div class="col-8">{{ $customer->date_of_birth->format('M d, Y') }}</div>
                </div>
                @endif
                @if($customer->gender)
                <div class="row mb-2">
                    <div class="col-4"><strong>Gender:</strong></div>
                    <div class="col-8">{{ ucfirst($customer->gender) }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
