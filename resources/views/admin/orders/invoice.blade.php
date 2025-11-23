<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice #{{ $order->order_number }} - {{ \App\Models\Setting::get('site_name', 'Brightness Fashion') }}</title>
    
    <!-- Bootstrap CSS for basic styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Invoice CSS -->
    <link href="{{ asset('admin-asset/css/invoice.css') }}" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="invoice-body">
    <div class="invoice-container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="row">
                <div class="col-6">
                    <div class="company-info">
                        <img src="{{ asset('uploads/website/' . \App\Models\Setting::get('site_icon')) }}" class="site-icon" alt="{{ \App\Models\Setting::get('site_name', 'Brightness Fashion') }}">
                        <h1 class="company-name">
                            {{ \App\Models\Setting::get('site_name', 'Brightness Fashion') }}
                        </h1>
                        <div class="company-details">
                            @if(\App\Models\Setting::get('footer_address'))
                                <div>{{ \App\Models\Setting::get('footer_address') }}</div>
                            @else
                                <div>123 Fashion Street, Style District</div>
                                <div>Dhaka, Bangladesh 1000</div>
                            @endif
                            @if(\App\Models\Setting::get('footer_phone'))
                                <div><i class="fas fa-phone"></i> {{ \App\Models\Setting::get('footer_phone') }}</div>
                            @else
                                <div><i class="fas fa-phone"></i> +880 1234-567890</div>
                            @endif
                            @if(\App\Models\Setting::get('footer_email'))
                                <div><i class="fas fa-envelope"></i> {{ \App\Models\Setting::get('footer_email') }}</div>
                            @else
                                <div><i class="fas fa-envelope"></i> info@brightnessfashion.com</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-6 text-end">


                    <div class="invoice-title">
                        <h2>INVOICE</h2>
                        <div class="invoice-meta">
                            <div class="invoice-number">#{{ $order->order_number }}</div>
                            <div class="invoice-date">{{ $order->created_at->format('F d, Y') }}</div>
                        </div>
                    </div>

                    <div class="info-card bill-to">
                        <h5 class="address-title">Bill To</h5>
                        <div class="address-content">
                            <strong>{{ $order->user->name }}</strong><br>
                            {{ $order->user->email }}
                            @if($order->billing_address)
                                <br>{{ $order->billing_address['address'] ?? '' }}
                                <br>{{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }} {{ $order->billing_address['zip'] ?? '' }}
                                @if(isset($order->billing_address['phone']))
                                    <br>{{ $order->billing_address['phone'] }}
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items Table -->
        <div class="order-items-section">
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Item Description</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            @if($item->product_meta)
                                <br><small class="text-muted">
                                    @foreach($item->product_meta as $key => $value)
                                        {{ ucfirst($key) }}: {{ $value }}@if(!$loop->last), @endif
                                    @endforeach
                                </small>
                            @endif
                        </td>
                        <td class="sku-cell">{{ $item->product_sku }}</td>
                        <td class="text-end">৳{{ number_format($item->price, 2) }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">৳{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Order Totals -->
        <div class="order-totals-section">
            <div class="row justify-content-end">
                <div class="col-5">
                    <table class="totals-table">
                        <tr>
                            <td>Subtotal:</td>
                            <td class="text-end">৳{{ number_format($order->orderItems->sum('total'), 2) }}</td>
                        </tr>
                        @if($order->shipping_fee > 0)
                        <tr>
                            <td>Shipping:</td>
                            <td class="text-end">৳{{ number_format($order->shipping_fee, 2) }}</td>
                        </tr>
                        @endif
                        @if($order->tax_amount > 0)
                        <tr>
                            <td>Tax:</td>
                            <td class="text-end">৳{{ number_format($order->tax_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="grand-total">
                            <td><strong>Total Amount:</strong></td>
                            <td class="text-end"><strong>৳{{ number_format($order->total_amount, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Signature Space -->
        <div class="signature-section">
            <!-- Intentionally left blank for signature -->
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="text-center">
                <small><strong>Thank you for shopping with {{ \App\Models\Setting::get('site_name', 'Brightness Fashion') }}!</strong></small>
            </div>
        </div>
    </div>

    <!-- Print JavaScript -->
    <script>
        window.onload = function() {
            if (window.location.search.includes('print=1')) {
                setTimeout(() => {
                    window.print();
                }, 500);
            }
        }
    </script>
</body>
</html>