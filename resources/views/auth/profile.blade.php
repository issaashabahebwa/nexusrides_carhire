@extends('layouts.app')
@section('title', 'My Profile')

@push('styles')
<style>
.profile-page{min-height:70vh;background:#0B132B;padding:3rem 1.25rem 4rem;color:#E0E0E0}
.profile-shell{max-width:1080px;margin:0 auto}
.profile-heading{display:flex;align-items:flex-end;justify-content:space-between;gap:1rem;margin-bottom:1.75rem}
.profile-kicker{font-size:.72rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#1A83FF}
.profile-heading h1{font-family:'Playfair Display',Georgia,serif;font-size:2.2rem;line-height:1.15;margin:.3rem 0;color:#fff}
.profile-heading p{margin:0;color:#B8C2D3;font-size:.92rem}
.profile-layout{display:grid;grid-template-columns:minmax(0,1.35fr) minmax(280px,.8fr);gap:1rem;align-items:start}
.profile-panel{background:#111B36;border:1px solid #26334F;border-radius:8px;overflow:hidden}
.profile-panel-head{padding:1.1rem 1.35rem;border-bottom:1px solid #26334F;display:flex;align-items:center;gap:.8rem}
.profile-icon{width:38px;height:38px;background:rgba(26,131,255,.15);color:#1A83FF;border-radius:7px;display:grid;place-items:center;flex:none}
.profile-icon svg{width:19px;height:19px}
.profile-panel-head h2{font-size:1rem;margin:0;color:#fff}
.profile-panel-head p{font-size:.78rem;color:#B8C2D3;margin:.2rem 0 0}
.profile-panel-body{padding:1.35rem}
.profile-fields{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.profile-field{min-width:0}
.profile-field.wide{grid-column:1/-1}
.profile-field label{display:block;font-size:.78rem;font-weight:700;color:#E0E0E0;margin-bottom:.38rem}
.profile-field input,.profile-field textarea{width:100%;border:1px solid #3B4965;border-radius:5px;padding:.7rem .75rem;font:inherit;font-size:.88rem;color:#fff;background:#172440}
.profile-field textarea{min-height:86px;resize:vertical}
.profile-field input:focus,.profile-field textarea:focus{outline:2px solid #1A83FF;outline-offset:1px;border-color:#1A83FF}
.profile-error{display:block;color:#a52b35;font-size:.76rem;margin-top:.3rem}
.profile-actions{display:flex;justify-content:flex-end;margin-top:1.2rem}
.profile-submit{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:.7rem 1rem;border:0;border-radius:5px;background:#006CFF;color:#fff;font:inherit;font-size:.85rem;font-weight:700;cursor:pointer}
.profile-submit:hover{background:#0059D6}
.profile-submit svg{width:16px;height:16px}
.profile-meta{display:grid;gap:0;margin:0}
.profile-meta div{display:flex;justify-content:space-between;gap:.75rem;padding:.8rem 0;border-bottom:1px solid #26334F;font-size:.83rem}
.profile-meta div:last-child{border-bottom:0}
.profile-meta dt{color:#B8C2D3}
.profile-meta dd{margin:0;text-align:right;font-weight:650;color:#fff;overflow-wrap:anywhere}
.profile-status{display:inline-flex;align-items:center;gap:.35rem;color:#1A83FF}
.password-form{display:grid;gap:.9rem}
.profile-note{font-size:.78rem;color:#B8C2D3;line-height:1.5;margin:1rem 0 0}
@media(max-width:760px){.profile-layout{grid-template-columns:1fr}.profile-heading{align-items:flex-start;flex-direction:column}.profile-page{padding-top:2rem}}
@media(max-width:480px){.profile-fields{grid-template-columns:1fr}.profile-field.wide{grid-column:auto}.profile-panel-body{padding:1rem}.profile-heading h1{font-size:1.8rem}}
</style>
@endpush

@section('content')
<div class="profile-page">
    <div class="profile-shell">
        <header class="profile-heading">
            <div>
                <div class="profile-kicker">Account settings</div>
                <h1>Your profile</h1>
                <p>Keep your contact and identity information up to date.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="profile-submit" style="text-decoration:none;background:#172440;color:#E0E0E0;border:1px solid #3B4965;">Back to dashboard</a>
        </header>

        @if($errors->any())
            <div class="alert alert-danger" role="alert">Please review the highlighted fields below.</div>
        @endif

        <div class="profile-layout">
            <section class="profile-panel">
                <div class="profile-panel-head">
                    <div class="profile-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></div>
                    <div><h2>Personal information</h2><p>These details are used for your bookings.</p></div>
                </div>
                <div class="profile-panel-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="profile-fields">
                            <div class="profile-field">
                                <label for="name">Full name</label>
                                <input id="name" name="name" value="{{ old('name', $user->name) }}" autocomplete="name" required>
                                @error('name')<span class="profile-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="profile-field">
                                <label for="email">Email address</label>
                                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="email" required>
                                @error('email')<span class="profile-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="profile-field">
                                <label for="phone">Phone number</label>
                                <input id="phone" name="phone" value="{{ old('phone', $user->phone) }}" autocomplete="tel">
                                @error('phone')<span class="profile-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="profile-field">
                                <label for="id_number">National ID / passport number</label>
                                <input id="id_number" name="id_number" value="{{ old('id_number', $user->id_number) }}" autocomplete="off">
                                @error('id_number')<span class="profile-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="profile-field wide">
                                <label for="address">Residential address</label>
                                <textarea id="address" name="address" autocomplete="street-address">{{ old('address', $user->address) }}</textarea>
                                @error('address')<span class="profile-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="profile-actions"><button class="profile-submit" type="submit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>Save profile</button></div>
                    </form>
                </div>
            </section>

            <div style="display:grid;gap:1rem">
                <section class="profile-panel">
                    <div class="profile-panel-head">
                        <div class="profile-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="10" width="18" height="11" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/><circle cx="12" cy="15" r="1"/></svg></div>
                        <div><h2>Change password</h2><p>Confirm your current password to continue.</p></div>
                    </div>
                    <div class="profile-panel-body">
                        <form class="password-form" method="POST" action="{{ route('profile.password.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="profile-field">
                                <label for="current_password">Current password</label>
                                <input id="current_password" type="password" name="current_password" autocomplete="current-password" required>
                                @error('current_password')<span class="profile-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="profile-field">
                                <label for="password">New password</label>
                                <input id="password" type="password" name="password" autocomplete="new-password" minlength="8" required>
                                @error('password')<span class="profile-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="profile-field">
                                <label for="password_confirmation">Confirm new password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required>
                            </div>
                            <div class="profile-actions"><button class="profile-submit" type="submit">Update password</button></div>
                        </form>
                    </div>
                </section>

                <section class="profile-panel">
                    <div class="profile-panel-head">
                        <div class="profile-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
                        <div><h2>Account details</h2><p>Information linked to your NexusRides account.</p></div>
                    </div>
                    <div class="profile-panel-body">
                        <dl class="profile-meta">
                            <div><dt>Account type</dt><dd>{{ ucfirst($user->role) }}</dd></div>
                            <div><dt>Email status</dt><dd><span class="profile-status">{{ $user->email_verified_at ? 'Verified' : 'Not verified' }}</span></dd></div>
                            <div><dt>Member since</dt><dd>{{ $user->created_at?->format('M j, Y') ?? '—' }}</dd></div>
                            <div><dt>Last updated</dt><dd>{{ $user->updated_at?->format('M j, Y') ?? '—' }}</dd></div>
                        </dl>
                        <p class="profile-note">Your account type and registration dates are managed by NexusRides and cannot be changed here.</p>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection