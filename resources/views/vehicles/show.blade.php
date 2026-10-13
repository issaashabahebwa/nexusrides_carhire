@extends('layouts.app')
@section('title', $vehicle->name)

@section('content')
<div style="background:var(--navy);color:#fff;padding:1.5rem;">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}" style="color:rgba(255,255,255,.7);">Home</a>
            <span class="sep">›</span>
            <a href="{{ route('vehicles.index') }}" style="color:rgba(255,255,255,.7);">Fleet</a>
            <span class="sep">›</span>
            <span style="color:#fff;">{{ $vehicle->name }}</span>
        </div>
    </div>
</div>

<div class="container section" style="padding-top:2rem;">
    <div class="grid-2" style="gap:2.5rem;align-items:start;">

        {{-- Left – Image + specs --}}
        <div>
            <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}" class="vehicle-detail-img">

            <div class="vehicle-specs">
                <div class="spec-item">
                    <div class="spec-icon">🚗</div>
                    <div class="spec-label">Type</div>
                    <div class="spec-value">{{ ucfirst($vehicle->type) }}</div>
                </div>
                <div class="spec-item">
                    <div class="spec-icon">👥</div>
                    <div class="spec-label">Seats</div>
                    <div class="spec-value">{{ $vehicle->seats }}</div>
                </div>
                <div class="spec-item">
                    <div class="spec-icon">⚙️</div>
                    <div class="spec-label">Transmission</div>
                    <div class="spec-value">{{ ucfirst($vehicle->transmission) }}</div>
                </div>
                <div class="spec-item">
                    <div class="spec-icon">⛽</div>
                    <div class="spec-label">Fuel</div>
                    <div class="spec-value">{{ ucfirst($vehicle->fuel_type) }}</div>
                </div>
                <div class="spec-item">
                    <div class="spec-icon">📅</div>
                    <div class="spec-label">Year</div>
                    <div class="spec-value">{{ $vehicle->year }}</div>
                </div>
                <div class="spec-item">
                    <div class="spec-icon">🎨</div>
                    <div class="spec-label">Color</div>
                    <div class="spec-value">{{ $vehicle->color ?? 'N/A' }}</div>
                </div>
            </div>

            @if($vehicle->description)
            <div class="card mt-3">
                <div class="card-header"><h3 class="card-title">Description</h3></div>
                <div class="card-body"><p>{{ $vehicle->description }}</p></div>
            </div>
            @endif
        </div>

        {{-- Right – Booking panel --}}
        <div>
            <div class="card" style="position:sticky;top:84px;">
                <div class="card-header">
                    <div>
                        <h2 style="color:var(--navy);margin-bottom:.2rem;">{{ $vehicle->name }}</h2>
                        <p style="color:#6c757d;margin:0;">{{ $vehicle->brand }} {{ $vehicle->model }} · {{ $vehicle->year }}</p>
                    </div>
                    <span class="badge badge-{{ $vehicle->status === 'available' ? 'success' : 'danger' }}">
                        {{ ucfirst($vehicle->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));color:#fff;border-radius:var(--radius);padding:1.2rem;margin-bottom:1.5rem;text-align:center;">
                        <div style="font-size:.85rem;opacity:.75;">Daily Rate</div>
                        <div style="font-size:2rem;font-weight:800;color:var(--gold);">UGX {{ number_format($vehicle->price_per_day,0) }}</div>
                        <div style="font-size:.8rem;opacity:.65;">per day</div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;margin-bottom:1rem;font-size:.88rem;">
                        <div style="background:var(--gray-50);padding:.8rem;border-radius:var(--radius);">
                            <div style="color:#6c757d;font-size:.78rem;">Plate Number</div>
                            <div style="font-weight:700;color:var(--navy);">{{ $vehicle->plate_number }}</div>
                        </div>
                        <div style="background:var(--gray-50);padding:.8rem;border-radius:var(--radius);">
                            <div style="color:#6c757d;font-size:.78rem;">Location</div>
                            <div style="font-weight:700;color:var(--navy);">{{ $vehicle->location ?? 'Kampala' }}</div>
                        </div>
                    </div>

                    @if($vehicle->status === 'available')
                    <form action="{{ route('vehicles.index') }}" method="GET" id="availabilityForm">
                        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                        <div class="form-row form-row-2" style="margin-bottom:1rem;">
                            <div class="form-group" style="margin:0;">
                                <label class="form-label">Pickup Date</label>
                                <input type="date" name="pickup_date" id="pickupDate" class="form-control"
                                       min="{{ date('Y-m-d') }}" value="{{ request('pickup_date') }}">
                            </div>
                            <div class="form-group" style="margin:0;">
                                <label class="form-label">Return Date</label>
                                <input type="date" name="return_date" id="returnDate" class="form-control"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ request('return_date') }}">
                            </div>
                        </div>
                        <div id="priceSummary" style="display:none;background:var(--gray-50);padding:1rem;border-radius:var(--radius);margin-bottom:1rem;font-size:.9rem;">
                            <div style="display:flex;justify-content:space-between;"><span>Days:</span><strong id="daysCount">0</strong></div>
                            <div style="display:flex;justify-content:space-between;margin-top:.3rem;"><span>Rate/day:</span><strong>UGX {{ number_format($vehicle->price_per_day,0) }}</strong></div>
                            <hr style="margin:.5rem 0;border:none;border-top:1px solid var(--gray-300);">
                            <div style="display:flex;justify-content:space-between;font-weight:700;color:var(--navy);"><span>Total:</span><strong id="totalPrice">UGX 0</strong></div>
                        </div>
                    </form>

                    @auth
                    <a href="{{ route('bookings.create', ['vehicle_id'=>$vehicle->id]) }}" class="btn btn-primary btn-lg btn-block" id="bookBtn">
                        🚗 Book This Vehicle
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-block">
                        Sign In to Book
                    </a>
                    <p style="text-align:center;font-size:.83rem;color:#6c757d;margin-top:.7rem;">
                        <a href="{{ route('register') }}" style="color:var(--navy);">Create a free account</a>
                    </p>
                    @endauth
                    @else
                    <div class="alert alert-warning">This vehicle is currently {{ $vehicle->status }} and not available for booking.</div>
                    @endif
                </div>
            </div>

            {{-- Payment methods --}}
            <div class="card mt-3">
                <div class="card-body" style="padding:1rem;">
                    <p style="font-size:.82rem;color:#6c757d;text-align:center;margin-bottom:.8rem;font-weight:600;">Accepted Payment Methods</p>
                    <div style="display:flex;justify-content:center;gap:1rem;flex-wrap:wrap;">
                        <span style="background:#FFC107;color:#000;padding:.3rem .8rem;border-radius:100px;font-size:.78rem;font-weight:700;">MTN MoMo</span>
                        <span style="background:#E63946;color:#fff;padding:.3rem .8rem;border-radius:100px;font-size:.78rem;font-weight:700;">Airtel Money</span>
                        <span style="background:#0a2540;color:#fff;padding:.3rem .8rem;border-radius:100px;font-size:.78rem;font-weight:700;">Card</span>
                        <span style="background:#2ecc71;color:#fff;padding:.3rem .8rem;border-radius:100px;font-size:.78rem;font-weight:700;">Cash</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Similar Vehicles --}}
    @if($similarVehicles->count())
    <div style="margin-top:3rem;">
        <h2 style="color:var(--navy);margin-bottom:1.5rem;">Similar Vehicles</h2>
        <div class="grid-3">
            @foreach($similarVehicles as $v)
            <div class="card vehicle-card">
                <div class="card-img">
                    <img src="{{ $v->image_url }}" alt="{{ $v->name }}" loading="lazy">
                    <span class="price-badge">UGX {{ number_format($v->price_per_day,0) }}/day</span>
                </div>
                <div class="card-body">
                    <div class="vehicle-name">{{ $v->name }}</div>
                    <div style="font-size:.82rem;color:#6c757d;">{{ $v->brand }} · {{ $v->seats }} seats · {{ ucfirst($v->transmission) }}</div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('vehicles.show',$v) }}" class="btn btn-outline btn-sm" style="flex:1;">View</a>
                    @auth
                    <a href="{{ route('bookings.create',['vehicle_id'=>$v->id]) }}" class="btn btn-primary btn-sm" style="flex:1;">Book</a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm" style="flex:1;">Book</a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
const rate = {{ $vehicle->price_per_day }};
function updatePrice() {
    const p = document.getElementById('pickupDate')?.value;
    const r = document.getElementById('returnDate')?.value;
    if (!p || !r) return;
    const d = Math.max(1, Math.ceil((new Date(r)-new Date(p))/(1000*60*60*24)));
    document.getElementById('daysCount').textContent = d;
    document.getElementById('totalPrice').textContent = 'UGX ' + (d * rate).toLocaleString();
    document.getElementById('priceSummary').style.display = 'block';

    const bookBtn = document.getElementById('bookBtn');
    if (bookBtn) {
        bookBtn.href = '{{ route("bookings.create") }}?vehicle_id={{ $vehicle->id }}&pickup_date=' + p + '&return_date=' + r;
    }
}
document.getElementById('pickupDate')?.addEventListener('change', updatePrice);
document.getElementById('returnDate')?.addEventListener('change', updatePrice);
</script>
@endpush
@endsection
