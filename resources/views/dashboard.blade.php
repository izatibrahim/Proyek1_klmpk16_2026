@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .stat-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
    }
    .stat-icon svg { width: 20px; height: 20px; stroke: white; fill: none; stroke-width: 2; }
    .stat-icon.purple { background: var(--primary); }
    .stat-icon.green  { background: var(--success); }
    .stat-icon.orange { background: var(--warning); }
    .stat-icon.red    { background: var(--danger); }

    .stat-value { font-size: 28px; font-weight: 800; color: var(--text-main); line-height: 1; }
    .stat-label { font-size: 13px; color: var(--text-muted); font-weight: 500; }

    .stat-sub {
        font-size: 12px;
        color: var(--success);
        font-weight: 600;
    }
    .stat-sub.warn { color: var(--warning); }

    /* ===== TWO COLUMNS ===== */
    .dashboard-cols {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 900px) {
        .dashboard-cols { grid-template-columns: 1fr; }
    }

    /* ===== CARDS ===== */
    .card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card-title svg { width: 16px; height: 16px; stroke: var(--primary); fill: none; stroke-width: 2; }

    .card-link {
        font-size: 12px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
    }
    .card-link:hover { text-decoration: underline; }

    /* ===== TODO LIST ===== */
    .todo-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 20px;
        border-bottom: 1px solid var(--border);
        transition: background .15s;
    }
    .todo-item:last-child { border-bottom: none; }
    .todo-item:hover { background: var(--bg-page); }

    .todo-check {
        width: 18px; height: 18px;
        border-radius: 50%;
        border: 2px solid var(--border);
        flex-shrink: 0;
        margin-top: 2px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
    }
    .todo-check.done {
        background: var(--success);
        border-color: var(--success);
    }
    .todo-check.done svg { width: 10px; height: 10px; stroke: white; fill: none; stroke-width: 3; }

    .todo-text { font-size: 14px; color: var(--text-main); line-height: 1.4; }
    .todo-text.done { text-decoration: line-through; color: var(--text-muted); }

    .todo-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 4px;
    }

    .category-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
        color: white;
    }

    .deadline-badge {
        font-size: 11px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 3px;
    }
    .deadline-badge svg { width: 11px; height: 11px; stroke: currentColor; fill: none; }
    .deadline-badge.overdue { color: var(--danger); font-weight: 600; }
    .deadline-badge.soon    { color: var(--warning); font-weight: 600; }

    /* ===== HABIT LIST ===== */
    .habit-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        border-bottom: 1px solid var(--border);
    }
    .habit-item:last-child { border-bottom: none; }

    .habit-info { flex: 1; min-width: 0; }
    .habit-name { font-size: 14px; font-weight: 600; color: var(--text-main); }
    .habit-freq { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

    .streak-badge {
        display: flex;
        align-items: center;
        gap: 4px;
        background: #fff7ed;
        color: #c2410c;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .streak-badge svg { width: 13px; height: 13px; stroke: currentColor; fill: currentColor; }

    .check-btn {
        width: 32px; height: 32px;
        border-radius: 50%;
        border: 2px solid var(--border);
        background: none;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all .15s;
        flex-shrink: 0;
    }
    .check-btn svg { width: 14px; height: 14px; stroke: var(--text-muted); fill: none; stroke-width: 2; }
    .check-btn:hover { border-color: var(--success); }
    .check-btn:hover svg { stroke: var(--success); }
    .check-btn.checked { background: var(--success); border-color: var(--success); }
    .check-btn.checked svg { stroke: white; }

    /* ===== PROGRESS BAR ===== */
    .progress-wrap { padding: 16px 20px; }
    .progress-label {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        margin-bottom: 8px;
    }
    .progress-label span:first-child { color: var(--text-muted); }
    .progress-label span:last-child  { font-weight: 700; color: var(--primary); }

    .progress-bar {
        height: 8px;
        background: var(--primary-light);
        border-radius: 99px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: var(--primary);
        border-radius: 99px;
        transition: width .5s ease;
    }

    /* ===== EMPTY STATE ===== */
    .empty {
        text-align: center;
        padding: 32px 20px;
        color: var(--text-muted);
        font-size: 14px;
    }
    .empty svg { width: 36px; height: 36px; stroke: var(--border); fill: none; margin-bottom: 8px; display: block; margin-inline: auto; }
</style>
@endpush

@section('content')

{{-- STATS --}}
@php
    $user        = auth()->user();
    $allTodos    = $user->todos;
    $todayTodos  = $allTodos->where('deadline', today()->toDateString());
    $doneTodos   = $allTodos->where('is_done', true);
    $pending     = $allTodos->where('is_done', false);
    $overdue     = $pending->filter(fn($t) => $t->deadline && $t->deadline->isPast());
    $habits      = $user->habits()->with('logs')->get();
    $todayDone   = $habits->filter(fn($h) => $h->logs->contains(fn($l) => $l->completed_at->isToday()));

    $pct = $allTodos->count() > 0 ? round($doneTodos->count() / $allTodos->count() * 100) : 0;
@endphp

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple">
            <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div class="stat-value">{{ $allTodos->count() }}</div>
        <div class="stat-label">Total Tugas</div>
        <div class="stat-sub">{{ $pct }}% selesai</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-value">{{ $doneTodos->count() }}</div>
        <div class="stat-label">Tugas Selesai</div>
        <div class="stat-sub">Kerja bagus!</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="stat-value">{{ $pending->count() }}</div>
        <div class="stat-label">Belum Selesai</div>
        @if($overdue->count() > 0)
            <div class="stat-sub warn">{{ $overdue->count() }} lewat deadline!</div>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-icon red">
            <svg viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div class="stat-value">{{ $todayDone->count() }}/{{ $habits->count() }}</div>
        <div class="stat-label">Habit Hari Ini</div>
        <div class="stat-sub">Jaga konsistensimu!</div>
    </div>
</div>

{{-- PROGRESS --}}
<div class="card" style="margin-bottom: 20px;">
    <div class="card-header">
        <span class="card-title">
            <svg viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Progress Keseluruhan
        </span>
        <span style="font-size: 13px; font-weight: 700; color: var(--primary);">{{ $pct }}%</span>
    </div>
    <div class="progress-wrap">
        <div class="progress-label">
            <span>{{ $doneTodos->count() }} dari {{ $allTodos->count() }} tugas selesai</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $pct }}%"></div>
        </div>
    </div>
