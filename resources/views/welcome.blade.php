<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ruang Aksara - Toko Buku Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        html, body { height: 100%; }
        /* Background: full-screen, fixed, no-repeat */
        body {
            margin: 0;
            background: linear-gradient(rgba(29,78,63,0.55), rgba(16,59,46,0.55)), url('/images/background.jpg') center/cover fixed no-repeat;
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                min-height: 100vh;
        }
        /* Improve text visibility on top of green overlay */
        h1, h2, h3, p { text-shadow: 0 2px 6px rgba(3,7,18,0.45); }
        .hero-title-highlight { color: #ffffff !important; }
            /* Floating help button + modal */
            .help-float {
                position: fixed;
                right: 24px;
                bottom: 24px;
                width: 56px;
                height: 56px;
                border-radius: 9999px;
                background: linear-gradient(135deg,#f97316,#10b981);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 30px rgba(2,6,23,0.35);
                z-index: 1200;
                cursor: pointer;
                transition: transform .12s ease, box-shadow .12s ease;
            }
            .help-float:hover { transform: translateY(-4px); box-shadow: 0 16px 36px rgba(2,6,23,0.45); }
            .help-float .badge { position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; font-size: 11px; padding: 2px 6px; border-radius: 9999px; }

            .help-modal-backdrop { position: fixed; inset: 0; background: rgba(2,6,23,0.6); display: none; align-items: center; justify-content: center; z-index: 1190; }
            .help-modal { background: white; width: 96%; max-width: 440px; border-radius: 12px; padding: 20px; box-shadow: 0 10px 40px rgba(2,6,23,0.4); }
            .help-modal h4 { margin-top: 0; margin-bottom: 8px; }
            .help-modal p { margin-bottom: 12px; color: #334155; }
            .help-modal .actions { display:flex; gap:10px; }
            @media (min-width: 768px) { .help-float{ right:32px; bottom:32px; } }
        /* Normalize browser autofill (make background white and text dark) */
        input:-webkit-autofill,
        textarea:-webkit-autofill,
        select:-webkit-autofill {
            -webkit-box-shadow: 0 0 0px 1000px #ffffff inset !important;
            -webkit-text-fill-color: #0f172a !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }
    </style>
    <style>
        /* Feature cards: improved contrast and spacing on hero */
        .feature-card {
            background: rgba(255,255,255,0.06);
            border-radius: 12px;
            padding: 28px 20px;
            max-width: 360px;
            box-shadow: 0 12px 30px rgba(2,6,23,0.45);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 170px;
        }
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 9999px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:28px;
            box-shadow: 0 8px 20px rgba(2,6,23,0.35);
        }
        .feature-title { font-size:1.125rem; font-weight:600; margin-top:12px; margin-bottom:8px; }
        .feature-desc { color: rgba(236,253,245,0.9); }
        @media (max-width: 767px) {
            .feature-card { padding:22px; margin:0 auto; }
            .feature-icon { width:64px; height:64px; font-size:22px; }
        }
        
        /* LOGIN PANEL - FONT & COLOR FIX */
        .login-panel {
            background: linear-gradient(135deg, rgba(45, 90, 61, 0.95), rgba(30, 62, 42, 0.95)) !important;
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5) !important;
        }
        .login-panel h3 {
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 2rem !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3) !important;
        }
        .login-panel label {
            color: #ffffff !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            text-shadow: 0 1px 3px rgba(0,0,0,0.3) !important;
            margin-bottom: 0.5rem !important;
        }
        .login-panel input {
            background: rgba(255, 255, 255, 0.95) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            font-size: 1rem !important;
            padding: 0.75rem 2.5rem !important;
        }
        .login-panel input:focus {
            background: rgba(255, 255, 255, 1) !important;
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
        }
        .login-panel button[type="submit"] {
            font-weight: 700 !important;
            font-size: 1.1rem !important;
            padding: 0.75rem !important;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4) !important;
        }
        .login-panel button[type="submit"]:hover {
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.6) !important;
            transform: translateY(-2px);
        }
        .login-panel .text-link {
            color: #fde047 !important;
            text-decoration: underline;
            font-weight: 600;
            transition: all 0.2s;
        }
        .login-panel .text-link:hover {
            color: #fef08a !important;
            text-shadow: 0 0 8px rgba(253, 224, 71, 0.5);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="shadow-lg sticky top-0 z-50" style="background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%) !important;">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-book"></i><span>Ruang Aksara</span>
                </a>
                <div class="flex gap-2">
                    <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 transition font-semibold shadow-sm">Home</span>
                    <a href="{{ route('about') }}" class="px-4 py-2 rounded-lg border border-white/35 text-white/90 hover:text-white hover:bg-white/10 transition font-semibold">About</a>
                    <a href="/books" class="px-4 py-2 rounded-lg border border-white/35 text-white/90 hover:text-white hover:bg-white/10 transition font-semibold">Katalog Buku</a>
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-100 text-red-600 px-4 py-2 rounded-lg hover:bg-red-200 transition font-semibold">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center text-white relative">
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-5xl font-bold mb-6 leading-tight">
                        Selamat Datang di <span class="hero-title-highlight" style="color:#FFD600 !important;">Ruang Aksara</span>
                    </h1>
                    <p class="text-xl mb-8 opacity-95">
                        Temukan buku favorit Anda dan mulailah petualangan membaca yang tak terlupakan. 
                        Ribuan buku berkualitas menanti untuk dibaca.
                    </p>
                    <div class="flex gap-4">
                        <a href="/books" class="bg-white text-green-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-green-50 transition flex items-center">
                            <i class="fas fa-book-open mr-3"></i>Jelajahi Katalog
                        </a>
                        <a href="/register" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-green-600 transition flex items-center">
                            <i class="fas fa-user-plus mr-3"></i>Daftar Sekarang
                        </a>
                    </div>
                </div>
                <div class="text-center">
                    <div class="flex justify-center lg:justify-end">
                        <div class="w-full max-w-md login-panel p-8 shadow-xl rounded-2xl backdrop-blur-lg" id="login-form">
                            <div class="h-1 w-20 bg-yellow-400 rounded mb-6 mx-auto"></div>
                            <h3 class="text-3xl font-bold text-white mb-6 text-center">Masuk</h3>

                            @if($errors->any())
                                <div class="mb-4 text-sm text-red-100 bg-red-600/80 p-3 rounded-lg">
                                    <ul class="list-disc pl-5 mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="mb-4 text-sm text-red-100 bg-red-600/80 p-3 rounded-lg">{{ session('error') }}</div>
                            @endif

                            @if(session('success'))
                                <div class="mb-4 text-sm text-green-100 bg-green-600/80 p-3 rounded-lg">
                                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                                </div>
                            @endif

                            @if(session('info'))
                                <div class="mb-4 text-sm text-blue-100 bg-blue-600/80 p-3 rounded-lg">
                                    <i class="fas fa-info-circle mr-2"></i>{{ session('info') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login.post') }}">
                                @csrf

                                <div class="mb-5 relative">
                                    <label for="email" class="block text-white font-semibold mb-2">Email</label>
                                    <span class="absolute left-3 top-11 text-gray-600"><i class="fas fa-envelope"></i></span>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                           class="block w-full rounded-lg border-2 focus:border-green-500 focus:ring-2 focus:ring-green-300 pl-10 pr-4 py-3 text-gray-900 bg-white transition-all duration-200"
                                           placeholder="nama@email.com">
                                </div>

                                <div class="mb-5 relative">
                                    <label for="password" class="block text-white font-semibold mb-2">Password</label>
                                    <span class="absolute left-3 top-11 text-gray-600"><i class="fas fa-lock"></i></span>
                                    <input id="password" name="password" type="password" required
                                           class="block w-full rounded-lg border-2 focus:border-green-500 focus:ring-2 focus:ring-green-300 pl-10 pr-4 py-3 text-gray-900 bg-white transition-all duration-200"
                                           placeholder="Masukkan password">
                                </div>

                                <div class="mb-5">
                                    <button type="submit" class="w-full px-6 py-3 rounded-lg text-white font-bold text-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:-translate-y-1">
                                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                                    </button>
                                </div>

                                <!-- Divider -->
                                <div class="relative my-6">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-400"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-3 bg-green-800/60 backdrop-blur text-gray-300 font-medium">Atau masuk dengan</span>
                                    </div>
                                </div>

                                <!-- Google Login Button -->
                                <div class="mb-5">
                                    <a href="{{ route('google.login.picker') }}" class="w-full flex items-center justify-center px-6 py-3 rounded-lg border-2 border-gray-400 bg-white hover:bg-gray-100 transition-all duration-200 text-gray-800 font-bold">
                                        <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                        </svg>
                                        Masuk dengan Google
                                    </a>
                                </div>

                                <div class="text-center text-white">
                                    <p class="text-sm">Belum punya akun? <a href="/register" class="text-link">Daftar di sini</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Books removed per request (duplicate CTA removed). -->

    <!-- Footer -->
    <footer class="text-white py-8" style="background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%) !important;">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
                <div class="lg:col-span-2 space-y-4">
                    <h3 class="text-2xl font-bold flex items-center gap-2">
                        <i class="fas fa-book"></i>
                        <span style="color: #FFD600;">Ruang Aksara</span>
                    </h3>
                    <p class="text-gray-200 text-sm leading-relaxed">
                        Platform buku yang berfokus pada kurasi bacaan bermanfaat dan dukungan pelanggan cepat.
                    </p>
                    <div>
                        <h4 class="text-lg font-semibold mb-3">Kontak Kami</h4>
                        <div class="flex flex-wrap gap-3">
                            <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-2xl px-4 py-3 text-sm">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/20"><i class="fas fa-map-marker-alt"></i></span>
                                <div>
                                    <p class="font-semibold text-white">Alamat</p>
                                    <p class="text-gray-200">Campus UII Main Library, Sleman</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-2xl px-4 py-3 text-sm">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/20"><i class="fas fa-phone"></i></span>
                                <div>
                                    <p class="font-semibold text-white">Telepon</p>
                                    <a href="tel:+62274123456" class="text-gray-200 hover:text-white">(0274) 123-456</a>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-2xl px-4 py-3 text-sm">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/20"><i class="fas fa-envelope"></i></span>
                                <div>
                                    <p class="font-semibold text-white">Email</p>
                                    <a href="mailto:ruangg.aksara@gmail.com" class="text-gray-200 hover:text-white">ruangg.aksara@gmail.com</a>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-2xl px-4 py-3 text-sm">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/20"><i class="fab fa-whatsapp"></i></span>
                                <div>
                                    <p class="font-semibold text-white">WhatsApp</p>
                                    <a href="https://wa.me/628123456789" target="_blank" class="text-gray-200 hover:text-white">+62 812-3456-789</a>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-2xl px-4 py-3 text-sm">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/20"><i class="fab fa-instagram"></i></span>
                                <div>
                                    <p class="font-semibold text-white">Instagram</p>
                                    <a href="https://www.instagram.com/ruanggaksara?igsh=MXZ2M3JwdHZiYWZzdA==" target="_blank" class="text-gray-200 hover:text-white">@ruanggaksara</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-3 space-y-4">
                    <h4 class="text-lg font-semibold flex items-center gap-2"><i class="fas fa-location-dot"></i> Lokasi Kami</h4>
                    <p class="text-gray-200 text-sm">Kunjungi kami langsung atau lihat rute melalui tautan berikut.</p>
                    <a href="https://maps.app.goo.gl/bPb1terfQACK9VCF7" target="_blank" class="inline-flex items-center gap-3 px-5 py-3 rounded-2xl border border-white/15 bg-white/10 text-sm text-yellow-200 hover:text-white transition">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/20"><i class="fas fa-external-link-alt"></i></span>
                        <span>
                            <span class="block text-white font-semibold">Google Maps</span>
                            <span class="text-gray-200">Campus UII Main Library</span>
                        </span>
                    </a>
                </div>
            </div>
            <div class="border-t border-white/20 mt-8 pt-4 text-center text-gray-200 text-sm">
                <p>&copy; 2025 Ruang Aksara. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <!-- Floating help button -->
    <div id="helpBackdrop" class="help-modal-backdrop" aria-hidden="true">
        <div class="help-modal" role="dialog" aria-modal="true" aria-labelledby="helpTitle">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <h4 id="helpTitle">Butuh Bantuan?</h4>
                <button id="helpClose" aria-label="Tutup" style="background:none;border:none;font-size:18px;">&times;</button>
            </div>
            <p>Butuh bantuan dengan pesanan atau mencari buku? Pilih salah satu opsi di bawah atau kunjungi Pusat Bantuan.</p>
            <div class="actions">
                <a href="/help" class="px-4 py-2 bg-green-600 text-white rounded-lg">Pusat Bantuan</a>
                <a href="mailto:ruangg.aksara@gmail.com" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg">Kirim Email</a>
            </div>
        </div>
    </div>

    <button id="helpButton" class="help-float" title="Butuh Bantuan?">
        <i class="fas fa-headset"></i>
        <span class="badge">?</span>
    </button>
</body>
<script>
    // Clear Google session and reload
    function clearGoogleSession() {
        // Make AJAX call to clear the session
        fetch('{{ route("google.clear.session") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}'
            }
        }).then(() => {
            window.location.reload();
        });
    }

    // Help modal toggle
    (function(){
        const btn = document.getElementById('helpButton');
        const backdrop = document.getElementById('helpBackdrop');
        const close = document.getElementById('helpClose');
        if(!btn || !backdrop) return;
        btn.addEventListener('click', function(){ backdrop.style.display = 'flex'; backdrop.setAttribute('aria-hidden','false'); document.body.style.overflow='hidden'; });
        close && close.addEventListener('click', function(){ backdrop.style.display='none'; backdrop.setAttribute('aria-hidden','true'); document.body.style.overflow=''; });
        backdrop.addEventListener('click', function(e){ if(e.target === backdrop){ backdrop.style.display='none'; backdrop.setAttribute('aria-hidden','true'); document.body.style.overflow=''; } });
        // keyboard close
        document.addEventListener('keydown', function(e){ if(e.key === 'Escape' && backdrop.style.display === 'flex'){ backdrop.style.display='none'; backdrop.setAttribute('aria-hidden','true'); document.body.style.overflow=''; } });
    })();
</script>
</html>