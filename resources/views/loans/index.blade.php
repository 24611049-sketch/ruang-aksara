<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman - Ruang Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

    <style>
        /* BACKGROUND SAMA SEPERTI DASHBOARD */
        body {
            background: 
                linear-gradient(rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.15)),
                url('/images/background.jpg') center/cover fixed no-repeat !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
            min-height: 100vh !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* KONTEN PUTIH TRANSPARAN */
        .content-card {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(8px);
        }

        /* Top Ten medal badges */
        .medal {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 9999px;
            font-weight: 700;
            color: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        }
        .medal.gold { background: linear-gradient(180deg,#FFD54A,#FFB300); color: #2b2b2b; }
        .medal.silver { background: linear-gradient(180deg,#E0E0E0,#BDBDBD); color: #1f1f1f; }
        .medal.bronze { background: linear-gradient(180deg,#D7A17A,#B87333); color: #fff; }
        .medal.gray { background: linear-gradient(180deg,#F3F4F6,#E5E7EB); color: #4b5563; }
        .rank-label { font-size: 0.85rem; margin-left: .6rem; color: #374151; }
        
        
    </style>
</head>
<body class="font-sans antialiased">
    <button id="hamburgerBtn" class="hamburger-btn" type="button" title="Toggle Sidebar" aria-controls="sidebar" aria-expanded="false" onclick="toggleSidebar()">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <aside class="sidebar hidden" id="sidebar">
        @include('partials.user-sidebar-content')
    </aside>

    <div class="main-wrapper" id="mainWrapper">
        <div class="content-wrapper">
            <div class="mx-auto max-w-6xl space-y-8">
                <header class="space-y-1">
                    <h1 class="text-3xl font-bold text-gray-800">Peminjaman Buku Saya</h1>
                    <p class="text-gray-600">Pantau status peminjaman buku Anda dari toko offline</p>
                </header>

                @if($activeLoans->count() > 0)
                    <section class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                            <div class="flex items-center">
                                <i class="fas fa-book text-3xl text-green-500 mr-3"></i>
                                <div>
                                    <p class="text-gray-600 text-sm">Sedang Dipinjam</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $activeLoans->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-3xl text-red-500 mr-3"></i>
                                <div>
                                    <p class="text-gray-600 text-sm">Jatuh Tempo</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $overdueLoans->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-3xl text-blue-500 mr-3"></i>
                                <div>
                                    <p class="text-gray-600 text-sm">Sudah Dikembalikan</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $returnedLoans->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif

                @if (session('success'))
                    <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if($overdueLoans->count() > 0)
                    <section class="content-card p-6 md:p-7 border border-red-200">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl mt-1"></i>
                            <div>
                                <h3 class="font-semibold text-red-800">Ada {{ $overdueLoans->count() }} Peminjaman Jatuh Tempo!</h3>
                                <p class="text-red-700 text-sm mt-1">Segera kembalikan buku berikut ke toko:</p>
                                <ul class="mt-2 space-y-1">
                                    @foreach($overdueLoans as $loan)
                                        @php $book = $loan->book ?? $loan->loanBook; @endphp
                                        <li class="text-red-700 text-sm">
                                            • <strong>{{ optional($book)->judul ?? 'Judul tidak tersedia' }}</strong>
                                            ({{ $loan->getLateDays() }} hari terlambat - harus kembali {{ $loan->return_date->format('d M Y') }})
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </section>
                @endif

                <section class="content-card space-y-6 p-6 md:p-8">
                    <div class="flex gap-4 border-b">
                        <button onclick="switchTab('active', this)" class="tab-btn px-4 py-2 {{ request('loan_search') ? 'border-b-2 border-transparent text-gray-600' : 'active border-b-2 border-blue-600 text-blue-600 font-semibold' }}">
                            <i class="fas fa-hourglass-start mr-1"></i>Aktif
                        </button>
                        <button onclick="switchTab('returned', this)" class="tab-btn px-4 py-2 {{ request('loan_search') ? 'border-b-2 border-transparent text-gray-600' : 'border-b-2 border-transparent hover:border-gray-300 text-gray-600 font-semibold' }}">
                            <i class="fas fa-history mr-1"></i>Riwayat
                        </button>
                        <button onclick="switchTab('catalog', this)" class="tab-btn px-4 py-2 {{ request('loan_search') ? 'active border-b-2 border-blue-600 text-blue-600 font-semibold' : 'border-b-2 border-transparent hover:border-gray-300 text-gray-600 font-semibold' }}">
                            <i class="fas fa-list mr-1"></i>Katalog Peminjaman
                        </button>
                        <button onclick="switchTab('topten', this)" class="tab-btn px-4 py-2 {{ request('loan_search') ? 'border-b-2 border-transparent text-gray-600' : 'border-b-2 border-transparent hover:border-gray-300 text-gray-600 font-semibold' }}">
                            <i class="fas fa-star mr-1"></i>Top Ten
                        </button>
                    </div>

                    <div id="active-tab" class="tab-content {{ request('loan_search') ? 'hidden' : '' }}">
                        @if($activeLoans->count() > 0)
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                @foreach($activeLoans as $loan)
                                        @php
                                            $book = $loan->book ?? $loan->loanBook;
                                            $rawDays = $loan->getDaysUntilReturn();
                                            $daysUntilReturn = is_null($rawDays) ? 0 : (int) ceil($rawDays);
                                            // Clamp durasi tampilan ke minimum 0 agar tidak tampil negatif
                                            $daysUntilReturnClamped = max(0, $daysUntilReturn);
                                            $isOverdue = $loan->isOverdue();
                                            $statusClass = $isOverdue ? 'border-red-500 bg-red-50' : ($daysUntilReturnClamped <= 2 ? 'border-yellow-500 bg-yellow-50' : 'border-green-500 bg-green-50');
                                            $statusColor = $isOverdue ? 'text-red-700' : ($daysUntilReturnClamped <= 2 ? 'text-yellow-700' : 'text-green-700');
                                        @endphp
                                    <div class="bg-white rounded-lg shadow-lg border-l-4 {{ $statusClass }} p-6 content-card">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-800">{{ optional($book)->judul ?? 'Judul tidak tersedia' }}</h3>
                                                <p class="text-gray-600 text-sm">{{ optional($book)->penulis ?? '' }}</p>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-white text-xs font-semibold {{ $isOverdue ? 'bg-red-500' : ($daysUntilReturn <= 2 ? 'bg-yellow-500' : 'bg-green-500') }}">
                                                @if($isOverdue)
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $loan->getLateDays() }} hari terlambat
                                                @elseif($daysUntilReturnClamped <= 0)
                                                    Harus kembali hari ini
                                                @else
                                                    {{ $daysUntilReturnClamped }} hari lagi
                                                @endif
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-gray-600 text-xs">Tanggal Pinjam</p>
                                                <p class="font-semibold text-gray-800">{{ $loan->borrowed_date->format('d M Y') }}</p>
                                                <p class="text-gray-600 text-xs">{{ $loan->borrowed_date->format('H:i') }}</p>
                                            </div>
                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-gray-600 text-xs">Harus Dikembalikan</p>
                                                <p class="font-semibold {{ $statusColor }}">{{ $loan->return_date->format('d M Y') }}</p>
                                                <p class="text-gray-600 text-xs">{{ $loan->return_date->format('H:i') }}</p>
                                            </div>
                                        </div>

                                        @if($loan->notes)
                                            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded text-sm text-blue-800">
                                                <i class="fas fa-sticky-note mr-1"></i>{{ $loan->notes }}
                                            </div>
                                        @endif

                                        <div class="flex gap-2 items-center">
                                            @if(in_array(optional(auth()->user())->role, ['admin','owner']))
                                                <button onclick="openReturnConfirm({{ $loan->id }}, {!! json_encode(optional($book)->judul ?? '') !!})" class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 font-semibold text-sm">
                                                    <i class="fas fa-undo mr-1"></i>Kembalikan
                                                </button>
                                            @else
                                                <div class="flex-1 text-sm text-gray-700 bg-gray-100 rounded-lg p-2">
                                                    Silakan serahkan buku ke admin/owner untuk diproses pengembalian.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-white rounded-lg content-card">
                                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                                <p class="text-gray-600 text-lg">Tidak ada peminjaman aktif</p>
                                <p class="text-gray-500 text-sm">Kunjungi toko offline kami untuk meminjam buku!</p>
                            </div>
                        @endif
                    </div>

                    <div id="returned-tab" class="tab-content {{ request('loan_search') ? 'hidden' : 'hidden' }}">
                        @if($returnedLoans->count() > 0)
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden content-card">
                                <table class="w-full">
                                    <thead class="bg-gray-100 border-b">
                                        <tr>
                                            <th class="text-left px-6 py-3 font-semibold text-gray-800">Buku</th>
                                            <th class="text-left px-6 py-3 font-semibold text-gray-800">Tanggal Pinjam</th>
                                            <th class="text-left px-6 py-3 font-semibold text-gray-800">Tanggal Dikembalikan</th>
                                            <th class="text-left px-6 py-3 font-semibold text-gray-800">Durasi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($returnedLoans as $loan)
                                            @php $book = $loan->book ?? $loan->loanBook; @endphp
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4">
                                                    <div class="font-semibold text-gray-900">{{ optional($book)->judul ?? 'Judul tidak tersedia' }}</div>
                                                    <div class="text-sm text-gray-600">{{ optional($book)->penulis ?? '' }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-700">
                                                    {{ $loan->borrowed_date->format('d M Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-700">
                                                    {{ $loan->returned_at->format('d M Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-700">
                                                    @php
                                                        // Hitung durasi dari tanggal pinjam sampai dikembalikan
                                                        try {
                                                            $durationDays = (int) max(0, ceil($loan->borrowed_date->floatDiffInDays($loan->returned_at)));
                                                        } catch (
                                                        Throwable $e) {
                                                            $durationDays = 0;
                                                        }
                                                    @endphp
                                                    {{ $durationDays }} hari
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12 bg-white rounded-lg content-card">
                                <i class="fas fa-history text-4xl text-gray-400 mb-4"></i>
                                <p class="text-gray-600 text-lg">Belum ada riwayat pengembalian</p>
                            </div>
                        @endif
                    </div>

                    <div id="catalog-tab" class="tab-content {{ request('loan_search') ? '' : 'hidden' }}">
                        <!-- Search form for catalog -->
                        <form method="GET" action="{{ route('loans.index') }}" class="mb-4">
                            <div class="flex items-center gap-2 max-w-md">
                                <input type="text" name="loan_search" value="{{ request('loan_search') }}" placeholder="Cari judul atau penulis..." class="w-full px-3 py-2 border rounded-lg">
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Cari</button>
                                <a href="{{ route('loans.index') }}" class="px-3 py-2 bg-gray-200 rounded-lg text-sm">Reset</a>
                            </div>
                        </form>

                        @if($loanBooks->isEmpty())
                            <div class="p-4 bg-yellow-50 rounded">Tidak ada buku untuk peminjaman saat ini.</div>
                        @else
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($loanBooks as $book)
                                    <div class="card p-3 text-center bg-white rounded shadow">
                                        @php
                                            // Support both Book and LoanBook models - prefer image_url accessor if available
                                            $img = data_get($book, 'image_url') ?: (data_get($book, 'image') ? asset('storage/book-covers/' . $book->image) : asset('images/default-book.jpg'));
                                            $title = data_get($book, 'judul') ?: data_get($book, 'title');
                                            $loanStock = data_get($book, 'loan_stok') ?: 0;
                                        @endphp
                                        <a href="{{ route('books.show', $book->id ?? ($book->original_book_id ?? '#')) }}">
                                            <img src="{{ $img }}" alt="{{ $title }}" class="mx-auto h-40 object-contain mb-2">
                                            <h3 class="font-semibold text-sm">{{ $title }}</h3>
                                        </a>
                                        <p class="text-xs text-gray-600">Stok pinjam: <strong>{{ $loanStock }}</strong></p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div id="topten-tab" class="tab-content hidden">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden content-card p-6">
                            <h3 class="text-xl font-bold mb-4">📊 10 Peminjam Terbanyak</h3>
                            @if(isset($loanByUsers) && $loanByUsers->isNotEmpty())
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-100 border-b">
                                                <tr>
                                                    <th class="text-left px-6 py-3 font-semibold text-gray-800">#</th>
                                                    <th class="text-left px-6 py-3 font-semibold text-gray-800">Nama</th>
                                                    <th class="text-left px-6 py-3 font-semibold text-gray-800">Email</th>
                                                    <th class="text-left px-6 py-3 font-semibold text-gray-800">Total Pinjam</th>
                                                    <th class="text-left px-6 py-3 font-semibold text-gray-800">Pinjam Terakhir</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y">
                                                @foreach($loanByUsers as $loan)
                                                    @php
                                                        $rank = $loop->iteration;
                                                        $medalClass = 'gray';
                                                        if ($rank === 1) $medalClass = 'gold';
                                                        else if ($rank === 2) $medalClass = 'silver';
                                                        else if ($rank === 3) $medalClass = 'bronze';
                                                    @endphp
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4">
                                                            <div class="flex items-center">
                                                                <span class="medal {{ $medalClass }}">{{ $rank }}</span>
                                                                <span class="rank-label">{{ $loop->remaining ? '' : '' }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            {{ $loan->user ? $loan->user->name : 'User Tidak Ditemukan' }}
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $loan->user ? $loan->user->email : '-' }}</td>
                                                        <td class="px-6 py-4"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $loan->total_loans }}</span></td>
                                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $loan->last_loan_date ? \Carbon\Carbon::parse($loan->last_loan_date)->format('d M Y') : '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                            @else
                                <div class="text-center py-12">
                                    <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600">Belum ada data peminjaman</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div class="nav-buttons">
        <button type="button" onclick="window.history.back()" title="Kembali">
            <i class="fas fa-arrow-left"></i>
        </button>
        <button type="button" onclick="window.history.forward()" title="Maju">
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>

    <!-- Return Confirmation Modal -->
    <div id="returnModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-md w-full">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold">Konfirmasi Pengembalian</h2>
                <button onclick="closeReturnModal()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <p class="text-gray-700">Apakah Anda yakin ingin mengembalikan buku:</p>
                <p id="returnBookTitle" class="font-bold text-lg text-gray-900"></p>
                
                <p class="text-gray-600 text-sm">Pastikan buku dalam kondisi baik sebelum dikembalikan ke toko.</p>

                <form id="returnForm" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="flex gap-2 justify-end pt-4 border-t">
                        <button type="button" onclick="closeReturnModal()" class="px-4 py-2 bg-gray-300 text-gray-900 rounded-lg hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-check mr-2"></i>Ya, Kembalikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>
        function switchTab(tabName, button) {
            document.querySelectorAll('.tab-content').forEach((tab) => {
                tab.classList.add('hidden');
            });

            document.querySelectorAll('.tab-btn').forEach((tabButton) => {
                tabButton.classList.remove('active', 'border-b-2', 'border-blue-600', 'text-blue-600');
                tabButton.classList.add('border-transparent', 'text-gray-600');
            });

            const targetTab = document.getElementById(`${tabName}-tab`);
            if (targetTab) {
                targetTab.classList.remove('hidden');
            }

            if (button) {
                button.classList.add('active', 'border-b-2', 'border-blue-600', 'text-blue-600');
                button.classList.remove('border-transparent', 'text-gray-600');
            }
        }

        function openReturnConfirm(loanId, bookTitle) {
            document.getElementById('returnBookTitle').textContent = bookTitle;
            document.getElementById('returnForm').action = `/loans/${loanId}/return`;
            document.getElementById('returnModal').classList.remove('hidden');
        }

        function closeReturnModal() {
            document.getElementById('returnModal').classList.add('hidden');
        }
    </script>
</body>
</html>
