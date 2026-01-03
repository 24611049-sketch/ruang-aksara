<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profil - Ruang Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <style>
        body {
            background:
                linear-gradient(rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.15)),
                url('/images/background.jpg') center/cover fixed no-repeat !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
            min-height: 100vh !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .content-card {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(8px);
            border-radius: 1.25rem;
            box-shadow: 0 14px 36px rgba(31, 124, 69, 0.12);
        }

        .form-input {
            padding: 0.8rem 1rem;
            border: 1px solid #d7dde5;
            border-radius: 0.65rem;
            font-size: 1rem;
            width: 100%;
            background: #f8fafc;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #2d5a3d;
            box-shadow: 0 8px 24px rgba(45, 90, 61, 0.18);
            background: #ffffff;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(120deg, #2d5a3d 0%, #1e3e2a 100%);
            color: #ffffff;
            border: none;
            border-radius: 0.85rem;
            padding: 0.95rem 1.8rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 14px 34px rgba(45, 90, 61, 0.36);
        }

        .btn-primary:hover {
            transform: translateY(-1.5px);
            box-shadow: 0 18px 40px rgba(45, 90, 61, 0.48);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid #cbd5e1;
            color: #0f172a;
            border-radius: 0.85rem;
            padding: 0.95rem 1.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease, border 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
        }

        .btn-outline:hover {
            background: #0f172a;
            color: #f8fafc;
            border-color: #0f172a;
        }

        .profile-avatar {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2fd4a5 0%, #0f7a54 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 2.4rem;
            margin: 0 auto 1.25rem;
            border: 5px solid #f8fafc;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.16);
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.1rem;
            background: #f8fafc;
            border-radius: 0.85rem;
            margin-bottom: 0.75rem;
            color: #0f172a;
            border: 1px solid #e2e8f0;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
        }

        .stat-label {
            font-size: 0.95rem;
            color: #0f172a;
        }

        .stat-value {
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.01em;
        }

        .logout-card {
            border: 1px solid rgba(254, 205, 211, 0.8);
            background: rgba(254, 242, 239, 0.9);
            border-radius: 1rem;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <button id="hamburgerBtn" class="hamburger-btn" type="button" title="Toggle Sidebar" aria-controls="sidebar" aria-expanded="false" onclick="toggleSidebar()">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <aside class="sidebar hidden" id="sidebar">
        @include('partials.user-sidebar-content')
    </aside>

    <div class="main-wrapper" id="mainWrapper">
        <div class="content-wrapper">
            <div class="mx-auto max-w-6xl space-y-6">
                <header class="content-card px-8 py-10">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center">
                        <i class="fas fa-user-cog text-green-500 mr-3"></i>
                        Pengaturan Profil
                    </h1>
                    <p class="text-gray-600">Kelola informasi akun, alamat, dan keamanaan profil Anda.</p>
                </header>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <section class="content-card p-6">
                        <div class="text-center mb-6">
                            <div class="profile-avatar">
                                @if(Auth::user()->foto_profil && file_exists(public_path('storage/' . Auth::user()->foto_profil)))
                                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover rounded-full">
                                @else
                                    <i class="fas fa-user"></i>
                                @endif
                            </div>
                            <h2 class="text-lg font-semibold text-gray-800">{{ Auth::user()->name }}</h2>
                            <p class="text-gray-600 text-sm">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="space-y-3">
                            <div class="stat-item">
                                <span class="stat-label">Member sejak</span>
                                <span class="stat-value">{{ Auth::user()->created_at->format('M Y') }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Total Pesanan</span>
                                <span class="stat-value">{{ Auth::user()->orders->count() }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Wishlist</span>
                                <span class="stat-value">{{ Auth::user()->wishlists->count() ?? 0 }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Poin</span>
                                <span class="stat-value">{{ Auth::user()->points ?? 0 }}</span>
                            </div>
                        </div>

                        <button type="button" id="ubah-foto-btn" class="mt-5 w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition flex items-center justify-center">
                            <i class="fas fa-camera mr-2"></i> Ubah Foto Profil
                        </button>

                        <input type="file" id="foto_profil_input" accept="image/*" class="hidden">
                    </section>

                    <section class="content-card p-6 lg:col-span-2">
                        @if (session('success'))
                            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                                <p class="font-semibold mb-2 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Terjadi kesalahan saat menyimpan:
                                </p>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <h2 class="text-xl font-semibold text-gray-800 mb-5 flex items-center">
                            <i class="fas fa-user-edit text-green-600 mr-2"></i>
                            Informasi Profil
                        </h2>

                        <form id="profile-form" action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-user mr-1"></i> Nama Lengkap
                                    </label>
                                    <input type="text" name="name" class="form-input" value="{{ old('name', Auth::user()->name) }}" required>
                                    <p class="text-sm text-gray-500 mt-1">Nama lengkap Anda yang akan ditampilkan.</p>
                                </div>
                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-envelope mr-1"></i> Alamat Email
                                    </label>
                                    <input type="email" class="form-input bg-gray-100 cursor-not-allowed" value="{{ Auth::user()->email }}" disabled readonly>
                                    <p class="text-sm text-red-500 mt-1">Email tidak dapat diubah.</p>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt mr-1"></i> Alamat
                                </label>
                                <textarea name="alamat" class="form-input" rows="3" placeholder="Masukkan alamat lengkap">{{ old('alamat', Auth::user()->alamat ?? '') }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-phone mr-1"></i> Nomor Telepon
                                    </label>
                                    <input type="tel" name="telepon" class="form-input" value="{{ old('telepon', Auth::user()->telepon ?? '') }}" placeholder="Contoh: 081234567890">
                                </div>
                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-calendar mr-1"></i> Tanggal Lahir
                                    </label>
                                    <input type="date" name="tanggal_lahir" class="form-input" value="{{ old('tanggal_lahir', Auth::user()->tanggal_lahir ?? '') }}">
                                </div>
                            </div>

                            <div>
                                <label class="form-label">
                                    <i class="fas fa-lock mr-1"></i> Password Saat Ini <span class="text-red-600">*</span>
                                </label>
                                <input type="password" name="current_password" class="form-input" required>
                                <p class="text-sm text-gray-500 mt-1">Diperlukan untuk menyimpan perubahan profil.</p>
                            </div>

                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-key text-green-600 mr-2"></i>
                                    Ubah Password
                                </h3>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="form-label">Password Baru</label>
                                        <input type="password" name="new_password" class="form-input">
                                        <p class="text-sm text-gray-500 mt-1">Minimal 8 karakter.</p>
                                    </div>
                                    <div>
                                        <label class="form-label">Konfirmasi Password Baru</label>
                                        <input type="password" name="new_password_confirmation" class="form-input">
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <button type="submit" form="profile-form" class="btn-primary flex items-center">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('home') }}" class="btn-outline">
                                <i class="fas fa-times mr-2"></i> Batal
                            </a>
                        </div>

                        <div class="logout-card mt-6 flex flex-col gap-3 p-5 md:flex-row md:items-center md:justify-between">
                            <p class="text-sm font-semibold text-red-700 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                Keluar dari akun ini
                            </p>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-outline" style="color: #b91c1c; border-color: #fecdd3;">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <div class="nav-buttons">
        <button type="button" onclick="window.history.back()" title="Kembali">
            <i class="fas fa-arrow-left"></i>
        </button>
        <button type="button" onclick="window.history.forward()" title="Maju">
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>

    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>
        const handleFotoUpload = (input) => {
            if (!input.files || !input.files[0]) {
                return;
            }

            const file = input.files[0];

            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB.');
                return;
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Tipe file tidak didukung! Gunakan JPEG, PNG, JPG, atau GIF.');
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                const preview = document.querySelector('.profile-avatar');
                if (!preview) {
                    return;
                }

                const previewImg = document.createElement('img');
                previewImg.src = event.target.result;
                previewImg.className = 'w-full h-full object-cover rounded-full';
                preview.innerHTML = '';
                preview.appendChild(previewImg);
            };
            reader.readAsDataURL(file);

            const form = new FormData();
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            form.append('foto_profil', file);
            form.append('_token', csrf);

            fetch('{{ route("profile.foto.upload") }}', {
                method: 'POST',
                body: form,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert('Foto profil berhasil diperbarui!');
                    } else {
                        alert(data.message || 'Terjadi kesalahan saat mengupload foto.');
                    }
                })
                .catch(() => {
                    alert('Terjadi kesalahan saat mengupload foto!');
                });
        };

        document.addEventListener('DOMContentLoaded', () => {
            const fotoBtn = document.getElementById('ubah-foto-btn');
            const fotoInput = document.getElementById('foto_profil_input');
            const profileForm = document.getElementById('profile-form');

            if (fotoBtn && fotoInput) {
                fotoBtn.addEventListener('click', () => fotoInput.click());
                fotoInput.addEventListener('change', () => handleFotoUpload(fotoInput));
            }

            if (profileForm) {
                profileForm.addEventListener('submit', (event) => {
                    const newPassword = profileForm.querySelector('input[name="new_password"]').value;
                    const confirmPassword = profileForm.querySelector('input[name="new_password_confirmation"]').value;

                    if (newPassword && newPassword !== confirmPassword) {
                        event.preventDefault();
                        alert('Konfirmasi password tidak sesuai!');
                        return;
                    }

                    const name = profileForm.querySelector('input[name="name"]').value;
                    const alamat = profileForm.querySelector('textarea[name="alamat"]').value;
                    const telepon = profileForm.querySelector('input[name="telepon"]').value;
                    const tanggalLahir = profileForm.querySelector('input[name="tanggal_lahir"]').value;

                    const originalName = "{{ Auth::user()->name }}";
                    const originalAlamat = "{{ Auth::user()->alamat ?? '' }}";
                    const originalTelepon = "{{ Auth::user()->telepon ?? '' }}";
                    const originalTanggalLahir = "{{ Auth::user()->tanggal_lahir ?? '' }}";

                    const isNameChanged = name !== originalName;
                    const isAlamatChanged = alamat !== originalAlamat;
                    const isTeleponChanged = telepon !== originalTelepon;
                    const isTanggalLahirChanged = tanggalLahir !== originalTanggalLahir;
                    const isPasswordChanged = newPassword !== '';

                    if (!isNameChanged && !isAlamatChanged && !isTeleponChanged && !isTanggalLahirChanged && !isPasswordChanged) {
                        event.preventDefault();
                        alert('Tidak ada perubahan yang dilakukan!');
                    }
                });
            }
        });
    </script>
</body>
</html>