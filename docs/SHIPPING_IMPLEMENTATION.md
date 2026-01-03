# Implementasi Sistem Ongkir Otomatis ✅

## 📦 Fitur yang Sudah Diimplementasikan

### 1. Database & Model
✅ **Migration**: Tambah kolom `berat` (integer, gram) di tabel `books`
✅ **Model Book**: Update fillable dengan field `berat`
✅ **Default Value**: 500g untuk buku yang belum ada beratnya

### 2. Admin Panel - Kelola Berat Buku
✅ **Form Create Book**: Input berat dengan helper text estimasi
✅ **Form Edit Book**: Input berat dengan value existing
✅ **Validasi**: Required, min 1 gram
✅ **Helper Text**: "Novel ~400g, Komik ~300g, Textbook ~800g"

### 3. Frontend - Tampilan Berat
✅ **Detail Buku**: Tampilkan berat di info buku (books/show.blade.php)
✅ **Format**: {berat}g (misal: 450g)

### 4. Config Zona Wilayah
✅ **File**: `config/shipping.php`
✅ **7 Zona**: Jakarta, Jawa Tengah-Timur, Sumatera, Kalimantan, Sulawesi, Bali-NTT, Maluku-Papua
✅ **Tarif Per Zona**: Base rate + per kg untuk setiap kurir (JNE, J&T, Ninja, AnterAja)
✅ **Free Shipping**: Minimum Rp 300.000

### 5. ShippingCalculator Service
✅ **File**: `app/Services/ShippingCalculator.php`
✅ **Method calculate()**: Hitung ongkir berdasarkan berat + provinsi + kurir
✅ **Method isFreeShipping()**: Cek eligibility gratis ongkir
✅ **Method getProvinces()**: List semua provinsi
✅ **Return**: ['cost' => int, 'eta' => string, 'zone' => string, 'zone_name' => string]

### 6. Form Checkout - Alamat Lengkap
✅ **Nama Penerima**: Required
✅ **Telepon**: Required
✅ **Provinsi**: Dropdown dengan semua provinsi dari config
✅ **Kota/Kabupaten**: Input text required
✅ **Kecamatan**: Input text required
✅ **Kode Pos**: Input 5 digit required
✅ **Alamat Detail**: Textarea untuk jalan, no rumah, RT/RW

### 7. Perhitungan Ongkir Otomatis
✅ **Trigger**: onChange di dropdown provinsi & kurir
✅ **JavaScript**: Function `calculateShipping()` 
✅ **Zona Detection**: Match provinsi dengan zona config
✅ **Formula**: Base rate + (per_kg × (weight_kg - 1))
✅ **Display Real-time**: Update estimasi, zona, dan total harga

### 8. Cart Controller Integration
✅ **checkoutForm()**: Hitung total berat dari semua buku di cart
✅ **checkout()**: 
  - Validasi alamat lengkap (provinsi, kota, kecamatan, kode pos)
  - Hitung ongkir server-side dengan ShippingCalculator
  - Format alamat lengkap untuk disimpan di order
  - Distribute ongkir proporsional ke setiap order

---

## 🎯 Cara Penggunaan

### Admin - Set Berat Buku
1. Login sebagai admin
2. Pergi ke **Kelola Buku** → **Tambah Buku** atau **Edit Buku**
3. Isi field **Berat (gram)** dengan estimasi:
   - Novel: ~400-500g
   - Komik: ~300-350g
   - Textbook: ~700-900g
   - Hardcover: ~600-800g
4. Save buku

### User - Checkout dengan Ongkir Otomatis
1. Tambahkan buku ke keranjang
2. Klik **Lanjutkan ke Checkout**
3. Isi **Informasi Pengiriman**:
   - Nama penerima
   - Telepon
   - **Pilih Provinsi** → Ongkir akan auto-calculate
   - Kota/Kabupaten
   - Kecamatan
   - Kode Pos (5 digit)
   - Alamat lengkap (Jl, No, RT/RW)
