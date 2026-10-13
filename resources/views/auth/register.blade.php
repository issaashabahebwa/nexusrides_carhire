@extends('layouts.app')
@section('title','Create Account')

@section('content')
<div class="auth-page" style="padding:3rem 1rem;">
    <div class="auth-card" style="max-width:540px;">
        <div class="auth-card-header">
            <div class="logo">Nexus<span>Rides</span></div>
            <p>Create your free account</p>
        </div>
        <div class="auth-card-body">

            @if($errors->any())
            <div class="alert alert-danger">
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin:.5rem 0 0 1rem;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-row form-row-2">
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name <span style="color:var(--red)">*</span></label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number <span style="color:var(--red)">*</span></label>
                        <input type="tel" id="phone" name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}" placeholder="+256 700 000 000" required>
                        @error('phone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email Address <span style="color:var(--red)">*</span></label>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="you@example.com" required>
                    @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="id_number">National ID / Passport Number</label>
                    <input type="text" id="id_number" name="id_number"
                           class="form-control @error('id_number') is-invalid @enderror"
                           value="{{ old('id_number') }}" placeholder="CM90000000XXXX">
                    @error('id_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="address">Address</label>
                    <input type="text" id="address" name="address"
                           class="form-control @error('address') is-invalid @enderror"
                           value="{{ old('address') }}" placeholder="Kampala, Uganda">
                    @error('address')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-row form-row-2">
                    <div class="form-group">
                        <label class="form-label" for="password">Password <span style="color:var(--red)">*</span></label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Min 8 characters" required>
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm Password <span style="color:var(--red)">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-control" placeholder="Repeat password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-navy btn-block btn-lg">
                    Create Account
                </button>
            </form>

            <div class="auth-divider">or</div>

            <p style="text-align:center;font-size:.9rem;color:#6c757d;">
                Already have an account?
                <a href="{{ route('login') }}" style="color:var(--navy);font-weight:600;">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection
