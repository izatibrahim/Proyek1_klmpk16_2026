@extends('layouts.app')
@section('title', 'Kategori')
@section('page-title', 'Kategori')

@push('styles')
<style>
    .page-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .page-desc { font-size: 14px; color: var(--text-muted); }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--primary);
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: var(--radius-sm);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background .15s;
    }
    .btn-primary svg { width: 16px; height: 16px; stroke: white; fill: none; stroke-width: 2; }
    .btn-primary:hover { background: var(--primary-dark); }

    /* ===== GRID KATEGORI ===== */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .category-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        transition: box-shadow .15s;
        border-top: 4px solid #ccc;
    }
    .category-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.07); }

    .cat-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .cat-dot {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px;
    }

    .cat-actions { display: flex; gap: 6px; }

    .btn-icon {
        width: 30px; height: 30px;
        background: none;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: all .15s;
    }
    .btn-icon svg { width: 14px; height: 14px; stroke: var(--text-muted); fill: none; stroke-width: 2; }
    .btn-icon:hover { background: var(--bg-page); }
    .btn-icon.del:hover { background: #fef2f2; border-color: var(--danger); }
    .btn-icon.del:hover svg { stroke: var(--danger); }

    .cat-name { font-size: 16px; font-weight: 700; color: var(--text-main); }

    .cat-count {
        font-size: 13px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .cat-count svg { width: 13px; height: 13px; stroke: currentColor; fill: none; }

    /* ===== FORM TAMBAH ===== */
    .form-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 28px;
    }

    .form-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-title svg { width: 16px; height: 16px; stroke: var(--primary); fill: none; stroke-width: 2; }

    .form-row { display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap; }

    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group label { font-size: 12px; font-weight: 600; color: var(--text-muted); letter-spacing: .04em; text-transform: uppercase; }

    .form-input {
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 14px;
        color: var(--text-main);
        background: var(--bg-page);
        outline: none;
        transition: border-color .15s;
        width: 100%;
    }
    .form-input:focus { border-color: var(--primary); background: white; }

    .color-row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

    .color-pick {
        width: 30px; height: 30px;
        border-radius: 50%;
        border: 3px solid transparent;
        cursor: pointer;
        transition: transform .15s;
    }
    .color-pick:hover { transform: scale(1.15); }
    .color-pick.selected { border-color: var(--text-main); }

    input[type="color"] {
        width: 30px; height: 30px;
        border-radius: 50%;
        border: 1px solid var(--border);
        cursor: pointer;
        padding: 0;
    }

    /* ===== MODAL EDIT ===== */
    .modal-bg {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 200;
        align-items: center;
        justify-content: center;
    }
    .modal-bg.open { display: flex; }

    .modal {
        background: var(--bg-card);
        border-radius: var(--radius);
        padding: 28px;
        width: 100%;
        max-width: 400px;
        margin: 16px;
    }

    .modal-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--text-main);
    }

    .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }

    .btn-ghost {
        padding: 9px 16px;
        background: none;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 14px;
        color: var(--text-muted);
        cursor: pointer;
    }
    .btn-ghost:hover { background: var(--bg-page); }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: var(--text-muted);
    }
    .empty-state svg { width: 48px; height: 48px; stroke: var(--border); fill: none; margin-bottom: 12px; }
    .empty-state p { font-size: 15px; margin-bottom: 4px; }
    .empty-state small { font-size: 13px; }
</style>
@endpush

@section('content')

<div class="page-top">
    <div>
        <div class="page-desc">Kelola label kategori untuk mengelompokkan tugas-tugasmu.</div>
    </div>
</div>

