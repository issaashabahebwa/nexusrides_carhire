<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NexusRides') – Vehicle Hire & Management</title>
    <meta name="description" content="@yield('meta_description', 'NexusRides – Premium vehicle hire and management system. Book your perfect ride today.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    @stack('styles')
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('home') }}" class="navbar-brand">
            <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <rect width="36" height="36" rx="8" fill="#f4a522"/>
                <path d="M6 26l5-10 5 5 5-8 5 6 4-6" stroke="#0a2540" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="9" cy="27" r="2.5" fill="#0a2540"/>
                <circle cx="27" cy="27" r="2.5" fill="#0a2540"/>
                <rect x="5" y="24" width="26" height="3" rx="1.5" fill="#0a2540" opacity=".15"/>
            </svg>
            <span class="brand-text">Nexus<span class="brand-accent">Rides</span></span>
        </a>

        <ul class="navbar-nav" id="mainNav">
            <li><a href="{{ route('home') }}"           class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('vehicles.index') }}" class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Our Fleet</a></li>
            @auth
            <li><a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}">My Bookings</a></li>
            <li><a href="{{ route('dashboard') }}"      class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
            @endauth
        </ul>

        <div class="navbar-actions">
            @auth
                @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                <!-- Notification bell -->
                <button class="notif-btn" id="notifBtn" title="Notifications">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    @if($unread > 0)<span class="notif-count">{{ $unread }}</span>@endif
                </button>

                <!-- User menu -->
                <div class="user-menu">
                    <button class="user-btn" id="userMenuBtn">
                        <span class="avatar">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</span>
                        <span class="user-name-text">{{ Str::limit(auth()->user()->name, 14) }}</span>
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
                    </button>
                    <div class="dropdown-menu" id="userDropdown">
                        <div class="dropdown-header">{{ auth()->user()->email }}</div>
                        <div class="dropdown-divider"></div>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                                Admin Panel
                            </a>
                            <div class="dropdown-divider"></div>
                        @endif
                        <a href="{{ route('dashboard') }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            Dashboard
                        </a>
                        <a href="{{ route('bookings.index') }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            My Bookings
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"    class="btn btn-outline-white btn-sm">Login</a>
                <a href="{{ route('register') }}" class="btn btn-gold btn-sm">Register</a>
            @endauth
        </div>

        <button class="navbar-toggle" id="navToggle" aria-label="Toggle navigation">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 12h18M3 6h18M3 18h18"/>
            </svg>
        </button>
    </div>
</nav>

<!-- Flash messages -->
@if(session('success') || session('error') || session('info') || session('warning'))
<div class="container mt-2">
    @foreach(['success','error','info','warning'] as $type)
        @if(session($type))
        <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                @if($type === 'success')
                    <polyline points="20 6 9 17 4 12"/>
                @elseif($type === 'error')
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                @else
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                @endif
            </svg>
            <span>{{ session($type) }}</span>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
        @endif
    @endforeach
</div>
@endif

<main>
    @yield('content')
</main>

<!-- Footer -->
<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="brand-name">Nexus<span class="brand-accent">Rides</span></div>
            <p>Uganda's trusted vehicle hire and fleet management platform. Safe, reliable, affordable.</p>
            <div class="footer-certifications">
                <span class="cert-badge">Licensed Operator</span>
                <span class="cert-badge">Verified Fleet</span>
            </div>
        </div>
        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('vehicles.index') }}">Browse Fleet</a></li>
                @guest
                <li><a href="{{ route('register') }}">Create Account</a></li>
                <li><a href="{{ route('login') }}">Login</a></li>
                @endguest
            </ul>
        </div>
        <div class="footer-col">
            <h4>Services</h4>
            <ul>
                <li><a href="#">Car Rental</a></li>
                <li><a href="#">SUV Hire</a></li>
                <li><a href="#">Van & Bus</a></li>
                <li><a href="#">Corporate Fleet</a></li>
                <li><a href="#">Airport Transfers</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Contact</h4>
            <ul>
                <li>
                    <a href="#" class="footer-contact-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Kampala, Uganda
                    </a>
                </li>
                <li>
                    <a href="tel:+256700000000" class="footer-contact-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        +256 700 000 000
                    </a>
                </li>
                <li>
                    <a href="mailto:info@nexusrides.ug" class="footer-contact-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        info@nexusrides.ug
                    </a>
                </li>
                <li>
                    <a href="#" class="footer-contact-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                        MTN: 0770 000 000
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; {{ date('Y') }} NexusRides. All rights reserved.</span>
        <div class="social-links">
            <a href="#" title="Facebook" aria-label="Facebook">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
            </a>
            <a href="#" title="Twitter" aria-label="Twitter">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
            </a>
            <a href="#" title="WhatsApp" aria-label="WhatsApp">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            </a>
        </div>
    </div>
</footer>

<script>
(function(){
    // Mobile nav toggle
    var toggle = document.getElementById('navToggle');
    var nav    = document.getElementById('mainNav');
    if(toggle && nav){
        toggle.addEventListener('click', function(){
            nav.classList.toggle('open');
        });
    }
    // User dropdown
    var btn      = document.getElementById('userMenuBtn');
    var dropdown = document.getElementById('userDropdown');
    if(btn && dropdown){
        btn.addEventListener('click', function(e){
            e.stopPropagation();
            dropdown.classList.toggle('open');
        });
        document.addEventListener('click', function(){
            dropdown.classList.remove('open');
        });
    }
    // Auto-dismiss alerts
    setTimeout(function(){
        document.querySelectorAll('.alert').forEach(function(el){
            el.style.transition = 'opacity .5s';
            el.style.opacity = '0';
            setTimeout(function(){ el.remove(); }, 520);
        });
    }, 5000);
})();
</script>
@stack('scripts')
</body>
</html>
