<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title') | {{ env('APP_NAME', 'ERP CWA') }} </title>

    <link rel="shortcut icon" href="{{ asset('custom/assets/compiled/svg/favicon2.svg') }}" type="image/x-icon">
    <style>
      @media (min-width: 1200px) {
        html {
          font-size: 14px;
        }
      }
      @media (max-width: 1199.98px) {
        html {
          font-size: 16px;
        }
      }
    </style>
    @stack('before-style')
    {{-- DataTables & Themes --}}
    <link rel="stylesheet" href="{{ asset('custom/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/assets/compiled/css/table-datatable-jquery.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/assets/extensions/datatables.net-bs5/css/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/assets/extensions/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/assets/compiled/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/assets/extensions/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/assets/extensions/jquery-confirm/jquery-confirm.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/assets/extensions/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/assets/extensions/flatpickr/flatpickr.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .sidebar-item .submenu .sidebar-link::after {
            display: none !important;
            content: none !important;
        }
        #main {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        #main .main-content {
            flex: 1;
        }
        #main footer {
            margin-top: auto;
        }
    </style>
    @stack('after-style')
</head>

<body>
    <script src="{{ asset('custom/assets/static/js/initTheme.js') }}"></script>
    <div id="app">

        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="{{ url('/') }}" class="d-flex align-items-center gap-3 text-decoration-none">
                                <img src="{{ asset('logo.png') }}" alt="Logo ERP CWA" class="sidebar-brand-icon" style="height:48px; width:auto; object-fit:contain;">
                                <h4 class="sidebar-brand-title mb-0" style="font-size:18px; font-weight:600;">ERP <span style="color:#FF6B6B;">CWA</span></h4>
                            </a>
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    @include('layouts.sidebar')
                </div>
            </div>
        </div>

        <div id="main" class="px-4 py-3">
            <header class="hz-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <a href="#" class="burger-btn d-inline-block d-xl-none me-2">
                            <i class="bi bi-justify fs-3"></i>
                        </a>
                        <div class="hz-breadcrumb">
                            Pages / <span>@yield('title', 'Main Dashboard')</span>
                        </div>
                        <h1 class="hz-page-title">@yield('title', 'Main Dashboard')</h1>
                    </div>

                    <div class="hz-topbar-pill">
                        <div class="hz-search-wrapper">
                            <i class="bi bi-search text-muted"></i>
                            <input type="text" placeholder="Search..." />
                        </div>

                        <button type="button" class="hz-icon-btn" title="Notifications">
                            <i class="bi bi-bell"></i>
                        </button>
                        <button type="button" class="hz-icon-btn" title="Information">
                            <i class="bi bi-info-circle"></i>
                        </button>
                        <button type="button" class="hz-icon-btn" id="hz-theme-toggle" title="Toggle Theme">
                            <i class="bi bi-moon"></i>
                        </button>

                        <div class="dropdown d-inline">
                            <a href="#" id="topbarUserDropdown" data-bs-toggle="dropdown" aria-expanded="false" class="d-flex align-items-center">
                                <img src="{{ asset('custom/assets/compiled/png/avatar1.png') }}" alt="Avatar" class="hz-avatar">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 border-0" aria-labelledby="topbarUserDropdown">
                                <li>
                                    <div class="px-3 py-2">
                                        <div class="fw-bold">{{ Auth::user()->username ?? 'User' }}</div>
                                        <small class="text-muted">{{ optional(Auth::user()->roles)->role_name ?? 'User' }}</small>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i> My Account</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger" style="border: none; background: none; width: 100%; text-align: left;">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <div class="main-content flex-grow-1">
                @yield('content')
            </div>

            <footer class="py-3 mt-auto">
                <div class="footer clearfix mb-0 text-muted fs-6">
                    <div class="float-start">
                        <p><span id="current-year"></span> &copy; ERP CWA Layout</p>
                    </div>
                    <div class="float-end">
                        <p>Crafted with <span class="text-danger"><i class="bi bi-heart-fill"></i></span></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @stack('before-script')

    <script src="{{ asset('custom/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('custom/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('custom/assets/extensions/jquery/jquery.min.js') }}"></script>
    {{-- DataTables --}}
    <script src="{{ asset('custom/assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('custom/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('custom/assets/extensions/datatables.net-bs5/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('custom/assets/extensions/datatables.net-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('custom/assets/static/js/pages/datatables.js') }}"></script>
    <script src="{{ asset('custom/assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('custom/assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('custom/assets/static/js/pages/parsley.js') }}"></script>
    <script src="{{ asset('custom/assets/extensions/blockUI/blockUI.js') }}"></script>
    <script src="{{ asset('custom/assets/extensions/jquery-confirm/jquery-confirm.min.js') }}"></script>
    <script src="{{ asset('custom/assets/extensions/select2/select2.min.js') }}"></script>
    <script src="{{ asset('custom/assets/extensions/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('custom/assets/helper/helper.js') }}"></script>
    <script src="{{ asset('custom/assets/compiled/js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            var activeSubmenu = $('.submenu .submenu-item.active').parent();
            activeSubmenu.addClass('active submenu-open');
            activeSubmenu.parent().addClass('active');
        });

        document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>

    @stack('after-script')
</body>

</html>
