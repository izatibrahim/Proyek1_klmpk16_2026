@extends('layouts.app')
@section('title', 'To-Do List')
@section('page-title', 'To-Do List')

@push('styles')
<style>
    /* ===== LAYOUT ===== */
    .todos-layout {
        display: grid;
        grid-template-columns: 340px 1fr;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 900px) {
        .todos-layout { grid-template-columns: 1fr; }
    }

    /* ===== FORM TAMBAH ===== */
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

    .form-input, .form-select, .form-textarea {
        width: 100%; padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 14px; color: var(--text-main);
        background: var(--bg-page);
        outline: none; transition: border-color .15s;
        font-family: inherit;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: var(--primary); background: white;
    }
    .form-textarea { resize: vertical; min-height: 80px; }

    .btn-primary {
        width: 100%;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        background: var(--primary); color: white;
        border: none; padding: 12px; border-radius: var(--radius-sm);
        font-size: 14px; font-weight: 600; cursor: pointer;
        transition: background .15s;
    }
    .btn-primary svg { width: 16px; height: 16px; stroke: white; fill: none; stroke-width: 2; }
    .btn-primary:hover { background: var(--primary-dark); }

    /* ===== FILTER BAR ===== */
    .filter-bar {
        display: flex; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; align-items: center;
    }

    .filter-btn {
        padding: 7px 14px; border-radius: 99px;
        border: 1px solid var(--border);
        background: var(--bg-card); color: var(--text-muted);
        font-size: 13px; font-weight: 600; cursor: pointer;
        text-decoration: none; transition: all .15s;
    }
    .filter-btn:hover { border-color: var(--primary); color: var(--primary); }
    .filter-btn.active { background: var(--primary); border-color: var(--primary); color: white; }

    .filter-select {
        padding: 7px 12px; border-radius: 99px;
        border: 1px solid var(--border);
        background: var(--bg-card); color: var(--text-muted);
        font-size: 13px; cursor: pointer; outline: none;
    }

    .todos-count {
        margin-left: auto;
        font-size: 13px; color: var(--text-muted);
    }
    .todos-count strong { color: var(--text-main); }

    /* ===== TODO CARD ===== */
    .todo-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        margin-bottom: 10px;
        overflow: hidden;
        transition: all .2s;
    }
    .todo-card:hover { border-color: var(--primary); }
    .todo-card.done { opacity: .65; }

    .todo-card-body {
        display: flex; align-items: flex-start; gap: 14px; padding: 16px 18px;
    }

    .todo-check-circle {
        width: 22px; height: 22px;
        border-radius: 50%;
        border: 2px solid var(--border);
        flex-shrink: 0; margin-top: 1px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .15s;
        background: none;
    }
    .todo-check-circle:hover { border-color: var(--success); }
    .todo-check-circle.done { background: var(--success); border-color: var(--success); }
    .todo-check-circle svg { width: 12px; height: 12px; stroke: white; fill: none; stroke-width: 3; }

    .todo-main { flex: 1; min-width: 0; }

    .todo-title {
        font-size: 15px; font-weight: 600; color: var(--text-main);
        line-height: 1.4; margin-bottom: 6px;
    }
    .todo-title.done { text-decoration: line-through; color: var(--text-muted); }

    .todo-desc {
        font-size: 13px; color: var(--text-muted); margin-bottom: 8px;
        line-height: 1.5;
    }

    .todo-tags { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

    .cat-tag {
        font-size: 11px; font-weight: 700; padding: 3px 10px;
        border-radius: 99px; color: white;
    }

    .deadline-tag {
        font-size: 12px; color: var(--text-muted);
        display: flex; align-items: center; gap: 4px;
    }
    .deadline-tag svg { width: 12px; height: 12px; stroke: currentColor; fill: none; }
    .deadline-tag.overdue { color: var(--danger); font-weight: 600; }
    .deadline-tag.soon    { color: var(--warning); font-weight: 600; }

    .todo-actions { display: flex; gap: 6px; flex-shrink: 0; }

    .btn-icon {
        width: 32px; height: 32px;
        background: none; border: 1px solid var(--border);
        border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .15s;
    }
    .btn-icon svg { width: 14px; height: 14px; stroke: var(--text-muted); fill: none; stroke-width: 2; }
    .btn-icon:hover { background: var(--bg-page); }
    .btn-icon.del:hover { background: #fef2f2; border-color: var(--danger); }
    .btn-icon.del:hover svg { stroke: var(--danger); }

    /* ===== INLINE EDIT ===== */
    .todo-edit-panel {
        display: none;
        border-top: 1px solid var(--border);
        padding: 16px 18px;
        background: var(--bg-page);
    }
    .todo-edit-panel.open { display: block; }

    .edit-row { display: flex; gap: 10px; flex-wrap: wrap; }
    .edit-input { padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; background: white; color: var(--text-main); outline: none; }
    .edit-input:focus { border-color: var(--primary); }
    .btn-save { padding: 8px 16px; background: var(--primary); color: white; border: none; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; cursor: pointer; }

    /* ===== EMPTY ===== */
    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
    .empty-state svg { width: 56px; height: 56px; stroke: var(--border); fill: none; margin-bottom: 16px; stroke-width: 1.5; }
    .empty-state p { font-size: 16px; font-weight: 600; margin-bottom: 6px; }
    .empty-state small { font-size: 13px; }
</style>
@endpush

@section('content')

<div class="todos-layout">

    {{-- FORM TAMBAH --}}
    <div class="form-card">
        <div class="form-title">
            <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Tugas
        </div>

        <form method="POST" action="{{ route('todos.store') }}">
            @csrf
            <div class="form-group">
                <label>Judul Tugas *</label>
                <input type="text" name="title" class="form-input" placeholder="Apa yang perlu dikerjakan?" required value="{{ old('title') }}">
                @error('title')<p style="color:var(--danger);font-size:11px;margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" class="form-textarea" placeholder="Catatan tambahan (opsional)...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" class="form-select">
                    <option value="">— Tanpa Kategori —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->categories_id }}" {{ old('category_id') == $cat->categories_id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Deadline</label>
                <input type="date" name="deadline" class="form-input" value="{{ old('deadline') }}" min="{{ today()->toDateString() }}">
            </div>

            <button type="submit" class="btn-primary">
                <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Tugas
            </button>
        </form>
    </div>

    {{-- DAFTAR TUGAS --}}
    <div>
        {{-- FILTER BAR --}}
        <div class="filter-bar">
            <a href="{{ route('todos.index') }}" class="filter-btn {{ !request('status') && !request('category_id') ? 'active' : '' }}">Semua</a>
            <a href="{{ route('todos.index', ['status' => 'pending']) }}" class="filter-btn {{ request('status') === 'pending' ? 'active' : '' }}">Belum Selesai</a>
            <a href="{{ route('todos.index', ['status' => 'done']) }}" class="filter-btn {{ request('status') === 'done' ? 'active' : '' }}">Selesai</a>

            <form method="GET" action="{{ route('todos.index') }}" style="display:inline">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <select name="category_id" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->categories_id }}" {{ request('category_id') == $cat->categories_id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </form>

            <span class="todos-count"><strong>{{ $todos->count() }}</strong> tugas</span>
        </div>

        {{-- TODOS --}}
        @forelse($todos as $todo)
            @php
                $isOverdue = !$todo->is_done && $todo->deadline && $todo->deadline->isPast();
                $isSoon    = !$todo->is_done && $todo->deadline && $todo->deadline->isToday();
            @endphp

            <div class="todo-card {{ $todo->is_done ? 'done' : '' }}" id="todo-{{ $todo->todos_id }}">
                <div class="todo-card-body">

                    {{-- CHECK --}}
                    <form method="POST" action="{{ route('todos.toggle', $todo->todos_id) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="todo-check-circle {{ $todo->is_done ? 'done' : '' }}" title="Toggle selesai">
                            @if($todo->is_done)
                                <svg viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </button>
                    </form>

                    {{-- KONTEN --}}
                    <div class="todo-main">
                        <div class="todo-title {{ $todo->is_done ? 'done' : '' }}">{{ $todo->title }}</div>
                        @if($todo->description)
                            <div class="todo-desc">{{ Str::limit($todo->description, 80) }}</div>
                        @endif
                        <div class="todo-tags">
                            @if($todo->category)
                                <span class="cat-tag" style="background: {{ $todo->category->color }}">{{ $todo->category->name }}</span>
                            @endif
                            @if($todo->deadline)
                                <span class="deadline-tag {{ $isOverdue ? 'overdue' : ($isSoon ? 'soon' : '') }}">
                                    <svg viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    {{ $isOverdue ? 'Terlambat · ' : ($isSoon ? 'Hari ini · ' : '') }}
                                    {{ $todo->deadline->translatedFormat('d M Y') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- AKSI --}}
                    <div class="todo-actions">
                        <button class="btn-icon" onclick="toggleEdit({{ $todo->todos_id }})" title="Edit">
                            <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('todos.destroy', $todo->todos_id) }}" onsubmit="return confirm('Hapus tugas ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon del" title="Hapus">
                                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- INLINE EDIT --}}
                <div class="todo-edit-panel" id="edit-{{ $todo->todos_id }}">
                    <form method="POST" action="{{ route('todos.update', $todo->todos_id) }}">
                        @csrf @method('PUT')
                        <div class="edit-row" style="margin-bottom: 10px;">
                            <input type="text" name="title" class="edit-input" style="flex:1; min-width:180px;"
                                   value="{{ $todo->title }}" required>
                            <select name="category_id" class="edit-input">
                                <option value="">— Tanpa Kategori —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->categories_id }}" {{ $todo->category_id == $cat->categories_id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="date" name="deadline" class="edit-input"
                                   value="{{ $todo->deadline ? $todo->deadline->toDateString() : '' }}">
                        </div>
                        <div style="display:flex; gap:8px; align-items:center;">
                            <textarea name="description" class="edit-input" style="flex:1; min-height:60px; resize:vertical;"
                                      placeholder="Deskripsi...">{{ $todo->description }}</textarea>
                            <button type="submit" class="btn-save">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p>Belum ada tugas</p>
                <small>Tambahkan tugas pertamamu melalui form di samping!</small>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleEdit(id) {
    const panel = document.getElementById('edit-' + id);
    panel.classList.toggle('open');
}
</script>
@endpush
