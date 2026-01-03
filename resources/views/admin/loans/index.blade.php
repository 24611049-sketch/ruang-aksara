@extends('layouts.app')

@section('content')
<div class="w-full px-0 py-6 pr-4" style="margin: 0 !important; padding-left: 0 !important;">
    <!-- Add New Loan Button -->
    <div class="mb-6">
            <button onclick="openAddLoanModal()" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 flex items-center">
                <i class="fas fa-plus mr-2"></i>Tambah Peminjaman Baru
            </button>
            <div class="mt-2 text-sm text-gray-600">
                Jumlah buku tersedia untuk pinjam: <strong>{{ isset($books) ? $books->count() : 0 }}</strong>
                <span class="text-gray-400 mx-2">|</span>
                <a href="{{ route('admin.loan-stock.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                    <i class="fas fa-warehouse mr-1"></i>Kelola Stok Peminjaman
                </a>
            </div>
            @if(!isset($books) || $books->isEmpty())
                <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded">
                    <i class="fas fa-info-circle mr-2"></i>Tidak ada buku yang tersedia untuk peminjaman. 
                    <a href="{{ route('admin.loan-stock.index') }}" class="font-semibold hover:underline">
                        Tambah buku di halaman Kelola Stok Peminjaman
                    </a>
                </div>
            @endif
        </div>

        <!-- Filter Tabs -->
        <div class="flex gap-4 mb-6 flex-wrap">
            <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" 
               class="px-4 py-2 rounded-lg {{ request('status', 'active') == 'active' ? 'bg-green-600 text-white' : 'bg-white text-gray-800' }}">
                <i class="fas fa-hourglass-start mr-2"></i>Aktif
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'overdue']) }}" 
               class="px-4 py-2 rounded-lg {{ request('status') == 'overdue' ? 'bg-red-600 text-white' : 'bg-white text-gray-800' }}">
                <i class="fas fa-exclamation-circle mr-2"></i>Jatuh Tempo
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'returned']) }}" 
               class="px-4 py-2 rounded-lg {{ request('status') == 'returned' ? 'bg-blue-600 text-white' : 'bg-white text-gray-800' }}">
                <i class="fas fa-check-circle mr-2"></i>Dikembalikan
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}" 
               class="px-4 py-2 rounded-lg {{ request('status') == 'all' ? 'bg-gray-600 text-white' : 'bg-white text-gray-800' }}">
                <i class="fas fa-list mr-2"></i>Semua
            </a>
        </div>

        <!-- Loans Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold text-gray-800">ID Peminjam</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-800">User</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-800">Buku</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-800">Tanggal Pinjam</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-800">Tanggal Kembali</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-800">Status</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-800">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold text-gray-900">#{{ $loan->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $loan->user->name }}</div>
                                <div class="text-sm text-gray-600">{{ $loan->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $loan->loanBook->judul ?? $loan->book->judul }}</div>
                                <div class="text-sm text-gray-600">{{ $loan->loanBook->penulis ?? $loan->book->penulis }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $loan->borrowed_date->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="text-gray-900">{{ $loan->return_date->format('d M Y') }}</div>
                                @if($loan->isOverdue())
                                    <div class="text-red-600 font-semibold text-xs">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ $loan->getLateDays() }} hari terlambat
                                    </div>
                                @elseif($loan->status === 'active')
                                    <div class="text-gray-600 text-xs">
                                        {{ $loan->getDaysUntilReturn() > 0 ? $loan->getDaysUntilReturn() . ' hari lagi' : 'Hari ini kembali' }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="status-badge status-{{ $loan->status }}">
                                    @switch($loan->status)
                                        @case('active')
                                            <i class="fas fa-hourglass-start mr-1"></i>Aktif
                                            @break
                                        @case('returned')
                                            <i class="fas fa-check-circle mr-1"></i>Dikembalikan
                                            @break
                                        @case('overdue')
                                            <i class="fas fa-exclamation-circle mr-1"></i>Jatuh Tempo
                                            @break
                                        @case('cancelled')
                                            <i class="fas fa-ban mr-1"></i>Dibatalkan
                                            @break
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    @if($loan->status === 'active')
                                        <button onclick="openReturnModal({{ $loan->id }})" class="text-green-600 hover:text-green-900 font-semibold text-sm">
                                            <i class="fas fa-undo mr-1"></i>Terima Kembali
                                        </button>
                                    @endif
                                    <button onclick="openViewModal({{ $loan->id }})" class="text-blue-600 hover:text-blue-900 font-semibold text-sm">
                                        <i class="fas fa-eye mr-1"></i>Lihat
                                    </button>
                                    @if($loan->status === 'active')
                                        <button onclick="openEditModal({{ $loan->id }})" class="text-orange-600 hover:text-orange-900 font-semibold text-sm">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-600">
                                <i class="fas fa-inbox text-3xl mb-2"></i><br>
                                Tidak ada data peminjaman
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $loans->links() }}
        </div>
    </div>

    <!-- Add/Edit Loan Modal -->
    <div id="loanModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-96 overflow-y-auto">
            <div class="sticky top-0 bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold" id="modalTitle">Tambah Peminjaman Baru</h2>
                <button onclick="closeLoanModal()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form id="loanForm" action="{{ route('admin.loans.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="loanId" name="id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">User (Peminjam)</label>
                    <select id="userId" name="user_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">-- Pilih User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buku</label>
                    <select id="bookId" name="book_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">-- Pilih Buku --</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" data-loanstok="{{ $book->loan_stok ?? 0 }}">{{ $book->judul }} - {{ $book->penulis }}</option>
                        @endforeach
                    </select>
                    <p id="bookStock" class="text-sm text-gray-600 mt-2">Stok pinjam: -</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pinjam</label>
                        <input type="datetime-local" id="borrowedDate" name="borrowed_date" required 
                               min="" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kembali</label>
                        <input type="datetime-local" id="returnDate" name="return_date" required 
                               min=""
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pinjam</label>
                    <input type="number" id="quantity" name="quantity" min="1" value="1" required 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p class="text-xs text-gray-500 mt-1">Masukkan jumlah kopi yang dipinjam oleh peminjam.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea id="notes" name="notes" rows="2" placeholder="Catatan peminjaman..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                </div>

                <div class="flex gap-2 justify-end pt-4 border-t">
                    <button type="button" onclick="closeLoanModal()" class="px-4 py-2 bg-gray-300 text-gray-900 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Loan Modal -->
    <div id="viewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-96 overflow-y-auto">
            <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold">Detail Peminjaman</h2>
                <button onclick="closeViewModal()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <div id="viewContent" class="p-6 space-y-3">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Return Loan Modal -->
    <div id="returnModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-md w-full">
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold">Terima Peminjaman Kembali</h2>
                <button onclick="closeReturnModal()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form id="returnForm" method="POST" action="" class="p-6 space-y-4">
                @csrf
                @method('PATCH')
                
                <p class="text-gray-700">Apakah buku sudah dikembalikan oleh peminjam?</p>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengembalian (opsional)</label>
                    <input type="datetime-local" name="returned_at" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <small class="text-gray-600">Kosongkan untuk menggunakan waktu sekarang</small>
                </div>

                <div class="flex gap-2 justify-end pt-4 border-t">
                    <button type="button" onclick="closeReturnModal()" class="px-4 py-2 bg-gray-300 text-gray-900 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i>Konfirmasi Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Helper function to convert Date to local datetime-local format
        function formatLocalDateTime(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        function openAddLoanModal() {
            document.getElementById('loanForm').reset();
            document.getElementById('loanForm').action = "{{ route('admin.loans.store') }}";
            document.getElementById('loanForm').method = 'POST';
            // remove any leftover method override when opening add modal
            const methodOverride = document.querySelector('#loanForm input[name="_method"]');
            if (methodOverride) methodOverride.remove();
            document.getElementById('modalTitle').textContent = 'Tambah Peminjaman Baru';
            document.getElementById('loanId').value = '';
            
            // Set default dates to current LOCAL time and 7 days from now
            const now = new Date();
            const sevenDaysLater = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
            
            // Set minimum date to today (prevent selecting past dates) - use local time
            const minDateTime = formatLocalDateTime(now);
            document.getElementById('borrowedDate').min = minDateTime;
            document.getElementById('returnDate').min = minDateTime;
            
            // Set default values to current LOCAL time
            document.getElementById('borrowedDate').value = minDateTime;
            document.getElementById('returnDate').value = formatLocalDateTime(sevenDaysLater);
            
            document.getElementById('loanModal').classList.remove('hidden');

            // update loan stock display for default selection
            updateBookStockDisplay();
        }

        function openEditModal(loanId) {
            fetch(`/admin/loans/${loanId}`)
                .then(response => response.json())
                .then(data => {
                    const book = data.loan_book || data.book;

                    // populate form
                    document.getElementById('loanForm').action = `/admin/loans/${loanId}`;
                    document.getElementById('loanForm').method = 'POST';

                    // ensure _method override exists for PUT
                    let methodInput = document.querySelector('#loanForm input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        document.getElementById('loanForm').appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';

                    document.getElementById('modalTitle').textContent = 'Edit Peminjaman';
                    document.getElementById('loanId').value = data.id;

                    // set user
                    if (data.user) {
                        const userSelect = document.getElementById('userId');
                        if (userSelect) userSelect.value = data.user.id;
                    }

                    // set book (loan_book id)
                    const bookSelect = document.getElementById('bookId');
                    if (bookSelect) {
                        const bookId = (data.loan_book && data.loan_book.id) ? data.loan_book.id : (data.book && data.book.id ? data.book.id : '');
                        bookSelect.value = bookId;
                        // update displayed stock
                        updateBookStockDisplay();
                    }

                    // dates
                    if (data.borrowed_date) document.getElementById('borrowedDate').value = new Date(data.borrowed_date).toISOString().slice(0,16);
                    if (data.return_date) document.getElementById('returnDate').value = new Date(data.return_date).toISOString().slice(0,16);

                    // quantity & notes
                    if (data.quantity !== undefined) document.getElementById('quantity').value = data.quantity;
                    document.getElementById('notes').value = data.notes || '';

                    document.getElementById('loanModal').classList.remove('hidden');
                });
        }

        function closeLoanModal() {
            document.getElementById('loanModal').classList.add('hidden');
        }

        // Update book stock display when selection changes
        document.getElementById('bookId').addEventListener('change', updateBookStockDisplay);

        // Update return date minimum when borrowed date changes
        document.getElementById('borrowedDate').addEventListener('change', function() {
            const borrowedDate = this.value;
            const returnDateInput = document.getElementById('returnDate');
            
            if (borrowedDate) {
                // Set minimum return date to borrowed date
                returnDateInput.min = borrowedDate;
                
                // If current return date is before borrowed date, update it
                if (returnDateInput.value && returnDateInput.value < borrowedDate) {
                    const borrowed = new Date(borrowedDate);
                    const sevenDaysLater = new Date(borrowed.getTime() + 7 * 24 * 60 * 60 * 1000);
                    returnDateInput.value = sevenDaysLater.toISOString().slice(0, 16);
                }
            }
        });

        function updateBookStockDisplay() {
            const select = document.getElementById('bookId');
            const opt = select.options[select.selectedIndex];
            const stockEl = document.getElementById('bookStock');
            if (!opt || !opt.dataset) {
                stockEl.textContent = 'Stok: -';
                return;
            }
            const stok = opt.dataset.stok !== undefined ? opt.dataset.stok : (opt.dataset.loanstok !== undefined ? opt.dataset.loanstok : '-');
            // prefer loan stok (data-loanstok)
            const loanStok = opt.dataset.loanstok !== undefined ? opt.dataset.loanstok : (opt.dataset.stok !== undefined ? opt.dataset.stok : '-');
            stockEl.textContent = `Stok pinjam: ${loanStok}`;
            // disable submit if stok < 1
            const submit = document.querySelector('#loanForm button[type="submit"]');
            if (Number(stok) < 1) {
                submit.disabled = true;
                submit.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                submit.disabled = false;
                submit.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            // update quantity max
            const qty = document.getElementById('quantity');
            if (qty) {
                qty.max = Number(loanStok) || 1;
                if (Number(qty.value) > Number(loanStok)) {
                    qty.value = loanStok;
                }
            }
        }



        function openViewModal(loanId) {
            // Make AJAX call to get loan details
            fetch(`/admin/loans/${loanId}`)
                .then(response => response.json())
                .then(data => {
                    // Use loanBook if available, otherwise fall back to book
                    const book = data.loan_book || data.book;
                    const html = `
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600 text-sm">ID Peminjam</p>
                                <p class="font-semibold">#${data.id}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Status</p>
                                <p class="font-semibold">
                                    <span class="px-2 py-1 rounded text-white" style="background-color: ${getStatusColor(data.status)}">
                                        ${getStatusLabel(data.status)}
                                    </span>
                                </p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-600 text-sm">Peminjam</p>
                                <p class="font-semibold">${data.user.name}</p>
                                <p class="text-gray-600 text-sm">${data.user.email}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-600 text-sm">Buku</p>
                                <p class="font-semibold">${book.judul}</p>
                                <p class="text-gray-600 text-sm">Penulis: ${book.penulis}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Tanggal Pinjam</p>
                                <p class="font-semibold">${new Date(data.borrowed_date).toLocaleString('id-ID')}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Tanggal Kembali</p>
                                <p class="font-semibold">${new Date(data.return_date).toLocaleString('id-ID')}</p>
                            </div>
                            ${data.returned_at ? `
                                <div>
                                    <p class="text-gray-600 text-sm">Tanggal Dikembalikan</p>
                                    <p class="font-semibold">${new Date(data.returned_at).toLocaleString('id-ID')}</p>
                                </div>
                            ` : ''}
                            ${data.notes ? `
                                <div class="col-span-2">
                                    <p class="text-gray-600 text-sm">Catatan</p>
                                    <p class="font-semibold">${data.notes}</p>
                                </div>
                            ` : ''}
                        </div>
                    `;
                    document.getElementById('viewContent').innerHTML = html;
                    document.getElementById('viewModal').classList.remove('hidden');
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        function openReturnModal(loanId) {
            document.getElementById('returnForm').action = `/admin/loans/${loanId}/return`;
            document.getElementById('returnModal').classList.remove('hidden');
        }

        function closeReturnModal() {
            document.getElementById('returnModal').classList.add('hidden');
        }

        function getStatusColor(status) {
            const colors = {
                active: '#16a34a',
                returned: '#1e40af',
                overdue: '#991b1b',
                cancelled: '#6b7280'
            };
            return colors[status] || '#6b7280';
        }

        function getStatusLabel(status) {
            const labels = {
                active: 'Aktif',
                returned: 'Dikembalikan',
                overdue: 'Jatuh Tempo',
                cancelled: 'Dibatalkan'
            };
            return labels[status] || status;
        }
    </script>
</div>
@endsection
