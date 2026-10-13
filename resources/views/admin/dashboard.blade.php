@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- ── Page Header ─────────────────────────────────────────── --}}
<div class="adm-page-header">
    <div>
        <h1 class="adm-page-title">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
        <p class="adm-page-sub">Here's what's happening with NexusRides today — {{ now()->format('l, F j, Y') }}</p>
    </div>
    <div class="adm-page-header-actions">
        <a href="{{ route('admin.vehicles.create') }}" class="adm-btn adm-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Vehicle
        </a>
        <a href="{{ route('admin.reports.index') }}" class="adm-btn adm-btn-outline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export Report
        </a>
    </div>
</div>

{{-- ── KPI Stat Cards ──────────────────────────────────────── --}}
<div class="adm-kpi-grid">

    <div class="adm-kpi-card adm-kpi-navy">
        <div class="adm-kpi-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2h-2"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
        </div>
        <div class="adm-kpi-body">
            <div class="adm-kpi-value">{{ $stats['total_vehicles'] }}</div>
            <div class="adm-kpi-label">Total Vehicles</div>
            <div class="adm-kpi-meta"><span class="adm-kpi-dot adm-dot-green"></span>{{ $stats['available_vehicles'] }} available</div>
        </div>
    </div>

    <div class="adm-kpi-card adm-kpi-gold">
        <div class="adm-kpi-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <div class="adm-kpi-body">
            <div class="adm-kpi-value">{{ $stats['total_bookings'] }}</div>
            <div class="adm-kpi-label">Total Bookings</div>
            <div class="adm-kpi-meta"><span class="adm-kpi-dot adm-dot-amber"></span>{{ $stats['pending_bookings'] }} pending</div>
        </div>
    </div>

    <div class="adm-kpi-card adm-kpi-green">
        <div class="adm-kpi-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <div class="adm-kpi-body">
            <div class="adm-kpi-value">{{ $stats['total_customers'] }}</div>
            <div class="adm-kpi-label">Customers</div>
            <div class="adm-kpi-meta"><span class="adm-kpi-dot adm-dot-blue"></span>{{ $stats['active_bookings'] }} active rentals</div>
        </div>
    </div>

    <div class="adm-kpi-card adm-kpi-red">
        <div class="adm-kpi-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div class="adm-kpi-body">
            <div class="adm-kpi-value adm-kpi-value-sm">{{ number_format($stats['total_revenue'] / 1000000, 1) }}M</div>
            <div class="adm-kpi-label">Total Revenue (UGX)</div>
            <div class="adm-kpi-meta"><span class="adm-kpi-dot adm-dot-green"></span>{{ number_format($stats['monthly_revenue'] / 1000, 0) }}K this month</div>
        </div>
    </div>

</div>

