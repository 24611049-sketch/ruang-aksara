<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SystemController extends Controller
{
    /**
     * Display system settings page
     */
    public function index()
    {
        $settings = [
            'site_name' => config('app.name', 'Ruang Aksara'),
            'site_email' => config('mail.from.address', 'admin@ruangaksara.com'),
            'timezone' => config('app.timezone', 'Asia/Jakarta'),
            'currency' => 'IDR',
            'maintenance_mode' => app()->isDownForMaintenance(),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'timezone' => 'required|string|max:50',
            'currency' => 'required|string|max:10',
        ]);

        // Update settings (in real implementation, you'd save to database)
        $settings = [
            'site_name' => $request->site_name,
            'site_email' => $request->site_email,
            'timezone' => $request->timezone,
            'currency' => $request->currency,
        ];

        // For demo purposes, we'll just flash to session
        session()->flash('system_settings', $settings);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan umum berhasil diperbarui!')
            ->with('settings', $settings);
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'low_stock_alerts' => 'boolean',
            'new_order_alerts' => 'boolean',
            'alert_threshold' => 'required|integer|min:1|max:100',
        ]);

        $notificationSettings = [
            'email_notifications' => $request->boolean('email_notifications'),
            'low_stock_alerts' => $request->boolean('low_stock_alerts'),
            'new_order_alerts' => $request->boolean('new_order_alerts'),
            'alert_threshold' => $request->alert_threshold,
        ];

        // For demo purposes
        session()->flash('notification_settings', $notificationSettings);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan notifikasi berhasil diperbarui!');
    }

    /**
     * Update payment verification settings
     */
    public function updatePaymentVerification(Request $request)
    {
        $request->validate([
            'require_payment_verification' => 'boolean',
            'auto_verify_cod' => 'boolean',
            'new_order_message' => 'nullable|string|max:500',
        ]);

        $paymentSettings = [
            'require_payment_verification' => $request->boolean('require_payment_verification'),
            'auto_verify_cod' => $request->boolean('auto_verify_cod'),
            'new_order_message' => $request->new_order_message ?? '',
        ];

        // Store in cache (for persistent storage, use database)
        Cache::put('payment_verification_settings', $paymentSettings, now()->addYears(1));
        session()->flash('payment_verification_settings', $paymentSettings);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan verifikasi pembayaran berhasil diperbarui!');
    }

    /**
     * Clear application cache
     */
    public function clearCache()
    {
        Cache::flush();
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');

        return back()->with('success', 'Cache berhasil dibersihkan!');
    }

    /**
     * Toggle maintenance mode
     */
    public function toggleMaintenance(Request $request)
    {
        $isDown = app()->isDownForMaintenance();

        if ($isDown) {
            \Artisan::call('up');
            $message = 'Maintenance mode dinonaktifkan. Situs sekarang aktif.';
        } else {
            \Artisan::call('down', [
                '--secret' => 'ruangaksara2024'
            ]);
            $message = 'Maintenance mode diaktifkan. Situs sekarang dalam perbaikan.';
        }

        return back()->with('success', $message);
    }

    /**
     * Backup database (basic implementation)
     */
    public function backupDatabase()
    {
        try {
            \Artisan::call('backup:run', ['--only-db' => true]);
            
            return back()->with('success', 'Backup database berhasil dilakukan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan backup: ' . $e->getMessage());
        }
    }
}