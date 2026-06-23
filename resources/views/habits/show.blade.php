@extends('layouts.app')
@section('title', $habit->name . ' — Detail Habit')
@section('page-title', 'Detail Habit')

@push('styles')
<style>
    /* ===== BACK LINK ===== */
    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--text-muted); font-size: 13px; font-weight: 600;
        text-decoration: none; margin-bottom: 20px;
        transition: color .15s;
    }
    .back-link svg { width: 14px; height: 14px; stroke: currentColor; fill: none; }
    .back-link:hover { color: var(--primary); }

    /* ===== HERO ===== */
    .habit-hero {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 28px; margin-bottom: 24px;
        display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
    }

    .hero-icon {
        width: 64px; height: 64px; border-radius: 16px;
        background: var(--primary-light);
        display: flex; align-items: center; justify-content: center;
        font-size: 30px; flex-shrink: 0;
    }

    .hero-info { flex: 1; min-width: 200px; }
    .hero-name { font-size: 22px; font-weight: 800; color: var(--text-main); margin-bottom: 6px; }
    .hero-meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

    .freq-badge {
        font-size: 11px; font-weight: 700; padding: 3px 10px;
        border-radius: 99px; text-transform: uppercase; letter-spacing: .05em;
    }
    .freq-badge.daily  { background: #ede9fe; color: #6d28d9; }
    .freq-badge.weekly { background: #dbeafe; color: #1e40af; }

    .started-badge {
        font-size: 12px; color: var(--text-muted);
        display: flex; align-items: center; gap: 4px;
    }
    .started-badge svg { width: 13px; height: 13px; stroke: currentColor; fill: none; }

    .hero-actions { display: flex; gap: 10px; flex-wrap: wrap; }

    .btn-check {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--success); color: white;
        border: none; padding: 11px 20px; border-radius: var(--radius-sm);
        font-size: 14px; font-weight: 600; cursor: pointer; transition: all .15s;
    }
    .btn-check svg { width: 16px; height: 16px; stroke: white; fill: none; stroke-width: 2.5; }
    .btn-check:hover { opacity: .9; }
    .btn-check:disabled { background: #d1fae5; color: #6ee7b7; cursor: not-allowed; }

    .btn-uncheck {
        display: inline-flex; align-items: center; gap: 8px;
        background: none; color: var(--danger);
        border: 1px solid var(--danger); padding: 10px 18px; border-radius: var(--radius-sm);
        font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s;
    }
    .btn-uncheck svg { width: 14px; height: 14px; stroke: currentColor; fill: none; }
    .btn-uncheck:hover { background: #fef2f2; }

    .btn-edit {
        display: inline-flex; align-items: center; gap: 6px;
        background: none; color: var(--text-muted);
        border: 1px solid var(--border); padding: 10px 16px; border-radius: var(--radius-sm);
        font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s;
    }
    .btn-edit svg { width: 14px; height: 14px; stroke: currentColor; fill: none; }
    .btn-edit:hover { border-color: var(--primary); color: var(--primary); }

    /* ===== STAT CARDS ===== */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 14px; margin-bottom: 24px;
    }

    .stat-box {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 18px 16px; text-align: center;
    }

    .stat-value { font-size: 26px; font-weight: 800; color: var(--text-main); line-height: 1; margin-bottom: 4px; }
    .stat-value.fire { color: #c2410c; }
    .stat-value.blue { color: var(--primary); }
    .stat-value.green { color: var(--success); }

    .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; }

    /* ===== RATE BAR ===== */
    .rate-bar-wrap {
        margin-top: 8px; height: 4px;
        background: var(--border); border-radius: 99px; overflow: hidden;
    }
    .rate-bar-fill { height: 100%; border-radius: 99px; background: var(--success); }

    /* ===== TWO COLUMNS ===== */
    .detail-cols {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px; align-items: start;
    }
    @media (max-width: 900px) {
        .detail-cols { grid-template-columns: 1fr; }
    }

    /* ===== CALENDAR ===== */
    .card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius); overflow: hidden; margin-bottom: 20px;
    }

    .card-header {
        padding: 14px 20px; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .card-title {
        font-size: 14px; font-weight: 700; color: var(--text-main);
        display: flex; align-items: center; gap: 8px;
    }
    .card-title svg { width: 15px; height: 15px; stroke: var(--primary); fill: none; stroke-width: 2; }

    .cal-nav { display: flex; align-items: center; gap: 8px; }
    .cal-month { font-size: 14px; font-weight: 700; color: var(--text-main); }
    .nav-btn {
        width: 28px; height: 28px; border-radius: var(--radius-sm);
        border: 1px solid var(--border); background: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; transition: all .15s;
    }
    .nav-btn svg { width: 14px; height: 14px; stroke: var(--text-muted); fill: none; }
    .nav-btn:hover:not(:disabled) { border-color: var(--primary); }
    .nav-btn:hover:not(:disabled) svg { stroke: var(--primary); }
    .nav-btn:disabled { opacity: .3; cursor: not-allowed; }

    .calendar { padding: 16px 20px; }

    .cal-weekdays {
        display: grid; grid-template-columns: repeat(7, 1fr);
        margin-bottom: 8px;
    }
    .cal-weekday {
        text-align: center; font-size: 11px; font-weight: 700;
        color: var(--text-muted); padding: 4px 0; text-transform: uppercase; letter-spacing: .05em;
    }

    .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }

    .cal-cell {
        aspect-ratio: 1; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 500; color: var(--text-muted);
        cursor: default; position: relative;
    }
    .cal-cell.empty { }
    .cal-cell.done {
        background: var(--success); color: white; font-weight: 700;
    }
    .cal-cell.today {
        border: 2px solid var(--primary); color: var(--primary); font-weight: 800;
    }
    .cal-cell.today.done { background: var(--primary); border-color: var(--primary); color: white; }
    .cal-cell.other-month { color: var(--border); }

    /* ===== LOG LIST ===== */
    .log-item {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 12px 20px; border-bottom: 1px solid var(--border);
    }
    .log-item:last-child { border-bottom: none; }

    .log-dot {
        width: 10px; height: 10px; border-radius: 50%;
        background: var(--success); flex-shrink: 0; margin-top: 5px;
    }

    .log-date { font-size: 14px; font-weight: 600; color: var(--text-main); }
    .log-note { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
    .log-ago  { font-size: 12px; color: var(--text-muted); margin-left: auto; white-space: nowrap; }

    .log-del {
        width: 26px; height: 26px; background: none;
        border: 1px solid var(--border); border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .15s; flex-shrink: 0;
    }
    .log-del svg { width: 12px; height: 12px; stroke: var(--text-muted); fill: none; stroke-width: 2; }
    .log-del:hover { background: #fef2f2; border-color: var(--danger); }
    .log-del:hover svg { stroke: var(--danger); }

    /* ===== MODAL EDIT ===== */
    .modal-bg {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.45); z-index: 200;
        align-items: center; justify-content: center;
    }
    .modal-bg.open { display: flex; }
    .modal {
        background: var(--bg-card); border-radius: var(--radius);
        padding: 28px; width: 100%; max-width: 400px; margin: 16px;
    }
    .modal-title { font-size: 16px; font-weight: 700; margin-bottom: 20px; color: var(--text-main); }
    .form-group { margin-bottom: 14px; }
    .form-group label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 6px; }
    .form-input, .form-select {
        width: 100%; padding: 10px 12px; border: 1px solid var(--border);
        border-radius: var(--radius-sm); font-size: 14px; color: var(--text-main);
        background: var(--bg-page); outline: none; font-family: inherit;
    }
    .form-input:focus, .form-select:focus { border-color: var(--primary); background: white; }
    .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
    .btn-ghost {
        padding: 9px 16px; background: none; border: 1px solid var(--border);
        border-radius: var(--radius-sm); font-size: 14px; color: var(--text-muted); cursor: pointer;
    }
    .btn-ghost:hover { background: var(--bg-page); }
    .btn-primary {
        padding: 9px 20px; background: var(--primary); color: white;
        border: none; border-radius: var(--radius-sm); font-size: 14px; font-weight: 600; cursor: pointer;
    }
    .btn-primary:hover { background: var(--primary-dark); }

    /* check-in note form */
    .checkin-form {
        background: #f0fdf4; border: 1px solid #bbf7d0;
        border-radius: var(--radius-sm); padding: 14px 16px; margin-top: 10px;
    }
    .checkin-note {
        width: 100%; padding: 8px 12px;
        border: 1px solid #bbf7d0; border-radius: var(--radius-sm);
        font-size: 13px; background: white; outline: none; font-family: inherit;
        margin-bottom: 8px;
    }
    .checkin-note:focus { border-color: var(--success); }
