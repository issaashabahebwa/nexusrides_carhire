@extends('layouts.admin')
@section('title', 'Bookings')

@section('content')

{{-- Page Header --}}
<div class="adm-page-header">
    <div>
        <h1 class="adm-page-title">Bookings</h1>
        <p class="adm-page-sub">Review and manage all rental bookings</p>
    </div>
    <a href="{{ route('admin.bookings.index', ['status'=>'pending']) }}" class="adm-btn adm-btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        Pending Only
    </a>
</div>

{{-- Filters --}}
<div class="adm-panel adm-mb">
    <div class="adm-panel-body">
        <form method="GET" class="adm-filter-bar">
            <div class="adm-filter-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" class="adm-filter-input" placeholder="Ref, customer, vehicle…" value="{{ request('search') }}">
            </div>
            <select name="status" class="adm-filter-select">
                <option value="">All Statuses</option>
                @foreach(['pending','approved','active','completed','cancelled','declined'] as $s)
                <option value="{{ $s }}" {{ request('status')==$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" class="adm-filter-date" value="{{ request('date_from') }}" title="From date">
            <input type="date" name="date_to"   class="adm-filter-date" value="{{ request('date_to') }}"   title="To date">
            <button type="submit" class="adm-btn adm-btn-primary">Filter</button>
            @if(request()->anyFilled(['search','status','date_from','date_to']))
            <a href="{{ route('admin.bookings.index') }}" class="adm-btn adm-btn-outline">Clear</a>
            @endif
        </form>
    </div>
</div>

{{-- Table --}}
<div class="adm-panel">
    <div class="adm-panel-head">
        <div>
            <h3 class="adm-panel-title">All Bookings</h3>
            <p class="adm-panel-sub">{{ $bookings->total() }} total</p>
        </div>
    </div>
    <div class="adm-panel-body adm-panel-body-flush">
        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Pickup</th>
                        <th>Return</th>
                        <th>Days</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                    <tr>
                        <td><span class="adm-mono">{{ $b->booking_reference }}</span></td>
                        <td>
                            <div class="adm-user-row">
                                <div class="adm-avatar-xs">{{ strtoupper(substr($b->user->name,0,1)) }}</div>
                                <div>
                                    <div style="font-weight:600;font-size:.85rem;">{{ $b->user->name }}</div>
                                    <div class="adm-text-muted adm-text-sm">{{ $b->user->phone }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="adm-text-sm">{{ $b->vehicle->name }}</td>
                        <td class="adm-text-sm adm-text-muted">{{ $b->pickup_date->format('d M Y') }}</td>
                        <td class="adm-text-sm adm-text-muted">{{ $b->return_date->format('d M Y') }}</td>
                        <td style="text-align:center;font-weight:600;">{{ $b->days }}</td>
                        <td class="adm-fw600">UGX {{ number_format($b->total_amount, 0) }}</td>
                        <td>
                            <span class="adm-status adm-status-{{ $b->status_badge }}">{{ ucfirst($b->status) }}</span>
                        </td>
                        <td>
                            @if($b->payment && $b->payment->status === 'completed')
                                <span class="adm-status adm-status-success">Paid</span>
                            @else
                                <span class="adm-status adm-status-secondary">Unpaid</span>
                            @endif
                        </td>
                        <td>
                            <div class="adm-row-actions">
                                <a href="{{ route('admin.bookings.show', $b) }}" class="adm-btn adm-btn-ghost adm-btn-xs">View</a>
                                @if($b->status === 'pending')
                                <form method="POST" action="{{ route('admin.bookings.approve', $b) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="adm-btn adm-btn-success adm-btn-xs"
                                            onclick="return confirm('Approve this booking?')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="adm-empty-row">No bookings found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
@if($bookings->hasPages())
<div class="adm-pagination">
    @if($bookings->onFirstPage())
        <span class="adm-page-btn adm-page-disabled">‹</span>
    @else
        <a href="{{ $bookings->previousPageUrl() }}" class="adm-page-btn">‹</a>
    @endif
    @foreach($bookings->getUrlRange(max(1,$bookings->currentPage()-2), min($bookings->lastPage(),$bookings->currentPage()+2)) as $page => $url)
        @if($page == $bookings->currentPage())
            <span class="adm-page-btn adm-page-active">{{ $page }}</span>
        @else
            <a href="{{ $url }}" class="adm-page-btn">{{ $page }}</a>
        @endif
    @endforeach
    @if($bookings->hasMorePages())
        <a href="{{ $bookings->nextPageUrl() }}" class="adm-page-btn">›</a>
    @else
        <span class="adm-page-btn adm-page-disabled">›</span>
    @endif
</div>
@endif

@endsection
