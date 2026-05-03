@props(['name', 'symbol', 'price', 'change', 'image' => null, 'sparkline' => null, 'index' => 0])

<div class="glass-card p-6 cursor-pointer animate-fade-in-up delay-{{ $index * 100 }}" onclick="window.location='{{ route('prediction', ['coin' => strtolower($symbol)]) }}'">
    {{-- Header & Price Container --}}
    <div class="flex flex-row sm:flex-col justify-between sm:justify-start items-center sm:items-start gap-4">
        {{-- Coin Info --}}
        <div class="flex items-center gap-3 sm:mb-4">
            @if($image)
                <img src="{{ $image }}" alt="{{ $name }}" class="w-10 h-10 rounded-full shadow-sm">
            @else
                <div class="w-10 h-10 rounded-full bg-linear-to-br from-accent-cyan to-accent-purple flex items-center justify-center text-sm font-bold text-white shadow-sm">
                    {{ strtoupper(substr($symbol, 0, 2)) }}
                </div>
            @endif
            <div>
                <h3 class="font-semibold text-text-primary leading-tight">{{ $name }}</h3>
                <span class="text-xs text-text-muted uppercase">{{ $symbol }}</span>
            </div>
        </div>

        {{-- Price & Change --}}
        <div class="text-right sm:text-left sm:w-full">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-1 sm:gap-0">
                <div>
                    <p class="text-lg sm:text-2xl font-bold text-text-primary">
                        $<span id="card-price-{{ strtolower($symbol) }}">{{ number_format($price, 2) }}</span>
                    </p>
                    <p id="card-change-container-{{ strtolower($symbol) }}" class="text-xs sm:text-sm font-medium {{ $change >= 0 ? 'price-up' : 'price-down' }}">
                        <span id="card-icon-{{ strtolower($symbol) }}">{{ $change >= 0 ? '▲' : '▼' }}</span>
                        <span id="card-change-{{ strtolower($symbol) }}">{{ number_format(abs($change), 2) }}</span>%
                    </p>
                </div>
                
                {{-- Sparkline (Desktop Only in this row) --}}
                @if($sparkline)
                <div class="hidden sm:block w-24 h-10">
                    <canvas id="sparkline-{{ strtolower($symbol) }}"></canvas>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Sparkline (Mobile Only below) --}}
    @if($sparkline)
    <div class="sm:hidden mt-4 w-full h-10">
        <canvas id="sparkline-mobile-{{ strtolower($symbol) }}"></canvas>
    </div>
    @endif
</div>