</style>
@endpush

@section('content')

<a href="{{ route('habits.index') }}" class="back-link">
    <svg viewBox="0 0 24 24" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    Kembali ke Habit Tracker
</a>

@php $isCheckedToday = $habit->is_checked_today; @endphp

{{-- HERO --}}
<div class="habit-hero">
    <div class="hero-icon">{{ $isCheckedToday ? '✅' : '⚡' }}</div>
    <div class="hero-info">
        <div class="hero-name">{{ $habit->name }}</div>
        <div class="hero-meta">
            <span class="freq-badge {{ $habit->frequency }}">
                {{ $habit->frequency === 'daily' ? '🌅 Harian' : '📅 Mingguan' }}
            </span>
            @if($habit->started_at)
                <span class="started-badge">
                    <svg viewBox="0 0 24 24" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Dimulai {{ $habit->started_at->translatedFormat('d M Y') }}
                </span>
            @endif
        </div>
    </div>
    <div class="hero-actions">
        @if(!$isCheckedToday)
            {{-- Tombol check in dengan note --}}
            <button class="btn-check" onclick="document.getElementById('checkinForm').classList.toggle('hidden')">
                <svg viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                Tandai Selesai
            </button>
        @else
            <span style="font-size:14px; color: var(--success); font-weight:700; display:flex; align-items:center; gap:6px;">
                <svg style="width:18px;height:18px;stroke:var(--success);fill:none;stroke-width:2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Selesai hari ini!
            </span>
            <form method="POST" action="{{ route('habits.uncheck', $habit->habits_id) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn-uncheck">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    Batalkan
                </button>
            </form>
        @endif
        <button class="btn-edit" onclick="document.getElementById('editModal').classList.add('open')">
            <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit
        </button>
    </div>
