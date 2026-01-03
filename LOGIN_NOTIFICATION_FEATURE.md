# 🔔 Login Notification Feature

## Deskripsi
Fitur notifikasi login yang menampilkan pesan selamat datang ketika admin atau owner berhasil login ke sistem. Notifikasi akan muncul di pojok kanan atas halaman dashboard dengan durasi 5 detik.

## Fitur
- ✅ Menampilkan pesan "Kamu login sebagai Owner/Admin"
- ✅ Menampilkan nama user yang login
- ✅ Menampilkan foto profil user (jika ada, fallback ke initial)
- ✅ Auto-dismiss setelah 5 detik
- ✅ Smooth animation saat muncul dan hilang
- ✅ Progress bar yang menunjukkan sisa waktu
- ✅ Manual dismiss dengan tombol X

## Implementasi

### 1. Update Controller Login
File: `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

Di method `store()`, ditambahkan data notifikasi:
```php
if ($user->role === 'owner') {
    return redirect()->intended(route('admin.dashboard', absolute: false))
        ->with('login_notification', [
            'message' => 'Kamu login sebagai Owner',
            'role' => $user->role,
            'name' => $user->name
        ]);
}
```

### 2. Routes Update
File: `routes/web.php`

Login routes sudah di-update untuk include notifikasi:
- POST `/login` - Login manual
- POST `/google/confirm-login` - Login via Google

### 3. Component
File: `resources/views/components/login-notification.blade.php`

Component standalone yang display toast notification dengan:
- Gradient header
- User info
- Progress bar
- Close button
- Auto-dismiss JavaScript

### 4. Layout Integration
File: `resources/views/layouts/app.blade.php`

Ditambahkan line:
```php
@include('components.login-notification')
```

## Styling
Menggunakan Tailwind CSS dengan custom animation:
- **fadeIn**: Animasi muncul smooth (0.4s)
- **shrink**: Progress bar yang mengecil (5s)

## Responsive
- Bekerja optimal di semua ukuran layar
- Adaptive positioning dengan `fixed top-8 right-8`
- Max-width 28rem untuk readability

## Customization
Jika ingin mengubah:

### Durasi notifikasi
Edit line di `components/login-notification.blade.php`:
```javascript
setTimeout(() => {
    closeLoginNotification();
}, 5000); // Ubah 5000 ke nilai lain (milliseconds)
```

### Styling
Ubah di section `<style>` di component atau gunakan Tailwind classes.

### Pesan
Edit di `AuthenticatedSessionController.php` atau `routes/web.php`:
```php
'message' => 'Kamu login sebagai Owner' // Ubah text di sini
```

## Testing
1. Login dengan akun admin/owner
2. Seharusnya muncul notifikasi di pojok kanan atas
3. Klik tombol X untuk dismiss atau tunggu 5 detik

## Catatan
- Notifikasi hanya muncul untuk role `admin` dan `owner`
- User biasa tidak akan melihat notifikasi ini
- Session data akan otomatis dihapus setelah di-display
