<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden</title>
    <link rel="stylesheet" href="{{ asset('custom/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/assets/compiled/css/error.css') }}">
</head>
<body>
    <script src="{{ asset('custom/assets/static/js/initTheme.js') }}"></script>
    <div id="error">
        <div class="error-page container">
            <div class="col-md-8 col-12 offset-md-2">
                <div class="text-center">
                    <img class="img-error" src="{{ asset('custom/assets/compiled/svg/error-403.svg') }}" alt="Forbidden">
                    <h1 class="error-title">Forbidden</h1>
                    <p class='fs-5 text-gray-600'>Kamu tidak memiliki izin untuk mengakses halaman ini.</p>
                    {{-- Info tambahan --}}
                    <p class="mt-4 text-muted">
                        Jika kamu merasa ini adalah kesalahan sistem, silakan hubungi <strong>Tim IT PT Citra Warna Abadi</strong>.
                    </p>
                    <a href="{{ url('/') }}" class="btn btn-lg btn-outline-primary mt-3">Go Home</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