</div>

{{-- FORM CHECK-IN dengan note --}}
@if(!$isCheckedToday)
<div id="checkinForm" class="hidden" style="margin-bottom: 24px;">
    <form method="POST" action="{{ route('habits.check', $habit->habits_id) }}">
        @csrf @method('PATCH')
        <div class="checkin-form">
            <p style="font-size:13px; font-weight:600; color:#065f46; margin-bottom:8px;">
                ✅ Tambahkan catatan hari ini (opsional)
            </p>
            <input type="text" name="note" class="checkin-note"
                   placeholder="Bagaimana rasanya hari ini? cth. Sudah 20 menit lari...">
            <button type="submit" class="btn-primary" style="width:100%; padding:10px;">
                Simpan Check-In
            </button>
        </div>
    </form>
</div>
@endif

{{-- STAT CARDS --}}
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-value fire">{{ $stats['current_streak'] }}🔥</div>
        <div class="stat-label">Streak Sekarang</div>
        <div class="rate-bar-wrap" style="margin-top:8px;">
            <div class="rate-bar-fill" style="width:{{ min(100, $stats['current_streak'] * 5) }}%; background: #f97316;"></div>
        </div>
    </div>

    <div class="stat-box">
        <div class="stat-value fire">{{ $stats['longest_streak'] }}⭐</div>
        <div class="stat-label">Streak Terpanjang</div>
    </div>

    <div class="stat-box">
        <div class="stat-value green">{{ $stats['total_done'] }}</div>
        <div class="stat-label">Total Hari Selesai</div>
    </div>

    <div class="stat-box">
        <div class="stat-value blue">{{ $stats['rate_30'] }}%</div>
        <div class="stat-label">Konsistensi 30 Hari</div>
        <div class="rate-bar-wrap" style="margin-top:8px;">
            <div class="rate-bar-fill" style="width:{{ $stats['rate_30'] }}%;"></div>
        </div>
    </div>

    <div class="stat-box">
        <div class="stat-value blue">{{ $stats['rate_7'] }}%</div>
        <div class="stat-label">Konsistensi 7 Hari</div>
        <div class="rate-bar-wrap" style="margin-top:8px;">
            <div class="rate-bar-fill" style="width:{{ $stats['rate_7'] }}%;"></div>
        </div>
    </div>

    <div class="stat-box">
        <div class="stat-value" style="color: var(--text-muted);">{{ $stats['days_since_start'] }}</div>
        <div class="stat-label">Hari Sejak Mulai</div>
    </div>
</div>

