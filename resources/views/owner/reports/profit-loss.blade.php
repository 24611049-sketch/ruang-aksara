@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">📊 Laporan Laba Rugi</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-chart-line text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Laba Bersih Bulan Ini</p>
                    <p class="text-2xl font-bold {{ $currentMonth['net_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format(abs($currentMonth['net_profit']), 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Margin: {{ number_format($currentMonth['profit_margin'], 1) }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-money-bill-wave text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Pendapatan Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($currentMonth['revenue'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-calendar-year text-purple-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Laba Bersih Tahun Ini</p>
                    <p class="text-2xl font-bold {{ $yearNetProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format(abs($yearNetProfit), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-full">
                    <i class="fas fa-receipt text-orange-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Pengeluaran Tahun Ini</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($yearExpenses, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Grafik Laba Rugi 6 Bulan Terakhir</h2>
        <canvas id="profitChart" height="80"></canvas>
    </div>

    <!-- Detail Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4">Detail Bulanan</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Pendapatan</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">HPP</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Laba Kotor</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Biaya Operasional</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Laba Bersih</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Margin</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($monthlyData as $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $data['month'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                Rp {{ number_format($data['revenue'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">
                                (Rp {{ number_format($data['hpp'], 0, ',', '.') }})
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-blue-600">
                                Rp {{ number_format($data['gross_profit'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">
                                (Rp {{ number_format($data['operating_expenses'], 0, ',', '.') }})
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold {{ $data['net_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format(abs($data['net_profit']), 0, ',', '.') }}
                                {{ $data['net_profit'] < 0 ? '(Rugi)' : '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right {{ $data['profit_margin'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($data['profit_margin'], 1) }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Disclaimer -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Catatan Metodologi:</strong><br>
                    • HPP (Harga Pokok Penjualan) dihitung dari purchase_price buku. Jika purchase_price tidak tersedia, diestimasi 60% dari harga jual.<br>
                    • Biaya operasional diambil dari data pengeluaran yang tercatat di sistem.<br>
                    • Laba Bersih = Pendapatan - HPP - Biaya Operasional<br>
                    • Margin Laba = (Laba Bersih / Pendapatan) × 100%
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('profitChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($monthlyData, 'month_short')) !!},
        datasets: [
            {
                label: 'Pendapatan (Rp)',
                data: {!! json_encode(array_column($monthlyData, 'revenue')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1
            },
            {
                label: 'Laba Bersih (Rp)',
                data: {!! json_encode(array_column($monthlyData, 'net_profit')) !!},
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.1
            },
            {
                label: 'Biaya Operasional (Rp)',
                data: {!! json_encode(array_column($monthlyData, 'operating_expenses')) !!},
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.1
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += new Intl.NumberFormat('id-ID', { 
                            style: 'currency', 
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(context.parsed.y);
                        return label;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('id-ID', { 
                            style: 'currency', 
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            notation: 'compact'
                        }).format(value);
                    }
                }
            }
        }
    }
});
</script>
@endsection
