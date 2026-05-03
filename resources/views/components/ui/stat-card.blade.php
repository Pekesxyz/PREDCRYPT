@props(['label', 'value', 'icon' => null, 'color' => 'cyan', 'prefix' => '', 'suffix' => ''])

@php
    $colorClasses = match($color) {
        'purple' => 'from-accent-purple to-accent-purple-light',
        'green' => 'from-success to-emerald-400',
        'red' => 'from-danger to-red-400',
        'yellow' => 'from-warning to-amber-400',
        default => 'from-accent-cyan to-accent-cyan-light',
    };
@endphp

<div class="glass-card-static p-6">
    <div class="flex items-center gap-3 mb-3">
        @if($icon)
            <div class="w-10 h-10 rounded-xl bg-linear-to-br {{ $colorClasses }} flex items-center justify-center text-white">
                {!! $icon !!}
            </div>
        @endif
        <span class="text-sm text-text-secondary">{{ $label }}</span>
    </div>
    <p class="text-2xl font-bold text-text-primary">
        {{ $prefix }}<span class="stat-value" {{ $attributes->whereStartsWith('x-') }}>{{ $value }}</span>{{ $suffix }}
    </p>
</div>

