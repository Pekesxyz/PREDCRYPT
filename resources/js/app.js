import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

// Make Alpine & Chart globally available
window.Alpine = Alpine;
window.Chart = Chart;

// ============================================
// Alpine.js Init
// ============================================
Alpine.start();

// ============================================
// Theme Toggle (Dark/Light) & Chart Colors
// ============================================
window.updateChartTheme = function() {
    const isLight = document.body.classList.contains('light-mode');
    const textColor = isLight ? '#475569' : '#94a3b8'; // text-secondary
    const gridColor = isLight ? 'rgba(0, 0, 0, 0.08)' : 'rgba(255, 255, 255, 0.04)';
    const tooltipBg = isLight ? 'rgba(255, 255, 255, 0.95)' : 'rgba(17, 24, 39, 0.9)';
    const tooltipTitle = isLight ? '#0f172a' : '#f1f5f9';
    const tooltipBody = isLight ? '#475569' : '#94a3b8';
    const tooltipBorder = isLight ? 'rgba(0, 0, 0, 0.1)' : 'rgba(255, 255, 255, 0.1)';

    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;
    
    if (Chart.defaults.plugins && Chart.defaults.plugins.tooltip) {
        Chart.defaults.plugins.tooltip.backgroundColor = tooltipBg;
        Chart.defaults.plugins.tooltip.titleColor = tooltipTitle;
        Chart.defaults.plugins.tooltip.bodyColor = tooltipBody;
        Chart.defaults.plugins.tooltip.borderColor = tooltipBorder;
        Chart.defaults.plugins.tooltip.borderWidth = 1;
    }

    // Update all existing charts dynamically
    for (let id in Chart.instances) {
        const chart = Chart.instances[id];
        if (chart.options.scales && chart.options.scales.x) {
            if (chart.options.scales.y.grid) chart.options.scales.y.grid.color = gridColor;
            if (chart.options.scales.y.ticks) chart.options.scales.y.ticks.color = textColor;
            if (chart.options.scales.x.ticks) chart.options.scales.x.ticks.color = textColor;
        }
        if (chart.options.plugins && chart.options.plugins.tooltip) {
            chart.options.plugins.tooltip.backgroundColor = tooltipBg;
            chart.options.plugins.tooltip.titleColor = tooltipTitle;
            chart.options.plugins.tooltip.bodyColor = tooltipBody;
            chart.options.plugins.tooltip.borderColor = tooltipBorder;
        }
        chart.update('none'); // Update without animation
    }
};

window.toggleTheme = function() {
    document.body.classList.toggle('light-mode');
    const isLight = document.body.classList.contains('light-mode');
    localStorage.setItem('predcrypt-theme', isLight ? 'light' : 'dark');
    window.updateChartTheme();
};

// Apply saved theme on load
(function() {
    const savedTheme = localStorage.getItem('predcrypt-theme');
    if (savedTheme === 'light') {
        document.body.classList.add('light-mode');
    }
    // Update chart defaults to match the current theme
    window.updateChartTheme();
})();

// ============================================
// Navbar Scroll Effect
// ============================================
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('navbar');
    if (navbar) {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }
}, { passive: true });

// ============================================
// Scroll Reveal Animation
// ============================================
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const scrollObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('revealed');
            scrollObserver.unobserve(entry.target);
        }
    });
}, observerOptions);

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.scroll-reveal').forEach(el => {
        scrollObserver.observe(el);
    });
});

// ============================================
// Count Up Animation
// ============================================
window.countUp = function(element, target, duration = 1500) {
    let start = 0;
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = start + (target - start) * eased;

        if (typeof target === 'number' && target % 1 !== 0) {
            element.textContent = current.toFixed(2);
        } else {
            element.textContent = Math.floor(current).toLocaleString('id-ID');
        }

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
};

// ============================================
// Chart.js Default Theme
// ============================================
Chart.defaults.color = '#94a3b8';
Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.06)';
Chart.defaults.font.family = "'Inter', sans-serif";

