# Setup Google OAuth Login

## Fitur yang ditambahkan:
✅ Login dengan akun Google  
✅ Auto-register user baru dari Google  
✅ Link existing user dengan Google account  
✅ Avatar dari Google profile  

---

## 📋 Langkah Setup

### 1. Install Laravel Socialite Package

Jalankan command berikut di terminal:

```bash
composer require laravel/socialite
```

### 2. Setup Google Cloud Console

1. **Buka Google Cloud Console**: https://console.cloud.google.com/

2. **Buat Project Baru (atau pilih existing)**
   - Klik "Select a project" → "New Project"
   - Nama: `Ruang Aksara` (atau nama lain)
   - Klik "Create"

3. **Enable Google+ API**
   - Di sidebar, pilih "APIs & Services" → "Library"
   - Cari "Google+ API"
   - Klik "Enable"

4. **Buat OAuth 2.0 Credentials**
   - Di sidebar, pilih "APIs & Services" → "Credentials"
   - Klik "Create Credentials" → "OAuth client ID"
   - Pilih "Application type": **Web application**
   - Nama: `Ruang Aksara Web Client`
   
   **Authorized JavaScript origins:**
   ```
   http://localhost:8000
   http://127.0.0.1:8000
   ```
   
   **Authorized redirect URIs:**
   ```
   http://localhost:8000/auth/google/callback
   http://127.0.0.1:8000/auth/google/callback
   ```
   
   - Klik "Create"

5. **Copy Credentials**
   - Setelah dibuat, akan muncul popup dengan:
     - **Client ID** (contoh: `123456789-abc.apps.googleusercontent.com`)
     - **Client Secret** (contoh: `GOCSPX-abc123xyz`)
   - Copy kedua nilai ini

### 3. Update .env File

Buka file `.env` dan update nilai berikut:

```env
GOOGLE_CLIENT_ID=paste-client-id-disini
GOOGLE_CLIENT_SECRET=paste-client-secret-disini
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**Penting**: Ganti `paste-client-id-disini` dan `paste-client-secret-disini` dengan credential yang tadi di-copy!

### 4. Jalankan Migration

```bash
php artisan migrate
```

Migration akan menambahkan kolom baru ke tabel users:
- `google_id` - ID unik dari Google
- `google_token` - Access token dari Google
- `google_refresh_token` - Refresh token (optional)
- `avatar` - URL avatar dari Google profile
- `password` - Sekarang nullable (karena Google OAuth users tidak butuh password)

### 5. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

---

## 🎯 Cara Menggunakan

1. **Buka halaman login**: http://localhost:8000/login

2. **Klik tombol "Masuk dengan Google"**

3. **Pilih akun Google** yang ingin digunakan

4. **Izinkan akses** ke aplikasi

5. **Otomatis login** dan redirect ke dashboard!

---

## 🔄 Alur Kerja

### User Baru (Belum ada akun)
1. Klik "Login with Google"
2. Google OAuth redirect
3. User approve permissions
4. Sistem **create user baru** dengan:
   - Email dari Google
   - Nama dari Google
   - Avatar dari Google
   - Role: user
   - Points: 0
   - Password: null
5. Auto login dan redirect ke `/home`

### User Existing (Sudah ada akun dengan email yang sama)
1. Klik "Login with Google"
2. Google OAuth redirect
3. Sistem **update existing user** dengan:
   - google_id
   - google_token
   - avatar (jika belum ada)
4. Auto login dan redirect sesuai role

---

## 🛡️ Keamanan

- ✅ OAuth tokens disimpan dengan aman
- ✅ CSRF protection aktif
- ✅ Validate email dari Google
- ✅ Cegah duplicate registration
- ✅ Session regeneration setelah login
- ✅ Error handling untuk OAuth failures

---

## 📝 Testing

### Test Login dengan Google:
1. Pastikan `.env` sudah diisi dengan benar
2. Buka http://localhost:8000/login
3. Klik tombol Google
4. Login dengan akun Google test

### Test Auto-create User:
1. Gunakan Google account yang belum pernah register
2. Setelah login, cek database `users` table
3. User baru akan muncul dengan `google_id` terisi

### Test Link Existing Account:
1. Register manual dengan email: test@gmail.com
2. Logout
3. Login dengan Google menggunakan test@gmail.com
4. Cek database: user akan terupdate dengan `google_id`

---

## 🚀 Production Setup

Untuk production, update:

### 1. Google Cloud Console
- Tambahkan production domain ke:
  - Authorized JavaScript origins: `https://ruangaksara.com`
  - Authorized redirect URIs: `https://ruangaksara.com/auth/google/callback`

### 2. .env Production
```env
APP_URL=https://ruangaksara.com
GOOGLE_REDIRECT_URI=https://ruangaksara.com/auth/google/callback
```

---

## 🐛 Troubleshooting

### Error: "Client ID not found"
- Pastikan GOOGLE_CLIENT_ID di .env sudah benar
- Jalankan `php artisan config:clear`

### Error: "Redirect URI mismatch"
- Pastikan redirect URI di Google Console sama persis dengan di .env
- Format: `http://localhost:8000/auth/google/callback` (tanpa trailing slash)

### Error: "Invalid credentials"
- Pastikan copy Client ID dan Secret dengan benar (tidak ada spasi)
- Regenerate credentials di Google Console jika perlu

### User tidak ter-create
- Cek log: `storage/logs/laravel.log`
- Pastikan migration sudah jalan: `php artisan migrate:status`

---

## 📚 Resources

- Laravel Socialite: https://laravel.com/docs/socialite
- Google OAuth Setup: https://console.cloud.google.com/
- Google OAuth Scopes: https://developers.google.com/identity/protocols/oauth2/scopes

---

Selamat! Login dengan Google sudah aktif! 🎉
