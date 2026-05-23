<x-app-layout>
    <!-- Inject font Cinzel & Lato langsung ke halaman -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <!-- Wrapper Utama (Tema Gelap Ungu) -->
    <div class="min-h-screen p-8" style="background-color: #1a0a2e; font-family: 'Lato', sans-serif;">
        <div class="max-w-xl mx-auto">

            <!-- Header Halaman -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold tracking-wide" style="font-family: 'Cinzel', serif; color: #f7a62b; text-shadow: 0 0 16px rgba(247, 166, 43, 0.4);">
                    📜 Tambah Kategori
                </h1>
                <p class="mt-1.5 text-sm" style="color: #9d82c4;">
                    Buat kategori baru untuk task petualanganmu
                </p>
            </div>

            <!-- Form Card (Gradient Gelap + Border Ungu) -->
            <div class="relative overflow-hidden rounded-2xl border p-8 shadow-2xl" 
                 style="background: linear-gradient(135deg, #2a1050, #1e0c3a); border-color: #4a2f7e;">
                
                <!-- Garis Cahaya di Atas Card -->
                <div class="absolute top-0 left-0 right-0 h-[2px]" style="background: linear-gradient(90deg, transparent, rgba(247, 166, 43, 0.3), transparent);"></div>

                <!-- Form Start -->
                <form action="/categories" method="POST">
                    @csrf

                    <!-- Input Nama Kategori -->
                    <div class="mb-6">
                        <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="font-family: 'Cinzel', serif; color: #ffd166;">
                            Nama Kategori
                        </label>
                        <input type="text"
                               name="name"
                               placeholder="Contoh: Belajar Sihir"
                               required
                               class="w-full border rounded-xl p-3 outline-none transition-all placeholder:text-purple-900/50"
                               style="background-color: #140724; border-color: #4a2f7e; color: #e8d5ff;">
                    </div>

                    <!-- Input Warna -->
                    <div class="mb-8">
                        <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="font-family: 'Cinzel', serif; color: #ffd166;">
                            Pilih Warna Lambang
                        </label>
                        <input type="color"
                               name="color"
                               value="#7a4fc4"
                               class="w-20 h-12 border rounded-lg cursor-pointer p-0.5"
                               style="background-color: #140724; border-color: #4a2f7e;">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex gap-3">
                        <!-- Simpan -->
                        <button type="submit" 
                                class="border font-semibold px-6 py-3 rounded-xl shadow-md transition-all duration-200 active:scale-95 cursor-pointer"
                                style="background: linear-gradient(135deg, #6a3db8, #4e2a8e); border-color: #f7a62b; color: #f7a62b; font-family: 'Cinzel', serif; font-size: 13px;">
                            ✦ Simpan Quest
                        </button>
                        
                        <!-- Kembali -->
                        <a href="/categories" 
                           class="border font-semibold px-6 py-3 rounded-xl transition-all duration-200 text-sm flex items-center"
                           style="background-color: #2a1050; border-color: #4a2f7e; color: #9d82c4;">
                            Kembali
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>