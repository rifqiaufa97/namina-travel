@extends('admin.layouts.app')

@section('title', 'Dashboard Analitik')

@section('content')
<div class="space-y-6">
    <!-- Top Header & Date Filter Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Dashboard Analitik & Laporan</h1>
            <p class="text-xs text-gray-500 mt-1">Pantau statistik kunjungan, tren penjualan tiket, pendapatan agen, dan pertumbuhan pengguna.</p>
        </div>
        
        <!-- Filter Form -->
        <form id="dateFilterForm" class="flex flex-wrap items-center gap-2 bg-white p-2 rounded-2xl border border-gray-200 shadow-sm">
            @csrf
            <div class="flex items-center space-x-1.5 px-2 py-1 text-xs text-gray-500">
                <i class="far fa-calendar-alt text-blue-600"></i>
                <span class="font-medium">Periode:</span>
            </div>
            <input type="date" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                   class="text-xs border border-gray-200 rounded-xl px-3 py-1.5 focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-700 font-medium">
            <span class="text-xs text-gray-400">s/d</span>
            <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                   class="text-xs border border-gray-200 rounded-xl px-3 py-1.5 focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-700 font-medium">
            <button type="submit" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition active:scale-95 cursor-pointer">
                Terapkan
            </button>
        </form>
    </div>

    <!-- Stats 4-Card Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Card 1: Visitors -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block">Total Kunjungan</span>
                    <span class="text-2xl font-black text-gray-900 mt-1 block" id="totalVisitors">
                        {{ number_format($visitorStats['total_visitors']) }}
                    </span>
                    <span class="text-[11px] text-blue-600 font-medium mt-1 flex items-center">
                        <i class="fas fa-eye mr-1 text-[10px]"></i> Traffic Pageview
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Unique Visitors -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block">Pengunjung Unik</span>
                    <span class="text-2xl font-black text-gray-900 mt-1 block" id="uniqueVisitors">
                        {{ number_format($visitorStats['unique_visitors']) }}
                    </span>
                    <span class="text-[11px] text-emerald-600 font-medium mt-1 flex items-center">
                        <i class="fas fa-user-check mr-1 text-[10px]"></i> Pengguna Unik
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Revenue -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block">Total Pendapatan</span>
                    <span class="text-xl font-black text-purple-700 mt-1 block truncate" id="totalRevenue">
                        Rp {{ number_format($revenueStats['total_revenue'], 0, ',', '.') }}
                    </span>
                    <span class="text-[11px] text-purple-600 font-medium mt-1 flex items-center">
                        <i class="fas fa-wallet mr-1 text-[10px]"></i> Transaksi Berhasil
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Bookings -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block">Total Pemesanan</span>
                    <span class="text-2xl font-black text-gray-900 mt-1 block" id="totalBookings">
                        {{ number_format($bookingStats['total_bookings']) }}
                    </span>
                    <span class="text-[11px] text-amber-600 font-medium mt-1 flex items-center">
                        <i class="fas fa-shopping-bag mr-1 text-[10px]"></i> Tiket Terbit
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-suitcase-rolling"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Charts Row 1: Traffic & Revenue -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Visitor Traffic Chart -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 flex items-center">
                    <i class="fas fa-chart-line text-blue-600 mr-2"></i> Tren Trafik Kunjungan
                </h3>
                <span class="text-[11px] text-gray-400 font-medium">Harian</span>
            </div>
            <div class="h-72">
                <canvas id="visitorChart"></canvas>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 flex items-center">
                    <i class="fas fa-coins text-purple-600 mr-2"></i> Grafik Pendapatan
                </h3>
                <span class="text-[11px] text-gray-400 font-medium">Nominal (Rp)</span>
            </div>
            <div class="h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Row 2: Bookings & User Growth -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Bookings Chart -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 flex items-center">
                    <i class="fas fa-shopping-cart text-amber-600 mr-2"></i> Volume Pemesanan
                </h3>
                <span class="text-[11px] text-gray-400 font-medium">Jumlah Pesanan</span>
            </div>
            <div class="h-72">
                <canvas id="bookingChart"></canvas>
            </div>
        </div>

        <!-- Users Growth Chart -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 flex items-center">
                    <i class="fas fa-user-plus text-emerald-600 mr-2"></i> Pendaftaran Pengguna Baru
                </h3>
                <span class="text-[11px] text-gray-400 font-medium">Akun Baru</span>
            </div>
            <div class="h-72">
                <canvas id="userChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Breakdown Row: Popular Destinations & Visitor Geography -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Popular Destinations -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 flex items-center">
                    <i class="fas fa-fire text-amber-500 mr-2"></i> Destinasi Wisata Terpopuler
                </h3>
                <span class="text-[11px] text-gray-400 font-medium">Berdasarkan Total Order</span>
            </div>

            <div class="divide-y divide-gray-100 text-xs">
                @forelse($popularDestinations as $index => $destination)
                    <div class="py-3 flex items-center justify-between group">
                        <div class="flex items-center space-x-3">
                            <span class="w-6 h-6 rounded-lg flex items-center justify-center font-bold text-[11px] 
                                @if($index === 0) bg-amber-100 text-amber-800 border border-amber-300
                                @elseif($index === 1) bg-slate-200 text-slate-700
                                @elseif($index === 2) bg-amber-50 text-amber-900
                                @else bg-gray-100 text-gray-500 @endif">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <a href="{{ route('destinations.show', $destination->slug) }}" target="_blank" class="font-bold text-gray-900 hover:text-blue-600 transition block">
                                    {{ $destination->name }}
                                </a>
                                <span class="text-[11px] text-gray-400">{{ $destination->total_orders }} transaksi pemesanan</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-extrabold text-blue-600">
                                Rp {{ number_format($destination->total_revenue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-400">
                        <p class="text-xs">Belum ada data pemesanan destinasi pada periode ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Visitor Geography -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 flex items-center">
                    <i class="fas fa-globe-asia text-indigo-600 mr-2"></i> Asal Lokasi Pengunjung
                </h3>
                <span class="text-[11px] text-gray-400 font-medium">Distribusi Wilayah</span>
            </div>

            <div class="space-y-3.5 text-xs">
                @forelse($visitorLocations as $location)
                    @php
                        $percentage = $visitorStats['total_visitors'] > 0 ? ($location->visits / $visitorStats['total_visitors']) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-gray-800">
                                {{ $location->country }}@if($location->city), {{ $location->city }}@endif
                            </span>
                            <span class="text-gray-500 font-mono text-[11px]">
                                {{ number_format($location->visits) }} kunjungan ({{ number_format($percentage, 1) }}%)
                            </span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-400">
                        <p class="text-xs">Belum ada data geolokasi pengunjung tercatat.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('dateFilterForm');
    
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const submitBtn = filterForm.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Memuat...';
            
            fetch('{{ route("admin.analytics.filter") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    start_date: startDate,
                    end_date: endDate
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.visitorStats) {
                    document.getElementById('totalVisitors').textContent = formatNumber(data.visitorStats.total_visitors);
                    document.getElementById('uniqueVisitors').textContent = formatNumber(data.visitorStats.unique_visitors);
                    updateVisitorChart(data.visitorStats.chart_data);
                }
                if (data.revenueStats) {
                    document.getElementById('totalRevenue').textContent = 'Rp ' + formatNumber(data.revenueStats.total_revenue);
                    updateRevenueChart(data.revenueStats.chart_data);
                }
                if (data.bookingStats) {
                    document.getElementById('totalBookings').textContent = formatNumber(data.bookingStats.total_bookings);
                    updateBookingChart(data.bookingStats.chart_data);
                }
                if (data.userStats) {
                    updateUserChart(data.userStats.chart_data);
                }
                
                const url = new URL(window.location);
                url.searchParams.set('start_date', startDate);
                url.searchParams.set('end_date', endDate);
                window.history.pushState({}, '', url);
            })
            .catch(error => {
                console.error('Error fetching analytics:', error);
                alert('Gagal memperbarui data analitik. Silakan coba lagi.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Terapkan';
            });
        });
    }
    
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
    
    // Initialize Charts with Modern Aesthetic Palettes
    initializeCharts();
});