</div>

{{-- TWO COLS --}}
<div class="dashboard-cols">

    {{-- TODO HARI INI --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Tugas Hari Ini
            </span>
            <a href="{{ route('todos.index') }}" class="card-link">Lihat semua →</a>
        </div>

        @forelse($todayTodos->take(5) as $todo)
            <div class="todo-item">
                <form method="POST" action="{{ route('todos.index', $todo->todos_id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="todo-check {{ $todo->is_done ? 'done' : '' }}" title="Toggle">
                        @if($todo->is_done)
                            <svg viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        @endif
                    </button>
                </form>
                <div style="flex:1; min-width:0;">
                    <div class="todo-text {{ $todo->is_done ? 'done' : '' }}">{{ $todo->title }}</div>
                    <div class="todo-meta">
                        @if($todo->category)
                            <span class="category-badge" style="background: {{ $todo->category->color }}">
                                {{ $todo->category->name }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty">
                <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Tidak ada tugas untuk hari ini
            </div>
        @endforelse
    </div>

    {{-- HABITS --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <svg viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Habit Hari Ini
            </span>
            <a href="{{ route('habits.index') }}" class="card-link">Kelola →</a>
        </div>

        @forelse($habits->take(5) as $habit)
            @php $checkedToday = $habit->logs->contains(fn($l) => $l->completed_at->isToday()); @endphp
            <div class="habit-item">
                <div class="habit-info">
                    <div class="habit-name">{{ $habit->name }}</div>
                    <div class="habit-freq">{{ $habit->frequency === 'daily' ? 'Harian' : 'Mingguan' }}</div>
                </div>
                <div class="streak-badge">
                    <svg viewBox="0 0 24 24" stroke-width="0"><path d="M12 2c.4 0 .7.2.9.5l2.8 5.6 6.2.9c.5.1.9.5.9 1s-.2.9-.5 1.2l-4.5 4.4 1.1 6.2c.1.5-.1 1-.5 1.3-.4.2-.9.3-1.3 0L12 20.1l-5.6 2.9c-.4.2-.9.2-1.3 0-.4-.3-.6-.8-.5-1.3l1.1-6.2L1.3 11c-.3-.3-.5-.7-.5-1.2s.4-.9.9-1l6.2-.9L10.1 2.5c.2-.3.5-.5.9-.5z"/></svg>
                    {{ $habit->streak_count }}
                </div>
                <form method="POST" action="{{ route('habits.check', $habit->habits_id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="check-btn {{ $checkedToday ? 'checked' : '' }}" title="Tandai selesai">
                        <svg viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    </button>
                </form>
            </div>
        @empty
            <div class="empty">
                <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Belum ada habit. Yuk tambahkan!
            </div>
        @endforelse
    </div>

</div>
@endsection
