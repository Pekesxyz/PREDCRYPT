<x-layout.app :active="'about'" :title="'Tentang'" :metaDescription="'Tentang PREDCRYPT - Sistem prediksi harga cryptocurrency menggunakan Linear Regression.'">

    <section class="pt-24 sm:pt-28 pb-12 sm:pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="text-center mb-16 animate-fade-in-up">
                <h1 class="text-3xl sm:text-4xl font-bold text-text-primary">Tentang <span class="gradient-text">PREDCRYPT</span></h1>
                <p class="mt-4 text-text-secondary text-lg leading-relaxed max-w-2xl mx-auto">
                    Sistem prediksi harga cryptocurrency berbasis web yang mengintegrasikan machine learning, API eksternal, dan visualisasi data dalam satu platform.
                </p>
            </div>

            {{-- Mission --}}
            <div class="glass-card-static p-6 sm:p-8 mb-8 scroll-reveal">
                <h2 class="text-xl font-bold text-text-primary mb-4">Tujuan Penelitian</h2>
                <ul class="space-y-3 text-text-secondary">
                    <li class="flex items-start gap-3">
                        <span class="text-accent mt-1">✦</span>
                        Mengembangkan sistem prediksi harga cryptocurrency berbasis web
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-accent mt-1">✦</span>
                        Mengimplementasikan metode Linear Regression dalam prediksi harga
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-accent mt-1">✦</span>
                        Menyediakan visualisasi data historis dan prediksi dalam bentuk grafik interaktif
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-accent mt-1">✦</span>
                        Menyediakan evaluasi model menggunakan MAE dan RMSE
                    </li>
                </ul>
            </div>

            {{-- Tech Stack --}}
            <div class="glass-card-static p-6 sm:p-8 mb-8 scroll-reveal">
                <h2 class="text-xl font-bold text-text-primary mb-6">Teknologi yang Digunakan</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach([
                        ['name' => 'Laravel 12', 'desc' => 'Backend Framework', 'icon' => 'laravel.svg'],
                        ['name' => 'Tailwind CSS v4', 'desc' => 'Styling Framework', 'icon' => 'tailwindcss.svg'],
                        ['name' => 'Chart.js', 'desc' => 'Visualisasi Grafik', 'icon' => 'chartdotjs.svg'],
                        ['name' => 'Python', 'desc' => 'Machine Learning', 'icon' => 'python.svg'],
                        ['name' => 'Scikit-learn', 'desc' => 'Linear Regression', 'icon' => 'scikitlearn.svg'],
                        ['name' => 'CoinGecko API', 'desc' => 'Data Sumber', 'icon' => 'coingecko.svg'],
                    ] as $tech)
                    <div class="p-4 rounded-xl bg-bg-tertiary border border-border hover:border-border-hover transition-all">
                        <div class="w-8 h-8 rounded-lg bg-accent mb-3 flex items-center justify-center text-white text-xs font-bold overflow-hidden">
                            <img src="{{ asset('images/icons/' . $tech['icon']) }}" alt="{{ $tech['name'] }} logo" 
                                 class="w-5 h-5 brightness-0 invert" />
                        </div>
                        <p class="font-semibold text-sm text-text-primary">{{ $tech['name'] }}</p>
                        <p class="text-xs text-text-muted mt-1">{{ $tech['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- How Linear Regression Works --}}
            <div class="glass-card-static p-8 mb-8 scroll-reveal">
                <h2 class="text-xl font-bold text-text-primary mb-4">Tentang Linear Regression</h2>
                <p class="text-text-secondary leading-relaxed mb-4">
                    Linear Regression adalah metode statistik yang digunakan untuk memodelkan hubungan antara variabel dependen (harga) dengan satu atau lebih variabel independen (waktu, volume, dll). Dalam konteks PREDCRYPT, kami menggunakan data historis harga cryptocurrency untuk melatih model dan menghasilkan prediksi harga di masa depan.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                    <div class="p-4 rounded-xl bg-bg-primary border border-border">
                        <p class="text-sm font-semibold text-warning mb-2">MAE (Mean Absolute Error)</p>
                        <p class="text-xs text-text-secondary">Rata-rata selisih absolut antara nilai aktual dan prediksi. Semakin kecil, semakin akurat model.</p>
                    </div>
                    <div class="p-4 rounded-xl bg-bg-primary border border-border">
                        <p class="text-sm font-semibold text-danger mb-2">RMSE (Root Mean Square Error)</p>
                        <p class="text-xs text-text-secondary">Akar rata-rata kuadrat dari selisih nilai aktual dan prediksi. Sensitif terhadap error yang besar.</p>
                    </div>
                </div>
            </div>

            {{-- Developer --}}
            <div class="glass-card-static p-8 scroll-reveal">
                <h2 class="text-xl font-bold text-text-primary mb-6">Pengembang</h2>
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 rounded-full bg-accent flex items-center justify-center text-2xl font-bold text-white shrink-0">
                        P
                    </div>
                    <div>
                        <p class="font-bold text-text-primary text-lg">Pradipta</p>
                        <p class="text-sm text-text-secondary">Mahasiswa — Pengembang PREDCRYPT</p>
                        <p class="text-xs text-text-muted mt-1">Proyek ini dibuat untuk keperluan akademik dan pembelajaran</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layout.app>

