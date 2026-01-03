<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Presensi masuk
    public function checkIn(Request $request)
    {
        $user = Auth::user();

        // Cek apakah sudah presensi masuk hari ini
        if (Attendance::hasCheckedInToday($user->id)) {
            return redirect()->back()->with('error', 'Anda sudah presensi masuk hari ini!');
        }

        $now = now();
        $checkInTime = $now->format('H:i:s');
        
        // Tentukan status - Terlambat jika check-in melebihi jam 07:30
        $lateThreshold = Carbon::createFromTime(7, 30, 0);
        $checkIn = Carbon::createFromTime($now->hour, $now->minute, $now->second);
        $status = $checkIn->gt($lateThreshold) ? 'late' : 'ontime';

        // Simpan presensi
        Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'check_in' => $checkInTime,
            'status' => $status,
            'notes' => $request->notes
        ]);

        $message = $status == 'ontime' ? 'Presensi masuk tepat waktu!' : 'Presensi masuk terlambat!';
        return redirect()->back()->with('success', $message);
    }

    // Presensi pulang
    public function checkOut(Request $request)
    {
        $user = Auth::user();

        // Cari presensi masuk hari ini
        $attendance = Attendance::today($user->id)->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'Anda belum presensi masuk hari ini!');
        }

        if ($attendance->check_out) {
            return redirect()->back()->with('error', 'Anda sudah presensi pulang hari ini!');
        }

        // Cek apakah sudah melebihi jam checkout maksimal (22:00)
        $now = now();
        $checkOutTime = $now->format('H:i:s');
        $maxCheckOutTime = Carbon::createFromTime(22, 0, 0);
        $currentTime = Carbon::createFromTime($now->hour, $now->minute, $now->second);

        if ($currentTime->gt($maxCheckOutTime)) {
            return redirect()->back()->with('error', 'Checkout hanya dapat dilakukan maksimal hingga jam 22:00. Sistem telah otomatis melakukan checkout pada jam 22:00.');
        }

        // Update presensi pulang
        $attendance->update([
            'check_out' => $checkOutTime,
            'notes' => $request->notes ?: $attendance->notes
        ]);

        return redirect()->back()->with('success', 'Presensi pulang berhasil!');
    }

    // Lihat riwayat presensi
    public function history(Request $request)
    {
        $user = Auth::user();
        
        $query = Attendance::where('user_id', $user->id);

        // Filter berdasarkan tanggal jika ada
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            
            $query->dateRange($startDate, $endDate);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->paginate(20);

        return view('attendance.history', compact('attendances'));
    }

    // Dashboard presensi
    public function dashboard()
    {
        $user = Auth::user();

        // Data hari ini
        $todayAttendance = Attendance::today($user->id)->first();

        // Statistik bulan ini menggunakan method dari model
        $monthlyStats = Attendance::getMonthlyStats($user->id);

        return view('attendance.dashboard', compact('todayAttendance', 'monthlyStats'));
    }

    // Store attendance (untuk kompatibilitas dengan route yang ada)
    public function store(Request $request)
    {
        return $this->checkIn($request);
    }

    // Detail presensi untuk owner - Lihat semua staff
    public function detail(Request $request)
    {
        // Get month and year from request, default to current month
        if ($request->filled('year') && $request->filled('month_num')) {
            $month = $request->input('year') . '-' . str_pad($request->input('month_num'), 2, '0', STR_PAD_LEFT);
        } elseif ($request->filled('month')) {
            $month = $request->input('month');
        } else {
            $month = now()->format('Y-m');
        }
        $monthCarbon = Carbon::parse($month . '-01');
        
        // Get all staff users
        $staffUsers = User::whereIn('role', ['admin', 'owner'])->orderBy('name')->get();
        
        // Get all attendance records for the selected month
        $startDate = $monthCarbon->copy()->startOfMonth()->toDateString();
        $endDate = $monthCarbon->copy()->endOfMonth()->toDateString();
        
        $attendances = Attendance::whereBetween('date', [$startDate, $endDate])->get();
        
        // Group by user_id and date - normalize date format
        $attendanceByUserAndDate = [];
        foreach ($attendances as $att) {
            // Convert datetime to date string for consistent key matching
            $attDate = Carbon::parse($att->date)->toDateString();
            $key = $att->user_id . '_' . $attDate;
            $attendanceByUserAndDate[$key] = $att;
        }
        
        // Calculate monthly stats
        $totalWorkDays = $monthCarbon->daysInMonth;
        $hadir = 0; $izin = 0; $alfa = 0; $terlambat = 0;
        $today = now()->startOfDay(); // Tanggal hari ini
        
        $attendanceData = [];
        foreach ($staffUsers as $user) {
            $userStats = ['hadir' => 0, 'terlambat' => 0, 'izin' => 0, 'alfa' => 0];
            $dailyData = [];
            
            // Loop through all days in the month
            for ($day = 1; $day <= $totalWorkDays; $day++) {
                $currentDate = $monthCarbon->copy()->day($day)->startOfDay();
                $dateString = $currentDate->toDateString();
                $key = $user->id . '_' . $dateString;
                $attendance = isset($attendanceByUserAndDate[$key]) ? $attendanceByUserAndDate[$key] : null;
                
                // Cek apakah tanggal sudah lewat atau belum
                if ($currentDate->isAfter($today)) {
                    // Tanggal belum terjadi, kosongkan saja
                    $status = 'belum_terjadi';
                } elseif ($attendance && $attendance->check_in) {
                    $checkInTime = Carbon::parse($attendance->check_in);
                    $lateThreshold = Carbon::createFromTime(7, 30, 0);
                    
                    if ($checkInTime->gt($lateThreshold)) {
                        $status = 'terlambat';
                        $userStats['terlambat']++;
                        $terlambat++;
                    } else {
                        $status = 'hadir';
                        $userStats['hadir']++;
                        $hadir++;
                    }
                } elseif ($attendance && $attendance->status === 'izin') {
                    $status = 'izin';
                    $userStats['izin']++;
                    $izin++;
                } else {
                    // Hanya hitung alfa jika tanggal sudah lewat
                    $status = 'alfa';
                    $userStats['alfa']++;
                    $alfa++;
                }
                
                $dailyData[$day] = [
                    'status' => $status,
                    'attendance' => $attendance
                ];
            }
            
            $attendanceData[] = [
                'user' => $user,
                'stats' => $userStats,
                'daily' => $dailyData
            ];
        }
        
        $totalStaff = $staffUsers->count();
        
        return view('admin.attendance.detail', compact('attendanceData', 'totalStaff', 'hadir', 'izin', 'alfa', 'terlambat', 'monthCarbon', 'totalWorkDays'));
    }
}