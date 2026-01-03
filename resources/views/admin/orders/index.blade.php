@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - Admin Ruang Aksara')

@section('content')
<div class="w-full py-6" style="margin: 0 !important; padding: 0 1rem 0 0 !important;">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-receipt mr-2"></i>Verifikasi Pembayaran
            </h1>
        </div>
        
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center justify-between">
                <span><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Filter Tabs with modern pill design -->
        <div class="bg-white rounded-lg shadow-sm p-2 mb-6 inline-flex gap-2">
            <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" 
               class="px-6 py-2.5 rounded-lg font-medium transition-all {{ request('status', 'pending') == 'pending' ? 'bg-yellow-500 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-hourglass-half mr-2"></i>Menunggu Verifikasi
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'verified']) }}" 
               class="px-6 py-2.5 rounded-lg font-medium transition-all {{ request('status') == 'verified' ? 'bg-green-500 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-check-circle mr-2"></i>Terverifikasi
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'failed']) }}" 
               class="px-6 py-2.5 rounded-lg font-medium transition-all {{ request('status') == 'failed' ? 'bg-red-500 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-times-circle mr-2"></i>Ditolak
            </a>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">ID Order</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Pembeli</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Buku</th>
                        <th class="text-right px-6 py-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Total</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Metode</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Status Bayar</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Status Pesanan</th>
                        <th class="text-center px-6 py-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                        @php
                            // For new structure: order has items collection
                            $items = $order->items ?? collect();
                            $totalItems = $items->sum('quantity');
                            $totalPrice = $items->sum('subtotal');
                            $totalShipping = $order->shipping_cost ?? 0;
                            $grandTotal = $totalPrice + $totalShipping;
                            $limitBooks = $items->take(2);
                            $hiddenBooks = $items->skip(2);
                            $pm = $order->payment_method ?? 'cash';
                        @endphp

                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 align-top">
                                <div class="flex flex-col gap-1">
                                    <span class="font-bold text-blue-700 text-sm">{{ $order->order_group_id ?: '#'.$order->id }}</span>
                                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full inline-flex items-center gap-1">
                                        <i class="fas fa-book"></i>{{ $items->count() }} buku
                                    </span>
                                    <span class="text-xs text-gray-600">{{ optional($order->created_at)->format('d M Y, H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="font-medium text-gray-900">{{ optional($order->user)->name ?? '—' }}</div>
                                <div class="text-xs text-gray-500">{{ optional($order->user)->email ?? '—' }}</div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="flex flex-col gap-2">
                                    @foreach($limitBooks as $item)
                                        <div class="flex items-center gap-2">
                                            @if($item->book && ($item->book->image ?? false))
                                                <img src="{{ asset('storage/book-covers/' . $item->book->image) }}" alt="{{ $item->book->judul ?? 'Buku' }}" class="w-8 h-10 object-cover rounded">
                                            @else
                                                <div class="w-8 h-10 bg-gray-200 rounded flex items-center justify-center">
                                                    <i class="fas fa-book text-xs text-gray-600"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-semibold leading-tight text-sm">{{ $item->book->judul ?? 'Buku tidak tersedia' }}</p>
                                                <p class="text-[11px] text-gray-600">Qty: {{ $item->quantity }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($items->count() > 2)
                                        <details class="text-[11px]">
                                            <summary class="text-blue-600 hover:underline cursor-pointer">Lihat {{ $items->count() - 2 }} buku lain</summary>
                                            <div class="mt-2 space-y-2">
                                                @foreach($hiddenBooks as $item)
                                                    <div class="flex items-center gap-2">
                                                        @if($item->book && ($item->book->image ?? false))
                                                            <img src="{{ asset('storage/book-covers/' . $item->book->image) }}" alt="{{ $item->book->judul ?? 'Buku' }}" class="w-7 h-9 object-cover rounded">
                                                        @else
                                                            <div class="w-7 h-9 bg-gray-200 rounded flex items-center justify-center">
                                                                <i class="fas fa-book text-[10px] text-gray-600"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="font-medium leading-tight text-xs">{{ $item->book->judul ?? 'Buku tidak tersedia' }}</p>
                                                            <p class="text-[10px] text-gray-600">Qty: {{ $item->quantity }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right align-top">
                                <div class="font-bold text-green-600 text-sm">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
                                @if($totalShipping > 0)
                                    <div class="text-[11px] text-gray-500">Subtotal: Rp {{ number_format($totalPrice, 0, ',', '.') }}</div>
                                    <div class="text-[11px] text-gray-500">Ongkir: Rp {{ number_format($totalShipping, 0, ',', '.') }}</div>
                                @endif
                                <div class="text-[11px] text-gray-500">{{ $totalItems }} item</div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                @if($pm == 'bca')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        <i class="fas fa-university mr-1"></i>BCA
                                    </span>
                                @elseif($pm == 'mandiri')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium">
                                        <i class="fas fa-university mr-1"></i>Mandiri
                                    </span>
                                @elseif($pm == 'bni')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                        <i class="fas fa-university mr-1"></i>BNI
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        <i class="fas fa-money-bill-wave mr-1"></i>COD
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top">
                                @if(($order->payment_status ?? 'pending') == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                                        <i class="fas fa-hourglass-half mr-1"></i>Menunggu
                                    </span>
                                @elseif(($order->payment_status ?? '') == 'verified')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i>Terverifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                        <i class="fas fa-times-circle mr-1"></i>Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top">
                                @if(in_array(Auth::user()->role, ['admin','owner']))
                                    <select id="status-select-{{ $order->id }}" 
                                            class="w-full text-xs px-2.5 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            onchange="updateShippingStatus({{ $order->id }})">
                                        <option value="pending" {{ $order->status=='pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                                        <option value="processing" {{ $order->status=='processing' ? 'selected' : '' }}>⚙️ Diproses</option>
                                        <option value="shipped" {{ $order->status=='shipped' ? 'selected' : '' }}>🚚 Dikirim</option>
                                        <option value="received" {{ $order->status=='received' ? 'selected' : '' }}>📦 Diterima</option>
                                        <option value="delivered" {{ $order->status=='delivered' ? 'selected' : '' }}>✅ Selesai</option>
                                        <option value="cancelled" {{ $order->status=='cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                                    </select>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center justify-center gap-2">
                                    @if(($order->payment_method ?? 'cash') != 'cash' && ($order->payment_status ?? '') == 'pending')
                                        <button onclick="viewProof({{ $order->id }})" 
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                                title="Lihat Bukti">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="approveOrder({{ $order->id }})" 
                                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                                title="Verifikasi">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                        <button onclick="rejectOrder({{ $order->id }})" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                title="Tolak">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    @elseif(in_array($order->status, ['shipped', 'delivered', 'received']))
                                        <button onclick="editTrackingNumber({{ $order->id }})" 
                                                class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" 
                                                title="Edit Nomor Resi">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @elseif(($order->payment_status ?? '') == 'verified')
                                        <span class="text-xs text-gray-500 italic">
                                            <i class="fas fa-check mr-1"></i>Terverifikasi
                                        </span>
                                    @elseif(($order->payment_status ?? '') == 'failed')
                                        <span class="text-xs text-gray-500 italic">
                                            <i class="fas fa-ban mr-1"></i>Ditolak
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-500">
                                <i class="fas fa-inbox text-5xl mb-3 text-gray-300"></i>
                                <p class="font-medium">Tidak ada order</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">
                {{-- Pagination links --}}
                @if(method_exists($orders, 'links'))
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Proof Modal -->
    <div id="proofModal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full mx-4 transform transition-all">
            <div class="border-b border-gray-200 p-6 flex justify-between items-center bg-gradient-to-r from-blue-50 to-blue-100">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-receipt mr-2 text-blue-600"></i>Bukti Pembayaran
                </h2>
                <button onclick="closeProof()" class="text-2xl text-gray-500 hover:text-gray-800 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 max-h-[70vh] overflow-y-auto">
                <div id="proofContent"></div>
            </div>
            <div class="border-t border-gray-200 p-6 flex gap-3 justify-end bg-gray-50">
                <button onclick="closeProof()" class="px-5 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors font-medium text-gray-700">
                    <i class="fas fa-times mr-2"></i>Tutup
                </button>
                <form id="verifyForm" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" name="action" value="approve" class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium shadow-sm">
                        <i class="fas fa-check-circle mr-2"></i>Verifikasi
                    </button>
                </form>
                <form id="rejectForm" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" name="action" value="reject" class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium shadow-sm">
                        <i class="fas fa-times-circle mr-2"></i>Tolak
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tracking Number Modal -->
    <div id="trackingModal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
            <div class="border-b border-gray-200 p-6 flex justify-between items-center bg-gradient-to-r from-blue-50 to-blue-100">
                <h2 class="text-xl font-bold text-gray-800" id="trackingModalTitle">
                    <i class="fas fa-barcode mr-2 text-blue-600"></i>Input Nomor Resi
                </h2>
                <button onclick="closeTrackingModal()" class="text-2xl text-gray-500 hover:text-gray-800 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <!-- Order Info Section (for edit mode) -->
                <div id="orderInfoSection" class="hidden mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-600 mb-2"><strong>Order ID:</strong> <span id="infoOrderId" class="text-gray-900 font-mono"></span></p>
                    <p class="text-xs text-gray-600 mb-2"><strong>Pembeli:</strong> <span id="infoUserName" class="text-gray-900 font-medium"></span></p>
                    <p class="text-xs text-gray-600"><strong>Total:</strong> <span id="infoTotal" class="text-green-600 font-bold"></span></p>
                </div>

                <form id="trackingForm" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-6">
                        <label for="trackingInput" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-barcode mr-1 text-blue-600"></i>Nomor Resi Pengiriman <span class="text-red-600">*</span>
                        </label>
                        <input type="text" 
                               id="trackingInput" 
                               name="tracking_number"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="Contoh: 1234567890ABC"
                               required>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>Masukkan nomor resi dari kurir (JNE, J&T, Ninja, dll)
                        </p>
                    </div>

                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="closeTrackingModal()" class="px-5 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors font-medium text-gray-700">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                        <button type="button" onclick="submitTrackingForm()" id="submitBtn" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm">
                            <i class="fas fa-save mr-2"></i>Simpan & Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function viewProof(orderId) {
            const modal = document.getElementById('proofModal');
            const content = document.getElementById('proofContent');
            const verifyForm = document.getElementById('verifyForm');
            const rejectForm = document.getElementById('rejectForm');

            // Set form actions
            verifyForm.action = `/admin/orders/${orderId}/verify`;
            rejectForm.action = `/admin/orders/${orderId}/verify`;

            // Fetch and display proof image
            fetch(`/admin/orders/${orderId}/proof`)
                .then(r => r.json())
                .then(data => {
                    if (data.proof_url) {
                        content.innerHTML = `
                            <div class="bg-white rounded-lg">
                                <div class="mb-5">
                                    <p class="text-sm text-gray-600 mb-3 font-medium">Bukti Transfer:</p>
                                    <img src="${data.proof_url}" alt="Proof of Payment" class="w-full rounded-lg border-2 border-gray-200 shadow-sm">
                                </div>
                                <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="space-y-2">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium text-gray-700">Order ID:</span> 
                                            <span class="font-bold text-blue-600">#${data.order_id}</span>
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium text-gray-700">Pembeli:</span> 
                                            <span class="font-semibold text-gray-900">${data.user_name}</span>
                                        </p>
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium text-gray-700">Total:</span> 
                                            <span class="font-bold text-green-600">Rp ${data.total.toLocaleString('id-ID')}</span>
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium text-gray-700">Metode:</span> 
                                            <span class="font-semibold text-gray-900 uppercase">${data.payment_method}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                        modal.classList.remove('hidden');
                    } else {
                        alert('Bukti pembayaran tidak tersedia');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal memuat bukti pembayaran');
                });
        }

        function closeProof() {
            document.getElementById('proofModal').classList.add('hidden');
        }

        // Direct approve function
        function approveOrder(orderId) {
            if (!confirm('✅ Verifikasi pembayaran order #' + orderId + '?')) return;
            
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}');
            formData.append('action', 'approve');

            fetch(`/admin/orders/${orderId}/verify`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.text())
            .then(html => {
                alert('✅ Pembayaran berhasil diverifikasi!');
                setTimeout(() => window.location.reload(), 800);
            })
            .catch(err => {
                console.error(err);
                alert('❌ Gagal memverifikasi pembayaran');
            });
        }

        // Direct reject function
        function rejectOrder(orderId) {
            if (!confirm('❌ Tolak pembayaran order #' + orderId + '?')) return;
            
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}');
            formData.append('action', 'reject');

            fetch(`/admin/orders/${orderId}/verify`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.text())
            .then(html => {
                alert('❌ Pembayaran berhasil ditolak');
                setTimeout(() => window.location.reload(), 800);
            })
            .catch(err => {
                console.error(err);
                alert('❌ Gagal menolak pembayaran');
            });
        }

        function updateShippingStatus(orderId) {
            const select = document.getElementById('status-select-' + orderId);
            if (!select) return;
            const status = select.value;
            
            const statusLabels = {
                pending: '⏳ Menunggu',
                processing: '⚙️ Diproses',
                shipped: '🚚 Dikirim',
                received: '📦 Diterima',
                delivered: '✅ Selesai',
                cancelled: '❌ Dibatalkan'
            };
            
            // Show tracking modal if changing to 'shipped' status
            if (status === 'shipped') {
                openTrackingModal(orderId);
                select.value = select.getAttribute('data-original') || 'pending';
                return;
            }
            
            if (!confirm(`Perbarui status pesanan #${orderId} menjadi ${statusLabels[status]}?`)) {
                select.value = select.getAttribute('data-original') || 'pending';
                return;
            }

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('status', status);

            fetch(`/admin/orders/${orderId}/status`, {
                method: 'PATCH',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(resp => resp.text())
            .then(() => {
                setTimeout(() => window.location.reload(), 500);
            })
            .catch(err => {
                console.error(err);
                alert('❌ Gagal memperbarui status pesanan');
                select.value = select.getAttribute('data-original') || 'pending';
            });
        }

        function openTrackingModal(orderId) {
            const modal = document.getElementById('trackingModal');
            const form = document.getElementById('trackingForm');
            const title = document.getElementById('trackingModalTitle');
            const submitBtn = document.getElementById('submitBtn');
            const infoSection = document.getElementById('orderInfoSection');
            
            // Reset mode
            form.dataset.orderId = orderId;
            form.dataset.isEditMode = 'false';
            
            // Update modal title
            title.innerHTML = '<i class="fas fa-barcode mr-2 text-blue-600"></i>Input Nomor Resi';
            
            // Update submit button
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Simpan & Kirim';
            submitBtn.classList.remove('bg-amber-600', 'hover:bg-amber-700');
            submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            
            // Hide order info
            infoSection.classList.add('hidden');
            
            // Clear input
            document.getElementById('trackingInput').value = '';
            form.action = `/admin/orders/${orderId}/tracking`;
            
            modal.classList.remove('hidden');
        }

        function closeTrackingModal() {
            document.getElementById('trackingModal').classList.add('hidden');
        }

        function editTrackingNumber(orderId) {
            // Fetch order data
            fetch(`/admin/orders/${orderId}/json`)
                .then(r => r.json())
                .then(data => {
                    const modal = document.getElementById('trackingModal');
                    const form = document.getElementById('trackingForm');
                    const title = document.getElementById('trackingModalTitle');
                    const submitBtn = document.getElementById('submitBtn');
                    const infoSection = document.getElementById('orderInfoSection');
                    
                    // Set mode
                    form.dataset.orderId = orderId;
                    form.dataset.isEditMode = 'true';
                    
                    // Update modal title
                    title.innerHTML = '<i class="fas fa-edit mr-2 text-amber-600"></i>Edit Nomor Resi';
                    
                    // Update submit button
                    submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Perbarui';
                    submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    submitBtn.classList.add('bg-amber-600', 'hover:bg-amber-700');
                    
                    // Show and populate order info
                    document.getElementById('infoOrderId').textContent = '#' + orderId;
                    document.getElementById('infoUserName').textContent = data.user_name || '—';
                    document.getElementById('infoTotal').textContent = 'Rp ' + (data.total || 0).toLocaleString('id-ID');
                    infoSection.classList.remove('hidden');
                    
                    // Populate existing tracking number
                    document.getElementById('trackingInput').value = data.tracking_number || '';
                    
                    // Open modal
                    modal.classList.remove('hidden');
                })
                .catch(err => {
                    console.error(err);
                    alert('❌ Gagal memuat data pesanan');
                });
        }

        function submitTrackingForm() {
            const trackingNumber = document.getElementById('trackingInput').value.trim();
            if (!trackingNumber) {
                alert('❌ Nomor resi tidak boleh kosong');
                return;
            }

            const form = document.getElementById('trackingForm');
            const orderId = form.dataset.orderId;

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('tracking_number', trackingNumber);

            fetch(`/admin/orders/${orderId}/tracking`, {
                method: 'PATCH',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(resp => resp.json())
            .then(data => {
                const statusFormData = new FormData();
                statusFormData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                statusFormData.append('status', 'shipped');

                return fetch(`/admin/orders/${orderId}/status`, {
                    method: 'PATCH',
                    body: statusFormData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
            })
            .then(resp => resp.text())
            .then(() => {
                alert('✅ Nomor resi disimpan!');
                closeTrackingModal();
                setTimeout(() => window.location.reload(), 800);
            })
            .catch(err => {
                console.error(err);
                alert('❌ Gagal: ' + (err.message || 'Unknown error'));
            });
        }
        
        // Store original values
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[id^="status-select-"]').forEach(select => {
                select.setAttribute('data-original', select.value);
            });
        });
    </script>
@endsection