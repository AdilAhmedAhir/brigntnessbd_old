@extends('admin.layouts.app')

@section('title', 'Site Settings')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Site Settings</h1>
</div>


<!-- Settings Form -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">General Settings</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.site.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group">
                        <label for="site_name">Site Name</label>
                        <input type="text" class="form-control @error('site_name') is-invalid @enderror" 
                               id="site_name" name="site_name" 
                               value="{{ old('site_name', $settings['site_name']) }}" required>
                        @error('site_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="site_description">Site Description</label>
                        <textarea class="form-control @error('site_description') is-invalid @enderror" 
                                  id="site_description" name="site_description" rows="3" required>{{ old('site_description', $settings['site_description']) }}</textarea>
                        @error('site_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="site_icon">Site Icon</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('site_icon') is-invalid @enderror" 
                                   id="site_icon" name="site_icon" accept="image/*">
                            <label class="custom-file-label" for="site_icon">Choose file...</label>
                        </div>
                        <small class="form-text text-muted">
                            Accepted formats: JPEG, PNG, JPG, GIF, SVG, ICO. Maximum size: 2MB
                        </small>
                        @error('site_icon')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        
                        @if($settings['site_icon'])
                            <div class="mt-3">
                                <label class="form-label">Current Icon:</label>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('uploads/website/' . $settings['site_icon']) }}" 
                                         alt="Site Icon" class="img-thumbnail" style="max-width: 64px; max-height: 64px;">
                                    <span class="ml-2 text-muted">{{ $settings['site_icon'] }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Footer Settings Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Footer Settings</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.site.update') }}" method="POST">
                    @csrf
                    
                    <!-- Hidden fields for site settings to maintain them -->
                    <input type="hidden" name="site_name" value="{{ $settings['site_name'] }}">
                    <input type="hidden" name="site_description" value="{{ $settings['site_description'] }}">
                    
                    <div class="form-group">
                        <label for="footer_description">Footer Description</label>
                        <textarea class="form-control @error('footer_description') is-invalid @enderror" id="footer_description" name="footer_description" rows="4" required>{{ old('footer_description', $settings['footer_description']) }}</textarea>
                        @error('footer_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer_facebook">Facebook URL</label>
                                <input type="url" class="form-control @error('footer_facebook') is-invalid @enderror" 
                                       id="footer_facebook" name="footer_facebook" 
                                       value="{{ old('footer_facebook', $settings['footer_facebook']) }}" 
                                       placeholder="https://facebook.com/yourpage">
                                @error('footer_facebook')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer_instagram">Instagram URL</label>
                                <input type="url" class="form-control @error('footer_instagram') is-invalid @enderror" 
                                       id="footer_instagram" name="footer_instagram" 
                                       value="{{ old('footer_instagram', $settings['footer_instagram']) }}" 
                                       placeholder="https://instagram.com/yourprofile">
                                @error('footer_instagram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer_twitter">Twitter URL</label>
                                <input type="url" class="form-control @error('footer_twitter') is-invalid @enderror" 
                                       id="footer_twitter" name="footer_twitter" 
                                       value="{{ old('footer_twitter', $settings['footer_twitter']) }}" 
                                       placeholder="https://twitter.com/yourprofile">
                                @error('footer_twitter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer_pinterest">Pinterest URL</label>
                                <input type="url" class="form-control @error('footer_pinterest') is-invalid @enderror" 
                                       id="footer_pinterest" name="footer_pinterest" 
                                       value="{{ old('footer_pinterest', $settings['footer_pinterest']) }}" 
                                       placeholder="https://pinterest.com/yourprofile">
                                @error('footer_pinterest')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="footer_address">Address</label>
                        <textarea class="form-control @error('footer_address') is-invalid @enderror" 
                                  id="footer_address" name="footer_address" rows="2" required>{{ old('footer_address', $settings['footer_address']) }}</textarea>
                        @error('footer_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer_phone">Phone</label>
                                <input type="text" class="form-control @error('footer_phone') is-invalid @enderror" 
                                       id="footer_phone" name="footer_phone" 
                                       value="{{ old('footer_phone', $settings['footer_phone']) }}" required>
                                @error('footer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer_email">Email</label>
                                <input type="email" class="form-control @error('footer_email') is-invalid @enderror" 
                                       id="footer_email" name="footer_email" 
                                       value="{{ old('footer_email', $settings['footer_email']) }}" required>
                                @error('footer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer_hours">Business Hours</label>
                                <input type="text" class="form-control @error('footer_hours') is-invalid @enderror" 
                                       id="footer_hours" name="footer_hours" 
                                       value="{{ old('footer_hours', $settings['footer_hours']) }}" 
                                       placeholder="Mon-Sat: 10AM-8PM" required>
                                @error('footer_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer_copyright">Copyright Text</label>
                                <input type="text" class="form-control @error('footer_copyright') is-invalid @enderror" 
                                       id="footer_copyright" name="footer_copyright" 
                                       value="{{ old('footer_copyright', $settings['footer_copyright']) }}" required>
                                @error('footer_copyright')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Footer Settings
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Contact Page Settings Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Contact Page Settings</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.site.update') }}" method="POST">
                    @csrf
                    
                    <!-- Hidden fields for site and footer settings to maintain them -->
                    <input type="hidden" name="site_name" value="{{ $settings['site_name'] }}">
                    <input type="hidden" name="site_description" value="{{ $settings['site_description'] }}">
                    <input type="hidden" name="footer_description" value="{{ $settings['footer_description'] }}">
                    <input type="hidden" name="footer_facebook" value="{{ $settings['footer_facebook'] }}">
                    <input type="hidden" name="footer_instagram" value="{{ $settings['footer_instagram'] }}">
                    <input type="hidden" name="footer_twitter" value="{{ $settings['footer_twitter'] }}">
                    <input type="hidden" name="footer_pinterest" value="{{ $settings['footer_pinterest'] }}">
                    <input type="hidden" name="footer_address" value="{{ $settings['footer_address'] }}">
                    <input type="hidden" name="footer_phone" value="{{ $settings['footer_phone'] }}">
                    <input type="hidden" name="footer_email" value="{{ $settings['footer_email'] }}">
                    <input type="hidden" name="footer_hours" value="{{ $settings['footer_hours'] }}">
                    <input type="hidden" name="footer_copyright" value="{{ $settings['footer_copyright'] }}">
                    
                    <div class="form-group">
                        <label for="contact_description">Contact Page Description</label>
                        <textarea class="form-control @error('contact_description') is-invalid @enderror" id="contact_description" name="contact_description" rows="3" placeholder="Describe your business or add a welcome message for the contact page...">{{ old('contact_description', $settings['contact_description']) }}</textarea>
                        @error('contact_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_admin_email">Admin Email (for contact form submissions)</label>
                        <input type="email" class="form-control @error('contact_admin_email') is-invalid @enderror" 
                               id="contact_admin_email" name="contact_admin_email" 
                               value="{{ old('contact_admin_email', $settings['contact_admin_email']) }}" placeholder="admin@yoursite.com">
                        <small class="form-text text-muted">Contact form messages will be sent to this email address. Leave empty to use footer email.</small>
                        @error('contact_admin_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_map_url">Google Maps Embed URL</label>
                        <input type="url" class="form-control @error('contact_map_url') is-invalid @enderror" 
                               id="contact_map_url" name="contact_map_url" 
                               value="{{ old('contact_map_url', $settings['contact_map_url']) }}" placeholder="https://www.google.com/maps/embed?pb=...">
                        <small class="form-text text-muted">Get the embed URL from Google Maps: Share → Embed a map → Copy HTML → Extract the src URL</small>
                        @error('contact_map_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Note:</strong> Contact address, phone, email, and business hours are shared with footer settings above.
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Contact Settings
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Preview</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    @if($settings['site_icon'])
                        <img src="{{ asset('uploads/website/' . $settings['site_icon']) }}" 
                             alt="Site Icon" class="img-thumbnail mb-3" style="max-width: 100px; max-height: 100px;">
                    @else
                        <div class="bg-light border rounded mb-3 d-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px; margin: 0 auto;">
                            <i class="fas fa-image text-muted fa-2x"></i>
                        </div>
                    @endif
                    <h5>{{ $settings['site_name'] }}</h5>
                    <p class="text-muted">{{ $settings['site_description'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update file input label when file is selected
document.getElementById('site_icon').addEventListener('change', function(e) {
    var fileName = e.target.files[0].name;
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});
</script>

@endsection
