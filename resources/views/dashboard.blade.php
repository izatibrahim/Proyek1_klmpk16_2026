@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* ===== GREETING ===== */
    .greeting {
        background: linear-gradient(135deg, var(--primary) 0%, #818cf8 100%);
        border-radius: var(--radius);
        padding: 24px 28px;
        color: white;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }
    .greeting-text h2 { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
    .greeting-text p  { font-size: 14px; opacity: .85; }
    .greeting-emoji   { font-size: 48px; line-height: 1; }

    /* ===== STATS GRID ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(155px, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px 16px;
    }
    .stat-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 12px;
    }
    .stat-icon svg { width: 18px; height: 18px; stroke: white; fill: none; stroke-width: 2; }
    .stat-icon.purple { background: var(--primary); }
    .stat-icon.green  { background: var(--success); }
    .stat-icon.orange { background: var(--warning); }
    .stat-icon.red    { background: var(--danger); }
    .stat-icon.fire   { background: #f97316; font-size: 18px; }

    .stat-value { font-size: 26px; font-weight: 800; color: var(--text-main); line-height: 1; }
    .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; margin-top: 4px; }
    .stat-change {
        font-size: 11px; font-weight: 700;
        margin-top: 6px; padding: 2px 8px;
        border-radius: 99px; display: inline-block;
    }
    .stat-change.good { background: #d1fae5; color: #065f46; }
    .stat-change.warn { background: #fef3c7; color: #92400e; }
    .stat-change.bad  { background: #fee2e2; color: #991b1b; }

    /* ===== PROGRESS BAR ===== */
    .prog-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 20px 24px; margin-bottom: 24px;
    }
    .prog-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;
    }
    .prog-title { font-size: 14px; font-weight: 700; color: var(--text-main); }
    .prog-pct   { font-size: 22px; font-weight: 800; color: var(--primary); }
    .prog-bar {
        height: 12px; background: var(--primary-light);
        border-radius: 99px; overflow: hidden;
    }
    .prog-fill {
        height: 100%; background: linear-gradient(90deg, var(--primary), #818cf8);
        border-radius: 99px; transition: width .8s cubic-bezier(.4,0,.2,1);
    }
    .prog-sub {
        display: flex; justify-content: space-between;
        font-size: 12px; color: var(--text-muted); margin-top: 8px;
    }

    /* ===== CHARTS GRID ===== */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 860px) {
        .charts-grid { grid-template-columns: 1fr; }
    }

    .chart-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 20px; overflow: hidden;
    }
    .chart-header {
        display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;
    }
    .chart-title {
        font-size: 13px; font-weight: 700; color: var(--text-main);
        display: flex; align-items: center; gap: 8px;
    }
    .chart-title svg { width: 14px; height: 14px; stroke: var(--primary); fill: none; stroke-width: 2; }
    .chart-subtitle { font-size: 11px; color: var(--text-muted); }

    .chart-wrap { position: relative; height: 180px; }

    /* ===== THREE COLUMNS ===== */
    .bottom-cols {
        display: grid;
        grid-template-columns: 1fr 1fr 320px;
        gap: 16px;
    }
    @media (max-width: 1100px) {
        .bottom-cols { grid-template-columns: 1fr 1fr; }
        .bottom-cols .notif-col { grid-column: 1 / -1; }
    }
    @media (max-width: 700px) {
        .bottom-cols { grid-template-columns: 1fr; }
    }

    /* ===== CARD BASE ===== */
    .card {
        background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden;
    }
    .card-header {
        padding: 14px 18px; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .card-title {
        font-size: 13px; font-weight: 700; color: var(--text-main);
        display: flex; align-items: center; gap: 8px;
    }
    .card-title svg { width: 14px; height: 14px; stroke: var(--primary); fill: none; stroke-width: 2; }
    .card-link { font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 600; }
    .card-link:hover { text-decoration: underline; }

    /* ===== TODO LIST ===== */
    .todo-item {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 11px 18px; border-bottom: 1px solid var(--border); transition: background .15s;
    }
    .todo-item:last-child { border-bottom: none; }
    .todo-item:hover { background: var(--bg-page); }

    .todo-check {
        width: 18px; height: 18px; border-radius: 50%;
        border: 2px solid var(--border); flex-shrink: 0; margin-top: 2px;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        background: none;
    }
    .todo-check.done { background: var(--success); border-color: var(--success); }
    .todo-check.done svg { width: 10px; height: 10px; stroke: white; fill: none; stroke-width: 3; }

    .todo-title { font-size: 13px; font-weight: 500; color: var(--text-main); }
    .todo-title.done { text-decoration: line-through; color: var(--text-muted); }
    .cat-badge {
        font-size: 10px; font-weight: 700; padding: 2px 7px;
        border-radius: 99px; color: white; margin-top: 3px; display: inline-block;
    }
    .deadline-tag {
        font-size: 11px; color: var(--text-muted); display: flex; align-items: center; gap: 3px;
    }
    .deadline-tag.overdue { color: var(--danger); font-weight: 600; }
    .deadline-tag.soon    { color: var(--warning); font-weight: 600; }
    .deadline-tag svg { width: 10px; height: 10px; stroke: currentColor; fill: none; }

    /* ===== HABIT LIST ===== */
    .habit-item {
        display: flex; align-items: center; gap: 10px;
        padding: 11px 18px; border-bottom: 1px solid var(--border);
    }
    .habit-item:last-child { border-bottom: none; }

    .habit-info { flex: 1; min-width: 0; }
    .habit-name { font-size: 13px; font-weight: 600; color: var(--text-main); }
    .habit-freq { font-size: 11px; color: var(--text-muted); }

    .streak-pill {
        font-size: 11px; font-weight: 700; padding: 3px 9px;
        border-radius: 99px; background: #fff7ed; color: #c2410c; white-space: nowrap;
    }

    .check-sm {
        width: 28px; height: 28px; border-radius: 50%;
        border: 2px solid var(--border); background: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; transition: all .15s;
    }
    .check-sm svg { width: 12px; height: 12px; stroke: var(--text-muted); fill: none; stroke-width: 2.5; }
    .check-sm.done { background: var(--success); border-color: var(--success); }
    .check-sm.done svg { stroke: white; }

    /* ===== NOTIFIKASI ===== */
    .notif-item {
        display: flex; gap: 10px;
        padding: 12px 16px; border-bottom: 1px solid var(--border);
        transition: background .15s; position: relative;
    }
    .notif-item:last-child { border-bottom: none; }
    .notif-item:hover { background: var(--bg-page); }
    .notif-item.unread { background: #f5f3ff; }
    .notif-item.unread::before {
        content: ''; width: 6px; height: 6px;
        background: var(--primary); border-radius: 50%;
        position: absolute; left: 8px; top: 18px;
    }

    .notif-icon { font-size: 20px; flex-shrink: 0; width: 24px; text-align: center; }
    .notif-body { flex: 1; min-width: 0; }
    .notif-title { font-size: 12px; font-weight: 700; color: var(--text-main); margin-bottom: 2px; }
    .notif-msg { font-size: 12px; color: var(--text-muted); line-height: 1.4; }
    .notif-time { font-size: 11px; color: var(--text-muted); margin-top: 3px; }

    .notif-del {
        width: 22px; height: 22px; background: none; border: none;
        cursor: pointer; opacity: .4; transition: opacity .15s;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .notif-del svg { width: 12px; height: 12px; stroke: var(--text-muted); fill: none; }
    .notif-del:hover { opacity: 1; }

    .notif-empty {
        text-align: center; padding: 28px; color: var(--text-muted); font-size: 13px;
    }

    /* ===== KATEGORI ===== */
    .cat-row {
        display: flex; align-items: center; gap: 12px;
        padding: 11px 18px; border-bottom: 1px solid var(--border);
    }
    .cat-row:last-child { border-bottom: none; }
    .cat-color { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
    .cat-info { flex: 1; min-width: 0; }
    .cat-name  { font-size: 13px; font-weight: 600; color: var(--text-main); }

    .mini-progress {
        height: 4px; background: var(--border); border-radius: 99px;
        overflow: hidden; margin-top: 4px;
    }
    .mini-fill { height: 100%; border-radius: 99px; }

    .cat-nums { font-size: 12px; color: var(--text-muted); white-space: nowrap; }

    /* ===== EMPTY ===== */
    .empty-sm {
        text-align: center; padding: 24px; color: var(--text-muted); font-size: 13px;
    }
</style>
@endpush

@section('content')

@php
    $hour = now('asia/Jakarta')->hour;
    $greeting = match(true) {
        $hour < 11 => 'Selamat Pagi',
        $hour < 15 => 'Selamat Siang',
        $hour < 18 => 'Selamat Sore',
        default    => 'Selamat Malam',
    };
    $emoji = match(true) {
        $hour < 11 => '🌅',
        $hour < 15 => '☀️',
        $hour < 18 => '🌤️',
        default    => '🌙',
    };
@endphp

{{-- GREETING --}}
<div class="greeting">
    <div class="greeting-text">
        <h2>{{ $greeting }}, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h2>
        <p>
            @if($habitsCheckedToday->count() === $habits->count() && $habits->count() > 0)
                Semua habit hari ini sudah selesai! Luar biasa 🔥
            @elseif($overdue->count() > 0)
                Ada {{ $overdue->count() }} tugas yang sudah melewati deadline. Yuk diselesaikan!
            @elseif($todayTodos->count() > 0)
                Kamu punya {{ $todayTodos->count() }} tugas untuk hari ini. Semangat!
            @else
                Tidak ada tugas mendesak. Waktu yang baik untuk menambah habit baru!
            @endif
        </p>
    </div>
    <div class="greeting-emoji">{{ $emoji }}</div>
</div>

{{-- STAT CARDS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple">
            <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
        </div>
        <div class="stat-value">{{ $allTodos->count() }}</div>
        <div class="stat-label">Total Tugas</div>
        <div class="stat-change good">{{ $completionPct }}% selesai</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-value">{{ $doneTodos->count() }}</div>
        <div class="stat-label">Tugas Selesai</div>
        <div class="stat-change good">Kerja bagus!</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="stat-value">{{ $pending->count() }}</div>
        <div class="stat-label">Belum Selesai</div>
        @if($overdue->count() > 0)
            <div class="stat-change bad">{{ $overdue->count() }} terlambat!</div>
        @else
            <div class="stat-change good">Tepat waktu 👍</div>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-icon fire">🔥</div>
        <div class="stat-value">{{ $bestStreak }}</div>
        <div class="stat-label">Streak Terbaik</div>
        <div class="stat-change good">Hari berturut-turut</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red">
            <svg viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div class="stat-value">{{ $habitsCheckedToday->count() }}/{{ $habits->count() }}</div>
        <div class="stat-label">Habit Hari Ini</div>
        @if($habits->count() > 0 && $habitsCheckedToday->count() === $habits->count())
            <div class="stat-change good">Semua selesai! 🎉</div>
        @else
            <div class="stat-change warn">{{ $habits->count() - $habitsCheckedToday->count() }} tersisa</div>
        @endif
    </div>
</div>

{{-- PROGRESS BAR --}}
<div class="prog-card">
    <div class="prog-header">
        <span class="prog-title">📊 Progress Keseluruhan Tugas</span>
        <span class="prog-pct">{{ $completionPct }}%</span>
    </div>
    <div class="prog-bar">
        <div class="prog-fill" id="progFill" style="width: 0%"></div>
    </div>
    <div class="prog-sub">
        <span>{{ $doneTodos->count() }} dari {{ $allTodos->count() }} tugas selesai</span>
        <span>{{ $pending->count() }} tersisa</span>
    </div>
</div>

{{-- CHARTS --}}
<div class="charts-grid">
    {{-- Chart: Todo per hari --}}
    <div class="chart-card">
        <div class="chart-header">
            <span class="chart-title">
                <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                Tugas Selesai
            </span>
            <span class="chart-subtitle">7 hari terakhir</span>
        </div>
        <div class="chart-wrap">
            <canvas id="todoChart"></canvas>
        </div>
    </div>

    {{-- Chart: Habit check-in per hari --}}
    <div class="chart-card">
        <div class="chart-header">
            <span class="chart-title">
                <svg viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Habit Check-In
            </span>
            <span class="chart-subtitle">7 hari terakhir</span>
        </div>
        <div class="chart-wrap">
            <canvas id="habitChart"></canvas>
        </div>
    </div>
</div>

{{-- BOTTOM 3 COLUMNS --}}
<div class="bottom-cols">

    {{-- TUGAS HARI INI --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Tugas Aktif
            </span>
            <a href="{{ route('todos.index') }}" class="card-link">Semua →</a>
        </div>

        @forelse($todayTodos->take(6) as $todo)
            @php
                $isOverdue = !$todo->is_done && $todo->deadline && $todo->deadline->isPast();
                $isSoon    = !$todo->is_done && $todo->deadline && $todo->deadline->isToday();
            @endphp
            <div class="todo-item">
                <form method="POST" action="{{ route('todos.toggle', $todo->todos_id) }}" style="flex-shrink:0">
                    @csrf @method('PATCH')
                    <button type="submit" class="todo-check {{ $todo->is_done ? 'done' : '' }}" title="Toggle">
                        @if($todo->is_done)
                            <svg viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        @endif
                    </button>
                </form>
                <div style="flex:1; min-width:0;">
                    <div class="todo-title {{ $todo->is_done ? 'done' : '' }}">{{ Str::limit($todo->title, 36) }}</div>
                    <div style="display:flex; gap:6px; align-items:center; margin-top:3px; flex-wrap:wrap;">
                        @if($todo->category)
                            <span class="cat-badge" style="background:{{ $todo->category->color }}">{{ $todo->category->name }}</span>
                        @endif
                        @if($todo->deadline)
                            <span class="deadline-tag {{ $isOverdue ? 'overdue' : ($isSoon ? 'soon' : '') }}">
                                <svg viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                {{ $isOverdue ? 'Terlambat' : 'Hari ini' }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-sm">🎉 Tidak ada tugas untuk hari ini</div>
        @endforelse
    </div>

    {{-- HABITS --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <svg viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Habit Tracker
            </span>
            <a href="{{ route('habits.index') }}" class="card-link">Kelola →</a>
        </div>

        @forelse($habits->take(6) as $habit)
            @php $checked = $habit->is_checked_today; @endphp
            <div class="habit-item">
                <div class="habit-info">
                    <div class="habit-name">{{ Str::limit($habit->name, 28) }}</div>
                    <div class="habit-freq">{{ $habit->frequency === 'daily' ? 'Harian' : 'Mingguan' }}</div>
                </div>
                @if($habit->streak_count > 0)
                    <span class="streak-pill">🔥 {{ $habit->streak_count }}</span>
                @endif
                <form method="POST" action="{{ route('habits.check', $habit->habits_id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="check-sm {{ $checked ? 'done' : '' }}"
                            {{ $checked ? 'disabled' : '' }} title="{{ $checked ? 'Sudah selesai!' : 'Tandai' }}">
                        <svg viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    </button>
                </form>
            </div>
        @empty
            <div class="empty-sm">⚡ Belum ada habit. <a href="{{ route('habits.index') }}" style="color:var(--primary)"></a></div>
        @endforelse
    </div>

    {{-- NOTIFIKASI --}}
    <div class="card notif-col">
        <div class="card-header">
            <span class="card-title">
                <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                Notifikasi
                @if($unreadCount > 0)
                    <span style="background:var(--danger);color:white;font-size:10px;font-weight:700;padding:2px 6px;border-radius:99px;">{{ $unreadCount }}</span>
                @endif
            </span>
            <a href="{{ route('notifications.index') }}" class="card-link">Semua →</a>
        </div>

        @php
            if (Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                $latestNotifs = auth()->user()->notifications()->orderByDesc('created_at')->take(5)->get();
            } else {
                $latestNotifs = collect();
            }
        @endphp

        @forelse($latestNotifs as $notif)
            <div class="notif-item {{ !$notif->is_read ? 'unread' : '' }}">
                <div class="notif-icon">{{ $notif->icon }}</div>
                <div class="notif-body">
                    <div class="notif-title">{{ $notif->title }}</div>
                    <div class="notif-msg">{{ Str::limit($notif->message, 60) }}</div>
                    <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                </div>
                <form method="POST" action="{{ route('notifications.destroy', $notif->notifications_id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="notif-del" title="Hapus">
                        <svg viewBox="0 0 24 24" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </form>
            </div>
        @empty
            <div class="notif-empty">🔔 Tidak ada notifikasi</div>
        @endforelse
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// Progress bar animasi
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.getElementById('progFill').style.width = '{{ $completionPct }}%';
    }, 300);
});

const chartDefaults = {
    font: { family: "'Plus Jakarta Sans', 'Segoe UI', sans-serif", size: 12 },
    color: '#6b7280',
};
Chart.defaults.font = chartDefaults.font;
Chart.defaults.color = chartDefaults.color;

// ===== CHART 1: Todo per hari =====
new Chart(document.getElementById('todoChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($todoChartLabels) !!},
        datasets: [{
            label: 'Tugas Selesai',
            data: {!! json_encode($todoChartData) !!},
            backgroundColor: '#6366f122',
            borderColor: '#6366f1',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#111',
                bodyColor: '#6b7280',
                borderColor: '#e5e7eb',
                borderWidth: 1,
                padding: 10,
                callbacks: {
                    label: ctx => ` ${ctx.raw} tugas selesai`,
                }
            }
        },
        scales: {
            x: { grid: { display: false }, border: { display: false } },
            y: {
                beginAtZero: true,
                grid: { color: '#f3f4f6' },
                border: { display: false },
                ticks: { stepSize: 1, precision: 0 }
            }
        }
    }
});

// ===== CHART 2: Habit check-in per hari =====
new Chart(document.getElementById('habitChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($todoChartLabels) !!},
        datasets: [{
            label: 'Habit Selesai',
            data: {!! json_encode($habitChartData) !!},
            backgroundColor: '#10b98115',
            borderColor: '#10b981',
            borderWidth: 2.5,
            pointBackgroundColor: '#10b981',
            pointRadius: 4,
            pointHoverRadius: 6,
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#111',
                bodyColor: '#6b7280',
                borderColor: '#e5e7eb',
                borderWidth: 1,
                padding: 10,
                callbacks: {
                    label: ctx => ` ${ctx.raw} habit selesai`,
                }
            }
        },
        scales: {
            x: { grid: { display: false }, border: { display: false } },
            y: {
                beginAtZero: true,
                grid: { color: '#f3f4f6' },
                border: { display: false },
                ticks: { stepSize: 1, precision: 0 }
            }
        }
    }
});
</script>
@endpush
