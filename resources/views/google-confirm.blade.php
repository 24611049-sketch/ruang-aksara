<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Konfirmasi Akun - Ruang Aksara</title>
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
    <div class="w-full max-w-lg mx-auto px-4">
        <!-- Info Message -->
        <div class="bg-green-600 text-white px-6 py-4 rounded-t-lg flex items-center shadow-lg">
            <i class="fas fa-info-circle text-2xl mr-3"></i>
            <p class="font-semibold text-lg">Silakan konfirmasi untuk masuk ke akun Anda</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-b-lg shadow-2xl overflow-hidden">
            <div class="p-8">
                <!-- User Info -->
                <div class="flex items-start mb-8">
                    <div class="flex-shrink-0">
                        @if(session('pending_user_avatar'))
                            <img src="{{ session('pending_user_avatar') }}" alt="Avatar" class="w-16 h-16 rounded-full border-4 border-blue-100">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-2xl font-bold border-4 border-blue-100">
                                {{ strtoupper(substr(session('pending_user_name', 'U'), 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="ml-5 flex-grow">
                        <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ session('pending_user_name') }}</h2>
                        <p class="text-gray-600 mb-3">{{ session('pending_user_email') }}</p>
                        
                        <div class="inline-flex items-center">
                            <span class="text-sm font-semibold text-gray-700 mr-2">Role:</span>
                            @if(session('pending_user_role') === 'admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-orange-100 text-orange-700 border border-orange-300">
                                    <i class="fas fa-user-shield mr-1"></i> Admin
                                </span>
                            @elseif(session('pending_user_role') === 'owner')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700 border border-red-300">
                                    <i class="fas fa-crown mr-1"></i> Owner
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700 border border-green-300">
                                    <i class="fas fa-user mr-1"></i> User
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    @if(session('google_login_pending'))
                        <!-- Login Confirmation -->
                        <form action="{{ route('google.confirm.login') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-6 py-4 rounded-lg bg-green-600 hover:bg-green-700 text-white font-bold text-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-arrow-right mr-3"></i>Lanjutkan ke Dashboard
                            </button>
                        </form>
                    @endif

                    @if(session('google_register_pending'))
                        <!-- Registration Confirmation: collect password + address -->
                        <form action="{{ route('google.confirm.register') }}" method="POST">
                            @csrf

                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-800 mb-1">Buat Password</label>
                                    <input type="password" name="password" required minlength="8" class="block w-full rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 px-3 py-2 bg-white text-gray-900">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-800 mb-1">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" required minlength="8" class="block w-full rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 px-3 py-2 bg-white text-gray-900">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-800 mb-1">Alamat</label>
                                    <textarea name="alamat" rows="2" class="block w-full rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 px-3 py-2 bg-white text-gray-900">{{ old('alamat') }}</textarea>
                                </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-1">Provinsi</label>
                                    <select name="province" required class="block w-full rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 px-3 py-2 bg-white text-gray-900">
                                        <option value="">Pilih provinsi</option>
                                        @foreach(config('indonesia.provinces') as $prov)
                                            <option value="{{ $prov }}" {{ old('province') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-1">Kota/Kabupaten</label>
                                    <input name="city" class="block w-full rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 px-3 py-2 bg-white text-gray-900">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-1">Kecamatan</label>
                                    <input name="district" class="block w-full rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 px-3 py-2 bg-white text-gray-900">
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="w-full flex items-center justify-center px-6 py-4 rounded-lg bg-green-600 hover:bg-green-700 text-white font-bold text-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <i class="fas fa-check mr-3"></i>Konfirmasi Pendaftaran
                                </button>
                            </div>
                        </form>
                    @endif

                    <button type="button" onclick="clearGoogleSession()" class="w-full flex items-center justify-center px-6 py-4 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-semibold text-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-times mr-3"></i>
                        @if(session('google_register_pending'))
                            Daftar dengan Akun Lain
                        @else
                            Gunakan Akun Lain
                        @endif
                    </button>
                </div>
            </div>

            <!-- Footer Info -->
                <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
                <div class="flex items-start mb-3">
                    <i class="fas fa-shield-alt text-green-600 mr-3 mt-1"></i>
                    <p class="text-sm text-gray-700">
                        <strong>Keamanan:</strong> Akun Anda dilindungi dengan enkripsi tingkat enterprise.
                    </p>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-green-600 mr-3 mt-1"></i>
                    <p class="text-sm text-gray-700">
                        Setelah konfirmasi, Anda akan diarahkan ke dashboard sesuai role akun Anda.
                    </p>
                </div>
            </div>
        </div>

        <!-- Back Link -->
        <div class="text-center mt-6">
            <a href="{{ route('welcome') }}" class="inline-flex items-center text-white hover:text-green-200 font-semibold transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke halaman utama
            </a>
        </div>
    </div>

    <script>
        // Clear Google session and redirect to Google account picker
        function clearGoogleSession() {
            fetch('{{ route("google.clear.session") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(() => {
                // Redirect to Google OAuth to select another account
                window.location.href = '{{ route("auth.google") }}';
            });
        }
    </script>
</body>
</html>
