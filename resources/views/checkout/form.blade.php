@extends('layouts.guest')

@section('content')
    <style>
        /* Checkout container dengan grid layout */
        .checkout-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            align-items: start;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            min-height: calc(100vh - 300px);
        }

        .checkout-form-wrapper {
            display: flex;
            flex-direction: column;
        }

        .checkout-form-card {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 300px);
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .checkout-form-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 2rem;
        }

        .checkout-form-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .checkout-form-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .checkout-form-scroll::-webkit-scrollbar-thumb {
            background: #16a34a;
            border-radius: 10px;
        }

        .checkout-form-scroll::-webkit-scrollbar-thumb:hover {
            background: #15803d;
        }

        .checkout-summary {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 300px);
            position: sticky;
            top: 100px;
        }

        .checkout-summary-content {
            height: 100%;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .checkout-summary-content::-webkit-scrollbar {
            width: 6px;
        }

        .checkout-summary-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .checkout-summary-content::-webkit-scrollbar-thumb {
            background: #16a34a;
            border-radius: 10px;
        }

        @media (max-width: 1024px) {
            .checkout-container {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .checkout-form-card {
                height: auto;
                max-height: 600px;
            }

            .checkout-summary {
                height: auto;
                position: relative;
                top: 0;
            }

            .checkout-summary-content {
                height: auto;
                overflow-y: visible;
            }
        }

        /* Fade out animation untuk notifikasi */
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        .alert-error {
            animation: fadeOut 0.5s ease-in-out 2.5s forwards;
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Checkout</h1>
            <p class="text-gray-600">Lengkapi data pengiriman dan pilih metode pembayaran</p>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-700 bg-red-100 p-4 rounded-lg alert-error">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Content -->
        <div class="checkout-container">
            <!-- Checkout Form -->
            <div class="checkout-form-wrapper">
                <div class="checkout-form-card">
                    <div class="checkout-form-scroll">
                        <form action="{{ route('cart.checkout') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
                    @csrf

                    <!-- Section 1: Informasi Pengiriman -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-4 border-b-2 border-green-600">
                            <i class="fas fa-map-marker-alt mr-2 text-green-600"></i>Informasi Pengiriman
                        </h2>

                        <!-- Nama -->
                        <div class="mb-5">
                            <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">Nama Penerima <span class="text-red-600">*</span></label>
                            <input type="text" 
                                   id="nama" 
                                   name="nama" 
                                   value="{{ old('nama', $user->name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                   placeholder="Masukkan nama lengkap penerima"
                                   required>
                        </div>

                        <!-- Email (readonly) -->
                        <div class="mb-5">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" 
                                   id="email" 
                                   value="{{ $user->email }}"
                                   readonly
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="mb-5">
                            <label for="telepon" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone mr-1 text-green-600"></i>Nomor Telepon <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   id="telepon" 
                                   name="telepon" 
                                   value="{{ old('telepon', $user->telepon ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                   placeholder="Contoh: 08123456789"
                                   required>
                            @error('telepon')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <!-- Provinsi -->
                            <div>
                                <label for="provinsi" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-map-marked-alt mr-1 text-green-600"></i>Provinsi <span class="text-red-600">*</span>
                                </label>
                                <select id="provinsi" 
                                        name="provinsi" 
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600 transition"
                                        onchange="calculateShipping()"
                                        required>
                                    <option value="">Pilih Provinsi</option>
                                    @php
                                        $provinces = \App\Services\ShippingCalculator::getProvinces();
                                        $uniqueProvinces = array_unique(array_column($provinces, 'name'));
                                        sort($uniqueProvinces);
                                    @endphp
                                    @foreach($uniqueProvinces as $prov)
                                        <option value="{{ $prov }}" {{ old('provinsi') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                    @endforeach
                                </select>
                                @error('provinsi')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Kota/Kabupaten -->
                            <div>
                                <label for="kota" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-city mr-1 text-green-600"></i>Kota/Kabupaten <span class="text-red-600">*</span>
                                </label>
                                <input type="text" 
                                       id="kota" 
                                       name="kota" 
                                       value="{{ old('kota') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                       placeholder="Contoh: Jakarta Selatan"
                                       required>
                                @error('kota')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <!-- Kecamatan -->
                            <div>
                                <label for="kecamatan" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kecamatan <span class="text-red-600">*</span>
                                </label>
                                <input type="text" 
                                       id="kecamatan" 
                                       name="kecamatan" 
                                       value="{{ old('kecamatan') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                       placeholder="Contoh: Kebayoran Baru"
                                       required>
                                @error('kecamatan')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Kode Pos -->
                            <div>
                                <label for="kode_pos" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-mailbox mr-1 text-green-600"></i>Kode Pos <span class="text-red-600">*</span>
                                </label>
                                <input type="text" 
                                       id="kode_pos" 
                                       name="kode_pos" 
                                       value="{{ old('kode_pos') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                       placeholder="Contoh: 12345"
                                       pattern="[0-9]{5}"
                                       maxlength="5"
                                       required>
                                @error('kode_pos')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Alamat Lengkap (Jalan dan Nomor Rumah) -->
                        <div class="mb-5">
                            <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-home mr-1 text-green-600"></i>Alamat Lengkap (Jalan, No. Rumah, RT/RW) <span class="text-red-600">*</span>
                            </label>
                            <textarea id="alamat" 
                                      name="alamat" 
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                      placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 05, Kelurahan Senayan"
                                      required>{{ old('alamat', $user->alamat ?? '') }}</textarea>
                            @error('alamat')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Section 2: Metode Pengiriman -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-4 border-b-2 border-green-600">
                            <i class="fas fa-truck mr-2 text-green-600"></i>Metode Pengiriman
                        </h2>
                        
                        <div class="mb-5">
                            <label for="shipping_method" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-shipping-fast mr-1 text-green-600"></i>Pilih Kurir <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <select id="shipping_method" 
                                        name="shipping_method" 
                                        class="w-full px-4 py-3 pr-10 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600 transition appearance-none cursor-pointer text-gray-800 font-medium bg-white hover:border-green-400"
                                        onchange="calculateShipping()"
                                        required>
                                    <option value="jne">🚚 JNE Regular - Estimasi 2-5 hari kerja</option>
                                    <option value="jnt">⚡ J&T Regular - Estimasi 2-4 hari kerja</option>
                                    <option value="ninja">🏃 Ninja Express - Estimasi 1-3 hari kerja (Premium)</option>
                                    <option value="antera">💰 AnterAja - Estimasi 3-6 hari kerja (Ekonomis)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-green-600"></i>
                                </div>
                            </div>
                            
                            <!-- Info Card Estimasi Ongkir -->
                            <div class="mt-3 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-box text-green-600 mt-1 text-xl"></i>
                                    <div class="flex-1">
                                        <p class="font-semibold text-green-800 mb-2">Rincian Pengiriman</p>
                                        <div class="grid grid-cols-2 gap-2 text-sm text-green-700">
                                            <div>
                                                <span class="font-medium">Berat Total:</span>
                                                <span id="totalWeightDisplay" class="ml-1">{{ $totalWeight }}g</span>
                                            </div>
                                            <div>
                                                <span class="font-medium">Zona:</span>
                                                <span id="shippingZone" class="ml-1">Pilih provinsi</span>
                                            </div>
                                            <div class="col-span-2 mt-1 pt-2 border-t border-green-300">
                                                <span class="font-semibold">Biaya Ongkir:</span>
                                                <span id="shippingEstDisplay" class="ml-2 text-lg font-bold text-green-900">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Ongkir Otomatis:</strong> Dihitung berdasarkan berat total buku ({{ $totalWeight }}g) dan zona pengiriman. Pilih provinsi untuk melihat estimasi biaya.
                            </p>
                        </div>
                    </div>

                    <!-- Section 3: Metode Pembayaran -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-4 border-b-2 border-green-600">
                            <i class="fas fa-credit-card mr-2 text-green-600"></i>Metode Pembayaran
                        </h2>

                        <div class="mb-5">
                            <label for="payment_method" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-wallet mr-1 text-green-600"></i>Pilih Metode <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <select id="payment_method" 
                                        name="payment_method" 
                                        class="w-full px-4 py-3 pr-10 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600 transition appearance-none cursor-pointer text-gray-800 font-medium bg-white hover:border-green-400"
                                        onchange="updateBankInfo()"
                                        required>
                                    <option value="bca">🏦 BCA - 1234567890 (PT Ruang Aksara)</option>
                                    <option value="mandiri">🏦 Mandiri - 9876543210 (PT Ruang Aksara)</option>
                                    <option value="bni">🏦 BNI - 1122334455 (PT Ruang Aksara)</option>
                                    <option value="cash">💵 Bayar di Tempat (COD) - Bayar saat terima barang</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-green-600"></i>
                                </div>
                            </div>
                        </div>

                        @error('payment_method')
                            <span class="text-red-600 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Section 4: Bukti Pembayaran -->
                    <div class="mb-8" id="proofSection" style="display: none;">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-4 border-b-2 border-green-600">
                            <i class="fas fa-receipt mr-2 text-green-600"></i>Bukti Pembayaran
                        </h2>

                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200 mb-4">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-warning mr-2"></i>
                                Setelah melakukan transfer, silakan upload bukti screenshot transfer Anda. Pesanan akan diproses setelah bukti kami verifikasi.
                            </p>
                        </div>

                        <div class="mb-5">
                            <label for="proof_of_payment" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-image mr-1 text-green-600"></i>Upload Bukti Transfer (Screenshot) <span class="text-red-600">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-green-600 transition" id="uploadArea">
                                <input type="file" 
                                       id="proof_of_payment" 
                                       name="proof_of_payment" 
                                       accept="image/*"
                                       class="hidden"
                                       required>
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-700 font-semibold mb-1">Klik untuk upload atau drag & drop</p>
                                    <p class="text-sm text-gray-500">JPG, PNG atau format gambar lainnya (Max 5MB)</p>
                                </div>
                            </div>
                            <div id="filePreview" class="mt-3" style="display: none;">
                                <p class="text-sm text-gray-600 mb-2">File terpilih:</p>
                                <div class="flex items-center gap-2 p-3 bg-green-50 border border-green-200 rounded">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span id="fileName" class="text-sm text-gray-800 font-semibold"></span>
                                </div>
                            </div>
                            @error('proof_of_payment')
                                <span class="text-red-600 text-sm mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-8">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Setelah upload bukti, pesanan akan masuk dalam status "Menunggu Verifikasi". Tim kami akan memverifikasi dalam 1x24 jam.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-4">
                        <a href="{{ route('cart.index') }}" class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-800 rounded-lg font-semibold hover:bg-gray-50 transition text-center">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Keranjang
                        </a>
                        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg font-semibold hover:from-green-700 hover:to-green-800 transition">
                            <i class="fas fa-check-circle mr-2"></i>Konfirmasi Checkout
                        </button>
                    </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pesanan -->
            <div class="checkout-summary">
                <div class="checkout-summary-content">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Ringkasan Pesanan</h3>

                    <!-- Items -->
                    <div class="space-y-4 mb-6 pb-6 border-b border-gray-200">
                        @foreach ($books as $book)
                            @if (isset($cart[$book->id]))
                                @php
                                    // Try multiple possible storage locations for book cover
                                    $coverUrl = null;
                                    if (!empty($book->image)) {
                                        $path1 = public_path('storage/book-covers/' . $book->image);
                                        $path2 = public_path('storage/' . $book->image);
                                        if (file_exists($path1)) {
                                            $coverUrl = asset('storage/book-covers/' . $book->image);
                                        } elseif (file_exists($path2)) {
                                            $coverUrl = asset('storage/' . $book->image);
                                        } elseif (filter_var($book->image, FILTER_VALIDATE_URL)) {
                                            $coverUrl = $book->image;
                                        }
                                    }
                                @endphp

                                <div class="flex gap-3">
                                    <!-- Book Image -->
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded flex items-center justify-center overflow-hidden">
                                            @if($coverUrl)
                                                <img src="{{ $coverUrl }}" alt="{{ $book->judul }}" class="w-full h-full object-cover">
                                            @else
                                                <img src="{{ asset('images/default-book-cover.svg') }}" alt="{{ $book->judul }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Book Info -->
                                    <div class="flex-grow">
                                        <div class="flex justify-between text-sm">
                                            <div>
                                                <div class="font-semibold text-gray-800">{{ $book->judul }}</div>
                                                <div class="text-gray-600">x{{ $cart[$book->id] }}</div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-semibold text-gray-800">
                                                    Rp {{ number_format($book->harga * $cart[$book->id], 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Summary -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Item:</span>
                            <span class="font-semibold text-gray-800">{{ count($cart) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Kuantitas:</span>
                            <span class="font-semibold text-gray-800">{{ array_sum($cart) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ongkir:</span>
                            <span class="font-semibold text-gray-800" id="shippingCost">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Pengiriman:</span>
                            <span class="font-semibold text-gray-800" id="shippingMethodName">JNE Regular</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">ETA:</span>
                            <span class="font-semibold text-gray-800" id="shippingEta">2-5 hari</span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-300 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-800">Total Harga:</span>
                            <span class="text-lg font-bold text-green-600" id="totalPrice">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Bank Info Box -->
                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg hidden" id="bankInfoBox">
                        <div class="flex items-center gap-3">
                            <div id="bankLogo" class="w-12 h-10 flex items-center justify-center bg-white rounded border flex-shrink-0">
                                <!-- logo will be injected here -->
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-green-800 mb-1">
                                    <i class="fas fa-bank mr-1"></i>Rekening:
                                </p>
                                <p class="text-sm font-bold text-green-700 font-mono" id="bankNumber"></p>
                                <p class="text-xs text-green-700" id="bankName"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide error alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-error');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 3000);
            });
        });

        // Update bank info ketika metode pembayaran berubah
        function updateBankInfo() {
            const selected = document.getElementById('payment_method');
            const bankInfo = document.getElementById('bankInfoBox');
            const proofSection = document.getElementById('proofSection');
            
            if (!selected || !selected.value) {
                bankInfo.classList.add('hidden');
                proofSection.style.display = 'none';
                return;
            }

            const bankData = {
                'bca': { 
                    number: '1234567890', 
                    name: 'Bank Central Asia (BCA)\nA.n: PT Ruang Aksara',
                    logo: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 40" preserveAspectRatio="xMidYMid meet"><rect width="120" height="40" fill="#0A2A77" rx="4"/><text x="12" y="26" font-family="Arial, Helvetica, sans-serif" font-size="14" fill="#fff">BCA</text></svg>`
                },
                'mandiri': { 
                    number: '9876543210', 
                    name: 'Bank Mandiri\nA.n: PT Ruang Aksara',
                    logo: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 40" preserveAspectRatio="xMidYMid meet"><rect width="120" height="40" fill="#003b71" rx="4"/><text x="8" y="26" font-family="Arial, Helvetica, sans-serif" font-size="12" fill="#fff">MANDIRI</text></svg>`
                },
                'bni': { 
                    number: '1122334455', 
                    name: 'Bank Nasional Indonesia (BNI)\nA.n: PT Ruang Aksara',
                    logo: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 40" preserveAspectRatio="xMidYMid meet"><rect width="120" height="40" fill="#6b1e85" rx="4"/><text x="12" y="26" font-family="Arial, Helvetica, sans-serif" font-size="14" fill="#fff">BNI</text></svg>`
                },
                'cash': { 
                    number: 'Pembayaran Tunai (COD)', 
                    name: 'Bayar saat barang sampai',
                    logo: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 40" preserveAspectRatio="xMidYMid meet"><rect width="120" height="40" fill="#10b981" rx="4"/><text x="12" y="26" font-family="Arial, Helvetica, sans-serif" font-size="12" fill="#fff">CASH</text></svg>`
                }
            };

            const data = bankData[selected.value];
            if (data) {
                document.getElementById('bankNumber').textContent = data.number;
                document.getElementById('bankName').innerHTML = data.name.replace(/\n/g, '<br>');
                const logoContainer = document.getElementById('bankLogo');
                logoContainer.innerHTML = data.logo || '';
            }
            
            // Show proof section hanya untuk transfer (bukan cash)
            if (selected.value !== 'cash') {
                proofSection.style.display = 'block';
                document.getElementById('proof_of_payment').required = true;
            } else {
                proofSection.style.display = 'none';
                document.getElementById('proof_of_payment').required = false;
            }
            
            if (data) {
                bankInfo.classList.remove('hidden');
            }
        }

        // Handle file upload area
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('proof_of_payment');
            const filePreview = document.getElementById('filePreview');
            const fileName = document.getElementById('fileName');

            if (uploadArea && fileInput) {
                // Click to upload
                uploadArea.addEventListener('click', () => fileInput.click());

                // Drag and drop
                uploadArea.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    uploadArea.classList.add('border-green-600', 'bg-green-50');
                });

                uploadArea.addEventListener('dragleave', () => {
                    uploadArea.classList.remove('border-green-600', 'bg-green-50');
                });

                uploadArea.addEventListener('drop', (e) => {
                    e.preventDefault();
                    uploadArea.classList.remove('border-green-600', 'bg-green-50');
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput.files = files;
                        updateFilePreview();
                    }
                });

                // File input change
                fileInput.addEventListener('change', updateFilePreview);

                function updateFilePreview() {
                    if (fileInput.files.length > 0) {
                        const file = fileInput.files[0];
                        fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
                        filePreview.style.display = 'block';
                    } else {
                        filePreview.style.display = 'none';
                    }
                }
            }

            // Init - trigger untuk load default values
            updateBankInfo();
            updateShippingInfo();
        });

        // Shipping estimation logic
        function formatCurrency(v) {
            return 'Rp ' + (Number(v) || 0).toLocaleString('id-ID');
        }

        // Calculate shipping based on weight, province, and courier
        function calculateShipping() {
            const provinceSelect = document.getElementById('provinsi');
            const courierSelect = document.getElementById('shipping_method');
            const shippingCostEl = document.getElementById('shippingCost');
            const shippingMethodNameEl = document.getElementById('shippingMethodName');
            const shippingEstDisplay = document.getElementById('shippingEstDisplay');
            const shippingZone = document.getElementById('shippingZone');
            const totalPriceEl = document.getElementById('totalPrice');
            const shippingEtaEl = document.getElementById('shippingEta');

            if (!provinceSelect || !courierSelect) return;

            const province = provinceSelect.value;
            const courier = courierSelect.value;
            const totalWeight = {{ $totalWeight }}; // Total weight in grams from server

            if (!province) {
                // No province selected yet
                if (shippingEstDisplay) shippingEstDisplay.textContent = 'Rp 0';
                if (shippingZone) shippingZone.textContent = 'Pilih provinsi';
                if (shippingCostEl) shippingCostEl.textContent = 'Rp 0';
                return;
            }

            // Zone detection and rate calculation
            const zones = {
                'zona_1': {
                    name: 'Jakarta & Sekitarnya',
                    provinces: ['DKI Jakarta', 'Banten', 'Jawa Barat'],
                    rates: {
                        'jne': {base: 10000, per_kg: 3000},
                        'jnt': {base: 9000, per_kg: 2500},
                        'ninja': {base: 12000, per_kg: 4000},
                        'antera': {base: 8000, per_kg: 2000}
                    }
                },
                'zona_2': {
                    name: 'Jawa Tengah & Timur',
                    provinces: ['Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur'],
                    rates: {
                        'jne': {base: 15000, per_kg: 4000},
                        'jnt': {base: 14000, per_kg: 3500},
                        'ninja': {base: 18000, per_kg: 5000},
                        'antera': {base: 12000, per_kg: 3000}
                    }
                },
                'zona_3': {
                    name: 'Sumatera',
                    provinces: ['Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Kepulauan Riau', 'Jambi', 'Sumatera Selatan', 'Bangka Belitung', 'Bengkulu', 'Lampung'],
                    rates: {
                        'jne': {base: 25000, per_kg: 6000},
                        'jnt': {base: 23000, per_kg: 5500},
                        'ninja': {base: 30000, per_kg: 7000},
                        'antera': {base: 20000, per_kg: 5000}
                    }
                },
                'zona_4': {
                    name: 'Kalimantan',
                    provinces: ['Kalimantan Barat', 'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara'],
                    rates: {
                        'jne': {base: 30000, per_kg: 7000},
                        'jnt': {base: 28000, per_kg: 6500},
                        'ninja': {base: 35000, per_kg: 8000},
                        'antera': {base: 25000, per_kg: 6000}
                    }
                },
                'zona_5': {
                    name: 'Sulawesi',
                    provinces: ['Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan', 'Sulawesi Tenggara', 'Sulawesi Barat', 'Gorontalo'],
                    rates: {
                        'jne': {base: 35000, per_kg: 8000},
                        'jnt': {base: 32000, per_kg: 7500},
                        'ninja': {base: 40000, per_kg: 9000},
                        'antera': {base: 28000, per_kg: 7000}
                    }
                },
                'zona_6': {
                    name: 'Bali, NTB, NTT',
                    provinces: ['Bali', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur'],
                    rates: {
                        'jne': {base: 30000, per_kg: 7000},
                        'jnt': {base: 28000, per_kg: 6500},
                        'ninja': {base: 35000, per_kg: 8000},
                        'antera': {base: 25000, per_kg: 6000}
                    }
                },
                'zona_7': {
                    name: 'Maluku & Papua',
                    provinces: ['Maluku', 'Maluku Utara', 'Papua', 'Papua Barat', 'Papua Tengah', 'Papua Pegunungan', 'Papua Selatan', 'Papua Barat Daya'],
                    rates: {
                        'jne': {base: 50000, per_kg: 12000},
                        'jnt': {base: 45000, per_kg: 11000},
                        'ninja': {base: 60000, per_kg: 14000},
                        'antera': {base: 40000, per_kg: 10000}
                    }
                }
            };

            // Find zone for selected province
            let selectedZone = null;
            let zoneName = 'Zona Tidak Diketahui';
            
            for (const [zoneKey, zoneData] of Object.entries(zones)) {
                for (const prov of zoneData.provinces) {
                    if (province.includes(prov) || prov.includes(province)) {
                        selectedZone = zoneData.rates[courier];
                        zoneName = zoneData.name;
                        break;
                    }
                }
                if (selectedZone) break;
            }

            // Default if zone not found
            if (!selectedZone) {
                selectedZone = {base: 15000, per_kg: 5000};
            }

            // Calculate cost
            const weightKg = Math.ceil(totalWeight / 1000);
            const cost = selectedZone.base + (selectedZone.per_kg * Math.max(0, weightKg - 1));

            // Update displays
            if (shippingEstDisplay) shippingEstDisplay.textContent = formatCurrency(cost);
            if (shippingZone) shippingZone.textContent = zoneName;
            if (shippingCostEl) shippingCostEl.textContent = formatCurrency(cost);

            // Update courier name
            const courierNames = {
                'jne': 'JNE Regular',
                'jnt': 'J&T Regular',
                'ninja': 'Ninja Express',
                'antera': 'AnterAja'
            };
            if (shippingMethodNameEl) shippingMethodNameEl.textContent = courierNames[courier] || courier;

            // Update ETA
            const etaMap = {
                'jne': '2-5 hari',
                'jnt': '2-4 hari',
                'ninja': '1-3 hari',
                'antera': '3-6 hari'
            };
            if (shippingEtaEl) shippingEtaEl.textContent = etaMap[courier] || '3-7 hari';

            // Update total price
            if (totalPriceEl) {
                const subtotal = {{ $total }};
                totalPriceEl.textContent = formatCurrency(subtotal + cost);
            }
        }

        function updateShippingInfo() {
            calculateShipping();
        }
    </script>
@endsection
