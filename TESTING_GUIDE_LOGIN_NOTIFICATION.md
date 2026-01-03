# 🧪 Testing Guide - Login Notification Feature

## Cara Testing

### Manual Login Testing
1. **Buka halaman login**
   - URL: `http://localhost:8000/login` atau `http://localhost:8000`
   
2. **Login sebagai Admin**
   - Email: admin@example.com (atau email admin di database Anda)
   - Password: sesuai password di database
   - Click "Masuk"

3. **Expected Result**
   - Redirect ke `/admin/dashboard`
   - Notifikasi toast muncul di **pojok kanan atas** dengan:
     - ✅ Pesan: "Selamat Datang!"
     - ✅ Sub-text: "Kamu login sebagai Admin"
     - ✅ Avatar dengan initial nama admin
     - ✅ Icon shield biru (admin)
     - ✅ Progress bar berjalan 5 detik
     - ✅ Auto-close setelah 5 detik

4. **Test Owner Login**
   - Logout terlebih dahulu
   - Login dengan akun owner
   - Harusnya muncul notifikasi dengan:
     - ✅ Pesan: "Kamu login sebagai Owner"
     - ✅ Icon crown emas (owner)
     - ✅ Sama flow seperti admin

5. **Test User Login**
   - Logout
   - Login dengan akun user biasa
   - **Expected**: Tidak ada notifikasi (langsung ke home)

### Google OAuth Testing
1. **Click "Login with Google"**
2. **Follow Google OAuth flow**
3. **Konfirmasi login**
4. **Expected Result**
   - Notifikasi muncul seperti manual login
   - Pesan sesuai role (owner/admin)

### Manual Close Testing
1. **Login sebagai admin**
2. **Notifikasi muncul**
3. **Click tombol X** di notifikasi
4. **Expected**: Notifikasi hilang dengan smooth fade-out

### Auto-Close Testing
1. **Login sebagai admin**
2. **Notifikasi muncul**
3. **Jangan di-click, tunggu 5 detik**
4. **Expected**: Notifikasi auto-disappear dengan smooth animation

## Browser Developer Tools Testing

### Console Check
1. Open DevTools (F12)
2. Go to Console tab
3. Login sebagai admin
4. Check apakah ada error message

### Network Check
1. Open DevTools
2. Go to Network tab
3. Login
4. Check POST /login request
5. Expected response headers ada `Set-Cookie` untuk session

### Session Storage Check
1. Open DevTools
2. Go to Application tab
3. Check Cookies
4. Should see LARAVEL_SESSION cookie
5. Session data berisi `login_notification`

### Element Inspection
1. Open DevTools
2. Go to Elements tab
3. Search element dengan id `loginNotification`
4. Expected: Element ada dengan struktur:
   ```html
   <div id="loginNotification" class="fixed top-8 right-8 z-50 animate-fade-in">
       <!-- Toast content -->
   </div>
   ```

## Responsive Testing

### Desktop
- Notifikasi padding & positioning OK
- Font readable
- Colors look good

### Tablet (iPad)
- Notifikasi still visible
- Tidak overlap dengan navbar
- Touch-friendly close button

### Mobile (iPhone/Android)
- Notifikasi fully visible
- Doesn't cover navbar
- Close button accessible
- Progress bar visible

## Edge Cases Testing

### 1. Fast Logout-Login
- Login as admin
- Logout immediately
- Login again quickly
- Expected: 2 notifikasi muncul (jika sudah clear session dari logout)

### 2. Tab-Open Logout-Login
- Tab 1: Admin dashboard
- Tab 2: Open login page
- Tab 2: Login admin lagi
- Expected: Notifikasi hanya muncul di tab 2 (session-based)

### 3. Browser Back Button
- Login as admin (notifikasi muncul)
- Click back button
- Expected: Notifikasi gone (session cleared)

### 4. Page Refresh
- Login (notifikasi muncul)
- Press F5 refresh
- Expected: Notifikasi gone (one-time display)

### 5. Multiple Admin Accounts
- Login admin1
- Logout
- Login admin2
- Expected: Notifikasi menampilkan nama admin2

## Debugging Checklist

- [ ] Component file exists: `resources/views/components/login-notification.blade.php`
- [ ] Component included in layout: `resources/views/layouts/app.blade.php`
- [ ] AuthenticatedSessionController updated dengan notifikasi data
- [ ] Routes updated (POST /login dan /google/confirm-login)
- [ ] No JavaScript errors di console
- [ ] Session `login_notification` berisi correct data
- [ ] HTML element `loginNotification` muncul di DOM
- [ ] CSS animation bekerja smooth
- [ ] Notifikasi auto-close setelah 5 detik
- [ ] Close button (X) berfungsi
- [ ] Responsive layout OK di semua breakpoints

## Test Accounts

Gunakan akun berikut untuk testing:

### Admin
- Email: `admin@ruangaksara.com` atau cek di database
- Role: `admin`
- Expected notification: "Kamu login sebagai Admin" + shield icon

### Owner
- Email: `owner@ruangaksara.com` atau cek di database
- Role: `owner`
- Expected notification: "Kamu login sebagai Owner" + crown icon

### User
- Email: any user account
- Role: `user`
- Expected: No notification

---

## Reported Bugs & Fixes

**None yet** - Feature baru! 🎉

---

**Last Updated**: 2025-12-12
**Status**: Ready for Testing ✅
