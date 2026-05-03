<footer class="border-t border-border mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- Brand --}}
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo.svg') }}" alt="PREDCRYPT" class="w-8 h-8">
                    <span class="text-xl font-bold text-text-primary">PREDCRYPT</span>
                </div>
                <p class="text-text-secondary text-sm leading-relaxed max-w-md">
                    Sistem prediksi harga cryptocurrency berbasis web menggunakan metode Linear Regression. Dibangun untuk tujuan akademik dan pembelajaran.
                </p>
            </div>

            {{-- Navigation --}}
            <div>
                <h4 class="font-semibold text-text-primary mb-4">Navigasi</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-sm text-text-secondary hover:text-accent transition-colors">Beranda</a></li>
                    <li><a href="{{ route('market') }}" class="text-sm text-text-secondary hover:text-accent transition-colors">Market</a></li>
                    <li><a href="{{ route('prediction') }}" class="text-sm text-text-secondary hover:text-accent transition-colors">Prediksi</a></li>
                    <li><a href="{{ route('about') }}" class="text-sm text-text-secondary hover:text-accent transition-colors">Tentang</a></li>
                </ul>
            </div>

            {{-- Tech --}}
            <div>
                <h4 class="font-semibold text-text-primary mb-4">Teknologi</h4>
                <ul class="space-y-2">
                    <li class="text-sm text-text-secondary">Laravel 12</li>
                    <li class="text-sm text-text-secondary">Tailwind CSS v4</li>
                    <li class="text-sm text-text-secondary">Chart.js</li>
                    <li class="text-sm text-text-secondary">Python / Scikit-learn</li>
                </ul>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="mt-10 pt-6 border-t border-border flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-xs text-text-muted">&copy; {{ date('Y') }} PREDCRYPT. Dibuat untuk keperluan akademik.</p>
            <div class="flex items-center gap-4">
                <span class="text-xs text-text-muted">Contact Us: <span class="text-accent font-semibold">pradiptazulva@gmail.com</span></span>
            </div>
        </div>
    </div>
</footer>

