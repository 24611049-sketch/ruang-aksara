<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - Ruang Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            background: linear-gradient(rgba(29,78,63,0.85), rgba(16,59,46,0.85)), url('/images/background.jpg') center/cover fixed no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0px 1000px #ffffff inset !important;
            -webkit-text-fill-color: #0f172a !important;
        }
    </style>
</head>
<body>
    <div class="w-full max-w-5xl mx-auto px-6 md:px-10 py-12">
        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">
                <i class="fas fa-book mr-2"></i>Ruang Aksara
            </h1>
            <p class="text-green-200 text-lg">Daftar Akun Baru</p>
        </div>

        <!-- Card -->
        <div class="bg-gradient-to-br from-green-700/95 to-green-800/95 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden border-2 border-white/20">
            <div class="md:grid md:grid-cols-2">
                <div class="hidden md:block bg-gradient-to-b from-green-800/70 to-green-900/60 p-10 border-r border-white/10 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center text-yellow-200 text-xl font-bold mb-8">
                            <span class="w-12 h-12 mr-3 rounded-full bg-yellow-400/20 flex items-center justify-center shadow-lg">
                                <i class="fas fa-shield-alt text-2xl"></i>
                            </span>
                            Aman dan Terpercaya
                        </div>
                        
                        <ul class="space-y-5 text-green-50/95 leading-relaxed text-base">
                            <li class="flex items-start">
                                <i class="fas fa-circle-check text-yellow-300 mt-1 mr-3 text-lg"></i>
                                <span>Akses langsung ke ribuan koleksi buku dan rekomendasi harian yang dipersonalisasi.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle-check text-yellow-300 mt-1 mr-3 text-lg"></i>
                                <span>Poin loyalitas setiap transaksi untuk diskon dan hadiah menarik di pembelian berikutnya.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle-check text-yellow-300 mt-1 mr-3 text-lg"></i>
                                <span>Pengiriman cepat dengan ongkir hemat serta pelacakan pengiriman real-time.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle-check text-yellow-300 mt-1 mr-3 text-lg"></i>
                                <span>Layanan peminjaman buku dari toko offline dengan sistem yang mudah dan praktis.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle-check text-yellow-300 mt-1 mr-3 text-lg"></i>
                                <span>Wishlist untuk menyimpan buku favorit yang ingin dibaca atau dibeli nanti.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle-check text-yellow-300 mt-1 mr-3 text-lg"></i>
                                <span>Sistem keamanan data terjamin dengan enkripsi standar industri.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Stats -->
                    <div class="mt-8 pt-6 border-t border-white/20">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-yellow-300">5000+</div>
                                <div class="text-xs text-green-200 mt-1">Koleksi Buku</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-yellow-300">1000+</div>
                                <div class="text-xs text-green-200 mt-1">Member Aktif</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-yellow-300">99%</div>
                                <div class="text-xs text-green-200 mt-1">Kepuasan</div>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial -->
                    <div class="mt-6 bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-300 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star ml-1"></i>
                                <i class="fas fa-star ml-1"></i>
                                <i class="fas fa-star ml-1"></i>
                                <i class="fas fa-star ml-1"></i>
                            </div>
                        </div>
                        <p class="text-white/90 text-sm italic">"Pelayanan sangat memuaskan, buku lengkap dan pengiriman cepat!"</p>
                        <p class="text-green-200 text-xs mt-2 font-semibold">- Siti Nurhaliza, Jakarta</p>
                    </div>
                </div>

                <div class="p-8 md:p-10">
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                <!-- Google Button -->
                <div class="mb-6">
                    <a href="{{ route('google.register.picker') }}" class="w-full flex items-center justify-center px-6 py-3 rounded-lg border-2 border-white bg-white hover:bg-gray-100 transition-all duration-200 text-gray-800 font-bold shadow-lg">
                        <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Daftar dengan Google
                    </a>
                </div>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/40"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-3 bg-green-700/60 backdrop-blur text-white font-medium">Atau isi form manual</span>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Nama -->
                    <div class="mb-5">
                        <label for="name" class="block text-white font-semibold mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">
                                <i class="fas fa-user"></i>
                            </span>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required
                                   class="block w-full rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 pl-12 pr-4 py-3 text-gray-900 bg-white"
                                   placeholder="Masukkan nama lengkap">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-5">
                        <label for="email" class="block text-white font-semibold mb-2">Email</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                   class="block w-full rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 pl-12 pr-4 py-3 text-gray-900 bg-white"
                                   placeholder="example@gmail.com">
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-5">
                        <label for="alamat" class="block text-white font-semibold mb-2">Alamat</label>
                        <div class="relative">
                            <span class="absolute left-4 top-4 text-gray-500">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <textarea id="alamat" name="alamat" rows="2" required
                                          class="block w-full rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 pl-12 pr-4 py-3 text-gray-900 bg-white resize-none"
                                          placeholder="Masukkan alamat (contoh: Jl. Merdeka No. 1)">{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                    <!-- Province / City / District -->
                    <div class="mb-5 grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label for="province" class="block text-white font-semibold mb-2">Provinsi</label>
                            <select id="province" name="province" required class="block w-full rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 px-3 py-2 bg-white text-gray-900">
                                <option value="">Pilih provinsi</option>
                                @foreach(config('indonesia.provinces') as $prov)
                                    <option value="{{ $prov }}" {{ old('province') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="city" class="block text-white font-semibold mb-2">Kota/Kabupaten</label>
                            <input id="city" name="city" type="text" value="{{ old('city') }}" required placeholder="Kota atau kabupaten"
                                   class="block w-full rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 px-3 py-2 bg-white text-gray-900">
                        </div>

                        <div>
                            <label for="district" class="block text-white font-semibold mb-2">Kecamatan</label>
                            <input id="district" name="district" type="text" value="{{ old('district') }}" required placeholder="Kecamatan"
                                   class="block w-full rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 px-3 py-2 bg-white text-gray-900">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-5">
                        <label for="password" class="block text-white font-semibold mb-2">Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input id="password" name="password" type="password" required
                                   class="block w-full rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 pl-12 pr-4 py-3 text-gray-900 bg-white"
                                   placeholder="Minimal 8 karakter">
                        </div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-white font-semibold mb-2">Konfirmasi Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                   class="block w-full rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 pl-12 pr-4 py-3 text-gray-900 bg-white"
                                   placeholder="Ulangi password">
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mb-5">
                        <button type="submit" class="w-full px-6 py-3 rounded-lg text-white font-bold text-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:-translate-y-1 shadow-lg">
                            <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center text-white">
                        <p class="text-sm">Sudah punya akun? <a href="{{ route('login') }}" class="text-yellow-300 hover:text-yellow-200 font-semibold underline">Masuk di sini</a></p>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
