<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MyHabit') — MyHabit</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ===== GLOBAL ===== */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:       #6366f1;
            --primary-dark:  #4f46e5;
            --primary-light: #e0e7ff;
            --success:       #10b981;
            --warning:       #f59e0b;
            --danger:        #ef4444;
            --text-main:     #111827;
            --text-muted:    #6b7280;
            --bg-page:       #f9fafb;
            --bg-card:       #ffffff;
            --border:        #e5e7eb;
            --sidebar-w:     240px;
            --radius:        12px;
            --radius-sm:     8px;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            background: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
        }

        /* ===== SIDEBAR REDESIGN ===== */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: var(--bg-card); border-right: 1px solid var(--border);
            display: flex; flex-direction: column; z-index: 100;
            transition: width .3s cubic-bezier(0.4, 0, 0.2, 1), transform .3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex; align-items: center; gap: 12px;
            border-bottom: 1px solid var(--border);
            height: 64px; overflow: hidden; flex-shrink: 0;
        }
        .brand-icon {
            width: 36px; height: 36px; background: var(--primary);
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15); flex-shrink: 0;
        }
        .brand-icon svg { width: 20px; height: 20px; stroke: white; fill: none; }
        
        .brand-name { 
            font-size: 16px; font-weight: 700; color: var(--text-main); letter-spacing: -.3px; 
            transition: opacity 0.2s ease, transform 0.2s ease; white-space: nowrap;
        }
        .brand-name span { color: var(--primary); }

        .sidebar-nav {
            padding: 20px 14px; flex: 1;
            display: flex; flex-direction: column; gap: 6px; overflow-y: auto; overflow-x: hidden;
        }

        .nav-label {
            font-size: 10px; font-weight: 700; letter-spacing: .08em;
            text-transform: uppercase; color: var(--text-muted);
            padding: 10px 12px 4px; opacity: 0.8;
            transition: opacity 0.2s ease, height 0.2s ease, padding 0.2s ease;
            white-space: nowrap;
        }

        .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; border-radius: 10px;
            color: var(--text-muted); text-decoration: none;
            font-size: 14px; font-weight: 500; 
            transition: background .2s, color .2s, transform .2s;
            white-space: nowrap;
        }
        .nav-link svg { width: 20px; height: 20px; stroke: currentColor; fill: none; flex-shrink: 0; }
        
        body:not(.sidebar-collapsed) .nav-link:hover { transform: translateX(4px); }
        .nav-link:hover { background: var(--bg-page); color: var(--text-main); }
        .nav-link.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }

        .nav-badge {
            margin-left: auto; background: var(--danger); color: white;
            font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 99px;
            min-width: 18px; text-align: center;
        }

        /* ===== SIDEBAR FOOTER ===== */
        .sidebar-footer { padding: 16px 14px; border-top: 1px solid var(--border); flex-shrink: 0; }

        .user-card {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px; border-radius: 10px; background: rgba(249, 250, 251, 0.7);
            overflow: hidden; transition: justify-content 0.3s;
        }
        .user-avatar {
            width: 36px; height: 36px; background: var(--primary-light);
            color: var(--primary); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(99, 102, 241, 0.1);
        }
        .user-meta { transition: opacity 0.2s ease; white-space: nowrap; }
        .user-name  { font-size: 13px; font-weight: 600; color: var(--text-main); line-height: 1.2; }
        .user-email { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

        .logout-btn {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 14px; margin-top: 10px; border-radius: 10px;
            color: var(--danger); font-size: 13px; font-weight: 600;
            cursor: pointer; background: #fff5f5; border: 1px solid rgba(239, 68, 68, 0.1); width: 100%;
            transition: all .2s; white-space: nowrap;
        }
        .logout-btn svg { width: 18px; height: 18px; stroke: currentColor; fill: none; flex-shrink: 0; }
        .logout-btn:hover { background: var(--danger); color: white; }

        /* ===== DESKTOP COLLAPSE MODE (ELEGANT MINI) ===== */
        @media (min-width: 769px) {
            body.sidebar-collapsed .sidebar { width: 78px; }
            body.sidebar-collapsed .main { margin-left: 78px; }
            
            body.sidebar-collapsed .brand-name,
            body.sidebar-collapsed .nav-link-text,
            body.sidebar-collapsed .user-meta,
            body.sidebar-collapsed .logout-text,
            body.sidebar-collapsed .nav-badge {
                opacity: 0; pointer-events: none; display: none;
            }
            
            body.sidebar-collapsed .nav-label {
                opacity: 0; height: 0; padding: 0; overflow: hidden;
            }
            
            body.sidebar-collapsed .user-card { justify-content: center; padding: 10px 0; background: transparent; }
            body.sidebar-collapsed .logout-btn { justify-content: center; padding: 11px 0; }
        }

        /* ===== MAIN WINDOW ===== */
        .main { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; transition: margin .3s ease; }

        .topbar {
            background: var(--bg-card); border-bottom: 1px solid var(--border);
            padding: 0 28px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }

        .topbar-left  { display: flex; align-items: center; gap: 12px; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .hamburger {
            display: none; background: none; border: none; cursor: pointer;
            padding: 6px; border-radius: var(--radius-sm);
        }
        .hamburger svg { width: 22px; height: 22px; stroke: var(--text-main); fill: none; }

        .sidebar-toggle {
            display: inline-flex; align-items: center; justify-content: center;
            width: 38px; height: 38px; border: 1px solid var(--border);
            border-radius: var(--radius-sm); background: var(--bg-page); cursor: pointer;
            transition: all .15s;
        }
        .sidebar-toggle svg { width: 18px; height: 18px; stroke: var(--text-main); fill: none; }
        .sidebar-toggle:hover { border-color: var(--primary); background: var(--primary-light); }

        .page-title { font-size: 17px; font-weight: 700; color: var(--text-main); }
        .topbar-date { font-size: 13px; color: var(--text-muted); }

        /* ===== BELL NOTIF ===== */
        .bell-wrap { position: relative; }
        .bell-btn {
            width: 38px; height: 38px; border-radius: var(--radius-sm);
            border: 1px solid var(--border); background: var(--bg-page);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all .15s; position: relative;
        }
        .bell-btn svg { width: 18px; height: 18px; stroke: var(--text-muted); fill: none; stroke-width: 1.8; }
        .bell-btn:hover { border-color: var(--primary); background: var(--primary-light); }
        .bell-btn:hover svg { stroke: var(--primary); }

        .bell-count {
            position: absolute; top: -5px; right: -5px;
            background: var(--danger); color: white;
            font-size: 9px; font-weight: 700;
            width: 17px; height: 17px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--bg-card);
        }
        .bell-count.hidden { display: none; }

        .bell-dropdown {
            display: none; position: absolute; top: calc(100% + 8px); right: 0;
            width: 320px; background: var(--bg-card);
            border: 1px solid var(--border); border-radius: var(--radius);
            box-shadow: 0 8px 32px rgba(0,0,0,.12);
            z-index: 200; overflow: hidden;
        }
        .bell-dropdown.open { display: block; }
        .bell-header { padding: 14px 16px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .bell-header-title { font-size: 13px; font-weight: 700; color: var(--text-main); }
        .bell-see-all { font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 600; }

        .bell-item { display: flex; gap: 10px; padding: 12px 16px; border-bottom: 1px solid var(--border); text-decoration: none; transition: background .15s; }
        .bell-item:last-child { border-bottom: none; }
        .bell-item:hover { background: var(--bg-page); }
        .bell-item.unread { background: #f5f3ff; }
        .bell-icon { font-size: 18px; flex-shrink: 0; width: 20px; text-align: center; margin-top: 1px; }
        .bell-body { flex: 1; min-width: 0; }
        .bell-title { font-size: 12px; font-weight: 700; color: var(--text-main); margin-bottom: 2px; }
        .bell-msg   { font-size: 11px; color: var(--text-muted); line-height: 1.4; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .bell-time  { font-size: 10px; color: var(--text-muted); margin-top: 3px; }
        .bell-empty { padding: 24px; text-align: center; font-size: 13px; color: var(--text-muted); }

        /* ===== CONTENT & ALERTS ===== */
        .content { padding: 28px; flex: 1; }
        .alert { padding: 12px 16px; border-radius: var(--radius-sm); font-size: 14px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger  { background: #fee2e2; color: #991b1b; }
        .alert svg { width: 16px; height: 16px; stroke: currentColor; fill: none; }

        /* ===== OVERLAY (mobile) ===== */
        .overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 90; }

        /* ===== MOBILE MEDIA QUERIES ===== */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0 !important; }
            .hamburger { display: flex; }
            .sidebar-toggle { display: none; }
            .content { padding: 16px; }
            .topbar { padding: 0 16px; }
            .overlay.show { display: block; }
            .bell-dropdown { width: 280px; }
            .topbar-date { display: none; }
        }
    </style>
    @stack('styles')
    
    {{-- Mencegah Flicker sebelum rendering body --}}
    <script>
        if (window.innerWidth > 768 && localStorage.getItem('sidebar-collapsed') === 'true') {
            document.documentElement.classList.add('preload-collapsed');
        }
    </script>
</head>
<body>

<script>
    if (window.innerWidth > 768 && localStorage.getItem('sidebar-collapsed') === 'true') {
        document.body.classList.add('sidebar-collapsed');
    }
</script>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

{{-- SIDEBAR --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
        </div>
        <span class="brand-name">My<span>Habit</span></span>
    </div>

    <nav class="sidebar-nav">
        <span class="nav-label">Menu Utama</span>

        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            <span class="nav-link-text">Dashboard</span>
        </a>

        <a href="{{ route('todos.index') }}" class="nav-link {{ request()->routeIs('todos.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" stroke-width="1.8"><path d="M9 12l2 2 4-4"/><path d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"/></svg>
            <span class="nav-link-text">To-Do List</span>
            @php $pendingCount = auth()->check() ? auth()->user()->todos()->where('is_done', false)->count() : 0; @endphp
            @if($pendingCount > 0)
                <span class="nav-badge">{{ $pendingCount > 99 ? '99+' : $pendingCount }}</span>
            @endif
        </a>

        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" stroke-width="1.8"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            <span class="nav-link-text">Kategori</span>
        </a>

        <a href="{{ route('habits.index') }}" class="nav-link {{ request()->routeIs('habits.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" stroke-width="1.8"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span class="nav-link-text">Habit Tracker</span>
        </a>

        <span class="nav-label">Lainnya</span>

        <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" stroke-width="1.8"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
            <span class="nav-link-text">Notifikasi</span>
            @php
                $unread = (auth()->check() && Illuminate\Support\Facades\Schema::hasTable('notifications'))
                    ? auth()->user()->notifications()->where('is_read', false)->count()
                    : 0;
            @endphp
            @if($unread > 0)
                <span class="nav-badge">{{ $unread > 99 ? '99+' : $unread }}</span>
            @endif
        </a>
    </nav>

    <div class="sidebar-footer">
        @auth
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div class="user-meta" style="min-width:0">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-email">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg viewBox="0 0 24 24" stroke-width="1.8"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span class="logout-text">Keluar</span>
                </button>
            </form>
        @else
            <div class="user-card">
                <div class="user-avatar">MY</div>
                <div class="user-meta" style="min-width:0">
                    <div class="user-name">Tamu</div>
                    <div class="user-email">Silakan login</div>
                </div>
            </div>
        @endauth
    </div>
</aside>

{{-- MAIN WINDOW --}}
<div class="main">
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()" title="Menu mobile">
                <svg viewBox="0 0 24 24" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <button class="sidebar-toggle" onclick="toggleSidebarCollapse()" title="Sembunyikan/Perlihatkan sidebar">
                <svg viewBox="0 0 24 24" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="topbar-right">
            <span class="topbar-date" id="date-display"></span>

            {{-- BELL NOTIFIKASI --}}
            <div class="bell-wrap" id="bellWrap">
                <button class="bell-btn" onclick="toggleBell()" title="Notifikasi">
                    <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                </button>
                <div class="bell-count hidden" id="bellCount">0</div>

                <div class="bell-dropdown" id="bellDropdown">
                    <div class="bell-header">
                        <span class="bell-header-title">🔔 Notifikasi</span>
                        <a href="{{ route('notifications.index') }}" class="bell-see-all">Lihat semua →</a>
                    </div>
                    <div id="bellItems">
                        <div class="bell-empty">Memuat...</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="content">
        @if(session('success'))
            <div class="alert alert-success">
                <svg viewBox="0 0 24 24" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <svg viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @hasSection('content')
            @yield('content')
        @else
            {{ $slot }}
        @endif
    </main>
</div>

{{-- LOGICA JAVASCRIPT --}}
<script>
// ===== SIDEBAR CONTROL ENGINE =====
function toggleSidebar() {
    if (window.innerWidth <= 768) {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('overlay').classList.toggle('show');
    }
}

function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('show');
}

function toggleSidebarCollapse() {
    if (window.innerWidth > 768) {
        document.body.classList.toggle('sidebar-collapsed');
        const isCollapsed = document.body.classList.contains('sidebar-collapsed');
        localStorage.setItem('sidebar-collapsed', isCollapsed);
    } else {
        toggleSidebar();
    }
}

// ===== REAL-TIME SYSTEM DATE =====
const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
const now    = new Date();
document.getElementById('date-display').textContent =
    `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;

// ===== BELL SYSTEM NOTIFICATION =====
let bellLoaded = false;

function toggleBell() {
    const dropdown = document.getElementById('bellDropdown');
    dropdown.classList.toggle('open');
    if (dropdown.classList.contains('open') && !bellLoaded) {
        loadNotifications();
    }
}

document.addEventListener('click', function(e) {
    const wrap = document.getElementById('bellWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('bellDropdown').classList.remove('open');
    }
});

async function loadNotifications() {
    try {
        const tokenElem = document.querySelector('meta[name="csrf-token"]');
        const headers = tokenElem ? { 'X-CSRF-TOKEN': tokenElem.content } : {};
        
        const res  = await fetch('{{ route("notifications.unreadCount") }}', { headers });
        const data = await res.json();

        const badge = document.getElementById('bellCount');
        if (data.count > 0) {
            badge.textContent = data.count > 9 ? '9+' : data.count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }

        const container = document.getElementById('bellItems');
        if (!data.items || data.items.length === 0) {
            container.innerHTML = '<div class="bell-empty">Tidak ada notifikasi baru</div>';
        } else {
            container.innerHTML = data.items.map(n => `
                <a href="${n.link || '#'}" class="bell-item unread">
                    <div class="bell-icon">${n.icon || '📌'}</div>
                    <div class="bell-body">
                        <div class="bell-title">${n.title}</div>
                        <div class="bell-msg">${n.message}</div>
                        <div class="bell-time">${n.created_at}</div>
                    </div>
                </a>
            `).join('');
        }
        bellLoaded = true;
    } catch(e) {
        document.getElementById('bellItems').innerHTML = '<div class="bell-empty">Gagal memuat notifikasi.</div>';
    }
}

window.addEventListener('DOMContentLoaded', async () => {
    try {
        const res  = await fetch('{{ route("notifications.unreadCount") }}');
        const data = await res.json();
        const badge = document.getElementById('bellCount');
        if (data.count > 0) {
            badge.textContent = data.count > 9 ? '9+' : data.count;
            badge.classList.remove('hidden');
        }
    } catch(e) {}
});
</script>

@stack('scripts')
</body>
</html>