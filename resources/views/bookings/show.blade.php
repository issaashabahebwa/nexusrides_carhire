@extends('layouts.app')
@section('title','Booking '.$booking->booking_reference)

@section('content')
<div style="background:var(--navy);padding:1.5rem;">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}" style="color:rgba(255,255,255,.7);">Home</a>
            <span class="sep" style="color:rgba(255,255,255,.5);">›</span>
            <a href="{{ route('bookings.index') }}" style="color:rgba(255,255,255,.7);">My Bookings</a>
            <span class="sep" style="color:rgba(255,255,255,.5);">›</span>
            <span style="color:#fff;">{{ $booking->booking_reference }}</span>
        </div>
    </div>
</div>

<div class="container section" style="padding-top:2rem;max-width:900px;">

    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h2 style="color:var(--navy);">Booking {{ $booking->booking_reference }}</h2>
            <p style="color:#6c757d;">Submitted {{ $booking->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div style="display:flex;gap:.8rem;flex-wrap:wrap;">
            <span class="badge badge-{{ $booking->status_badge }}" style="font-size:.9rem;padding:.4rem 1rem;">
                {{ ucfirst($booking->status) }}
            </span>
            @if(in_array($booking->status,['pending','approved']))
            <form method="POST" action="{{ route('bookings.cancel',$booking) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Cancel Booking</button>
            </form>
            @endif
            @if($booking->status === 'approved' && !($booking->payment && $booking->payment->status === 'completed'))
            <a href="{{ route('payments.create',$booking) }}" class="btn btn-primary">💳 Pay Now</a>
            @endif
        </div>
    </div>

    <div class="grid-2" style="gap:1.5rem;align-items:start;">

        {{-- Booking details --}}
        <div>
            <div class="card">
                <div class="card-header"><h3 class="card-title">Vehicle Details</h3></div>
                <div class="card-body">
                    <div style="display:flex;gap:1rem;align-items:center;margin-bottom:1rem;">
                        <img src="{{ $booking->vehicle->image_url }}" alt="" style="width:100px;height:74px;object-fit:cover;border-radius:var(--radius);">
                        <div>
                            <div style="font-weight:700;color:var(--navy);font-size:1.05rem;">{{ $booking->vehicle->name }}</div>
                            <div style="font-size:.85rem;color:#6c757d;">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</div>
                            <div style="font-size:.82rem;color:#6c757d;">Plate: {{ $booking->vehicle->plate_number }}</div>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;font-size:.88rem;">
                        <div style="background:var(--gray-50);padding:.7rem;border-radius:var(--radius);">
                            <div style="color:#6c757d;font-size:.75rem;">Type</div>
                            <div style="font-weight:600;">{{ ucfirst($booking->vehicle->type) }}</div>
                        </div>
                        <div style="background:var(--gray-50);padding:.7rem;border-radius:var(--radius);">
                            <div style="color:#6c757d;font-size:.75rem;">Seats</div>
                            <div style="font-weight:600;">{{ $booking->vehicle->seats }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header"><h3 class="card-title">Booking Information</h3></div>
                <div class="card-body">
                    <table style="width:100%;font-size:.9rem;border-collapse:collapse;">
                        @foreach([
                            ['Reference',    $booking->booking_reference],
                            ['Pickup Date',  $booking->pickup_date->format('l, d M Y')],
                            ['Return Date',  $booking->return_date->format('l, d M Y')],
                            ['Duration',     $booking->days.' day'.($booking->days>1?'s':'')],
                            ['Pickup From',  $booking->pickup_location],
                            ['Return To',    $booking->return_location ?? $booking->pickup_location],
                        ] as [$label, $val])
                        <tr>
                            <td style="padding:.5rem 0;color:#6c757d;width:40%;">{{ $label }}</td>
                            <td style="padding:.5rem 0;font-weight:600;color:var(--navy);">{{ $val }}</td>
                        </tr>
                        @endforeach
                    </table>
                    @if($booking->notes)
                    <div style="background:var(--gray-50);border-radius:var(--radius);padding:.8rem;margin-top:.8rem;">
                        <div style="font-size:.78rem;color:#6c757d;margin-bottom:.3rem;">Your Notes:</div>
                        <p style="font-size:.88rem;margin:0;">{{ $booking->notes }}</p>
                    </div>
                    @endif
                    @if($booking->admin_notes)
                    <div style="background:#fff3cd;border-radius:var(--radius);padding:.8rem;margin-top:.8rem;">
                        <div style="font-size:.78rem;color:#856404;margin-bottom:.3rem;">Admin Note:</div>
                        <p style="font-size:.88rem;margin:0;color:#856404;">{{ $booking->admin_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Cost & Payment --}}
        <div>
            <div class="card">
                <div class="card-header"><h3 class="card-title">Cost Summary</h3></div>
                <div class="card-body">
                    <div style="font-size:.9rem;">
                        <div style="display:flex;justify-content:space-between;padding:.4rem 0;border-bottom:1px solid var(--gray-200);">
                            <span style="color:#6c757d;">Daily Rate</span>
                            <span>UGX {{ number_format($booking->vehicle->price_per_day,0) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:.4rem 0;border-bottom:1px solid var(--gray-200);">
                            <span style="color:#6c757d;">× Days</span>
                            <span>{{ $booking->days }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:.8rem 0;font-size:1.1rem;font-weight:800;color:var(--navy);">
                            <span>Total</span>
                            <span>UGX {{ number_format($booking->total_amount,0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header"><h3 class="card-title">Payment Status</h3></div>
                <div class="card-body">
                    @if($booking->payment && $booking->payment->status === 'completed')
                    <div style="text-align:center;padding:1rem;">
                        <div style="font-size:2.5rem;margin-bottom:.5rem;">✅</div>
                        <div style="font-weight:700;color:var(--success);font-size:1.1rem;">Payment Confirmed</div>
                        <div style="font-size:.85rem;color:#6c757d;margin-top:.5rem;">
                            Method: {{ $booking->payment->method_label }}<br>
                            Txn ID: {{ $booking->payment->transaction_id }}<br>
                            Paid: {{ $booking->payment->paid_at?->format('d M Y, H:i') }}
                        </div>
                    </div>
                    @elseif($booking->status === 'approved')
                    <div style="text-align:center;padding:1rem;">
                        <div style="font-size:2.5rem;margin-bottom:.5rem;">💳</div>
                        <div style="font-weight:700;color:var(--warning);margin-bottom:.8rem;">Payment Required</div>
                        <p style="font-size:.85rem;color:#6c757d;margin-bottom:1rem;">Your booking is approved. Please complete payment to confirm your rental.</p>
                        <a href="{{ route('payments.create',$booking) }}" class="btn btn-primary btn-block">Pay UGX {{ number_format($booking->total_amount,0) }}</a>
                    </div>
                    @elseif($booking->status === 'pending')
                    <div style="text-align:center;padding:1rem;">
                        <div style="font-size:2.5rem;margin-bottom:.5rem;">⏳</div>
                        <div style="font-weight:700;color:var(--warning);">Awaiting Approval</div>
                        <p style="font-size:.85rem;color:#6c757d;margin-top:.5rem;">Payment will be required once your booking is approved.</p>
                    </div>
                    @else
                    <div style="text-align:center;padding:1rem;color:#6c757d;">
                        <div style="font-size:2rem;margin-bottom:.5rem;">🚫</div>
                        Booking {{ $booking->status }}.
                    </div>
                    @endif
                </div>
            </div>

            @if($booking->approved_at)
            <div class="card mt-3">
                <div class="card-body" style="font-size:.85rem;color:#6c757d;">
                    ✅ Approved by {{ $booking->approvedBy?->name ?? 'Admin' }} on {{ $booking->approved_at->format('d M Y, H:i') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
