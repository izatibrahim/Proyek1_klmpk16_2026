@extends('layouts.app')
@section('title', 'Statistik')
@section('page-title', 'Statistik')

@push('styles')
<style>
    /* ===== SECTION TITLE ===== */
    .section-title {
        font-size: 15px; font-weight: 700; color: var(--text-main);
        margin: 28px 0 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .section-title svg { width: 16px; height: 16px; stroke: var(--primary); fill: none; stroke-width: 2; }
    .section-title:first-child { margin-top: 0; }

    /* ===== OVERVIEW CARDS ===== */
    .overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px; margin-bottom: 8px;
    }

    .ov-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 20px 16px;
        text-align: center;
    }
    .ov-value { font-size: 30px; font-weight: 800; color: var(--text-main); line-height: 1; }
    .ov-label { font-size: 12px; color: var(--text-muted); font-weight: 500; margin-top: 6px; }
    .ov-sub   { font-size: 11px; margin-top: 5px; font-weight: 700; }
    .ov-sub.green  { color: var(--success); }
    .ov-sub.orange { color: var(--warning); }
    .ov-sub.purple { color: var(--primary); }

    /* ===== CHART CARDS ===== */
    .chart-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }
    .chart-row-equal {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }
    @media (max-width: 860px) {
        .chart-row, .chart-row-equal { grid-template-columns: 1fr; }
    }

    .chart-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 20px; overflow: hidden;
    }
    .chart-header {
        display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;
    }
    .chart-title {
        font-size: 14px; font-weight: 700; color: var(--text-main);
        display: flex; align-items: center; gap: 8px;
    }
    .chart-title svg { width: 15px; height: 15px; stroke: var(--primary); fill: none; stroke-width: 2; }
    .chart-sub { font-size: 12px; color: var(--text-muted); }

    .chart-wrap-tall   { position: relative; height: 240px; }
    .chart-wrap-medium { position: relative; height: 200px; }
    .chart-wrap-donut  { position: relative; height: 200px; display: flex; align-items: center; justify-content: center; }

    /* ===== HABIT TABLE ===== */
    .habit-table {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius); overflow: hidden; margin-bottom: 20px;
    }

    .ht-header {
        display: grid;
        grid-template-columns: 1fr 90px 90px 90px 100px;
        padding: 11px 18px;
        background: var(--bg-page);
        border-bottom: 1px solid var(--border);
        font-size: 11px; font-weight: 700; color: var(--text-muted);
        text-transform: uppercase; letter-spacing: .05em;
    }
    @media (max-width: 680px) {
        .ht-header { grid-template-columns: 1fr 80px 80px; }
        .ht-hide { display: none; }
    }

    .ht-row {
        display: grid;
        grid-template-columns: 1fr 90px 90px 90px 100px;
        padding: 13px 18px;
        border-bottom: 1px solid var(--border);
        align-items: center;
        transition: background .15s;
    }
    .ht-row:last-child { border-bottom: none; }
    .ht-row:hover { background: var(--bg-page); }
    @media (max-width: 680px) {
        .ht-row { grid-template-columns: 1fr 80px 80px; }
        .ht-row .ht-hide { display: none; }
    }

    .ht-name { font-size: 14px; font-weight: 600; color: var(--text-main); }
    .ht-freq { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

    .ht-val { font-size: 14px; font-weight: 700; text-align: center; }
    .ht-val.streak { color: #c2410c; }
    .ht-val.done   { color: var(--success); }
    .ht-val.rate   { color: var(--primary); }

    .mini-bar-wrap { height: 6px; background: var(--border); border-radius: 99px; overflow: hidden; margin-top: 4px; }
    .mini-bar-fill { height: 100%; background: var(--success); border-radius: 99px; }

    /* ===== CAT TABLE ===== */
    .cat-table {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius); overflow: hidden;
    }

    .ct-row {
        display: flex; align-items: center; gap: 14px;
        padding: 13px 18px; border-bottom: 1px solid var(--border);
    }
    .ct-row:last-child { border-bottom: none; }
    .ct-color { width: 12px; height: 12px; border-radius: 3px; flex-shrink: 0; }
    .ct-info  { flex: 1; min-width: 0; }
    .ct-name  { font-size: 14px; font-weight: 600; color: var(--text-main); }
    .ct-prog  { display: flex; align-items: center; gap: 8px; margin-top: 5px; }
    .ct-bar   { flex: 1; height: 6px; background: var(--border); border-radius: 99px; overflow: hidden; }
    .ct-fill  { height: 100%; border-radius: 99px; }
    .ct-pct   { font-size: 11px; color: var(--text-muted); white-space: nowrap; font-weight: 600; }
    .ct-nums  { font-size: 12px; color: var(--text-muted); white-space: nowrap; }
</style>
@endpush

@section('content')

{{-- ===== OVERVIEW ===== --}}
<div class="section-title">
    <svg viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
    Ringkasan
</div>

<div class="overview-grid">
    <div class="ov-card">
        <div class="ov-value">{{ $stats['total_todos'] }}</div>
        <div class="ov-label">Total Tugas</div>
        <div class="ov-sub purple">{{ $stats['completion_pct'] }}% selesai</div>
    </div>
    <div class="ov-card">
        <div class="ov-value">{{ $stats['done_todos'] }}</div>
        <div class="ov-label">Tugas Selesai</div>
        <div class="ov-sub green">Kerja bagus!</div>
    </div>
    <div class="ov-card">
        <div class="ov-value">{{ $stats['overdue_todos'] }}</div>
        <div class="ov-label">Terlambat</div>
        <div class="ov-sub {{ $stats['overdue_todos'] > 0 ? 'orange' : 'green' }}">
            {{ $stats['overdue_todos'] > 0 ? 'Perlu perhatian' : 'Oke!' }}
        </div>
    </div>
    <div class="ov-card">
        <div class="ov-value">{{ $stats['total_habits'] }}</div>
        <div class="ov-label">Total Habit</div>
        <div class="ov-sub purple">{{ $stats['daily_habits'] }} harian, {{ $stats['weekly_habits'] }} mingguan</div>
    </div>
    <div class="ov-card">
        <div class="ov-value">🔥{{ $stats['best_streak'] }}</div>
        <div class="ov-label">Streak Terbaik</div>
        <div class="ov-sub orange">Hari berturut-turut</div>
    </div>
    <div class="ov-card">
        <div class="ov-value">{{ $stats['total_log_days'] }}</div>
        <div class="ov-label">Total Check-In</div>
        <div class="ov-sub green">Semua habit</div>
    </div>
</div>

{{-- ===== CHARTS: Monthly + Doughnut ===== --}}
<div class="section-title">
    <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
    Aktivitas 30 Hari Terakhir
</div>

<div class="chart-row">
    <div class="chart-card">
        <div class="chart-header">
            <span class="chart-title">
                <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                Tugas & Habit per Hari
            </span>
            <span class="chart-sub">30 hari terakhir</span>
        </div>
        <div class="chart-wrap-tall">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <span class="chart-title">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                Status Tugas
            </span>
        </div>
        <div class="chart-wrap-donut">
            <canvas id="donutChart"></canvas>
        </div>
        <div style="display:flex; gap:16px; justify-content:center; margin-top:12px;">
            <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted);">
                <div style="width:12px;height:12px;border-radius:3px;background:#10b981;"></div>
                Selesai ({{ $stats['done_todos'] }})
            </div>
            <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted);">
                <div style="width:12px;height:12px;border-radius:3px;background:#6366f1;"></div>
                Pending ({{ $stats['pending_todos'] }})
            </div>
            @if($stats['overdue_todos'] > 0)
            <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted);">
                <div style="width:12px;height:12px;border-radius:3px;background:#ef4444;"></div>
                Terlambat ({{ $stats['overdue_todos'] }})
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ===== TABEL HABIT ===== --}}
<div class="section-title">
    <svg viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
    Detail Per Habit
