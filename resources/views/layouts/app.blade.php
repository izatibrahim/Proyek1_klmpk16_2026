<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--bg-card);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border);
        }

        .brand-icon {
            width: 36px; height: 36px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .brand-icon svg { width: 20px; height: 20px; stroke: white; fill: none; }

        .brand-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -.3px;
        }
        .brand-name span { color: var(--primary); }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 8px 8px 4px;
            margin-top: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all .15s;
        }
        .nav-link svg { width: 18px; height: 18px; stroke: currentColor; fill: none; flex-shrink: 0; }
        .nav-link:hover { background: var(--bg-page); color: var(--text-main); }
        .nav-link.active { background: var(--primary-light); color: var(--primary); }
        .nav-link.active svg { stroke: var(--primary); }

        .nav-badge {
            margin-left: auto;
            background: var(--primary);
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            cursor: default;
        }

        .user-avatar {
            width: 34px; height: 34px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .user-name  { font-size: 13px; font-weight: 600; color: var(--text-main); }
        .user-email { font-size: 11px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px; }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            margin-top: 8px;
            border-radius: var(--radius-sm);
            color: var(--danger);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            transition: background .15s;
        }
        .logout-btn svg { width: 16px; height: 16px; stroke: currentColor; fill: none; }
        .logout-btn:hover { background: #fef2f2; }

        /* ===== MAIN CONTENT ===== */
        .main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-left { display: flex; align-items: center; gap: 12px; }

        .hamburger {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: var(--radius-sm);
        }
        .hamburger svg { width: 22px; height: 22px; stroke: var(--text-main); fill: none; }

        .page-title { font-size: 17px; font-weight: 700; color: var(--text-main); }

        .topbar-date {
            font-size: 13px;
            color: var(--text-muted);
        }

        .content {
            padding: 28px;
            flex: 1;
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger   { background: #fee2e2; color: #991b1b; }
        .alert svg { width: 16px; height: 16px; stroke: currentColor; fill: none; }

        /* ===== OVERLAY (mobile) ===== */
        .overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.4);
            z-index: 90;
        }

        /* ===== MOBILE ===== */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .hamburger { display: flex; }
            .content { padding: 16px; }
            .topbar { padding: 0 16px; }
            .overlay.show { display: block; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Overlay mobile --}}
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

{{-- Sidebar --}}
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
        <span class="nav-label">Menu</span>

        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>

        <a href="{{ route('todos.index') }}" class="nav-link {{ request()->routeIs('todos.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" stroke-width="1.8"><path d="M9 12l2 2 4-4"/><path d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"/></svg>
            To-Do List
        </a>

        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" stroke-width="1.8"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Kategori
        </a>

        <a href="{{ route('habits.index') }}" class="nav-link {{ request()->routeIs('habits.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" stroke-width="1.8"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Habit Tracker
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div style="min-width:0">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-email">{{ auth()->user()->email }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg viewBox="0 0 24 24" stroke-width="1.8"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- Main --}}
<div class="main">
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()" aria-label="Menu">
                <svg viewBox="0 0 24 24" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        </div>
        <span class="topbar-date" id="date-display"></span>
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

        @yield('content')
    </main>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('overlay').classList.toggle('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('overlay').classList.remove('show');
    }

    // Tanggal Indonesia
    const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const now    = new Date();
    document.getElementById('date-display').textContent =
        `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
</script>

@stack('scripts')
</body>
</html>
