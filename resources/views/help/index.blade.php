@extends('layouts.help')

@section('title', 'Pusat Bantuan - Ruang Aksara')

@push('styles')
<style>
    /* Improve visibility & contrast of help panels */
    .content-card {
        background-color: rgba(255, 255, 255, 0.98) !important; /* more opaque */
        backdrop-filter: blur(4px);
        border-radius: 1.25rem;
        box-shadow: 0 18px 40px rgba(16, 24, 32, 0.08);
        border: 1px solid rgba(30, 62, 42, 0.04);
    }

    /* Help section tiles */
    .help-card {
        border-radius: 1rem;
        background: #ffffff;
        border: 1px solid rgba(30,62,42,0.04);
        box-shadow: 0 8px 20px rgba(16,24,32,0.04);
        transition: transform 0.12s ease, box-shadow 0.12s ease;
    }
    .help-card:hover { transform: translateY(-4px); box-shadow: 0 12px 26px rgba(16,24,32,0.06); }

    /* Search box should stand out over background image */
    .help-search input {
        border-radius: 9999px;
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 6px 18px rgba(16,24,32,0.06);
        color: #0f172a;
    }
    .help-search input::placeholder { color: rgba(15,23,42,0.45); }
    .help-search button {
        border-radius: 9999px;
        box-shadow: 0 6px 18px rgba(16,24,32,0.08);
    }

    /* Card link tiles inside Quick Links */
    .card-link { background: #ffffff; border: 1px solid rgba(30,62,42,0.03); }

    /* Ensure floating nav buttons (history) are visible above panels */
    .nav-buttons {
        position: fixed;
        right: 18px;
        bottom: 28px;
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
        z-index: 1300; /* above panels and modal */
    }
    .nav-buttons button { box-shadow: 0 8px 18px rgba(0,0,0,0.12); }
</style>
@endpush

@section('content')
<div class="mx-auto max-w-6xl space-y-8">
    <header class="content-card px-8 py-10 text-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-3">
            <i class="fas fa-hands-helping text-green-500 mr-2"></i>Pusat Bantuan
        </h1>
        <p class="text-gray-600 mb-6">Butuh bantuan dalam menggunakan Ruang Aksara? Temukan jawaban untuk pertanyaan Anda di sini.</p>

        <div class="help-search mx-auto flex max-w-2xl items-center gap-3">
            <input id="helpSearch" type="text" placeholder="Cari bantuan..." class="w-full px-5 py-3 border border-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600">
            <button id="helpSearchBtn" class="px-5 py-3 bg-green-600 text-white shadow-md hover:bg-green-700">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </header>

    <section class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="help-card p-6 text-center cursor-pointer" onclick="location.href='{{ route('help.faq') }}'">
            <div class="text-4xl text-green-600 mb-3">
                <i class="fas fa-question-circle"></i>
            </div>
            <h3 class="font-semibold text-lg mb-2 text-gray-800">Pertanyaan Umum (FAQ)</h3>
            <p class="text-gray-600 mb-4">Jawaban atas pertanyaan yang sering diajukan.</p>
            <a href="{{ route('help.faq') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 border border-green-600 text-green-600 rounded-full">Lihat FAQ<i class="fas fa-arrow-right text-sm"></i></a>
        </div>

        <div class="help-card p-6 text-center cursor-pointer" onclick="location.href='{{ route('help.shipping') }}'">
            <div class="text-4xl text-green-600 mb-3">
                <i class="fas fa-shipping-fast"></i>
            </div>
            <h3 class="font-semibold text-lg mb-2 text-gray-800">Pengiriman & Pengembalian</h3>
            <p class="text-gray-600 mb-4">Kebijakan pengiriman, estimasi, dan proses pengembalian.</p>
            <a href="{{ route('help.shipping') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 border border-green-600 text-green-600 rounded-full">Pelajari Lebih<i class="fas fa-arrow-right text-sm"></i></a>
        </div>

        <div class="help-card p-6 text-center cursor-pointer" onclick="location.href='{{ route('help.contact') }}'">
            <div class="text-4xl text-green-600 mb-3">
                <i class="fas fa-headset"></i>
            </div>
            <h3 class="font-semibold text-lg mb-2 text-gray-800">Hubungi Kami</h3>
            <p class="text-gray-600 mb-4">Tim support kami siap membantu Anda.</p>
            <a href="{{ route('help.contact') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 border border-green-600 text-green-600 rounded-full">Hubungi<i class="fas fa-arrow-right text-sm"></i></a>
        </div>
    </section>

    <section class="content-card p-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">
            <i class="fas fa-bolt text-yellow-500 mr-3"></i>Tautan Cepat
        </h2>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <a href="{{ route('help.faq') }}" class="card-link flex items-start gap-4 p-4 border border-green-100 rounded-xl bg-white">
                <div class="text-green-600 text-2xl"><i class="fas fa-book"></i></div>
                <div>
                    <div class="font-semibold text-gray-800">Cara Memesan Buku</div>
                    <div class="text-sm text-gray-600">Panduan langkah demi langkah</div>
                </div>
            </a>

            <a href="{{ route('help.returns') }}" class="card-link flex items-start gap-4 p-4 border border-green-100 rounded-xl bg-white">
                <div class="text-green-600 text-2xl"><i class="fas fa-exchange-alt"></i></div>
                <div>
                    <div class="font-semibold text-gray-800">Kebijakan Pengembalian</div>
                    <div class="text-sm text-gray-600">Syarat dan ketentuan</div>
                </div>
            </a>

            <a href="{{ route('help.payment-methods') }}" class="card-link flex items-start gap-4 p-4 border border-green-100 rounded-xl bg-white">
                <div class="text-green-600 text-2xl"><i class="fas fa-credit-card"></i></div>
                <div>
                    <div class="font-semibold text-gray-800">Metode Pembayaran</div>
                    <div class="text-sm text-gray-600">Pilihan pembayaran yang tersedia</div>
                </div>
            </a>

            <a href="{{ route('help.account-security') }}" class="card-link flex items-start gap-4 p-4 border border-green-100 rounded-xl bg-white">
                <div class="text-green-600 text-2xl"><i class="fas fa-user-shield"></i></div>
                <div>
                    <div class="font-semibold text-gray-800">Keamanan Akun</div>
                    <div class="text-sm text-gray-600">Tips menjaga keamanan akun</div>
                </div>
            </a>
        </div>
    </section>

    <section class="content-card p-8 text-center">
        <h2 class="text-xl font-semibold text-gray-800 mb-3">
            <i class="fas fa-envelope text-blue-500 mr-2"></i>Masih Butuh Bantuan?
        </h2>
        <p class="text-gray-600 mb-6">Tim support kami siap membantu Anda 7 hari seminggu.</p>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-xl border border-green-100 bg-white p-6">
                <div class="text-2xl text-green-600 mb-1"><i class="fas fa-envelope"></i></div>
                <div class="font-semibold text-gray-800">Email</div>
                <div class="text-sm text-gray-600"><a href="mailto:ruangg.aksara@gmail.com" class="text-green-600 hover:underline">ruangg.aksara@gmail.com</a></div>
            </div>
            <div class="rounded-xl border border-green-100 bg-white p-6">
                <div class="text-2xl text-green-600 mb-1"><i class="fas fa-phone"></i></div>
                <div class="font-semibold text-gray-800">Telepon</div>
                <div class="text-sm text-gray-600"><a href="tel:+62274123456" class="text-green-600 hover:underline">(0274) 123-456</a></div>
            </div>
            <div class="rounded-xl border border-green-100 bg-white p-6">
                <div class="text-2xl text-green-600 mb-1"><i class="fab fa-whatsapp"></i></div>
                <div class="font-semibold text-gray-800">WhatsApp</div>
                <div class="text-sm text-gray-600"><a href="https://wa.me/628123456789" class="text-green-600 hover:underline" target="_blank">+62 812-3456-789</a></div>
            </div>
        </div>
        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="rounded-xl border border-green-100 bg-white p-6">
                <div class="text-2xl text-green-600 mb-1"><i class="fab fa-instagram"></i></div>
                <div class="font-semibold text-gray-800">Instagram</div>
                <div class="text-sm text-gray-600"><a href="https://www.instagram.com/ruanggaksara?igsh=MXZ2M3JwdHZiYWZzdA==" class="text-green-600 hover:underline" target="_blank">@ruanggaksara</a></div>
            </div>
            <div class="rounded-xl border border-green-100 bg-white p-6">
                <div class="text-2xl text-green-600 mb-1"><i class="fas fa-clock"></i></div>
                <div class="font-semibold text-gray-800">Jam Operasional</div>
                <div class="text-sm text-gray-600">Senin - Minggu, 08:00 - 22:00 WIB</div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('helpSearch');
    const button = document.getElementById('helpSearchBtn');

    if (!input || !button) {
        return;
    }

    const doSearch = () => {
        const query = (input.value || '').toLowerCase().trim();

        if (!query) {
            return;
        }

        if (query.includes('cara') || query.includes('pesan') || query.includes('order') || query.includes('ordering')) {
            window.location.href = '{{ route('help.faq') }}';
            return;
        }

        if (query.includes('pembayaran') || query.includes('metode') || query.includes('payment')) {
            window.location.href = '{{ route('help.faq') }}';
            return;
        }

        if (query.includes('pengiriman') || query.includes('kirim') || query.includes('ongkir')) {
            window.location.href = '{{ route('help.shipping') }}';
            return;
        }

        if (query.includes('pengembalian') || query.includes('return') || query.includes('refund') || query.includes('ganti')) {
            window.location.href = '{{ route('help.returns') }}';
            return;
        }

        if (query.includes('kontak') || query.includes('hubung') || query.includes('support') || query.includes('email') || query.includes('telepon')) {
            window.location.href = '{{ route('help.contact') }}';
            return;
        }

        window.location.href = '{{ route('help.faq') }}';
    };

    input.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            doSearch();
        }
    });

    button.addEventListener('click', doSearch);
});
</script>
@endpush

