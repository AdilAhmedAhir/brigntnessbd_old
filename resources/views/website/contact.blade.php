@extends('website.layouts.app')

@section('title', 'Contact Us - ' . $siteSettings['site_name'])

@section('content')
<div class="contact-page">
    <!-- Contact Header -->
    <div class="contact-header">
        <div class="container">
            <h1>Contact Us</h1>
            <p>Get in touch with us - we'd love to hear from you</p>
        </div>
    </div>

    <!-- Contact Content -->
    <div class="container">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="contact-content">
            <!-- Contact Form Section -->
            <div class="contact-form-section">
                <div class="section-title">
                    <i class="fas fa-envelope"></i>
                    Send us a Message
                </div>
                
                <form method="POST" action="{{ route('contact.send') }}" class="contact-form">
                    @csrf
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="{{ old('name') }}" placeholder="Your full name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="{{ old('email') }}" placeholder="your@email.com" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-control" 
                                   value="{{ old('phone') }}" placeholder="Your phone number">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject" class="form-label">Subject *</label>
                            <input type="text" id="subject" name="subject" class="form-control" 
                                   value="{{ old('subject') }}" placeholder="Message subject" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="message" class="form-label">Message *</label>
                        <textarea id="message" name="message" class="form-control" rows="6" 
                                  placeholder="Tell us how we can help you..." required>{{ old('message') }}</textarea>
                    </div>
                    
                    <div class="form-submit-section">
                        <button type="submit" class="btn-send-message">
                            <i class="fas fa-paper-plane"></i>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>

            <!-- Contact Information Section -->
            <div class="contact-info-section">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Contact Information
                </div>
                
                <div class="contact-info-cards">
                    @if($contactSettings['contact_address'])
                        <div class="contact-info-card">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h3>Address</h3>
                                <p>{{ $contactSettings['contact_address'] }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($contactSettings['contact_phone'])
                        <div class="contact-info-card">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-details">
                                <h3>Phone</h3>
                                <p><a href="tel:{{ $contactSettings['contact_phone'] }}">{{ $contactSettings['contact_phone'] }}</a></p>
                            </div>
                        </div>
                    @endif
                    
                    @if($contactSettings['contact_email'])
                        <div class="contact-info-card">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h3>Email</h3>
                                <p><a href="mailto:{{ $contactSettings['contact_email'] }}">{{ $contactSettings['contact_email'] }}</a></p>
                            </div>
                        </div>
                    @endif
                    
                    @if($contactSettings['contact_hours'])
                        <div class="contact-info-card">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-details">
                                <h3>Business Hours</h3>
                                <p>{{ $contactSettings['contact_hours'] }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                @if($contactSettings['contact_description'])
                    <div class="contact-description">
                        <p>{{ $contactSettings['contact_description'] }}</p>
                    </div>
                @endif

                @if($contactSettings['contact_map_url'])
                    <div class="contact-map">
                        <div class="map-title">
                            <h3><i class="fas fa-map"></i> Find Us</h3>
                        </div>
                        <div class="map-container">
                            <iframe src="{{ $contactSettings['contact_map_url'] }}" 
                                    width="100%" height="300" style="border:0;" 
                                    allowfullscreen="" loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.contact-page {
    padding: 0;
}

/* Contact Header */
.contact-header {
    background: var(--primary-gradient);
    color: white;
    padding: 60px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.contact-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
}

.contact-header .container {
    position: relative;
    z-index: 2;
}

.contact-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    background: var(--golden-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.contact-header p {
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

/* Contact Content */
.contact-content {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 40px;
    padding: 60px 0;
}

/* Alerts */
.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
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

/* Form Styles */
.contact-form {
    background: #fff;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid #f0f0f0;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 25px;
}

.form-label {
    display: block;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.form-control {
    width: 100%;
    padding: 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #fff;
}

.form-control:focus {
    outline: none;
    border-color: var(--golden-color);
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}

.form-control::placeholder {
    color: #999;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

.form-submit-section {
    padding-top: 20px;
    border-top: 1px solid #f0f0f0;
}

.btn-send-message {
    background: var(--golden-gradient);
    color: white;
    padding: 15px 40px;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
}

.btn-send-message:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
}

/* Contact Info Section */
.contact-info-section {
    background: #fff;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid #f0f0f0;
    height: fit-content;
}

.contact-info-cards {
    display: flex;
    flex-direction: column;
    gap: 25px;
    margin-bottom: 30px;
}

.contact-info-card {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid var(--golden-color);
}

.contact-icon {
    width: 50px;
    height: 50px;
    background: var(--golden-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.contact-details h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 5px;
}

.contact-details p {
    margin: 0;
    color: #666;
    line-height: 1.5;
}

.contact-details a {
    color: var(--golden-color);
    text-decoration: none;
    transition: color 0.3s ease;
}

.contact-details a:hover {
    color: var(--golden-dark);
}

.contact-description {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    margin-bottom: 30px;
}

.contact-description p {
    margin: 0;
    color: #666;
    line-height: 1.6;
}

/* Map Section */
.contact-map {
    margin-top: 30px;
}

.map-title {
    margin-bottom: 15px;
}

.map-title h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--primary-color);
    display: flex;
    align-items: center;
    gap: 10px;
}

.map-title i {
    color: var(--golden-color);
}

.map-container {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Responsive Design */
@media (max-width: 992px) {
    .contact-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .contact-header h1 {
        font-size: 2.5rem;
    }
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .contact-form,
    .contact-info-section {
        padding: 25px;
    }
    
    .contact-header {
        padding: 40px 0;
    }
    
    .contact-header h1 {
        font-size: 2rem;
    }
    
    .contact-header p {
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }
    
    .contact-content {
        padding: 40px 0;
    }
    
    .form-row {
        gap: 0;
    }
    
    .contact-form,
    .contact-info-section {
        padding: 20px;
    }
    
    .btn-send-message {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush