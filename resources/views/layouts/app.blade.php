<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NexusRides') – Vehicle Hire &amp; Management</title>
    <meta name="description" content="@yield('meta_description', 'NexusRides – Premium vehicle hire and management system. Book your perfect ride today.')">
    <link rel="icon" href="{{ asset('assets/img/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    @stack('styles')
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('home') }}" class="navbar-brand">
            <img class="brand-logo" src="{{ asset('assets/img/logo.png') }}" alt="">
            <span class="brand-text">Nexus<span class="brand-accent">Rides</span></span>
        </a>
        <ul class="navbar-nav" id="mainNav">
            <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('vehicles.index') }}" class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Our Fleet</a></li>
            @auth
                <li><a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}">My Bookings</a></li>
                <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
            @endauth
        </ul>
        <div class="navbar-actions">
            @auth
                @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                @php $recentNotifications = auth()->user()->notifications()->latest()->take(5)->get(); @endphp
                <div class="notification-menu" id="notificationMenu">
                    <button class="notif-btn" id="notifBtn" type="button" title="Notifications" aria-label="Notifications" aria-expanded="false" aria-controls="notificationPanel">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        @if($unread > 0)<span class="notif-count">{{ $unread > 99 ? '99+' : $unread }}</span>@endif
                    </button>
                    <section class="notification-panel" id="notificationPanel" aria-label="Recent notifications" hidden>
                        <div class="notification-panel-head">
                            <div>
                                <h2>Notifications</h2>
                                <p>{{ $unread ? $unread . ' unread' : 'You are all caught up' }}</p>
                            </div>
                            <span class="notification-total">{{ $recentNotifications->count() }}</span>
                        </div>
                        @if($recentNotifications->isNotEmpty())
                            <ul class="notification-list">
                                @foreach($recentNotifications as $notification)
                                    <li>
                                        <a class="notification-item {{ $notification->is_read ? '' : 'is-unread' }}" href="{{ $notification->link ?: route('dashboard') }}">
                                            <span class="notification-type-icon type-{{ $notification->type }}" aria-hidden="true">
                                                @if($notification->type === 'booking')
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 3v3m10-3v3M4 9h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z"/></svg>
                                                @elseif($notification->type === 'payment')
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18m-13 5h4"/></svg>
                                                @else
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 11v5m0-8h.01"/></svg>
                                                @endif
                                            </span>
                                            <span class="notification-copy">
                                                <span class="notification-title">{{ $notification->title }}</span>
                                                <span class="notification-message">{{ $notification->message }}</span>
                                                <time class="notification-time" datetime="{{ $notification->created_at?->toIso8601String() }}">{{ $notification->created_at?->diffForHumans() }}</time>
                                            </span>
                                            @unless($notification->is_read)<span class="notification-unread-dot" aria-label="Unread"></span>@endunless
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="notification-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9M10 21h4"/></svg>
                                <p>No notifications yet</p>
                            </div>
                        @endif
                        <a class="notification-view-all" href="{{ route('dashboard') }}">View all activity <span aria-hidden="true">&rarr;</span></a>
                    </section>
                </div>
                <div class="user-menu">
                    <button class="user-btn" id="userMenuBtn" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="userDropdown">
                        <span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                        <span class="user-name-text">{{ Str::limit(auth()->user()->name, 14) }}</span>
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
                    </button>
                    <div class="dropdown-menu" id="userDropdown" aria-label="Account menu">
                        <div class="dropdown-user-card">
                            <span class="dropdown-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                            <span class="dropdown-user-copy">
                                <span class="dropdown-eyebrow">Signed in as</span>
                                <strong>{{ auth()->user()->name }}</strong>
                                <span class="dropdown-email">{{ auth()->user()->email }}</span>
                            </span>
                        </div>
                        <div class="dropdown-divider"></div>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Admin Panel</a>
                            <div class="dropdown-divider"></div>
                        @endif
                        <a href="{{ route('dashboard') }}"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Dashboard</a>
                        <a href="{{ route('profile.edit') }}"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>My Profile</a>
                        <a href="{{ route('bookings.index') }}"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>My Bookings</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-logout"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Sign out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-white btn-sm">Login</a>
                <a href="{{ route('register') }}" class="btn btn-gold btn-sm">Register</a>
            @endauth
        </div>
        <button class="navbar-toggle" id="navToggle" aria-label="Toggle navigation">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
        </button>
    </div>
