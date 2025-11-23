<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - Brightness Fashion</title>

    <link href="{{ asset('admin-asset/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="{{ asset('admin-asset/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-asset/css/custom_admin.css') }}" rel="stylesheet">

    @stack('styles')

</head>

<body id="page-top">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="sidebar-brand-text mx-3 text-left">Brightness Fashion</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Product Management
            </div>

            <li class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProducts"
                    aria-expanded="true" aria-controls="collapseProducts">
                    <i class="fas fa-fw fa-box"></i>
                    <span>Products</span>
                </a>
                <div id="collapseProducts" class="collapse {{ request()->routeIs('admin.products.*') ? 'show' : '' }}" aria-labelledby="headingProducts" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Product Management:</h6>
                        <a class="collapse-item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">All Products</a>
                        <a class="collapse-item {{ request()->routeIs('admin.products.create') ? 'active' : '' }}" href="{{ route('admin.products.create') }}">Add New Product</a>
                    </div>
                </div>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-fw fa-tags"></i>
                    <span>Category</span></a>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.coupons.index') }}">
                    <i class="fas fa-fw fa-ticket-alt"></i>
                    <span>Coupons</span></a>
            </li>


            <li class="nav-item {{ request()->routeIs('admin.edit_home') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.edit_home') }}">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Home Page</span>
                </a> 
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Store Management
            </div>

            <li class="nav-item {{ request()->is('admin/stores*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.stores.index') }}">
                    <i class="fas fa-fw fa-store"></i>
                    <span>Stores</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-fw fa-shopping-bag"></i>
                    <span>Orders</span></a>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.customers.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Customers</span></a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Settings
            </div>

            <li class="nav-item {{ request()->routeIs('admin.settings.site') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.settings.site') }}">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Site Settings</span></a>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.settings.admin') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.settings.admin') }}">
                    <i class="fas fa-fw fa-user-shield"></i>
                    <span>Admin Settings</span></a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search position-relative">
                        <div class="input-group">
                            <input type="text" id="adminSearch" class="form-control bg-light border-0 small" 
                                placeholder="Search navigation..." aria-label="Search" aria-describedby="basic-addon2" 
                                autocomplete="off">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="searchBtn">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                        <div id="searchResults" class="position-absolute bg-white border rounded shadow-sm mt-1 w-100" 
                             style="top: 100%; z-index: 1000; display: none; max-height: 300px; overflow-y: auto;">
                        </div>
                    </form>

                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search position-relative">
                                    <div class="input-group">
                                        <input type="text" id="mobileAdminSearch" class="form-control bg-light border-0 small"
                                            placeholder="Search navigation..." aria-label="Search"
                                            aria-describedby="basic-addon2" autocomplete="off">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" id="mobileSearchBtn">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="mobileSearchResults" class="w-100 mt-2" style="display: none;">
                                    </div>
                                </form>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('admin-asset/img/undraw_profile.svg') }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('admin.settings.admin') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.settings.site') }}">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <div class="container-fluid">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @yield('content')

                </div>
                </div>
            </div>
        </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('admin-asset/vendor/jquery/jquery.min.js') }}"></script>
 
    <script src="{{ asset('admin-asset/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('admin-asset/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <script src="{{ asset('admin-asset/js/sb-admin-2.min.js') }}"></script>

    @stack('scripts')

    <script>
        // Admin Navigation Search Functionality
        $(document).ready(function() {
            // Function to extract navigation items from sidebar
            function getNavItemsFromSidebar() {
                let navItems = [];
                
                // Get main navigation links (direct nav links)
                $('#accordionSidebar .nav-item .nav-link').each(function() {
                    let $link = $(this);
                    let href = $link.attr('href');
                    let text = $link.find('span').text().trim();
                    
                    // Skip if it's a collapse trigger or has no href
                    if (href && href !== '#' && text) {
                        navItems.push({
                            name: text,
                            url: href,
                            element: $link
                        });
                    }
                });
                
                // Get collapse menu items (sub-navigation items)
                $('#accordionSidebar .collapse-item').each(function() {
                    let $link = $(this);
                    let href = $link.attr('href');
                    let text = $link.text().trim();
                    
                    if (href && text) {
                        navItems.push({
                            name: text,
                            url: href,
                            element: $link
                        });
                    }
                });
                
                return navItems;
            }

            // Search function
            function performSearch(query, resultsContainer, isMobile = false) {
                if (query.length < 1) {
                    $(resultsContainer).hide().empty();
                    return;
                }

                let navItems = getNavItemsFromSidebar();
                const searchText = query.toLowerCase();
                
                const filteredItems = navItems.filter(item => {
                    return item.name.toLowerCase().includes(searchText);
                });

                if (filteredItems.length > 0) {
                    let resultsHtml = '';
                    filteredItems.forEach(item => {
                        if (isMobile) {
                            resultsHtml += `
                                <div class="search-result-item p-2 mb-1 bg-light rounded">
                                    <a href="${item.url}" class="text-decoration-none text-dark d-block">
                                        <i class="fas fa-chevron-right mr-2"></i>${item.name}
                                    </a>
                                </div>
                            `;
                        } else {
                            resultsHtml += `
                                <a href="${item.url}" class="dropdown-item search-result-item">
                                    <i class="fas fa-chevron-right mr-2"></i>${item.name}
                                </a>
                            `;
                        }
                    });
                    $(resultsContainer).html(resultsHtml).show();
                } else {
                    const noResultsHtml = isMobile 
                        ? '<div class="p-2 text-muted"><small>No results found</small></div>'
                        : '<div class="dropdown-item text-muted"><small>No results found</small></div>';
                    $(resultsContainer).html(noResultsHtml).show();
                }
            }

            // Desktop search
            $('#adminSearch').on('input', function() {
                const query = $(this).val();
                performSearch(query, '#searchResults');
            });

            // Mobile search
            $('#mobileAdminSearch').on('input', function() {
                const query = $(this).val();
                performSearch(query, '#mobileSearchResults', true);
            });

            // Hide results when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.navbar-search').length) {
                    $('#searchResults, #mobileSearchResults').hide();
                }
            });

            // Clear search on escape key
            $('#adminSearch, #mobileAdminSearch').on('keyup', function(e) {
                if (e.keyCode === 27) { // Escape key
                    $(this).val('');
                    $('#searchResults, #mobileSearchResults').hide();
                }
            });

            // Handle search button clicks
            $('#searchBtn').on('click', function() {
                const query = $('#adminSearch').val();
                if (query.length > 0) {
                    performSearch(query, '#searchResults');
                }
            });

            $('#mobileSearchBtn').on('click', function() {
                const query = $('#mobileAdminSearch').val();
                if (query.length > 0) {
                    performSearch(query, '#mobileSearchResults', true);
                }
            });

            // Add some CSS for better styling
            $('<style>')
                .prop('type', 'text/css')
                .html(`
                    .search-result-item:hover {
                        background-color: #f8f9fa !important;
                    }
                    #searchResults .dropdown-item {
                        padding: 8px 15px;
                        font-size: 14px;
                    }
                    #searchResults .dropdown-item:hover {
                        background-color: #e9ecef;
                        color: #495057;
                    }
                    #mobileSearchResults .search-result-item:hover {
                        background-color: #e9ecef !important;
                    }
                    /* Active state for collapse items */
                    .collapse-item.active {
                        background-color: #4e73df !important;
                        color: #fff !important;
                        font-weight: 700;
                    }
                    .collapse-item.active:hover {
                        background-color: #2e59d9 !important;
                        color: #fff !important;
                    }
                `)
                .appendTo('head');
        });
    </script>

</body>

</html>