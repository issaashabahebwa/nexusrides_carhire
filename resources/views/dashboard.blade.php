@extends('layouts.app')
@section('title','My Dashboard')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600;700;800&display=swap');
*{box-sizing:border-box;}
:root{
  --nr-navy:#0a2540;--nr-gold:#C9A84C;--nr-gold2:#E8C96A;
  --nr-burg:#8B1A2E;--nr-green:#166534;--nr-cream:#F8F4EE;
  --nr-gray:#6c757d;--nr-border:#e9ecef;
  --fn-head:'Playfair Display',Georgia,serif;
  --fn-body:'Inter','Segoe UI',sans-serif;
}

/* ─── Page wrapper ─────────────────────────────── */
.pg-wrap{background:#f0f2f5;min-height:100vh;font-family:var(--fn-body);}

/* ─── Hero header ──────────────────────────────── */
.pg-hero{
  background:linear-gradient(120deg,#061729 0%,#0a2540 45%,#5e1020 100%);
  padding:3rem clamp(1rem,5vw,2.5rem) 5rem;
  position:relative;overflow:hidden;
}
.pg-hero::after{
  content:'';position:absolute;inset:0;
  background:url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.025'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
  pointer-events:none;
}
.pg-hero-inner{max-width:1200px;margin:0 auto;position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:2rem;flex-wrap:wrap;}
.pg-hero-label{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(255,255,255,.45);margin-bottom:.45rem;}
.pg-hero h1{font-family:var(--fn-head);font-size:clamp(1.6rem,3.5vw,2.5rem);color:#fff;font-weight:700;margin-bottom:.3rem;line-height:1.15;}
.pg-hero h1 em{font-style:normal;color:var(--nr-gold);}
.pg-hero-sub{font-size:.88rem;color:rgba(255,255,255,.58);}
.pg-hero-btns{display:flex;gap:.7rem;flex-wrap:wrap;margin-top:1.4rem;}
.hbtn-gold{display:inline-flex;align-items:center;gap:.45rem;background:var(--nr-gold);color:#0a2540;padding:.58rem 1.3rem;border-radius:8px;font-size:.83rem;font-weight:700;text-decoration:none;border:none;cursor:pointer;transition:all .22s;}
.hbtn-gold:hover{background:var(--nr-gold2);transform:translateY(-1px);}
.hbtn-ghost{display:inline-flex;align-items:center;gap:.45rem;background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.22);padding:.58rem 1.3rem;border-radius:8px;font-size:.83rem;font-weight:600;text-decoration:none;transition:all .22s;}
.hbtn-ghost:hover{background:rgba(255,255,255,.18);}
.pg-hero-badges{display:flex;gap:1rem;flex-wrap:wrap;}
.hero-badge-box{text-align:center;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.13);border-radius:12px;padding:.9rem 1.3rem;}
.hbb-num{font-size:1.7rem;font-weight:800;color:var(--nr-gold);line-height:1;}
.hbb-lbl{font-size:.7rem;color:rgba(255,255,255,.5);margin-top:.15rem;}

/* ─── Stats row (pulls up over hero) ──────────── */
.pg-stats{
  max-width:1200px;margin:-3rem auto 0;
  padding:0 clamp(1rem,5vw,2.5rem);
  display:grid;grid-template-columns:repeat(4,1fr);gap:1.1rem;
  position:relative;z-index:10;
}
.pg-stat{
  background:#fff;border-radius:14px;padding:1.3rem 1.1rem;
  box-shadow:0 4px 18px rgba(0,0,0,.09);
  border-top:3px solid transparent;
  display:flex;align-items:center;gap:.9rem;
  transition:transform .22s,box-shadow .22s;
}
.pg-stat:hover{transform:translateY(-3px);box-shadow:0 8px 28px rgba(0,0,0,.13);}
.pg-stat.sn{border-color:var(--nr-navy);}
.pg-stat.sg{border-color:var(--nr-gold);}
.pg-stat.sv{border-color:var(--nr-green);}
.pg-stat.sb{border-color:var(--nr-burg);}
.ps-icon{width:44px;height:44px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.ps-icon svg{width:20px;height:20px;}
.ic-n{background:rgba(10,37,64,.09);color:var(--nr-navy);}
.ic-g{background:rgba(201,168,76,.12);color:#7a5c10;}
.ic-v{background:rgba(22,101,52,.09);color:var(--nr-green);}
.ic-b{background:rgba(139,26,46,.09);color:var(--nr-burg);}
.ps-num{font-size:1.6rem;font-weight:800;color:var(--nr-navy);line-height:1;}
.ps-lbl{font-size:.76rem;color:var(--nr-gray);margin-top:.1rem;}

/* ─── Main two-col grid ────────────────────────── */
.pg-body{max-width:1200px;margin:2rem auto 3rem;padding:0 clamp(1rem,5vw,2.5rem);display:grid;grid-template-columns:1fr 1fr;gap:1.4rem;align-items:start;}

/* ─── Section card ──────────────────────────────── */
.sec-card{background:#fff;border-radius:14px;box-shadow:0 2px 10px rgba(0,0,0,.07);border:1px solid var(--nr-border);overflow:hidden;}
.sc-head{padding:1rem 1.3rem;border-bottom:1px solid #f1f3f5;display:flex;align-items:center;justify-content:space-between;gap:.5rem;}
.sc-head-title{display:flex;align-items:center;gap:.5rem;font-size:.88rem;font-weight:700;color:var(--nr-navy);}
.sc-head-title svg{width:15px;height:15px;color:var(--nr-gold);flex-shrink:0;}
.sc-head-link{font-size:.78rem;font-weight:600;color:var(--nr-navy);text-decoration:none;display:flex;align-items:center;gap:.25rem;}
.sc-head-link:hover{color:var(--nr-burg);}

/* ─── Active booking ─────────────────────────────── */
.ab-vehicle{display:flex;gap:.9rem;align-items:center;padding:1.1rem 1.3rem;border-bottom:1px solid #f1f3f5;}
.ab-vehicle img{width:88px;height:64px;object-fit:cover;border-radius:9px;flex-shrink:0;}
.ab-vname{font-family:var(--fn-head);font-size:1.05rem;font-weight:700;color:var(--nr-navy);}
.ab-vref{font-size:.75rem;color:var(--nr-gray);margin-top:.1rem;}
.ab-grid{display:grid;grid-template-columns:1fr 1fr;gap:.55rem;padding:.9rem 1.3rem;}
.ab-cell{background:#f8f9fa;border-radius:8px;padding:.55rem .75rem;}
.ab-clbl{font-size:.68rem;text-transform:uppercase;letter-spacing:.07em;color:var(--nr-gray);font-weight:600;}
.ab-cval{font-size:.86rem;font-weight:700;color:var(--nr-navy);margin-top:.1rem;}
.ab-foot{padding:.85rem 1.3rem;display:flex;gap:.55rem;}

/* ─── Empty state ───────────────────────────────── */
.empty-state-box{padding:2.4rem 1.3rem;text-align:center;}
.esb-icon{width:62px;height:62px;border-radius:50%;background:linear-gradient(135deg,#0a2540,#1a4480);display:flex;align-items:center;justify-content:center;margin:0 auto .9rem;}
.esb-icon svg{width:26px;height:26px;color:#fff;}
.esb-title{font-family:var(--fn-head);font-size:1.1rem;color:var(--nr-navy);margin-bottom:.35rem;}
.esb-sub{font-size:.86rem;color:var(--nr-gray);margin-bottom:1.1rem;}

/* ─── Notifications ─────────────────────────────── */
.notif-item{padding:.8rem 1.3rem;border-bottom:1px solid #f1f3f5;display:flex;gap:.75rem;align-items:flex-start;transition:background .18s;}
.notif-item:last-child{border-bottom:none;}
.notif-item:hover{background:#fafafa;}
.notif-item.unread{background:#f4f7ff;}
.notif-ic{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.notif-ic svg{width:15px;height:15px;}
.ni-bk{background:rgba(10,37,64,.08);color:var(--nr-navy);}
.ni-py{background:rgba(201,168,76,.12);color:#7a5c10;}
.ni-sy{background:rgba(22,101,52,.08);color:var(--nr-green);}
.notif-title{font-size:.83rem;font-weight:700;color:var(--nr-navy);}
.notif-msg{font-size:.76rem;color:var(--nr-gray);line-height:1.45;margin-top:.1rem;}
.notif-time{font-size:.7rem;color:#adb5bd;margin-top:.18rem;}
.notif-dot{width:7px;height:7px;border-radius:50%;background:var(--nr-burg);flex-shrink:0;margin-top:.45rem;}
.notif-empty{padding:1.8rem;text-align:center;color:var(--nr-gray);font-size:.86rem;}

/* ─── Booking list ───────────────────────────────── */
.bk-item{padding:.9rem 1.3rem;border-bottom:1px solid #f1f3f5;display:flex;gap:.75rem;align-items:flex-start;transition:background .18s;}
.bk-item:hover{background:#fafafa;}
.bk-item:last-child{border-bottom:none;}
.bk-img{width:46px;height:34px;object-fit:cover;border-radius:6px;flex-shrink:0;margin-top:.15rem;}
.bk-name{font-size:.86rem;font-weight:700;color:var(--nr-navy);}
.bk-ref{font-size:.72rem;color:var(--nr-gray);}
.bk-dates{font-size:.74rem;color:var(--nr-gray);margin-top:.2rem;}
.bk-right{margin-left:auto;text-align:right;flex-shrink:0;}
.bk-amount{font-size:.83rem;font-weight:700;color:var(--nr-navy);margin-top:.25rem;}
.bk-acts{display:flex;gap:.35rem;margin-top:.4rem;flex-wrap:wrap;}
.bk-acts a,.bk-acts button{display:inline-flex;align-items:center;padding:.25rem .65rem;border-radius:6px;font-size:.73rem;font-weight:600;text-decoration:none;transition:all .2s;border:none;cursor:pointer;font-family:var(--fn-body);}
.ba-view{background:#f0f2f5;color:var(--nr-navy);}
.ba-view:hover{background:var(--nr-navy);color:#fff;}
.ba-pay{background:var(--nr-gold);color:#0a2540;}
.ba-pay:hover{background:var(--nr-gold2);}
.bk-empty{padding:2rem;text-align:center;color:var(--nr-gray);font-size:.86rem;}

/* ─── Status badges ─────────────────────────────── */
.xb{display:inline-flex;align-items:center;padding:.18rem .6rem;border-radius:100px;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;}
.xb-pending  {background:#fef9ec;color:#7a5c10;border:1px solid #f0dfa0;}
.xb-approved {background:#dbeafe;color:#1e40af;border:1px solid #bfdbfe;}
.xb-active   {background:#dcfce7;color:#166534;border:1px solid #bbf7d0;}
.xb-completed{background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;}
.xb-cancelled,.xb-declined{background:#fce7f3;color:#8b1a2e;border:1px solid #fbcfe8;}

/* ─── Utility buttons ───────────────────────────── */
.ubtn{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1.1rem;border-radius:8px;font-size:.82rem;font-weight:700;text-decoration:none;transition:all .22s;border:none;cursor:pointer;font-family:var(--fn-body);}
.ubtn-gold{background:var(--nr-gold);color:#0a2540;}
.ubtn-gold:hover{background:var(--nr-gold2);transform:translateY(-1px);}
.ubtn-navy{background:var(--nr-navy);color:#fff;}
.ubtn-navy:hover{background:#061729;}
.ubtn-outline{background:transparent;color:var(--nr-navy);border:2px solid var(--nr-navy);}
.ubtn-outline:hover{background:var(--nr-navy);color:#fff;}

/* ─── Responsive ────────────────────────────────── */
@media(max-width:980px){
  .pg-stats{grid-template-columns:repeat(2,1fr);}
  .pg-body{grid-template-columns:1fr;}
}
@media(max-width:520px){
  .pg-stats{grid-template-columns:1fr 1fr;gap:.7rem;}
  .pg-hero{padding-bottom:4rem;}
  .pg-hero-badges{display:none;}
  .ab-grid{grid-template-columns:1fr 1fr;}
}
</style>
@endpush

@section('content')
<div class="pg-wrap">

  {{-- ── Hero ──────────────────────────────────── --}}
  <div class="pg-hero">
    <div class="pg-hero-inner">
      <div>
        <div class="pg-hero-label">Customer Portal</div>
        <h1>Welcome back, <em>{{ explode(' ',$user->name)[0] }}</em></h1>
        <p class="pg-hero-sub">Manage your bookings and track your rentals from here.</p>
        <div class="pg-hero-btns">
          <a href="{{ route('vehicles.index') }}" class="hbtn-gold">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            Browse Fleet
          </a>
          <a href="{{ route('bookings.index') }}" class="hbtn-ghost">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            All Bookings
          </a>
        </div>
      </div>
      <div class="pg-hero-badges">
        <div class="hero-badge-box">
          <div class="hbb-num">{{ $user->bookings()->whereIn('status',['approved','active'])->count() }}</div>
          <div class="hbb-lbl">Active Rentals</div>
        </div>
        <div class="hero-badge-box">
          <div class="hbb-num">{{ $user->bookings()->where('status','completed')->count() }}</div>
          <div class="hbb-lbl">Trips Done</div>
        </div>
      </div>
    </div>
  </div>

  {{-- ── Stats ─────────────────────────────────── --}}
  <div class="pg-stats">
    <div class="pg-stat sn">
      <div class="ps-icon ic-n">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
      <div>
        <div class="ps-num">{{ $user->bookings()->count() }}</div>
        <div class="ps-lbl">Total Bookings</div>
      </div>
    </div>
    <div class="pg-stat sg">
      <div class="ps-icon ic-g">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <div>
        <div class="ps-num">{{ $user->bookings()->where('status','pending')->count() }}</div>
        <div class="ps-lbl">Pending Approval</div>
      </div>
    </div>
    <div class="pg-stat sv">
      <div class="ps-icon ic-v">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
      <div>
        <div class="ps-num">{{ $user->bookings()->where('status','completed')->count() }}</div>
        <div class="ps-lbl">Completed</div>
      </div>
    </div>
    <div class="pg-stat sb">
      <div class="ps-icon ic-b">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </div>
      <div>
        <div class="ps-num" style="font-size:1rem;">{{ number_format($totalSpent,0) }}</div>
        <div class="ps-lbl">UGX Total Spent</div>
      </div>
    </div>
  </div>

  {{-- ── Body grid ─────────────────────────────── --}}
  <div class="pg-body">

    {{-- Left col --}}
    <div style="display:flex;flex-direction:column;gap:1.4rem;">

      {{-- Active booking --}}
      <div class="sec-card">
        <div class="sc-head">
          <div class="sc-head-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            Active Booking
          </div>
          @if($activeBooking)
            @php $st=match($activeBooking->status){'approved'=>'xb-approved','active'=>'xb-active',default=>'xb-pending'}; @endphp
            <span class="xb {{ $st }}">{{ ucfirst($activeBooking->status) }}</span>
          @endif
        </div>

        @if($activeBooking)
          <div class="ab-vehicle">
            <img src="{{ $activeBooking->vehicle->image_url }}" alt="{{ $activeBooking->vehicle->name }}">
            <div>
              <div class="ab-vname">{{ $activeBooking->vehicle->name }}</div>
              <div class="ab-vref">{{ $activeBooking->booking_reference }}</div>
            </div>
          </div>
          <div class="ab-grid">
            <div class="ab-cell"><div class="ab-clbl">Pickup</div><div class="ab-cval">{{ $activeBooking->pickup_date->format('d M Y') }}</div></div>
            <div class="ab-cell"><div class="ab-clbl">Return</div><div class="ab-cval">{{ $activeBooking->return_date->format('d M Y') }}</div></div>
            <div class="ab-cell"><div class="ab-clbl">Duration</div><div class="ab-cval">{{ $activeBooking->days }} day{{ $activeBooking->days>1?'s':'' }}</div></div>
            <div class="ab-cell"><div class="ab-clbl">Total</div><div class="ab-cval" style="color:var(--nr-burg);">UGX {{ number_format($activeBooking->total_amount,0) }}</div></div>
          </div>
          <div class="ab-foot">
            <a href="{{ route('bookings.show',$activeBooking) }}" class="ubtn ubtn-outline" style="flex:1;justify-content:center;">View Details</a>
            @if($activeBooking->status==='approved' && !($activeBooking->payment && $activeBooking->payment->status==='completed'))
              <a href="{{ route('payments.create',$activeBooking) }}" class="ubtn ubtn-gold" style="flex:1;justify-content:center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                Pay Now
              </a>
            @endif
          </div>
        @else
          <div class="empty-state-box">
            <div class="esb-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            </div>
            <div class="esb-title">No Active Booking</div>
            <p class="esb-sub">Ready for your next journey? Browse our fleet and book your vehicle.</p>
            <a href="{{ route('vehicles.index') }}" class="ubtn ubtn-gold">Browse Fleet</a>
          </div>
        @endif
      </div>

      {{-- Notifications --}}
      <div class="sec-card">
        <div class="sc-head">
          <div class="sc-head-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            Notifications
          </div>
          @php $uc=auth()->user()->unreadNotifications()->count(); @endphp
          @if($uc>0)
            <span style="background:var(--nr-burg);color:#fff;font-size:.65rem;font-weight:700;padding:.15rem .5rem;border-radius:100px;">{{ $uc }} new</span>
          @endif
        </div>
        @forelse($notifications as $notif)
          <div class="notif-item {{ !$notif->is_read?'unread':'' }}">
            <div class="notif-ic {{ match($notif->type){'booking'=>'ni-bk','payment'=>'ni-py',default=>'ni-sy'} }}">
              @if($notif->type==='payment')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
              @elseif($notif->type==='booking')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              @endif
            </div>
            <div style="flex:1;min-width:0;">
              <div class="notif-title">{{ $notif->title }}</div>
              <div class="notif-msg">{{ Str::limit($notif->message,85) }}</div>
              <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
            </div>
            @if(!$notif->is_read)<span class="notif-dot"></span>@endif
          </div>
        @empty
          <div class="notif-empty">No notifications yet.</div>
        @endforelse
        
      </div>
    </div>

    {{-- Right col — Recent bookings --}}
    <div class="sec-card">
      <div class="sc-head">
        <div class="sc-head-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="12" y2="17"/></svg>
          Recent Bookings
        </div>
        <a href="{{ route('bookings.index') }}" class="sc-head-link">
          View all
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>

      @forelse($bookings as $booking)
        <div class="bk-item">
          <img src="{{ $booking->vehicle->image_url }}" alt="{{ $booking->vehicle->name }}" class="bk-img">
          <div style="flex:1;min-width:0;">
            <div class="bk-name">{{ $booking->vehicle->name }}</div>
            <div class="bk-ref">{{ $booking->booking_reference }}</div>
            <div class="bk-dates">{{ $booking->pickup_date->format('d M') }} &rarr; {{ $booking->return_date->format('d M Y') }} &middot; {{ $booking->days }} day{{ $booking->days>1?'s':'' }}</div>
            <div class="bk-acts">
              <a href="{{ route('bookings.show',$booking) }}" class="ba-view">View</a>
              @if($booking->status==='approved' && !($booking->payment && $booking->payment->status==='completed'))
                <a href="{{ route('payments.create',$booking) }}" class="ba-pay">Pay Now</a>
              @endif
            </div>
          </div>
          <div class="bk-right">
            @php $xb=match($booking->status){'pending'=>'xb-pending','approved'=>'xb-approved','active'=>'xb-active','completed'=>'xb-completed',default=>'xb-cancelled'}; @endphp
            <span class="xb {{ $xb }}">{{ ucfirst($booking->status) }}</span>
            <div class="bk-amount">UGX {{ number_format($booking->total_amount,0) }}</div>
          </div>
        </div>
      @empty
        <div class="bk-empty">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="opacity:.25;margin:0 auto .7rem;display:block;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          No bookings yet. <a href="{{ route('vehicles.index') }}" style="color:var(--nr-navy);font-weight:600;">Browse fleet</a>
        </div>
      @endforelse
    </div>

  </div>
</div>
@endsection
