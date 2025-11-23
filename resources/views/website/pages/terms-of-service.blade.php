@extends('website.layouts.app')

@section('title', 'Terms of Service - ' . $siteSettings['site_name'])

@section('content')
<div class="static-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1>Terms of Service</h1>
            <p>Terms and conditions for using our website and services</p>
        </div>
    </div>

    <!-- Page Content -->
    <div class="container">
        <div class="page-content">
            <div class="content-section">
                <div class="content-card">
                    <p><strong>Last Updated:</strong> September 11, 2025</p>
                    <p>Welcome to {{ $siteSettings['site_name'] ?? 'Brightness Fashion' }}. These Terms of Service ("Terms") govern your use of our website and services. By accessing or using our website, you agree to be bound by these Terms.</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-handshake"></i>
                    Acceptance of Terms
                </div>
                
                <div class="content-card">
                    <p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-user-shield"></i>
                    User Accounts
                </div>
                
                <div class="content-card">
                    <h3>Account Creation</h3>
                    <ul>
                        <li>You must provide accurate and complete information when creating an account</li>
                        <li>You are responsible for maintaining the confidentiality of your account credentials</li>
                        <li>You must be at least 18 years old to create an account</li>
                        <li>One person may not maintain more than one account</li>
                    </ul>
                    
                    <h3>Account Responsibilities</h3>
                    <ul>
                        <li>You are responsible for all activities that occur under your account</li>
                        <li>Notify us immediately of any unauthorized use of your account</li>
                        <li>Keep your contact information up to date</li>
                        <li>Use strong passwords and change them regularly</li>
                    </ul>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-shopping-cart"></i>
                    Orders and Payments
                </div>
                
                <div class="content-card">
                    <h3>Order Placement</h3>
                    <ul>
                        <li>All orders are subject to acceptance and availability</li>
                        <li>We reserve the right to refuse or cancel any order</li>
                        <li>Prices are subject to change without notice</li>
                        <li>All prices are listed in Bangladeshi Taka (BDT)</li>
                    </ul>
                    
                    <h3>Payment Terms</h3>
                    <ul>
                        <li>Payment is required at the time of order placement</li>
                        <li>We accept cash on delivery and mobile banking payments</li>
                        <li>All payments must be made in Bangladeshi Taka</li>
                        <li>Additional charges may apply for certain payment methods</li>
                    </ul>
                    
                    <h3>Order Confirmation</h3>
                    <p>Order confirmation does not guarantee product availability. We will notify you if any items are out of stock and offer suitable alternatives or refunds.</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-truck"></i>
                    Shipping and Delivery
                </div>
                
                <div class="content-card">
                    <ul>
                        <li>Delivery times are estimates and not guaranteed</li>
                        <li>Risk of loss passes to you upon delivery</li>
                        <li>You must inspect items upon delivery and report any issues immediately</li>
                        <li>We are not responsible for delays due to weather, natural disasters, or other circumstances beyond our control</li>
                        <li>Accurate delivery address information is your responsibility</li>
                    </ul>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-undo-alt"></i>
                    Returns and Refunds
                </div>
                
                <div class="content-card">
                    <ul>
                        <li>Returns must be initiated within 7 days of delivery</li>
                        <li>Items must be in original condition with tags attached</li>
                        <li>Return shipping costs may apply for non-defective items</li>
                        <li>Refunds will be processed within 3-5 business days after inspection</li>
                        <li>Some items may not be eligible for return (see our Returns Policy)</li>
                    </ul>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-ban"></i>
                    Prohibited Uses
                </div>
                
                <div class="content-card">
                    <h3>You may not use our website for:</h3>
                    <ul>
                        <li>Any unlawful purpose or to solicit others to perform unlawful acts</li>
                        <li>Violating any international, federal, provincial, or state regulations, rules, laws, or local ordinances</li>
                        <li>Infringing upon or violating our intellectual property rights or the intellectual property rights of others</li>
                        <li>Harassing, abusing, insulting, harming, defaming, slandering, disparaging, intimidating, or discriminating</li>
                        <li>Submitting false or misleading information</li>
                        <li>Uploading or transmitting viruses or any other type of malicious code</li>
                        <li>Collecting or tracking the personal information of others</li>
                        <li>Spamming, phishing, pharming, pretexting, spidering, crawling, or scraping</li>
                    </ul>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-copyright"></i>
                    Intellectual Property
                </div>
                
                <div class="content-card">
                    <h3>Our Rights</h3>
                    <ul>
                        <li>All content on this website is owned by {{ $siteSettings['site_name'] ?? 'Brightness Fashion' }} or our licensors</li>
                        <li>You may not reproduce, distribute, or create derivative works without permission</li>
                        <li>Our trademarks and trade names may not be used without permission</li>
                        <li>Product images and descriptions are for illustration purposes only</li>
                    </ul>
                    
                    <h3>Your Content</h3>
                    <ul>
                        <li>You retain ownership of content you submit (reviews, comments, etc.)</li>
                        <li>You grant us a license to use your content for business purposes</li>
                        <li>You are responsible for ensuring your content doesn't violate any rights</li>
                        <li>We reserve the right to remove any inappropriate content</li>
                    </ul>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Disclaimers and Limitations
                </div>
                
                <div class="content-card">
                    <h3>Disclaimer of Warranties</h3>
                    <p>Our website and services are provided "as is" without any warranties, expressed or implied. We do not guarantee that our website will be error-free or uninterrupted.</p>
                    
                    <h3>Limitation of Liability</h3>
                    <p>In no event shall {{ $siteSettings['site_name'] ?? 'Brightness Fashion' }} be liable for any indirect, incidental, special, consequential, or punitive damages arising out of your use of our website or services.</p>
                    
                    <h3>Product Information</h3>
                    <ul>
                        <li>We strive for accuracy but cannot guarantee all product information is error-free</li>
                        <li>Colors may appear differently on different devices</li>
                        <li>Actual products may vary slightly from images</li>
                        <li>Size charts are for guidance only</li>
                    </ul>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-gavel"></i>
                    Governing Law
                </div>
                
                <div class="content-card">
                    <p>These Terms shall be interpreted and governed in accordance with the laws of Bangladesh. Any disputes arising from these Terms or your use of our website shall be subject to the exclusive jurisdiction of the courts of Bangladesh.</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-edit"></i>
                    Modifications
                </div>
                
                <div class="content-card">
                    <p>We reserve the right to modify these Terms at any time. Changes will be effective immediately upon posting on our website. Your continued use of our website after any changes constitutes acceptance of the new Terms.</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-times-circle"></i>
                    Termination
                </div>
                
                <div class="content-card">
                    <p>We may terminate or suspend your account and access to our website at our sole discretion, without prior notice, for conduct that we believe violates these Terms or is harmful to other users, us, or third parties.</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-envelope"></i>
                    Contact Information
                </div>
                
                <div class="content-card">
                    <p>If you have any questions about these Terms of Service, please contact us:</p>
                    <ul class="contact-list">
                        @if($siteSettings['footer_email'])
                            <li><i class="fas fa-envelope"></i> <a href="mailto:{{ $siteSettings['footer_email'] }}">{{ $siteSettings['footer_email'] }}</a></li>
                        @endif
                        @if($siteSettings['footer_phone'])
                            <li><i class="fas fa-phone"></i> <a href="tel:{{ $siteSettings['footer_phone'] }}">{{ $siteSettings['footer_phone'] }}</a></li>
                        @endif
                        @if($siteSettings['footer_address'])
                            <li><i class="fas fa-map-marker-alt"></i> {{ $siteSettings['footer_address'] }}</li>
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
