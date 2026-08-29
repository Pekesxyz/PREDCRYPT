<x-layout.app :active="'market'" :title="'Market'" :metaDescription="'Pantau harga cryptocurrency terkini - Bitcoin, Ethereum, Dogecoin, BNB dengan data real-time.'">

    <section class="pt-24 sm:pt-28 pb-12 sm:pb-20 px-4 sm:px-6 lg:px-8" x-data="marketPage()">
        <div class="max-w-7xl mx-auto">
            {{-- Header --}}
            <div class="mb-10 animate-fade-in-up">
                <h1 class="text-3xl font-bold text-text-primary"><span class="gradient-text">Market</span>
                    Cryptocurrency</h1>
                <p class="mt-2 text-text-secondary">Pantau harga dan performa koin secara real-time</p>
            </div>

            {{-- Search & Filter --}}
            <div class="flex flex-col sm:flex-row gap-4 mb-8 animate-fade-in-up delay-100">
                <div class="flex-1 relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-text-muted" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" x-model="search" placeholder="Cari koin..."
                        class="form-input form-input-icon-left" id="market-search">
                </div>
                <div class="flex flex-wrap gap-2">
                    <button @click="filter = 'all'"
                        :class="filter === 'all' ? 'gradient-btn text-white' : 'glass-card-static text-text-secondary hover:text-accent-cyan'"
                        class="px-4 py-2 rounded-xl text-sm font-medium transition-all flex items-center justify-center">
                        <span>Semua</span>
                    </button>
                    <button @click="filter = 'gainers'"
                        :class="filter === 'gainers' ? 'gradient-btn text-white' : 'glass-card-static text-text-secondary hover:text-success'"
                        class="px-4 py-2 rounded-xl text-sm font-medium transition-all flex items-center justify-center">
                        <span>📈 Naik</span>
                    </button>
                    <button @click="filter = 'losers'"
                        :class="filter === 'losers' ? 'gradient-btn text-white' : 'glass-card-static text-text-secondary hover:text-danger'"
                        class="px-4 py-2 rounded-xl text-sm font-medium transition-all flex items-center justify-center">
                        <span>📉 Turun</span>
                    </button>
                    @auth
                        <button @click="filter = 'favorites'"
                            :class="filter === 'favorites' ? 'gradient-btn text-white' : 'glass-card-static text-text-secondary hover:text-warning'"
                            class="px-4 py-2 rounded-xl text-sm font-medium transition-all flex items-center justify-center">
                            <span>⭐ Favorit</span>
                        </button>
                    @endauth
                </div>
            </div>

            {{-- Coin Table (Desktop) --}}
            <div class="hidden md:block glass-card-static overflow-hidden animate-fade-in-up delay-200">
                <table class="w-full" id="market-table">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="text-center px-6 py-4 text-xs font-semibold text-text-muted uppercase tracking-wider"></th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-text-muted uppercase tracking-wider">Koin</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-text-muted uppercase tracking-wider">Harga</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-text-muted uppercase tracking-wider">24j Perubahan</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-text-muted uppercase tracking-wider">Market Cap</th>
                            <th class="text-center px-6 py-4 text-xs font-semibold text-text-muted uppercase tracking-wider">Chart</th>
                            <th class="text-center px-6 py-4 text-xs font-semibold text-text-muted uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(coin, index) in filteredCoins" :key="coin.symbol">
                            <tr class="border-b border-border hover:bg-bg-tertiary transition-colors cursor-pointer"
                                @click="window.location = '/prediction?coin=' + coin.id">
                                <td class="px-6 py-4 text-center text-sm text-text-muted">
                                    @auth
                                        <button @click.stop="toggleFavorite(coin.id)"
                                            class="text-xl transition-transform hover:scale-110"
                                            :class="isFavorite(coin.id) ? 'opacity-100' : 'opacity-30 hover:opacity-100'">
                                            <span x-text="isFavorite(coin.id) ? '⭐' : '☆'"></span>
                                        </button>
                                    @else
                                        <span x-text="index + 1"></span>
                                    @endauth
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img :src="coin.image" :alt="coin.name" class="w-8 h-8 rounded-full shadow-sm"
                                            @@error="$el.src = 'https://assets.coingecko.com/coins/images/1/small/bitcoin.png'">
                                        <div>
                                            <p class="font-semibold text-text-primary" x-text="coin.name"></p>
                                            <p class="text-xs text-text-muted uppercase" x-text="coin.symbol"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-left font-semibold text-text-primary"
                                    x-text="'$' + coin.price.toLocaleString('en-US', {minimumFractionDigits: 2})"></td>
                                <td class="px-6 py-4 text-left">
                                    <span :class="coin.change >= 0 ? 'price-up' : 'price-down'"
                                        class="font-medium text-sm">
                                        <span x-text="coin.change >= 0 ? '▲' : '▼'"></span>
                                        <span x-text="Math.abs(coin.change).toFixed(2) + '%'"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-left text-sm text-text-secondary"
                                    x-text="'$' + coin.marketCap"></td>
                                <td class="px-6 py-4">
                                    <div class="w-24 h-8 mx-auto">
                                        <canvas :id="'spark-' + coin.symbol"></canvas>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a :href="'/prediction?coin=' + coin.id"
                                        class="gradient-btn text-xs px-4 py-2"><span>Prediksi</span></a>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            {{-- Coin Cards (Mobile) --}}
            <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-4">
                <template x-for="(coin, index) in filteredCoins" :key="coin.symbol">
                    <div class="glass-card p-5 cursor-pointer" @click="window.location = '/prediction?coin=' + coin.id">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                @auth
                                    <button @click.stop="toggleFavorite(coin.id)"
                                        class="text-xl transition-transform hover:scale-110"
                                        :class="isFavorite(coin.id) ? 'opacity-100' : 'opacity-30 hover:opacity-100'">
                                        <span x-text="isFavorite(coin.id) ? '⭐' : '☆'"></span>
                                    </button>
                                @endauth
                                <img :src="coin.image" :alt="coin.name" class="w-10 h-10 rounded-full shadow-sm"
                                    @@error="$el.src = 'https://assets.coingecko.com/coins/images/1/small/bitcoin.png'">
                                <div>
                                    <p class="font-semibold text-text-primary" x-text="coin.name"></p>
                                    <p class="text-xs text-text-muted uppercase" x-text="coin.symbol"></p>
                                </div>
                            </div>
                            <span :class="coin.change >= 0 ? 'price-up' : 'price-down'" class="text-sm font-medium">
                                <span
                                    x-text="(coin.change >= 0 ? '▲ ' : '▼ ') + Math.abs(coin.change).toFixed(2) + '%'"></span>
                            </span>
                        </div>
                        <p class="text-xl font-bold text-text-primary"
                            x-text="'$' + coin.price.toLocaleString('en-US', {minimumFractionDigits: 2})"></p>
                    </div>
                </template>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            function marketPage() {
                return {
                    search: '',
                    filter: 'all',
                    coins: {!! $initialCoins ?? '[]' !!},
                    favorites: {!! $favorites ?? '[]' !!},
                    get filteredCoins() {
                        let result = this.coins;
                        if (this.search) {
                            const q = this.search.toLowerCase();
                            result = result.filter(c => c.name.toLowerCase().includes(q) || c.symbol.toLowerCase().includes(q));
                        }
                        if (this.filter === 'gainers') result = result.filter(c => c.change >= 0);
                        if (this.filter === 'losers') result = result.filter(c => c.change < 0);
                        if (this.filter === 'favorites') result = result.filter(c => this.isFavorite(c.id));
                        return result;
                    },
                    isFavorite(id) {
                        return this.favorites.includes(id);
                    },
                    async toggleFavorite(id) {
                        try {
                            const response = await fetch('{{ route('preferences.toggle') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ coin: id })
                            });

                            if (response.ok) {
                                if (this.isFavorite(id)) {
                                    this.favorites = this.favorites.filter(c => c !== id);
                                    // Jika sedang filter favorites dan bintang dicabut, bisa dibiarkan hilang atau tetap
                                } else {
                                    this.favorites.push(id);
                                }
                            } else {
                                // Kalo belum login
                                window.location.href = '{{ route('login') }}';
                            }
                        } catch (error) {
                            console.error('Error toggling favorite', error);
                        }
                    },
                    init() {
                        this.$nextTick(() => {
                            this.coins.forEach(coin => {
                                const color = coin.change >= 0 ? '#22c55e' : '#ef4444';
                                if (coin.sparkline) {
                                    window.createSparkline('spark-' + coin.symbol, coin.sparkline, color);
                                }
                            });
                        });
                    }
                };
            }
        </script>
    @endpush

</x-layout.app>