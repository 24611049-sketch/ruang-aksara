@extends('layouts.help')

@section('title', 'Metode Pembayaran - Ruang Aksara')

@section('content')
<div class="max-w-6xl mx-auto px-4">
    <div class="card p-8 mb-6 text-center">
        <h1 class="text-2xl font-bold"><i class="fas fa-credit-card mr-2"></i> Metode Pembayaran</h1>
        <p class="text-gray-600">Pilihan metode pembayaran yang kami sediakan untuk kemudahan Anda</p>
    </div>

    <!-- Metode Transfer -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="card p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl">
                    <i class="fas fa-university"></i>
                </div>
                <h3 class="font-bold text-gray-800">Transfer Bank BCA</h3>
            </div>
            <div class="space-y-2 text-sm text-gray-700">
                <p><strong>Nomor Rekening:</strong></p>
                <p class="font-mono text-lg font-bold text-green-600 mb-3">1234567890</p>
                <p><strong>Atas Nama:</strong></p>
                <p>PT Ruang Aksara</p>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 text-xl">
                    <i class="fas fa-university"></i>
                </div>
                <h3 class="font-bold text-gray-800">Transfer Bank Mandiri</h3>
            </div>
            <div class="space-y-2 text-sm text-gray-700">
                <p><strong>Nomor Rekening:</strong></p>
                <p class="font-mono text-lg font-bold text-green-600 mb-3">9876543210</p>
                <p><strong>Atas Nama:</strong></p>
                <p>PT Ruang Aksara</p>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 text-xl">
                    <i class="fas fa-university"></i>
                </div>
                <h3 class="font-bold text-gray-800">Transfer Bank BNI</h3>
            </div>
            <div class="space-y-2 text-sm text-gray-700">
                <p><strong>Nomor Rekening:</strong></p>
                <p class="font-mono text-lg font-bold text-green-600 mb-3">1122334455</p>
                <p><strong>Atas Nama:</strong></p>
                <p>PT Ruang Aksara</p>
            </div>
        </div>
    </div>

    <!-- Panduan Transfer -->
    <div class="card p-6 mb-6">
        <h2 class="text-xl font-bold mb-4"><i class="fas fa-list-ol mr-2 text-green-600"></i> Panduan Transfer</h2>
        <div class="space-y-4">
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-sm">1</div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Pilih Metode Transfer</h4>
                    <p class="text-sm text-gray-700">Saat checkout, pilih salah satu bank: BCA, Mandiri, atau BNI sesuai rekening Anda.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-sm">2</div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Lakukan Transfer</h4>
                    <p class="text-sm text-gray-700">Transfer ke nomor rekening yang ditampilkan dengan jumlah sesuai total pesanan.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-sm">3</div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Upload Bukti Transfer</h4>
                    <p class="text-sm text-gray-700">Ambil screenshot bukti transfer Anda dan upload di form checkout.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-sm">4</div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Tunggu Verifikasi</h4>
                    <p class="text-sm text-gray-700">Tim kami akan memverifikasi pembayaran Anda dalam 1x24 jam.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-sm">5</div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Pesanan Diproses</h4>
                    <p class="text-sm text-gray-700">Setelah verifikasi, pesanan langsung diproses dan siap dikirim.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- COD -->
    <div class="card p-6 mb-6 bg-gradient-to-r from-green-50 to-blue-50 border border-green-200">
        <h2 class="text-xl font-bold mb-4"><i class="fas fa-truck mr-2 text-green-600"></i> Pembayaran Tunai (COD)</h2>
        <div class="space-y-3">
            <div class="flex gap-4">
                <div class="text-2xl text-green-600"><i class="fas fa-check-circle"></i></div>
                <div>
                    <p class="font-semibold text-gray-800">Bayar saat barang sampai di tangan Anda</p>
                    <p class="text-sm text-gray-700 mt-1">Tidak perlu transfer terlebih dahulu. Anda hanya perlu membayar saat kurir mengantar barang.</p>
                </div>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mt-4">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Catatan:</strong> Pilihan COD hanya tersedia untuk area tertentu. Pastikan alamat Anda termasuk area layanan sebelum checkout.
                </p>
            </div>
        </div>
    </div>

    <!-- FAQ -->
    <div class="card p-6">
        <h2 class="text-xl font-bold mb-4"><i class="fas fa-comments mr-2 text-green-600"></i> Pertanyaan Umum</h2>
        <div class="space-y-4">
            <div class="border-b pb-4">
                <h4 class="font-semibold text-gray-800 mb-2">Berapa lama verifikasi pembayaran?</h4>
                <p class="text-sm text-gray-700">Tim kami akan memverifikasi pembayaran dalam waktu 1x24 jam. Notifikasi akan dikirim via email setelah verifikasi selesai.</p>
            </div>
            <div class="border-b pb-4">
                <h4 class="font-semibold text-gray-800 mb-2">Apa yang terjadi jika saya transfer kurang?</h4>
                <p class="text-sm text-gray-700">Pembayaran akan ditolak. Silakan lakukan transfer ulang dengan jumlah yang tepat sesuai total pesanan.</p>
            </div>
            <div class="border-b pb-4">
                <h4 class="font-semibold text-gray-800 mb-2">Bisa transfer dari rekening orang lain?</h4>
                <p class="text-sm text-gray-700">Bisa, asalkan Anda cantumkan nomor pesanan/nomor order di kolom berita transfer agar mudah dicocokkan.</p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800 mb-2">Bagaimana jika bukti transfer saya ditolak?</h4>
                <p class="text-sm text-gray-700">Hubungi support kami di <a href="mailto:ruangg.aksara@gmail.com" class="text-green-600 hover:underline">ruangg.aksara@gmail.com</a> atau WhatsApp. Kami akan membantu verifikasi pembayaran manual.</p>
            </div>
        </div>
    </div>

    <!-- Kontak Support -->
    <div class="card p-6 mt-6 bg-blue-50 border border-blue-200 text-center">
        <h3 class="font-bold text-gray-800 mb-3"><i class="fas fa-headset mr-2 text-blue-600"></i> Ada Pertanyaan?</h3>
        <p class="text-gray-700 mb-4">Hubungi support kami untuk bantuan pembayaran:</p>
        <div class="flex flex-col gap-2 justify-center">
            <a href="mailto:ruangg.aksara@gmail.com" class="text-green-600 hover:underline font-semibold">📧 ruangg.aksara@gmail.com</a>
            <a href="tel:+62274123456" class="text-green-600 hover:underline font-semibold">📞 (0274) 123-456</a>
            <a href="https://wa.me/628123456789" target="_blank" class="text-green-600 hover:underline font-semibold">💬 WhatsApp: +62 812-3456-789</a>
        </div>
    </div>
</div>
@endsection
