@extends('layouts.app')
@section('title','New Booking')

@section('content')
<div style="background:var(--navy);padding:1.5rem;">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}" style="color:rgba(255,255,255,.7);">Home</a>
            <span class="sep" style="color:rgba(255,255,255,.5);">›</span>
            <a href="{{ route('vehicles.index') }}" style="color:rgba(255,255,255,.7);">Fleet</a>
            <span class="sep" style="color:rgba(255,255,255,.5);">›</span>
            <span style="color:#fff;">New Booking</span>
        </div>
    </div>
</div>

<div class="container-sm section" style="padding-top:2rem;">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">🚗 Book a Vehicle</h2>
        </div>
        <div class="card-body">

            @if($errors->any())
            <div class="alert alert-danger">
                <div>
                    @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
                </div>
                <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
            </div>
            @endif

            <form method="POST" action="{{ route('bookings.store') }}">
                @csrf

                {{-- Vehicle selector --}}
                <div class="form-group">
                    <label class="form-label">Vehicle <span style="color:var(--red)">*</span></label>
                    @if($vehicle)
                        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                        <div style="display:flex;align-items:center;gap:1rem;background:var(--gray-50);padding:1rem;border-radius:var(--radius);border:1.5px solid var(--gray-300);">
                            <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}" style="width:80px;height:60px;object-fit:cover;border-radius:var(--radius);">
                            <div>
                                <div style="font-weight:700;color:var(--navy);">{{ $vehicle->name }}</div>
                                <div style="font-size:.83rem;color:#6c757d;">{{ $vehicle->brand }} · {{ ucfirst($vehicle->type) }} · {{ $vehicle->seats }} seats</div>
                                <div style="font-size:.9rem;font-weight:700;color:var(--red);">UGX {{ number_format($vehicle->price_per_day,0) }}/day</div>
                            </div>
                            <a href="{{ route('vehicles.index') }}" class="btn btn-outline btn-sm" style="margin-left:auto;">Change</a>
                        </div>
                    @else
                        <select name="vehicle_id" class="form-control form-select @error('vehicle_id') is-invalid @enderror" required>
                            <option value="">-- Select a vehicle --</option>
                            @foreach(\App\Models\Vehicle::where('status','available')->orderBy('name')->get() as $v)
                            <option value="{{ $v->id }}" {{ old('vehicle_id') == $v->id ? 'selected' : '' }}>
                                {{ $v->name }} – {{ $v->brand }} {{ $v->model }} (UGX {{ number_format($v->price_per_day,0) }}/day)
                            </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    @endif
                </div>

                <div class="form-row form-row-2">
                    <div class="form-group">
                        <label class="form-label">Pickup Date <span style="color:var(--red)">*</span></label>
                        <input type="date" name="pickup_date" class="form-control @error('pickup_date') is-invalid @enderror"
                               min="{{ date('Y-m-d') }}" value="{{ old('pickup_date', request('pickup_date')) }}" required id="pickupDate">
                        @error('pickup_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Return Date <span style="color:var(--red)">*</span></label>
                        <input type="date" name="return_date" class="form-control @error('return_date') is-invalid @enderror"
                               min="{{ date('Y-m-d',strtotime('+1 day')) }}" value="{{ old('return_date', request('return_date')) }}" required id="returnDate">
                        @error('return_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Price summary --}}
                @if($vehicle)
                <div id="priceSummary" style="background:var(--gray-50);border:1.5px solid var(--gray-300);border-radius:var(--radius);padding:1rem;margin-bottom:1rem;display:none;">
                    <div style="display:flex;justify-content:space-between;font-size:.9rem;"><span>Number of days:</span><strong id="daysCount">0</strong></div>
                    <div style="display:flex;justify-content:space-between;font-size:.9rem;margin-top:.3rem;"><span>Rate per day:</span><strong>UGX {{ number_format($vehicle->price_per_day,0) }}</strong></div>
                    <hr style="margin:.7rem 0;border:none;border-top:1px solid var(--gray-300);">
                    <div style="display:flex;justify-content:space-between;font-weight:700;font-size:1rem;color:var(--navy);"><span>Estimated Total:</span><span id="totalPrice">UGX 0</span></div>
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Pickup Location <span style="color:var(--red)">*</span></label>
                    <input type="text" name="pickup_location" class="form-control @error('pickup_location') is-invalid @enderror"
                           value="{{ old('pickup_location') }}" placeholder="e.g. Kampala City Centre, Entebbe Airport" required>
                    @error('pickup_location')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Return Location <span style="color:#6c757d;font-weight:400;">(leave blank if same as pickup)</span></label>
                    <input type="text" name="return_location" class="form-control @error('return_location') is-invalid @enderror"
                           value="{{ old('return_location') }}" placeholder="Default: same as pickup">
                    @error('return_location')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Additional Notes</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Any special requirements or requests…">{{ old('notes') }}</textarea>
                </div>

                <div style="background:#f0f4ff;border-radius:var(--radius);padding:1rem;font-size:.85rem;color:#495057;margin-bottom:1.2rem;">
                    <strong>ℹ️ Note:</strong> Your booking will be reviewed and approved by our team. Payment is required only after approval.
                </div>

                <div style="display:flex;gap:1rem;">
                    <a href="{{ $vehicle ? route('vehicles.show',$vehicle) : route('vehicles.index') }}" class="btn btn-outline">← Cancel</a>
                    <button type="submit" class="btn btn-primary" style="flex:1;">Submit Booking Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($vehicle)
@push('scripts')
<script>
const rate = {{ $vehicle->price_per_day }};
function calc() {
    const p = document.getElementById('pickupDate')?.value;
    const r = document.getElementById('returnDate')?.value;
    if (!p || !r) return;
    const days = Math.max(1, Math.ceil((new Date(r)-new Date(p))/(86400000)));
    document.getElementById('daysCount').textContent = days;
    document.getElementById('totalPrice').textContent = 'UGX ' + (days*rate).toLocaleString();
    document.getElementById('priceSummary').style.display = 'block';
}
document.getElementById('pickupDate')?.addEventListener('change', calc);
document.getElementById('returnDate')?.addEventListener('change', calc);
window.addEventListener('load', calc);
</script>
@endpush
@endif
@endsection
