@extends('layouts.app')
@section('title', 'Habit Tracker')
@section('page-title', 'Habit Tracker')

@push('styles')
<style>
    /* ===== LAYOUT ===== */
    .habits-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 900px) {
        .habits-layout { grid-template-columns: 1fr; }
    }

    /* ===== FORM ===== */
    .form-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        position: sticky;
        top: 88px;
    }

    .form-title {
        font-size: 15px; font-weight: 700; color: var(--text-main);
        margin-bottom: 18px;
        display: flex; align-items: center; gap: 8px;
    }
    .form-title svg { width: 16px; height: 16px; stroke: var(--primary); fill: none; stroke-width: 2; }

    .form-group { margin-bottom: 14px; }
    .form-group label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 6px; }

    .form-input, .form-select {
        width: 100%; padding: 10px 12px;
        border: 1px solid var(--border); border-radius: var(--radius-sm);
        font-size: 14px; color: var(--text-main);
        background: var(--bg-page); outline: none;
        transition: border-color .15s; font-family: inherit;
    }
    .form-input:focus, .form-select:focus { border-color: var(--primary); background: white; }

    .freq-options { display: flex; gap: 10px; }

    .freq-btn {
        flex: 1; padding: 10px; border-radius: var(--radius-sm);
        border: 2px solid var(--border); background: var(--bg-page);
        font-size: 13px; font-weight: 600; color: var(--text-muted);
        cursor: pointer; text-align: center; transition: all .15s;
    }
    .freq-btn:hover { border-color: var(--primary); color: var(--primary); }
    .freq-btn.selected { border-color: var(--primary); background: var(--primary-light); color: var(--primary); }

    .btn-primary {
        width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px;
        background: var(--primary); color: white;
        border: none; padding: 12px; border-radius: var(--radius-sm);
        font-size: 14px; font-weight: 600; cursor: pointer; transition: background .15s;
    }
    .btn-primary svg { width: 16px; height: 16px; stroke: white; fill: none; stroke-width: 2; }
    .btn-primary:hover { background: var(--primary-dark); }

    /* ===== HABIT CARDS ===== */
    .habit-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        margin-bottom: 14px;
        overflow: hidden;
        transition: border-color .2s;
    }
    .habit-card:hover { border-color: var(--primary); }
    .habit-card.done-today { border-left: 4px solid var(--success); }

    .habit-card-top {
        display: flex; align-items: center; gap: 14px; padding: 18px 20px;
    }

    .habit-icon {
        width: 46px; height: 46px;
        background: var(--primary-light);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .habit-icon.done { background: #d1fae5; }

    .habit-info { flex: 1; min-width: 0; }

    .habit-name { font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 4px; }

    .habit-meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

    .freq-badge {
        font-size: 11px; font-weight: 700; padding: 3px 10px;
        border-radius: 99px; text-transform: uppercase; letter-spacing: .05em;
    }
    .freq-badge.daily   { background: #ede9fe; color: #6d28d9; }
    .freq-badge.weekly  { background: #dbeafe; color: #1e40af; }

    .streak-display {
        display: flex; align-items: center; gap: 5px;
        font-size: 13px; font-weight: 700; color: #c2410c;
    }
    .streak-display svg { width: 15px; height: 15px; }

    .habit-right { display: flex; align-items: center; gap: 10px; }

    .check-big {
        width: 44px; height: 44px;
        border-radius: 50%;
        border: 2px solid var(--border);
        background: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all .2s;
        flex-shrink: 0;
    }
    .check-big svg { width: 20px; height: 20px; stroke: var(--text-muted); fill: none; stroke-width: 2; }
    .check-big:hover { border-color: var(--success); background: #f0fdf4; }
    .check-big:hover svg { stroke: var(--success); }
    .check-big.done { background: var(--success); border-color: var(--success); }
    .check-big.done svg { stroke: white; }

    .btn-icon {
        width: 34px; height: 34px;
        background: none; border: 1px solid var(--border);
        border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .15s;
    }
    .btn-icon svg { width: 14px; height: 14px; stroke: var(--text-muted); fill: none; stroke-width: 2; }
    .btn-icon.del:hover { background: #fef2f2; border-color: var(--danger); }
    .btn-icon.del:hover svg { stroke: var(--danger); }

    /* ===== MINI CALENDAR (7 hari terakhir) ===== */
    .habit-calendar {
        border-top: 1px solid var(--border);
        padding: 12px 20px 14px;
        background: var(--bg-page);
    }

    .cal-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--text-muted); margin-bottom: 8px; }

    .cal-dots { display: flex; gap: 8px; align-items: center; }

    .cal-day {
        display: flex; flex-direction: column; align-items: center; gap: 4px;
    }

    .cal-day-name { font-size: 10px; color: var(--text-muted); font-weight: 600; }

    .cal-dot {
        width: 28px; height: 28px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 600;
        border: 1px solid var(--border);
    }
    .cal-dot.filled { background: var(--success); border-color: var(--success); color: white; }
    .cal-dot.today  { border-color: var(--primary); color: var(--primary); font-weight: 800; }
    .cal-dot.today.filled { background: var(--primary); border-color: var(--primary); color: white; }

    /* ===== EMPTY ===== */
    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
    .empty-state svg { width: 56px; height: 56px; stroke: var(--border); fill: none; margin-bottom: 16px; stroke-width: 1.5; }
    .empty-state p { font-size: 16px; font-weight: 600; margin-bottom: 6px; }
    .empty-state small { font-size: 13px; }

    /* ===== SUMMARY BAR ===== */
    .summary-bar {
        display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;
    }

    .summary-pill {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 99px; padding: 8px 16px;
        font-size: 13px; color: var(--text-muted);
        display: flex; align-items: center; gap: 6px;
    }
    .summary-pill strong { color: var(--text-main); }
    .summary-pill.highlight { background: var(--primary-light); border-color: var(--primary); color: var(--primary); }
    .summary-pill.highlight strong { color: var(--primary); }
</style>
@endpush

@section('content')

@php
    $totalHabits  = $habits->count();
    $doneToday    = $habits->filter(fn($h) => $h->logs->contains(fn($l) => $l->completed_at->isToday()))->count();
    $bestStreak   = $habits->max('streak_count') ?? 0;
@endphp

<div class="habits-layout">

    {{-- FORM TAMBAH --}}
    <div class="form-card">
        <div class="form-title">
            <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Habit Baru
        </div>

        <form method="POST" action="{{ route('habits.store') }}" id="habitForm">
            @csrf
            <div class="form-group">
                <label>Nama Kebiasaan *</label>
                <input type="text" name="name" class="form-input" placeholder="cth. Baca buku 30 menit..." required value="{{ old('name') }}">
                @error('name')<p style="color:var(--danger);font-size:11px;margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Frekuensi *</label>
                <div class="freq-options">
                    <div class="freq-btn {{ old('frequency', 'daily') === 'daily' ? 'selected' : '' }}"
                         onclick="setFreq('daily', this)">
                        🌅 Harian
                    </div>
                    <div class="freq-btn {{ old('frequency') === 'weekly' ? 'selected' : '' }}"
                         onclick="setFreq('weekly', this)">
                        📅 Mingguan
                    </div>
                </div>
                <input type="hidden" name="frequency" id="freqInput" value="{{ old('frequency', 'daily') }}">
            </div>

            <button type="submit" class="btn-primary">
                <svg viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Tambah Habit
            </button>
        </form>
    </div>

    {{-- DAFTAR HABITS --}}
    <div>
        {{-- SUMMARY --}}
        @if($totalHabits > 0)
        <div class="summary-bar">
            <div class="summary-pill highlight">
                <strong>{{ $doneToday }}/{{ $totalHabits }}</strong> selesai hari ini
            </div>
            <div class="summary-pill">
                🔥 Streak terbaik: <strong>{{ $bestStreak }} hari</strong>
            </div>
            <div class="summary-pill">
                📋 Total habit: <strong>{{ $totalHabits }}</strong>
            </div>
        </div>
        @endif

        @forelse($habits as $habit)
            @php
                $checkedToday = $habit->logs->contains(fn($l) => $l->completed_at->isToday());
                $logDates     = $habit->logs->pluck('completed_at')->map(fn($d) => $d->toDateString())->toArray();
                $last7        = collect(range(6, 0))->map(fn($i) => now()->subDays($i));
            @endphp

            <div class="habit-card {{ $checkedToday ? 'done-today' : '' }}">
                <div class="habit-card-top">
                    <div class="habit-icon {{ $checkedToday ? 'done' : '' }}">
                        {{ $checkedToday ? '✅' : '⚡' }}
                    </div>

                    <div class="habit-info">
                        <div class="habit-name">{{ $habit->name }}</div>
                        <div class="habit-meta">
                            <span class="freq-badge {{ $habit->frequency }}">
                                {{ $habit->frequency === 'daily' ? '🌅 Harian' : '📅 Mingguan' }}
                            </span>
                            @if($habit->streak_count > 0)
                                <span class="streak-display">
                                    🔥 {{ $habit->streak_count }} hari streak
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="habit-right">
                        {{-- TOMBOL CHECK --}}
                        <form method="POST" action="{{ route('habits.check', $habit->habits_id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="check-big {{ $checkedToday ? 'done' : '' }}"
                                    title="{{ $checkedToday ? 'Sudah selesai hari ini!' : 'Tandai selesai' }}"
                                    {{ $checkedToday ? 'disabled' : '' }}>
                                <svg viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </form>

                        {{-- HAPUS --}}
                        <form method="POST" action="{{ route('habits.destroy', $habit->habits_id) }}" onsubmit="return confirm('Hapus habit ini? Semua log akan terhapus.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon del" title="Hapus">
                                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- MINI CALENDAR 7 HARI --}}
                <div class="habit-calendar">
                    <div class="cal-label">7 Hari Terakhir</div>
                    <div class="cal-dots">
                        @foreach($last7 as $day)
                            @php
                                $dayStr    = $day->toDateString();
                                $filled    = in_array($dayStr, $logDates);
                                $isToday   = $day->isToday();
                                $dayNames  = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
                                $dayName   = $dayNames[$day->dayOfWeek];
                            @endphp
                            <div class="cal-day">
                                <span class="cal-day-name">{{ $dayName }}</span>
                                <div class="cal-dot {{ $filled ? 'filled' : '' }} {{ $isToday ? 'today' : '' }}">
                                    {{ $day->format('d') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <svg viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <p>Belum ada habit</p>
                <small>Mulai membangun kebiasaan positifmu sekarang!</small>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
function setFreq(val, el) {
    document.getElementById('freqInput').value = val;
    document.querySelectorAll('.freq-btn').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
}
</script>
@endpush
