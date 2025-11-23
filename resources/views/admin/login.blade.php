<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Login - {{ $siteSettings['site_name'] }}</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin-asset/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin-asset/css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image" style="background: url('{{ asset('admin-asset/img/login-banner.jpg') }}') center; background-size: cover;"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center mb-4">
                                        <!-- Site Icon and Name -->
                                        <div class="mb-3">
                                            @if($siteSettings['site_icon_url'])
                                                <img src="{{ $siteSettings['site_icon_url'] }}" alt="{{ $siteSettings['site_name'] }}" 
                                                     class="mb-3" style="width: 80px; height: 80px; object-fit: contain; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                            @else
                                                <div class="mb-3 d-inline-flex align-items-center justify-content-center" 
                                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                                    <i class="fas fa-spa text-white" style="font-size: 35px;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <h1 class="h3 text-gray-900 mb-2 font-weight-bold">{{ $siteSettings['site_name'] }}</h1>
                                        <p class="text-muted mb-1">{{ $siteSettings['site_description'] }}</p>
                                        <p class="mb-4 text-primary font-weight-bold">Admin Panel</p>
                                    </div>
                                    
                                    @if(session('error'))
                                        <div class="alert alert-danger" role="alert">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    @if($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form class="user" action="{{ route('admin.login.post') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="email" name="email" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address..." value="{{ old('email') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="password" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                                                <label class="custom-control-label" for="remember">Remember Me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('admin-asset/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-asset/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('admin-asset/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('admin-asset/js/sb-admin-2.min.js') }}"></script>

</body>

</html>
