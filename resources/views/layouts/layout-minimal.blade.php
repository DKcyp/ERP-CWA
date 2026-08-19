<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title') | {{ env('APP_NAME', 'ERP CWA') }} </title>

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2563EB">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="ERP CWA">
    <link rel="apple-touch-icon" href="{{ asset('pwa/icons/icon-192x192.png') }}">

    <link rel="shortcut icon" href="{{ asset('custom/assets/compiled/svg/favicon2.svg') }}" type="image/x-icon">
    <style>
      @media (min-width: 1200px) { html { font-size: 14px; } }
      @media (max-width: 1199.98px) { html { font-size: 16px; } }
    </style>
    @stack('before-style')
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
        #main { display:flex; flex-direction:column; min-height:100vh; }
        #main .main-content { flex:1; }
        #main footer { margin-top:auto; }
        .hz-header { border-bottom:1px solid #e9ecef; padding-bottom:0.75rem; margin-bottom:1rem; }
    </style>
    @stack('after-style')
</head>

<body>
    <script src="{{ asset('custom/assets/static/js/initTheme.js') }}"></script>
    <div id="app">
        <div id="main" class="px-4 py-3">
            <header class="hz-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <div class="hz-breadcrumb">Pages / <span>@yield('title', 'Detail')</span></div>
                            <h1 class="hz-page-title">@yield('title', 'Detail')</h1>
                        </div>
                    </div>
                    <div class="hz-topbar-pill">
                        <button type="button" class="hz-icon-btn" id="hz-theme-toggle" title="Toggle Theme">
                            <i class="bi bi-moon"></i>
                        </button>
                        <div class="dropdown d-inline">
                            <a href="#" id="topbarUserDropdown" data-bs-toggle="dropdown" aria-expanded="false" class="d-flex align-items-center">
                                <img src="{{ asset('custom/assets/compiled/png/avatar1.png') }}" alt="Avatar" class="hz-avatar">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 border-0" aria-labelledby="topbarUserDropdown">
                                <li><div class="px-3 py-2"><div class="fw-bold">{{ Auth::user()->username ?? 'User' }}</div><small class="text-muted">{{ optional(Auth::user()->roles)->role_name ?? 'User' }}</small></div></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i> My Account</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-inline">@csrf<button type="submit" class="dropdown-item text-danger" style="border:none;background:none;width:100%;text-align:left;"><i class="fas fa-sign-out-alt me-2"></i> Logout</button></form></li>
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
                    <div class="float-start"><p><span id="current-year"></span> &copy; ERP CWA Layout</p></div>
                    <div class="float-end"><p>Crafted with <span class="text-danger"><i class="bi bi-heart-fill"></i></span></p></div>
                </div>
            </footer>
        </div>
    </div>

    @stack('before-script')
    <script src="{{ asset('custom/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('custom/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('custom/assets/extensions/jquery/jquery.min.js') }}"></script>
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
        document.getElementById('current-year').textContent = new Date().getFullYear();
        (function(){
            var t=document.getElementById('hz-theme-toggle');if(!t)return;var i=t.querySelector('i');
            function u(e){if(!i)return;i.className=e==='dark'?'bi bi-sun':'bi bi-moon';}
            var c=localStorage.getItem('theme')||'light';document.documentElement.setAttribute('data-bs-theme',c);u(c);
            t.addEventListener('click',function(){var a=document.documentElement.getAttribute('data-bs-theme');var n=a==='dark'?'light':'dark';document.documentElement.setAttribute('data-bs-theme',n);localStorage.setItem('theme',n);u(n);});
        })();
    </script>
    @stack('after-script')
</body>
</html>
