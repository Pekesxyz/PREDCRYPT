<x-layout.app :active="'history'" :title="'Riwayat Prediksi'">

    <section class="pt-28 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="mb-10 animate-fade-in-up">
                <h1 class="text-3xl font-bold text-text-primary"><span class="gradient-text">Riwayat</span> Prediksi</h1>
                <p class="mt-2 text-text-secondary">Lihat semua prediksi yang pernah Anda lakukan</p>
            </div>

            {{-- Filter --}}
            <div class="flex justify-center sm:justify-start mb-8 animate-fade-in-up delay-100" x-data="{ filterCoin: '{{ $currentFilter ?? 'all' }}' }">
                <form action="{{ route('history') }}" method="GET" id="historyFilterForm" class="w-full max-w-xs">
                    <select name="coin" x-model="filterCoin" @change="$event.target.form.submit()" class="form-input">
                        <option value="all">Semua Koin</option>
                        <option value="bitcoin">Bitcoin (BTC)</option>
                        <option value="ethereum">Ethereum (ETH)</option>
                        <option value="solana">Solana (SOL)</option>
                        <option value="binancecoin">BNB (BNB)</option>
                    </select>
                </form>
            </div>

            {{-- Responsive Table/List --}}
            <div class="animate-fade-in-up delay-200">
                {{-- Desktop Table --}}
                <div class="hidden sm:block glass-card-static overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-border">
                                <th class="text-left px-6 py-4 text-xs font-semibold text-text-muted uppercase">Tanggal</th>
                                <th class="text-left px-6 py-4 text-xs font-semibold text-text-muted uppercase">Koin</th>
                                <th class="text-right px-6 py-4 text-xs font-semibold text-text-muted uppercase">Harga Saat Itu</th>
                                <th class="text-right px-6 py-4 text-xs font-semibold text-text-muted uppercase">Prediksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($histories as $item)
                            <tr class="border-b border-border hover:bg-bg-tertiary transition-colors">
                                <td class="px-6 py-4 text-sm text-text-secondary">{{ $item->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-text-primary">{{ strtoupper($item->coin) }}</td>
                                <td class="px-6 py-4 text-sm text-right text-text-primary">${{ number_format($item->current_price, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-right font-semibold gradient-text">${{ number_format($item->predicted_price, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="text-5xl mb-4 opacity-30">📭</div>
                                    <p class="text-text-muted">Belum ada riwayat prediksi</p>
                                    <a href="{{ route('prediction') }}" class="gradient-btn text-sm mt-4 inline-block"><span>Mulai Prediksi</span></a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile List --}}
                <div class="sm:hidden space-y-4">
                    @forelse($histories as $item)
                    <div class="glass-card-static p-5">
                        <div class="flex justify-between items-start mb-4 pb-4 border-b border-border">
                            <div>
                                <p class="text-sm font-bold text-text-primary">{{ strtoupper($item->coin) }}</p>
                                <p class="text-xs text-text-muted">{{ $item->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-text-muted">Harga Saat Itu</p>
                                <p class="text-sm font-semibold text-text-primary">${{ number_format($item->current_price, 2) }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-text-muted">Prediksi</p>
                            <p class="text-sm font-bold gradient-text">${{ number_format($item->predicted_price, 2) }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="glass-card-static p-10 text-center">
                        <div class="text-4xl mb-3 opacity-30">📭</div>
                        <p class="text-text-muted">Belum ada riwayat prediksi</p>
                    </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($histories->hasPages())
                    <div class="mt-12 px-2 animate-fade-in-up delay-300">
                        {{ $histories->links('vendor.pagination.custom') }}
                    </div>
                @endif
            </div>
        </div>
    </section>

</x-layout.app>

