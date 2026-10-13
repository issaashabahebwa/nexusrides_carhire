@extends('layouts.app')
@section('title','My Bookings')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700;800&display=swap');
*{box-sizing:border-box;}
:root{--nb:#0a2540;--ng:#C9A84C;--nr:#8B1A2E;--ngr:#166534;--fn:'Inter','Segoe UI',sans-serif;--fh:'Playfair Display',Georgia,serif;}
.bk-pg{background:#f0f2f5;min-height:100vh;font-family:var(--fn);}
/* Hero */
.bk-hero{background:linear-gradient(120deg,#061729 0%,#0a2540 50%,#5e1020 100%);padding:2.5rem clamp(1rem,5vw,2.5rem) 4.5rem;position:relative;overflow:hidden;}
.bk-hero::after{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.025'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");pointer-events:none;}
.bk-hero-inner{max-width:1200px;margin:0 auto;position:relative;z-index:1;display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;}
.bk-hero-label{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(255,255,255,.45);margin-bottom:.4rem;}
.bk-hero h1{font-family:var(--fh);color:#fff;font-size:clamp(1.5rem,3.5vw,2.3rem);font-weight:700;margin:0;}
.bk-hero-sub{color:rgba(255,255,255,.55);font-size:.86rem;margin-top:.3rem;}
/* Content */
.bk-content{max-width:1200px;margin:-2.8rem auto 3rem;padding:0 clamp(1rem,5vw,2.5rem);position:relative;z-index:10;}
/* Toolbar */
.bk-toolbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.8rem;margin-bottom:1.2rem;}
.bk-toolbar-left{display:flex;gap:.5rem;flex-wrap:wrap;}
.bk-filter-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.45rem 1rem;border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none;border:1.5px solid #dee2e6;background:#fff;color:#495057;cursor:pointer;transition:all .2s;font-family:var(--fn);}
.bk-filter-btn:hover,.bk-filter-btn.active{background:var(--nb);color:#fff;border-color:var(--nb);}
.bk-new-btn{display:inline-flex;align-items:center;gap:.45rem;background:var(--ng);color:#0a2540;padding:.52rem 1.2rem;border-radius:8px;font-size:.84rem;font-weight:700;text-decoration:none;transition:all .22s;}
.bk-new-btn:hover{background:#E8C96A;transform:translateY(-1px);}
/* Cards grid */
.bk-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1.2rem;}
/* Booking card */
.bk-card{background:#fff;border-radius:14px;border:1px solid #e9ecef;box-shadow:0 2px 10px rgba(0,0,0,.07);overflow:hidden;display:flex;flex-direction:column;transition:transform .25s,box-shadow .25s;}
.bk-card:hover{transform:translateY(-4px);box-shadow:0 10px 32px rgba(0,0,0,.12);}
/* Card top image strip */
.bk-card-img{height:160px;overflow:hidden;position:relative;background:linear-gradient(135deg,#0a2540,#1a4480);}
.bk-card-img img{width:100%;height:100%;object-fit:cover;transition:transform .4s;}
.bk-card:hover .bk-card-img img{transform:scale(1.05);}
.bk-card-status{position:absolute;top:10px;right:10px;}
.bk-card-ref{position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,.7),transparent);padding:.6rem .85rem .5rem;font-size:.75rem;font-weight:700;color:rgba(255,255,255,.9);letter-spacing:.03em;}
/* Card body */
.bk-card-body{padding:1.1rem 1.2rem;flex:1;}
.bk-card-vname{font-family:var(--fh);font-size:1.05rem;font-weight:700;color:var(--nb);margin-bottom:.6rem;}
.bk-info-row{display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-bottom:.8rem;}
.bk-info-cell{background:#f8f9fa;border-radius:7px;padding:.5rem .7rem;}
.bk-ic-lbl{font-size:.65rem;text-transform:uppercase;letter-spacing:.07em;color:#6c757d;font-weight:700;}
.bk-ic-val{font-size:.83rem;font-weight:700;color:var(--nb);margin-top:.1rem;}
/* Price strip */
.bk-price-strip{background:linear-gradient(90deg,var(--nb),#1a3a60);color:#fff;padding:.6rem 1.2rem;display:flex;justify-content:space-between;align-items:center;}
.bk-ps-label{font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.6);}
.bk-ps-amount{font-size:1.05rem;font-weight:800;color:var(--ng);}
/* Card footer */
.bk-card-foot{padding:.85rem 1.2rem;border-top:1px solid #f1f3f5;display:flex;gap:.5rem;flex-wrap:wrap;}
.bk-act{display:inline-flex;align-items:center;gap:.35rem;padding:.38rem .85rem;border-radius:7px;font-size:.78rem;font-weight:600;text-decoration:none;transition:all .2s;border:none;cursor:pointer;font-family:var(--fn);}
.ba-view2{background:#f0f2f5;color:var(--nb);}
.ba-view2:hover{background:var(--nb);color:#fff;}
.ba-pay2{background:var(--ng);color:#0a2540;}
.ba-pay2:hover{background:#E8C96A;}
.ba-cancel{background:#fce7f3;color:var(--nr);border:1px solid #fbcfe8;}
.ba-cancel:hover{background:var(--nr);color:#fff;}
/* Status badges */
.sb2{display:inline-flex;align-items:center;padding:.2rem .65rem;border-radius:100px;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;}
.sb-pending  {background:#fef9ec;color:#7a5c10;border:1px solid #f0dfa0;}
.sb-approved {background:#dbeafe;color:#1e40af;border:1px solid #bfdbfe;}
.sb-active   {background:#dcfce7;color:#166534;border:1px solid #bbf7d0;}
.sb-completed{background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;}
.sb-cancelled,.sb-declined{background:#fce7f3;color:#8b1a2e;border:1px solid #fbcfe8;}
/* Payment badge */
.py-paid{background:#dcfce7;color:#166534;border:1px solid #bbf7d0;display:inline-flex;align-items:center;gap:.3rem;padding:.18rem .55rem;border-radius:100px;font-size:.68rem;font-weight:700;}
.py-due{background:#fef9ec;color:#7a5c10;border:1px solid #f0dfa0;display:inline-flex;align-items:center;gap:.3rem;padding:.18rem .55rem;border-radius:100px;font-size:.68rem;font-weight:700;}
/* Empty state */
.bk-empty{background:#fff;border-radius:14px;border:1px solid #e9ecef;padding:4rem 2rem;text-align:center;}
.be-icon{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#0a2540,#1a4480);display:flex;align-items:center;justify-content:center;margin:0 auto 1.2rem;}
.be-icon svg{width:30px;height:30px;color:#fff;}
.be-title{font-family:var(--fh);font-size:1.3rem;color:var(--nb);margin-bottom:.4rem;}
.be-sub{color:#6c757d;font-size:.88rem;margin-bottom:1.4rem;}
/* Pagination */
.bk-pager{display:flex;justify-content:center;gap:.4rem;flex-wrap:wrap;margin-top:1.5rem;}
.bk-pager a,.bk-pager span{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;font-size:.86rem;font-weight:500;border:1.5px solid #dee2e6;color:#495057;text-decoration:none;transition:all .2s;}
.bk-pager a:hover{background:var(--nb);color:#fff;border-color:var(--nb);}
.bk-pager .pg-active{background:var(--nb);color:#fff;border-color:var(--nb);font-weight:700;}
.bk-pager .pg-dis{opacity:.38;pointer-events:none;}
/* Responsive */
@media(max-width:640px){.bk-grid{grid-template-columns:1fr;}.bk-toolbar{flex-direction:column;align-items:flex-start;}.bk-info-row{grid-template-columns:1fr 1fr;}}
</style>
@endpush

@section('content')
<div class="bk-pg">

  {{-- Hero --}}
  <div class="bk-hero">
    <div class="bk-hero-inner">
      <div>
        <div class="bk-hero-label">Customer Portal</div>
        <h1>My Bookings</h1>
        <p class="bk-hero-sub">Track and manage all your vehicle reservations in one place.</p>
      </div>
    </div>
  </div>

  {{-- Content --}}
  <div class="bk-content">

    {{-- Toolbar --}}
    <div class="bk-toolbar">
      <div class="bk-toolbar-left">
        @foreach(['all'=>'All','pending'=>'Pending','approved'=>'Approved','active'=>'Active','completed'=>'Completed'] as $val=>$lbl)
          <a href="{{ route('bookings.index') }}{{ $val!=='all' ? '?status='.$val : '' }}"
             class="bk-filter-btn {{ (request('status')===$val || ($val==='all' && !request('status'))) ? 'active' : '' }}">
            {{ $lbl }}
          </a>
        @endforeach
      </div>
      <a href="{{ route('vehicles.index') }}" class="bk-new-btn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Booking
      </a>
    </div>

    @if($bookings->count())
    <div class="bk-grid">
      @foreach($bookings as $booking)
      <div class="bk-card">

        {{-- Image --}}
        <div class="bk-card-img">
          <img src="{{ $booking->vehicle->image_url }}" alt="{{ $booking->vehicle->name }}" loading="lazy">
          <div class="bk-card-status">
            @php $sc=match($booking->status){'pending'=>'sb-pending','approved'=>'sb-approved','active'=>'sb-active','completed'=>'sb-completed',default=>'sb-cancelled'}; @endphp
            <span class="sb2 {{ $sc }}">{{ ucfirst($booking->status) }}</span>
          </div>
          <div class="bk-card-ref">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:middle;margin-right:3px"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            {{ $booking->booking_reference }}
          </div>
        </div>

        {{-- Body --}}
        <div class="bk-card-body">
          <div class="bk-card-vname">{{ $booking->vehicle->name }}</div>
          <div class="bk-info-row">
            <div class="bk-info-cell">
              <div class="bk-ic-lbl">Pickup</div>
              <div class="bk-ic-val">{{ $booking->pickup_date->format('d M Y') }}</div>
            </div>
            <div class="bk-info-cell">
              <div class="bk-ic-lbl">Return</div>
              <div class="bk-ic-val">{{ $booking->return_date->format('d M Y') }}</div>
            </div>
            <div class="bk-info-cell">
              <div class="bk-ic-lbl">Duration</div>
              <div class="bk-ic-val">{{ $booking->days }} day{{ $booking->days>1?'s':'' }}</div>
            </div>
            <div class="bk-info-cell">
              <div class="bk-ic-lbl">Payment</div>
              <div class="bk-ic-val" style="margin-top:.15rem;">
                @if($booking->payment && $booking->payment->status==='completed')
                  <span class="py-paid">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>Paid
                  </span>
                @elseif($booking->status==='approved')
                  <span class="py-due">Due</span>
                @else
                  <span style="color:#6c757d;font-size:.78rem;">N/A</span>
                @endif
              </div>
            </div>
          </div>
        </div>

        {{-- Price --}}
        <div class="bk-price-strip">
          <div class="bk-ps-label">Total Amount</div>
          <div class="bk-ps-amount">UGX {{ number_format($booking->total_amount,0) }}</div>
        </div>

        {{-- Footer actions --}}
        <div class="bk-card-foot">
          <a href="{{ route('bookings.show',$booking) }}" class="bk-act ba-view2">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            View
          </a>
          @if($booking->status==='approved' && !($booking->payment && $booking->payment->status==='completed'))
          <a href="{{ route('payments.create',$booking) }}" class="bk-act ba-pay2">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
            Pay Now
          </a>
          @endif
          @if(in_array($booking->status,['pending','approved']))
          <form method="POST" action="{{ route('bookings.cancel',$booking) }}" onsubmit="return confirm('Cancel this booking?')">
            @csrf
            <button type="submit" class="bk-act ba-cancel">Cancel</button>
          </form>
          @endif
        </div>

      </div>
      @endforeach
    </div>

    {{-- Pagination --}}
    @if($bookings->hasPages())
    <div class="bk-pager">
      @if($bookings->onFirstPage())
        <span class="pg-dis">&lsaquo;</span>
      @else
        <a href="{{ $bookings->previousPageUrl() }}">&lsaquo;</a>
      @endif
      @foreach($bookings->getUrlRange(max(1,$bookings->currentPage()-2),min($bookings->lastPage(),$bookings->currentPage()+2)) as $page=>$url)
        @if($page==$bookings->currentPage())
          <span class="pg-active">{{ $page }}</span>
        @else
          <a href="{{ $url }}">{{ $page }}</a>
        @endif
      @endforeach
      @if($bookings->hasMorePages())
        <a href="{{ $bookings->nextPageUrl() }}">&rsaquo;</a>
      @else
        <span class="pg-dis">&rsaquo;</span>
      @endif
    </div>
    @endif

    @else
    <div class="bk-empty">
      <div class="be-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="12" y2="17"/></svg>
      </div>
      <div class="be-title">No Bookings Yet</div>
      <p class="be-sub">Browse our fleet and make your first booking in minutes.</p>
      <a href="{{ route('vehicles.index') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:var(--nb);color:#fff;padding:.65rem 1.5rem;border-radius:8px;font-size:.88rem;font-weight:700;text-decoration:none;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>
        Browse Fleet
      </a>
    </div>
    @endif

  </div>
</div>
@endsection
