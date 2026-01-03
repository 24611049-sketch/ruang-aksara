# Setup: Tambahkan Buku "Dari Balik Penjara dan Pengasingan"

Buku baru "Dari Balik Penjara dan Pengasingan" oleh Badruddin telah ditambahkan ke sistem.

## Langkah Setup:

### 1. Simpan Cover Image
Simpan file image yang tersedia (`dari-balik-penjara.jpg`) ke lokasi:
```
storage/app/public/book-covers/dari-balik-penjara.jpg
```

### 2. Setup Storage Link (jika belum ada)
Jalankan perintah untuk membuat symbolic link agar file tersimpan bisa diakses publik:

```bash
php artisan storage:link
```

### 3. Seed Database
Jalankan seeder untuk menambahkan data buku ke database:

**Opsi A: Seed hanya buku baru**
```bash
php artisan db:seed --class=AddDariBalikPenjaraSeeder
```

**Opsi B: Seed semua (termasuk user, buku default, dan buku baru)**
```bash
php artisan db:seed
```

## File yang ditambahkan:
- `database/seeders/AddDariBalikPenjaraSeeder.php` — Seeder untuk buku baru
- `docs/ADD_BOOK_IMAGE.md` — Dokumentasi detail

## Detail Buku:
- **Judul**: Dari Balik Penjara dan Pengasingan
- **Penulis**: Badruddin
- **Kategori**: Biografi
- **Harga**: Rp90,000 (default, sama seperti buku referensi)
- **Halaman**: 240
- **Stok**: 50
- **Image Path**: `book-covers/dari-balik-penjara.jpg`

Atribut lainnya (penerbit, isbn, terjual) akan dikopikan dari buku yang sudah ada atau menggunakan default sensible.

Seeder akan otomatis menghindari duplikat judul jika sudah pernah dijalankan sebelumnya.