</div>

<div class="habit-table">
    <div class="ht-header">
        <div>Nama Habit</div>
        <div style="text-align:center">🔥 Streak</div>
        <div style="text-align:center" class="ht-hide">⭐ Terbaik</div>
        <div style="text-align:center" class="ht-hide">✅ Total</div>
        <div style="text-align:center">Konsistensi</div>
    </div>

    @forelse($habitStats as $h)
        <div class="ht-row">
            <div>
                <div class="ht-name">{{ $h['name'] }}</div>
                <div class="ht-freq">{{ $h['frequency'] === 'daily' ? '🌅 Harian' : '📅 Mingguan' }}</div>
            </div>
            <div class="ht-val streak">{{ $h['streak'] }}🔥</div>
            <div class="ht-val done ht-hide">{{ $h['longest'] }}⭐</div>
            <div class="ht-val done ht-hide">{{ $h['total'] }}</div>
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--primary);text-align:center;">{{ $h['rate'] }}%</div>
                <div class="mini-bar-wrap">
                    <div class="mini-bar-fill" style="width:{{ $h['rate'] }}%"></div>
                </div>
            </div>
        </div>
    @empty
        <div style="text-align:center; padding:32px; color:var(--text-muted); font-size:14px;">
            Belum ada habit yang dibuat.
        </div>
    @endforelse
