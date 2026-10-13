@extends('layouts.admin')
@section('title', 'Booking ' . $booking->booking_reference)

@section('content')

{{-- Page Header --}}
<div class="adm-page-header">
    <div>
        <h1 class="adm-page-title">Booking Details</h1>
        <p class="adm-page-sub">
            <span class="adm-mono" style="font-size:.9rem;">{{ $booking->booking_reference }}</span>
            &nbsp;·&nbsp; Submitted {{ $booking->created_at->format('d M Y, H:i') }}
        </p>
    </div>
    <div class="adm-page-header-actions">
        @if($booking->status === 'pending')
        <form method="POST" action="{{ route('admin.bookings.approve', $booking) }}">
            @csrf
            <button type="submit" class="adm-btn adm-btn-success"
                    onclick="return confirm('Approve this booking?')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Approve
            </button>
        </form>
        <button type="button" class="adm-btn adm-btn-danger"
                onclick="document.getElementById('declinePanel').classList.remove('adm-hidden')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            Decline
        </button>
        @endif
        @if($booking->status === 'active')
        <form method="POST" action="{{ route('admin.bookings.complete', $booking) }}">
            @csrf
            <button type="submit" class="adm-btn adm-btn-success"
                    onclick="return confirm('Mark as completed?')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Mark Completed
            </button>
        </form>
        @endif
        <a href="{{ route('admin.bookings.index') }}" class="adm-btn adm-btn-outline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Back
        </a>
    </div>
</div>

{{-- Decline Panel --}}
@if($booking->status === 'pending')
<div id="declinePanel" class="adm-panel adm-mb adm-hidden">
    <div class="adm-panel-head">
        <h3 class="adm-panel-title" style="color:#991b1b;">Decline Booking</h3>
        <button class="adm-btn adm-btn-ghost adm-btn-xs"
                onclick="document.getElementById('declinePanel').classList.add('adm-hidden')">Dismiss</button>
    </div>
    <div class="adm-panel-body">
        <form method="POST" action="{{ route('admin.bookings.decline', $booking) }}">
            @csrf
            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Reason for declining <span style="color:#9ca3af;font-weight:400;">(optional)</span></label>
                <textarea name="admin_notes" class="form-control" rows="3"
                          placeholder="Explain why this booking is being declined…"></textarea>
            </div>
            <div style="display:flex;gap:.75rem;">
                <button type="button" class="adm-btn adm-btn-outline"
                        onclick="document.getElementById('declinePanel').classList.add('adm-hidden')">Cancel</button>
                <button type="submit" class="adm-btn adm-btn-danger">Confirm Decline</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- Two-column grid --}}
