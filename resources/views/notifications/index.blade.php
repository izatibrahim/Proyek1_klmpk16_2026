@extends('layouts.app')
@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@push('styles')
<style>
    .page-top { display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px; }
    .page-desc { font-size:14px;color:var(--text-muted); }
    .btn-ghost { display:inline-flex;align-items:center;gap:6px;background:none;border:1px solid var(--border);padding:9px 16px;border-radius:var(--radius-sm);font-size:13px;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all .15s; }
    .btn-ghost svg { width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2; }
    .btn-ghost:hover { background:var(--bg-page);color:var(--text-main); }

    .notif-summary { display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap; }
    .summary-chip { display:flex;align-items:center;gap:6px;background:var(--bg-card);border:1px solid var(--border);border-radius:99px;padding:6px 14px;font-size:12px;color:var(--text-muted); }
    .summary-chip strong { color:var(--text-main); }
    .chip-dot { width:8px;height:8px;border-radius:50%; }

    .notif-card { background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:10px;transition:border-color .15s; }
    .notif-card:hover { border-color:var(--primary); }
    .notif-card.unread { border-left:4px solid var(--primary); }

    .notif-row { display:flex;align-items:flex-start;gap:14px;padding:16px 18px; }

    .notif-icon-wrap { width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0; }
    .notif-icon-wrap.deadline_reminder { background:#fee2e2; }
    .notif-icon-wrap.habit_reminder    { background:#fef3c7; }
    .notif-icon-wrap.achievement       { background:#d1fae5; }
    .notif-icon-wrap.info              { background:var(--primary-light); }

    .notif-content { flex:1;min-width:0; }
    .notif-head { display:flex;align-items:center;gap:10px;margin-bottom:4px;flex-wrap:wrap; }
    .notif-title-text { font-size:14px;font-weight:700;color:var(--text-main); }

    .type-badge { font-size:10px;font-weight:700;padding:2px 8px;border-radius:99px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap; }
    .type-badge.deadline_reminder { background:#fee2e2;color:#991b1b; }
    .type-badge.habit_reminder    { background:#fef3c7;color:#92400e; }
    .type-badge.achievement       { background:#d1fae5;color:#065f46; }
    .type-badge.info              { background:var(--primary-light);color:var(--primary-dark); }

    .unread-dot { width:8px;height:8px;border-radius:50%;background:var(--primary);flex-shrink:0; }
    .notif-msg  { font-size:13px;color:var(--text-muted);line-height:1.5; }
    .notif-time { font-size:11px;color:var(--text-muted);margin-top:6px;display:flex;align-items:center;gap:4px; }
    .notif-time svg { width:11px;height:11px;stroke:currentColor;fill:none; }

    .notif-actions { display:flex;flex-direction:column;gap:6px;align-items:flex-end;flex-shrink:0; }
    .btn-sm { display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:var(--radius-sm);font-size:11px;font-weight:600;cursor:pointer;border:none;text-decoration:none;white-space:nowrap;transition:all .15s; }
    .btn-sm svg { width:11px;height:11px;stroke:currentColor;fill:none;stroke-width:2; }
    .btn-sm.primary { background:var(--primary-light);color:var(--primary); }
    .btn-sm.primary:hover { background:var(--primary);color:white; }
    .btn-sm.danger  { background:#fee2e2;color:var(--danger); }
    .btn-sm.danger:hover { background:var(--danger);color:white; }

    .empty-state { text-align:center;padding:64px 20px;color:var(--text-muted); }
    .empty-state .big-icon { font-size:56px;margin-bottom:16px; }
    .empty-state p { font-size:16px;font-weight:600;margin-bottom:6px;color:var(--text-main); }
    .empty-state small { font-size:14px; }

    .pagination-wrap { display:flex;justify-content:center;margin-top:20px; }
</style>
@endpush

@section('content')
<div class="page-top">
    <div class="page-desc">Semua notifikasi — deadline, reminder habit, dan pencapaian.</div>
    <form method="POST" action="{{ route('notifications.markAllRead') }}">
        @csrf @method('PATCH')
        <button type="submit" class="btn-ghost">
            <svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Tandai Semua Dibaca
        </button>
    </form>
</div>

@php
    $totalNotifs  = $totalNotifs ?? 0;
    $unreadNotifs = $unreadNotifs ?? 0;
    $deadlineCount = $deadlineCount ?? 0;
    $habitCount = $habitCount ?? 0;
    $achievementCount = $achievementCount ?? 0;
@endphp

<div class="notif-summary">
    <div class="summary-chip">Total: <strong>{{ $totalNotifs }}</strong></div>
    <div class="summary-chip"><div class="chip-dot" style="background:var(--primary)"></div> Belum dibaca: <strong>{{ $unreadNotifs }}</strong></div>
    <div class="summary-chip"><div class="chip-dot" style="background:var(--danger)"></div> Deadline: <strong>{{ $deadlineCount }}</strong></div>
    <div class="summary-chip"><div class="chip-dot" style="background:var(--success)"></div> Pencapaian: <strong>{{ $achievementCount }}</strong></div>
</div>

@forelse($notifications as $notif)
<div class="notif-card {{ !$notif->is_read ? 'unread' : '' }}">
    <div class="notif-row">
        <div class="notif-icon-wrap {{ $notif->type }}">{!! $notif->icon !!}</div>
        <div class="notif-content">
            <div class="notif-head">
                <span class="notif-title-text">{{ $notif->title }}</span>
                <span class="type-badge {{ $notif->type }}">{{ match($notif->type) { 'deadline_reminder'=>'Deadline','habit_reminder'=>'Habit','achievement'=>'Pencapaian',default=>'Info' } }}</span>
                @if(!$notif->is_read)<div class="unread-dot"></div>@endif
            </div>
            <div class="notif-msg">{{ $notif->message }}</div>
            <div class="notif-time">
                <svg viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                {{ $notif->created_at->diffForHumans() }}
                @if($notif->read_at) · Dibaca {{ $notif->read_at->diffForHumans() }} @endif
            </div>
        </div>
        <div class="notif-actions">
            @if($notif->link)
                <a href="{{ $notif->link }}" class="btn-sm primary">
                    <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>Lihat
                </a>
            @endif
            <form method="POST" action="{{ route('notifications.destroy', $notif->notifications_id) }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn-sm danger">
                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@empty
<div class="empty-state">
    <div class="big-icon">🔔</div>
    <p>Tidak ada notifikasi</p>
    <small>Notifikasi muncul saat ada deadline, reminder habit, atau pencapaian streak.</small>
</div>
@endforelse

@if(method_exists($notifications, 'hasPages') && $notifications->hasPages())
<div class="pagination-wrap">{{ $notifications->links() }}</div>
@endif
@endsection