@extends('layouts.app')

@section('title', 'Edit Buku - Admin')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Edit Buku</h1>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-6">
                    <!-- Judul Buku -->
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700">Judul Buku *</label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul', $book->judul) }}" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Penulis -->
                    <div>
                        <label for="penulis" class="block text-sm font-medium text-gray-700">Penulis *</label>
                        <input type="text" name="penulis" id="penulis" value="{{ old('penulis', $book->penulis) }}" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori *</label>
                        <div class="flex gap-2 mt-1">
                            <select name="kategori" id="kategori" required 
                                    class="flex-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategories as $kategori)
                                    <option value="{{ $kategori }}" {{ old('kategori', $book->kategori) == $kategori ? 'selected' : '' }}>
                                        {{ $kategori }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" onclick="openCategoryModalBooksEdit()" 
                                    class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-semibold whitespace-nowrap">
                                <i class="fas fa-plus mr-1"></i>Baru
                            </button>
                        </div>
                    </div>

                    <!-- Penerbit -->
                    <div>
                        <label for="penerbit" class="block text-sm font-medium text-gray-700">Penerbit</label>
                        <input type="text" name="penerbit" id="penerbit" value="{{ old('penerbit', $book->penerbit) }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                        <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6">
                    <!-- Harga Jual -->
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700">Harga Jual (Rp)</label>
                        <input type="number" name="harga" id="harga" value="{{ old('harga') ?? $book->harga }}" min="0" step="1"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Optional - akan dihitung otomatis jika kosong</p>
                    </div>

                    <!-- Harga Beli -->
                    <div>
                        <label for="purchase_price" class="block text-sm font-medium text-gray-700">Harga Beli (Rp)</label>
                        <input type="number" name="purchase_price" id="purchase_price" value="{{ old('purchase_price') ?? $book->purchase_price }}" min="0" step="1"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Optional - akan dihitung otomatis jika kosong</p>
                    </div>

                    <!-- Margin Keuntungan -->
                    <div>
                        <label for="profit_margin_percent" class="block text-sm font-medium text-gray-700">Margin Keuntungan (%) *</label>
                        <div class="flex gap-2">
                            <input type="number" name="profit_margin_percent" id="profit_margin_percent" value="{{ old('profit_margin_percent', $book->profit_margin_percent ?? 35) }}" required min="0" max="100" step="1"
                                   class="flex-1 mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" onclick="autoCalculateSellingPrice()" class="mt-1 px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition whitespace-nowrap font-semibold">
                                <i class="fas fa-calculator mr-1"></i>Hitung
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Novel/Fiksi: 35%, Referensi: 25%, Non-Fiksi: 30%</p>
                    </div>

                    <!-- Stok -->
                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-700">Stok *</label>
                        <input type="number" name="stok" id="stok" value="{{ old('stok', $book->stok) }}" required min="0" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Berat -->
                    <div>
                        <label for="berat" class="block text-sm font-medium text-gray-700">
                            Berat (gram) *
                            <span class="text-xs text-gray-500 font-normal">- untuk perhitungan ongkir</span>
                        </label>
                        <input type="number" name="berat" id="berat" value="{{ old('berat', $book->berat ?? 500) }}" required min="1" step="1"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Estimasi: Novel ~400g, Komik ~300g, Textbook ~800g</p>
                    </div>

                    <!-- Halaman -->
                    <div>
                        <label for="halaman" class="block text-sm font-medium text-gray-700">Jumlah Halaman *</label>
                        <input type="number" name="halaman" id="halaman" value="{{ old('halaman', $book->halaman) }}" required min="1" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                        <select name="status" id="status" required 
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="available" {{ old('status', $book->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="unavailable" {{ old('status', $book->status) == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                    </div>

                    <!-- Cover Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Cover Buku</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Format: JPEG, PNG, JPG, GIF (Maks. 2MB)</p>
                        
                        <!-- Current Image Display -->
                        @if($book->image)
                            <div class="mt-2 p-2 bg-gray-50 rounded border border-gray-200">
                                <p class="text-xs text-gray-600 mb-2">Cover Saat Ini:</p>
                                <img src="{{ asset('storage/book-covers/' . $book->image) }}" alt="{{ $book->judul }}" class="h-32 w-24 object-cover rounded border border-gray-300">
                            </div>
                        @endif

                        <!-- Image Preview for New Upload -->
                        <div id="imagePreview" class="mt-2 hidden">
                            <p class="text-xs text-gray-600 mb-2">Preview Baru:</p>
                            <img id="preview" class="h-32 w-24 object-cover rounded border border-gray-300">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deskripsi (Full Width) -->
            <div class="mt-6">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi *</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" required
                          class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi', $book->deskripsi) }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Update Buku
                </button>
                
                <!-- TOMBOL BATAL -->
                <button type="button" onclick="handleBatal()" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-400 flex items-center transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Auto-calculate Harga Jual (Selling Price) dari Harga Beli + Margin
    function autoCalculateSellingPrice() {
        const hargaBeli = parseFloat(document.getElementById('purchase_price').value) || 0;
        const marginPercent = parseFloat(document.getElementById('profit_margin_percent').value) || 35;

        if (hargaBeli <= 0) {
            alert('Masukkan Harga Beli terlebih dahulu');
            document.getElementById('purchase_price').focus();
            return;
        }

        // Formula: Harga Jual = Harga Beli ÷ (1 - Margin%)
        const margin = marginPercent / 100;
        const hargaJual = Math.round(hargaBeli / (1 - margin));

        document.getElementById('harga').value = hargaJual;
        
        // Show confirmation
        const margin_input = marginPercent.toFixed(0);
        const keuntungan = hargaJual - hargaBeli;
        alert(`✅ Harga Jual dihitung otomatis:\n\nHarga Beli: Rp ${hargaBeli.toLocaleString('id-ID')}\nMargin: ${margin_input}%\nKeuntungan: Rp ${keuntungan.toLocaleString('id-ID')}\n\nHarga Jual: Rp ${hargaJual.toLocaleString('id-ID')}`);
    }

    // Validasi margin ketika berubah
    document.addEventListener('DOMContentLoaded', function() {
        const profitMarginInput = document.getElementById('profit_margin_percent');
        if (profitMarginInput) {
            profitMarginInput.addEventListener('change', function() {
                const margin = parseFloat(this.value) || 35;
                if (margin < 20 || margin > 50) {
                    console.warn('⚠️ Warning: Margin normal untuk buku adalah 20-50%');
                }
            });
        }
    });

    // Preview Image Function
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('imagePreview');
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            
            reader.readAsDataURL(this.files[0]);
        } else {
            previewContainer.classList.add('hidden');
        }
    });

    // SIMPAN REFERRER SAAT PAGE LOAD
    document.addEventListener('DOMContentLoaded', function() {
        // Simpan URL sebelumnya ke sessionStorage
        if (document.referrer && !document.referrer.includes('/admin/books/edit')) {
            sessionStorage.setItem('previousUrl', document.referrer);
        }
    });

    // FUNGSI TOMBOL BATAL
    function handleBatal() {
        const previousUrl = sessionStorage.getItem('previousUrl');
        const currentUrl = window.location.href;
        
        // Prioritaskan URL yang disimpan di sessionStorage
        if (previousUrl && previousUrl !== currentUrl && !previousUrl.includes('/admin/books/edit')) {
            window.location.href = previousUrl;
        } 
        // Jika tidak ada URL yang disimpan, coba history back
        else if (window.history.length > 2) {
            window.history.go(-1);
        }
        // Fallback ke halaman kelola buku
        else {
            window.location.href = "{{ route('admin.books.index') }}";
        }
    }

    // Category Modal Functions
    function openCategoryModalBooksEdit() {
        const modal = document.getElementById('categoryModalBooksEdit');
        if (!modal) {
            // Create modal if it doesn't exist
            const modalHtml = `
                <div id="categoryModalBooksEdit" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-lg max-w-md w-full">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 flex justify-between items-center">
                            <h2 class="text-xl font-bold">Tambah Kategori Baru</h2>
                            <button type="button" onclick="closeCategoryModalBooksEdit()" class="text-white hover:text-gray-200">
                                <i class="fas fa-times text-2xl"></i>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
                                <input type="text" id="newCategoryInputBooksEdit" placeholder="Masukkan nama kategori baru"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-2">Kategori akan ditambahkan ke daftar dan dipilih otomatis</p>
                            </div>
                            <div class="flex gap-2 justify-end pt-4 border-t">
                                <button type="button" onclick="closeCategoryModalBooksEdit()" class="px-4 py-2 bg-gray-300 text-gray-900 rounded-lg hover:bg-gray-400">
                                    Batal
                                </button>
                                <button type="button" onclick="addNewCategoryBooksEdit()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-plus mr-1"></i>Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }
        document.getElementById('categoryModalBooksEdit').classList.remove('hidden');
        document.getElementById('newCategoryInputBooksEdit').focus();
    }

    function closeCategoryModalBooksEdit() {
        const modal = document.getElementById('categoryModalBooksEdit');
        if (modal) {
            modal.classList.add('hidden');
            document.getElementById('newCategoryInputBooksEdit').value = '';
        }
    }

    function addNewCategoryBooksEdit() {
        const input = document.getElementById('newCategoryInputBooksEdit');
        const categoryName = input.value.trim();

        if (!categoryName) {
            alert('Masukkan nama kategori terlebih dahulu');
            return;
        }

        const select = document.getElementById('kategori');
        
        // Check if category already exists
        let exists = false;
        for (let option of select.options) {
            if (option.value === categoryName) {
                exists = true;
                break;
            }
        }

        if (exists) {
            alert('Kategori "' + categoryName + '" sudah ada dalam daftar');
            return;
        }

        // Create new option and add to select
        const option = document.createElement('option');
        option.value = categoryName;
        option.textContent = categoryName;
        option.selected = true;
        select.appendChild(option);

        closeCategoryModalBooksEdit();
        alert('Kategori "' + categoryName + '" berhasil ditambahkan!');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('newCategoryInputBooksEdit');
        if (input) {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    addNewCategoryBooksEdit();
                }
            });
        }
    });
</script>
@endsection