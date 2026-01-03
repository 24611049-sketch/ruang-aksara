<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Ruang Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(29,78,63,0.6), rgba(16,59,46,0.6)), url('/images/background.jpg') center/cover fixed no-repeat;
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
        }
        
        .register-box {
            background: linear-gradient(135deg, rgba(45, 90, 61, 0.85), rgba(30, 62, 42, 0.85));
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 3rem;
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 1200px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .logo i {
            font-size: 3.5rem;
            color: #fde047;
            display: block;
            margin-bottom: 1rem;
        }
        
        h2 {
            text-align: center;
            margin-bottom: 1rem;
            margin-top: 0;
            color: #ffffff;
            font-size: 2rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .divider {
            height: 4px;
            width: 80px;
            background: #fde047;
            border-radius: 2px;
            margin: 0 auto 2rem;
        }
        
        .input-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.95rem;
            text-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 1rem;
            pointer-events: none;
            z-index: 10;
        }
        
        input {
            width: 100%;
            padding: 1.125rem 1.25rem 1.125rem 3.5rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 0.75rem;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.95);
            color: #1f2937;
            transition: all 0.2s;
            box-sizing: border-box;
        }
        
        input::placeholder {
            color: #9ca3af;
        }
        
        input:focus {
            border-color: #10b981;
            outline: none;
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }
        
        .error-message {
            background: rgba(239, 68, 68, 0.9);
            color: white;
            padding: 0.875rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .error-message ul {
            list-style: disc;
            padding-left: 1.25rem;
            margin: 0;
        }
        
        button {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(to right, #10b981, #059669);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 1.125rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }
        
        button:hover {
            background: linear-gradient(to right, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.6);
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: white;
            font-size: 0.95rem;
        }
        
        .login-link a {
            color: #fde047;
            text-decoration: underline;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .login-link a:hover {
            color: #fef08a;
            text-shadow: 0 0 8px rgba(253, 224, 71, 0.5);
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <div class="logo">
                <i class="fas fa-book-open"></i>
            </div>
            <h2>Daftar Akun</h2>
            <div class="divider"></div>
            
            @if($errors->any())
                <div class="error-message">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-500 bg-opacity-90 text-white p-3 rounded-lg mb-4 border border-blue-300 text-sm">
                    {{ session('info') }}
                </div>
            @endif

            @if(!session('google_data'))
                <!-- Google Sign-Up Button -->
                <a href="{{ route('auth.google') }}" style="display: flex; align-items: center; justify-content: center; width: 100%; padding: 0.875rem; background: rgba(255, 255, 255, 0.95); border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 0.75rem; font-size: 1rem; font-weight: 700; color: #1f2937; text-decoration: none; transition: all 0.2s; margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);" onmouseover="this.style.background='rgba(255, 255, 255, 1)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(255, 255, 255, 0.3)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.95)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(255, 255, 255, 0.2)';">
                    <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Daftar dengan Google
                </a>

                <!-- Divider -->
                <div style="position: relative; margin: 1.5rem 0; text-align: center;">
                    <div style="position: absolute; top: 50%; left: 0; right: 0; height: 2px; background: rgba(255, 255, 255, 0.3);"></div>
                    <span style="position: relative; background: linear-gradient(135deg, rgba(45, 90, 61, 0.85), rgba(30, 62, 42, 0.85)); padding: 0 1rem; color: rgba(255, 255, 255, 0.8); font-size: 0.9rem; font-weight: 600;">Atau isi form manual</span>
                </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Store Google data if available -->
                @php
                    $googleData = session('google_data');
                @endphp

                @if($googleData)
                    <input type="hidden" name="google_id" value="{{ $googleData['google_id'] }}">
                    <input type="hidden" name="google_token" value="{{ $googleData['google_token'] }}">
                    <input type="hidden" name="google_refresh_token" value="{{ $googleData['google_refresh_token'] }}">
                    <input type="hidden" name="avatar" value="{{ $googleData['avatar'] }}">
                    <input type="hidden" name="from_google" value="1">
                @endif

                @if(!$googleData)
                    <div class="input-group">
                        <label>Nama Lengkap</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap">
                        </div>
                    </div>
                @else
                    <input type="hidden" name="name" value="{{ $googleData['name'] }}">
                @endif
                
                <div class="input-group">
                    <label>Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" value="{{ old('email', $googleData['email'] ?? '') }}" required placeholder="nama@email.com" {{ $googleData ? 'readonly' : '' }}>
                    </div>
                    @if($googleData)
                        <p class="text-xs text-gray-200 mt-1"><i class="fas fa-check-circle mr-1"></i>Email dari akun Google Anda</p>
                    @endif
                </div>

                @if(!$googleData)
                    <div class="input-group">
                        <label>Alamat</label>
                        <div class="input-wrapper">
                            <i class="fas fa-map-marker-alt input-icon"></i>
                            <input type="text" name="alamat" value="{{ old('alamat') }}" required placeholder="Masukkan alamat lengkap">
                        </div>
                    </div>
                @else
                    <input type="hidden" name="alamat" value="-">
                @endif
                
                <div class="input-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" required placeholder="Minimal 8 karakter">
                    </div>
                    @if($googleData)
                        <p class="text-xs text-gray-200 mt-1">Buat password untuk login manual</p>
                    @endif
                </div>
                
                <div class="input-group">
                    <label>Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi password">
                    </div>
                </div>
                
                <button type="submit">
                    <i class="fas fa-user-plus mr-2"></i>{{ $googleData ? 'Selesaikan Pendaftaran' : 'Daftar Sekarang' }}
                </button>
            </form>
            
            <div class="login-link">
                <p>Sudah punya akun? <a href="/login">Masuk di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>