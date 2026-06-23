@extends('layouts.app')
@section('title', 'Log – ' . $habit->name)
@section('page-title', 'Riwayat Habit')

@push('styles')
<style>
    /* ===== BACK LINK ===== */
    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--text-muted); font-size: 13px; font-weight: 600;
        text-decoration: none; margin-bottom: 20px;
        transition: color .15s;
    }
    .back-link svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; }
    .back-link:hover { color: var(--primary); }

    /* ===== HABIT HEADER ===== */
    .habit-header {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 24px 28px;
        margin-bottom: 24px;
        display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
    }

    .habit-icon-big {
        width: 60px; height: 60px; border-radius: 16px;
        background: var(--primary-light);
        display: flex; align-items: center; justify-content: center;
        font-size: 28px; flex-shrink: 0;
    }

    .habit-header-info { flex: 1; min-width: 0; }
    .habit-header-name { font-size: 22px; font-weight: 800; color: var(--text-main); margin-bottom: 6px; }
    .habit-header-meta { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }

    .freq-badge {
        font-size: 11px; font-weight: 700; padding: 3px 10px;
        border-radius: 99px; text-transform: uppercase; letter-spacing: .05em;
    }
    .freq-badge.daily  { background: #ede9fe; color: #6d28d9; }
    .freq-badge.weekly { background: #dbeafe; color: #1e40af; }

    .since-badge {
        font-size: 12px; color: var(--text-muted);
    }

    /* ===== STATS ROW ===== */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .stat-box {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 18px 20px;
        text-align: center;
    }

    .stat-box-val {
        font-size: 32px; font-weight: 800; color: var(--text-main); line-height: 1;
        margin-bottom: 6px;
    }
    .stat-box-val.fire  { color: #c2410c; }
    .stat-box-val.green { color: var(--success); }
    .stat-box-val.blue  { color: var(--primary); }

    .stat-box-label {
        font-size: 12px; color: var(--text-muted); font-weight: 600;
    }

    /* ===== HEATMAP ===== */
    .section-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius); margin-bottom: 20px; overflow: hidden;
    }

    .section-head {
        padding: 16px 20px; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .section-title {
        font-size: 14px; font-weight: 700; color: var(--text-main);
        display: flex; align-items: center; gap: 8px;
    }
    .section-title svg { width: 15px; height: 15px; stroke: var(--primary); fill: none; stroke-width: 2; }

    .heatmap-wrap { padding: 20px; overflow-x: auto; }

    .heatmap-grid {
        display: grid;
        grid-template-rows: repeat(7, 14px);
        grid-auto-flow: column;
        gap: 3px;
    }

    .heatmap-day-labels {
        display: grid;
        grid-template-rows: repeat(7, 14px);
        gap: 3px;
        margin-right: 8px;
        flex-shrink: 0;
    }
    .heatmap-day-label {
        font-size: 9px; color: var(--text-muted); font-weight: 600;
        display: flex; align-items: center; height: 14px;
    }

    .heatmap-cell {
        width: 14px; height: 14px;
        border-radius: 3px;
        background: var(--bg-page);
        border: 1px solid var(--border);
        cursor: default;
        transition: transform .1s;
        position: relative;
    }
    .heatmap-cell:hover { transform: scale(1.3); z-index: 10; }
    .heatmap-cell.done { background: var(--success); border-color: var(--success); }
    .heatmap-cell.today-cell { border-color: var(--primary); }

    .heatmap-container { display: flex; align-items: flex-start; }

    .heatmap-legend {
        display: flex; align-items: center; gap: 6px;
        margin-top: 12px; font-size: 11px; color: var(--text-muted);
    }
    .legend-cell {
        width: 12px; height: 12px; border-radius: 2px;
    }

    /* ===== STREAK TIMELINE ===== */
    .streak-bar-wrap { padding: 20px; }

    .week-row {
        display: flex; align-items: center; gap: 8px; margin-bottom: 10px;
    }
    .week-label { font-size: 11px; color: var(--text-muted); width: 70px; flex-shrink: 0; text-align: right; }
    .week-days  { display: flex; gap: 4px; }

    .week-day-dot {
        width: 28px; height: 28px; border-radius: 50%;
        border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        font-size: 10px; font-weight: 600; color: var(--text-muted);
    }
    .week-day-dot.done    { background: var(--success); border-color: var(--success); color: white; }
    .week-day-dot.today   { border-color: var(--primary); color: var(--primary); font-weight: 800; }
    .week-day-dot.missed  { background: #fef2f2; border-color: #fecaca; color: #ef4444; }

    /* ===== LOG TABLE ===== */
    .log-table { width: 100%; border-collapse: collapse; }

    .log-table th {
        padding: 10px 20px; text-align: left;
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .05em; color: var(--text-muted);
        border-bottom: 1px solid var(--border);
        background: var(--bg-page);
    }

    .log-table td {
        padding: 12px 20px; font-size: 14px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .log-table tr:last-child td { border-bottom: none; }
    .log-table tr:hover td { background: var(--bg-page); }

    .log-date { font-weight: 600; color: var(--text-main); }
    .log-day  { font-size: 12px; color: var(--text-muted); }
    .log-note { font-size: 13px; color: var(--text-muted); font-style: italic; }

    .delete-log-btn {
        background: none; border: 1px solid var(--border);
        width: 28px; height: 28px; border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .15s;
    }
    .delete-log-btn svg { width: 12px; height: 12px; stroke: var(--text-muted); fill: none; stroke-width: 2; }
    .delete-log-btn:hover { background: #fef2f2; border-color: var(--danger); }
    .delete-log-btn:hover svg { stroke: var(--danger); }

    /* ===== PAGINATION ===== */
    .pagi { display: flex; justify-content: center; padding: 16px; }

    /* ===== EMPTY ===== */
    .empty-log { text-align: center; padding: 48px 20px; color: var(--text-muted); }
    .empty-log svg { width: 48px; height: 48px; stroke: var(--border); fill: none; margin-bottom: 12px; stroke-width: 1.5; }

    /* ===== CHECK FORM (sticky top) ===== */
    .check-banner {
        background: linear-gradient(135deg, var(--primary) 0%, #818cf8 100%);
        border-radius: var(--radius); padding: 20px 24px;
        margin-bottom: 24px;
        display: flex; align-items: center; justify-content: space-between; gap: 16px;
        flex-wrap: wrap;
    }
    .check-banner-text { color: white; }
    .check-banner-text h3 { font-size: 16px; font-weight: 700; margin-bottom: 4px; }
    .check-banner-text p  { font-size: 13px; opacity: .8; }

    .check-banner-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

    .btn-check-now {
        display: flex; align-items: center; gap: 6px;
        background: white; color: var(--primary);
        border: none; padding: 10px 20px; border-radius: var(--radius-sm);
        font-size: 14px; font-weight: 700; cursor: pointer;
        transition: opacity .15s;
    }
    .btn-check-now svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; }
    .btn-check-now:hover { opacity: .9; }
    .btn-check-now:disabled { opacity: .5; cursor: default; }

    .btn-uncheck {
        background: rgba(255,255,255,.2); color: white;
        border: 1px solid rgba(255,255,255,.4);
        padding: 10px 16px; border-radius: var(--radius-sm);
        font-size: 13px; font-weight: 600; cursor: pointer;
    }

    .note-input {
        padding: 9px 12px; border-radius: var(--radius-sm);
        border: 1px solid rgba(255,255,255,.4); background: rgba(255,255,255,.15);
        color: white; font-size: 13px; width: 200px;
        outline: none;
    }
    .note-input::placeholder { color: rgba(255,255,255,.6); }
    @media (max-width: 600px) { .note-input { width: 100%; } }
</style>
@endpush

@section('content')

<a href="{{ route('habits.index') }}" class="back-link">
    <svg viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    Kembali ke Habit Tracker
</a>

{{-- HABIT HEADER --}}
<div class="habit-header">
    <div class="habit-icon-big">⚡</div>
    <div class="habit-header-info">
        <div class="habit-header-name">{{ $habit->name }}</div>
        <div class="habit-header-meta">
            <span class="freq-badge {{ $habit->frequency }}">
                {{ $habit->frequency === 'daily' ? '🌅 Harian' : '📅 Mingguan' }}
            </span>
            <span class="since-badge">
                Dibuat {{ $habit->created_at->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>
</div>

{{-- CHECK BANNER --}}
@php $doneToday = $habit->isDoneToday(); @endphp

@if($doneToday)
    <div class="check-banner" style="background: linear-gradient(135deg, var(--success) 0%, #34d399 100%);">
        <div class="check-banner-text">
            <h3>✅ Selesai hari ini!</h3>
            <p>Kamu sudah menyelesaikan habit ini hari ini. Pertahankan!</p>
        </div>
        <div class="check-banner-actions">
            <form method="POST" action="{{ route('habits.uncheck', $habit->habits_id) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn-uncheck"
                        onclick="return confirm('Batalkan check hari ini?')">
                    Batalkan
                </button>
            </form>
        </div>
    </div>
@else
    <div class="check-banner">
        <div class="check-banner-text">
            <h3>Sudah selesai hari ini?</h3>
            <p>Tandai habit ini sebagai selesai untuk menjaga streak-mu!</p>
        </div>
        <form method="POST" action="{{ route('habits.check', $habit->habits_id) }}" class="check-banner-actions">
            @csrf @method('PATCH')
            <input type="text" name="note" class="note-input" placeholder="Tambah catatan (opsional)...">
            <button type="submit" class="btn-check-now">
                <svg viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                Tandai Selesai
            </button>
        </form>
    </div>
@endif

{{-- STATS ROW --}}
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-box-val fire">
            🔥 {{ $habit->streak_count }}
        </div>
        <div class="stat-box-label">Streak Sekarang</div>
    </div>

    <div class="stat-box">
        <div class="stat-box-val" style="color: #c2410c;">
            {{ $habit->longest_streak }}
        </div>
        <div class="stat-box-label">Streak Terpanjang</div>
    </div>

    <div class="stat-box">
        <div class="stat-box-val green">{{ $rate30 }}%</div>
        <div class="stat-box-label">Sukses 30 Hari</div>
    </div>

    <div class="stat-box">
        <div class="stat-box-val blue">{{ $rate7 }}%</div>
        <div class="stat-box-label">Sukses 7 Hari</div>
    </div>

    <div class="stat-box">
        <div class="stat-box-val">{{ $logs->total() }}</div>
        <div class="stat-box-label">Total Log</div>
    </div>
</div>

{{-- HEATMAP 12 MINGGU --}}
<div class="section-card">
    <div class="section-head">
        <span class="section-title">
            <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/></svg>
            Heatmap Aktivitas (12 Minggu Terakhir)
        </span>
        <span style="font-size: 12px; color: var(--text-muted);" id="heatmap-hover-label">Arahkan ke kotak untuk melihat tanggal</span>
    </div>
    <div class="heatmap-wrap">
        <div class="heatmap-container">
            <div class="heatmap-day-labels">
                <div class="heatmap-day-label">Min</div>
                <div class="heatmap-day-label">Sen</div>
                <div class="heatmap-day-label">Sel</div>
                <div class="heatmap-day-label">Rab</div>
                <div class="heatmap-day-label">Kam</div>
                <div class="heatmap-day-label">Jum</div>
                <div class="heatmap-day-label">Sab</div>
            </div>
            <div class="heatmap-grid" id="heatmap"></div>
        </div>
        <div class="heatmap-legend">
            <span>Kurang</span>
            <div class="legend-cell" style="background: var(--bg-page); border: 1px solid var(--border);"></div>
            <div class="legend-cell" style="background: #bbf7d0;"></div>
            <div class="legend-cell" style="background: #4ade80;"></div>
            <div class="legend-cell" style="background: var(--success);"></div>
            <span>Lebih banyak</span>
        </div>
    </div>
</div>

{{-- KALENDER MINGGUAN (4 minggu terakhir) --}}
<div class="section-card">
    <div class="section-head">
        <span class="section-title">
            <svg viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Detail 4 Minggu Terakhir
        </span>
    </div>
    <div class="streak-bar-wrap">
        @php
            $dayNames  = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
            $doneDates = collect($heatmapData);
        @endphp

        @for($w = 3; $w >= 0; $w--)
            @php
                $weekStart = now()->subWeeks($w)->startOfWeek(Carbon\Carbon::SUNDAY);
                $weekLabel = $weekStart->format('d M');
            @endphp
            <div class="week-row">
                <span class="week-label">{{ $weekLabel }}</span>
                <div class="week-days">
                    @for($d = 0; $d < 7; $d++)
                        @php
                            $day      = $weekStart->copy()->addDays($d);
                            $dateStr  = $day->toDateString();
                            $isFuture = $day->isFuture();
                            $isToday  = $day->isToday();
                            $isDone   = $doneDates->contains($dateStr);
                            $isMissed = !$isDone && !$isFuture && !$isToday && $day->gte(now()->subWeeks(4));
                        @endphp
                        <div class="week-day-dot
                            {{ $isDone   ? 'done'   : '' }}
                            {{ $isToday  ? 'today'  : '' }}
                            {{ ($isMissed && !$isToday) ? 'missed' : '' }}"
                             title="{{ $day->translatedFormat('l, d F Y') }}">
                            {{ $day->format('d') }}
                        </div>
                    @endfor
                </div>
            </div>
        @endfor

        <div style="display: flex; gap: 16px; margin-top: 12px; flex-wrap: wrap;">
            <span style="font-size: 11px; color: var(--text-muted); display:flex; align-items:center; gap:5px;">
                <span style="width:12px;height:12px;border-radius:50%;background:var(--success);display:inline-block;"></span> Selesai
            </span>
            <span style="font-size: 11px; color: var(--text-muted); display:flex; align-items:center; gap:5px;">
                <span style="width:12px;height:12px;border-radius:50%;background:#fef2f2;border:1px solid #fecaca;display:inline-block;"></span> Terlewat
            </span>
            <span style="font-size: 11px; color: var(--text-muted); display:flex; align-items:center; gap:5px;">
                <span style="width:12px;height:12px;border-radius:50%;border:1px solid var(--primary);display:inline-block;"></span> Hari ini
            </span>
        </div>
    </div>
</div>

{{-- LOG HISTORY TABLE --}}
<div class="section-card">
    <div class="section-head">
        <span class="section-title">
            <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
            Riwayat Log
        </span>
        <span style="font-size: 12px; color: var(--text-muted);">{{ $logs->total() }} entri</span>
    </div>

    @if($logs->isEmpty())
        <div class="empty-log">
            <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
            <p>Belum ada log</p>
        </div>
    @else
        <table class="log-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Hari</th>
                    <th>Catatan</th>
                    <th style="width:50px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>
                            <span class="log-date">{{ $log->completed_at->format('d M Y') }}</span>
                        </td>
                        <td>
                            <span class="log-day">{{ $log->completed_at->translatedFormat('l') }}</span>
                        </td>
                        <td>
                            @if($log->note)
                                <span class="log-note">{{ $log->note }}</span>
                            @else
                                <span style="color: var(--border);">—</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST"
                                  action="{{ route('habitlog.destroy', $log->habit_logs_id) }}"
                                  onsubmit="return confirm('Hapus log ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="delete-log-btn" title="Hapus log">
                                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- PAGINATION --}}
        @if($logs->hasPages())
            <div class="pagi">{{ $logs->links() }}</div>
        @endif
    @endif
</div>

@endsection

@push('scripts')
<script>
// ===== HEATMAP GENERATOR =====
const doneDates = @json($heatmapData);
const grid      = document.getElementById('heatmap');
const label     = document.getElementById('heatmap-hover-label');
const todayStr  = new Date().toISOString().split('T')[0];
const days      = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
const months    = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

// Mundur 84 hari (12 minggu), lalu align ke Minggu
const start = new Date();
start.setDate(start.getDate() - 83);
// Mundurkan ke hari Minggu paling dekat
while (start.getDay() !== 0) start.setDate(start.getDate() - 1);

const totalDays = Math.ceil((new Date() - start) / (1000 * 60 * 60 * 24)) + 1;

for (let i = 0; i < totalDays; i++) {
    const d    = new Date(start);
    d.setDate(start.getDate() + i);
    const dStr = d.toISOString().split('T')[0];

    const cell = document.createElement('div');
    cell.className = 'heatmap-cell';

    if (doneDates.includes(dStr)) cell.classList.add('done');
    if (dStr === todayStr) cell.classList.add('today-cell');

    const dayName = days[d.getDay()];
    const label2  = `${dayName}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
    cell.title    = label2;

    cell.addEventListener('mouseenter', () => {
        label.textContent = label2 + (doneDates.includes(dStr) ? ' ✅' : '');
    });
    cell.addEventListener('mouseleave', () => {
        label.textContent = 'Arahkan ke kotak untuk melihat tanggal';
    });

    grid.appendChild(cell);
}
</script>
@endpush