</div>

{{-- ===== TABEL KATEGORI ===== --}}
<div class="section-title">
    <svg viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
    Tugas per Kategori
</div>

<div class="cat-table">
    @forelse($categoryStats as $cat)
        @php
            $pct = $cat->todos_count > 0
                ? round($cat->done_count / $cat->todos_count * 100)
                : 0;
        @endphp
        <div class="ct-row">
            <div class="ct-color" style="background: {{ $cat->color }}"></div>
            <div class="ct-info">
                <div class="ct-name">{{ $cat->name }}</div>
                <div class="ct-prog">
                    <div class="ct-bar">
                        <div class="ct-fill" style="width:{{ $pct }}%; background:{{ $cat->color }}"></div>
                    </div>
                    <span class="ct-pct">{{ $pct }}%</span>
                </div>
            </div>
            <div class="ct-nums">{{ $cat->done_count }}/{{ $cat->todos_count }} selesai</div>
        </div>
    @empty
        <div style="text-align:center; padding:24px; color:var(--text-muted); font-size:14px;">
            Belum ada kategori.
        </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
Chart.defaults.font = { family: "'Plus Jakarta Sans','Segoe UI',sans-serif", size: 12 };
Chart.defaults.color = '#6b7280';

// ===== MONTHLY CHART =====
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($monthlyLabels) !!},
        datasets: [
            {
                label: 'Tugas Selesai',
                data: {!! json_encode($monthlyTodos) !!},
                backgroundColor: '#6366f133',
                borderColor: '#6366f1',
                borderWidth: 2,
                borderRadius: 4,
                order: 2,
            },
            {
                label: 'Habit Check-In',
                data: {!! json_encode($monthlyHabits) !!},
                type: 'line',
                borderColor: '#10b981',
                backgroundColor: '#10b98115',
                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: '#10b981',
                tension: 0.4,
                fill: true,
                order: 1,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: { usePointStyle: true, padding: 20, boxWidth: 8 }
            },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#111',
                bodyColor: '#6b7280',
                borderColor: '#e5e7eb',
                borderWidth: 1,
                padding: 12,
            }
        },
        scales: {
            x: { grid: { display: false }, border: { display: false }, ticks: { maxTicksLimit: 10 } },
            y: { beginAtZero: true, grid: { color: '#f3f4f6' }, border: { display: false }, ticks: { stepSize: 1, precision: 0 } }
        }
    }
});

// ===== DONUT CHART =====
new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: ['Selesai', 'Pending', 'Terlambat'],
        datasets: [{
            data: [
                {{ $stats['done_todos'] }},
                {{ $stats['pending_todos'] - $stats['overdue_todos'] }},
                {{ $stats['overdue_todos'] }}
            ],
            backgroundColor: ['#10b981', '#6366f1', '#ef4444'],
            borderWidth: 0,
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#111',
                bodyColor: '#6b7280',
                borderColor: '#e5e7eb',
                borderWidth: 1,
                padding: 10,
            }
        }
    }
});
</script>
@endpush
