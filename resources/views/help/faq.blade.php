@extends('layouts.help')

@section('title', 'Pertanyaan Umum - Ruang Aksara')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="card p-8 mb-6 text-center">
        <h1 class="text-2xl font-bold mb-2"><i class="fas fa-question-circle mr-2"></i> Pertanyaan Umum</h1>
        <p class="text-gray-600">Jawaban untuk pertanyaan yang sering diajukan. Klik pertanyaan untuk menampilkan jawaban.</p>
    </div>

    <div class="space-y-4">
        @php
            $faqs = [
                ['q' => 'Bagaimana cara memesan buku?', 'a' => "1) Login atau daftar akun.\n2) Jelajahi katalog buku dan buka halaman detail produk.\n3) Pilih jumlah yang diinginkan.\n4) Klik 'Tambah ke Keranjang'.\n5) Pergi ke keranjang dan klik 'Lanjutkan ke Checkout'.\n6) Lengkapi data pengiriman dan pilih metode pembayaran.\n7) Konfirmasi checkout dan lakukan pembayaran sesuai instruksi.\n8) Tunggu konfirmasi dan nomor resi via email."],
                ['q' => 'Metode pembayaran apa saja yang diterima?', 'a' => "Transfer Bank BCA (1234567890), Bank Mandiri (9876543210), Bank BNI (1122334455), dan Pembayaran Tunai/COD."],
                // FAQ about free shipping removed per request
                ['q' => 'Bagaimana cara melacak pesanan saya?', 'a' => "Setelah pesanan dikirim, Anda akan menerima nomor resi via email. Gunakan nomor resi di website kurir untuk melacak status pengiriman."],
                ['q' => 'Bagaimana kebijakan pengembalian?', 'a' => "Pengembalian diterima dalam 7 hari jika produk rusak atau salah kirim. Hubungi support dengan bukti (foto)."],
                ['q' => 'Apakah saya bisa membatalkan pesanan?', 'a' => "Pesanan dapat dibatalkan sebelum diproses/diterima oleh kurir. Hubungi support segera jika ingin membatalkan."],
                ['q' => 'Saya tidak menerima email konfirmasi, apa yang harus dilakukan?', 'a' => "Periksa folder spam; jika tidak ada, hubungi support dengan nomor pesanan dan email Anda."],
                ['q' => 'Bagaimana cara mengajukan refund?', 'a' => "Jika pengembalian disetujui, refund akan diproses sesuai metode pembayaran semula dan memakan waktu beberapa hari kerja."],
            ];
        @endphp

        @foreach($faqs as $i => $item)
        <div class="card overflow-hidden">
            <button type="button" class="faq-q w-full text-left px-6 py-4 flex justify-between items-center">
                <span class="font-semibold">{{ $item['q'] }}</span>
                <i class="fas fa-chevron-down transition-transform duration-200"></i>
            </button>
            <div class="faq-a px-6 pb-6 hidden text-gray-700 whitespace-pre-line">{{ $item['a'] }}</div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.faq-q').forEach(btn => {
        btn.addEventListener('click', function(){
            const answer = this.nextElementSibling;
            const icon = this.querySelector('i');
            const isOpen = !answer.classList.contains('hidden');
            if(isOpen){
                answer.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            } else {
                answer.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            }
        });
    });
});
</script>
@endpush