</nav>

@if(session('success') || session('error') || session('info') || session('warning'))
<div class="container mt-2">
    @foreach(['success', 'error', 'info', 'warning'] as $type)
        @if(session($type))
        <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                @if($type === 'success')
                    <polyline points="20 6 9 17 4 12"/>
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

<footer class="footer">
    <div class="footer-cta">
        <div>
            <span class="footer-eyebrow">Your next journey starts here</span>
            <h2>Find the right ride for the road ahead.</h2>
        </div>
        <a href="{{ route('vehicles.index') }}" class="footer-cta-link">Explore the fleet<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
    </div>
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="brand-name">Nexus<span class="brand-accent">Rides</span></div>
            <p>Reliable rides for every journey. Explore a verified fleet and book with confidence across Uganda.</p>
            <div class="footer-certifications"><span class="cert-badge">Licensed Operator</span><span class="cert-badge">Verified Fleet</span></div>
        </div>
        <div class="footer-col">
            <h4>Explore</h4>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('vehicles.index') }}">Browse the fleet</a></li>
                <li><a href="{{ route('register') }}">Create an account</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Your account</h4>
            <ul>
                @auth
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('bookings.index') }}">My bookings</a></li>
                    <li><a href="{{ route('profile.edit') }}">Profile settings</a></li>
                @else
                    <li><a href="{{ route('login') }}">Sign in</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
        <div class="footer-col">
            <h4>Get in touch</h4>
            <ul>
                <li><span class="footer-contact-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>Kampala, Uganda</span></li>
                <li><a href="tel:+256700000000" class="footer-contact-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>+256 700 000 000</a></li>
                <li><a href="mailto:info@nexusrides.ug" class="footer-contact-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>info@nexusrides.ug</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; {{ date('Y') }} NexusRides. All rights reserved.</span>
        <span class="footer-bottom-note">Made for the journey, wherever it takes you.</span>
    </div>
</footer>

<script>
(function(){
    var toggle = document.getElementById('navToggle');
    var nav = document.getElementById('mainNav');
    if(toggle && nav){
        toggle.addEventListener('click', function(){ nav.classList.toggle('open'); });
    }
    var notificationButton = document.getElementById('notifBtn');
    var notificationMenu = document.getElementById('notificationMenu');
    var notificationPanel = document.getElementById('notificationPanel');
    function closeNotifications(){
        if(notificationButton && notificationPanel){
            notificationPanel.hidden = true;
            notificationButton.setAttribute('aria-expanded', 'false');
        }
    }
    if(notificationButton && notificationMenu && notificationPanel){
        notificationButton.addEventListener('click', function(event){
            event.stopPropagation();
            notificationPanel.hidden = !notificationPanel.hidden;
            notificationButton.setAttribute('aria-expanded', String(!notificationPanel.hidden));
            var userDropdown = document.getElementById('userDropdown');
            if(!notificationPanel.hidden && userDropdown){ userDropdown.classList.remove('open'); }
        });
        document.addEventListener('click', closeNotifications);
        document.addEventListener('keydown', function(event){
            if(event.key === 'Escape'){
                closeNotifications();
                notificationButton.focus();
            }
        });
    }
    var btn = document.getElementById('userMenuBtn');
    var dropdown = document.getElementById('userDropdown');
    if(btn && dropdown){
        btn.addEventListener('click', function(e){
            e.stopPropagation();
            var isOpen = dropdown.classList.toggle('open');
            btn.setAttribute('aria-expanded', String(isOpen));
            if(isOpen){ closeNotifications(); }
        });
        document.addEventListener('click', function(){
            dropdown.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        });
        document.addEventListener('keydown', function(event){
            if(event.key === 'Escape'){
                dropdown.classList.remove('open');
                btn.setAttribute('aria-expanded', 'false');
                btn.focus();
            }
        });
    }
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
