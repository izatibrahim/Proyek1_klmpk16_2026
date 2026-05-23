<x-app-layout>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Lato:wght@300;400;700&display=swap');

        .hab-wrap {
            background: #1a0a2e;
            min-height: 100vh;
            padding: 32px 20px;
            font-family: 'Lato', sans-serif;
        }

        .hab-title {
            font-family: 'Cinzel', serif;
            color: #f7a62b;
            font-size: 28px;
            font-weight: 700;
            text-shadow: 0 0 16px rgba(247, 166, 43, 0.4);
            letter-spacing: 1px;
            margin: 0;
        }

        .hab-subtitle {
            color: #9d82c4;
            font-size: 14px;
            margin: 6px 0 0;
        }

        .hab-btn-add {
            background: linear-gradient(135deg, #6a3db8, #4e2a8e);
            border: 1.5px solid #f7a62b;
            color: #f7a62b;
            font-family: 'Cinzel', serif;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            letter-spacing: 0.5px;
            box-shadow: 0 0 12px rgba(247, 166, 43, 0.2);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .hab-btn-add:hover {
            background: linear-gradient(135deg, #7a4dc8, #5e3a9e);
            box-shadow: 0 0 20px rgba(247, 166, 43, 0.4);
            color: #f7a62b;
        }

        .hab-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .hab-stat {
            background: #1e0c3a;
            border: 1px solid #3a2060;
            border-radius: 10px;
            padding: 14px 16px;
            text-align: center;
        }

        .hab-stat-num {
            font-family: 'Cinzel', serif;
            color: #f7a62b;
            font-size: 22px;
            font-weight: 700;
        }

        .hab-stat-label {
            color: #7b5fa0;
            font-size: 11px;
            margin-top: 4px;
        }

        .hab-card {
            background: linear-gradient(135deg, #2a1050, #1e0c3a);
            border: 1px solid #4a2f7e;
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            transition: border-color 0.2s ease, transform 0.15s ease;
        }

        .hab-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(247, 166, 43, 0.3), transparent);
        }

        .hab-card:hover {
            border-color: #7a4fc4;
            transform: translateY(-1px);
        }

        .hab-gem {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            flex-shrink: 0;
            border: 1.5px solid rgba(255, 255, 255, 0.12);
        }

        .hab-cat-name {
            font-family: 'Cinzel', serif;
            color: #e8d5ff;
            font-size: 15px;
            font-weight: 600;
            margin: 0;
        }

        .hab-cat-id {
            color: #6b4f99;
            font-size: 12px;
            margin: 4px 0 0;
        }

        .hab-xp-bar {
            width: 90px;
            height: 5px;
            background: #3a2060;
            border-radius: 99px;
            overflow: hidden;
            margin-top: 8px;
        }

        .hab-xp-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #7a4fc4, #c49aff);
            width: 50%;
        }

        .hab-btn-edit {
            background: linear-gradient(135deg, #c07d00, #8a5a00);
            border: 1px solid #f7a62b;
            color: #ffd166;
            font-family: 'Cinzel', serif;
            font-size: 11px;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .hab-btn-edit:hover {
            background: linear-gradient(135deg, #d08e00, #9a6a00);
            color: #ffd166;
        }

        .hab-btn-del {
            background: linear-gradient(135deg, #6b1a1a, #4a0e0e);
            border: 1px solid #e05252;
            color: #ff8c8c;
            font-family: 'Cinzel', serif;
            font-size: 11px;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .hab-btn-del:hover {
            background: linear-gradient(135deg, #7b2a2a, #5a1e1e);
        }

        /* Empty state */
        .hab-empty {
            background: linear-gradient(135deg, #2a1050, #1e0c3a);
            border: 1px dashed #4a2f7e;
            border-radius: 16px;
            padding: 60px 20px;
            text-align: center;
        }

        .hab-empty-icon {
            font-size: 56px;
            display: block;
            margin-bottom: 16px;
        }

        .hab-empty-title {
            font-family: 'Cinzel', serif;
            color: #9d82c4;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .hab-empty-sub {
            color: #5a4080;
            font-size: 14px;
            margin-bottom: 24px;
        }
    </style>

    <div class="hab-wrap">
        <div style="max-width: 760px; margin: 0 auto;">

            {{-- Header --}}
            <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 28px;">
                <div>
                    <h1 class="hab-title">⚔ Category Quests</h1>
                    <p class="hab-subtitle">Kelola semua kategori task kamu</p>
                </div>
                <a href="/categories/create" class="hab-btn-add">
                    ✦ Tambah Kategori
                </a>
            </div>

            {{-- Stats Bar --}}
            <div class="hab-stats">
                <div class="hab-stat">
                    <div class="hab-stat-num">{{ $categories->count() }}</div>
                    <div class="hab-stat-label">Kategori Aktif</div>
                </div>
                <div class="hab-stat">
                    <div class="hab-stat-num">🔮</div>
                    <div class="hab-stat-label">Dungeon Level</div>
                </div>
                <div class="hab-stat">
                    <div class="hab-stat-num">⚡</div>
                    <div class="hab-stat-label">Quest Points</div>
                </div>
            </div>

            {{-- Category Cards --}}
            <div style="display: flex; flex-direction: column; gap: 10px;">

                @forelse($categories as $category)

                    <div class="hab-card">

                        {{-- Left: color gem + info --}}
                        <div style="display: flex; align-items: center; gap: 16px;">

                            <div class="hab-gem"
                                 style="background-color: {{ $category->color }};">
                            </div>

                            <div>
                                <p class="hab-cat-name">{{ $category->name }}</p>
                                <p class="hab-cat-id">ID: #{{ str_pad($category->id, 3, '0', STR_PAD_LEFT) }}</p>
                                <div class="hab-xp-bar">
                                    <div class="hab-xp-fill"></div>
                                </div>
                            </div>

                        </div>

                        {{-- Right: actions --}}
                        <div style="display: flex; align-items: center; gap: 10px; flex-shrink: 0;">

                            <a href="/categories/{{ $category->id }}/edit"
                               class="hab-btn-edit">
                                ✎ Edit
                            </a>

                            <form action="/categories/{{ $category->id }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="hab-btn-del">
                                    ✖ Hapus
                                </button>
                            </form>

                        </div>

                    </div>

                @empty

                    {{-- Empty State --}}
                    <div class="hab-empty">
                        <span class="hab-empty-icon">🏚</span>
                        <h2 class="hab-empty-title">Dungeon Masih Kosong</h2>
                        <p class="hab-empty-sub">Tambahkan kategori pertama kamu dan mulai petualanganmu!</p>
                        <a href="/categories/create" class="hab-btn-add" style="display: inline-flex;">
                            ✦ Mulai Quest
                        </a>
                    </div>

                @endforelse

            </div>

        </div>
    </div>

</x-app-layout>