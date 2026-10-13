<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — NexusRides Admin</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="admin-body">

<div class="adm-layout" id="adminLayout">

    {{-- ══════════════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════════════ --}}
    <aside class="adm-sidebar" id="adminSidebar">

        {{-- Brand --}}
        <a href="{{ route('admin.dashboard') }}" class="adm-brand">
            <div class="adm-brand-icon">
                <svg width="22" height="22" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M6 26l5-10 5 5 5-8 5 6 4-6" stroke="#0a2540" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="9" cy="27" r="2.5" fill="#0a2540"/>
                    <circle cx="27" cy="27" r="2.5" fill="#0a2540"/>
                </svg>
            </div>
            <div class="adm-brand-text">
                <span class="adm-brand-name">Nexus<em>Rides</em></span>
                <span class="adm-brand-sub">Admin Console</span>
            </div>
        </a>

        {{-- Nav --}}
        <nav class="adm-nav">

            <div class="adm-nav-group">
                <span class="adm-nav-label">Overview</span>
                <a href="{{ route('admin.dashboard') }}"
                   class="adm-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                    <span class="adm-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    </span>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="adm-nav-group">
                <span class="adm-nav-label">Fleet</span>
                <a href="{{ route('admin.vehicles.index') }}"
                   class="adm-nav-link {{ request()->routeIs('admin.vehicles.*') ? 'is-active' : '' }}">
                    <span class="adm-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2h-2"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
                    </span>
                    <span>Vehicles</span>
                </a>
            </div>

            <div class="adm-nav-group">
                <span class="adm-nav-label">Operations</span>
                <a href="{{ route('admin.bookings.index') }}"
                   class="adm-nav-link {{ request()->routeIs('admin.bookings.*') ? 'is-active' : '' }}">
                    <span class="adm-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </span>
                    <span>Bookings</span>
                    @php $pending = \App\Models\Booking::where('status','pending')->count(); @endphp
                    @if($pending > 0)
                        <span class="adm-nav-badge">{{ $pending }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.customers.index') }}"
                   class="adm-nav-link {{ request()->routeIs('admin.customers.*') ? 'is-active' : '' }}">
                    <span class="adm-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    </span>
                    <span>Customers</span>
                </a>
            </div>

            <div class="adm-nav-group">
                <span class="adm-nav-label">Analytics</span>
                <a href="{{ route('admin.reports.index') }}"
                   class="adm-nav-link {{ request()->routeIs('admin.reports.*') ? 'is-active' : '' }}">
                    <span class="adm-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </span>
                    <span>Reports</span>
                </a>
            </div>

        </nav>

        {{-- Sidebar Footer --}}
        <div class="adm-sidebar-footer">
            <a href="{{ route('home') }}" class="adm-nav-link adm-nav-link-muted">
                <span class="adm-nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </span>
                <span>View Website</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="adm-nav-link adm-nav-link-muted adm-logout-btn">
                    <span class="adm-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    </span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ══════════════════════════════════════════════
         MAIN AREA
    ══════════════════════════════════════════════ --}}
    <div class="adm-main-area">

        {{-- Top Bar --}}
        <header class="adm-topbar">
            <div class="adm-topbar-left">
                <button class="adm-sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <div class="adm-breadcrumb">
                    <span class="adm-breadcrumb-home">Admin</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="adm-breadcrumb-sep"><polyline points="9 18 15 12 9 6"/></svg>
                    <span class="adm-breadcrumb-current">@yield('title', 'Dashboard')</span>
                </div>
            </div>

            <div class="adm-topbar-right">
                @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                <button class="adm-icon-btn" title="Notifications">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                    @if($unread > 0)
                        <span class="adm-icon-btn-badge">{{ $unread }}</span>
                    @endif
                </button>

                <div class="adm-user-chip">
                    <div class="adm-user-avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                    <div class="adm-user-info">
                        <span class="adm-user-name">{{ auth()->user()->name }}</span>
                        <span class="adm-user-role">Administrator</span>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success') || session('error') || session('info') || session('warning'))
        <div class="adm-alerts-wrap">
            @foreach(['success','error','info','warning'] as $type)
                @if(session($type))
                <div class="adm-alert adm-alert-{{ $type === 'error' ? 'danger' : $type }}">
                    <span class="adm-alert-icon">
                        @if($type === 'success') ✓
                        @elseif($type === 'error') ✕
                        @elseif($type === 'warning') ⚠
                        @else ℹ
                        @endif
                    </span>
                    <span>{{ session($type) }}</span>
                    <button class="adm-alert-close" onclick="this.closest('.adm-alert').remove()">✕</button>
                </div>
                @endif
            @endforeach
        </div>
        @endif

        {{-- Page Content --}}
        <main class="adm-content">
            @yield('content')
        </main>
    </div>
</div>

<script>
// Sidebar toggle
const toggle = document.getElementById('sidebarToggle');
const layout = document.getElementById('adminLayout');
if (toggle) {
    toggle.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            layout.classList.toggle('sidebar-open');
        } else {
            layout.classList.toggle('sidebar-collapsed');
        }
    });
}

// Auto-dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.adm-alert').forEach(el => {
        el.style.transition = 'opacity .4s, transform .4s';
        el.style.opacity = '0';
        el.style.transform = 'translateY(-6px)';
        setTimeout(() => el.remove(), 400);
    });
}, 5000);
</script>
@stack('scripts')
</body>
</html>
