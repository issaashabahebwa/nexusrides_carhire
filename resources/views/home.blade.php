@extends('layouts.app')
@section('title','Home')
@section('meta_description','NexusRides – Uganda\'s premier vehicle hire platform. Executive sedans, luxury SUVs, vans and buses with mobile money payments.')

@section('content')

{{-- ═══════════════════════════════════════════════════════
     HERO  –  full-bleed vehicle image background
════════════════════════════════════════════════════════ --}}
<section class="hero3">

    {{-- Banner background: uses first featured vehicle photo if available --}}
    @php $heroVehicle = $featuredVehicles->first(); @endphp
    @if($heroVehicle)
        <img class="hero3-bg"
             src="{{ $heroVehicle->image_url }}"
             alt="NexusRides Luxury Interior"
             loading="eager">
    @endif

    <div class="hero3-overlay"></div>

    {{-- Main copy --}}
    <div class="hero3-content">
        <div class="hero3-copy">
            <div class="hero3-eyebrow">
                <span class="eyebrow-dot"></span>
                Uganda's Premier Vehicle Hire
            </div>

            <h1>
                Drive in <span class="h1-gold">Comfort</span><br>
                Book with Confidence
            </h1>

            <p class="hero3-desc">
                From executive sedans to rugged 4x4s and luxury buses — the right vehicle
                for every journey. Instant booking, mobile money payments, 24/7 support.
            </p>

            <div class="hero3-actions">
                <a href="{{ route('vehicles.index') }}" class="btn btn-primary btn-lg">
                    View Our Fleet
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-outline-white btn-lg">
                        Create Free Account
                    </a>
                @else
                    <a href="{{ route('bookings.create') }}" class="btn btn-outline-white btn-lg">
                        Book a Vehicle
                    </a>
                @endguest
            </div>

            <div class="hero3-trust">
                <div class="trust-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Verified Fleet
                </div>
                <div class="trust-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Mobile Money Accepted
                </div>
                <div class="trust-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    24 / 7 Support
                </div>
            </div>
        </div>
    </div>

    {{-- Search bar pinned to bottom of hero --}}
    <div class="hero3-search-wrap">
        <div class="hero3-search-inner">
            <form action="{{ route('vehicles.index') }}" method="GET">
                <div class="hero3-search-grid">
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Pickup Date</label>
                        <input type="date" name="pickup_date" class="form-control"
                               min="{{ date('Y-m-d') }}" value="{{ request('pickup_date') }}">
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Return Date</label>
                        <input type="date" name="return_date" class="form-control"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ request('return_date') }}">
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Vehicle Type</label>
                        <select name="type" class="form-control form-select">
                            <option value="">All Types</option>
                            @foreach(['sedan','suv','van','truck','bus','convertible','pickup'] as $t)
                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block" style="height:48px;">
                            Search Vehicles
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     STATS STRIP
════════════════════════════════════════════════════════ --}}
<section class="stats-strip3">
    <div class="stats-strip3-grid">

        <div class="stat3">
            <div class="stat3-icon ic-navy">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="1" y="3" width="15" height="13" rx="2"/>
                    <path d="M16 8h4l3 3v5h-7V8z"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/>
                    <circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
            </div>
            <div class="stat3-num">{{ $stats['total_vehicles'] }}+</div>
            <div class="stat3-label">Premium Vehicles</div>
        </div>

        <div class="stat3">
            <div class="stat3-icon ic-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <div class="stat3-num">{{ $stats['available'] }}</div>
            <div class="stat3-label">Available Now</div>
        </div>

        <div class="stat3">
            <div class="stat3-icon ic-gold">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div class="stat3-num">500+</div>
            <div class="stat3-label">Happy Customers</div>
        </div>

        <div class="stat3">
            <div class="stat3-icon ic-burg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <div class="stat3-num">24/7</div>
            <div class="stat3-label">Customer Support</div>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     WHY NEXUSRIDES
