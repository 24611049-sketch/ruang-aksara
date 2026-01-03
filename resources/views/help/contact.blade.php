@extends('layouts.help')

@section('title', 'Hubungi Kami - Ruang Aksara')

@section('content')
<div class="max-w-6xl mx-auto px-4">
    <div class="card p-8 mb-6 text-center">
        <h1 class="text-2xl font-bold mb-1"><i class="fas fa-headset mr-2"></i> Hubungi Kami</h1>
        <p class="text-gray-600">Tim support kami siap membantu Anda. Isi formulir di bawah atau gunakan kontak langsung.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card p-6">
            <h2 class="font-semibold mb-4">Informasi Kontak</h2>

            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="text-green-600 text-2xl"><i class="fas fa-envelope"></i></div>
                    <div>
                        <div class="font-semibold">Email</div>
                        <div class="text-sm text-gray-600"><a href="mailto:ruangg.aksara@gmail.com" class="text-green-600 hover:underline">ruangg.aksara@gmail.com</a><br><small>Response biasanya dalam 1-2 jam kerja</small></div>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="text-green-600 text-2xl"><i class="fas fa-phone"></i></div>
                    <div>
                        <div class="font-semibold">Telepon</div>
                        <div class="text-sm text-gray-600"><a href="tel:+62274123456" class="text-green-600 hover:underline">(0274) 123-456</a><br><small>Senin - Jumat, 08:00-17:00</small></div>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="text-green-600 text-2xl"><i class="fab fa-whatsapp"></i></div>
                    <div>
                        <div class="font-semibold">WhatsApp</div>
                        <div class="text-sm text-gray-600"><a href="https://wa.me/628123456789" class="text-green-600 hover:underline" target="_blank">+62 812-3456-789</a><br><small>Setiap hari, 08:00-22:00</small></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h2 class="font-semibold mb-4">Kirim Pesan</h2>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('help.contact.submit') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                    <input name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border rounded" />
                    @error('name') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border rounded" />
                    @error('email') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Subjek</label>
                    <select name="subject" class="w-full px-3 py-2 border rounded">
                        <option value="Pertanyaan tentang produk">Pertanyaan tentang produk</option>
                        <option value="Masalah dengan pesanan">Masalah dengan pesanan</option>
                        <option value="Bantuan teknis">Bantuan teknis</option>
                        <option value="Kerjasama">Kerjasama</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Pesan</label>
                    <textarea name="message" rows="5" required class="w-full px-3 py-2 border rounded">{{ old('message') }}</textarea>
                    @error('message') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded">Kirim Pesan</button>
            </form>
        </div>
    </div>
</div>
@endsection