{{-- DETAIL COLUMNS --}}
<div class="detail-cols">

    {{-- KALENDER BULANAN --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Kalender Aktivitas
            </span>
            <div class="cal-nav">
                <a href="{{ route('habits.show', $habit->habits_id) }}?month={{ $prevMonth->month }}&year={{ $prevMonth->year }}" class="nav-btn" title="Bulan sebelumnya">
                    <svg viewBox="0 0 24 24" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                </a>
                <span class="cal-month">{{ $carbonDate->translatedFormat('F Y') }}</span>
                @if($canGoNext)
                    <a href="{{ route('habits.show', $habit->habits_id) }}?month={{ $nextMonth->month }}&year={{ $nextMonth->year }}" class="nav-btn" title="Bulan berikutnya">
                        <svg viewBox="0 0 24 24" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                    </a>
                @else
                    <button class="nav-btn" disabled>
                        <svg viewBox="0 0 24 24" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                @endif
            </div>
        </div>

        <div class="calendar">
            {{-- Nama hari --}}
            <div class="cal-weekdays">
                @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $d)
                    <div class="cal-weekday">{{ $d }}</div>
                @endforeach
            </div>

            @php
                $firstDay    = $carbonDate->copy()->startOfMonth();
                $lastDay     = $carbonDate->copy()->endOfMonth();
                $startOffset = $firstDay->dayOfWeek; // 0=Minggu, 6=Sabtu
                $daysInMonth = $carbonDate->daysInMonth;
                $todayDay    = now()->month === $carbonDate->month && now()->year === $carbonDate->year ? now()->day : -1;
            @endphp

            <div class="cal-grid">
                {{-- Offset awal --}}
                @for($i = 0; $i < $startOffset; $i++)
                    <div class="cal-cell empty"></div>
                @endfor

                {{-- Tanggal --}}
                @for($d = 1; $d <= $daysInMonth; $d++)
                    @php
                        $done    = in_array($d, $monthLogs);
                        $isToday = $d === $todayDay;
                    @endphp
                    <div class="cal-cell {{ $done ? 'done' : '' }} {{ $isToday ? 'today' : '' }}" title="{{ $done ? '✅ Selesai' : '' }}">
                        {{ $d }}
                    </div>
                @endfor
            </div>

            {{-- Legenda --}}
            <div style="display:flex; gap:16px; margin-top:16px; font-size:12px; color:var(--text-muted);">
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:14px;height:14px;border-radius:50%;background:var(--success);"></div>
                    Selesai
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:14px;height:14px;border-radius:50%;border:2px solid var(--primary);"></div>
                    Hari ini
                </div>
            </div>
        </div>
    </div>

    {{-- RIWAYAT LOG --}}
    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">
                    <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                    Riwayat Log
                </span>
                <span style="font-size:12px;color:var(--text-muted);">{{ $recentLogs->count() }} terbaru</span>
            </div>

            @forelse($recentLogs as $log)
                <div class="log-item">
                    <div class="log-dot"></div>
                    <div style="flex:1; min-width:0;">
                        <div class="log-date">
                            {{ Carbon\Carbon::parse($log->completed_at)->translatedFormat('l, d M Y') }}
                        </div>
                        @if($log->note)
                            <div class="log-note">📝 {{ $log->note }}</div>
                        @endif
                    </div>
                    <span class="log-ago">{{ Carbon\Carbon::parse($log->completed_at)->diffForHumans() }}</span>
                    <form method="POST" action="{{ route('habits.logs.destroy', [$habit->habits_id, $log->habit_logs_id]) }}"
                          onsubmit="return confirm('Hapus log ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="log-del" title="Hapus log">
                            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                        </button>
                    </form>
                </div>
            @empty
                <div style="text-align:center; padding:32px 20px; color:var(--text-muted); font-size:14px;">
                    Belum ada log tercatat.
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal-bg" id="editModal">
    <div class="modal">
        <div class="modal-title">Edit Habit</div>
        <form method="POST" action="{{ route('habits.update', $habit->habits_id) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Nama Habit</label>
                <input type="text" name="name" class="form-input" value="{{ $habit->name }}" required>
            </div>
            <div class="form-group">
                <label>Frekuensi</label>
                <select name="frequency" class="form-select">
                    <option value="daily"  {{ $habit->frequency === 'daily'  ? 'selected' : '' }}>🌅 Harian</option>
                    <option value="weekly" {{ $habit->frequency === 'weekly' ? 'selected' : '' }}>📅 Mingguan</option>
                </select>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-ghost" onclick="document.getElementById('editModal').classList.remove('open')">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle form check-in
document.addEventListener('click', function(e) {
    if (e.target.closest('.modal-bg') === document.getElementById('editModal') &&
        !e.target.closest('.modal')) {
        document.getElementById('editModal').classList.remove('open');
    }
});
</script>
@endpush