════════════════════════════════════════════════════════ --}}
<section class="features3">
    <div class="sec-title">
        <div class="sec-pre">Why NexusRides</div>
        <h2>Everything You Need in One Place</h2>
        <p>Simple, secure, and professional vehicle hire — designed for every Ugandan journey.</p>
        <div class="sec-line"></div>
    </div>
    <div class="features3-grid">

        <div class="feat3-card">
            <div class="feat3-icon ic-navy">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="1" y="3" width="15" height="13" rx="2"/>
                    <path d="M16 8h4l3 3v5h-7V8z"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/>
                    <circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
            </div>
            <h3>Wide Fleet Selection</h3>
            <p>Sedans, SUVs, minibuses, pickups and buses — the right vehicle for every trip, budget, and occasion.</p>
        </div>

        <div class="feat3-card">
            <div class="feat3-icon ic-burg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                    <polyline points="13 2 13 9 20 9"/>
                    <line x1="9" y1="13" x2="15" y2="13"/>
                    <line x1="9" y1="17" x2="12" y2="17"/>
                </svg>
            </div>
            <h3>Instant Booking</h3>
            <p>Book in under 3 minutes. Submit your request, receive approval, and you are on the road.</p>
        </div>

        <div class="feat3-card">
            <div class="feat3-icon ic-gold">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="2" y="5" width="20" height="14" rx="2"/>
                    <line x1="2" y1="10" x2="22" y2="10"/>
                </svg>
            </div>
            <h3>Mobile Money Payments</h3>
            <p>Pay securely via MTN Mobile Money, Airtel Money, or card. Trusted local payment methods you know.</p>
        </div>

        <div class="feat3-card">
            <div class="feat3-icon ic-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            <h3>Safe and Verified Fleet</h3>
            <p>Every vehicle is regularly inspected and maintained to the highest safety standards in Uganda.</p>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     FEATURED VEHICLES  –  real SVG images in cards
