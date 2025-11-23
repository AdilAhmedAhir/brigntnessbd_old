@extends('website.layouts.app')

@section('title', 'My Profile - Brightness Fashion | Account Settings')

@push('styles')
<link rel="stylesheet" href="{{ asset('site-asset/css/account.css') }}">
@endpush

@section('content')
<div class="account-page">
    <!-- Account Header -->
    <div class="account-header">
        <div class="container">
            <h1>My Profile</h1>
            <p>Manage your personal information and account settings</p>
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
                        <li><a href="{{ route('account.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a></li>
                        <li><a href="{{ route('account.orders') }}">
                            <i class="fas fa-shopping-bag"></i>
                            Orders
                        </a></li>
                        <li><a href="{{ route('account.profile') }}" class="active">
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
                <div class="section-title">Account Settings</div>

                <!-- Messages -->
                <div id="profileMessages"></div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="profile-form">
                    <!-- Personal Information -->
                    <form id="profileForm" class="form-section">
                        @csrf
                        <h3>
                            <i class="fas fa-user"></i>
                            Personal Information
                        </h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-control" value="{{ $user->phone }}" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="member_since" class="form-label">Member Since</label>
                            <input type="text" id="member_since" class="form-control" value="{{ $user->created_at->format('F d, Y') }}" disabled>
                        </div>
                        
                        <button type="submit" class="btn-update">
                            <i class="fas fa-save"></i>
                            Update Information
                        </button>
                    </form>

                    <!-- Password Change -->
                    <form id="passwordForm" class="form-section password-section">
                        @csrf
                        <h3>
                            <i class="fas fa-lock"></i>
                            Change Password
                        </h3>
                        
                        <div class="form-group">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="password-requirements">
                            <h5>Password Requirements:</h5>
                            <ul>
                                <li>At least 8 characters long</li>
                                <li>Mix of uppercase and lowercase letters</li>
                                <li>At least one number</li>
                                <li>At least one special character</li>
                            </ul>
                        </div>
                        
                        <button type="submit" class="btn-update">
                            <i class="fas fa-key"></i>
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile form submission
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        handleFormSubmit(this, 'profile', 'Updating profile...');
    });
    
    // Password form submission
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        handleFormSubmit(this, 'password', 'Updating password...');
    });
});

function handleFormSubmit(form, type, loadingText) {
    const submitBtn = form.querySelector('.btn-update');
    const formData = new FormData(form);
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.classList.add('loading');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = loadingText;
    
    // Clear previous messages
    clearMessages();
    
    // Submit form
    fetch('{{ route("account.profile.update") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message || 'Profile updated successfully!', 'success');
            
            // Clear password form if it was password update
            if (type === 'password') {
                form.reset();
            }
            
            // Update sidebar profile if name or email changed
            if (type === 'profile') {
                updateSidebarProfile(formData.get('name'), formData.get('email'));
            }
        } else {
            if (data.errors) {
                // Show validation errors
                let errorMessage = 'Please correct the following errors:<br>';
                Object.values(data.errors).forEach(errors => {
                    errors.forEach(error => {
                        errorMessage += '• ' + error + '<br>';
                    });
                });
                showMessage(errorMessage, 'error');
            } else {
                showMessage(data.message || 'Something went wrong. Please try again.', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Profile update error:', error);
        showMessage('Something went wrong. Please try again.', 'error');
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.classList.remove('loading');
        submitBtn.innerHTML = originalText;
    });
}

function updateSidebarProfile(name, email) {
    const profileName = document.querySelector('.profile-name');
    const profileEmail = document.querySelector('.profile-email');
    const profileAvatar = document.querySelector('.profile-avatar');
    
    if (profileName) profileName.textContent = name;
    if (profileEmail) profileEmail.textContent = email;
    if (profileAvatar) profileAvatar.textContent = name.charAt(0).toUpperCase();
}

function showMessage(message, type) {
    const messagesContainer = document.getElementById('profileMessages');
    const messageClass = type === 'success' ? 'alert-success' : 'alert-error';
    
    messagesContainer.innerHTML = `<div class="alert ${messageClass}">${message}</div>`;
    messagesContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    
    // Auto-hide success messages after 5 seconds
    if (type === 'success') {
        setTimeout(() => {
            messagesContainer.innerHTML = '';
        }, 5000);
    }
}

function clearMessages() {
    document.getElementById('profileMessages').innerHTML = '';
}
</script>
@endpush