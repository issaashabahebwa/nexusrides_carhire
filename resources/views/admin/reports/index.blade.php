@extends('layouts.admin')
@section('title', 'Reports')

@section('content')

{{-- Page Header --}}
<div class="adm-page-header">
    <div>
        <h1 class="adm-page-title">Reports & Analytics</h1>
        <p class="adm-page-sub">Revenue, bookings, and fleet performance for {{ $year }}</p>
    </div>
    <form method="GET" style="display:flex;align-items:center;gap:.6rem;">
        <select name="year" class="adm-filter-select" style="width:120px;">
            @foreach($years as $y)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <button type="submit" class="adm-btn adm-btn-primary">Apply</button>
    </form>
</div>

{{-- KPI Summary --}}
<div class="adm-kpi-grid" style="margin-bottom:1.25rem;">
    <div class="adm-kpi-card adm-kpi-red">
        <div class="adm-kpi-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div class="adm-kpi-body">
            <div class="adm-kpi-value adm-kpi-value-sm">{{ number_format($totalRevenue/1000000,1) }}M</div>
            <div class="adm-kpi-label">Total Revenue {{ $year }} (UGX)</div>
        </div>
    </div>
    <div class="adm-kpi-card adm-kpi-navy">
        <div class="adm-kpi-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div class="adm-kpi-body">
            <div class="adm-kpi-value">{{ $bookingStats['total'] }}</div>
            <div class="adm-kpi-label">Total Bookings</div>
        </div>
    </div>
    <div class="adm-kpi-card adm-kpi-green">
        <div class="adm-kpi-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="adm-kpi-body">
            <div class="adm-kpi-value">{{ $bookingStats['completed'] }}</div>
            <div class="adm-kpi-label">Completed</div>
        </div>
    </div>
    <div class="adm-kpi-card adm-kpi-gold">
        <div class="adm-kpi-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </div>
        <div class="adm-kpi-body">
            <div class="adm-kpi-value">{{ $bookingStats['cancelled'] + $bookingStats['declined'] }}</div>
            <div class="adm-kpi-label">Cancelled / Declined</div>
        </div>
    </div>
</div>

{{-- Chart + Status Breakdown --}}
<div class="adm-mid-grid" style="grid-template-columns:1fr 320px;margin-bottom:1.25rem;">

    {{-- Monthly Revenue Chart --}}
    <div class="adm-panel">
        <div class="adm-panel-head">
            <div>
                <h3 class="adm-panel-title">Monthly Revenue — {{ $year }}</h3>
                <p class="adm-panel-sub">UGX · all 12 months</p>
            </div>
        </div>
        <div class="adm-panel-body">
            @php
                $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                $maxR   = max(array_values($revenueData) ?: [1]);
            @endphp
            <div class="adm-chart-bars" style="height:180px;">
                @foreach($revenueData as $m => $rev)
                @php $pct = $maxR > 0 ? max(4, round(($rev/$maxR)*100)) : 4; @endphp
                <div class="adm-chart-col">
                    <div class="adm-chart-val">{{ $rev > 0 ? number_format($rev/1000,0).'k' : '' }}</div>
                    <div class="adm-chart-bar-wrap">
                        <div class="adm-chart-bar"
                             style="height:{{ $pct }}%;{{ $rev == 0 ? 'background:#e5e7eb;' : '' }}"
                             title="UGX {{ number_format($rev,0) }}"></div>
                    </div>
                    <div class="adm-chart-month">{{ $months[$m-1] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Booking Status Breakdown --}}
    <div class="adm-panel">
        <div class="adm-panel-head">
            <h3 class="adm-panel-title">Booking Breakdown</h3>
        </div>
        <div class="adm-panel-body">
            @php
                $statusMap = [
                    'pending'   => ['label'=>'Pending',   'color'=>'#f4a522', 'bg'=>'#fef3c7'],
                    'approved'  => ['label'=>'Approved',  'color'=>'#3b82f6', 'bg'=>'#dbeafe'],
                    'active'    => ['label'=>'Active',    'color'=>'#10b981', 'bg'=>'#dcfce7'],
                    'completed' => ['label'=>'Completed', 'color'=>'#0a2540', 'bg'=>'#e0e7ff'],
                    'cancelled' => ['label'=>'Cancelled', 'color'=>'#e63946', 'bg'=>'#fee2e2'],
                    'declined'  => ['label'=>'Declined',  'color'=>'#991b1b', 'bg'=>'#fecaca'],
                ];
            @endphp
            <div style="display:flex;flex-direction:column;gap:.85rem;">
                @foreach($statusMap as $key => $cfg)
                @php
                    $count = $bookingStats[$key] ?? 0;
                    $pct   = $bookingStats['total'] > 0 ? round($count / $bookingStats['total'] * 100) : 0;
                @endphp
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:.35rem;">
                        <span style="font-size:.82rem;font-weight:600;color:#374151;">{{ $cfg['label'] }}</span>
                        <span style="font-size:.82rem;color:#9ca3af;">{{ $count }} &nbsp;<span style="font-weight:700;color:#6b7280;">{{ $pct }}%</span></span>
                    </div>
                    <div style="background:#f3f4f6;border-radius:100px;height:7px;overflow:hidden;">
                        <div style="width:{{ $pct }}%;height:100%;background:{{ $cfg['color'] }};border-radius:100px;transition:width .6s;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- Vehicle Utilization --}}
