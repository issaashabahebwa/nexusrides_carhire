@extends('layouts.app')
@section('title','Make Payment')

@section('content')
<div style="background:var(--navy);padding:1.5rem;">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}" style="color:rgba(255,255,255,.7);">Home</a>
            <span class="sep" style="color:rgba(255,255,255,.5);">›</span>
            <a href="{{ route('bookings.index') }}" style="color:rgba(255,255,255,.7);">Bookings</a>
            <span class="sep" style="color:rgba(255,255,255,.5);">›</span>
            <span style="color:#fff;">Payment</span>
        </div>
    </div>
</div>

<div class="container-sm section" style="padding-top:2rem;">

    {{-- Booking summary --}}
    <div class="card" style="margin-bottom:1.5rem;border-left:4px solid var(--navy);">
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
                <div style="display:flex;gap:1rem;align-items:center;">
                    <img src="{{ $booking->vehicle->image_url }}" alt="" style="width:70px;height:52px;object-fit:cover;border-radius:var(--radius);">
                    <div>
                        <div style="font-weight:700;color:var(--navy);">{{ $booking->vehicle->name }}</div>
                        <div style="font-size:.82rem;color:#6c757d;">Ref: {{ $booking->booking_reference }}</div>
                        <div style="font-size:.82rem;color:#6c757d;">{{ $booking->pickup_date->format('d M') }} → {{ $booking->return_date->format('d M Y') }} · {{ $booking->days }} day{{ $booking->days>1?'s':'' }}</div>
                    </div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:.8rem;color:#6c757d;">Amount Due</div>
                    <div style="font-size:1.5rem;font-weight:800;color:var(--navy);">UGX {{ number_format($booking->total_amount,0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2 class="card-title">💳 Select Payment Method</h2></div>
        <div class="card-body">

            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
                <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
            </div>
            @endif

            <form method="POST" action="{{ route('payments.store',$booking) }}" id="paymentForm">
                @csrf

                {{-- Payment method selection --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
                    @foreach([
                        ['mtn_momo',     'MTN Mobile Money', 'Pay via MTN MoMo',     '📱', '#FFC107', '#000'],
                        ['airtel_money', 'Airtel Money',     'Pay via Airtel Money', '📱', '#E63946', '#fff'],
                        ['card',         'Card Payment',     'Visa / Mastercard',    '💳', '#0a2540', '#fff'],
                        ['cash',         'Cash',             'Pay at pickup',        '💵', '#2ecc71', '#fff'],
                    ] as [$val,$name,$desc,$icon,$bg,$fg])
                    <label class="payment-method" id="method-{{ $val }}" onclick="selectMethod('{{ $val }}')">
                        <input type="radio" name="method" value="{{ $val }}" {{ old('method') == $val ? 'checked' : '' }}>
                        <span style="width:44px;height:44px;border-radius:var(--radius);background:{{ $bg }};color:{{ $fg }};display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">{{ $icon }}</span>
                        <div>
                            <div class="method-name">{{ $name }}</div>
                            <div class="method-desc">{{ $desc }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>

                {{-- Mobile money fields --}}
                <div id="momoFields" style="display:none;">
                    <div class="form-group">
                        <label class="form-label">Mobile Money Number <span style="color:var(--red)">*</span></label>
                        <input type="tel" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror"
                               placeholder="e.g. 0770 000 000" value="{{ old('phone_number') }}">
                        @error('phone_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        <div class="form-text">Enter the registered mobile money number.</div>
                    </div>
                </div>

                {{-- Card fields --}}
                <div id="cardFields" style="display:none;">
                    <div class="form-group">
                        <label class="form-label">Card Number <span style="color:var(--red)">*</span></label>
                        <input type="text" name="card_number" class="form-control @error('card_number') is-invalid @enderror"
                               placeholder="1234 5678 9012 3456" maxlength="19" value="{{ old('card_number') }}">
                        @error('card_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-row form-row-2">
                        <div class="form-group">
                            <label class="form-label">Cardholder Name <span style="color:var(--red)">*</span></label>
                            <input type="text" name="card_name" class="form-control @error('card_name') is-invalid @enderror"
                                   placeholder="Full name on card" value="{{ old('card_name') }}">
                            @error('card_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Expiry Date</label>
                            <input type="text" class="form-control" placeholder="MM / YY" maxlength="7">
                        </div>
                    </div>
                </div>

                <div style="background:#f0f4ff;border-radius:var(--radius);padding:1rem;font-size:.85rem;color:#495057;margin-bottom:1.5rem;">
                    <strong>🔒 Secure Payment:</strong> Your payment information is encrypted and processed securely. NexusRides does not store card details.
                </div>

                <div style="display:flex;gap:1rem;">
                    <a href="{{ route('bookings.show',$booking) }}" class="btn btn-outline">← Back</a>
                    <button type="submit" class="btn btn-primary" style="flex:1;font-size:1rem;">
                        Pay UGX {{ number_format($booking->total_amount,0) }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function selectMethod(val) {
    document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
    document.getElementById('method-' + val)?.classList.add('selected');
    document.querySelector('input[value="'+val+'"]').checked = true;
    document.getElementById('momoFields').style.display = (val === 'mtn_momo' || val === 'airtel_money') ? 'block' : 'none';
    document.getElementById('cardFields').style.display  = val === 'card' ? 'block' : 'none';
}
// Restore on page load
const old = '{{ old('method') }}';
if (old) selectMethod(old);

// Card number formatting
document.querySelector('input[placeholder="1234 5678 9012 3456"]')?.addEventListener('input', function() {
    this.value = this.value.replace(/\D/g,'').replace(/(.{4})/g,'$1 ').trim().slice(0,19);
});
</script>
@endpush
@endsection