4. Pilih **Metode Pengiriman** (JNE/J&T/Ninja/AnterAja) → Ongkir update
5. Lihat **Rincian Pengiriman**:
   - Berat Total: {total}g
   - Zona: {nama_zona}
   - Biaya Ongkir: Rp {cost}
6. Pilih metode pembayaran
7. Upload bukti transfer (jika transfer bank)
8. **Konfirmasi Checkout**

---

## 💡 Contoh Perhitungan

### Skenario 1: Pesan 2 Novel ke Jakarta
- **Buku 1**: 400g × 1 = 400g
- **Buku 2**: 450g × 2 = 900g
- **Total Berat**: 1.300g ≈ 2kg
- **Provinsi**: DKI Jakarta (Zona 1)
- **Kurir**: JNE Regular
- **Perhitungan**: Rp 10.000 (base) + (Rp 3.000 × 1kg) = **Rp 13.000**

### Skenario 2: Pesan 5 Textbook ke Papua
- **Total Berat**: 800g × 5 = 4.000g ≈ 4kg
- **Provinsi**: Papua (Zona 7)
- **Kurir**: J&T Regular
- **Perhitungan**: Rp 45.000 (base) + (Rp 11.000 × 3kg) = **Rp 78.000**

### Skenario 3: Free Shipping
- **Subtotal**: Rp 350.000
- **Threshold**: Rp 300.000
- **Ongkir**: **Rp 0** (GRATIS!)

---

## 🔧 Konfigurasi Tarif

Edit file `config/shipping.php` untuk update tarif:

```php
'zona_1' => [
    'name' => 'Jakarta & Sekitarnya',
    'provinces' => ['DKI Jakarta', 'Banten', 'Jawa Barat'],
    'rates' => [
        'jne' => ['base' => 10000, 'per_kg' => 3000],
        'jnt' => ['base' => 9000, 'per_kg' => 2500],
        // dst...
    ]
]
```

**Parameter**:
- `base`: Tarif dasar (Rp)
- `per_kg`: Tarif per kilogram tambahan (Rp)

---

## 📊 Data yang Disimpan di Order

```
alamat: "Jl. Merdeka No. 123, RT 01/RW 05, Kelurahan Senayan
         Jakarta Selatan, Kec. Kebayoran Baru
         DKI Jakarta, 12345
         (Jakarta & Sekitarnya)"
         
telepon: "08123456789"
shipping_cost: 13000 (disimpan proporsional per order)
```

---

## 🚀 Fitur Tambahan yang Bisa Ditambahkan

1. **RajaOngkir API Integration**: Ongkir real-time dari kurir
2. **Tracking Resi**: Update status pengiriman
3. **Estimasi Sampai**: Tampilkan tanggal perkiraan
4. **Asuransi**: Optional untuk barang mahal
5. **Pickup Point**: COD di lokasi tertentu
6. **Notif WA/Email**: Konfirmasi ongkir ke customer

---

## ✅ Testing Checklist

- [x] Migration berat berjalan tanpa error
- [x] Input berat muncul di form admin create/edit
- [x] Berat tampil di detail buku
- [x] Dropdown provinsi terisi dari config
- [x] Perhitungan ongkir update saat ganti provinsi
- [x] Perhitungan ongkir update saat ganti kurir
- [x] Total harga include ongkir
- [x] Validasi alamat lengkap berfungsi
- [x] Checkout berhasil dengan alamat terformat
- [x] Order tersimpan dengan ongkir yang benar

---

## 🎉 Selesai!

Sistem ongkir otomatis sudah fully functional dengan:
✅ Perhitungan akurat berdasarkan berat & zona
✅ Form alamat lengkap terstruktur
✅ Real-time calculation di frontend
✅ Server-side validation & calculation
✅ Admin bisa manage berat buku
✅ User melihat estimasi jelas sebelum checkout
