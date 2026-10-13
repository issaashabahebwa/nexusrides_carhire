@extends('layouts.app')
@section('title','Browse Fleet')

@section('content')
<div style="background:var(--navy);color:#fff;padding:2rem 1.5rem;">
    <div class="container">
        <div class="breadcrumb" style="margin-bottom:.8rem;">
            <a href="{{ route('home') }}" style="color:rgba(255,255,255,.7);">Home</a>
            <span class="sep">›</span>
            <span style="color:#fff;">Fleet</span>
        </div>
        <h1 style="color:#fff;font-size:1.8rem;">Our Vehicle Fleet</h1>
        <p style="color:rgba(255,255,255,.75);">{{ $vehicles->total() }} vehicle{{ $vehicles->total() != 1 ? 's' : '' }} available</p>
    </div>
</div>

<div class="container section" style="padding-top:2rem;">
    <div class="vehicles-layout">

        {{-- ── Filter Sidebar ───────────────────────────────── --}}
        <aside>
            <div class="filter-card">
                <h3>🔍 Filter Vehicles</h3>
                <form method="GET" action="{{ route('vehicles.index') }}">

                    <div class="filter-group">
                        <label>Pickup Date</label>
                        <input type="date" name="pickup_date" class="form-control"
                               min="{{ date('Y-m-d') }}" value="{{ request('pickup_date') }}">
                    </div>
                    <div class="filter-group">
                        <label>Return Date</label>
                        <input type="date" name="return_date" class="form-control"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ request('return_date') }}">
                    </div>

                    <div class="filter-group">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="Brand, model, name…" value="{{ request('search') }}">
                    </div>

                    <div class="filter-group">
                        <label>Vehicle Type</label>
                        <select name="type" class="form-control form-select">
                            <option value="">All Types</option>
                            @foreach($types as $t)
                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Transmission</label>
                        <select name="transmission" class="form-control form-select">
                            <option value="">Any</option>
                            <option value="automatic" {{ request('transmission')=='automatic'?'selected':'' }}>Automatic</option>
                            <option value="manual"    {{ request('transmission')=='manual'?'selected':'' }}>Manual</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Min Seats</label>
                        <select name="seats" class="form-control form-select">
                            <option value="">Any</option>
                            @foreach([2,4,5,7,9,12,15,30] as $s)
                            <option value="{{ $s }}" {{ request('seats')==$s?'selected':'' }}>{{ $s }}+ seats</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row form-row-2" style="margin-bottom:1rem;">
                        <div>
                            <label class="filter-group" style="font-size:.85rem;font-weight:600;color:var(--gray-700);">Min Price (UGX)</label>
                            <input type="number" name="min_price" class="form-control"
                                   placeholder="0" value="{{ request('min_price') }}" min="0">
                        </div>
                        <div>
                            <label class="filter-group" style="font-size:.85rem;font-weight:600;color:var(--gray-700);">Max Price (UGX)</label>
                            <input type="number" name="max_price" class="form-control"
                                   placeholder="Any" value="{{ request('max_price') }}" min="0">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-navy btn-block">Apply Filters</button>
                    @if(request()->anyFilled(['type','search','seats','transmission','min_price','max_price','pickup_date','return_date']))
                    <a href="{{ route('vehicles.index') }}" class="btn btn-outline btn-block" style="margin-top:.5rem;">Clear Filters</a>
                    @endif
                </form>
            </div>
        </aside>

        {{-- ── Vehicle Grid ─────────────────────────────────── --}}
        <div>
            @if($vehicles->count())
            <div class="grid-3" style="grid-template-columns:repeat(auto-fill,minmax(260px,1fr));">
                @foreach($vehicles as $vehicle)
                <div class="card vehicle-card">
                    <div class="card-img">
                        <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}" loading="lazy">
                        <span class="price-badge">UGX {{ number_format($vehicle->price_per_day,0) }}/day</span>
                    </div>
                    <div class="card-body">
                        <div class="vehicle-name">{{ $vehicle->name }}</div>
                        <div style="font-size:.82rem;color:#6c757d;margin-bottom:.4rem;">
                            {{ $vehicle->brand }} · {{ $vehicle->model }} · {{ $vehicle->year }}
                        </div>
                        <div class="vehicle-meta">
                            <span class="meta-tag">🚗 {{ ucfirst($vehicle->type) }}</span>
                            <span class="meta-tag">👥 {{ $vehicle->seats }}</span>
                            <span class="meta-tag">⚙️ {{ ucfirst($vehicle->transmission) }}</span>
                            @if($vehicle->color)<span class="meta-tag">🎨 {{ $vehicle->color }}</span>@endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-outline btn-sm" style="flex:1;">Details</a>
                        @auth
                        <a href="{{ route('bookings.create', ['vehicle_id'=>$vehicle->id] + request()->only(['pickup_date','return_date'])) }}"
                           class="btn btn-primary btn-sm" style="flex:1;">Book</a>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm" style="flex:1;">Book</a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($vehicles->hasPages())
            <div class="pagination" style="justify-content:center;">
                {{-- Previous --}}
                @if($vehicles->onFirstPage())
                    <span class="disabled">‹</span>
                @else
                    <a href="{{ $vehicles->previousPageUrl() }}">‹</a>
                @endif

                @foreach($vehicles->getUrlRange(max(1,$vehicles->currentPage()-2), min($vehicles->lastPage(),$vehicles->currentPage()+2)) as $page => $url)
                    @if($page == $vehicles->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($vehicles->hasMorePages())
                    <a href="{{ $vehicles->nextPageUrl() }}">›</a>
                @else
                    <span class="disabled">›</span>
                @endif
            </div>
            @endif

            @else
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <h3>No vehicles found</h3>
                <p>Try adjusting your filters or clearing the search.</p>
                <a href="{{ route('vehicles.index') }}" class="btn btn-navy mt-3">Clear All Filters</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
