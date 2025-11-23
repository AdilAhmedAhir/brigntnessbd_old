@extends('admin.layouts.app')

@section('title', 'Admin Settings')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Admin Settings</h1>
</div>

<!-- Admin Settings -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Admin Account Settings</h6>
            </div>
            <div class="card-body">

                <form action="{{ route('admin.settings.admin.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Admin Name</label>
                        <input type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $admin->name ?? '') }}" 
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Admin Email</label>
                        <input type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', $admin->email ?? '') }}" 
                            required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr class="my-4">
                    <h6 class="text-muted mb-3">Change Password (Optional)</h6>
                    
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" 
                            class="form-control @error('current_password') is-invalid @enderror" 
                            id="current_password" 
                            name="current_password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Required only if you want to change your password.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" 
                            class="form-control @error('new_password') is-invalid @enderror" 
                            id="new_password" 
                            name="new_password">
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Password must be at least 8 characters with uppercase, lowercase, numbers, and symbols.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input type="password" 
                            class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                            id="new_password_confirmation" 
                            name="new_password_confirmation">
                        @error('new_password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Update Admin Settings
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Password strength indicator
    $('#new_password').on('input', function() {
        const password = $(this).val();
        const strength = checkPasswordStrength(password);
        updatePasswordStrengthIndicator(strength);
    });

    // Confirm password validation
    $('#new_password_confirmation').on('input', function() {
        const password = $('#new_password').val();
        const confirmPassword = $(this).val();
        
        if (confirmPassword && password !== confirmPassword) {
            $(this).addClass('is-invalid');
            if (!$(this).siblings('.invalid-feedback.custom').length) {
                $(this).after('<div class="invalid-feedback custom">Passwords do not match.</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback.custom').remove();
        }
    });

    // Form validation before submit
    $('form').on('submit', function(e) {
        const currentPassword = $('#current_password').val();
        const newPassword = $('#new_password').val();
        const confirmPassword = $('#new_password_confirmation').val();

        // If any password field is filled, all should be filled
        if (currentPassword || newPassword || confirmPassword) {
            if (!currentPassword) {
                e.preventDefault();
                $('#current_password').addClass('is-invalid');
                if (!$('#current_password').siblings('.invalid-feedback.custom').length) {
                    $('#current_password').after('<div class="invalid-feedback custom">Current password is required when changing password.</div>');
                }
                return false;
            }
            
            if (!newPassword) {
                e.preventDefault();
                $('#new_password').addClass('is-invalid');
                if (!$('#new_password').siblings('.invalid-feedback.custom').length) {
                    $('#new_password').after('<div class="invalid-feedback custom">New password is required.</div>');
                }
                return false;
            }
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                $('#new_password_confirmation').addClass('is-invalid');
                if (!$('#new_password_confirmation').siblings('.invalid-feedback.custom').length) {
                    $('#new_password_confirmation').after('<div class="invalid-feedback custom">Passwords do not match.</div>');
                }
                return false;
            }
        }
    });

    function checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        return strength;
    }

    function updatePasswordStrengthIndicator(strength) {
        const indicator = $('#password-strength-indicator');
        
        // Remove existing indicator
        indicator.remove();
        
        if ($('#new_password').val().length > 0) {
            let strengthText = '';
            let strengthClass = '';
            
            switch(strength) {
                case 0:
                case 1:
                    strengthText = 'Very Weak';
                    strengthClass = 'text-danger';
                    break;
                case 2:
                    strengthText = 'Weak';
                    strengthClass = 'text-warning';
                    break;
                case 3:
                    strengthText = 'Fair';
                    strengthClass = 'text-info';
                    break;
                case 4:
                    strengthText = 'Good';
                    strengthClass = 'text-primary';
                    break;
                case 5:
                    strengthText = 'Strong';
                    strengthClass = 'text-success';
                    break;
            }
            
            $('#new_password').after(`<small id="password-strength-indicator" class="form-text ${strengthClass}">Password strength: ${strengthText}</small>`);
        }
    }

    // Clear custom error messages when user starts typing
    $('input').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback.custom').remove();
    });
});
</script>
@endpush
