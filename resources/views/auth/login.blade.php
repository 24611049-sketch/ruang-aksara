@extends('layouts.guest')

@section('content')
    <!-- Hero Section (mirip dengan welcome) -->
    <section class="min-h-screen flex items-center text-white relative">
        <div class="absolute inset-0" style="background: linear-gradient(90deg, rgba(45,90,61,0.8), rgba(30,62,42,0.8));"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-5xl font-bold mb-6 leading-tight">
                        Selamat Datang di <span class="text-yellow-300">Ruang Aksara</span>
                    </h1>
                    <p class="text-xl mb-8 opacity-90">
                        Temukan buku favorit Anda dan mulailah petualangan membaca yang tak terlupakan.
                        Ribuan buku berkualitas menanti untuk dibaca.
                    </p>
                    <div class="flex gap-4">
                        <a href="/books" class="bg-white text-green-700 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-green-50 transition flex items-center">
                            <i class="fas fa-book-open mr-3"></i>Jelajahi Katalog
                        </a>
                        <a href="/register" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-green-700 transition flex items-center">
                            <i class="fas fa-user-plus mr-3"></i>Daftar Sekarang
                        </a>
                    </div>
                </div>

                <!-- Login card on the right, semi-transparent to match site style -->
                <div class="flex justify-center lg:justify-end">
                    <div class="w-full max-w-md card p-8">
                        <h3 class="text-2xl font-semibold text-green-800 mb-4">Masuk</h3>

                        @if($errors->any())
                            <div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">
                                <ul class="list-disc pl-5 mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                       class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-green-700 focus:ring focus:ring-green-200 px-3 py-2 text-gray-900">
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input id="password" name="password" type="password" required
                                       class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-green-700 focus:ring focus:ring-green-200 px-3 py-2 text-gray-900">
                            </div>

                            <div class="mb-6">
                                <button type="submit" class="w-full px-4 py-2 rounded text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 transition font-semibold">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                                </button>
                            </div>
                        </form>

                        <!-- Divider -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-gray-500 font-medium">Atau masuk dengan</span>
                            </div>
                        </div>

                        <!-- Google Login Button -->
                        <div class="mb-6">
                            <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white hover:bg-gray-50 transition text-gray-700 font-semibold">
                                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                Masuk dengan Google
                            </a>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('register') }}" class="text-green-700 font-semibold hover:text-green-800">Belum punya akun? Daftar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection