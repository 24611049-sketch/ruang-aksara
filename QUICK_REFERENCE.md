# 🎓 Quick Reference Guide - Book Management System

## 📚 Key Integration Points

### 1. Admin Panel Flow

```
ADMIN DASHBOARD
  ↓
KELOLA BUKU (/admin/books)
  ├─ Lihat semua buku dengan foto cover
  ├─ Status stok: HABIS (🔴) | RENDAH (🟡) | TERSEDIA (🟢)
  └─ Actions: Edit | Hapus
      ↓
    TAMBAH BUKU BARU (/admin/books/create)
      ├─ Form: Judul, Penulis, Kategori, Harga, Stok, Halaman, ISBN, Penerbit, Deskripsi
      ├─ Upload Cover Image (JPEG/PNG/GIF, max 2MB)
      └─ Submit → Disimpan ke storage/app/public/book-covers/
      
    EDIT BUKU (/admin/books/{id}/edit)
      ├─ Update semua field
      ├─ Lihat cover lama
      ├─ Upload cover baru
      └─ Submit → Update ke storage
```

### 2. User Catalog Flow

```
USER DASHBOARD
  ↓
KATALOG BUKU (/books)
  ├─ Grid display dengan COVER PHOTO setiap buku
  ├─ Search & Filter by Category
  ├─ Lihat: Judul, Penulis, Harga, Rating, Stok Status
  └─ Action: Detail | Add to Cart
      ↓
    DETAIL BUKU (/books/{id})
      ├─ LARGE BOOK COVER (High Quality)
      ├─ Semua info: ISBN, Penerbit, Halaman, Kategori, Deskripsi
      ├─ Status stok: "Stok tersedia: X unit" atau "HABIS"
      ├─ Related Books dari kategori sama
      └─ Action: Add to Cart | Add to Wishlist
          ↓
        KERANJANG BELANJA (/cart)
          └─ Quantity × Harga = Total
              ↓
            CHECKOUT
              └─ Stok OTOMATIS berkurang (-quantity) ✅
```

### 3. Stock Management Flow

```
BOOK CREATED
├─ Initial Stok = User Input (e.g., 10)
├─ Terjual = 0
└─ Status = available

         ↓ (User checkout)

ORDER CREATED
├─ stok -= quantity (10 - 3 = 7) ✅
├─ terjual += quantity (0 + 3 = 3) ✅
└─ Order Status = pending

         ↓ (Admin cancel order)

ORDER CANCELLED
├─ stok += quantity (7 + 3 = 10) ✅
├─ terjual -= quantity (3 - 3 = 0) ✅
└─ Order Status = cancelled
```

---

## 💾 Storage Configuration

### Upload Path
```
public/storage (symlink)
    ↓
storage/app/public/
    ├─ book-covers/ ← Cover files disimpan di sini
    │   ├─ cover-1.jpg
    │   ├─ cover-2.png
    │   └─ cover-3.gif
    └─ other files...
```

### Access via Web
```
Upload: POST /admin/books/create
File saved: storage/app/public/book-covers/cover-abc123.jpg
Access via: /storage/book-covers/cover-abc123.jpg
```

### Database Storage
```
Table: books
├─ id
├─ judul
├─ penulis
├─ harga
├─ kategori
├─ stok ← ⭐ Stock tracking
├─ terjual ← ⭐ Sales tracking
├─ status (available/unavailable)
├─ image ← ⭐ Only filename stored (e.g., "cover-abc123.jpg")
└─ timestamps
```

---

## 🔧 Important Code Snippets

### Stock Decrement on Checkout
**File**: `app/Http/Controllers/CartController.php` → `checkout()`
```php
foreach ($cart as $bookId => $quantity) {
    $book->decrement('stok', $quantity);      // ← Stok berkurang
    $book->increment('terjual', $quantity);   // ← Terjual bertambah
}
```

### Stock Restore on Cancel
**File**: `app/Http/Controllers/OrderController.php` → `updateStatus()`
```php
if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
    $order->book->increment('stok', $order->quantity);      // ← Stok kembali
    $order->book->decrement('terjual', $order->quantity);   // ← Terjual berkurang
}
```

### Image Display in Views
**File**: `resources/views/books/index.blade.php`
```php
@if($book->image)
    <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->judul }}">
@else
    <div class="placeholder">No Image</div>
@endif
```