window.createPredictionChart = function(canvasId, labels, historicalData, predictionData) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;

    const isLight = document.body.classList.contains('light-mode');

    // Buat gradient fill untuk garis historis (Cyan → Transparan)
    const chartCtx = ctx.getContext('2d');
    const gradientHistorical = chartCtx.createLinearGradient(0, 0, 0, ctx.offsetHeight || 320);
    gradientHistorical.addColorStop(0, 'rgba(6, 182, 212, 0.35)');
    gradientHistorical.addColorStop(0.6, 'rgba(6, 182, 212, 0.08)');
    gradientHistorical.addColorStop(1, 'rgba(6, 182, 212, 0.0)');

    // Buat gradient fill untuk garis prediksi (Ungu → Transparan)
    const gradientPrediction = chartCtx.createLinearGradient(0, 0, 0, ctx.offsetHeight || 320);
    gradientPrediction.addColorStop(0, 'rgba(139, 92, 246, 0.3)');
    gradientPrediction.addColorStop(0.6, 'rgba(139, 92, 246, 0.06)');
    gradientPrediction.addColorStop(1, 'rgba(139, 92, 246, 0.0)');

    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Harga Historis',
                    data: historicalData,
                    borderColor: '#06b6d4',
                    backgroundColor: gradientHistorical,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.2,       // Lebih tajam seperti grafik harga asli
                    pointRadius: 0,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#06b6d4',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2,
                },
                {
                    label: 'Prediksi',
                    data: predictionData,
                    borderColor: '#8b5cf6',
                    backgroundColor: gradientPrediction,
                    borderWidth: 2.5,
                    borderDash: [6, 4],
                    fill: true,
                    tension: 0.2,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#8b5cf6',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    position: 'top',
                    onClick: function(e, legendItem, legend) {
                        const index = legendItem.datasetIndex;
                        const ci = legend.chart;
                        
                        // Cek apakah dataset yang di-klik adalah satu-satunya yang saat ini terlihat
                        let isOnlyVisible = true;
                        ci.data.datasets.forEach((d, i) => {
                            if (i !== index && ci.isDatasetVisible(i)) {
                                isOnlyVisible = false;
                            }
                        });

                        if (isOnlyVisible) {
                            // Jika sudah sendirian dan di-klik lagi, tampilkan semua (reset)
                            ci.data.datasets.forEach((d, i) => {
                                ci.show(i);
                            });
                        } else {
                            // Jika tidak, isolasi dataset ini (sembunyikan yang lain)
                            ci.data.datasets.forEach((d, i) => {
                                if (i === index) {
                                    ci.show(i);
                                } else {
                                    ci.hide(i);
                                }
                            });
                        }
                        // Hapus ci.update() agar animasi show/hide bawaan Chart.js berjalan mulus
                    },
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        generateLabels: function(chart) {
                            const isLight = document.body.classList.contains('light-mode');
                            const activeText = isLight ? '#0f172a' : '#f1f5f9';
                            const inactiveText = isLight ? 'rgba(71, 85, 105, 0.5)' : 'rgba(148, 163, 184, 0.5)';
                            
                            const labels = Chart.defaults.plugins.legend.labels.generateLabels(chart);
                            labels.forEach(label => {
                                const dataset = chart.data.datasets[label.datasetIndex];
                                
                                if (label.hidden) {
                                    // Status Inactive (Sembunyi)
                                    label.hidden = false; // Hilangkan efek coret (strikethrough)
                                    label.fillStyle = 'transparent'; // Lingkaran kosong (hollow)
                                    label.strokeStyle = dataset.borderColor;
                                    label.lineWidth = 2;
                                    label.fontColor = inactiveText; // Redupkan teks sesuai tema
                                } else {
                                    // Status Active (Tampil)
                                    label.fillStyle = dataset.borderColor; // Lingkaran terisi penuh
                                    label.strokeStyle = dataset.borderColor;
                                    label.lineWidth = 0;
                                    label.fontColor = activeText; // Teks terang/gelap sesuai tema
                                }
                            });
                            return labels;
                        }
                    }
                },
                tooltip: {
                    backgroundColor: isLight ? 'rgba(255, 255, 255, 0.95)' : 'rgba(17, 24, 39, 0.9)',
                    titleColor: isLight ? '#0f172a' : '#f1f5f9',
                    bodyColor: isLight ? '#475569' : '#94a3b8',
                    borderColor: isLight ? 'rgba(0, 0, 0, 0.1)' : 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': $' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { 
                        maxTicksLimit: 8,
                        color: isLight ? '#475569' : '#94a3b8'
                    }
                },
                y: {
                    grid: { color: isLight ? 'rgba(0, 0, 0, 0.08)' : 'rgba(255, 255, 255, 0.04)' },
                    ticks: {
                        color: isLight ? '#475569' : '#94a3b8',
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
};

window.createSparkline = function(canvasId, data, color = '#06b6d4') {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;

    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map((_, i) => i),
            datasets: [{
                data: data,
                borderColor: color,
                borderWidth: 1.5,
                fill: false,
                tension: 0.4,
                pointRadius: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            scales: {
                x: { display: false },
                y: { display: false }
            }
        }
    });
};

// ============================================
// Toast Notification System
// ============================================
window.showToast = function(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = "glass-card-static p-4 mb-3 animate-fade-in-up flex items-center gap-3 shadow-[0_10px_30px_rgba(0,0,0,0.5)] border-l-4 " + (type === 'success' ? 'border-[var(--color-success)] text-[var(--color-text-primary)]' : 'border-[var(--color-danger)] text-[var(--color-danger)]');

    const icon = type === 'success' ? '<svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>' : '<svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
    toast.innerHTML = `<span class="shrink-0">${icon}</span><p class="text-sm font-medium">${message}</p>`;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => {
            if (toast.parentNode) toast.parentNode.removeChild(toast);
        }, 300);
    }, 5000);
};
