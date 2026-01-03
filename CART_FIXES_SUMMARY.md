# Ruang Aksara - Cart (Keranjang) Functionality Fixes

## Problem Summary
The shopping cart functionality had CSRF token mismatch errors that prevented users from adding items to cart, clearing cart, and properly increasing/decreasing quantities.

## Root Causes Fixed

### 1. **CSRF Token Mismatch Error on Clear Cart**
   - **Issue**: The clear cart button was using GET method without CSRF protection
   - **Fix**: Changed to POST method with `@csrf` token in the form
   - **Files Modified**: `resources/views/cart/index.blade.php`

### 2. **Increase/Add Button in Cart View**
   - **Issue**: The increase quantity button in cart was an anchor tag (`<a>`) instead of a proper form
   - **Fix**: Changed to a proper form submission with POST method and CSRF token
   - **Files Modified**: `resources/views/cart/index.blade.php`

### 3. **Route Configuration**
   - **Issue**: Cart routes weren't properly configured for state-changing operations
   - **Fix**: 
     - Added POST route for `/cart/add/{book}` (named `cart.add.post`)
     - Added POST route for `/cart/increase/{book}` (named `cart.increase`)
     - Changed `/cart/clear` from GET to POST
   - **Files Modified**: `routes/web.php`

### 4. **Missing CSRF Token Meta Tag**
   - **Issue**: The CSRF token meta tag was missing from the books index page
   - **Fix**: Added `<meta name="csrf-token" content="{{ csrf_token() }}">` to head section
   - **Files Modified**: `resources/views/books/index.blade.php`

## Changes Made

### File: `routes/web.php`
```php
// ✅ CART ROUTES
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/add/{book}', [CartController::class, 'add'])->name('add');
    Route::post('/add/{book}', [CartController::class, 'add'])->name('add.post');           // NEW: POST for AJAX
    Route::get('/api/count', [CartController::class, 'getCartCount'])->name('apiCount');
    Route::post('/increase/{book}', [CartController::class, 'add'])->name('increase');     // NEW: POST for form
    Route::post('/decrease/{book}', [CartController::class, 'decrease'])->name('decrease');
    Route::post('/remove/{book}', [CartController::class, 'remove'])->name('remove');
    Route::post('/update-quantity/{book}', [CartController::class, 'updateQuantity'])->name('updateQuantity');
    Route::get('/checkout-form', [CartController::class, 'checkoutForm'])->name('checkoutForm');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');              // CHANGED: GET to POST
});
```

### File: `resources/views/cart/index.blade.php`

#### Change 1: Increase Button (Lines 70-90)
```blade
<!-- OLD: Anchor tag without CSRF -->
<a href="{{ route('cart.add', $book->id) }}" class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition"
   @if ($cart[$book->id] >= $book->stok) onclick="return false; return confirm('Stok tidak cukup!')" style="opacity: 0.5; cursor: not-allowed;" @endif>
    <i class="fas fa-plus"></i>
</a>

<!-- NEW: Form with POST and CSRF -->
<form action="{{ route('cart.increase', $book->id) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" 
            class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition border-0 bg-transparent cursor-pointer"
            @if ($cart[$book->id] >= $book->stok) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
        <i class="fas fa-plus"></i>
    </button>
</form>
```

#### Change 2: Clear Cart Button (Lines 167-172)
```blade
<!-- OLD: GET method -->
<form action="{{ route('cart.clear') }}" method="GET" style="display:inline-block; width:100%;" onsubmit="return confirm('Yakin ingin mengosongkan keranjang?')">
    <button type="submit" class="w-full bg-red-100 text-red-600 py-2 rounded-lg font-semibold hover:bg-red-200 transition border-0 cursor-pointer">
        <i class="fas fa-trash mr-2"></i>Kosongkan Keranjang
    </button>
</form>

<!-- NEW: POST method with CSRF -->
<form action="{{ route('cart.clear') }}" method="POST" style="display:inline-block; width:100%;" onsubmit="return confirm('Yakin ingin mengosongkan keranjang?')">
    @csrf
    <button type="submit" class="w-full bg-red-100 text-red-600 py-2 rounded-lg font-semibold hover:bg-red-200 transition border-0 cursor-pointer">
        <i class="fas fa-trash mr-2"></i>Kosongkan Keranjang
    </button>
</form>
```

### File: `resources/views/books/index.blade.php`
```html
<!-- Added CSRF token meta tag in <head> -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```

## Cart Functionality - Flow Overview

### Adding to Cart (from Books Catalog)
1. User clicks "Keranjang" button on book card
2. JavaScript function `addToCartAjax()` sends POST request to `/cart/add/{bookId}`
3. Request includes CSRF token from meta tag: `X-CSRF-TOKEN` header
4. CartController validates and adds book to session
5. Success response updates cart count badge globally

### Cart Management (in Cart Page)
1. **Decrease Quantity**: POST form to `/cart/decrease/{bookId}` with CSRF token
2. **Increase Quantity**: POST form to `/cart/increase/{bookId}` with CSRF token
3. **Remove Item**: POST form to `/cart/remove/{bookId}` with CSRF token
4. **Clear Cart**: POST form to `/cart/clear` with CSRF token

### Checkout
1. User clicks "Lanjutkan ke Checkout" 
2. GET request to `/cart/checkout-form` displays form
3. User fills in delivery address and payment method
4. POST request to `/cart/checkout` with CSRF token
5. System creates Order records and clears cart

## Testing Checklist

- ✅ Add book to cart from catalog (AJAX request)
- ✅ Increase quantity in cart view (POST form)
- ✅ Decrease quantity in cart view (POST form)
- ✅ Remove item from cart (POST form)
- ✅ Clear entire cart (POST form)
- ✅ Proceed to checkout
- ✅ Complete checkout process
- ✅ Verify cart count updates globally

## Technical Details

### CSRF Token Handling
- **Global AJAX Requests**: Include `X-CSRF-TOKEN` header
- **Form Submissions**: Include `@csrf` Blade directive
- **Session-based Cart**: Uses Laravel session middleware for persistence
- **CORS**: Same-origin requests only

### HTTP Methods
- **GET**: Safe, idempotent operations (view cart, get count)
- **POST**: State-changing operations (add, remove, clear, checkout)

## Backward Compatibility
- GET route for `/cart/add/{book}` still works for legacy links
- POST route `/cart/add/{book}` for new AJAX implementation
- All changes maintain existing session-based cart storage

---

**Date Fixed**: 2025-12-02  
**Version**: v1.0  
**Status**: ✅ Complete
