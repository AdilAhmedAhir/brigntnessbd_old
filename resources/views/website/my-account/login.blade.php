@extends('website.layouts.app')

@section('title', 'Login - Brightness Fashion | Sign In to Your Account')

@push('styles')
<style>
    /* Login Page Styles */
    .login-page {
        background: #f7f7f7;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .login-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        max-width: 900px;
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 600px;
    }

    .login-left {
        background: var(--primary-gradient);
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        text-align: center;
    }

    .login-brand {
        margin-bottom: 30px;
    }

    .brand-icon {
        width: 80px;
        height: 80px;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 32px;
        color: var(--text-primary);
        box-shadow: 0 4px 20px rgba(212, 175, 55, 0.3);
    }

    .brand-name {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .brand-tagline {
        font-size: 16px;
        opacity: 0.9;
        line-height: 1.6;
    }

    .login-features {
        list-style: none;
        padding: 0;
        margin: 30px 0 0 0;
    }

    .login-features li {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .login-features i {
        font-size: 16px;
        color: var(--golden-color);
        opacity: 0.9;
    }

    .login-right {
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .login-header h1 {
        color: var(--text-primary);
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .login-header p {
        color: #666;
        font-size: 16px;
        margin: 0;
    }

    .auth-tabs {
        display: flex;
        margin-bottom: 30px;
        background: #f8f9fa;
        border-radius: 10px;
        padding: 5px;
    }

    .auth-tab {
        flex: 1;
        padding: 12px 20px;
        text-align: center;
        border-radius: 8px;
        background: transparent;
        border: none;
        color: #666;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .auth-tab.active {
        background: var(--golden-gradient);
        color: var(--text-primary);
        box-shadow: 0 2px 10px rgba(212, 175, 55, 0.3);
        border: 2px solid var(--golden-color);
    }

    .auth-form {
        display: none;
    }

    .auth-form.active {
        display: block;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #fff;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--golden-color);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px 0;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--golden-color);
    }

    .checkbox-group label {
        color: #666;
        font-size: 14px;
        margin: 0;
        cursor: pointer;
    }

    .btn-auth {
        width: 100%;
        background: var(--golden-gradient);
        color: var(--text-primary);
        border: none;
        padding: 16px 20px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-auth:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
    }

    .btn-auth:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-auth.loading {
        position: relative;
    }

    .btn-auth.loading::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid transparent;
        border-top: 2px solid var(--golden-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .error-message {
        background: #f8d7da;
        color: #721c24;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        border-left: 4px solid #dc3545;
    }

    .success-message {
        background: rgba(212, 175, 55, 0.1);
        color: var(--golden-dark);
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        border-left: 4px solid var(--golden-color);
    }

    .forgot-password {
        text-align: center;
        margin-top: 20px;
    }

    .forgot-password a {
        color: var(--golden-color);
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
    }

    .forgot-password a:hover {
        text-decoration: underline;
    }

    /* Animations */
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .login-container {
            grid-template-columns: 1fr;
            max-width: 500px;
            margin: 20px;
        }
        
        .login-left {
            padding: 40px 30px;
        }
        
        .login-right {
            padding: 40px 30px;
        }
        
        .login-header h1 {
            font-size: 26px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .brand-name {
            font-size: 20px;
        }
        
        .brand-icon {
            width: 60px;
            height: 60px;
            font-size: 24px;
        }
    }

    @media (max-width: 480px) {
        .login-page {
            padding: 20px 10px;
        }
        
        .login-left,
        .login-right {
            padding: 30px 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="login-page">
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="login-left">
            <div class="login-brand">
                <div class="brand-icon">
                    @if($siteSettings['site_icon_url'])
                        <img src="{{ $siteSettings['site_icon_url'] }}" alt="{{ $siteSettings['site_name'] }}" style="width: 50px; height: 50px; object-fit: contain;">
                    @else
                        <i class="fas fa-spa"></i>
                    @endif
                </div>
                <div class="brand-name">{{ $siteSettings['site_name'] }}</div>
                <div class="brand-tagline">{{ $siteSettings['site_description'] }}</div>
            </div>
            
            <ul class="login-features">
                <li>
                    <i class="fas fa-check-circle"></i>
                    <span>Faster checkout process</span>
                </li>
                <li>
                    <i class="fas fa-history"></i>
                    <span>Track your order history</span>
                </li>
                <li>
                    <i class="fas fa-heart"></i>
                    <span>Save your favorite items</span>
                </li>
                <li>
                    <i class="fas fa-gift"></i>
                    <span>Exclusive member offers</span>
                </li>
            </ul>
        </div>

        <!-- Right Side - Forms -->
        <div class="login-right">
            <div class="login-header">
                <h1>Welcome</h1>
                <p>Sign in to your account or create a new one</p>
            </div>

            <!-- Auth Tabs -->
            <div class="auth-tabs">
                <button type="button" class="auth-tab active" data-tab="login">Sign In</button>
                <button type="button" class="auth-tab" data-tab="register">Sign Up</button>
            </div>

            <!-- Messages -->
            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        • {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Login Form -->
            <form id="loginForm" class="auth-form active" method="POST" action="{{ route('account.login.post') }}">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ request('redirect_to', route('account.dashboard')) }}">
                
                <div class="form-group">
                    <label for="login_email" class="form-label">Email Address</label>
                    <input type="email" id="login_email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                
                <div class="form-group">
                    <label for="login_password" class="form-label">Password</label>
                    <input type="password" id="login_password" name="password" class="form-control" required>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Remember me</label>
                </div>
                
                <button type="submit" class="btn-auth">Sign In</button>
            </form>

            <!-- Register Form -->
            <form id="registerForm" class="auth-form" method="POST" action="{{ route('account.register') }}">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="register_name" class="form-label">Full Name</label>
                        <input type="text" id="register_name" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="register_phone" class="form-label">Phone Number</label>
                        <input type="tel" id="register_phone" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="register_email" class="form-label">Email Address</label>
                    <input type="email" id="register_email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="register_password" class="form-label">Password</label>
                        <input type="password" id="register_password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="register_password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" id="register_password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                
                <button type="submit" class="btn-auth">Create Account</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching only
    const tabButtons = document.querySelectorAll('.auth-tab');
    const authForms = document.querySelectorAll('.auth-form');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Update active tab
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Update active form
            authForms.forEach(form => form.classList.remove('active'));
            document.getElementById(targetTab + 'Form').classList.add('active');
        });
    });
});
</script>
@endpush