<div class="adm-panel adm-mb">
    <div class="adm-panel-head">
        <div>
            <h3 class="adm-panel-title">Top Vehicle Utilization</h3>
            <p class="adm-panel-sub">Ranked by completed rentals</p>
        </div>
    </div>
    <div class="adm-panel-body adm-panel-body-flush">
        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Vehicle</th>
                        <th>Type</th>
                        <th>Plate</th>
                        <th>Completed Rentals</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicleUtil as $i => $v)
                    @php $maxU = $vehicleUtil->max('completed_bookings') ?: 1; @endphp
                    <tr>
                        <td style="font-weight:700;color:#f4a522;font-size:.9rem;">{{ $i + 1 }}</td>
                        <td>
                            <div class="adm-fw600" style="font-size:.88rem;">{{ $v->name }}</div>
                            <div class="adm-text-muted adm-text-sm">{{ $v->brand }} {{ $v->model }} · {{ $v->year }}</div>
                        </td>
                        <td><span class="adm-pill adm-pill-navy">{{ ucfirst($v->type) }}</span></td>
                        <td><span class="adm-mono">{{ $v->plate_number }}</span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.8rem;">
                                <div style="flex:1;background:#f3f4f6;border-radius:100px;height:7px;overflow:hidden;">
                                    <div style="width:{{ round($v->completed_bookings/$maxU*100) }}%;height:100%;background:linear-gradient(90deg,#1a4a80,#0a2540);border-radius:100px;"></div>
                                </div>
                                <span class="adm-fw600" style="min-width:24px;text-align:right;">{{ $v->completed_bookings }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="adm-status adm-status-{{ match($v->status) {
                                'available'   => 'success',
                                'hired'       => 'info',
                                'maintenance' => 'warning',
                                default       => 'secondary'
                            } }}">{{ ucfirst($v->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="adm-empty-row">No vehicle data available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Top Customers --}}
<div class="adm-panel">
    <div class="adm-panel-head">
        <div>
            <h3 class="adm-panel-title">Top Customers by Revenue</h3>
            <p class="adm-panel-sub">Highest spending customers in {{ $year }}</p>
        </div>
    </div>
    <div class="adm-panel-body adm-panel-body-flush">
        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Bookings</th>
                        <th>Total Paid (UGX)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topCustomers as $i => $c)
                    <tr>
                        <td>
                            @if($i === 0)
                                <span style="font-size:1.1rem;">🥇</span>
                            @elseif($i === 1)
                                <span style="font-size:1.1rem;">🥈</span>
                            @elseif($i === 2)
                                <span style="font-size:1.1rem;">🥉</span>
                            @else
                                <span style="font-weight:700;color:#9ca3af;">{{ $i + 1 }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="adm-user-row">
                                <div class="adm-avatar-xs">{{ strtoupper(substr($c->name,0,1)) }}</div>
                                <span class="adm-fw600" style="font-size:.88rem;">{{ $c->name }}</span>
                            </div>
                        </td>
                        <td class="adm-text-sm adm-text-muted">{{ $c->email }}</td>
                        <td style="text-align:center;font-weight:600;">{{ $c->bookings_count }}</td>
                        <td class="adm-fw600">{{ number_format($c->total_paid ?? 0, 0) }}</td>
                        <td>
                            <a href="{{ route('admin.customers.show', $c) }}" class="adm-btn adm-btn-ghost adm-btn-xs">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="adm-empty-row">No customer data yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