{{-- FORM TAMBAH KATEGORI --}}
<div class="form-card">
    <div class="form-title">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Kategori Baru
    </div>
    <form method="POST" action="{{ route('categories.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group" style="flex: 1; min-width: 180px;">
                <label>Nama Kategori</label>
                <input type="text" name="name" class="form-input" placeholder="cth. Kuliah, Pribadi, Olahraga" required value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label>Warna Label</label>
                <div class="color-row">
                    @foreach(['#6366f1','#10b981','#f59e0b','#ef4444','#3b82f6','#ec4899','#8b5cf6','#14b8a6'] as $c)
                        <div class="color-pick {{ old('color', '#6366f1') === $c ? 'selected' : '' }}"
                             style="background:{{ $c }}"
                             onclick="selectColor('{{ $c }}', this)"
                             title="{{ $c }}"></div>
                    @endforeach
                    <input type="color" id="custom-color" title="Pilih warna kustom"
                           onchange="selectColor(this.value, null)">
                    <input type="hidden" name="color" id="color-input" value="{{ old('color', '#6366f1') }}">
                </div>
            </div>
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="submit" class="btn-primary">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah
                </button>
            </div>
        </div>
        @error('name')<p style="color:var(--danger);font-size:12px;margin-top:8px;">{{ $message }}</p>@enderror
    </form>
</div>

{{-- GRID KATEGORI --}}
@if($categories->isEmpty())
    <div class="empty-state">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        <p>Belum ada kategori</p>
        <small>Tambahkan kategori pertamamu di atas!</small>
    </div>
@else
    <div class="categories-grid">
        @foreach($categories as $cat)
            <div class="category-card" style="border-top-color: {{ $cat->color }}">
                <div class="cat-head">
                    <div class="cat-dot" style="background: {{ $cat->color }}20">
                        <span style="font-size:18px">🏷️</span>
                    </div>
                    <div class="cat-actions">
                        <button class="btn-icon" onclick="openEdit({{ $cat->categories_id }}, '{{ $cat->name }}', '{{ $cat->color }}')" title="Edit">
                            <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('categories.destroy', $cat->categories_id) }}" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon del" title="Hapus">
                                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="cat-name">{{ $cat->name }}</div>
                <div class="cat-count">
                    <svg viewBox="0 0 24 24" stroke-width="1.8"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                    {{ $cat->todos_count }} tugas
                </div>
                <div style="height:4px; border-radius:99px; background:{{ $cat->color }}33; overflow:hidden;">
                    <div style="height:100%; width:{{ $cat->todos_count > 0 ? min(100, $cat->todos_count * 20) : 0 }}%; background:{{ $cat->color }};"></div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- MODAL EDIT --}}
<div class="modal-bg" id="editModal">
    <div class="modal">
        <div class="modal-title">Edit Kategori</div>
        <form method="POST" id="editForm">
            @csrf @method('PUT')
            <div class="form-group" style="margin-bottom: 14px;">
                <label>Nama Kategori</label>
                <input type="text" name="name" id="editName" class="form-input" required>
            </div>
            <div class="form-group">
                <label>Warna</label>
                <div class="color-row">
                    @foreach(['#6366f1','#10b981','#f59e0b','#ef4444','#3b82f6','#ec4899','#8b5cf6','#14b8a6'] as $c)
                        <div class="color-pick"
                             style="background:{{ $c }}"
                             onclick="selectEditColor('{{ $c }}', this)"
                             title="{{ $c }}"
                             data-color="{{ $c }}"></div>
                    @endforeach
                    <input type="hidden" name="color" id="editColorInput" value="">
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-ghost" onclick="closeEdit()">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function selectColor(hex, el) {
    document.getElementById('color-input').value = hex;
    document.querySelectorAll('.color-pick').forEach(d => d.classList.remove('selected'));
    if (el) el.classList.add('selected');
}

function selectEditColor(hex, el) {
    document.getElementById('editColorInput').value = hex;
    document.querySelectorAll('#editModal .color-pick').forEach(d => d.classList.remove('selected'));
    if (el) el.classList.add('selected');
}

function openEdit(id, name, color) {
    document.getElementById('editForm').action = `/categories/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editColorInput').value = color;
    document.querySelectorAll('#editModal .color-pick').forEach(d => {
        d.classList.toggle('selected', d.dataset.color === color);
    });
    document.getElementById('editModal').classList.add('open');
}

function closeEdit() {
    document.getElementById('editModal').classList.remove('open');
}
</script>
@endpush
