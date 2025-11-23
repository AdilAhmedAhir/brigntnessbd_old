@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

        {{-- Date Range Filter --}}
        <div class="d-none d-sm-inline-block">
            <div class="input-group" style="width: 280px;">
                <input type="text" id="daterange" class="form-control form-control-sm" placeholder="Select Date Range">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">



        <!-- Total Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's - Sales
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">৳ {{ number_format($todaysSales, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Total Sales Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Sales (Period)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">৳ {{ number_format($monthlySales, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        <!-- Total Orders Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Orders</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-bag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Customers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCustomers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Orders Overview - Last 30 Days</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Actions:</div>
                            <a class="dropdown-item" href="#">View Details</a>
                            <a class="dropdown-item" href="#">Export Data</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Order Status Distribution</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink2">
                            <div class="dropdown-header">Actions:</div>
                            <a class="dropdown-item" href="#">View Orders</a>
                            <a class="dropdown-item" href="#">Manage Status</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @if(!empty($statusLabels))
                            @foreach($statusLabels as $index => $label)
                                <span class="mr-2">
                                    <i class="fas fa-circle" style="color: {{ ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'][$index % 4] }}"></i> {{ $label }}
                                </span>
                            @endforeach
                        @else
                            <span class="mr-2">
                                <i class="fas fa-circle text-primary"></i> Processing
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> Completed
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-info"></i> Shipped
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-warning"></i> Pending
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Recent Orders -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->order_number }}</a></td>
                                        <td>{{ $order->user ? $order->user->name : 'Guest' }}</td>
                                        <td>৳{{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $order->status_badge }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Selling Products</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Sales</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $product)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.products.show', $product) }}">
                                                {{ Str::limit($product->name, 30) }}
                                            </a>
                                        </td>
                                        <td>{{ $product->primary_category ? $product->primary_category->name : 'Uncategorized' }}</td>
                                        <td>{{ $product->total_sold ?? 0 }} units</td>
                                        <td>৳{{ number_format(($product->total_sold ?? 0) * $product->price, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No products found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <!-- Date Range Picker JavaScript -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('admin-asset/vendor/chart.js/Chart.min.js') }}"></script>
    <!-- Fallback Chart.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Custom chart configuration for ecommerce dashboard -->
    <script>
    // Global variables for charts
    let myLineChart, myPieChart;

    // Chart.js v3+ configuration
    Chart.defaults.font.family = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.color = '#858796';

    // Initialize Date Range Picker
    $(document).ready(function() {
        // Set initial date range (last 30 days)
        var start = moment().subtract(29, 'days');
        var end = moment();
        
        // Initialize date range picker
        $('#daterange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: 'MM/DD/YYYY'
            }
        }, function(start, end, label) {
            $('#daterange').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
            updateDashboard(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        });
        
        // Set initial display value
        $('#daterange').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
        
        // Initialize charts with initial data
        initializeCharts();
    });

    // Function to update dashboard data
    function updateDashboard(startDate, endDate) {
        // Show loading state
        $('.card-body').addClass('loading');
        
        $.ajax({
            url: '{{ route("admin.dashboard") }}',
            method: 'GET',
            data: {
                start_date: startDate,
                end_date: endDate
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(data) {
                // Update dashboard metrics
                updateDashboardMetrics(data);
                
                // Update charts
                updateCharts(data);
                
                // Update tables
                updateTables(data);
                
                // Remove loading state
                $('.card-body').removeClass('loading');
            },
            error: function(xhr, status, error) {
                console.error('Error updating dashboard:', error);
                $('.card-body').removeClass('loading');
            }
        });
    }

    // Function to update dashboard metrics
    function updateDashboardMetrics(data) {
        // Update sales figures
        $('.h5:contains("৳")').each(function() {
            var $this = $(this);
            var parent = $this.closest('.card');
            
            if (parent.find('.text-success').length > 0) {
                // Today's sales
                $this.text('৳ ' + new Intl.NumberFormat().format(data.todaysSales));
            } else if (parent.find('.text-primary').length > 0) {
                // Period sales
                $this.text('৳ ' + new Intl.NumberFormat().format(data.monthlySales));
            }
        });
        
        // Update orders count
        $('.card .text-info').closest('.card').find('.h5').text(data.totalOrders);
        
        // Update customers count
        $('.card .text-warning').closest('.card').find('.h5').text(data.totalCustomers);
    }

    // Function to update charts
    function updateCharts(data) {
        // Update line chart
        if (myLineChart) {
            myLineChart.data.labels = data.chartLabels;
            myLineChart.data.datasets[0].data = data.chartData;
            myLineChart.update();
        }
        
        // Update pie chart
        if (myPieChart) {
            myPieChart.data.labels = data.statusLabels;
            myPieChart.data.datasets[0].data = data.statusCounts;
            myPieChart.update();
        }
    }

    // Function to update tables
    function updateTables(data) {
        // Update recent orders table
        var recentOrdersBody = $('.table').first().find('tbody');
        recentOrdersBody.empty();
        
        if (data.recentOrders.length > 0) {
            data.recentOrders.forEach(function(order) {
                var statusBadgeClass = getStatusBadgeClass(order.status);
                var customerName = order.user ? order.user.name : 'Guest';
                var row = `
                    <tr>
                        <td><a href="/admin/orders/${order.id}">#${order.order_number}</a></td>
                        <td>${customerName}</td>
                        <td>৳${new Intl.NumberFormat().format(order.total_amount)}</td>
                        <td><span class="badge badge-${statusBadgeClass}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span></td>
                    </tr>
                `;
                recentOrdersBody.append(row);
            });
        } else {
            recentOrdersBody.html('<tr><td colspan="4" class="text-center">No orders found</td></tr>');
        }
        
        // Update top products table
        var topProductsBody = $('.table').last().find('tbody');
        topProductsBody.empty();
        
        if (data.topProducts.length > 0) {
            data.topProducts.forEach(function(product) {
                var categoryName = product.primary_category ? product.primary_category.name : 'Uncategorized';
                var revenue = (product.total_sold || 0) * product.price;
                var row = `
                    <tr>
                        <td><a href="/admin/products/${product.id}">${product.name.length > 30 ? product.name.substring(0, 30) + '...' : product.name}</a></td>
                        <td>${categoryName}</td>
                        <td>${product.total_sold || 0} units</td>
                        <td>৳${new Intl.NumberFormat().format(revenue)}</td>
                    </tr>
                `;
                topProductsBody.append(row);
            });
        } else {
            topProductsBody.html('<tr><td colspan="4" class="text-center">No products found</td></tr>');
        }
    }

    // Helper function to get status badge class
    function getStatusBadgeClass(status) {
        const statusClasses = {
            'pending': 'warning',
            'processing': 'info',
            'shipped': 'primary',
            'delivered': 'success',
            'cancelled': 'danger'
        };
        return statusClasses[status] || 'secondary';
    }

    // Function to initialize charts
    function initializeCharts() {
        // Area Chart for Orders
        var ctx = document.getElementById("myAreaChart");
        myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
            label: "Orders",
            tension: 0.3,
            backgroundColor: "rgba(78, 115, 223, 0.05)",
            borderColor: "rgba(78, 115, 223, 1)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(78, 115, 223, 1)",
            pointBorderColor: "rgba(78, 115, 223, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: {!! json_encode($chartData) !!},
            fill: true
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
            },
            scales: {
            x: {
                grid: {
                display: false,
                drawBorder: false
                },
                ticks: {
                maxTicksLimit: 7
                }
            },
            y: {
                ticks: {
                maxTicksLimit: 5,
                padding: 10,
                callback: function(value, index, values) {
                    return value + ' orders';
                }
                },
                grid: {
                color: "rgb(234, 236, 244)",
                zeroLineColor: "rgb(234, 236, 244)",
                drawBorder: false,
                borderDash: [2],
                zeroLineBorderDash: [2]
                }
            }
            },
            plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: "rgb(255,255,255)",
                bodyColor: "#858796",
                titleMarginBottom: 10,
                titleColor: '#6e707e',
                titleFont: {
                size: 14
                },
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                label: function(context) {
                    return context.dataset.label + ': ' + context.parsed.y + ' orders';
                }
                }
            }
            }
        }
        });

        // Pie Chart for Order Status
        var ctx2 = document.getElementById("myPieChart");
        myPieChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusLabels) !!},
            datasets: [{
            data: {!! json_encode($statusCounts) !!},
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#f4b619'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
            tooltip: {
                backgroundColor: "rgb(255,255,255)",
                bodyColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            }
            },
            cutout: '80%'
        },
        });
    }
</script>
@endpush


@push('styles')
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Add some CSS for loading state -->
    <style>
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endpush