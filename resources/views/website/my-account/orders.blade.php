@extends('website.layouts.app')

@section('title', 'My Orders - Brightness Fashion | Order History')

@push('styles')
<link rel="stylesheet" href="{{ asset('site-asset/css/account.css') }}">
<style>
    .order-actions{
        background: #f7f7f7;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
        margin-top: 20px;
    }
    .order-details{
        align-items: start;
    }
</style>
@endpush

@section('content')
<div class="account-page">
    <!-- Account Header -->
    <div class="account-header">
        <div class="container">
            <h1>My Orders</h1>
            <p>Track and manage your order history</p>
        </div>
    </div>

    <!-- Account Content -->
    <div class="container">
        <div class="account-content">
            <!-- Sidebar -->
            <div class="account-sidebar">
                <div class="sidebar-profile">
                    <div class="profile-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="profile-name">{{ auth()->user()->name }}</div>
                    <div class="profile-email">{{ auth()->user()->email }}</div>
                </div>

                <nav>
                    <ul class="account-nav">
                        <li><a href="{{ route('account.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a></li>
                        <li><a href="{{ route('account.orders') }}" class="active">
                            <i class="fas fa-shopping-bag"></i>
                            Orders
                        </a></li>
                        <li><a href="{{ route('account.profile') }}">
                            <i class="fas fa-user-edit"></i>
                            Profile
                        </a></li>
                        <li>
                            <form action="{{ route('account.logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="logout-button" style="width: 100%; background: none; border: none; padding: 0; text-align: left;">
                                    <a href="#" style="pointer-events: none;">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Logout
                                    </a>
                                </button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="account-main">
                <div class="section-title">Order History</div>

                @if($orders->count() > 0)
                    <div class="orders-list">
                        @foreach($orders as $order)
                            <div class="order-card">
                                <div class="order-header">
                                    <div class="order-info">
                                        <div class="order-number">#{{ $order->order_number }}</div>
                                        <div class="order-date">Placed on {{ $order->created_at->format('M d, Y g:i A') }}</div>
                                    </div>
                                    <div class="order-status {{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </div>
                                </div>

                                <div class="order-details">
                                    <div class="order-items">
                                        @foreach($order->orderItems->take(3) as $item)
                                            <div class="order-item">
                                                <div class="item-image">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset($item->product->image) }}" alt="{{ $item->product_name }}">
                                                    @else
                                                        <img src="{{ asset('site-asset/img/no-image.jpg') }}" alt="{{ $item->product_name }}">
                                                    @endif
                                                </div>
                                                <div class="item-info">
                                                    <div class="item-name">{{ $item->product_name }}</div>
                                                    <div class="item-details">
                                                        Qty: {{ $item->quantity }}
                                                        @if($item->size)
                                                            | Size: {{ $item->size }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="item-price">৳{{ number_format($item->price, 2) }}</div>
                                            </div>
                                        @endforeach
                                        
                                        @if($order->orderItems->count() > 3)
                                            <div style="text-align: center; color: #666; font-size: 14px; margin-top: 10px;">
                                                +{{ $order->orderItems->count() - 3 }} more item(s)
                                            </div>
                                        @endif
                                    </div>

                                    <div class="order-actions">
                                        <div class="order-pricing">
                                            @if($order->discount_amount > 0)
                                                <div class="order-subtotal">
                                                    <small>Subtotal: ৳{{ number_format($order->subtotal, 2) }}</small>
                                                </div>
                                                <div class="order-discount">
                                                    <small style="color: #28a745;">
                                                        <i class="fas fa-ticket-alt"></i>
                                                        Discount ({{ $order->coupon_code }}): -৳{{ number_format($order->discount_amount, 2) }}
                                                    </small>
                                                </div>
                                            @endif
                                            <div class="order-total">৳{{ number_format($order->total_amount, 2) }}</div>
                                        </div>
                                        <a href="{{ route('account.order.view', $order->id) }}" class="btn-view">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($orders->hasPages())
                        <div class="pagination-wrapper">
                            {{ $orders->links() }}
                        </div>
                    @endif

                @else
                    <div class="no-orders">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>No Orders Yet</h3>
                        <p>You haven't placed any orders yet. Start shopping to see your orders here.</p>
                        <a href="{{ route('products.index') }}" class="btn-shop">Start Shopping</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection