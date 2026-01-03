# Opsi Perhitungan Ongkos Kirim

## 🎯 Pilihan Strategi Ongkir

### 1. **Flat Rate Sederhana** (Paling Mudah - Recommended untuk Mulai)
Ongkir tetap berdasarkan kurir yang dipilih, tidak peduli berat/jarak.

**Implementasi:** Sudah diterapkan di sistem saat ini
```php
// Di controller checkout
$shippingRates = [
    'jne' => 15000,
    'jnt' => 14000,
    'ninja' => 20000,
    'antera' => 12000
];
```

**Kelebihan:**
- ✅ Mudah dikelola
- ✅ Tidak perlu integrasi API
- ✅ Transparan untuk customer

**Kekurangan:**
- ❌ Kurang adil (berat 1 buku = 10 buku sama)
- ❌ Bisa rugi kalau berat banyak
- ❌ Tidak real-time

---

### 2. **Flat Rate + Berat Bertingkat** (Balance)
Ongkir berdasarkan total berat buku di keranjang.

**Implementasi:**
```php
// app/Services/ShippingCalculator.php (sudah ada di sistem)
public function calculate($books, $shippingMethod)
{
    $totalWeight = 0;
    foreach ($books as $book) {
        $totalWeight += ($book->berat ?? 500) * $cart[$book->id];
    }
    
    // Tarif bertingkat per kg
    $baseRates = [
        'jne' => ['base' => 15000, 'per_kg' => 5000],
        'jnt' => ['base' => 14000, 'per_kg' => 4500],
        'ninja' => ['base' => 20000, 'per_kg' => 6000],
        'antera' => ['base' => 12000, 'per_kg' => 4000]
    ];
    
    $weightInKg = ceil($totalWeight / 1000);
    $rate = $baseRates[$shippingMethod];
    
    return $rate['base'] + ($weightInKg * $rate['per_kg']);
}
```

**Cara Pakai:**
1. Tambahkan kolom `berat` (integer, gram) di tabel `books`
2. Update setiap buku dengan berat estimasi (~300-800g)
3. Panggil `ShippingCalculator::calculate()` di controller

**Kelebihan:**
- ✅ Lebih adil berdasarkan berat
- ✅ Tetap sederhana
- ✅ Tidak perlu API eksternal

**Kekurangan:**
- ❌ Harus maintain data berat buku
- ❌ Tidak real-time dari kurir

---

### 3. **RajaOngkir API** (Paling Akurat - Recommended untuk Production)
Integrasi dengan API RajaOngkir untuk ongkir real-time dari kurir.

**Setup:**
1. Daftar di https://rajaongkir.com (Gratis: 1000 request/bulan)
2. Install package:
```bash
composer require rajaongkir/rajaongkir
```

3. Tambah API key di `.env`:
```env
RAJAONGKIR_API_KEY=your_api_key_here
```

4. Implementasi Service:
```php
// app/Services/RajaOngkirService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    private $apiKey;
    private $baseUrl = 'https://api.rajaongkir.com/starter';
    
    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.key');
    }
    
    public function getCities()
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->get($this->baseUrl . '/city');
        
        return $response->json();
    }
    
    public function calculateCost($origin, $destination, $weight, $courier)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->post($this->baseUrl . '/cost', [
            'origin' => $origin, // ID kota asal (misal Jakarta: 152)
            'destination' => $destination, // ID kota tujuan
            'weight' => $weight, // dalam gram
            'courier' => $courier // jne, jnt, pos, tiki, dll
        ]);
        
        return $response->json();
    }
}
```

5. Update checkout flow:
```php
// CartController@checkout
$rajaOngkir = new RajaOngkirService();
$totalWeight = /* hitung total berat */;

$cost = $rajaOngkir->calculateCost(
    152, // Jakarta (kota toko)
    $request->city_id, // dari input customer
    $totalWeight,
    $request->shipping_method
);

$shippingCost = $cost['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'];
```

**Kelebihan:**
- ✅ Ongkir real-time dari kurir
- ✅ Akurat 100%
- ✅ Support banyak kurir
- ✅ Profesional

**Kekurangan:**
- ❌ Butuh API key (limited free tier)
- ❌ Lebih kompleks
- ❌ Harus handle error/timeout API

---

### 4. **Zona Wilayah** (Alternative Sederhana)
Bagi Indonesia jadi beberapa zona dengan tarif berbeda.

**Implementasi:**
```php
// config/shipping_zones.php
return [
    'zona_1' => ['DKI Jakarta', 'Jawa Barat', 'Banten'], // 15k
    'zona_2' => ['Jawa Tengah', 'Jawa Timur', 'DI Yogyakarta'], // 20k
    'zona_3' => ['Sumatera', 'Kalimantan', 'Sulawesi'], // 30k
    'zona_4' => ['Papua', 'Maluku', 'NTT', 'NTB'], // 50k
];

// ShippingCalculator
public function calculateByZone($province, $courier)
{
    $zoneRates = [
        'zona_1' => ['jne' => 15000, 'jnt' => 14000],
        'zona_2' => ['jne' => 20000, 'jnt' => 18000],
        'zona_3' => ['jne' => 30000, 'jnt' => 28000],
        'zona_4' => ['jne' => 50000, 'jnt' => 45000],
    ];
    
    $zone = $this->getZoneByProvince($province);
    return $zoneRates[$zone][$courier] ?? 15000;
}
```

**Kelebihan:**
- ✅ Lebih adil dari flat rate
- ✅ Mudah dikelola
- ✅ Tidak perlu API

**Kekurangan:**
- ❌ Tetap estimasi
- ❌ Harus update manual kalau tarif berubah

---

## 🎯 Rekomendasi

### Untuk Skala Kecil/Development:
**Gunakan Opsi 2 (Flat Rate + Berat)** - Balance antara akurasi dan kemudahan

### Untuk Production:
**Gunakan Opsi 3 (RajaOngkir API)** - Paling profesional dan akurat

### Quick Win:
Mulai dengan **Opsi 1 (Flat Rate)** yang sudah ada, upgrade ke RajaOngkir nanti setelah dapat customer.

---

## 📝 Action Steps

1. **Sekarang (sudah aktif):** Flat rate sederhana
2. **Minggu depan:** Tambah kolom `berat` di tabel books + implementasi Opsi 2
3. **Setelah launch:** Daftar RajaOngkir + implementasi Opsi 3
4. **Bonus:** Tambah "Free Shipping" di atas pembelian Rp 200.000

---

## 💡 Tips

- Tampilkan estimasi jelas di checkout: "Estimasi Ongkir (bisa berubah)"
- Verifikasi ongkir final saat admin approve order
- Kasih customer notif kalau ada perbedaan ongkir
- Tracking: simpan log perhitungan ongkir untuk analisis