### Form Upload & Validation
**File**: `app/Http/Controllers/BookController.php` → `store()`
```php
'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

if ($request->hasFile('image')) {
    $path = $request->file('image')->store('book-covers', 'public');
    $validated['image'] = basename($path);  // ← Simpan hanya filename
}
```

---

## ✨ View & Admin Features

### Admin Book Index (Grid Layout)
- [x] Cover thumbnail di sebelah kiri (28x40 pixels)
- [x] Book info di tengah (Judul, Penulis, Kategori, ISBN)
- [x] Price & Stock di kanan
- [x] Stock status badge dengan warna
- [x] Edit/Delete actions di far right

### User Book Catalog (Grid Layout)
- [x] Cover gambar penuh di atas (200px height)
- [x] Hot badge di top-left
- [x] Book title (2 line clamp)
- [x] Author name & Rating stars
- [x] Price dengan warna merah
- [x] Badge kategori
- [x] Detail button + Add to Cart button

### Image Upload Form
- [x] File input dengan accept="image/*"
- [x] Live preview sebelum submit
- [x] Format & size validation
- [x] Error messages jika invalid
- [x] Display current image di edit form

---

## 📊 Monitoring Stock

### Admin Dashboard Statistics
```
├─ Total Buku
├─ Stok Rendah (≤5) 
├─ Habis Stok (=0)
└─ Jumlah Kategori
```

### Low Stock Alert
**Route**: `/admin/books/stock/low`
- Buku dengan stok ≤5 unit
- Buku dengan stok ≤2 unit (critical)

### Book Info Display
```
Book Card menampilkan:
├─ Stok: X unit
├─ Status: HABIS | RENDAH | TERSEDIA (colored badge)
├─ Terjual: Y unit
└─ Availability: Aktif/Nonaktif
```

---

## 🚨 Common Issues & Solutions

### Image tidak muncul di user view
```
1. Cek apakah file ada di: storage/app/public/book-covers/
2. Verifikasi symlink: ls -la public/storage
3. Cek permission: chmod 755 storage/app/public/book-covers
4. Clear cache: php artisan cache:clear
```

### Stok tidak berkurang saat checkout
```
1. Cek OrderController::checkout() method
2. Verifikasi $book->decrement('stok', $quantity) dipanggil
3. Cek database: SELECT * FROM orders WHERE id=X
4. Trace log di storage/logs/laravel.log
```

### Upload gagal dengan error
```
1. Cek max upload size di php.ini
2. Verifikasi folder permission: 755
3. Cek validation rule di controller
4. Lihat error message di form
```

### Cover tidak display, hanya placeholder
```
1. Verifikasi image column di database tidak null
2. Cek filename format di database
3. Verifikasi asset path di view
4. Inspect element → Check <img src>
```

---

## 📝 Routes Reference

### Public Routes
```
GET  /books                    → List all available books
GET  /books/{id}               → Book detail view
```

### Admin Routes
```
GET    /admin/books            → List all books
GET    /admin/books/create     → Create form
POST   /admin/books            → Store new book
GET    /admin/books/{id}/edit  → Edit form
PATCH  /admin/books/{id}       → Update book
DELETE /admin/books/{id}       → Delete book
PUT    /admin/books/{id}/stock → Update stock
GET    /admin/books/stock/low  → Low stock report
```

### Cart & Order Routes
```
GET    /cart                    → View cart
POST   /cart/add/{book}         → Add to cart
POST   /cart/decrease/{book}    → Decrease quantity
POST   /cart/remove/{book}      → Remove from cart
GET    /checkout/form           → Checkout page
POST   /checkout                → Process checkout
PATCH  /admin/orders/{id}/status → Update order status
```

---

## 🎯 Next Steps for Full Implementation

1. **Payment Gateway Integration**
   - Integrate with payment processor (e.g., Midtrans, Stripe)
   - Auto-update order status from payment provider

2. **Email Notifications**
   - Send order confirmation with book cover thumbnail
   - Stock low notifications to admin

3. **Analytics & Reports**
   - Best-selling books by cover view
   - Stock movement history
   - Sales by category

4. **Advanced Features**
   - Bulk book import with images
   - Image optimization & CDN integration
   - Book ratings & reviews with photo preview

---

*Last Updated: 2 Desember 2025*
*Ruang Aksara Book Management System v1.0*
