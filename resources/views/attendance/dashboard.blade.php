<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Presensi Harian - Ruang Aksara</title>
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
                            Kembali
                        </a>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Presensi Harian</h1>
                    <div class="text-sm text-gray-600">
                        <span id="real-time">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y H:i:s') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto py-6 px-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Today's Attendance Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Status Presensi Hari Ini</h2>
                
                @if($todayAttendance)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="text-center p-4 border rounded-lg {{ $todayAttendance->check_in ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                            <div class="text-2xl font-bold {{ $todayAttendance->check_in ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $todayAttendance->check_in ? \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') : '--:--' }}
                            </div>
                            <div class="text-sm text-gray-600">Check In</div>
                        </div>
                        
                        <div class="text-center p-4 border rounded-lg {{ $todayAttendance->check_out ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                            <div class="text-2xl font-bold {{ $todayAttendance->check_out ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $todayAttendance->check_out ? \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') : '--:--' }}
                            </div>
                            <div class="text-sm text-gray-600">Check Out</div>
                        </div>
                        
                        <div class="text-center p-4 border rounded-lg bg-blue-50 border-blue-200">
                            <div class="text-2xl font-bold text-blue-600 capitalize">
                                @if($todayAttendance->status == 'ontime')
                                    Tepat Waktu
                                @elseif($todayAttendance->status == 'late')
                                    Terlambat
                                @else
                                    Tidak Hadir
                                @endif
                            </div>
                            <div class="text-sm text-gray-600">Status</div>
                        </div>
                    </div>

                    @if(!$todayAttendance->check_out)
                        <form action="{{ route('attendance.checkout') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-4">
                                <label for="notes_checkout" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                <textarea name="notes" id="notes_checkout" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tambahkan catatan untuk presensi pulang...">{{ $todayAttendance->notes }}</textarea>
                            </div>
                            <button type="submit" class="w-full bg-red-600 text-white py-3 px-4 rounded-lg hover:bg-red-700 font-semibold">
                                <i class="fas fa-sign-out-alt mr-2"></i>Presensi Pulang
                            </button>
                        </form>
                    @else
                        <div class="bg-gray-100 p-4 rounded-lg text-center">
                            <p class="text-gray-600">Presensi hari ini sudah selesai.</p>
                        </div>
                    @endif

                @else
                    <div class="text-center py-8">
                        <i class="fas fa-clock text-4xl text-gray-300 mb-4"></i>
                        
                        @php
                            $now = \Carbon\Carbon::now();
                            $maxCheckInTime = \Carbon\Carbon::createFromTime(10, 0, 0);
                            $isCheckInClosed = $now->gt($maxCheckInTime);
                        @endphp

                        @if($isCheckInClosed)
                            <p class="text-red-600 font-semibold mb-4">Waktu check-in sudah ditutup!</p>
                            <p class="text-gray-500 mb-4">Check-in hanya dapat dilakukan hingga jam 10:00.</p>
                            <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                                <p class="text-red-700">Anda tidak dapat melakukan check-in lagi hari ini.</p>
                            </div>
                        @else
                            <p class="text-gray-500 mb-4">Belum ada presensi hari ini.</p>
                            <form action="{{ route('attendance.checkin') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="notes_checkin" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                    <textarea name="notes" id="notes_checkin" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tambahkan catatan untuk presensi masuk..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 font-semibold">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Presensi Masuk
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Monthly Stats -->
            @if($monthlyStats)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Statistik Bulan Ini</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $monthlyStats->total_days ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Total Hari</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $monthlyStats->ontime_days ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Tepat Waktu</div>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">{{ $monthlyStats->late_days ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Terlambat</div>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">{{ $monthlyStats->absent_days ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Tidak Hadir</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Update waktu real-time
        function updateRealTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            
            // Format untuk Indonesia
            const formatter = new Intl.DateTimeFormat('id-ID', options);
            document.getElementById('real-time').textContent = formatter.format(now);
        }

        // Update setiap detik
        setInterval(updateRealTime, 1000);
        updateRealTime(); // Jalankan pertama kali
    </script>
</body>
</html>