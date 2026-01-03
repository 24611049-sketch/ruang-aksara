@extends('layouts.help')

@section('title', 'Informasi Pengiriman - Ruang Aksara')

@section('content')
<div class="max-w-6xl mx-auto px-4">
    <div class="card p-8 mb-6 text-center">
        <h1 class="text-2xl font-bold"><i class="fas fa-shipping-fast mr-2"></i> Informasi Pengiriman</h1>
        <p class="text-gray-600">Kami menggunakan JNE untuk pengiriman ke seluruh Indonesia. Lihat tarif dan estimasi pengiriman di bawah.</p>
    </div>

    <!-- Kurir Info -->
    <div class="card p-6 mb-6 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200">
        <div class="flex items-center gap-4">
            <div class="text-4xl text-blue-600"><i class="fas fa-building"></i></div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">JNE - Jasa Pengiriman Nasional</h3>
                <p class="text-gray-700 text-sm">Kurir resmi yang kami gunakan untuk semua pengiriman pesanan Anda ke seluruh nusantara.</p>
            </div>
        </div>
    </div>

    <!-- Tarif JNE Regular -->
    <div class="card p-6 mb-6">
        <h2 class="text-xl font-bold mb-4"><i class="fas fa-list mr-2 text-green-600"></i>Tarif JNE Regular</h2>
        <p class="text-gray-600 mb-4">Estimasi sampai: <strong>3-5 hari kerja</strong></p>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-green-600">
                        <th class="text-left py-3 px-4 font-semibold text-gray-800">Zona Pengiriman</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-800">Estimasi</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-800">Tarif (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-200 hover:bg-green-50">
                        <td class="py-3 px-4">Jawa (Bandung, Jakarta, Yogyakarta, Surabaya, dll)</td>
                        <td class="py-3 px-4">3 hari kerja</td>
                        <td class="py-3 px-4 text-right font-semibold">15.000</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-green-50">
                        <td class="py-3 px-4">Sumatera (Medan, Palembang, Jambi, dll)</td>
                        <td class="py-3 px-4">4-5 hari kerja</td>
                        <td class="py-3 px-4 text-right font-semibold">20.000</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-green-50">
                        <td class="py-3 px-4">Kalimantan (Banjarmasin, Pontianak, Balikpapan, dll)</td>
                        <td class="py-3 px-4">4-5 hari kerja</td>
                        <td class="py-3 px-4 text-right font-semibold">25.000</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-green-50">
                        <td class="py-3 px-4">Sulawesi (Makassar, Manado, Palu, dll)</td>
                        <td class="py-3 px-4">4-5 hari kerja</td>
                        <td class="py-3 px-4 text-right font-semibold">25.000</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-green-50">
                        <td class="py-3 px-4">Bali & Nusa Tenggara</td>
                        <td class="py-3 px-4">4-5 hari kerja</td>
                        <td class="py-3 px-4 text-right font-semibold">25.000</td>
                    </tr>
                    <tr class="hover:bg-green-50">
                        <td class="py-3 px-4">Papua & Maluku</td>
                        <td class="py-3 px-4">5+ hari kerja</td>
                        <td class="py-3 px-4 text-right font-semibold">35.000</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-4">
            <p class="text-sm text-yellow-800">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Catatan:</strong> Tarif di atas adalah estimasi untuk pengiriman 1 kg. Tarif aktual dapat berbeda tergantung berat paket dan lokasi penerima yang spesifik.
            </p>
        </div>
    </div>

    <!-- Proses Pengiriman -->
    <div class="card p-6 mb-6">
        <h2 class="text-xl font-bold mb-4"><i class="fas fa-check-circle mr-2 text-green-600"></i>Proses Pengiriman</h2>
        <div class="space-y-4">
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">1</div>
                <div>
                    <h4 class="font-semibold text-gray-800">Pembayaran Diterima & Diverifikasi</h4>
                    <p class="text-sm text-gray-600">Setelah kami menerima dan memverifikasi bukti pembayaran Anda.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">2</div>
                <div>
                    <h4 class="font-semibold text-gray-800">Pesanan Diproses</h4>
                    <p class="text-sm text-gray-600">Tim kami akan mempersiapkan buku dalam waktu 1-2 hari kerja.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">3</div>
                <div>
                    <h4 class="font-semibold text-gray-800">Buku Dikemas dengan Aman</h4>
                    <p class="text-sm text-gray-600">Buku dikemas menggunakan bahan bubble wrap dan kardus berkualitas.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">4</div>
                <div>
                    <h4 class="font-semibold text-gray-800">Serahkan ke JNE</h4>
                    <p class="text-sm text-gray-600">Paket dikirim ke JNE dan Anda akan menerima nomor resi via email.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">5</div>
                <div>
                    <h4 class="font-semibold text-gray-800">Lacak Pengiriman</h4>
                    <p class="text-sm text-gray-600">Gunakan nomor resi di website JNE untuk melacak status pengiriman paket Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Tambahan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="card p-6">
            <h3 class="font-bold text-gray-800 mb-3"><i class="fas fa-question-circle mr-2 text-green-600"></i>Asuransi Pengiriman</h3>
            <p class="text-sm text-gray-700 mb-3">Semua pengiriman kami sudah tercover dengan asuransi pengiriman dari JNE untuk memastikan keamanan buku Anda.</p>
            <p class="text-sm text-gray-600">Jika terjadi kerusakan atau hilang, segera hubungi support kami untuk klaim asuransi.</p>
        </div>

        <div class="card p-6">
            <h3 class="font-bold text-gray-800 mb-3"><i class="fas fa-phone mr-2 text-green-600"></i>Jika Ada Masalah</h3>
            <p class="text-sm text-gray-700 mb-3">Jika paket mengalami keterlambatan atau kerusakan, hubungi support kami:</p>
            <ul class="text-sm text-gray-600 space-y-1">
                <li><strong>Email:</strong> <a href="mailto:ruangg.aksara@gmail.com" class="text-green-600 hover:underline">ruangg.aksara@gmail.com</a></li>
                <li><strong>WhatsApp:</strong> <a href="https://wa.me/628123456789" class="text-green-600 hover:underline" target="_blank">+62 812-3456-789</a></li>
            </ul>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="card p-6">
        <h2 class="text-xl font-bold mb-4"><i class="fas fa-comments mr-2 text-green-600"></i>Pertanyaan Umum</h2>
        <div class="space-y-4">
            <div class="border-b pb-4">
                <h4 class="font-semibold text-gray-800 mb-2">Bisakah ongkir lebih murah?</h4>
                <p class="text-sm text-gray-700">Biaya pengiriman tergantung pada layanan kurir dan lokasi penerima. Silakan cek tabel tarif di atas untuk estimasi.</p>
            </div>
            <div class="border-b pb-4">
                <h4 class="font-semibold text-gray-800 mb-2">Apa yang terjadi jika paket rusak?</h4>
                <p class="text-sm text-gray-700">Hubungi support kami dalam 48 jam penerimaan. Kami akan melakukan klaim ke JNE atau memberikan solusi terbaik.</p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800 mb-2">Berapa lama paket bisa dikembalikan?</h4>
                <p class="text-sm text-gray-700">Anda memiliki 7 hari setelah penerimaan untuk melakukan pengembalian (jika item rusak atau tidak sesuai).</p>
            </div>
        </div>
    </div>
</div>
@endsection