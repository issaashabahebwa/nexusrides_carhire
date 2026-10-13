@extends('layouts.app')
@section('title','Login')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-card-header">
            <div class="logo">Nexus<span>Rides</span></div>
            <p>Sign in to your account</p>
        </div>
        <div class="auth-card-body">

            @if($errors->any())
            <div class="alert alert-danger">
                <span>{{ $errors->first() }}</span>
                <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                    @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">
                        Password
                    </label>
                    <input type="password" id="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Enter your password" required>
                    @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-group" style="display:flex;align-items:center;gap:.6rem;">
                    <input type="checkbox" id="remember" name="remember" value="1" style="width:16px;height:16px;">
                    <label for="remember" style="font-size:.9rem;color:#495057;cursor:pointer;margin:0;">Remember me</label>
                </div>

                <button type="submit" class="btn btn-navy btn-block btn-lg" style="margin-top:.5rem;">
                    Sign In
                </button>
            </form>

            <div class="auth-divider">or</div>

            <p style="text-align:center;font-size:.9rem;color:#6c757d;">
                Don't have an account?
                <a href="{{ route('register') }}" style="color:var(--navy);font-weight:600;">Create one free</a>
            </p>

            <div style="margin-top:1.5rem;padding:1rem;background:#f8f9fa;border-radius:8px;font-size:.83rem;color:#6c757d;">
                <strong>Demo credentials:</strong><br>
                Admin: admin@nexusrides.ug / password<br>
                Customer: customer@nexusrides.ug / password
            </div>
        </div>
    </div>
</div>
@endsection
