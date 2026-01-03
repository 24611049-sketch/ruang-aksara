@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tambah Buku untuk Peminjaman</h1>
                <p class="text-gray-600 mt-2">Buat buku baru yang dapat dipinjamkan</p>
            </div>
            <a href="{{ route('admin.loan-stock.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form action="{{ route('admin.loan-stock.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Display Errors -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-red-800 mb-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>Terjadi Kesalahan
                    </h3>
                    <ul class="list-disc list-inside text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Row 1: Judul & Penulis -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-book mr-2 text-blue-600"></i>Judul Buku <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" value="{{ old('judul') }}" required
                           placeholder="Masukkan judul buku"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-blue-600"></i>Penulis <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="penulis" value="{{ old('penulis') }}" required
                           placeholder="Masukkan nama penulis"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <!-- Row 2: Kategori & Penerbit -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag mr-2 text-blue-600"></i>Kategori <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <select name="kategori" id="kategoriSelect" required
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('kategori') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" onclick="openCategoryModal()" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold whitespace-nowrap">
                            <i class="fas fa-plus mr-1"></i>Tambah Baru
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fas fa-lightbulb mr-1"></i>Klik "Tambah Baru" untuk membuat kategori baru yang tidak ada di daftar
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-building mr-2 text-blue-600"></i>Penerbit
                    </label>
                    <input type="text" name="penerbit" value="{{ old('penerbit') }}"
                           placeholder="Nama penerbit (opsional)"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <!-- Row 3: ISBN & Halaman -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-barcode mr-2 text-blue-600"></i>ISBN
                    </label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}"
                           placeholder="ISBN (opsional)"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-file-alt mr-2 text-blue-600"></i>Jumlah Halaman
                    </label>
                    <input type="number" name="halaman" value="{{ old('halaman') }}" min="1"
                           placeholder="Jumlah halaman (opsional)"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>



            <!-- Row 5: Stok Peminjaman (IMPORTANT) -->
            <div class="bg-green-50 border-2 border-green-300 rounded-lg p-6">
                <label class="block text-sm font-semibold text-green-800 mb-3">
                    <i class="fas fa-warehouse mr-2"></i>Stok Peminjaman <span class="text-red-500">*</span>
                </label>
                <input type="number" name="loan_stok" value="{{ old('loan_stok', 1) }}" required min="0" max="9999"
                       placeholder="Berapa banyak buku yang bisa dipinjam?"
                       class="w-full px-4 py-3 border-2 border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-lg font-semibold">
                <p class="text-sm text-green-700 mt-3">
                    <i class="fas fa-info-circle mr-2"></i>Jumlah buku yang tersedia untuk peminjaman. Buku ini akan langsung bisa dipinjam setelah disimpan.
                </p>
            </div>

            <!-- Row 6: Deskripsi -->
            <!-- Row 5b: Cover Image -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-image mr-2 text-blue-600"></i>Cover Buku (opsional)
                </label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <p class="text-xs text-gray-500 mt-2">Format: JPEG, PNG, JPG, GIF (Maks. 2MB)</p>
                <div id="imagePreview" class="mt-3 hidden">
                    <p class="text-xs text-gray-600 mb-1">Preview:</p>
                    <img id="preview" class="h-40 w-28 object-cover rounded border border-gray-300">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-2 text-blue-600"></i>Deskripsi
                </label>
                <textarea name="deskripsi" rows="5" placeholder="Masukkan deskripsi buku (opsional)"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('deskripsi') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Maks 1000 karakter</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-end pt-6 border-t">
                <a href="{{ route('admin.loan-stock.index') }}" class="px-6 py-3 bg-gray-300 text-gray-900 rounded-lg hover:bg-gray-400 transition font-semibold">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>Simpan Buku
                </button>
            </div>
        </form>
    </div>

    <!-- Category Modal -->
    <div id="categoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-md w-full">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold">Tambah Kategori Baru</h2>
                <button type="button" onclick="closeCategoryModal()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
                    <input type="text" id="newCategoryInput" placeholder="Masukkan nama kategori baru"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-2">Kategori akan ditambahkan ke daftar dan dipilih otomatis</p>
                </div>
                <div class="flex gap-2 justify-end pt-4 border-t">
                    <button type="button" onclick="closeCategoryModal()" class="px-4 py-2 bg-gray-300 text-gray-900 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="button" onclick="addNewCategory()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-1"></i>Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="font-semibold text-blue-900 mb-3">
            <i class="fas fa-lightbulb mr-2"></i>Tips Menambah Buku</h3>
        <ul class="text-blue-800 space-y-2 text-sm">
            <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Isi judul, penulis, kategori, dan stok peminjaman (wajib)</li>
            <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Stok peminjaman menentukan berapa banyak buku bisa dipinjam</li>
            <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Buku akan langsung tersedia untuk dipinjam setelah disimpan</li>
            <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Data lainnya bersifat opsional untuk fleksibilitas</li>
        </ul>
    </div>
</div>

<script>
    function openCategoryModal() {
        document.getElementById('categoryModal').classList.remove('hidden');
        document.getElementById('newCategoryInput').focus();
    }

    function closeCategoryModal() {
        document.getElementById('categoryModal').classList.add('hidden');
        document.getElementById('newCategoryInput').value = '';
    }

    function addNewCategory() {
        const input = document.getElementById('newCategoryInput');
        const categoryName = input.value.trim();

        if (!categoryName) {
            alert('Masukkan nama kategori terlebih dahulu');
            return;
        }

        // Add to select dropdown
        const select = document.getElementById('kategoriSelect');
        
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

        // Close modal
        closeCategoryModal();
        
        // Show success message
        alert('Kategori "' + categoryName + '" berhasil ditambahkan!');
    }

    // Allow Enter key to add category
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('newCategoryInput');
        if (input) {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    addNewCategory();
                }
            });
        }
        const imgInput = document.getElementById('image');
        if (imgInput) {
            imgInput.addEventListener('change', function() {
                const preview = document.getElementById('preview');
                const container = document.getElementById('imagePreview');
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        container.classList.remove('hidden');
                    }
                    reader.readAsDataURL(this.files[0]);
                } else {
                    container.classList.add('hidden');
                }
            });
        }
    });
</script>
@endsection
