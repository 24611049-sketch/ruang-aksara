@extends('layouts.app')

@section('content')
<style>
    .histogram-shell {
        --hist-axis-offset: 3rem;
        position: relative;
        padding-left: 3.5rem;
        padding-bottom: calc(var(--hist-axis-offset) + 0.6rem);
    }

    .animated-histogram {
        display: flex;
        align-items: flex-end;
        gap: 0.75rem;
        height: 390px;
        padding: 1.25rem 1rem 0 0;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
        background-image: linear-gradient(
            to top,
            rgba(229, 231, 235, 0.65) 1px,
            transparent 1px
        );
        background-size: 100% 52px;
        position: relative;
        overflow: visible;
    }

    .animated-histogram::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        border-left: 2px solid #d1d5db;
        border-bottom: 2px solid #d1d5db;
        pointer-events: none;
    }

    .histogram-y-scale {
        position: absolute;
        left: 0;
        top: 0.75rem;
        bottom: 0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: flex-end;
        padding-right: 0.75rem;
        padding-bottom: 0.35rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
    }

    .histogram-y-scale span:last-child {
        transform: translateY(-1.9rem);
    }

    .histogram-bar-group {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        gap: 0.35rem;
        min-width: 32px;
        position: relative;
        padding-bottom: 0;
        height: 100%;
        align-self: stretch;
    }

    .histogram-bar-wrapper {
        width: 100%;
        max-width: 32px;
        height: 100%;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        position: relative;
    }

    .histogram-bar {
        width: 70%;
        height: calc(var(--bar-height, 0) * 1%);
        border-radius: 0;
        background: linear-gradient(180deg, #34d399 0%, #059669 100%);
        position: relative;
        overflow: hidden;
        transition: height var(--hist-duration, 900ms) cubic-bezier(0.2, 0.8, 0.2, 1), transform 180ms, box-shadow 180ms;
        transform-origin: center bottom;
        box-shadow: inset 0 -6px 12px rgba(0, 0, 0, 0.1);
    }

    .histogram-bar-group:nth-child(odd) .histogram-bar {
        background: linear-gradient(180deg, #60a5fa 0%, #1d4ed8 100%);
    }

    .histogram-bar::after {
        content: '';
        position: absolute;
        inset: 6px;
        border-radius: inherit;
        background: rgba(255, 255, 255, 0.25);
        opacity: 0.7;
    }

    .histogram-count {
        position: absolute;
        bottom: calc(var(--bar-height, 50) * 1% + 0.35rem);
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.8rem;
        font-weight: 600;
        color: #111827;
        pointer-events: none;
    }

    .histogram-label {
        position: absolute;
        bottom: calc(-1 * (var(--hist-axis-offset) - 0.4rem) - 0.4rem);
        left: 50%;
        transform: translateX(-50%);
        width: 140%;
        max-width: 5rem;
        font-size: 0.75rem;
        text-align: center;
        color: #4b5563;
        line-height: 1.15;
        letter-spacing: 0.01em;
        text-transform: uppercase;
        word-break: break-word;
        hyphens: auto;
        white-space: normal;
    }

    /* Removed continuous keyframes: histogram now uses a single grow transition triggered by JS */

    @media (max-width: 768px) {
        .histogram-shell {
            padding-left: 0;
            --hist-axis-offset: 3.4rem;
            padding-bottom: calc(var(--hist-axis-offset) + 0.8rem);
        }

        .histogram-y-scale {
            position: static;
            flex-direction: row;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .animated-histogram {
            flex-wrap: wrap;
            height: auto;
            min-height: 260px;
            padding-top: 3rem;
            padding-bottom: 0;
        }

        .histogram-bar-group {
            flex: 0 1 40%;
            padding-bottom: 0;
        }
    }
</style>
<div class="content-wrapper min-h-screen">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header Card -->
        <div class="mb-6">
            <div class="card p-6 flex items-center gap-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-md bg-green-600 text-white text-xl">📊</div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Owner</h1>
                    <p class="text-sm text-gray-600">Terakhir diperbarui: {{ now()->translatedFormat('l, d F Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="mb-6">
            <div class="flex gap-2 flex-wrap bg-white bg-opacity-80 backdrop-blur rounded-lg p-2 shadow-md">
                <button class="tab-button active px-6 py-3 rounded-lg font-semibold transition text-gray-700 hover:bg-gray-200" data-tab="dashboard">
                    <i class="fas fa-chart-line mr-2"></i>Dashboard
                </button>
                <button class="tab-button px-6 py-3 rounded-lg font-semibold transition text-gray-700 hover:bg-gray-200" data-tab="penjualan">
                    <i class="fas fa-shopping-cart mr-2"></i>Penjualan
                </button>
                <button class="tab-button px-6 py-3 rounded-lg font-semibold transition text-gray-700 hover:bg-gray-200" data-tab="peminjaman">
                    <i class="fas fa-book-open mr-2"></i>Peminjaman
                </button>
                <!-- Presensi tab removed to avoid duplication with navbar -->
                <button class="tab-button px-6 py-3 rounded-lg font-semibold transition text-gray-700 hover:bg-gray-200" data-tab="pengguna">
                    <i class="fas fa-users mr-2"></i>Pengguna
                </button>
            </div>
        </div>

        <!-- TAB 1: DASHBOARD -->
        <div id="dashboard" class="tab-content">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Pendapatan -->
                <div class="card p-6 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold uppercase">💰 Total Pendapatan</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                            <p class="text-green-600 text-sm mt-2"><i class="fas fa-check-circle"></i> {{ $totalBooksSold }} buku terjual</p>
                        </div>
                    </div>
                </div>

                <!-- Total Pengguna -->
                <div class="card p-6 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold uppercase">👥 Total Pengguna</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $totalUsers }}</h3>
                            <p class="text-blue-600 text-sm mt-2"><i class="fas fa-users"></i> Terdaftar di sistem</p>
                        </div>
                    </div>
                </div>

                <!-- Buku Terjual -->
                 <div class="card p-6 hover:shadow-lg transition cursor-pointer" onclick="openBooksSoldModal()">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold uppercase">📚 Buku Terjual</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $totalBooksSold }}</h3>
                            <p class="text-blue-600 text-sm mt-2"><i class="fas fa-box"></i> Unit terjual</p>
                        </div>
                    </div>
                </div>

                <!-- Pesanan Pending -->
                <div class="card p-6 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold uppercase">⏳ Pesanan Pending</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $pendingOrders }}</h3>
                            <p class="text-orange-600 text-sm mt-2"><i class="fas fa-clock"></i> Menunggu konfirmasi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2 card p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">📈 Grafik Pendapatan 6 Bulan Terakhir</h3>
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
                <div class="card p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">🏷️ Kategori Buku</h3>
                    <canvas id="categoryChart" height="80"></canvas>
                </div>
            </div>

            <!-- Table -->
            <div class="card p-6 overflow-x-auto">
                <h3 class="text-xl font-bold text-gray-900 mb-6">📋 Ringkasan Laporan Bulanan</h3>
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Bulan</th>
                            <th class="px-4 py-3 text-left font-semibold">Pendapatan</th>
                            <th class="px-4 py-3 text-left font-semibold">Buku Terjual</th>
                            <th class="px-4 py-3 text-left font-semibold">Pengguna Baru</th>
                            <th class="px-4 py-3 text-left font-semibold">Total Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyReports as $report)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $report['month'] }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($report['revenue'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $report['books_sold'] }}</td>
                            <td class="px-4 py-3">{{ $report['new_users'] }}</td>
                            <td class="px-4 py-3">{{ $report['orders'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 2: PENJUALAN -->
        <div id="penjualan" class="tab-content hidden">
            <!-- Quick Action Button -->
            <div class="mb-6">
                <a href="{{ route('owner.reports.profit-loss') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition">
                    <i class="fas fa-chart-pie mr-2"></i>Lihat Laporan Laba Rugi Lengkap
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white bg-opacity-90 backdrop-blur rounded-lg shadow-md p-6">
                    <p class="text-gray-600 text-sm font-semibold uppercase">💰 Total Pendapatan</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white bg-opacity-90 backdrop-blur rounded-lg shadow-md p-6">
                    <p class="text-gray-600 text-sm font-semibold uppercase">📦 Total Terjual</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalBooksSold }} unit</h3>
                </div>
            </div>

            <!-- Chart panel for sales removed -->

            <!-- Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Best Sellers -->
                <div class="bg-white bg-opacity-90 backdrop-blur rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">🏆 5 Buku Terlaris</h3>
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @forelse($bestsellers as $order)
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-sm">{{ $order->book->judul ?? 'Buku Tidak Ditemukan' }}</span>
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $order->total_sold }} unit</span>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-8">Belum ada data penjualan</p>
                        @endforelse
                    </div>
                </div>

                <!-- Order Status (ringkasan tabel) -->
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">📌 Status Pesanan</h3>
                    @php
                        $statusList = $orderStatusData->pluck('status')->toArray() ?? [];
                        $statusCounts = $orderStatusData->pluck('total')->toArray() ?? [];
                        $orderTotal = array_sum($statusCounts);
                    @endphp
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 border-b-2 border-gray-300">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                                    <th class="px-4 py-3 text-right font-semibold">Jumlah</th>
                                    <th class="px-4 py-3 text-right font-semibold">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statusList as $i => $status)
                                @php $count = $statusCounts[$i] ?? 0; $pct = $orderTotal > 0 ? number_format(($count / $orderTotal) * 100, 1) : '0.0'; @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $status }}</td>
                                    <td class="px-4 py-3 text-right">{{ $count }}</td>
                                    <td class="px-4 py-3 text-right">{{ $pct }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 border-t-2 border-gray-200 font-semibold">
                                <tr>
                                    <td class="px-4 py-3">TOTAL</td>
                                    <td class="px-4 py-3 text-right">{{ $orderTotal }}</td>
                                    <td class="px-4 py-3 text-right">100%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Low Stock -->
                <div class="bg-white bg-opacity-90 backdrop-blur rounded-lg shadow-md p-6 border-t-4 border-orange-400">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">⚠️ Stok Menipis</h3>
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @forelse($lowStockBooks as $book)
                        <div class="flex justify-between items-center pb-3 border-b {{ $book->stok == 0 ? 'bg-red-50' : 'bg-yellow-50' }} p-2 rounded">
                            <span class="text-sm">{{ $book->judul }}</span>
                            <span class="font-bold {{ $book->stok == 0 ? 'text-red-600' : 'text-orange-600' }}">{{ $book->stok }}</span>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-8">✅ Semua stok aman</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: PEMINJAMAN -->
        <div id="peminjaman" class="tab-content hidden">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white bg-opacity-90 backdrop-blur rounded-lg shadow-md p-6 border-l-4 border-blue-600">
                    <p class="text-gray-600 text-sm font-semibold uppercase">📖 Peminjaman Aktif</p>
                    <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ $activeLoans }}</h3>
                </div>
                <div class="bg-white bg-opacity-90 backdrop-blur rounded-lg shadow-md p-6 border-l-4 border-green-600">
                    <p class="text-gray-600 text-sm font-semibold uppercase">✅ Dikembalikan</p>
                    <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $returnedLoans }}</h3>
                </div>
                <div class="bg-white bg-opacity-90 backdrop-blur rounded-lg shadow-md p-6 border-l-4 border-red-600">
                    <p class="text-gray-600 text-sm font-semibold uppercase">🔴 Overdue</p>
                    <h3 class="text-3xl font-bold text-red-600 mt-2">{{ $overdueLoans }}</h3>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white bg-opacity-90 backdrop-blur rounded-lg shadow-md p-6 overflow-x-auto">
                <h3 class="text-xl font-bold text-gray-900 mb-6">📊 10 Peminjam Terbanyak</h3>
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold">Email</th>
                            <th class="px-4 py-3 text-left font-semibold">Total Pinjam</th>
                            <th class="px-4 py-3 text-left font-semibold">Pinjam Terakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loanByUsers as $loan)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $loan->user ? $loan->user->name : 'User Tidak Ditemukan' }}</td>
                            <td class="px-4 py-3">{{ $loan->user ? $loan->user->email : '-' }}</td>
                            <td class="px-4 py-3"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $loan->total_loans }}</span></td>
                            <td class="px-4 py-3">{{ $loan->last_loan_date ? \Carbon\Carbon::parse($loan->last_loan_date)->translatedFormat('d F Y') : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada data peminjaman</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 4: PRESENSI -->
        <!-- Presensi tab removed from reports to avoid duplication with navbar -->

        <!-- TAB 5: PENGGUNA -->
        <div id="pengguna" class="tab-content hidden">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white bg-opacity-90 backdrop-blur rounded-lg shadow-md p-6">
                    <p class="text-gray-600 text-sm font-semibold uppercase">👨‍💼 Admin</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $adminUsers }}</h3>
                </div>
                <div class="bg-white bg-opacity-90 backdrop-blur rounded-lg shadow-md p-6">
                    <p class="text-gray-600 text-sm font-semibold uppercase">👑 Owner</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $ownerUsers }}</h3>
                </div>
                <div class="bg-white bg-opacity-90 backdrop-blur rounded-lg shadow-md p-6">
                    <p class="text-gray-600 text-sm font-semibold uppercase">👥 Pengguna Biasa</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $normalUsers }}</h3>
                </div>
            </div>

            <!-- Chart & Info -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white bg-opacity-90 backdrop-blur rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">📊 Ringkasan Distribusi Pengguna</h3>

                    <div class="relative user-chart-wrapper p-2">
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 z-20 flex flex-col gap-2">
                            <button type="button" class="user-chart-scroll left hidden w-10 h-10 bg-white bg-opacity-90 rounded-full shadow flex items-center justify-center" aria-label="Scroll left">◀</button>
                            <button type="button" class="user-chart-scroll right hidden w-10 h-10 bg-white bg-opacity-90 rounded-full shadow flex items-center justify-center" aria-label="Scroll right">▶</button>
                        </div>

                        <div class="overflow-x-auto">
                            <canvas id="userChart" height="340"></canvas>
                        </div>
                    </div>

                    @php
                        $provinceCollection = collect($provinceList ?? [])->map(function ($label, $index) use ($provinceCounts) {
                            return [
                                'label' => $label,
                                'count' => (int) ($provinceCounts[$index] ?? 0),
                            ];
                        })->filter(function ($item) {
                            return filled($item['label']) && ($item['count'] ?? 0) > 0;
                        })->sortByDesc('count')->take(10)->values();

                        $maxTopProvince = $provinceCollection->pluck('count')->max() ?? 0;
                        $scaleBaseline = max(1, $maxTopProvince);
                        $axisTopLabel = $maxTopProvince > 0 ? $maxTopProvince : 0;
                        $axisMidLabel = $maxTopProvince > 0 ? (int) ceil($maxTopProvince / 2) : 0;
                    @endphp

                    <div class="mt-6">
                        <div class="flex items-center justify-between flex-wrap gap-2 mb-3">
                            <h4 class="text-base font-semibold text-gray-900">10 Provinsi Pendaftar Terbanyak</h4>
                            <p class="text-sm text-gray-500">Provinsi dengan jumlah pengguna paling tinggi</p>
                        </div>
                        @if($provinceCollection->isEmpty())
                            <p class="text-sm text-gray-500 text-center py-6">Belum ada data distribusi pengguna</p>
                        @else
                            <div class="histogram-shell">
                                <div class="histogram-y-scale">
                                    <span>{{ number_format($axisTopLabel) }}</span>
                                    <span>{{ number_format($axisMidLabel) }}</span>
                                    <span>0</span>
                                </div>
                                <div class="animated-histogram">
                                    @foreach($provinceCollection as $index => $province)
                                        @php
                                            $barHeight = max(12, round(($province['count'] / $scaleBaseline) * 100));
                                            $animationDelay = number_format($index * 0.08, 2, '.', '') . 's';
                                        @endphp
                                        <div class="histogram-bar-group" style="--bar-delay: {{ $animationDelay }}; --bar-height: 0;" data-target="{{ $barHeight }}">
                                            <div class="histogram-bar-wrapper">
                                                <div class="histogram-bar"></div>
                                                <div class="histogram-count">{{ number_format($province['count']) }}</div>
                                            </div>
                                            <div class="histogram-label">{{ $province['label'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
                <div class="bg-white bg-opacity-90 backdrop-blur rounded-lg shadow-md p-6 flex flex-col justify-center text-center">
                    <p class="text-gray-600 text-sm font-semibold uppercase mb-4">📈 Total Pengguna (non-admin/owner)</p>
                    <h2 class="text-5xl font-bold text-blue-600">{{ $totalUsersNonAdmin }}</h2>
                    <p class="text-gray-600 text-sm mt-4">Pengguna biasa terdaftar di sistem</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Tab Navigation
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Remove active class from buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'bg-green-600', 'text-white');
                btn.classList.add('text-gray-700', 'hover:bg-gray-200');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.remove('hidden');
            
            // Add active class to button
            this.classList.add('active', 'bg-green-600', 'text-white');
            this.classList.remove('text-gray-700', 'hover:bg-gray-200');
            // initialize pengguna charts only when pengguna tab is activated (first time)
            if (tabName === 'pengguna' && typeof window.initPenggunaCharts === 'function') {
                window.initPenggunaCharts();
            }
        });
    });

    // Set first button as active
    document.querySelector('.tab-button').classList.add('bg-green-600', 'text-white');
    document.querySelector('.tab-button').classList.remove('text-gray-700', 'hover:bg-gray-200');

    // Initialize Charts
    document.addEventListener('DOMContentLoaded', function() {
        const chartConfig = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        };

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json($revenueChartLabels),
                    datasets: [
                        {
                            label: 'Pendapatan (Rp)',
                            data: @json($revenueChartData),
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22, 163, 74, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3
                        },
                        {
                            label: 'Biaya Operasional (Rp)',
                            data: @json($operationalChartData ?? []),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.08)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2,
                            borderDash: [4,2]
                        }
                    ]
                },
                options: chartConfig
            });
        }

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx) {
            new Chart(categoryCtx, {
                type: 'bar',
                data: {
                    labels: @json($categoryLabels),
                    datasets: [{
                        label: 'Jumlah Buku',
                        data: @json($categoryCounts),
                        backgroundColor: '#3b82f6',
                        borderColor: '#2563eb',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    ...chartConfig,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 10
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // Sales Chart removed (canvas + init removed). No JS changes needed because existence is checked before use.

        // Order status is rendered as a summary table in the markup; no Chart.js init required.

        // User chart + histogram initialization for 'pengguna' tab.
        // We define an initializer and only run it when the tab becomes visible to satisfy "animate only when visible".
        window.penggunaChartsInitialized = false;
        window.initPenggunaCharts = function() {
            if (window.penggunaChartsInitialized) return;
            window.penggunaChartsInitialized = true;

            const userCtx = document.getElementById('userChart');
            const wrapper = document.querySelector('.user-chart-wrapper');
            const btnLeft = document.querySelector('.user-chart-scroll.left');
            const btnRight = document.querySelector('.user-chart-scroll.right');
            if (userCtx && wrapper) {
                const provinceLabels = @json($provinceList ?? []);
                const provinceData = @json($provinceCounts ?? []);
                const labelCount = provinceLabels.length;

                // For horizontal bars: set canvas height to fit number of labels, enable vertical scroll on wrapper
                userCtx.style.width = '';
                wrapper.style.overflowX = 'hidden';
                wrapper.style.overflowY = 'auto';
                const threshold = 12;
                if (labelCount > threshold) {
                    const pxPerLabel = 36;
                    const desiredHeight = Math.min(Math.max(300, labelCount * pxPerLabel), 1400);
                    userCtx.style.height = desiredHeight + 'px';
                    wrapper.style.maxHeight = '600px';
                } else {
                    userCtx.style.height = Math.max(300, labelCount * 36) + 'px';
                    wrapper.style.maxHeight = '';
                }

                // Compute data (preserve ordering and include zeros)
                const rawMax = Math.max(...provinceData, 0);
                const targetTicks = 8;
                const scaleFactor = Math.max(1, Math.ceil(rawMax / targetTicks));
                const scaledData = provinceData.map(v => Math.round((v || 0) / scaleFactor));

                // Build dataset colors
                const datasetColors = provinceLabels.map((_, i) => i % 2 === 0 ? '#3b82f6' : '#10b981');
                const datasetBorders = provinceLabels.map((_, i) => i % 2 === 0 ? '#2563eb' : '#059669');

                // Create chart with horizontal bars (indexAxis: 'y') and entry animation
                const initialDuration = 1000; // within 800-1200ms
                const ci = new Chart(userCtx, {
                    type: 'bar',
                    data: {
                        labels: provinceLabels,
                        datasets: [{
                            label: 'Jumlah Pengguna',
                            data: scaledData,
                            backgroundColor: datasetColors,
                            borderColor: datasetBorders,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        ...chartConfig,
                        indexAxis: 'y',
                        maintainAspectRatio: false,
                        animation: {
                            duration: initialDuration,
                            easing: 'easeOutQuart'
                        },
                        scales: {
                            x: { beginAtZero: true, ticks: { precision: 0 } },
                            y: { beginAtZero: true }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });

                // Disable animations permanently after first run
                ci.options.animation.onComplete = function() {
                    if (!userCtx.dataset.animated) {
                        userCtx.dataset.animated = '1';
                        ci.options.animation = false;
                        try { ci.update({ duration: 0 }); } catch (e) {}
                    }
                };

                userCtx._chartInstance = ci;

                // Hover visual: use element options modification + draw (no data re-fetch/update)
                function brightenHex(hex, pct) {
                    if (!hex) return hex;
                    hex = hex.replace('#', '');
                    if (hex.length === 3) hex = hex.split('').map(c => c + c).join('');
                    const num = parseInt(hex, 16);
                    let r = (num >> 16) & 0xFF;
                    let g = (num >> 8) & 0xFF;
                    let b = num & 0xFF;
                    r = Math.min(255, Math.round(r + (255 - r) * pct));
                    g = Math.min(255, Math.round(g + (255 - g) * pct));
                    b = Math.min(255, Math.round(b + (255 - b) * pct));
                    return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
                }
                const hoverColors = datasetColors.map(c => brightenHex(c, 0.08));
                let prevHover = null;
                userCtx.addEventListener('mousemove', function(evt) {
                    const chart = userCtx._chartInstance;
                    if (!chart) return;
                    const points = chart.getElementsAtEventForMode(evt, 'nearest', {intersect: true}, false);
                    if (points && points.length) {
                        const p = points[0];
                        const idx = p.index;
                        if (prevHover === idx) return;
                        if (prevHover !== null) {
                            const prevEl = chart.getDatasetMeta(0).data[prevHover];
                            if (prevEl) {
                                prevEl.options.backgroundColor = datasetColors[prevHover];
                                prevEl.options.borderWidth = 1;
                            }
                        }
                        const el = chart.getDatasetMeta(0).data[idx];
                        if (el) {
                            el.options.backgroundColor = hoverColors[idx] || hoverColors[idx % hoverColors.length];
                            el.options.borderWidth = 2;
                        }
                        chart.draw();
                        prevHover = idx;
                    } else {
                        if (prevHover !== null) {
                            const chart = userCtx._chartInstance;
                            const prevEl = chart.getDatasetMeta(0).data[prevHover];
                            if (prevEl) {
                                prevEl.options.backgroundColor = datasetColors[prevHover];
                                prevEl.options.borderWidth = 1;
                            }
                            chart.draw();
                            prevHover = null;
                        }
                    }
                });
                userCtx.addEventListener('mouseleave', function() {
                    if (prevHover !== null && userCtx._chartInstance) {
                        const chart = userCtx._chartInstance;
                        const prevEl = chart.getDatasetMeta(0).data[prevHover];
                        if (prevEl) {
                            prevEl.options.backgroundColor = datasetColors[prevHover];
                            prevEl.options.borderWidth = 1;
                        }
                        chart.draw();
                        prevHover = null;
                    }
                });

                // Button visibility logic
                function updateButtons() {
                    const isOverflowing = wrapper.scrollWidth > wrapper.clientWidth + 4;
                    if (isOverflowing) {
                        btnLeft.classList.remove('hidden');
                        btnRight.classList.remove('hidden');
                    } else {
                        btnLeft.classList.add('hidden');
                        btnRight.classList.add('hidden');
                    }
                }
                updateButtons();
                btnLeft.onclick = () => wrapper.scrollBy({ left: -Math.floor(wrapper.clientWidth * 0.6), behavior: 'smooth' });
                btnRight.onclick = () => wrapper.scrollBy({ left: Math.floor(wrapper.clientWidth * 0.6), behavior: 'smooth' });

                // On resize, only resize chart without re-triggering animations
                window.addEventListener('resize', function() {
                    if (userCtx._chartInstance) {
                        userCtx._chartInstance.options.animation = false;
                        try { userCtx._chartInstance.resize(); } catch (e) {}
                    }
                    updateButtons();
                });
            }

            // Histogram: animate once when pengguna tab visible
            (function animateHistogramOnce() {
                const shell = document.getElementById('pengguna');
                if (!shell || shell.classList.contains('hidden')) return;
                if (document.body.dataset.histogramAnimated) return;
                const groups = document.querySelectorAll('.histogram-bar-group[data-target]');
                if (!groups || groups.length === 0) return;
                requestAnimationFrame(function() {
                    setTimeout(function() {
                        groups.forEach(group => {
                            const target = Number(group.dataset.target || 0);
                            const delayRaw = getComputedStyle(group).getPropertyValue('--bar-delay') || '0s';
                            const delayMs = Math.max(0, parseFloat(delayRaw) * 1000);
                            const durationMs = 900; // within 800-1000ms
                            group.style.setProperty('--hist-duration', durationMs + 'ms');
                            setTimeout(() => {
                                group.style.setProperty('--bar-height', String(target));
                            }, delayMs);
                        });
                        document.body.dataset.histogramAnimated = '1';
                    }, 60);
                });
            })();
        };

        // If pengguna tab is visible on initial load, initialize charts after paint
        document.addEventListener('readystatechange', function() {
            if (document.readyState === 'complete') {
                const penggunaTab = document.getElementById('pengguna');
                if (penggunaTab && !penggunaTab.classList.contains('hidden')) {
                    requestAnimationFrame(function() { setTimeout(function() { if (typeof window.initPenggunaCharts === 'function') window.initPenggunaCharts(); }, 60); });
                }
            }
        });
    });
</script>

<!-- Modal Buku Terjual Detail -->
<div id="booksSoldModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 text-white flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">📚 Detail Buku Terjual</h2>
                <p class="text-blue-100 text-sm mt-1">Ringkasan penjualan per buku</p>
            </div>
            <button onclick="closeBooksSoldModal()" class="text-2xl hover:text-blue-200 transition">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            @if($booksSoldDetail->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b-2 border-gray-300 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">No</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Judul Buku</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Penulis</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Qty Terjual</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Jumlah Pesanan</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Harga Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booksSoldDetail as $index => $item)
                        <tr class="border-b hover:bg-blue-50 transition">
                            <td class="px-4 py-3 font-semibold text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900">{{ $item->book->judul ?? $item->book->title ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">Kategori: {{ $item->book->kategori ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $item->book->penulis ?? $item->book->author ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                    {{ $item->total_sold }} pcs
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-700">{{ $item->order_count }} pesanan</td>
                            <td class="px-4 py-3 text-right font-semibold text-green-600">
                                Rp {{ number_format($item->book->harga ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 border-t-2 border-gray-300 font-bold">
                        <tr>
                            <td colspan="3" class="px-4 py-3">TOTAL</td>
                            <td class="px-4 py-3 text-right">{{ $totalBooksSold }} pcs</td>
                            <td class="px-4 py-3 text-right">{{ $booksSoldDetail->sum('order_count') }} pesanan</td>
                            <td class="px-4 py-3 text-right"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-300 text-4xl mb-2"></i>
                <p class="text-gray-500">Belum ada data penjualan buku</p>
            </div>
            @endif
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-3 border-t text-right">
            <button onclick="closeBooksSoldModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openBooksSoldModal() {
        document.getElementById('booksSoldModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeBooksSoldModal() {
        document.getElementById('booksSoldModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('booksSoldModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeBooksSoldModal();
        }
    });

    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeBooksSoldModal();
        }
    });
</script>

<!-- rely on global layout and Tailwind utilities for consistent styling -->
@endsection
