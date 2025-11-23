@extends('website.layouts.app')

@section('title', 'Privacy Policy - ' . $siteSettings['site_name'])

@section('content')
<div class="static-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1>Privacy Policy</h1>
            <p>How we collect, use, and protect your personal information</p>
        </div>
    </div>

    <!-- Page Content -->
    <div class="container">
        <div class="page-content">
            <div class="content-section">
                <div class="content-card">
                    <p><strong>Last Updated:</strong> September 11, 2025</p>
                    <p>{{ $siteSettings['site_name'] ?? 'Brightness Fashion' }} ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website and use our services.</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Information We Collect
                </div>
                
                <div class="content-card">
                    <h3>Personal Information</h3>
                    <p>We may collect the following personal information when you:</p>
                    <ul>
                        <li>Create an account on our website</li>
                        <li>Make a purchase</li>
                        <li>Subscribe to our newsletter</li>
                        <li>Contact us through our contact form</li>
                        <li>Participate in surveys or promotions</li>
                    </ul>
                    
                    <h4>This information may include:</h4>
                    <ul>
                        <li>Name and contact information (email, phone number, address)</li>
                        <li>Payment information (processed securely through third-party providers)</li>
                        <li>Order history and preferences</li>
                        <li>Communication preferences</li>
                    </ul>
                </div>

                <div class="content-card">
                    <h3>Automatically Collected Information</h3>
                    <p>When you visit our website, we may automatically collect:</p>
                    <ul>
                        <li>IP address and browser information</li>
                        <li>Device and operating system information</li>
                        <li>Pages visited and time spent on our site</li>
                        <li>Referring website information</li>
                        <li>Cookies and similar tracking technologies</li>
                    </ul>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-cogs"></i>
                    How We Use Your Information
                </div>
                
                <div class="content-card">
                    <h3>We use your information to:</h3>
                    <ul>
                        <li>Process and fulfill your orders</li>
                        <li>Communicate with you about your orders and account</li>
                        <li>Provide customer support and respond to inquiries</li>
                        <li>Send promotional emails and newsletters (with your consent)</li>
                        <li>Improve our website and services</li>
                        <li>Prevent fraud and ensure security</li>
                        <li>Comply with legal obligations</li>
                        <li>Analyze website usage and customer behavior</li>
                    </ul>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-share-alt"></i>
                    Information Sharing and Disclosure
                </div>
                
                <div class="content-card">
                    <h3>We may share your information with:</h3>
                    <ul>
                        <li><strong>Service Providers:</strong> Third-party companies that help us operate our business (payment processors, shipping companies, email service providers)</li>
                        <li><strong>Legal Compliance:</strong> When required by law or to protect our rights and safety</li>
                        <li><strong>Business Transfers:</strong> In connection with a merger, acquisition, or sale of assets</li>
                        <li><strong>With Your Consent:</strong> Any other sharing with your explicit permission</li>
                    </ul>
                    
                    <p><strong>We do not sell, rent, or trade your personal information to third parties for marketing purposes.</strong></p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-cookie-bite"></i>
                    Cookies and Tracking
                </div>
                
                <div class="content-card">
                    <h3>We use cookies to:</h3>
                    <ul>
                        <li>Remember your preferences and settings</li>
                        <li>Keep you logged in to your account</li>
                        <li>Analyze website traffic and usage patterns</li>
                        <li>Provide personalized content and recommendations</li>
                        <li>Enable shopping cart functionality</li>
                    </ul>
                    
                    <p>You can control cookies through your browser settings, but disabling them may affect website functionality.</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-shield-alt"></i>
                    Data Security
                </div>
                
                <div class="content-card">
                    <h3>We protect your information through:</h3>
                    <ul>
                        <li>SSL encryption for data transmission</li>
                        <li>Secure payment processing systems</li>
                        <li>Regular security assessments and updates</li>
                        <li>Limited access to personal information</li>
                        <li>Employee training on data protection</li>
                    </ul>
                    
                    <p>While we strive to protect your information, no method of transmission over the internet is 100% secure. We cannot guarantee absolute security but will notify you of any significant data breaches as required by law.</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-user-check"></i>
                    Your Rights and Choices
                </div>
                
                <div class="content-card">
                    <h3>You have the right to:</h3>
                    <ul>
                        <li><strong>Access:</strong> Request a copy of your personal information</li>
                        <li><strong>Update:</strong> Correct or update your account information</li>
                        <li><strong>Delete:</strong> Request deletion of your personal information</li>
                        <li><strong>Opt-out:</strong> Unsubscribe from marketing communications</li>
                        <li><strong>Restrict:</strong> Limit how we use your information</li>
                        <li><strong>Portability:</strong> Request your data in a portable format</li>
                    </ul>
                    
                    <p>To exercise these rights, please contact us at {{ $siteSettings['footer_email'] ?? 'privacy@brightnessbd.com' }}</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-child"></i>
                    Children's Privacy
                </div>
                
                <div class="content-card">
                    <p>Our services are not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If we discover that we have collected information from a child under 13, we will delete it immediately.</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-edit"></i>
                    Changes to This Policy
                </div>
                
                <div class="content-card">
                    <p>We may update this Privacy Policy from time to time. We will notify you of any material changes by posting the new policy on our website with an updated "Last Updated" date. Your continued use of our services after such changes constitutes acceptance of the updated policy.</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-envelope"></i>
                    Contact Us
                </div>
                
                <div class="content-card">
                    <p>If you have any questions about this Privacy Policy or our data practices, please contact us:</p>
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