<div class="adm-detail-grid">

    {{-- LEFT COLUMN --}}
    <div class="adm-detail-col">

        {{-- Booking Info --}}
        <div class="adm-panel adm-mb">
            <div class="adm-panel-head">
                <h3 class="adm-panel-title">Booking Info</h3>
                <span class="adm-status adm-status-{{ $booking->status_badge }}">{{ ucfirst($booking->status) }}</span>
            </div>
            <div class="adm-panel-body">
                <div class="adm-info-table">
                    @foreach([
                        ['Reference',   $booking->booking_reference],
                        ['Pickup Date', $booking->pickup_date->format('l, d M Y')],
                        ['Return Date', $booking->return_date->format('l, d M Y')],
                        ['Duration',    $booking->days.' day'.($booking->days>1?'s':'')],
                        ['Pickup From', $booking->pickup_location],
                        ['Return To',   $booking->return_location ?? $booking->pickup_location],
                    ] as [$lbl, $val])
                    <div class="adm-info-row">
                        <span class="adm-info-label">{{ $lbl }}</span>
                        <span class="adm-info-value">{{ $val }}</span>
                    </div>
                    @endforeach
                </div>

                @if($booking->notes)
                <div class="adm-note-box adm-note-gray">
                    <div class="adm-note-title">Customer Notes</div>
                    <p class="adm-note-text">{{ $booking->notes }}</p>
                </div>
                @endif

                @if($booking->admin_notes)
                <div class="adm-note-box adm-note-amber">
                    <div class="adm-note-title">Admin Notes</div>
                    <p class="adm-note-text">{{ $booking->admin_notes }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Vehicle --}}
        <div class="adm-panel">
            <div class="adm-panel-head">
                <h3 class="adm-panel-title">Vehicle</h3>
            </div>
            <div class="adm-panel-body">
                <div style="display:flex;gap:1rem;align-items:center;">
                    <img src="{{ $booking->vehicle->image_url }}" alt="{{ $booking->vehicle->name }}"
                         style="width:96px;height:68px;object-fit:cover;border-radius:10px;flex-shrink:0;">
                    <div>
                        <div class="adm-fw600" style="font-size:.95rem;margin-bottom:.2rem;">{{ $booking->vehicle->name }}</div>
                        <div class="adm-text-muted adm-text-sm">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} · {{ $booking->vehicle->year }}</div>
                        <div class="adm-text-sm" style="margin-top:.3rem;">
                            Plate: <span class="adm-mono">{{ $booking->vehicle->plate_number }}</span>
                        </div>
                        <div style="margin-top:.5rem;">
                            <span class="adm-status adm-status-{{ match($booking->vehicle->status) {
                                'available'   => 'success',
                                'hired'       => 'info',
                                'maintenance' => 'warning',
                                default       => 'secondary'
                            } }}">{{ ucfirst($booking->vehicle->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div class="adm-detail-col">

        {{-- Customer --}}
        <div class="adm-panel adm-mb">
            <div class="adm-panel-head">
                <h3 class="adm-panel-title">Customer</h3>
                <a href="{{ route('admin.customers.show', $booking->user) }}" class="adm-btn adm-btn-ghost adm-btn-xs">View Profile</a>
            </div>
            <div class="adm-panel-body">
                <div style="display:flex;align-items:center;gap:.9rem;margin-bottom:1.1rem;">
                    <div style="width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#0a2540,#1a4a80);color:#f4a522;font-size:1.1rem;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        {{ strtoupper(substr($booking->user->name,0,1)) }}
                    </div>
                    <div>
                        <div class="adm-fw600">{{ $booking->user->name }}</div>
                        <div class="adm-text-muted adm-text-sm">{{ $booking->user->email }}</div>
                    </div>
                </div>
                <div class="adm-info-table">
                    @foreach([
                        ['Phone',      $booking->user->phone ?? 'N/A'],
                        ['Address',    $booking->user->address ?? 'N/A'],
                        ['ID/Passport',$booking->user->id_number ?? 'N/A'],
                    ] as [$lbl, $val])
                    <div class="adm-info-row">
                        <span class="adm-info-label">{{ $lbl }}</span>
                        <span class="adm-info-value">{{ $val }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Payment Summary --}}
        <div class="adm-panel adm-mb">
            <div class="adm-panel-head">
                <h3 class="adm-panel-title">Payment Summary</h3>
            </div>
            <div class="adm-panel-body">
                <div class="adm-info-table" style="margin-bottom:1rem;">
                    <div class="adm-info-row">
                        <span class="adm-info-label">Rate / day</span>
                        <span class="adm-info-value">UGX {{ number_format($booking->vehicle->price_per_day, 0) }}</span>
                    </div>
                    <div class="adm-info-row">
                        <span class="adm-info-label">Days</span>
                        <span class="adm-info-value">{{ $booking->days }}</span>
                    </div>
                    <div class="adm-info-row" style="border-top:2px solid #e5e7eb;padding-top:.75rem;margin-top:.25rem;">
                        <span class="adm-info-label" style="font-weight:700;color:#111827;font-size:.95rem;">Total</span>
                        <span class="adm-info-value adm-fw600" style="font-size:1.05rem;color:#0a2540;">UGX {{ number_format($booking->total_amount, 0) }}</span>
                    </div>
                </div>

                @if($booking->payment && $booking->payment->status === 'completed')
                <div class="adm-note-box adm-note-green">
                    <div class="adm-note-title">✓ Payment Confirmed</div>
                    <div class="adm-text-sm" style="margin-top:.4rem;">
                        <div>Method: <strong>{{ $booking->payment->method_label }}</strong></div>
                        <div>Txn ID: <span class="adm-mono">{{ $booking->payment->transaction_id }}</span></div>
                        <div>Date: {{ $booking->payment->paid_at?->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @else
                <div class="adm-note-box adm-note-amber">
                    <div class="adm-note-title">⏳ Payment Pending</div>
                    <p class="adm-note-text">Payment has not been received yet.</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Approval info --}}
        @if($booking->approved_at)
        <div class="adm-panel">
            <div class="adm-panel-body">
                <div style="display:flex;align-items:center;gap:.6rem;font-size:.85rem;color:#6b7280;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>
                    Approved by <strong style="color:#111827;">{{ $booking->approvedBy?->name }}</strong>
                    &nbsp;on {{ $booking->approved_at->format('d M Y, H:i') }}
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
