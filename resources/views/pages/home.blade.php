<x-layout.app :active="'home'" :title="'Beranda'" :metaDescription="'Prediksi harga cryptocurrency dengan AI Linear Regression. Analisis BTC, ETH, DOGE, BNB secara real-time.'">

    {{-- Hero Section --}}
    <section class="relative pt-24 sm:pt-32 pb-12 sm:pb-20 px-4 sm:px-6 lg:px-8 bg-grid bg-radial-glow overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-2xl sm:text-4xl lg:text-6xl font-extrabold leading-tight animate-fade-in-up">
                    Prediksi Harga Crypto<br>
                    <span class="gradient-text">dengan Linear Regression</span>
                </h1>
                <p class="mt-4 text-sm sm:text-base text-text-secondary leading-relaxed animate-fade-in-up delay-200">
                    Manfaatkan metode Linear Regression untuk menganalisis tren harga cryptocurrency. Dapatkan prediksi harga berdasarkan data historis dari CoinGecko.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up delay-300">
                    <a href="{{ route('prediction') }}" class="gradient-btn text-base px-6 py-3 animate-pulse-glow">
                        <span>Mulai Prediksi</span>
                    </a>
                    <a href="{{ route('market') }}" class="glass-card-static px-6 py-3 rounded-xl text-text-secondary hover:text-accent transition-all text-base font-semibold flex items-center justify-center gap-2">
                        <span>Lihat Market</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Floating elements --}}
        <div class="absolute top-40 left-10 w-20 h-20 rounded-full bg-accent opacity-5 animate-float"></div>
        <div class="absolute bottom-20 right-10 w-32 h-32 rounded-full bg-accent-purple opacity-5 animate-float delay-500"></div>
    </section>

    {{-- Price Ticker --}}
    <section class="bg-bg-secondary overflow-hidden py-3 border-y border-border">
        <div class="flex animate-ticker w-max hover:[animation-play-state:paused]" id="price-ticker">
            {{-- Duplicated 4 times to ensure it fills the screen and animates seamlessly --}}
            @for($i = 0; $i < 4; $i++)
                @foreach($coins as $coin)
                <div class="flex items-center gap-3 px-8 shrink-0 border-r border-border last:border-r-0">
                    <img src="{{ $coin['image'] }}" alt="{{ $coin['name'] }} logo" class="w-6 h-6 rounded-full shadow-sm">
                    <span class="font-bold text-text-primary">{{ $coin['symbol'] }}</span>
                    <span class="text-text-secondary font-mono">$<span class="ticker-price-{{ $coin['id'] }}">{{ number_format($coin['price'], 2) }}</span></span>
                    <span class="ticker-change-container-{{ $coin['id'] }} {{ $coin['change'] >= 0 ? 'price-up' : 'price-down' }} text-sm font-medium flex items-center gap-1">
                        <span class="ticker-icon-{{ $coin['id'] }}">{{ $coin['change'] >= 0 ? '▲' : '▼' }}</span>
                        <span class="ticker-change-{{ $coin['id'] }}">{{ number_format(abs($coin['change']), 2) }}</span>%
                    </span>
                </div>
                @endforeach
            @endfor
        </div>
    </section>

    {{-- Featured Coins --}}
    <section class="py-12 sm:py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12 scroll-reveal">
                <h2 class="text-3xl font-bold text-text-primary">Cryptocurrency <span class="gradient-text">Populer</span></h2>
                <p class="mt-3 text-text-secondary">Pantau harga dan prediksi koin favorit Anda</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($coins as $index => $coin)
                    <x-ui.coin-card
                        :name="$coin['name']"
                        :symbol="$coin['symbol']"
                        :image="$coin['image']"
                        :price="$coin['price']"
                        :change="$coin['change']"
                        :index="$index"
                    />
                @endforeach
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-12 sm:py-20 px-4 sm:px-6 lg:px-8 bg-bg-secondary">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12 scroll-reveal">
                <h2 class="text-3xl font-bold text-text-primary">Cara <span class="gradient-text">Kerja</span></h2>
                <p class="mt-3 text-text-secondary">Tiga langkah mudah untuk mendapatkan prediksi harga</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach([
                    ['step' => '1', 'title' => 'Pilih Koin', 'desc' => 'Pilih cryptocurrency yang ingin Anda prediksi dari daftar koin yang tersedia.'],
                    ['step' => '2', 'title' => 'Jalankan Prediksi', 'desc' => 'Sistem mengambil data historis dan menjalankan model Linear Regression secara otomatis.'],
                    ['step' => '3', 'title' => 'Analisis Hasil', 'desc' => 'Lihat hasil prediksi beserta grafik interaktif dan metrik evaluasi model (MAE & RMSE).'],
                ] as $i => $item)
                <div class="glass-card-static p-8 text-center scroll-reveal" style="animation-delay: {{ $i * 150 }}ms">
                    <div class="text-xs font-bold text-accent mb-2">LANGKAH {{ $item['step'] }}</div>
                    <h3 class="text-xl font-bold text-text-primary mb-3">{{ $item['title'] }}</h3>
                    <p class="text-text-secondary text-sm leading-relaxed">{{ $item['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Chart Preview --}}
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12 scroll-reveal">
                <h2 class="text-xl font-bold text-text-primary">Grafik <span class="gradient-text">Harga</span></h2>
                <p class="mt-3 text-text-secondary">Visualisasi tren harga Bitcoin 30 hari terakhir</p>
            </div>

            <div class="glass-card-static p-6 scroll-reveal">
                <div class="h-80">
                    <canvas id="preview-chart"></canvas>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="relative py-20 px-4 sm:px-6 lg:px-8 bg-radial-glow overflow-hidden">
        <div class="max-w-3xl mx-auto text-center scroll-reveal">
            <h2 class="text-3xl sm:text-4xl font-bold text-text-primary mb-6">
                Siap Memprediksi<br><span class="gradient-text">Harga Crypto?</span>
            </h2>
            <p class="text-text-secondary mb-8 text-lg">
                Mulai analisis dan prediksi harga cryptocurrency sekarang. Gratis dan mudah digunakan.
            </p>
            <a href="{{ route('prediction') }}" class="gradient-btn text-lg px-10 py-4 animate-pulse-glow">
                <span>Mulai Sekarang</span>
            </a>
        </div>
    </section>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sample data for preview chart
        const labels = Array.from({length: 35}, (_, i) => `Hari ${i + 1}`);
        const data = [64000, 64500, 63800, 65200, 66000, 65500, 66800, 67200, 66900, 67500,
                      67800, 67200, 68000, 68500, 67800, 68200, 69000, 68500, 69200, 69800,
                      69500, 70000, 69200, 69800, 70500, 70200, 70800, 71000, 70500, 71200,
                      null, null, null, null, null];
        const prediction = [null, null, null, null, null, null, null, null, null, null,
                           null, null, null, null, null, null, null, null, null, null,
                           null, null, null, null, null, null, null, null, null, 71200,
                           71800, 72500, 73100, 73600, 74200];

        window.createPredictionChart('preview-chart', labels, data, prediction);

        // --- Live Price Polling ---
        setInterval(() => {
            fetch('/api/prices')
                .then(res => res.json())
                .then(coins => {
                    coins.forEach(coin => {
                        const symbol = coin.symbol.toLowerCase();
                        const priceFormatted = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(coin.price);
                        const changeFormatted = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Math.abs(coin.change));
                        const isUp = coin.change >= 0;

                        // Update Ticker (Classes because duplicated)
                        document.querySelectorAll(`.ticker-price-${coin.id}`).forEach(el => el.textContent = priceFormatted);
                        document.querySelectorAll(`.ticker-change-${coin.id}`).forEach(el => el.textContent = changeFormatted);
                        document.querySelectorAll(`.ticker-icon-${coin.id}`).forEach(el => el.textContent = isUp ? '▲' : '▼');
                        document.querySelectorAll(`.ticker-change-container-${coin.id}`).forEach(el => {
                            el.classList.remove('price-up', 'price-down');
                            el.classList.add(isUp ? 'price-up' : 'price-down');
                        });

                        // Update Coin Cards (Unique IDs)
                        const cardPrice = document.getElementById(`card-price-${symbol}`);
                        if (cardPrice) cardPrice.textContent = priceFormatted;
                        
                        const cardChange = document.getElementById(`card-change-${symbol}`);
                        if (cardChange) cardChange.textContent = changeFormatted;
                        
                        const cardIcon = document.getElementById(`card-icon-${symbol}`);
                        if (cardIcon) cardIcon.textContent = isUp ? '▲' : '▼';
                        
                        const cardContainer = document.getElementById(`card-change-container-${symbol}`);
                        if (cardContainer) {
                            cardContainer.classList.remove('price-up', 'price-down');
                            cardContainer.classList.add(isUp ? 'price-up' : 'price-down');
                        }
                    });
                })
                .catch(err => console.error('Error polling prices:', err));
        }, 60000); // Poll every 60 seconds
    });
    </script>
    @endpush

</x-layout.app>