function initializeCharts() {
    // 1. Visitor Chart
    const visitorCanvas = document.getElementById('visitorChart');
    if (visitorCanvas) {
        const visitorCtx = visitorCanvas.getContext('2d');
        window.visitorChart = new Chart(visitorCtx, {
            type: 'line',
            data: {
                labels: @json($visitorStats['chart_data']['labels']),
                datasets: [{
                    label: 'Total Kunjungan',
                    data: @json($visitorStats['chart_data']['datasets'][0]['data']),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 6
                }, {
                    label: 'Pengunjung Unik',
                    data: @json($visitorStats['chart_data']['datasets'][1]['data']),
                    borderColor: '#059669',
                    backgroundColor: 'rgba(5, 150, 105, 0.08)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, font: { family: 'Plus Jakarta Sans', size: 11 } } },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0, font: { family: 'Plus Jakarta Sans', size: 11 } } },
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } } }
                }
            }
        });
    }
    
    // 2. Revenue Chart
    const revenueCanvas = document.getElementById('revenueChart');
    if (revenueCanvas) {
        const revenueCtx = revenueCanvas.getContext('2d');
        window.revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: @json($revenueStats['chart_data']['labels']),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json($revenueStats['chart_data']['datasets'][0]['data']),
                    backgroundColor: 'rgba(124, 58, 237, 0.85)',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function(v) { return 'Rp ' + (v >= 1000000 ? (v/1000000) + 'jt' : v.toLocaleString('id-ID')); },
                            font: { family: 'Plus Jakarta Sans', size: 11 }
                        }
                    },
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } } }
                }
            }
        });
    }

    // 3. Booking Chart
    const bookingCanvas = document.getElementById('bookingChart');
    if (bookingCanvas) {
        const bookingCtx = bookingCanvas.getContext('2d');
        window.bookingChart = new Chart(bookingCtx, {
            type: 'line',
            data: {
                labels: @json($bookingStats['chart_data']['labels']),
                datasets: [{
                    label: 'Pemesanan',
                    data: @json($bookingStats['chart_data']['datasets'][0]['data']),
                    borderColor: '#d97706',
                    backgroundColor: 'rgba(217, 119, 6, 0.08)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0, font: { family: 'Plus Jakarta Sans', size: 11 } } },
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } } }
                }
            }
        });
    }

    // 4. User Chart
    const userCanvas = document.getElementById('userChart');
    if (userCanvas) {
        const userCtx = userCanvas.getContext('2d');
        window.userChart = new Chart(userCtx, {
            type: 'bar',
            data: {
                labels: @json($userStats['chart_data']['labels']),
                datasets: [{
                    label: 'Pengguna Baru',
                    data: @json($userStats['chart_data']['datasets'][0]['data']),
                    backgroundColor: 'rgba(5, 150, 105, 0.85)',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0, font: { family: 'Plus Jakarta Sans', size: 11 } } },
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } } }
                }
            }
        });
    }
}

function updateVisitorChart(data) {
    if (!window.visitorChart) return;
    window.visitorChart.data.labels = data.labels;
    window.visitorChart.data.datasets[0].data = data.datasets[0].data;
    window.visitorChart.data.datasets[1].data = data.datasets[1].data;
    window.visitorChart.update();
}

function updateRevenueChart(data) {
    if (!window.revenueChart) return;
    window.revenueChart.data.labels = data.labels;
    window.revenueChart.data.datasets[0].data = data.datasets[0].data;
    window.revenueChart.update();
}

function updateBookingChart(data) {
    if (!window.bookingChart) return;
    window.bookingChart.data.labels = data.labels;
    window.bookingChart.data.datasets[0].data = data.datasets[0].data;
    window.bookingChart.update();
}

function updateUserChart(data) {
    if (!window.userChart) return;
    window.userChart.data.labels = data.labels;
    window.userChart.data.datasets[0].data = data.datasets[0].data;
    window.userChart.update();
}
</script>
@endpush
@endsection
