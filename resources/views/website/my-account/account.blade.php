@extends('website.layouts.app')

@section('title', 'My Account - Brightness Fashion | Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('site-asset/css/account.css') }}">
@endpush

@section('content')
<div class="account-page">
    <!-- Account Header -->
    <div class="account-header">
        <div class="container">
            <h1>My Account</h1>
            <p>Welcome back, {{ $user->name }}! Manage your account and orders.</p>
        </div>
    </div>

    <!-- Account Content -->
    <div class="container">
        <div class="account-content">
            <!-- Sidebar -->
            <div class="account-sidebar">
                <div class="sidebar-profile">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-email">{{ $user->email }}</div>
                </div>

                <nav>
                    <ul class="account-nav">
                        <li><a href="{{ route('account.dashboard') }}" class="active">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a></li>
                        <li><a href="{{ route('account.orders') }}">
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
                <div class="section-title">Dashboard</div>

                <!-- Dashboard Stats -->
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <div class="stat-number">{{ $recentOrders->count() }}</div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $recentOrders->where('status', 'pending')->count() }}</div>
                        <div class="stat-label">Pending Orders</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $recentOrders->where('status', 'delivered')->count() }}</div>
                        <div class="stat-label">Completed Orders</div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="recent-orders">
                    <h3>Recent Orders</h3>
                    
                    @if($recentOrders->count() > 0)
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td>#{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="order-status {{ $order->status }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>৳{{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <a href="{{ route('account.order.view', $order->id) }}" class="btn-view">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        @if($recentOrders->count() >= 5)
                            <div style="text-align: center; margin-top: 20px;">
                                <a href="{{ route('account.orders') }}" class="btn-view">View All Orders</a>
                            </div>
                        @endif
                    @else
                        <div class="no-orders">
                            <i class="fas fa-shopping-bag"></i>
                            <p>You haven't placed any orders yet.</p>
                            <a href="{{ route('products.index') }}" class="btn-view">Start Shopping</a>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection