@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Order Details - #{{ $order->order_number }}</h1>
    <div class="btn-group">
        <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-invoice"></i> View Invoice
        </a>
        <a href="{{ route('admin.orders.invoice', $order) }}?print=1" class="btn btn-primary" target="_blank">
            <i class="fas fa-print"></i> Print Invoice
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>
</div>

<div class="row">
    <!-- Order Info -->
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order Items</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center" style="gap:10px">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset($item->product->image) }}" class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $item->product_name }}</div>
                                            @if($item->product_meta)
                                                <small class="text-muted">
                                                    @foreach($item->product_meta as $key => $value)
                                                        {{ ucfirst($key) }}: {{ $value }}
                                                        @if(!$loop->last), @endif
                                                    @endforeach
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->product_sku }}</td>
                                <td>৳{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="font-weight-bold">৳{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Subtotal:</td>
                                <td class="font-weight-bold">৳{{ number_format($order->orderItems->sum('total'), 2) }}</td>
                            </tr>
                            @if($order->shipping_fee > 0)
                            <tr>
                                <td colspan="4" class="text-right">Shipping:</td>
                                <td>৳{{ number_format($order->shipping_fee, 2) }}</td>
                            </tr>
                            @endif
                            @if($order->tax_amount > 0)
                            <tr>
                                <td colspan="4" class="text-right">Tax:</td>
                                <td>৳{{ number_format($order->tax_amount, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="table-success">
                                <td colspan="4" class="text-right font-weight-bold">Total:</td>
                                <td class="font-weight-bold">৳{{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Shipping & Billing Address -->
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Shipping Address</h6>
                    </div>
                    <div class="card-body">
                        @if($order->shipping_address)
                            <address class="mb-0">
                                {{ $order->shipping_address['name'] ?? '' }}<br>
                                {{ $order->shipping_address['address'] ?? '' }}<br>
                                {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['zip'] ?? '' }}<br>
                                {{ $order->shipping_address['country'] ?? '' }}
                                @if(isset($order->shipping_address['phone']))
                                    <br>Phone: {{ $order->shipping_address['phone'] }}
                                @endif
                            </address>
                        @else
                            <p class="text-muted">No shipping address provided</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Billing Address</h6>
                    </div>
                    <div class="card-body">
                        @if($order->billing_address)
                            <address class="mb-0">
                                {{ $order->billing_address['name'] ?? '' }}<br>
                                {{ $order->billing_address['address'] ?? '' }}<br>
                                {{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }} {{ $order->billing_address['zip'] ?? '' }}<br>
                                {{ $order->billing_address['country'] ?? '' }}
                                @if(isset($order->billing_address['phone']))
                                    <br>Phone: {{ $order->billing_address['phone'] }}
                                @endif
                            </address>
                        @else
                            <p class="text-muted">No billing address provided</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status & Actions -->
    <div class="col-lg-4">
        <!-- Order Summary -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order Summary</h6>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6"><strong>Order Date:</strong></div>
                    <div class="col-6">{{ $order->created_at->format('M d, Y H:i') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Customer:</strong></div>
                    <div class="col-6">
                        <a href="{{ route('admin.customers.show', $order->user) }}">{{ $order->user->name }}</a>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Email:</strong></div>
                    <div class="col-6">{{ $order->user->email }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Payment Method:</strong></div>
                    <div class="col-6">{{ $order->payment_method ?: 'N/A' }}</div>
                </div>
                @if($order->shipped_at)
                <div class="row mb-2">
                    <div class="col-6"><strong>Shipped:</strong></div>
                    <div class="col-6">{{ $order->shipped_at->format('M d, Y') }}</div>
                </div>
                @endif
                @if($order->delivered_at)
                <div class="row mb-2">
                    <div class="col-6"><strong>Delivered:</strong></div>
                    <div class="col-6">{{ $order->delivered_at->format('M d, Y') }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Status -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order Status</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mb-3">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="status">Order Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </form>

                <form action="{{ route('admin.orders.update-payment-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="payment_status">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-control">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-credit-card"></i> Update Payment
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Notes -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order Notes</h6>
            </div>
            <div class="card-body">
                @if($order->notes)
                    <p class="mb-0">{{ $order->notes }}</p>
                @else
                    <p class="text-muted mb-0">No notes for this order</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
