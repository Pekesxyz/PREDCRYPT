<x-layout.app :active="'prediction'" :title="'Prediksi'" :metaDescription="'Prediksi harga cryptocurrency menggunakan Linear Regression. Analisis BTC, ETH, DOGE, BNB.'">

    <section class="pt-24 sm:pt-28 pb-12 sm:pb-20 px-4 sm:px-6 lg:px-8" x-data="predictionPage()">
        <div class="max-w-7xl mx-auto">
            {{-- Header --}}
            <div class="mb-10 animate-fade-in-up">
                <h1 class="text-3xl font-bold text-text-primary"><span class="gradient-text">Prediksi</span> Harga</h1>
                <p class="mt-2 text-text-secondary">Pilih koin dan jalankan prediksi menggunakan Linear Regression</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                {{-- Control Panel (Left) --}}
                <div class="lg:col-span-1 space-y-6 animate-fade-in-up delay-100">
                    {{-- Coin Selection --}}
                    <div class="glass-card-static p-6">
                        <h3 class="font-semibold text-text-primary mb-4">Pilih Koin</h3>
                        <select x-model="selectedCoin" class="form-input" id="coin-select">
                            <option value="">-- Pilih Koin --</option>
                            <option value="bitcoin">Bitcoin (BTC)</option>
                            <option value="ethereum">Ethereum (ETH)</option>
                            <option value="solana">Solana (SOL)</option>
                            <option value="binancecoin">BNB (BNB)</option>
                        </select>

                        <button @click="runPrediction()" :disabled="!selectedCoin || loading"
                            class="gradient-btn w-full mt-4 text-sm" id="predict-btn">
                            <span x-show="!loading">Jalankan Prediksi</span>
                            <span x-show="loading" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                Memproses...
                            </span>
                        </button>
                    </div>

                    {{-- Results Stats --}}
                    <div x-show="result || loading" x-transition class="space-y-4">
                        <div class="glass-card-static p-5">
                            <p class="text-xs text-text-muted mb-1">Harga Saat Ini</p>
                            <template x-if="loading">
                                <x-ui.skeleton class="h-7 w-24 mt-1" />
                            </template>
                            <template x-if="!loading && result">
                                <p class="text-xl font-bold text-text-primary">$<span x-text="result ? result.currentPrice.toLocaleString('en-US', {minimumFractionDigits: 2}) : '0'"></span></p>
                            </template>
                        </div>
                        <div class="glass-card-static p-5">
                            <p class="text-xs text-text-muted mb-1">Harga Prediksi</p>
                            <template x-if="loading">
                                <x-ui.skeleton class="h-7 w-24 mt-1" />
                            </template>
                            <template x-if="!loading && result">
                                <p class="text-xl font-bold gradient-text">$<span x-text="result ? result.predictedPrice.toLocaleString('en-US', {minimumFractionDigits: 2}) : '0'"></span></p>
                            </template>
                        </div>
                        <div class="glass-card-static p-5">
                            <p class="text-xs text-text-muted mb-1">Perubahan</p>
                            <template x-if="loading">
                                <x-ui.skeleton class="h-7 w-20 mt-1" />
                            </template>
                            <template x-if="!loading && result">
                                <p class="text-xl font-bold" :class="result && result.change >= 0 ? 'price-up' : 'price-down'">
                                    <span x-text="result ? (result.change >= 0 ? '▲' : '▼') + ' ' + Math.abs(result.change).toFixed(2) + '%' : ''"></span>
                                </p>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Chart & Metrics (Right) --}}
                <div class="lg:col-span-3 space-y-6">
                    {{-- Chart --}}
                    <div class="glass-card-static p-6 animate-fade-in-up delay-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-text-primary">Grafik Harga & Prediksi</h3>
                            <span x-show="result && !loading" class="text-xs text-text-muted" x-text="result ? result.coinName : ''"></span>
                        </div>

                        {{-- Empty State --}}
                        <div x-show="!result && !loading" class="h-80 flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-text-muted">Pilih koin dan klik "Jalankan Prediksi" untuk melihat grafik</p>
                            </div>
                        </div>

                        {{-- Loading State (Skeleton) --}}
                        <div x-show="loading" class="h-80 space-y-4">
                            <div class="flex items-end justify-between gap-2 h-64 px-2">
                                <x-ui.skeleton class="w-full h-32" />
                                <x-ui.skeleton class="w-full h-40" />
                                <x-ui.skeleton class="w-full h-36" />
                                <x-ui.skeleton class="w-full h-48" />
                                <x-ui.skeleton class="w-full h-44" />
                                <x-ui.skeleton class="w-full h-56" />
                                <x-ui.skeleton class="w-full h-52" />
                                <x-ui.skeleton class="w-full h-64" />
                            </div>
                            <div class="flex justify-between px-2">
                                <x-ui.skeleton class="w-12 h-3" />
                                <x-ui.skeleton class="w-12 h-3" />
                                <x-ui.skeleton class="w-12 h-3" />
                                <x-ui.skeleton class="w-12 h-3" />
                            </div>
                        </div>

                        {{-- Chart Canvas --}}
                        <div x-show="result && !loading" class="h-80">
                            <canvas id="prediction-chart"></canvas>
                        </div>
                    </div>

                    {{-- Metrics --}}
                    <div x-show="result || loading" x-transition class="grid grid-cols-1 sm:grid-cols-2 gap-4 animate-fade-in-up delay-300">
                        <div class="glass-card-static p-4">
                             <p class="text-xs text-text-muted mb-2">MAE</p>
                             <div x-show="loading"><x-ui.skeleton class="h-6 w-16" /></div>
                             <div x-show="!loading" class="text-lg font-bold" x-text="result ? result.mae.toFixed(4) : '-'"></div>
                        </div>
                        <div class="glass-card-static p-4">
                             <p class="text-xs text-text-muted mb-2">RMSE</p>
                             <div x-show="loading"><x-ui.skeleton class="h-6 w-16" /></div>
                             <div x-show="!loading" class="text-lg font-bold" x-text="result ? result.rmse.toFixed(4) : '-'"></div>
                        </div>
                    </div>

                    {{-- Price Alerts Section --}}
                    <div class="mt-8 animate-fade-in-up delay-400">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-text-primary">Notifikasi Harga</h3>
                        </div>

                        @auth
                            @if(session('success'))
                                <div class="mb-4 p-4 rounded-xl bg-[rgba(34,197,94,0.1)] border border-success text-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            {{-- Create Alert Form --}}
                            <div class="glass-card-static p-6 mb-6">
                                <h4 class="font-semibold text-text-primary mb-4">Buat Alert Baru</h4>
                                <form action="{{ route('notifications.store') }}" method="POST">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <select name="coin" class="form-input" required>
                                            <option value="">Pilih Koin</option>
                                            <option value="bitcoin" {{ request('coin') == 'bitcoin' ? 'selected' : '' }}>Bitcoin (BTC)</option>
                                            <option value="ethereum" {{ request('coin') == 'ethereum' ? 'selected' : '' }}>Ethereum (ETH)</option>
                                            <option value="solana" {{ request('coin') == 'solana' ? 'selected' : '' }}>Solana (SOL)</option>
                                            <option value="binancecoin" {{ request('coin') == 'binancecoin' ? 'selected' : '' }}>BNB (BNB)</option>
                                        </select>
                                        <select name="direction" class="form-input" required>
                                            <option value="above">Di atas</option>
                                            <option value="below">Di bawah</option>
                                        </select>
                                        <input type="number" name="target_price" class="form-input" placeholder="Target harga ($)" step="0.01" required>
                                        <button type="submit" class="gradient-btn text-sm">
                                            <span>Tambah</span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- Active Alerts --}}
                            <div class="space-y-3">
                                <h4 class="font-semibold text-text-primary mb-2">Alert Aktif</h4>
                                @if(isset($alerts) && $alerts->isEmpty())
                                    <div class="glass-card-static p-6 text-center text-text-muted text-sm">
                                        Belum ada alert aktif
                                    </div>
                                @elseif(isset($alerts))
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach($alerts as $alert)
                                            @php
                                                $coinMap = [
                                                    'bitcoin' => ['name' => 'Bitcoin', 'symbol' => 'BTC', 'icon' => 'https://assets.coingecko.com/coins/images/1/small/bitcoin.png'],
                                                    'ethereum' => ['name' => 'Ethereum', 'symbol' => 'ETH', 'icon' => 'https://assets.coingecko.com/coins/images/279/small/ethereum.png'],
                                                    'solana' => ['name' => 'Solana', 'symbol' => 'SOL', 'icon' => 'https://assets.coingecko.com/coins/images/4128/small/solana.png'],
                                                    'binancecoin' => ['name' => 'BNB', 'symbol' => 'BNB', 'icon' => 'https://assets.coingecko.com/coins/images/825/small/bnb-icon2_2x.png'],
                                                ];
                                                $info = $coinMap[$alert->coin] ?? ['name' => $alert->coin, 'symbol' => strtoupper(substr($alert->coin, 0, 3)), 'icon' => ''];
                                            @endphp
                                            <div id="alert-row-{{ $alert->id }}" class="glass-card-static p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 transition-all duration-500">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-bg-tertiary flex items-center justify-center overflow-hidden border border-border">
                                                        @if($info['icon'])
                                                            <img src="{{ $info['icon'] }}" alt="{{ $info['name'] }}" class="w-5 h-5">
                                                        @else
                                                            <span class="text-[10px] font-bold text-text-muted">{{ $info['symbol'] }}</span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-sm text-text-primary">{{ $info['name'] }}</p>
                                                        <p class="text-xs text-text-secondary">
                                                            {{ $alert->direction === 'above' ? 'Di atas' : 'Di bawah' }}
                                                            <span class="font-semibold text-text-primary">${{ number_format($alert->target_price, 2) }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <form action="{{ route('notifications.toggle', $alert) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="p-1.5 rounded-lg hover:bg-bg-tertiary transition-colors">
                                                            <svg class="w-4 h-4 {{ !$alert->is_triggered ? 'text-success' : 'text-text-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('notifications.destroy', $alert) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1.5 rounded-lg hover:bg-[rgba(239,68,68,0.1)] transition-colors text-text-muted hover:text-danger">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="glass-card-static p-6 text-center">
                                <p class="text-text-muted mb-3">Login untuk mengatur notifikasi harga.</p>
                                <a href="{{ route('login') }}" class="gradient-btn text-sm px-4 py-2 inline-flex"><span>Masuk</span></a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
    function predictionPage() {
        return {
            selectedCoin: new URLSearchParams(window.location.search).get('coin') || '',
            loading: false,
            result: null,
            chart: null,

            async runPrediction() {
                if (!this.selectedCoin) return;
                this.loading = true;
                
                // Jangan hapus result lama segera, agar skeleton muncul menggantikan elemen spesifik
                // ini memberikan transisi yang lebih mulus

                if (this.chart) {
                    this.chart.destroy();
                    this.chart = null;
                }

                try {
                    const response = await fetch('/api/predict', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ coin: this.selectedCoin })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.error || 'Terjadi kesalahan saat memprediksi');
                    }

                    const coinNames = {
                        bitcoin: 'Bitcoin (BTC)',
                        ethereum: 'Ethereum (ETH)',
                        solana: 'Solana (SOL)',
                        binancecoin: 'BNB (BNB)'
                    };

                    this.result = {
                        coinName: coinNames[this.selectedCoin],
                        currentPrice: data.current_price,
                        predictedPrice: data.predicted_price,
                        mae: data.mae,
                        rmse: data.rmse,
                        change: data.change,
                    };

                    this.$nextTick(() => {
                        this.chart = window.createPredictionChart(
                            'prediction-chart', 
                            data.labels, 
                            data.historical_prices, 
                            data.prediction_prices
                        );
                    });

                } catch (error) {
                    alert('Error: ' + error.message);
                } finally {
                    this.loading = false;
                }
            },

            init() {
                if (this.selectedCoin) {
                    this.runPrediction();
                }
            }
        };
    }
    </script>
    @endpush

</x-layout.app>

