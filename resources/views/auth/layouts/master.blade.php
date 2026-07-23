<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ env('APP_NAME', 'ERP CWA') }}</title>

    <link rel="shortcut icon" href="{{ asset('custom/assets/compiled/svg/favicon2.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('custom/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/assets/compiled/css/custom.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    @stack('after-style')
</head>

<body>
    <script src="{{ asset('custom/assets/static/js/initTheme.js') }}"></script>
    <div id="auth" class="hz-auth-container p-3 p-md-4">
        @yield('content')
    </div>
    <script src="{{ asset('custom/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('custom/assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('custom/assets/static/js/pages/parsley.js') }}"></script>
</body>

</html>
