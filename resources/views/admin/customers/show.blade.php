@extends('layouts.admin')
@section('title', $user->name)

@section('content')

{{-- Page Header --}}
<div class="adm-page-header">
    <div style="display:flex;align-items:center;gap:1rem;">
        <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#0a2540,#1a4a80);color:#f4a522;font-size:1.3rem;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            {{ strtoupper(substr($user->name,0,1)) }}
        </div>
        <div>
            <h1 class="adm-page-title">{{ $user->name }}</h1>
            <p class="adm-page-sub">{{ $user->email }} &nbsp;·&nbsp; Customer since {{ $user->created_at->format('M Y') }}</p>
        </div>
    </div>
    <a href="{{ route('admin.customers.index') }}" class="adm-btn adm-btn-outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Customers
    </a>
</div>

{{-- Stats strip --}}
<div class="adm-kpi-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:1.25rem;">
    <div class="adm-kpi-card adm-kpi-navy">
        <div class="adm-kpi-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div class="adm-kpi-body">
            <div class="adm-kpi-value">{{ $user->bookings->count() }}</div>
            <div class="adm-kpi-label">Total Bookings</div>
        </div>
    </div>
    <div class="adm-kpi-card adm-kpi-green">
        <div class="adm-kpi-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="adm-kpi-body">
            <div class="adm-kpi-value">{{ $user->bookings->where('status','completed')->count() }}</div>
            <div class="adm-kpi-label">Completed</div>
        </div>
    </div>
    <div class="adm-kpi-card adm-kpi-gold">
        <div class="adm-kpi-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="adm-kpi-body">
            <div class="adm-kpi-value">{{ $user->bookings->where('status','pending')->count() }}</div>
            <div class="adm-kpi-label">Pending</div>
        </div>
    </div>
    <div class="adm-kpi-card adm-kpi-red">
        <div class="adm-kpi-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div class="adm-kpi-body">
            <div class="adm-kpi-value adm-kpi-value-sm">{{ number_format($totalSpent/1000000,1) }}M</div>
            <div class="adm-kpi-label">Total Spent (UGX)</div>
        </div>
    </div>
</div>

{{-- Two-column layout --}}
<div class="adm-detail-grid">

    {{-- LEFT: Profile + Contact --}}
    <div class="adm-detail-col">
        <div class="adm-panel">
            <div class="adm-panel-head">
                <h3 class="adm-panel-title">Contact Details</h3>
                <span class="adm-pill adm-pill-navy">Customer</span>
            </div>
            <div class="adm-panel-body">
                <div class="adm-info-table">
                    @foreach([
                        ['Full Name',     $user->name],
                        ['Email',         $user->email],
                        ['Phone',         $user->phone ?? 'N/A'],
                        ['Address',       $user->address ?? 'N/A'],
                        ['ID / Passport', $user->id_number ?? 'N/A'],
                        ['Joined',        $user->created_at->format('d M Y')],
                    ] as [$lbl, $val])
                    <div class="adm-info-row">
                        <span class="adm-info-label">{{ $lbl }}</span>
                        <span class="adm-info-value">{{ $val }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Booking History --}}
    <div class="adm-detail-col">
        <div class="adm-panel">
            <div class="adm-panel-head">
                <h3 class="adm-panel-title">Booking History</h3>
                <span class="adm-pill adm-pill-navy">{{ $user->bookings->count() }} total</span>
            </div>
            <div class="adm-panel-body adm-panel-body-flush">
                @forelse($user->bookings as $b)
                <div class="adm-booking-history-row">
                    <div class="adm-booking-history-main">
                        <div>
                            <div class="adm-fw600 adm-text-sm">
                                <span class="adm-mono">{{ $b->booking_reference }}</span>
                            </div>
                            <div class="adm-text-muted adm-text-sm">{{ $b->vehicle->name }}</div>
                            <div class="adm-text-muted adm-text-sm" style="margin-top:.15rem;">
                                {{ $b->pickup_date->format('d M Y') }} → {{ $b->return_date->format('d M Y') }}
                                · {{ $b->days }} day{{ $b->days > 1 ? 's' : '' }}
                            </div>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <span class="adm-status adm-status-{{ $b->status_badge }}">{{ ucfirst($b->status) }}</span>
                            <div class="adm-fw600 adm-text-sm" style="margin-top:.35rem;">
                                UGX {{ number_format($b->total_amount, 0) }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.bookings.show', $b) }}" class="adm-btn adm-btn-ghost adm-btn-xs" style="margin-top:.6rem;align-self:flex-start;">
                        View Booking
                    </a>
                </div>
                @empty
                <div class="adm-empty-row">No bookings found for this customer.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection
