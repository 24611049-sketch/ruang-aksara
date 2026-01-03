@extends('layouts.help')

@section('title', 'Keamanan Akun - Ruang Aksara')

@section('content')
<div class="max-w-6xl mx-auto px-4">
    <div class="card p-8 mb-6 text-center">
        <h1 class="text-2xl font-bold"><i class="fas fa-shield-alt mr-2"></i> Keamanan Akun</h1>
        <p class="text-gray-600">Tips dan panduan menjaga keamanan akun Ruang Aksara Anda</p>
    </div>

    <!-- Keamanan Password -->
    <div class="card p-6 mb-6">
        <h2 class="text-xl font-bold mb-4"><i class="fas fa-lock mr-2 text-green-600"></i> Keamanan Password</h2>
        <div class="space-y-4">
            <div class="flex gap-4">
                <div class="text-2xl text-green-600 flex-shrink-0"><i class="fas fa-check-circle"></i></div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Gunakan Password yang Kuat</h4>
                    <p class="text-sm text-gray-700">Password harus minimal 8 karakter dengan kombinasi huruf besar, huruf kecil, angka, dan simbol.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="text-2xl text-green-600 flex-shrink-0"><i class="fas fa-check-circle"></i></div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Jangan Bagikan Password</h4>
                    <p class="text-sm text-gray-700">Kami tidak akan pernah meminta password Anda. Tidak ada staff yang perlu tahu password akun Anda.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="text-2xl text-green-600 flex-shrink-0"><i class="fas fa-check-circle"></i></div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Ganti Password Secara Berkala</h4>
                    <p class="text-sm text-gray-700">Ganti password minimal 3 bulan sekali, terutama jika Anda merasa akun Anda terancam.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="text-2xl text-green-600 flex-shrink-0"><i class="fas fa-check-circle"></i></div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Hindari Password Umum</h4>
                    <p class="text-sm text-gray-700">Jangan gunakan tanggal lahir, nama, nomor telepon, atau data pribadi lainnya sebagai password.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Keamanan Email -->
    <div class="card p-6 mb-6">
        <h2 class="text-xl font-bold mb-4"><i class="fas fa-envelope mr-2 text-green-600"></i> Keamanan Email</h2>
        <div class="space-y-4">
            <div class="flex gap-4">
                <div class="text-2xl text-blue-600 flex-shrink-0"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Email Terdaftar = Akses Akun</h4>
                    <p class="text-sm text-gray-700">Email yang terdaftar di akun Anda dapat digunakan untuk reset password. Pastikan email Anda aman.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="text-2xl text-blue-600 flex-shrink-0"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Verifikasi Email Anda</h4>
                    <p class="text-sm text-gray-700">Pastikan email Anda sudah diverifikasi saat pendaftaran. Periksa inbox atau folder spam jika tidak menemukan email verifikasi.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="text-2xl text-blue-600 flex-shrink-0"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Jangan Bagikan Email</h4>
                    <p class="text-sm text-gray-700">Jangan beritahu email akun Anda kepada orang lain. Email adalah akses utama ke akun Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Akun -->
    <div class="card p-6 mb-6">
        <h2 class="text-xl font-bold mb-4"><i class="fas fa-user-check mr-2 text-green-600"></i> Pantau Aktivitas Akun</h2>
        <div class="space-y-4">
            <div class="flex gap-4">
                <div class="text-2xl text-purple-600 flex-shrink-0"><i class="fas fa-eye"></i></div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Periksa Pesanan Anda</h4>
                    <p class="text-sm text-gray-700">Secara rutin periksa halaman "Pesanan Saya" untuk memastikan tidak ada transaksi mencurigakan.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="text-2xl text-purple-600 flex-shrink-0"><i class="fas fa-eye"></i></div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Logout dari Perangkat Lain</h4>
                    <p class="text-sm text-gray-700">Jika Anda login di perangkat publik, pastikan untuk logout setelah selesai.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="text-2xl text-purple-600 flex-shrink-0"><i class="fas fa-eye"></i></div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Aktifkan Session Aman</h4>
                    <p class="text-sm text-gray-700">Gunakan HTTPS saat login. Periksa bahwa URL dimulai dengan "https://" bukan "http://".</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pencegahan Scam -->
    <div class="card p-6 mb-6 bg-red-50 border border-red-200">
        <h2 class="text-xl font-bold mb-4"><i class="fas fa-exclamation-triangle mr-2 text-red-600"></i> Hati-hati Penipuan</h2>
        <div class="space-y-3 text-sm text-gray-700">
            <div class="flex gap-3">
                <i class="fas fa-times-circle text-red-600 flex-shrink-0 mt-1"></i>
                <p><strong>Kami TIDAK pernah:</strong> Meminta password via email, telepon, atau chat. Jika ada yang meminta, itu pasti PENIPUAN.</p>
            </div>
            <div class="flex gap-3">
                <i class="fas fa-times-circle text-red-600 flex-shrink-0 mt-1"></i>
                <p><strong>Waspada email palsu:</strong> Jangan klik link di email yang tidak resmi. Selalu kunjungi situs kami langsung.</p>
            </div>
            <div class="flex gap-3">
                <i class="fas fa-times-circle text-red-600 flex-shrink-0 mt-1"></i>
                <p><strong>Periksa URL:</strong> Pastikan Anda mengakses ruang-aksara.com (bukan domain serupa seperti ruang-aksara.net, dll).</p>
            </div>
            <div class="flex gap-3">
                <i class="fas fa-times-circle text-red-600 flex-shrink-0 mt-1"></i>
                <p><strong>Harga aneh:</strong> Jika ada yang menawarkan diskon sangat besar via chat pribadi, itu pasti SCAM.</p>
            </div>
        </div>
    </div>

    <!-- Jika Akun Terancam -->
    <div class="card p-6 mb-6 bg-orange-50 border border-orange-200">
        <h2 class="text-xl font-bold mb-4"><i class="fas fa-alert-circle mr-2 text-orange-600"></i> Jika Akun Anda Terancam</h2>
        <div class="space-y-4">
            <ol class="list-decimal ml-6 space-y-2 text-sm text-gray-700">
                <li><strong>Segera ganti password</strong> dari akun Anda jika merasa dicurigai.</li>
                <li><strong>Hubungi support kami</strong> jika Anda tidak bisa akses akun atau terjadi transaksi tidak sah.</li>
                <li><strong>Jangan lakukan transaksi apapun</strong> sampai akun Anda aman kembali.</li>
                <li><strong>Lapor ke kami</strong> segera dengan detail yang jelas.</li>
            </ol>
            <div class="bg-white border border-orange-300 rounded p-4 mt-4">
                <p class="font-semibold text-gray-800 mb-2">Hubungi Support Kami:</p>
                <ul class="space-y-1 text-sm">
                    <li><a href="mailto:ruangg.aksara@gmail.com" class="text-green-600 hover:underline">📧 ruangg.aksara@gmail.com</a></li>
                    <li><a href="tel:+62274123456" class="text-green-600 hover:underline">📞 (0274) 123-456</a></li>
                    <li><a href="https://wa.me/628123456789" target="_blank" class="text-green-600 hover:underline">💬 WhatsApp: +62 812-3456-789</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- FAQ -->
    <div class="card p-6">
        <h2 class="text-xl font-bold mb-4"><i class="fas fa-comments mr-2 text-green-600"></i> Pertanyaan Umum</h2>
        <div class="space-y-4">
            <div class="border-b pb-4">
                <h4 class="font-semibold text-gray-800 mb-2">Bagaimana cara reset password?</h4>
                <p class="text-sm text-gray-700">Klik tombol "Lupa Password" di halaman login, kemudian masukkan email Anda. Ikuti link reset yang dikirim ke email Anda.</p>
            </div>
            <div class="border-b pb-4">
                <h4 class="font-semibold text-gray-800 mb-2">Apakah data saya aman?</h4>
                <p class="text-sm text-gray-700">Kami menggunakan enkripsi SSL untuk melindungi data Anda. Semua transaksi dan informasi pribadi dienkripsi dengan aman.</p>
            </div>
            <div class="border-b pb-4">
                <h4 class="font-semibold text-gray-800 mb-2">Apa bedanya HTTP dan HTTPS?</h4>
                <p class="text-sm text-gray-700">HTTPS lebih aman karena data dienkripsi. Selalu gunakan HTTPS saat login atau transaksi. Cek di URL browser Anda.</p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800 mb-2">Bisa login dari banyak perangkat?</h4>
                <p class="text-sm text-gray-700">Bisa, tapi sebaiknya logout dari perangkat lain yang tidak Anda gunakan lagi untuk keamanan maksimal.</p>
            </div>
        </div>
    </div>
</div>
@endsection
