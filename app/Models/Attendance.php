<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'check_in', 'check_out', 'status', 'notes'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Method untuk cek status presensi
    public function calculateStatus()
    {
        if (!$this->check_in) {
            return 'absent';
        }

        $checkInTime = Carbon::parse($this->check_in);
        $lateThreshold = Carbon::createFromTime(7, 30, 0);

        return $checkInTime->gt($lateThreshold) ? 'late' : 'ontime';
    }

    // ✅ FIXED: Method untuk cek apakah sudah presensi hari ini (VERSI SIMPLE)
    public static function hasCheckedInToday($userId)
    {
        $today = now()->format('Y-m-d');
        return static::where('user_id', $userId)
                    ->where('date', $today) // ✅ PAKAI where() BIASA, BUKAN whereDate()
                    ->whereNotNull('check_in')
                    ->exists();
    }

    // ✅ FIXED: Method untuk cek apakah sudah presensi pulang hari ini (VERSI SIMPLE)
    public static function hasCheckedOutToday($userId)
    {
        $today = now()->format('Y-m-d');
        return static::where('user_id', $userId)
                    ->where('date', $today) // ✅ PAKAI where() BIASA, BUKAN whereDate()
                    ->whereNotNull('check_out')
                    ->exists();
    }

    // Scope untuk mendapatkan presensi hari ini
    public function scopeToday($query, $userId = null)
    {
        $today = now()->format('Y-m-d');
        $query = $query->where('date', $today);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query;
    }

    // Scope untuk filter berdasarkan rentang tanggal
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    // Accessor untuk durasi kerja
    public function getWorkDurationAttribute()
    {
        if (!$this->check_in || !$this->check_out) {
            return null;
        }

        $checkIn = Carbon::parse($this->check_in);
        $checkOut = Carbon::parse($this->check_out);

        return $checkOut->diff($checkIn)->format('%H:%I:%S');
    }

    // Accessor untuk status dengan label yang lebih user-friendly
    public function getStatusLabelAttribute()
    {
        $labels = [
            'ontime' => 'Tepat Waktu',
            'late' => 'Terlambat',
            'absent' => 'Tidak Hadir'
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    // Method untuk mendapatkan statistik bulanan
    public static function getMonthlyStats($userId, $month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        return static::where('user_id', $userId)
                   ->whereYear('date', $year)
                   ->whereMonth('date', $month)
                   ->selectRaw('
                       COUNT(*) as total_days,
                       SUM(CASE WHEN status = "ontime" THEN 1 ELSE 0 END) as ontime_days,
                       SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_days,
                       SUM(CASE WHEN check_in IS NULL THEN 1 ELSE 0 END) as absent_days
                   ')
                   ->first();
    }

    // Method untuk otomatis checkout pada jam 22:00
    public static function autoCheckoutAt22()
    {
        $maxCheckOutTime = Carbon::createFromTime(22, 0, 0);
        
        // Cari semua presensi hari ini yang belum checkout
        $attendances = static::where('date', now()->format('Y-m-d'))
                            ->whereNull('check_out')
                            ->get();

        foreach ($attendances as $attendance) {
            // Update checkout ke jam 22:00 jika belum ada
            $attendance->update([
                'check_out' => $maxCheckOutTime->format('H:i:s')
            ]);
        }

        return count($attendances);
    }
}