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

        .hab-card {
            background: linear-gradient(135deg, #2a1050, #1e0c3a);
            border: 1px solid #4a2f7e;
            border-radius: 16px;
            padding: 32px;
            margin-top: 24px;
            position: relative;
            overflow: hidden;
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

        .hab-label {
            display: block;
            font-family: 'Cinzel', serif;
            color: #ffd166;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .hab-input {
            width: 100%;
            background: #140724;
            border: 1px solid #4a2f7e;
            color: #e8d5ff;
            border-radius: 8px;
            padding: 12px;
            outline: none;
            transition: all 0.2s ease;
        }

        .hab-input:focus {
            border-color: #f7a62b;
            box-shadow: 0 0 10px rgba(247, 166, 43, 0.2);
        }

        .hab-input-color {
            width: 80px;
            height: 48px;
            background: #140724;
            border: 1px solid #4a2f7e;
            border-radius: 8px;
            cursor: pointer;
            padding: 2px;
        }

        .hab-gem-preview {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            flex-shrink: 0;
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            transition: background-color 0.15s ease;
        }

        .hab-color-text {
            color: #9d82c4;
            font-size: 14px;
            font-family: 'Courier New', Courier, monospace;
        }

        .hab-btn-submit {
            background: linear-gradient(135deg, #c07d00, #8a5a00);
            border: 1.5px solid #f7a62b;
            color: #ffd166;
            font-family: 'Cinzel', serif;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            letter-spacing: 0.5px;
            box-shadow: 0 0 12px rgba(247, 166, 43, 0.2);
            transition: all 0.2s ease;
        }

        .hab-btn-submit:hover {
            background: linear-gradient(135deg, #d08e00, #9a6a00);
            box-shadow: 0 0 20px rgba(247, 166, 43, 0.4);
        }

        .hab-btn-back {
            background: #2a1050;
            border: 1px solid #4a2f7e;
            color: #9d82c4;
            font-family: 'Lato', sans-serif;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        .hab-btn-back:hover {
            background: #3a1a68;
            color: #e8d5ff;
            border-color: #6b4f99;
        }
    </style>

    <div class="hab-wrap">
        <div style="max-width: 600px; margin: 0 auto;">

            {{-- Header --}}
            <div>
                <h1 class="hab-title">✎ Edit Kategori</h1>
                <p class="hab-subtitle">Ubah status dan data kategori sesuai kebutuhan strategimu</p>
            </div>

            {{-- Form Card --}}
            <div class="hab-card">
                <form action="/categories/{{ $category->id }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nama -->
                    <div style="margin-bottom: 24px;">
                        <label class="hab-label">Nama Kategori</label>
                        <input type="text"
                               name="name"
                               value="{{ $category->name }}"
                               class="hab-input"
                               required>
                    </div>

                    <!-- Color -->
                    <div style="margin-bottom: 32px;">
                        <label class="hab-label">Warna Kategori</label>
                        <div style="display: flex; align-items: center; gap: 16px;">
                            
                            <input type="color"
                                   id="colorPicker"
                                   name="color"
                                   value="{{ $category->color }}"
                                   class="hab-input-color">

                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div id="gemPreview" 
                                     class="hab-gem-preview"
                                     style="background-color: {{ $category->color }};">
                                </div>
                                <span id="colorText" class="hab-color-text">
                                    {{ $category->color }}
                                </span>
                            </div>

                        </div>
                    </div>

                    <!-- Actions -->
                    <div style="display: flex; gap: 12px;">
                        <button type="submit" class="hab-btn-submit">
                            Update Kode
                        </button>
                        <a href="/categories" class="hab-btn-back">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- Simple script biar warna gem & teks berubah secara real-time saat user milih warna --}}
    <script>
        document.getElementById('colorPicker').addEventListener('input', function(e) {
            const selectedColor = e.target.value;
            document.getElementById('gemPreview').style.backgroundColor = selectedColor;
            document.getElementById('colorText').textContent = selectedColor;
        });
    </script>

</x-app-layout>