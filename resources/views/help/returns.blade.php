@extends('layouts.help')

@section('title', 'Kebijakan Pengembalian - Ruang Aksara')

@section('content')
<div class="max-w-5xl mx-auto px-4">
    <div class="card p-8 mb-6 text-center">
        <h1 class="text-2xl font-bold"><i class="fas fa-exchange-alt mr-2"></i> Kebijakan Pengembalian</h1>
        <p class="text-gray-600">Proses pengembalian dan syarat yang berlaku.</p>
    </div>

    <div class="card p-6 mb-4">
        <h2 class="font-semibold mb-2">Syarat Pengembalian</h2>
        <ul class="list-disc ml-6 text-gray-700 space-y-2">
            <li>Produk dapat dikembalikan dalam waktu <strong>7 hari</strong> setelah diterima.</li>
            <li>Buku harus dalam kondisi <strong>seperti baru</strong> tanpa kerusakan fisik.</li>
            <li>Cover dan halaman tidak boleh terlipat, robek, atau kotor.</li>
            <li>Stiker harga asli masih terpasang jika ada.</li>
            <li>Beberapa promosi atau diskon tidak dapat dikembalikan — periksa syarat produk.</li>
        </ul>
    </div>

    <div class="card p-6 mb-4">
        <h2 class="font-semibold mb-2">Alasan yang Diterima</h2>
        <ul class="list-disc ml-6 text-gray-700 space-y-2">
            <li>Buku cacat produksi (halaman kosong, salah cetak).</li>
            <li>Buku yang salah dikirim (judul atau edisi tidak sesuai pesanan).</li>
            <li>Buku rusak saat diterima.</li>
        </ul>
    </div>

    <div class="card p-6">
        <h2 class="font-semibold mb-2">Proses Pengembalian</h2>
        <ol class="list-decimal ml-6 text-gray-700 space-y-2">
            <li>Hubungi customer service via WhatsApp atau Email dan sertakan bukti (foto).</li>
            <li>Tim kami akan memverifikasi dan memberikan instruksi lebih lanjut.</li>
            <li>Jika disetujui, ikuti petunjuk pengembalian dan kirim paket ke alamat yang diberikan.</li>
            <li>Setelah diterima dan diperiksa, dana akan dikembalikan sesuai metode pembayaran semula.</li>
        </ol>
    </div>
</div>
@endsection