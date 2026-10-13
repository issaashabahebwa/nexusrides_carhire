@extends('layouts.admin')
@section('title', 'Customers')

@section('content')

{{-- Page Header --}}
<div class="adm-page-header">
    <div>
        <h1 class="adm-page-title">Customers</h1>
        <p class="adm-page-sub">All registered NexusRides customers</p>
    </div>
</div>

{{-- Search --}}
<div class="adm-panel adm-mb">
    <div class="adm-panel-body">
        <form method="GET" class="adm-filter-bar">
            <div class="adm-filter-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" class="adm-filter-input" placeholder="Search name, email, phone…" value="{{ request('search') }}">
            </div>
            <button type="submit" class="adm-btn adm-btn-primary">Search</button>
            @if(request('search'))
            <a href="{{ route('admin.customers.index') }}" class="adm-btn adm-btn-outline">Clear</a>
            @endif
        </form>
    </div>
</div>

{{-- Table --}}
<div class="adm-panel">
    <div class="adm-panel-head">
        <div>
            <h3 class="adm-panel-title">All Customers</h3>
            <p class="adm-panel-sub">{{ $customers->total() }} registered</p>
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
                        <th>Phone</th>
                        <th>ID / Passport</th>
                        <th>Bookings</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                    <tr>
                        <td class="adm-text-muted adm-text-sm">{{ $c->id }}</td>
                        <td>
                            <div class="adm-user-row">
                                <div class="adm-avatar-xs">{{ strtoupper(substr($c->name,0,1)) }}</div>
                                <span class="adm-fw600" style="font-size:.88rem;">{{ $c->name }}</span>
                            </div>
                        </td>
                        <td class="adm-text-sm">{{ $c->email }}</td>
                        <td class="adm-text-sm">{{ $c->phone ?? '—' }}</td>
                        <td class="adm-text-sm adm-text-muted">{{ $c->id_number ?? '—' }}</td>
                        <td style="text-align:center;">
                            <span class="adm-pill adm-pill-navy">{{ $c->bookings_count }}</span>
                        </td>
                        <td class="adm-text-sm adm-text-muted">{{ $c->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.customers.show', $c) }}" class="adm-btn adm-btn-ghost adm-btn-xs">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="adm-empty-row">No customers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
@if($customers->hasPages())
<div class="adm-pagination">
    @if($customers->onFirstPage())
        <span class="adm-page-btn adm-page-disabled">‹</span>
    @else
        <a href="{{ $customers->previousPageUrl() }}" class="adm-page-btn">‹</a>
    @endif
    @foreach($customers->getUrlRange(max(1,$customers->currentPage()-2), min($customers->lastPage(),$customers->currentPage()+2)) as $page => $url)
        @if($page == $customers->currentPage())
            <span class="adm-page-btn adm-page-active">{{ $page }}</span>
        @else
            <a href="{{ $url }}" class="adm-page-btn">{{ $page }}</a>
        @endif
    @endforeach
    @if($customers->hasMorePages())
        <a href="{{ $customers->nextPageUrl() }}" class="adm-page-btn">›</a>
    @else
        <span class="adm-page-btn adm-page-disabled">›</span>
    @endif
</div>
@endif

@endsection
