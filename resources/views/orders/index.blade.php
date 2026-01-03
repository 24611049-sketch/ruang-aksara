<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Ruang Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <style>
        body {
            background:
                linear-gradient(rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.15)),
                url('/images/background.jpg') center/cover fixed no-repeat !important;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .content-card {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 1.5rem;
            box-shadow: 0 14px 36px rgba(31, 124, 69, 0.12);
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        thead tr {
            background-color: #f3f4f6;
        }

        tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: #f9fafb;
        }

        td {
            vertical-align: middle;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            border-radius: 9999px;
            font-weight: 600;
            padding: 0.35rem 0.85rem;
            color: #1f2937;
            background: rgba(229, 231, 235, 0.85);
        }

        .status-pending {
            background: rgba(253, 230, 138, 0.85);
            color: #92400e;
        }

        .status-processing {
            background: rgba(191, 219, 254, 0.85);
            color: #1d4ed8;
        }

        .status-shipped {
            background: rgba(147, 197, 253, 0.9);
            color: #1e40af;
        }

        .status-received {
            background: rgba(187, 247, 208, 0.9);
            color: #047857;
        }

        .status-delivered {
            background: rgba(167, 243, 208, 0.95);
            color: #065f46;
        }

        .status-cancelled,
        .status-failed {
            background: rgba(254, 202, 202, 0.9);
            color: #b91c1c;
        }

        .status-verified {
            background: rgba(187, 247, 208, 0.95);
            color: #047857;
        }
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
            <div class="mx-auto max-w-6xl space-y-6">
                <header class="space-y-1">
                    <h1 class="text-3xl font-bold text-gray-800">Riwayat Pesanan</h1>
                    <p class="text-gray-600">Lihat semua pesanan buku yang telah Anda buat</p>
                </header>

                <section class="rounded-xl border border-blue-200 bg-blue-50 p-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-blue-600 mt-1 flex-shrink-0"></i>
                        <div class="space-y-2">
                            <h3 class="font-semibold text-blue-800">Status Pembayaran</h3>
                            <p class="text-sm text-blue-700">
                                <strong>Pembayaran Menunggu:</strong> Bukti pembayaran Anda sedang menunggu verifikasi dari admin.<br>
                                <strong>Pembayaran Terverifikasi:</strong> Pembayaran Anda telah disetujui dan pesanan akan segera diproses.<br>
                                <strong>Pembayaran Ditolak:</strong> Pembayaran Anda ditolak. Hubungi admin untuk bantuan.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="content-card border p-6">
                    @if($orders->count() > 0)
                        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
                            <div class="border border-blue-200 bg-blue-50 p-4">
                                <div class="text-sm font-medium text-blue-600">Total Pesanan</div>
                                <div class="text-2xl font-bold text-blue-800">{{ $orders->count() }}</div>
                            </div>
                            <div class="border border-green-200 bg-green-50 p-4">
                                <div class="text-sm font-medium text-green-600">Selesai</div>
                                <div class="text-2xl font-bold text-green-800">{{ $orders->where('status', 'delivered')->count() }}</div>
                            </div>
                            <div class="border border-yellow-200 bg-yellow-50 p-4">
                                <div class="text-sm font-medium text-yellow-600">Diproses</div>
                                <div class="text-2xl font-bold text-yellow-800">{{ $orders->whereIn('status', ['pending', 'processing'])->count() }}</div>
                            </div>
                            <div class="border border-purple-200 bg-purple-50 p-4">
                                <div class="text-sm font-medium text-purple-600">Total Belanja</div>
                                <div class="text-2xl font-bold text-purple-800">Rp {{ number_format($orders->sum('total_price'), 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b-2 border-gray-300">
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Order ID</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Buku</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Jumlah</th>
                                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Harga</th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Status</th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($groupedOrders as $groupKey => $group)
                                        @php 
                                            $groupKeyId = 'grp_' . str_replace([' ', ':', '-'], '_', $groupKey);
                                            $first = $group->first();
                                            $allItems = $group->flatMap(fn($order) => $order->items);
                                        @endphp
                                        <tr class="transition hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                                <div class="flex flex-col gap-1">
                                                    <span class="font-semibold text-blue-700">{{ $groupKey ?: '#'.$group->first()->id }}</span>
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2 py-0.5 text-[11px] text-blue-800">
                                                        <i class="fas fa-book"></i>{{ $allItems->count() }} buku
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 align-top text-sm text-gray-700">
                                                @php $limitBooks = $allItems->take(2); $hiddenBooks = $allItems->skip(2); @endphp
                                                <div class="flex flex-col gap-2">
                                                    @foreach($limitBooks as $item)
                                                        <div class="flex items-center gap-2">
                                                            @if($item->book && ($item->book->image ?? false))
                                                                <img src="{{ asset('storage/book-covers/' . $item->book->image) }}" alt="{{ $item->book->judul ?? 'Buku' }}" class="h-10 w-8 rounded object-cover">
                                                            @else
                                                                <div class="flex h-10 w-8 items-center justify-center rounded bg-gray-300">
                                                                    <i class="fas fa-book text-xs text-gray-600"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <p class="font-medium leading-tight">{{ $item->book->judul ?? 'Buku tidak tersedia' }}</p>
                                                                <p class="text-[11px] text-gray-600">{{ $item->book->penulis ?? 'Unknown' }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    @if($allItems->count() > 2)
                                                        <details class="text-[11px]">
                                                            <summary class="cursor-pointer text-blue-600 hover:underline">Lihat {{ $allItems->count() - 2 }} buku lain</summary>
                                                            <div class="mt-2 space-y-2">
                                                                @foreach($hiddenBooks as $item)
                                                                    <div class="flex items-center gap-2">
                                                                        @if($item->book && ($item->book->image ?? false))
                                                                            <img src="{{ asset('storage/book-covers/' . $item->book->image) }}" alt="{{ $item->book->judul ?? 'Buku' }}" class="h-9 w-7 rounded object-cover">
                                                                        @else
                                                                            <div class="flex h-9 w-7 items-center justify-center rounded bg-gray-300">
                                                                                <i class="fas fa-book text-[10px] text-gray-600"></i>
                                                                            </div>
                                                                        @endif
                                                                        <div>
                                                                            <p class="text-xs font-medium leading-tight">{{ $item->book->judul ?? 'Buku tidak tersedia' }}</p>
                                                                            <p class="text-[10px] text-gray-600">{{ $item->book->penulis ?? 'Unknown' }}</p>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </details>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                {{ optional($first->created_at)->format('d M Y') }}<br>
                                                <span class="text-xs text-gray-600">{{ optional($first->created_at)->format('H:i') }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-700">{{ $allItems->sum('quantity') }}</td>
                                            <td class="px-4 py-3 text-right text-sm font-semibold text-gray-800">
                                                Rp {{ number_format($first->total_price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-center align-top">
                                                <div class="flex flex-col items-center gap-1 text-xs leading-tight text-gray-700">
                                                    @if($first && $first->payment_method != 'cash')
                                                        <span class="status-badge status-{{ $first->payment_status ?? 'pending' }}">
                                                            @if(($first->payment_status ?? 'pending') == 'pending')
                                                                Menunggu Verifikasi
                                                            @elseif($first->payment_status == 'verified')
                                                                Terverifikasi
                                                            @elseif($first->payment_status == 'failed')
                                                                Ditolak
                                                            @endif
                                                        </span>
                                                    @endif
                                                    <span class="status-badge status-{{ $first->status ?? 'pending' }}">
                                                        @if($first->status == 'pending')
                                                            Menunggu
                                                        @elseif($first->status == 'processing')
                                                            Diproses
                                                        @elseif($first->status == 'shipped')
                                                            Dikirim
                                                        @elseif($first->status == 'received')
                                                            Diterima
                                                        @elseif($first->status == 'delivered')
                                                            Selesai
                                                        @else
                                                            Dibatalkan
                                                        @endif
                                                    </span>
                                                    <span class="text-[11px] {{ $first->tracking_number ? 'text-gray-600' : 'text-gray-500' }}">
                                                        {{ $first->tracking_number ? 'Resi: '.$first->tracking_number : 'Resi belum tersedia' }}
                                                    </span>
                                                    @if($first->confirmed_by_user)
                                                        <span class="text-[11px] text-green-700">Sudah dikonfirmasi</span>
                                                    @elseif(in_array($first->status, ['shipped','processing','delivered']))
                                                        <form method="POST" action="{{ route('orders.confirm', $first->id) }}" class="mt-1">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-[11px] text-blue-600 hover:underline">Tandai Diterima</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <a href="{{ route('orders.show', $group->first()->id) }}" class="inline-flex items-center rounded bg-green-600 px-3 py-2 text-sm text-white transition hover:bg-green-700">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-center">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="space-y-6 py-12 text-center">
                            <div class="text-6xl text-gray-300">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="space-y-2">
                                <h2 class="text-2xl font-bold text-gray-800">Belum ada pesanan</h2>
                                <p class="mx-auto max-w-md text-gray-600">Anda belum melakukan pemesanan buku. Yuk jelajahi katalog buku kami untuk menemukan bacaan menarik!</p>
                            </div>
                            <a href="{{ route('books.index') }}" class="inline-flex items-center rounded-lg bg-green-600 px-6 py-3 text-white transition hover:bg-green-700">
                                <i class="fas fa-book mr-2"></i> Jelajahi Buku
                            </a>
                        </div>
                    @endif
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

    <script src="{{ asset('js/sidebar.js') }}"></script>
</body>
</html>