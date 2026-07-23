@extends('auth.layouts.master')
@section('title', 'Sign In')
@section('content')
<div class="hz-auth-card">
    <div class="row g-0">
        {{-- Left: Form Section --}}
        <div class="col-12 col-lg-6">
            <div class="hz-auth-form-wrapper">
                <div class="mb-4">
                    <h2 class="hz-auth-title mb-1">Sign In</h2>
                    <p class="hz-auth-subtitle">Enter your username and password to sign in!</p>
                </div>

                @if (Session::has('failed'))
                    <div class="alert alert-danger rounded-4 mb-4 border-0 text-white" style="background-color: #EE5D50;">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('failed') }}
                    </div>
                @endif

                <form id="loginForm" action="{{ route('login') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                    @csrf
                    <div class="hz-input-group">
                        <label class="hz-input-label" for="username">Username<span class="text-danger">*</span></label>
                        <input type="text" class="hz-input" id="username" name="username"
                            placeholder="mail@simmmple.com or username" data-parsley-required="true" autofocus required />
                        @if ($errors->has('username'))
                            <span class="text-danger small mt-1 d-block">{{ $errors->first('username') }}</span>
                        @endif
                    </div>

                    <div class="hz-input-group mb-4">
                        <label class="hz-input-label" for="password">Password<span class="text-danger">*</span></label>
                        <input type="password" class="hz-input" id="password" name="password"
                            placeholder="Min. 8 characters" data-parsley-required="true" required />
                        @if ($errors->has('password'))
                            <span class="text-danger small mt-1 d-block">{{ $errors->first('password') }}</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="keepLoggedIn">
                            <label class="form-check-label text-muted small fw-semibold" for="keepLoggedIn">
                                Keep me logged in
                            </label>
                        </div>
                        <a href="#" class="small text-decoration-none fw-bold" style="color: var(--hz-primary);">Forgot password?</a>
                    </div>

                    <button class="hz-btn-primary" type="submit">
                        Sign In
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <span class="text-muted small">Not registered yet? </span>
                    <a href="#" class="small text-decoration-none fw-bold" style="color: var(--hz-primary);">Create an Account</a>
                </div>
            </div>
        </div>

        {{-- Right: Banner Section --}}
        <div class="col-lg-6 d-none d-lg-flex">
            <div class="hz-auth-banner w-100">
                <div class="text-center px-4">
                    <div class="mb-4">
                        <img src="{{ asset('logo.png') }}" alt="Logo" style="max-height: 90px; filter: brightness(0) invert(1);" onError="this.onerror=null; this.style.display='none';">
                    </div>
                    <h2 class="fw-extrabold text-white display-6 mb-3" style="letter-spacing:-0.5px;">
                        HORIZON <span style="font-weight: 300;">FREE</span>
                    </h2>
                    <p class="text-white-50 fs-5 mb-0" style="max-width: 380px; margin: 0 auto; line-height: 1.5;">
                        {{ $data['app_description'] ?? 'Learn more about Horizon UI Dashboard & ERP System controls' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
