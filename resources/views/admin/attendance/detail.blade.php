@extends('layouts.app')

@section('title', 'Detail Presensi - Ruang Aksara')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-user-check mr-3 text-green-600"></i>
            Detail Presensi
        </h1>
        <p class="text-gray-600 mt-2">Lihat status presensi admin dan owner</p>
    </div>

    <!-- Filter & Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <!-- Total Admin -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-600">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm font-semibold uppercase">👥 Total Admin/Owner</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalStaff }}</h3>
                </div>
                <i class="fas fa-users text-blue-600 text-3xl opacity-20"></i>
            </div>
        </div>

        <!-- Hadir -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-600">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm font-semibold uppercase">✅ Hadir</p>
                    <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $hadir }}</h3>
                </div>
                <i class="fas fa-check-circle text-green-600 text-3xl opacity-20"></i>
            </div>
        </div>

        <!-- Terlambat -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-600">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm font-semibold uppercase">⏰ Terlambat</p>
                    <h3 class="text-3xl font-bold text-orange-600 mt-2">{{ $terlambat }}</h3>
                </div>
                <i class="fas fa-clock text-orange-600 text-3xl opacity-20"></i>
            </div>
        </div>

        <!-- Izin -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-600">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm font-semibold uppercase">📋 Izin</p>
                    <h3 class="text-3xl font-bold text-yellow-600 mt-2">{{ $izin }}</h3>
                </div>
                <i class="fas fa-clipboard text-yellow-600 text-3xl opacity-20"></i>
            </div>
        </div>

        <!-- Alfa -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-600">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm font-semibold uppercase">❌ Alfa</p>
                    <h3 class="text-3xl font-bold text-red-600 mt-2">{{ $alfa }}</h3>
                </div>
                <i class="fas fa-times-circle text-red-600 text-3xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Month Filter -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex gap-4 flex-1">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2"></i>Tahun
                    </label>
                    <select name="year" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        @for($y = 2020; $y <= now()->year; $y++)
                            <option value="{{ $y }}" {{ (request()->input('year') ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Bulan
                    </label>
                    <select name="month_num" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="01" {{ (request()->input('month_num') ?? now()->format('m')) == '01' ? 'selected' : '' }}>Januari</option>
                        <option value="02" {{ (request()->input('month_num') ?? now()->format('m')) == '02' ? 'selected' : '' }}>Februari</option>
                        <option value="03" {{ (request()->input('month_num') ?? now()->format('m')) == '03' ? 'selected' : '' }}>Maret</option>
                        <option value="04" {{ (request()->input('month_num') ?? now()->format('m')) == '04' ? 'selected' : '' }}>April</option>
                        <option value="05" {{ (request()->input('month_num') ?? now()->format('m')) == '05' ? 'selected' : '' }}>Mei</option>
                        <option value="06" {{ (request()->input('month_num') ?? now()->format('m')) == '06' ? 'selected' : '' }}>Juni</option>
                        <option value="07" {{ (request()->input('month_num') ?? now()->format('m')) == '07' ? 'selected' : '' }}>Juli</option>
                        <option value="08" {{ (request()->input('month_num') ?? now()->format('m')) == '08' ? 'selected' : '' }}>Agustus</option>
                        <option value="09" {{ (request()->input('month_num') ?? now()->format('m')) == '09' ? 'selected' : '' }}>September</option>
                        <option value="10" {{ (request()->input('month_num') ?? now()->format('m')) == '10' ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ (request()->input('month_num') ?? now()->format('m')) == '11' ? 'selected' : '' }}>November</option>
                        <option value="12" {{ (request()->input('month_num') ?? now()->format('m')) == '12' ? 'selected' : '' }}>Desember</option>
                    </select>
                </div>
            </div>
            <a href="{{ \Illuminate\Support\Facades\Route::has('owner.attendance.detail') ? route('owner.attendance.detail') : (\Illuminate\Support\Facades\Route::has('admin.attendance.detail') ? route('admin.attendance.detail') : url('/owner/attendance/detail')) }}" class="bg-gray-400 hover:bg-gray-500 text-white font-semibold px-6 py-2 rounded-lg transition flex items-center h-fit">
                <i class="fas fa-redo mr-2"></i>Reset
            </a>
        </form>
    </div>

    <!-- Detail Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-list mr-2 text-green-600"></i>
                Data Presensi - {{ $monthCarbon->translatedFormat('F Y') }}
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-800 sticky left-0 bg-gray-100 z-10">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-800 sticky left-12 bg-gray-100 z-10">Nama</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-800 bg-green-100">Hadir</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-800 bg-orange-100">Terlambat</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-800 bg-yellow-100">Izin</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-800 bg-red-100">Alfa</th>
                        @for($day = 1; $day <= $totalWorkDays; $day++)
                            <th class="px-2 py-3 text-center font-semibold text-gray-800 min-w-[40px] border-l">{{ $day }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceData as $idx => $data)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-semibold text-gray-600 sticky left-0 bg-white z-10">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3 sticky left-12 bg-white z-10">
                                <div class="flex items-center gap-2">
                                    @if($data['user']->foto_profil && file_exists(public_path('storage/' . $data['user']->foto_profil)))
                                        <img src="{{ asset('storage/' . $data['user']->foto_profil) }}" alt="{{ $data['user']->name }}" 
                                             class="w-6 h-6 rounded-full object-cover">
                                    @else
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($data['user']->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-800 text-xs whitespace-nowrap">{{ $data['user']->name }}</p>
                                        @if($data['user']->role === 'owner')
                                            <span class="text-xs text-yellow-600">👑</span>
                                        @else
                                            <span class="text-xs text-blue-600">🛡️</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center font-semibold text-green-600 bg-green-50">{{ $data['stats']['hadir'] }}</td>
                            <td class="px-4 py-3 text-center font-semibold text-orange-600 bg-orange-50">{{ $data['stats']['terlambat'] }}</td>
                            <td class="px-4 py-3 text-center font-semibold text-yellow-600 bg-yellow-50">{{ $data['stats']['izin'] }}</td>
                            <td class="px-4 py-3 text-center font-semibold text-red-600 bg-red-50">{{ $data['stats']['alfa'] }}</td>
                            @for($day = 1; $day <= $totalWorkDays; $day++)
                                <td class="px-2 py-3 text-center border-l">
                                    @php
                                        $status = $data['daily'][$day]['status'];
                                    @endphp
                                    @if($status === 'belum_terjadi')
                                        <span class="text-gray-300" title="Belum Terjadi">-</span>
                                    @elseif($status === 'hadir')
                                        <span class="text-green-600 font-bold text-lg" title="Hadir">✓</span>
                                    @elseif($status === 'terlambat')
                                        <span class="text-orange-600 font-bold" title="Terlambat">⏰</span>
                                    @elseif($status === 'izin')
                                        <span class="text-yellow-600 font-bold" title="Izin">📋</span>
                                    @else
                                        <span class="text-red-600 font-bold text-lg" title="Alfa">✗</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 6 + $totalWorkDays }}" class="text-center py-8">
                                <i class="fas fa-inbox text-4xl text-gray-400 mb-2 block"></i>
                                <p class="text-gray-500">Tidak ada data presensi untuk bulan ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Info -->
        <div class="px-6 py-4 bg-gray-50 border-t text-sm text-gray-600">
            <p>Total Records: <strong>{{ count($attendanceData) }}</strong></p>
        </div>
    </div>

    <!-- Info Section -->
    <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-lg text-blue-800">
        <h3 class="font-semibold mb-3 flex items-center">
            <i class="fas fa-info-circle mr-2"></i>
            Keterangan
        </h3>
        <ul class="space-y-2 text-sm">
            <li>✓ <strong>Hadir:</strong> Admin/Owner check-in sebelum jam 07:30 (tepat waktu)</li>
            <li>✓ <strong>Terlambat:</strong> Admin/Owner check-in setelah jam 07:30</li>
            <li>✓ <strong>Izin:</strong> Admin/Owner mengajukan izin untuk tidak masuk</li>
            <li>✓ <strong>Alfa:</strong> Admin/Owner tidak hadir tanpa keterangan</li>
        </ul>
    </div>
</div>

<style>
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slide-down {
        animation: slideDown 0.3s ease-out;
    }
</style>
@endsection