{{-- ── Middle row: Chart + Quick Actions + Status Ring ───────── --}}
<div class="adm-mid-grid">

    {{-- Revenue Bar Chart --}}
    <div class="adm-panel adm-panel-chart">
        <div class="adm-panel-head">
            <div>
                <h3 class="adm-panel-title">Revenue Overview</h3>
                <p class="adm-panel-sub">Last 6 months · UGX</p>
            </div>
            <span class="adm-pill adm-pill-navy">6 Months</span>
        </div>
        <div class="adm-panel-body">
            @php
                $maxRev = max(array_column($monthlyData,'revenue') ?: [1]);
            @endphp
            <div class="adm-chart-bars">
                @foreach($monthlyData as $m)
                @php $pct = $maxRev > 0 ? max(4, round(($m['revenue']/$maxRev)*100)) : 4; @endphp
                <div class="adm-chart-col">
                    <div class="adm-chart-val">{{ $m['revenue'] > 0 ? number_format($m['revenue']/1000,0).'k' : '—' }}</div>
                    <div class="adm-chart-bar-wrap">
                        <div class="adm-chart-bar" style="height:{{ $pct }}%;"
                             title="UGX {{ number_format($m['revenue'],0) }}"></div>
                    </div>
                    <div class="adm-chart-month">{{ substr($m['month'],0,3) }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="adm-panel adm-panel-actions">
        <div class="adm-panel-head">
            <h3 class="adm-panel-title">Quick Actions</h3>
        </div>
        <div class="adm-panel-body">
            <div class="adm-action-list">
                <a href="{{ route('admin.vehicles.create') }}" class="adm-action-item adm-action-navy">
                    <span class="adm-action-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </span>
                    <div>
                        <div class="adm-action-label">Add New Vehicle</div>
                        <div class="adm-action-sub">Expand your fleet</div>
                    </div>
                    <svg class="adm-action-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </a>

                <a href="{{ route('admin.bookings.index', ['status'=>'pending']) }}" class="adm-action-item adm-action-amber">
                    <span class="adm-action-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </span>
                    <div>
                        <div class="adm-action-label">Pending Bookings</div>
                        <div class="adm-action-sub">{{ $stats['pending_bookings'] }} awaiting review</div>
                    </div>
                    <svg class="adm-action-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </a>

                <a href="{{ route('admin.customers.index') }}" class="adm-action-item adm-action-blue">
                    <span class="adm-action-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </span>
                    <div>
                        <div class="adm-action-label">Manage Customers</div>
                        <div class="adm-action-sub">{{ $stats['total_customers'] }} registered</div>
                    </div>
                    <svg class="adm-action-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="adm-action-item adm-action-green">
                    <span class="adm-action-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </span>
                    <div>
                        <div class="adm-action-label">View Reports</div>
                        <div class="adm-action-sub">Revenue &amp; analytics</div>
                    </div>
                    <svg class="adm-action-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Fleet Status --}}
    <div class="adm-panel adm-panel-fleet">
        <div class="adm-panel-head">
            <h3 class="adm-panel-title">Fleet Status</h3>
        </div>
        <div class="adm-panel-body adm-fleet-body">
            @php
                $total    = $stats['total_vehicles'] ?: 1;
                $availPct = round(($stats['available_vehicles'] / $total) * 100);
                $activePct= round(($stats['active_bookings']    / $total) * 100);
                $restPct  = max(0, 100 - $availPct - $activePct);
            @endphp

            <div class="adm-donut-wrap">
                <svg class="adm-donut" viewBox="0 0 120 120">
                    @php
                        $r = 46; $cx = 60; $cy = 60;
                        $circ = 2 * M_PI * $r;
                        $aOff = 0;
                        $segments = [
                            ['pct'=>$availPct,  'color'=>'#10b981'],
                            ['pct'=>$activePct, 'color'=>'#f4a522'],
                            ['pct'=>$restPct,   'color'=>'#e5e7eb'],
                        ];
                    @endphp
                    @foreach($segments as $seg)
                    @php
                        $dash  = ($seg['pct']/100) * $circ;
                        $gap   = $circ - $dash;
                        $rotate= -90 + ($aOff/100)*360;
                        $aOff += $seg['pct'];
                    @endphp
                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"
                            fill="none"
                            stroke="{{ $seg['color'] }}"
                            stroke-width="16"
                            stroke-dasharray="{{ round($dash,2) }} {{ round($gap,2) }}"
                            stroke-dashoffset="0"
                            transform="rotate({{ $rotate }} {{ $cx }} {{ $cy }})" />
                    @endforeach
                    <text x="60" y="56" text-anchor="middle" class="adm-donut-pct" fill="#0a2540">{{ $availPct }}%</text>
                    <text x="60" y="70" text-anchor="middle" class="adm-donut-label" fill="#6b7280">available</text>
                </svg>
            </div>

            <div class="adm-fleet-legend">
                <div class="adm-legend-item">
                    <span class="adm-legend-dot" style="background:#10b981"></span>
                    <span class="adm-legend-text">Available <strong>{{ $stats['available_vehicles'] }}</strong></span>
                </div>
                <div class="adm-legend-item">
                    <span class="adm-legend-dot" style="background:#f4a522"></span>
                    <span class="adm-legend-text">Active Rentals <strong>{{ $stats['active_bookings'] }}</strong></span>
                </div>
                <div class="adm-legend-item">
                    <span class="adm-legend-dot" style="background:#e5e7eb"></span>
                    <span class="adm-legend-text">Other <strong>{{ max(0, $stats['total_vehicles'] - $stats['available_vehicles'] - $stats['active_bookings']) }}</strong></span>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Recent Bookings ─────────────────────────────────────── --}}
<div class="adm-panel adm-mb">
    <div class="adm-panel-head">
        <div>
            <h3 class="adm-panel-title">Recent Bookings</h3>
            <p class="adm-panel-sub">Latest rental activity</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="adm-link-more">
            View all
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </div>
    <div class="adm-panel-body adm-panel-body-flush">
        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Dates</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $b)
                    <tr>
                        <td><span class="adm-mono">{{ $b->booking_reference }}</span></td>
                        <td>
                            <div class="adm-user-row">
                                <div class="adm-avatar-xs">{{ strtoupper(substr($b->user->name,0,1)) }}</div>
                                <span>{{ $b->user->name }}</span>
                            </div>
                        </td>
                        <td class="adm-text-sm">{{ $b->vehicle->name }}</td>
                        <td class="adm-text-muted adm-text-sm">
                            {{ $b->pickup_date->format('d M') }} → {{ $b->return_date->format('d M Y') }}
                        </td>
                        <td class="adm-fw600">UGX {{ number_format($b->total_amount, 0) }}</td>
                        <td>
                            <span class="adm-status adm-status-{{ $b->status_badge }}">{{ ucfirst($b->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.bookings.show', $b) }}" class="adm-btn adm-btn-ghost adm-btn-xs">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="adm-empty-row">No bookings yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Recent Payments ─────────────────────────────────────── --}}
<div class="adm-panel">
    <div class="adm-panel-head">
        <div>
            <h3 class="adm-panel-title">Recent Payments</h3>
            <p class="adm-panel-sub">Confirmed transactions</p>
        </div>
        <span class="adm-pill adm-pill-green">Live</span>
    </div>
    <div class="adm-panel-body adm-panel-body-flush">
        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Customer</th>
                        <th>Booking</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $p)
                    <tr>
                        <td><span class="adm-mono adm-text-sm">{{ $p->transaction_id }}</span></td>
                        <td>
                            <div class="adm-user-row">
                                <div class="adm-avatar-xs">{{ strtoupper(substr($p->user->name,0,1)) }}</div>
                                <span>{{ $p->user->name }}</span>
                            </div>
                        </td>
                        <td><span class="adm-mono adm-text-sm">{{ $p->booking->booking_reference }}</span></td>
                        <td class="adm-fw600">UGX {{ number_format($p->amount, 0) }}</td>
                        <td><span class="adm-pill adm-pill-blue adm-pill-sm">{{ $p->method_label }}</span></td>
                        <td class="adm-text-muted adm-text-sm">{{ $p->paid_at?->format('d M Y · H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="adm-empty-row">No payments yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
