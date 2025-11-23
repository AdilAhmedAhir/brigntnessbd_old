@extends('website.layouts.app')

@section('title', 'Shipping & Returns - ' . $siteSettings['site_name'])

@section('content')
<div class="static-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1>Shipping & Returns</h1>
            <p>Everything you need to know about our shipping policy and return process</p>
        </div>
    </div>

    <!-- Page Content -->
    <div class="container">
        <div class="page-content">
            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-shipping-fast"></i>
                    Shipping Information
                </div>
                
                <div class="content-card">
                    <h3>Delivery Options</h3>
                    <ul>
                        <li><strong>Standard Delivery:</strong> 3-5 business days - Free for orders over ৳2,000</li>
                        <li><strong>Express Delivery:</strong> 1-2 business days - ৳150 delivery charge</li>
                        <li><strong>Same Day Delivery:</strong> Available in Dhaka city - ৳200 delivery charge</li>
                    </ul>
                </div>

                <div class="content-card">
                    <h3>Shipping Locations</h3>
                    <p>We deliver nationwide across Bangladesh. Delivery times may vary for remote areas.</p>
                    <ul>
                        <li>Dhaka & surrounding areas: 1-2 business days</li>
                        <li>Major cities (Chittagong, Sylhet, Rajshahi): 2-3 business days</li>
                        <li>Other locations: 3-5 business days</li>
                    </ul>
                </div>

                <div class="content-card">
                    <h3>Order Processing</h3>
                    <p>Orders are processed Monday through Saturday. Orders placed after 6:00 PM on weekdays or on weekends will be processed the next business day.</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-undo"></i>
                    Returns & Exchanges
                </div>
                
                <div class="content-card">
                    <h3>Return Policy</h3>
                    <p>We want you to be completely satisfied with your purchase. If you're not happy with your order, you can return it within <strong>7 days</strong> of delivery.</p>
                    
                    <h4>Return Conditions:</h4>
                    <ul>
                        <li>Items must be in original condition with tags attached</li>
                        <li>Items must be unworn and unwashed</li>
                        <li>Original packaging must be included</li>
                        <li>Return request must be initiated within 7 days of delivery</li>
                    </ul>
                </div>

                <div class="content-card">
                    <h3>How to Return</h3>
                    <ol>
                        <li>Contact our customer service at {{ $siteSettings['footer_email'] ?? 'info@brightnessbd.com' }}</li>
                        <li>Provide your order number and reason for return</li>
                        <li>Package the item securely with all original materials</li>
                        <li>Our delivery partner will collect the item from your location</li>
                        <li>Refund will be processed within 3-5 business days after inspection</li>
                    </ol>
                </div>

                <div class="content-card">
                    <h3>Exchange Policy</h3>
                    <p>We offer size exchanges for the same product within 7 days of delivery, subject to availability.</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-credit-card"></i>
                    Refunds
                </div>
                
                <div class="content-card">
                    <h3>Refund Process</h3>
                    <ul>
                        <li>Refunds are processed to the original payment method</li>
                        <li>Cash on delivery orders will receive bank transfer refunds</li>
                        <li>Refund processing time: 3-5 business days</li>
                        <li>Return shipping costs are covered by us for defective items</li>
                        <li>Customer is responsible for return shipping costs for change of mind returns</li>
                    </ul>
                </div>

                <div class="content-card">
                    <h3>Non-Returnable Items</h3>
                    <p>The following items cannot be returned for hygiene and safety reasons:</p>
                    <ul>
                        <li>Undergarments and intimate apparel</li>
                        <li>Swimwear</li>
                        <li>Items marked as "Final Sale"</li>
                        <li>Customized or personalized items</li>
                    </ul>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-question-circle"></i>
                    Need Help?
                </div>
                
                <div class="content-card">
                    <p>If you have any questions about shipping or returns, please don't hesitate to contact us:</p>
                    <ul class="contact-list">
                        @if($siteSettings['footer_phone'])
                            <li><i class="fas fa-phone"></i> <a href="tel:{{ $siteSettings['footer_phone'] }}">{{ $siteSettings['footer_phone'] }}</a></li>
                        @endif
                        @if($siteSettings['footer_email'])
                            <li><i class="fas fa-envelope"></i> <a href="mailto:{{ $siteSettings['footer_email'] }}">{{ $siteSettings['footer_email'] }}</a></li>
                        @endif
                        @if($siteSettings['footer_hours'])
                            <li><i class="fas fa-clock"></i> {{ $siteSettings['footer_hours'] }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.static-page {
    padding: 0;
}

/* Page Header */
.page-header {
    background: var(--primary-gradient);
    color: white;
    padding: 60px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
}

.page-header .container {
    position: relative;
    z-index: 2;
}

.page-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    background: var(--golden-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-header p {
    font-size: 1.2rem;
    opacity: 0.9;
    margin: 0;
}

/* Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Page Content */
.page-content {
    padding: 60px 0;
    max-width: 800px;
    margin: 0 auto;
}

.content-section {
    margin-bottom: 50px;
}

/* Section Titles */
.section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--golden-color);
}

.section-title i {
    color: var(--golden-color);
}

/* Content Cards */
.content-card {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid #f0f0f0;
    margin-bottom: 25px;
}

.content-card h3 {
    color: var(--primary-color);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.content-card h4 {
    color: var(--primary-color);
    font-size: 1.1rem;
    font-weight: 600;
    margin: 20px 0 10px 0;
}

.content-card p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 15px;
}

.content-card ul,
.content-card ol {
    color: #666;
    line-height: 1.6;
    padding-left: 20px;
}

.content-card li {
    margin-bottom: 8px;
}

.content-card strong {
    color: var(--primary-color);
}

/* Contact List */
.contact-list {
    list-style: none;
    padding: 0;
}

.contact-list li {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    color: #666;
}

.contact-list i {
    color: var(--golden-color);
    width: 20px;
}

.contact-list a {
    color: var(--golden-color);
    text-decoration: none;
    transition: color 0.3s ease;
}

.contact-list a:hover {
    color: var(--golden-dark);
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header {
        padding: 40px 0;
    }
    
    .page-header h1 {
        font-size: 2.5rem;
    }
    
    .page-content {
        padding: 40px 0;
    }
    
    .content-card {
        padding: 20px;
    }
    
    .section-title {
        font-size: 1.3rem;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .page-header p {
        font-size: 1rem;
    }
    
    .content-card {
        padding: 15px;
    }
}
</style>
@endpush