════════════════════════════════════════════════════════ --}}
<section class="vehicles3">
    <div class="vehicles3-inner">
        <div class="sec-title">
            <div class="sec-pre">Our Fleet</div>
            <h2>Featured Vehicles</h2>
            <p>Carefully maintained vehicles ready for your next journey.</p>
            <div class="sec-line"></div>
        </div>

        {{-- Type tabs --}}
        <div class="vtabs">
            <a href="{{ route('vehicles.index') }}" class="vtab active">All Vehicles</a>
            @foreach(['sedan','suv','van','bus','pickup'] as $t)
            <a href="{{ route('vehicles.index', ['type'=>$t]) }}" class="vtab">{{ ucfirst($t) }}</a>
            @endforeach
        </div>

        @if($featuredVehicles->count())
        <div class="vgrid3">
            @foreach($featuredVehicles as $vehicle)
            <div class="vcard3">
                <div class="vcard3-img">
                    <img src="{{ $vehicle->image_url }}"
                         alt="{{ $vehicle->name }}"
                         loading="lazy">
                    <span class="vcard3-type">{{ ucfirst($vehicle->type) }}</span>
                    <span class="vcard3-avail">
                        <span class="vcard3-avail-dot"></span>
                        Available
                    </span>
                    <div class="vcard3-price">
                        UGX <span>{{ number_format($vehicle->price_per_day, 0) }}</span>/day
                    </div>
                </div>

                <div class="vcard3-body">
                    <div class="vcard3-name">{{ $vehicle->name }}</div>
                    <div class="vcard3-sub">{{ $vehicle->brand }} &middot; {{ $vehicle->model }} &middot; {{ $vehicle->year }}</div>

                    <div class="vcard3-specs">
                        <span class="vcard3-spec">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                            </svg>
                            {{ $vehicle->seats }} Seats
                        </span>
                        <span class="vcard3-spec">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 8v4l3 3"/>
                            </svg>
                            {{ ucfirst($vehicle->transmission) }}
                        </span>
                        <span class="vcard3-spec">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 22V8l9-6 9 6v14"/>
                                <path d="M9 22V12h6v10"/>
                            </svg>
                            {{ ucfirst($vehicle->fuel_type) }}
                        </span>
                        @if($vehicle->location)
                        <span class="vcard3-spec">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            {{ $vehicle->location }}
                        </span>
                        @endif
                    </div>
                </div>

                <div class="vcard3-footer">
                    <a href="{{ route('vehicles.show', $vehicle) }}"
                       class="btn btn-outline btn-sm" style="flex:1;justify-content:center;">
                        View Details
                    </a>
                    @auth
                    <a href="{{ route('bookings.create', ['vehicle_id' => $vehicle->id]) }}"
                       class="btn btn-primary btn-sm" style="flex:1;justify-content:center;">
                        Book Now
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                       class="btn btn-primary btn-sm" style="flex:1;justify-content:center;">
                        Book Now
                    </a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>

        <div class="view-all3">
            <a href="{{ route('vehicles.index') }}">
                View All {{ $stats['total_vehicles'] }} Vehicles
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @else
        <div style="text-align:center;padding:4rem 2rem;color:#6c757d;">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="margin:0 auto 1rem;opacity:.3">
                <rect x="1" y="3" width="15" height="13" rx="2"/>
                <path d="M16 8h4l3 3v5h-7V8z"/>
                <circle cx="5.5" cy="18.5" r="2.5"/>
                <circle cx="18.5" cy="18.5" r="2.5"/>
            </svg>
            <h3 style="color:#495057;margin-bottom:.5rem;">Fleet coming soon</h3>
            <p>We are adding vehicles to our fleet. Check back shortly.</p>
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     HOW IT WORKS
════════════════════════════════════════════════════════ --}}
<section class="hiw3">
    <div class="hiw3-inner">
        <div class="sec-title" style="margin-bottom:3.5rem;">
            <div class="sec-pre" style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.8);border-color:rgba(255,255,255,.15);">Simple Process</div>
            <h2 style="color:#fff;">How It Works</h2>
            <p style="color:rgba(255,255,255,.55);">Rent a vehicle in four simple steps</p>
            <div class="sec-line"></div>
        </div>
        <div class="hiw3-grid">

            @foreach([
                [
                    'num'  => '01',
                    'title'=> 'Browse and Compare',
                    'desc' => 'Search our fleet, view full specifications, compare prices, and choose the vehicle that fits your needs.',
                    'icon' => '<path d="M21 21l-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"/>',
                ],
                [
                    'num'  => '02',
                    'title'=> 'Submit Your Booking',
                    'desc' => 'Pick your dates, enter your pickup location, add any notes, and submit your booking request in minutes.',
                    'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>',
                ],
                [
                    'num'  => '03',
                    'title'=> 'Receive Approval',
                    'desc' => 'Our team reviews and approves your booking quickly. You will receive a confirmation notification in your dashboard.',
                    'icon' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
                ],
                [
                    'num'  => '04',
                    'title'=> 'Pay and Drive',
                    'desc' => 'Complete payment via MTN MoMo, Airtel Money, or card. Collect your vehicle and enjoy the journey.',
                    'icon' => '<rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>',
                ],
            ] as $step)
            <div class="hiw3-step">
                <div class="hiw3-num">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        {!! $step['icon'] !!}
                    </svg>
                </div>
                <span class="hiw3-step-num">{{ $step['num'] }}</span>
                <h3>{{ $step['title'] }}</h3>
                <p>{{ $step['desc'] }}</p>
            </div>
            @endforeach

        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     TESTIMONIALS
