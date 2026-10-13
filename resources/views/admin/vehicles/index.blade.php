@extends('layouts.admin')
@section('title', 'Vehicles')

@section('content')

{{-- Page Header --}}
<div class="adm-page-header">
    <div>
        <h1 class="adm-page-title">Fleet Management</h1>
        <p class="adm-page-sub">Manage all vehicles in the NexusRides fleet</p>
    </div>
    <a href="{{ route('admin.vehicles.create') }}" class="adm-btn adm-btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Vehicle
    </a>
</div>

{{-- Filters --}}
<div class="adm-panel adm-mb">
    <div class="adm-panel-body">
        <form method="GET" class="adm-filter-bar">
            <div class="adm-filter-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" class="adm-filter-input" placeholder="Search name, brand, plate…" value="{{ request('search') }}">
            </div>
            <select name="status" class="adm-filter-select">
                <option value="">All Statuses</option>
                @foreach(['available','hired','maintenance','retired'] as $s)
                <option value="{{ $s }}" {{ request('status')==$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="type" class="adm-filter-select">
                <option value="">All Types</option>
                @foreach(['sedan','suv','van','truck','bus','convertible','pickup'] as $t)
                <option value="{{ $t }}" {{ request('type')==$t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
            <button type="submit" class="adm-btn adm-btn-primary">Filter</button>
            @if(request()->anyFilled(['search','status','type']))
            <a href="{{ route('admin.vehicles.index') }}" class="adm-btn adm-btn-outline">Clear</a>
            @endif
        </form>
    </div>
</div>

{{-- Table --}}
<div class="adm-panel">
    <div class="adm-panel-head">
        <div>
            <h3 class="adm-panel-title">All Vehicles</h3>
            <p class="adm-panel-sub">{{ $vehicles->total() }} total</p>
        </div>
    </div>
    <div class="adm-panel-body adm-panel-body-flush">
        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Plate</th>
                        <th>Type</th>
                        <th>Seats</th>
                        <th>Price / Day</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $v)
                    <tr>
                        <td>
                            <div class="adm-vehicle-row">
                                <img src="{{ $v->image_url }}" alt="{{ $v->name }}" class="adm-vehicle-thumb">
                                <div>
                                    <div class="adm-fw600">{{ $v->name }}</div>
                                    <div class="adm-text-muted adm-text-sm">{{ $v->brand }} {{ $v->model }} · {{ $v->year }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="adm-mono">{{ $v->plate_number }}</span></td>
                        <td><span class="adm-pill adm-pill-navy">{{ ucfirst($v->type) }}</span></td>
                        <td class="adm-text-sm">{{ $v->seats }} seats</td>
                        <td class="adm-fw600">UGX {{ number_format($v->price_per_day, 0) }}</td>
                        <td>
                            <span class="adm-status adm-status-{{ match($v->status) {
                                'available'   => 'success',
                                'hired'       => 'info',
                                'maintenance' => 'warning',
                                default       => 'secondary'
                            } }}">{{ ucfirst($v->status) }}</span>
                        </td>
                        <td>
                            <div class="adm-row-actions">
                                <a href="{{ route('vehicles.show', $v) }}" target="_blank" class="adm-btn adm-btn-ghost adm-btn-xs" title="View public page">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a href="{{ route('admin.vehicles.edit', $v) }}" class="adm-btn adm-btn-outline adm-btn-xs">Edit</a>
                                <form method="POST" action="{{ route('admin.vehicles.destroy', $v) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($v->name) }}? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="adm-btn adm-btn-danger adm-btn-xs">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="adm-empty-row">
                            No vehicles found.
                            <a href="{{ route('admin.vehicles.create') }}" style="color:#0a2540;font-weight:600;">Add one →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
@if($vehicles->hasPages())
<div class="adm-pagination">
    @if($vehicles->onFirstPage())
        <span class="adm-page-btn adm-page-disabled">‹</span>
    @else
        <a href="{{ $vehicles->previousPageUrl() }}" class="adm-page-btn">‹</a>
    @endif
    @foreach($vehicles->getUrlRange(max(1,$vehicles->currentPage()-2), min($vehicles->lastPage(),$vehicles->currentPage()+2)) as $page => $url)
        @if($page == $vehicles->currentPage())
            <span class="adm-page-btn adm-page-active">{{ $page }}</span>
        @else
            <a href="{{ $url }}" class="adm-page-btn">{{ $page }}</a>
        @endif
    @endforeach
    @if($vehicles->hasMorePages())
        <a href="{{ $vehicles->nextPageUrl() }}" class="adm-page-btn">›</a>
    @else
        <span class="adm-page-btn adm-page-disabled">›</span>
    @endif
</div>
@endif

@endsection
