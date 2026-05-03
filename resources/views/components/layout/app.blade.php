<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta id="viewport-meta" name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <script>
        // Dinamis mengunci zoom hanya untuk smartphone (lebar < 768px)
        // Tablet (>= 768px) tetap bisa zoom sesuai permintaan
        function updateViewport() {
            const meta = document.getElementById('viewport-meta');
            if (window.innerWidth < 768) {
                meta.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover');
            } else {
                meta.setAttribute('content', 'width=device-width, initial-scale=1.0, viewport-fit=cover');
            }
        }
        updateViewport();
        window.addEventListener('resize', updateViewport);
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $metaDescription ?? 'PREDCRYPT - Sistem Prediksi Harga Cryptocurrency Berbasis Web Menggunakan Linear Regression' }}">
    <title>{{ $title ?? 'PREDCRYPT' }} | Prediksi Harga Crypto</title>
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col overflow-x-hidden">

    {{-- Navbar --}}
    <x-ui.navbar :active="$active ?? ''" />

    {{-- Main Content --}}
    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <x-ui.footer />

    {{-- Toast Container --}}
    <div id="toast-container" class="toast-container"></div>

    {{-- Page-specific scripts --}}
    @stack('scripts')

    {{-- Global Price Alert Checker --}}
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success') || session('error'))
                // Reset timer jika user baru saja melakukan aksi (tambah/hapus alert)
                sessionStorage.removeItem('last_alert_check');
            @endif

            // Cek apakah kita baru saja mengecek alert dalam 15 detik terakhir
            const lastCheck = sessionStorage.getItem('last_alert_check');
            const now = new Date().getTime();
            
            if (lastCheck && (now - lastCheck < 15000)) {
                return; // Lewati pengecekan jika belum 15 detik
            }

            // Tunggu 1.5 detik setelah load agar smooth
            setTimeout(() => {
                fetch('{{ route('api.check_alerts') }}', {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    sessionStorage.setItem('last_alert_check', now); // Catat waktu pengecekan

                    if (data.triggered && data.triggered.length > 0) {
                        data.triggered.forEach(alert => {
                            const coinName = alert.coin.toUpperCase();
                            const dirText = alert.direction === 'above' ? 'di atas' : 'di bawah';
                            const price = parseFloat(alert.current_price).toLocaleString('en-US', {minimumFractionDigits: 2});
                            
                            // Tampilkan popup notifikasi
                            window.showToast(
                                `🚨 ALERT ${coinName}: Harga telah ${dirText} target $${alert.target_price}! (Live: $${price})`,
                                'success'
                            );

                            // Animasi menghilang otomatis dari daftar (jika ada di halaman prediksi)
                            const alertRow = document.getElementById('alert-row-' + alert.id);
                            if (alertRow) {
                                alertRow.style.opacity = '0';
                                alertRow.style.transform = 'scale(0.9)';
                                setTimeout(() => alertRow.remove(), 500);
                            }
                        });
                    }
                })
                .catch(err => console.error('Error checking alerts:', err));
            }, 1500);
        });
    </script>
    @endauth
</body>
</html>