════════════════════════════════════════════════════════ --}}
<section class="testimonials3">
    <div class="sec-title">
        <div class="sec-pre">Customer Stories</div>
        <h2>What Our Clients Say</h2>
        <p>Trusted by hundreds of customers across Uganda for reliable vehicle hire.</p>
        <div class="sec-line"></div>
    </div>
    <div class="tgrid3">
        @foreach([
            ['IA','Issa Ashabahebwa','Business Executive, Kampala HQ',
             'Hired a Mercedes E-Class for a corporate event. The vehicle was spotless, the booking process seamless, and the team incredibly professional. NexusRides is now our go-to for all corporate transport.'],
            ['PN','Patricia Nuwatuha','Tourism Operator, Kisoro',
             'We regularly use NexusRides for our safari packages. The Land Cruiser V8 is perfect for game drives. Reliable, clean, well-maintained, and great value for money every single time.'],
            ['BA','Bright Atamba','Events Coordinator, Mbarara',
             'Booked the Coaster Bus for our company retreat — 30 people, smooth ride, and perfectly on time. MTN Mobile Money payment was so convenient. Will keep using NexusRides for all our events.'],
        ] as [$initials, $name, $role, $quote])
        <div class="tcard3">
            <div class="tcard3-quote">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/>
                    <path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/>
                </svg>
            </div>
            <div class="tcard3-stars">
                @for($s=0;$s<5;$s++)
                <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                @endfor
            </div>
            <p class="tcard3-text">"{{ $quote }}"</p>
            <div class="tcard3-author">
                <div class="tcard3-avatar">{{ $initials }}</div>
                <div>
                    <div class="tcard3-name">{{ $name }}</div>
                    <div class="tcard3-role">{{ $role }}</div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     PARTNERS & STAKEHOLDERS
════════════════════════════════════════════════════════ --}}
<section class="partners3">
    <div class="partners3-inner">
        <div class="sec-title">
            <div class="sec-pre">Ecosystem</div>
            <h2>Partners and Stakeholders</h2>
            <p>Working with trusted organisations to deliver reliable, safe, and affordable vehicle hire across Uganda.</p>
            <div class="sec-line"></div>
        </div>

        {{-- Official Partners --}}
        <div style="margin-bottom:1rem;">
            <h3 style="font-size:.8rem;text-transform:uppercase;letter-spacing:.1em;color:var(--gray-500);font-weight:700;margin-bottom:1.5rem;text-align:center;">Official Partners</h3>
        </div>
        <div class="partners3-grid">

            <div class="partner-card">
                <div class="partner-logo pl-gold">MTN</div>
                <h4>MTN Uganda</h4>
                <p>Seamless mobile money payments through MTN MoMo integration for fast, secure transactions.</p>
                <span class="partner-tag">Payment Partner</span>
            </div>

            <div class="partner-card">
                <div class="partner-logo pl-burg">AIRTEL</div>
                <h4>Airtel Uganda</h4>
                <p>Airtel Money integration allowing customers to pay for vehicle bookings directly from their mobile wallet.</p>
                <span class="partner-tag">Payment Partner</span>
            </div>

            <div class="partner-card">
                <div class="partner-logo pl-blue">VISA</div>
                <h4>Visa / Mastercard</h4>
                <p>International card payment processing enabling secure transactions for customers across the globe.</p>
                <span class="partner-tag">Payment Partner</span>
            </div>

            <div class="partner-card">
                <div class="partner-logo pl-green">NEMA</div>
                <h4>NEMA Uganda</h4>
                <p>National Environment Management Authority — ensuring our fleet meets environmental compliance standards.</p>
                <span class="partner-tag">Regulatory</span>
            </div>

            <div class="partner-card">
                <div class="partner-logo pl-navy">UNRA</div>
                <h4>UNRA</h4>
                <p>Uganda National Roads Authority partnership for compliance with road safety and vehicle standards.</p>
                <span class="partner-tag">Road Authority</span>
            </div>

            <div class="partner-card">
                <div class="partner-logo pl-orange">UTA</div>
                <h4>Uganda Tourism Board</h4>
                <p>Registered fleet provider for tourism and hospitality operators across Uganda's national parks and destinations.</p>
                <span class="partner-tag">Tourism Partner</span>
            </div>

        </div>

        {{-- Stakeholders --}}
        <div style="margin-bottom:1.5rem;margin-top:2.5rem;">
            <h3 style="font-size:.8rem;text-transform:uppercase;letter-spacing:.1em;color:var(--gray-500);font-weight:700;margin-bottom:1.5rem;text-align:center;">Key Stakeholders</h3>
        </div>
        <div class="stakeholders-row">

            <div class="stakeholder-card">
                <div class="stakeholder-icon si-gov">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 22V8l9-6 9 6v14"/>
                        <path d="M9 22V12h6v10"/>
                    </svg>
                </div>
                <div class="stakeholder-info">
                    <h5>Ministry of Works and Transport</h5>
                    <p>Government regulatory oversight ensuring legal operation and fleet compliance.</p>
                </div>
            </div>

            <div class="stakeholder-card">
                <div class="stakeholder-icon si-fin">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="5" width="20" height="14" rx="2"/>
                        <line x1="2" y1="10" x2="22" y2="10"/>
                    </svg>
                </div>
                <div class="stakeholder-info">
                    <h5>Stanbic Bank Uganda</h5>
                    <p>Financial partner providing banking infrastructure and corporate account services.</p>
                </div>
            </div>

            <div class="stakeholder-card">
                <div class="stakeholder-icon si-corp">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="stakeholder-info">
                    <h5>Corporate Clients</h5>
                    <p>Leading Ugandan companies and NGOs relying on NexusRides for their transport needs.</p>
                </div>
            </div>

            <div class="stakeholder-card">
                <div class="stakeholder-icon si-tech">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2"/>
                        <path d="M8 21h8M12 17v4"/>
                    </svg>
                </div>
                <div class="stakeholder-info">
                    <h5>Technology Partners</h5>
                    <p>Software and infrastructure partners powering NexusRides' digital platform and operations.</p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     PAYMENT METHODS  (informational – not a form)
