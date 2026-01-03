<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About - Ruang Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            background: linear-gradient(rgba(29,78,63,0.55), rgba(16,59,46,0.55)), url('/images/background.jpg') center/cover fixed no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .glass-card { 
            background: rgba(255,255,255,0.95); 
            backdrop-filter: blur(10px); 
            box-shadow: 0 15px 45px rgba(0,0,0,0.12); 
        }
        .team-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .story-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 12px;
        }
        .floating-card {
            transform: translateY(0);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .floating-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        .owner-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: bold;
        }
        .admin-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: bold;
        }
        .service-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 20px;
            font-size: 30px;
        }
        .peminjaman-icon {
            background: linear-gradient(135deg, #93c5fd, #3b82f6);
            color: white;
        }
        .penjualan-icon {
            background: linear-gradient(135deg, #86efac, #10b981);
            color: white;
        }
        .komunitas-icon {
            background: linear-gradient(135deg, #fbbf24, #d97706);
            color: white;
        }
    </style>
</head>
<body class="text-gray-900">
    <nav class="shadow-lg sticky top-0 z-50" style="background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%) !important;">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-book"></i><span>Ruang Aksara</span>
                </a>
                <div class="flex gap-2">
                    <a href="/" class="px-4 py-2 rounded-lg border border-white/35 text-white/90 hover:text-white hover:bg-white/10 transition font-semibold">Home</a>
                    <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 transition font-semibold shadow-sm">About</span>
                    <a href="/books" class="px-4 py-2 rounded-lg border border-white/35 text-white/90 hover:text-white hover:bg-white/10 transition font-semibold">Katalog Buku</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-16">
        <div class="container mx-auto px-4 max-w-6xl space-y-16">
            <!-- Header Section -->
            <div class="text-center text-white">
                <p class="text-sm font-semibold text-green-100 mb-2 uppercase tracking-widest">Tentang Kami</p>
                <h1 class="text-5xl font-extrabold mb-4 text-white">Ruang Aksara</h1>
                <p class="text-xl text-green-50 max-w-3xl mx-auto leading-relaxed">Perpustakaan digital pertama di Indonesia yang menggabungkan layanan peminjaman buku dengan penjualan buku baru & bekas berkualitas.</p>
            </div>

            <!-- Layanan Kami Section -->
            <div class="p-8 rounded-2xl glass-card floating-card">
                <h2 class="text-3xl font-bold text-center text-green-900 mb-10">Layanan Unggulan Kami</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="p-6 rounded-xl border border-blue-100 bg-gradient-to-b from-blue-50 to-white text-center">
                        <div class="service-icon peminjaman-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Peminjaman Buku Digital & Fisik</h3>
                        <p class="text-gray-600 mb-4">Akses ribuan buku digital atau pinjam buku fisik dengan sistem pengiriman ke seluruh Indonesia. Masa pinjam fleksibel hingga 30 hari.</p>
                        <ul class="text-sm text-gray-700 text-left space-y-2">
                            <li class="flex items-center gap-2"><i class="fas fa-check text-green-500"></i> Gratis untuk anggota premium</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check text-green-500"></i> Pengembalian melalui kurir</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check text-green-500"></i> Notifikasi pengembalian otomatis</li>
                        </ul>
                    </div>
                    
                    <div class="p-6 rounded-xl border border-green-100 bg-gradient-to-b from-green-50 to-white text-center">
                        <div class="service-icon penjualan-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Penjualan Buku Baru & Bekas</h3>
                        <p class="text-gray-600 mb-4">Temukan buku baru terbitan terkini atau buku bekas berkualitas dengan harga terjangkau. Semua buku melalui proses kurasi ketat.</p>
                        <ul class="text-sm text-gray-700 text-left space-y-2">
                            <li class="flex items-center gap-2"><i class="fas fa-check text-green-500"></i> Garansi kualitas 100%</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check text-green-500"></i> Diskon untuk anggota</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check text-green-500"></i> Pengiriman cepat 1-3 hari</li>
                        </ul>
                    </div>
                    
                    <div class="p-6 rounded-xl border border-amber-100 bg-gradient-to-b from-amber-50 to-white text-center">
                        <div class="service-icon komunitas-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Komunitas Pembaca Aktif</h3>
                        <p class="text-gray-600 mb-4">Bergabung dengan komunitas pembaca terbesar di Indonesia. Diskusi buku, bedah karya, dan temu penulis secara rutin.</p>
                        <ul class="text-sm text-gray-700 text-left space-y-2">
                            <li class="flex items-center gap-2"><i class="fas fa-check text-green-500"></i> Klub membaca mingguan</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check text-green-500"></i> Bincang-bincang dengan penulis</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check text-green-500"></i> Pertukaran buku antar anggota</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Cerita Kami Section -->
            <div class="p-8 rounded-2xl glass-card floating-card">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-12 h-12 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xl">
                        <i class="fas fa-history"></i>
                    </span>
                    <h2 class="text-3xl font-bold text-green-900">Cerita Kami</h2>
                </div>
                
                <div class="grid md:grid-cols-2 gap-10 items-center">
                    <div>
                        <p class="text-gray-700 leading-relaxed text-lg mb-4">
                            <span class="font-bold text-green-700 text-xl">Ruang Aksara</span> didirikan pada tahun 2020 dengan misi revolusioner: membuat akses terhadap buku menjadi lebih mudah, terjangkau, dan berkelanjutan bagi semua orang di Indonesia.
                        </p>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            Kami memulai sebagai perpustakaan komunitas kecil yang meminjamkan buku secara gratis. Seiring waktu, kami menyadari bahwa banyak pembaca yang ingin memiliki koleksi pribadi, sementara yang lain memiliki buku yang sudah tidak dibaca lagi.
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            Dari sinilah lahir konsep hybrid: <span class="font-semibold text-green-700">perpustakaan online yang juga menjadi marketplace buku</span>. Sekarang, Anda bisa meminjam buku untuk dibaca, membeli buku baru untuk koleksi, atau menjual buku bekas Anda kepada pembaca lain - semua dalam satu platform.
                        </p>
                    </div>
                    <div>
                        <img src="https://images.unsplash.com/photo-1589829085413-56de8ae18c73?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2072&q=80" 
                             alt="Ruang baca Ruang Aksara" 
                             class="story-img shadow-lg">
                        <p class="text-sm text-gray-500 mt-3 text-center italic">Ruang baca digital kami yang bisa diakses dari mana saja</p>
                    </div>
                </div>
            </div>

            <!-- Visi & Misi Section -->
            <div class="p-8 rounded-2xl glass-card floating-card">
                <h2 class="text-3xl font-bold text-center text-green-900 mb-10">Visi & Misi Kami</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Visi -->
                    <div class="p-6 rounded-xl border border-green-100 bg-gradient-to-b from-green-50 to-white">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-12 h-12 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold text-xl">V</span>
                            <h3 class="text-2xl font-bold text-green-900">Visi</h3>
                        </div>
                        <p class="text-gray-700 leading-relaxed">Menjadi ekosistem literasi digital terintegrasi pertama di Indonesia yang menghubungkan pembaca, penulis, dan penerbit dalam satu platform yang berkelanjutan.</p>
                    </div>
                    
                    <!-- Misi -->
                    <div class="p-6 rounded-xl border border-emerald-100 bg-gradient-to-b from-emerald-50 to-white">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xl">M</span>
                            <h3 class="text-2xl font-bold text-green-900">Misi</h3>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm mt-1 flex-shrink-0"><i class="fas fa-book"></i></span>
                                <span class="text-gray-700"><span class="font-semibold">Memperluas Akses Buku:</span> Menyediakan layanan peminjaman buku digital dan fisik dengan biaya terjangkau.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-sm mt-1 flex-shrink-0"><i class="fas fa-shopping-cart"></i></span>
                                <span class="text-gray-700"><span class="font-semibold">Memfasilitasi Perdagangan Buku:</span> Menyediakan platform jual-beli buku baru dan bekas berkualitas.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-sm mt-1 flex-shrink-0"><i class="fas fa-users"></i></span>
                                <span class="text-gray-700"><span class="font-semibold">Membangun Komunitas:</span> Menciptakan ruang interaksi antar pecinta buku di seluruh Indonesia.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-sm mt-1 flex-shrink-0"><i class="fas fa-recycle"></i></span>
                                <span class="text-gray-700"><span class="font-semibold">Mendorong Sirkularitas Buku:</span> Memperpanjang siklus hidup buku melalui sistem pertukaran dan penjualan kembali.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Tim Kami dengan 6 Anggota -->
            <div class="p-8 rounded-2xl glass-card floating-card">
                <div class="p-8 rounded-2xl glass-card border border-white/50 floating-card">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xl">
                            <i class="fas fa-users"></i>
                        </span>
                        <h2 class="text-3xl font-bold text-gray-900">Tim Kami</h2>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-8 text-lg">Tim kecil dengan semangat besar yang terdiri dari 6 orang yang berdedikasi untuk membuat Ruang Aksara menjadi ekosistem literasi terbaik di Indonesia.</p>
                    
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <!-- Owner -->
                        <div class="relative p-6 rounded-xl bg-gradient-to-br from-amber-50 to-white text-center shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="owner-badge">OWNER</div>
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80" 
                                 alt="Andi Wijaya - Owner" 
                                 class="team-img rounded-full mx-auto mb-4">
                            <div class="font-bold text-gray-900 text-lg">Andi Wijaya</div>
                            <div class="text-sm text-amber-600 font-semibold mb-2">Owner & Founder</div>
                            <p class="text-sm text-gray-600">Mendirikan Ruang Aksara dengan visi membuat buku dapat diakses semua kalangan.</p>
                            <div class="mt-4 flex justify-center space-x-2">
                                <span class="text-xs px-2 py-1 bg-amber-100 text-amber-700 rounded-full">Leadership</span>
                                <span class="text-xs px-2 py-1 bg-amber-100 text-amber-700 rounded-full">Strategy</span>
                            </div>
                        </div>
                        
                        <!-- Admin 1 -->
                        <div class="relative p-6 rounded-xl bg-gradient-to-br from-emerald-50 to-white text-center shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="admin-badge">ADMIN</div>
                            <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80" 
                                 alt="Sari Dewi - Admin" 
                                 class="team-img rounded-full mx-auto mb-4">
                            <div class="font-bold text-gray-900 text-lg">Sari Dewi</div>
                            <div class="text-sm text-emerald-600 font-semibold mb-2">Admin & Community Manager</div>
                            <p class="text-sm text-gray-600">Mengelola komunitas dan sistem peminjaman buku untuk anggota.</p>
                            <div class="mt-4 flex justify-center space-x-2">
                                <span class="text-xs px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full">Community</span>
                                <span class="text-xs px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full">Support</span>
                            </div>
                        </div>
                        
                        <!-- Admin 2 -->
                        <div class="relative p-6 rounded-xl bg-gradient-to-br from-emerald-50 to-white text-center shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="admin-badge">ADMIN</div>
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80" 
                                 alt="Budi Santoso - Admin" 
                                 class="team-img rounded-full mx-auto mb-4">
                            <div class="font-bold text-gray-900 text-lg">Budi Santoso</div>
                            <div class="text-sm text-emerald-600 font-semibold mb-2">Admin & Marketplace Manager</div>
                            <p class="text-sm text-gray-600">Mengelola penjualan buku dan transaksi marketplace.</p>
                            <div class="mt-4 flex justify-center space-x-2">
                                <span class="text-xs px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full">Sales</span>
                                <span class="text-xs px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full">E-commerce</span>
                            </div>
                        </div>
                        
                        <!-- Kurator & Pustakawan -->
                        <div class="p-6 rounded-xl bg-gradient-to-br from-green-50 to-white text-center shadow-md hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80" 
                                 alt="Rina Marlina - Kurator" 
                                 class="team-img rounded-full mx-auto mb-4">
                            <div class="font-bold text-gray-900 text-lg">Rina Marlina</div>
                            <div class="text-sm text-green-600 font-semibold mb-2">Kurator & Pustakawan</div>
                            <p class="text-sm text-gray-600">Mengelola koleksi perpustakaan dan memastikan kualitas buku yang dipinjam/dijual.</p>
                            <div class="mt-4 flex justify-center space-x-2">
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">Cataloging</span>
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">Quality Control</span>
                            </div>
                        </div>
                        
                        <!-- Desainer & UX -->
                        <div class="p-6 rounded-xl bg-gradient-to-br from-blue-50 to-white text-center shadow-md hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=776&q=80" 
                                 alt="Dian Permata - Desainer" 
                                 class="team-img rounded-full mx-auto mb-4">
                            <div class="font-bold text-gray-900 text-lg">Dian Permata</div>
                            <div class="text-sm text-blue-600 font-semibold mb-2">Desainer UX/UI</div>
                            <p class="text-sm text-gray-600">Merancang pengalaman meminjam dan membeli buku yang mudah dan menyenangkan.</p>
                            <div class="mt-4 flex justify-center space-x-2">
                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full">Design</span>
                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full">UX/UI</span>
                            </div>
                        </div>
                        
                        <!-- Pengembang & IT -->
                        <div class="p-6 rounded-xl bg-gradient-to-br from-purple-50 to-white text-center shadow-md hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80" 
                                 alt="Rizky Arif - Pengembang" 
                                 class="team-img rounded-full mx-auto mb-4">
                            <div class="font-bold text-gray-900 text-lg">Rizky Arif</div>
                            <div class="text-sm text-purple-600 font-semibold mb-2">Pengembang Sistem</div>
                            <p class="text-sm text-gray-600">Mengembangkan sistem peminjaman dan e-commerce yang aman dan stabil.</p>
                            <div class="mt-4 flex justify-center space-x-2">
                                <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full">Backend</span>
                                <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full">E-commerce</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Section -->
            <div class="p-8 rounded-2xl glass-card floating-card">
                <h2 class="text-3xl font-bold text-center text-green-900 mb-10">Ruang Aksara dalam Angka</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div class="p-6">
                        <div class="text-4xl font-bold text-blue-600 mb-2">15,000+</div>
                        <div class="text-gray-600">Buku Dipinjam</div>
                        <p class="text-xs text-gray-500 mt-1">Sejak 2020</p>
                    </div>
                    <div class="p-6">
                        <div class="text-4xl font-bold text-green-600 mb-2">8,500+</div>
                        <div class="text-gray-600">Buku Terjual</div>
                        <p class="text-xs text-gray-500 mt-1">Melalui marketplace</p>
                    </div>
                    <div class="p-6">
                        <div class="text-4xl font-bold text-amber-600 mb-2">10,000+</div>
                        <div class="text-gray-600">Anggota Aktif</div>
                        <p class="text-xs text-gray-500 mt-1">Di seluruh Indonesia</p>
                    </div>
                    <div class="p-6">
                        <div class="text-4xl font-bold text-purple-600 mb-2">6</div>
                        <div class="text-gray-600">Anggota Tim</div>
                        <p class="text-xs text-gray-500 mt-1">Dedikasi penuh</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-white py-12 mt-12" style="background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%) !important;">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4">
                        <i class="fas fa-book mr-2"></i> <span style="color: #FFD600;">Ruang Aksara</span>
                    </h3>
                    <p class="text-gray-300 leading-relaxed">
                        Toko buku online terpercaya dengan koleksi buku terlengkap. 
                        Mari bersama-sama membangun budaya membaca yang lebih baik.
                    </p>
                </div>
                <div>
                    <h4 class="text-xl font-semibold mb-4">Kontak Kami</h4>
                    <p class="text-gray-300 mb-2">
                        <i class="fas fa-map-marker-alt mr-3"></i> Jl. Membaca No. 123, Yogyakarta
                    </p>
                    <p class="text-gray-300 mb-2">
                        <i class="fas fa-phone mr-3"></i> <a href="tel:+62274123456" class="text-gray-300 hover:text-white">(0274) 123-456</a>
                    </p>
                    <p class="text-gray-300 mb-2">
                        <i class="fas fa-envelope mr-3"></i> <a href="mailto:ruangg.aksara@gmail.com" class="text-gray-300 hover:text-white">ruangg.aksara@gmail.com</a>
                    </p>
                    <p class="text-gray-300">
                        <i class="fab fa-whatsapp mr-3"></i> <a href="https://wa.me/628123456789" target="_blank" class="text-gray-300 hover:text-white">+62 812-3456-789</a>
                    </p>
                    <p class="text-gray-300">
                        <i class="fab fa-instagram mr-3"></i> <a href="https://www.instagram.com/ruanggaksara?igsh=MXZ2M3JwdHZiYWZzdA==" target="_blank" class="text-gray-300 hover:text-white">@ruanggaksara</a>
                    </p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Ruang Aksara. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>