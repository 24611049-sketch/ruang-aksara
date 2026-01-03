# Panduan Kelola Stok Peminjaman Buku

## 📚 Ringkasan Fitur

Sistem "Kelola Stok Peminjaman" memungkinkan admin/owner untuk:
1. **Menambah buku baru** yang bisa dipinjamkan
2. **Mengatur stok peminjaman** untuk setiap buku
3. **Melihat riwayat perubahan** stok
4. **Integrasi otomatis** dengan halaman peminjaman

---

## 🚀 Cara Menggunakan

### 1. Akses Halaman Kelola Stok Peminjaman
- Klik menu **"Stok Pinjam"** di navigation bar admin
- Atau akses langsung: `/admin/loan-stock`

### 2. Tambah Buku Baru

#### Langkah-langkah:
1. Klik tombol **"Tambah Buku Baru"** (warna biru)
2. Isi form dengan data buku:
   - **Judul** (wajib) - Nama buku
   - **Penulis** (wajib) - Nama penulis
   - **Kategori** (wajib) - Kategori buku (pilih dari daftar atau ketik baru)
   - **Stok Peminjaman** (wajib) - **PENTING**: Jumlah buku yang bisa dipinjam
   - Data lainnya (opsional) - ISBN, halaman, harga, deskripsi, dll

3. Klik **"Simpan Buku"**
4. Buku akan langsung tersedia untuk dipinjam dengan stok yang Anda tentukan

### 3. Mengelola Stok Peminjaman

#### View Halaman Utama:
- **Tabel Buku**: Menampilkan semua buku dengan stok saat ini
- **Status Badge**: 
  - 🔴 **Habis** = Stok peminjaman 0
  - 🟠 **Rendah** = Stok peminjaman < 5
  - 🟢 **Tersedia** = Stok peminjaman >= 5

#### Mengubah Stok:
1. Ubah nilai di kolom **"Stok Peminjaman"**
2. Klik tombol **"Simpan"** di baris yang sama
3. Konfirmasi perubahan
4. Stok akan langsung terupdate dan tersimpan di database

#### Melihat Riwayat:
1. Klik tombol **"Riwayat"** di kolom aksi
2. Modal akan menampilkan:
   - Stok saat ini
   - Daftar 10 perubahan terakhir
   - Tanggal perubahan
   - Tipe perubahan (Peminjaman/Penyesuaian)
   - Perubahan stok (dari → ke)

### 4. Filter & Pencarian

- **Cari Buku**: Ketik judul atau nama penulis
- **Kategori**: Filter berdasarkan kategori buku
- **Urutkan**: 
  - Nama (A-Z)
  - Stok (Terendah ke Tertinggi)
  - Stok (Tertinggi ke Terendah)

---

## 🔗 Integrasi dengan Peminjaman

### Alur Otomatis:
1. Buku ditambahkan di **Kelola Stok Peminjaman** dengan `loan_stok = N`
2. Buku langsung muncul di halaman **"Tambah Peminjaman"**
3. Ketika peminjaman dibuat, stok `loan_stok` berkurang otomatis
4. Ketika buku dikembalikan, stok `loan_stok` bertambah otomatis

### Praktik Terbaik:
- ✅ Selalu atur stok peminjaman saat menambah buku baru
- ✅ Monitor stok secara berkala (terutama buku dengan stok rendah)
- ✅ Gunakan riwayat untuk audit perubahan stok
- ✅ Sesuaikan stok berdasarkan ketersediaan fisik buku

---

## 📊 Contoh Penggunaan

### Scenario 1: Tambah Buku Baru
```
Admin menambah buku "Laskar Pelangi" karya Andrea Hirata
- Stok Peminjaman: 5 (5 copy bisa dipinjam)
- Hasil: Buku langsung tersedia untuk dipinjam
```

### Scenario 2: Mengurangi Stok
```
Setelah 3 peminjaman, stok "Laskar Pelangi" = 2
- Admin memeriksa halaman Kelola Stok
- Melihat status "Rendah" (🟠)
- Dapat menambah stok kembali jika ada buku baru
```

### Scenario 3: Buku Hilang/Rusak
```
Buku yang dipinjam tidak dikembalikan
- Admin bisa mengurangi stok secara manual di halaman Kelola Stok
- Riwayat akan mencatat perubahan sebagai "Penyesuaian"
```

---

## ⚙️ Technical Details

### Database:
- Kolom: `loan_stok` (integer, default 0)
- Model: `Book`
- Table: `books`

### API Endpoints:
- `GET /admin/loan-stock` - Tampilkan semua buku
- `GET /admin/loan-stock/create` - Form tambah buku
- `POST /admin/loan-stock` - Simpan buku baru
- `PUT /admin/loan-stock/{id}` - Update stok buku
- `GET /admin/loan-stock/{id}/history` - Ambil riwayat perubahan

### Logging:
- Semua perubahan stok dicatat di tabel `stock_logs`
- Tipe perubahan: `loan`, `loan_adjustment`
- Audit trail otomatis untuk keamanan

---

## 🐛 Troubleshooting

### Masalah: Buku tidak muncul di halaman peminjaman
**Solusi:**
1. Pastikan `loan_stok > 0` di halaman Kelola Stok
2. Refresh halaman peminjaman
3. Periksa browser console untuk error

### Masalah: Stok tidak berkurang saat peminjaman dibuat
**Solusi:**
1. Periksa apakah kolom `loan_stok` ada di database
2. Jalankan: `php artisan migrate`
3. Pastikan stok peminjaman cukup sebelum membuat peminjaman

### Masalah: Riwayat tidak menampilkan data
**Solusi:**
1. Pastikan tabel `stock_logs` sudah ada
2. Jalankan: `php artisan migrate`
3. Buat perubahan stok baru untuk mulai mencatat

---

## 📝 Notes
- Kolom `stok` = Stok untuk penjualan buku
- Kolom `loan_stok` = Stok untuk peminjaman buku (BARU)
- Keduanya terpisah dan independen
- Admin dapat mengelola kedua stok secara terpisah

Terakhir diupdate: **3 Desember 2025**