════════════════════════════════════════════════════════ --}}
<section class="payments3">
    <div class="payments3-inner">
        <h3>Secure Payment Methods</h3>
        <p>We support all major local and international payment options. Payment is completed in your dashboard after booking approval.</p>
        <div class="pay3-row">
            <div class="pay3-badge pay3-mtn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
                    <line x1="12" y1="18" x2="12.01" y2="18"/>
                </svg>
                MTN Mobile Money
            </div>
            <div class="pay3-badge pay3-airtel">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
                    <line x1="12" y1="18" x2="12.01" y2="18"/>
                </svg>
                Airtel Money
            </div>
            <div class="pay3-badge pay3-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="5" width="20" height="14" rx="2"/>
                    <line x1="2" y1="10" x2="22" y2="10"/>
                </svg>
                Visa / Mastercard
            </div>
            <div class="pay3-badge pay3-cash">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                Cash on Pickup
            </div>
        </div>
        <div class="pay3-secure">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            All transactions are encrypted and processed securely. Payment is only required after booking approval.
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     CTA  –  vehicle image as background
════════════════════════════════════════════════════════ --}}
<section class="cta3">
    @if($featuredVehicles->count() > 1)
    <img class="cta3-bg-img"
         src="{{ $featuredVehicles->get(1)->image_url }}"
         alt=""
         loading="lazy"
         aria-hidden="true">
    @endif
    <div class="cta3-overlay"></div>
    <div class="cta3-inner">
        <h2>Ready to <span>Hit the Road?</span></h2>
        <p>
            Join hundreds of customers who trust NexusRides for every journey.
            Create your free account in under two minutes and book your first vehicle today.
        </p>
        <div class="cta3-btns">
            @guest
            <a href="{{ route('register') }}" class="btn btn-primary btn-xl">
                Create Free Account
            </a>
            <a href="{{ route('vehicles.index') }}" class="btn btn-outline-white btn-xl">
                Browse Fleet First
            </a>
            @else
            <a href="{{ route('vehicles.index') }}" class="btn btn-primary btn-xl">
                Browse Our Fleet
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-white btn-xl">
                My Dashboard
            </a>
            @endguest
        </div>
        <p class="cta3-note">No credit card required &middot; Free to register &middot; Instant booking</p>
    </div>
</section>

@endsection
