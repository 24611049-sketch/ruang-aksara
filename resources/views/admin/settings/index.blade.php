@extends('layouts.app')

@section('title', 'Pengaturan Sistem - Admin')

@section('content')
<div class="max-w-6xl mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        <i class="fas fa-cog mr-2"></i>Pengaturan Sistem
    </h1>

    <!-- System Actions -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Aksi Sistem</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <form action="{{ route('admin.settings.clearCache') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600 flex items-center justify-center">
                    <i class="fas fa-broom mr-2"></i>Bersihkan Cache
                </button>
            </form>
            
            <form action="{{ route('admin.settings.toggleMaintenance') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-orange-500 text-white py-2 px-4 rounded hover:bg-orange-600 flex items-center justify-center">
                    <i class="fas fa-tools mr-2"></i>
                    {{ app()->isDownForMaintenance() ? 'Nonaktifkan Maintenance' : 'Aktifkan Maintenance' }}
                </button>
            </form>
            
            <form action="{{ route('admin.settings.backupDatabase') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 flex items-center justify-center">
                    <i class="fas fa-database mr-2"></i>Backup Database
                </button>
            </form>
        </div>
    </div>

    <!-- General Settings -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Pengaturan Umum</h2>
        <form action="{{ route('admin.settings.updateGeneral') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Situs</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Ruang Aksara' }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Situs</label>
                    <input type="email" name="site_email" value="{{ $settings['site_email'] ?? 'admin@ruangaksara.com' }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zona Waktu</label>
                    <select name="timezone" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="Asia/Jakarta" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                        <option value="Asia/Makassar" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                        <option value="Asia/Jayapura" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                        <option value="UTC" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'UTC' ? 'selected' : '' }}>UTC</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mata Uang</label>
                    <select name="currency" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="IDR" {{ ($settings['currency'] ?? 'IDR') == 'IDR' ? 'selected' : '' }}>Rupiah (IDR)</option>
                        <option value="USD" {{ ($settings['currency'] ?? 'IDR') == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                    <i class="fas fa-save mr-2"></i>Simpan Pengaturan Umum
                </button>
            </div>
        </form>
    </div>

    <!-- Notification Settings -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Pengaturan Notifikasi</h2>
        <form action="{{ route('admin.settings.updateNotifications') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" name="email_notifications" id="email_notifications" value="1" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ ($settings['email_notifications'] ?? true) ? 'checked' : '' }}>
                    <label for="email_notifications" class="ml-2 text-sm text-gray-700">Notifikasi Email</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="low_stock_alerts" id="low_stock_alerts" value="1" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ ($settings['low_stock_alerts'] ?? true) ? 'checked' : '' }}>
                    <label for="low_stock_alerts" class="ml-2 text-sm text-gray-700">Peringatan Stok Menipis</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="new_order_alerts" id="new_order_alerts" value="1" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ ($settings['new_order_alerts'] ?? true) ? 'checked' : '' }}>
                    <label for="new_order_alerts" class="ml-2 text-sm text-gray-700">Peringatan Order Baru</label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Batas Peringatan Stok</label>
                    <input type="number" name="alert_threshold" value="{{ $settings['alert_threshold'] ?? 5 }}" min="1" max="100" 
                           class="w-32 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span class="text-sm text-gray-500 ml-2">item</span>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                    <i class="fas fa-bell mr-2"></i>Simpan Pengaturan Notifikasi
                </button>
            </div>
        </form>
    </div>

    <!-- Payment Verification Settings -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
        <h2 class="text-lg font-semibold mb-4"><i class="fas fa-credit-card mr-2"></i>Pengaturan Verifikasi Pembayaran</h2>
        <form action="{{ route('admin.settings.updatePaymentVerification') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="border border-blue-200 bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-gray-700 mb-3">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Aktifkan verifikasi pembayaran untuk meninjau bukti transfer sebelum memproses pesanan.
                    </p>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="require_payment_verification" id="require_payment_verification" value="1" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                           {{ ($settings['require_payment_verification'] ?? false) ? 'checked' : '' }}>
                    <label for="require_payment_verification" class="ml-3 text-gray-700">
                        <span class="font-semibold">Wajibkan Verifikasi Pembayaran</span>
                        <p class="text-sm text-gray-600 mt-1">Pesanan dengan transfer (BCA/Mandiri/BNI) harus diverifikasi admin sebelum diproses</p>
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="auto_verify_cod" id="auto_verify_cod" value="1" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                           {{ ($settings['auto_verify_cod'] ?? true) ? 'checked' : '' }}>
                    <label for="auto_verify_cod" class="ml-3 text-gray-700">
                        <span class="font-semibold">Auto-Verifikasi COD</span>
                        <p class="text-sm text-gray-600 mt-1">Pesanan COD (tunai) otomatis terverifikasi tanpa perlu manual check</p>
                    </label>
                </div>

                <div class="pt-2 border-t">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pesan untuk Admin saat ada Order Baru</label>
                    <textarea name="new_order_message" placeholder="Contoh: Periksa bukti pembayaran di dashboard admin..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              rows="3">{{ $settings['new_order_message'] ?? '' }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                    <i class="fas fa-save mr-2"></i>Simpan Pengaturan Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection