<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Presensi - Ruang Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <a href="javascript:history.back()" class="flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Presensi
                        </a>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Riwayat Presensi</h1>
                    <div class="text-sm text-gray-600">
                        {{ Auth::user()->name }}
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto py-6 px-4">
            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select id="month" name="month" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Bulan</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select id="year" name="year" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Tahun</option>
                            @for($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="button" id="filterBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        <button type="button" id="resetBtn" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                            <i class="fas fa-refresh mr-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Attendance List -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Daftar Presensi</h2>
                    <div class="text-sm text-gray-500">
                        Total: {{ $attendances->total() }} records
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d F Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('l') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-mono">
                                        {{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-mono">
                                        {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'ontime' => 'bg-green-100 text-green-800',
                                            'late' => 'bg-yellow-100 text-yellow-800', 
                                            'absent' => 'bg-red-100 text-red-800'
                                        ];
                                        $statusLabels = [
                                            'ontime' => 'Tepat Waktu',
                                            'late' => 'Terlambat',
                                            'absent' => 'Tidak Hadir'
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$attendance->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$attendance->status] ?? $attendance->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate relative group cursor-help">
                                        {{ $attendance->notes ?: '-' }}
                                        @if($attendance->notes && strlen($attendance->notes) > 30)
                                        <div class="absolute invisible group-hover:visible bg-gray-800 text-white p-3 rounded text-sm max-w-md z-10 bottom-full left-0 mb-2 shadow-lg">
                                            {{ $attendance->notes }}
                                            <div class="absolute top-full left-4 border-8 border-transparent border-t-gray-800"></div>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-history text-5xl text-gray-300 mb-4"></i>
                                    <p class="text-lg">Belum ada riwayat presensi.</p>
                                    <p class="text-sm mt-2">Mulai dengan melakukan presensi harian terlebih dahulu.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($attendances->hasPages())
                <div class="px-6 py-4 border-t bg-gray-50">
                    {{ $attendances->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Filter functionality
        document.getElementById('filterBtn').addEventListener('click', function() {
            const month = document.getElementById('month').value;
            const year = document.getElementById('year').value;
            
            let url = new URL(window.location.href);
            
            if (month) {
                url.searchParams.set('month', month);
            } else {
                url.searchParams.delete('month');
            }
            
            if (year) {
                url.searchParams.set('year', year);
            } else {
                url.searchParams.delete('year');
            }
            
            window.location.href = url.toString();
        });

        // Reset filters
        document.getElementById('resetBtn').addEventListener('click', function() {
            window.location.href = window.location.pathname;
        });

        // Apply current filter values from URL
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const month = urlParams.get('month');
            const year = urlParams.get('year');
            
            if (month) document.getElementById('month').value = month;
            if (year) document.getElementById('year').value = year;
        });
    </script>
</body>
</html>