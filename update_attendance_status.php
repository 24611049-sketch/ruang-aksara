<?php

// Script untuk update status attendance yang sudah ada
// Jalankan dengan: php update_attendance_status.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Attendance;
use Carbon\Carbon;

echo "Updating attendance status based on check-in time...\n\n";

// Ambil semua attendance yang punya check_in
$attendances = Attendance::whereNotNull('check_in')->get();

$updated = 0;
$lateThreshold = Carbon::createFromTime(7, 30, 0);

foreach ($attendances as $attendance) {
    $checkInTime = Carbon::parse($attendance->check_in);
    
    // Tentukan status berdasarkan waktu check-in
    if ($checkInTime->gt($lateThreshold)) {
        $newStatus = 'late';
    } else {
        $newStatus = 'ontime';
    }
    
    // Update jika status berbeda
    if ($attendance->status !== $newStatus) {
        $attendance->status = $newStatus;
        $attendance->save();
        $updated++;
        
        echo "Updated: {$attendance->user->name} - Date: {$attendance->date} - Check-in: {$attendance->check_in} - New Status: {$newStatus}\n";
    }
}

echo "\n✅ Done! Updated {$updated} records.\n";
