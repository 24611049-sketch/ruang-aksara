@extends('layouts.app')

@section('title', 'Biaya Operasional - Ruang Aksara')

@section('content')
<div class="max-w-6xl mx-auto py-6">
    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg p-4 mb-4">
        <div class="flex items-center gap-3">
            <i class="fas fa-wallet text-2xl"></i>
            <div>
                <h1 class="text-lg font-bold">Ringkasan Biaya Operasional</h1>
                <p class="text-sm opacity-90">Kelola biaya operasional — tambah, edit, dan kaitkan biaya dengan buku saat restock.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="col-span-2 bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-bold text-gray-800">Daftar Biaya Operasional</h2>
                <form method="POST" action="{{ route('admin.operational-costs.store') }}" class="flex items-center gap-2">
                    @csrf
                    <input name="item" placeholder="Item singkat" class="form-input" style="width:200px" required>
                    <input name="amount" placeholder="Jumlah (Rp)" class="form-input" style="width:140px" required>
                    <button type="submit" class="btn-primary">Tambah</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs text-gray-500 uppercase border-b">
                            <th class="py-2">Tanggal</th>
                            <th class="py-2">Item</th>
                            <th class="py-2">Kategori</th>
                            <th class="py-2">Jumlah</th>
                            <th class="py-2">Terkait Buku</th>
                            <th class="py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($costs as $cost)
                        @php $total += $cost->amount; @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2 align-top text-xs text-gray-600">{{ $cost->created_at->format('Y-m-d') }}</td>
                            <td class="py-2 align-top">{{ $cost->item }}</td>
                            <td class="py-2 align-top text-xs text-gray-600">{{ $cost->category }}</td>
                            <td class="py-2 align-top font-semibold">Rp {{ number_format($cost->amount,0,',','.') }}</td>
                            <td class="py-2 align-top text-xs text-gray-600">{{ optional($cost->relatedBook)->judul ?? '-' }}</td>
                            <td class="py-2 align-top text-xs">
                                <form method="POST" action="{{ route('admin.operational-costs.update', $cost) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="item" value="{{ $cost->item }}">
                                    <input type="hidden" name="amount" value="{{ $cost->amount }}">
                                    <button class="text-blue-600 mr-2">Edit</button>
                                </form>
                                <form method="POST" action="{{ route('admin.operational-costs.destroy', $cost) }}" class="inline-block" onsubmit="return confirm('Hapus biaya ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="bg-gray-100 font-bold">
                            <td class="py-2">Total</td>
                            <td class="py-2"></td>
                            <td class="py-2"></td>
                            <td class="py-2">Rp {{ number_format($total,0,',','.') }}</td>
                            <td class="py-2" colspan="2">Ringkasan biaya (halaman ini dapat diekspor CSV nanti)</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $costs->links() }}
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <h3 class="font-bold text-gray-800 mb-2">Ide & Optimasi</h3>
            <ul class="list-disc pl-5 text-sm text-gray-700 space-y-2">
                <li><strong>Optimalkan stok best-seller:</strong> Kurangi biaya penyimpanan dengan memesan berdasarkan tingkat penjualan mingguan.</li>
                <li><strong>Program langganan:</strong> Tawarkan paket langganan bulanan untuk pembaca tetap guna menaikkan pendapatan berulang.</li>
                <li><strong>Event kecil di toko:</strong> Peluncuran buku lokal atau diskon akhir pekan dapat meningkatkan traffic dan pembelian impulsif.</li>
                <li><strong>Cross-sell & bundling:</strong> Gabungkan buku dengan merchandise kecil (bookmark, totebag) untuk menaikkan nilai pesanan rata-rata.</li>
                <li><strong>Kontrol biaya tenaga kerja:</strong> Tinjau jadwal shift agar jam sibuk terlayani tanpa overstaffing pada jam sepi.</li>
            </ul>

            <div class="mt-4">
                <h4 class="font-semibold text-gray-800">Integrasi yang saya tambahkan</h4>
                <ol class="list-decimal pl-5 text-sm text-gray-700 mt-2">
                    <li>CRUD untuk menyimpan biaya (tabel <code>operational_costs</code>).</li>
                    <li>Auto-create biaya saat restock buku (berdasarkan <code>purchase_price</code> buku).</li>
                    <li>Kolom <code>related_book_id</code> agar biaya dapat dikaitkan ke buku.</li>
                    <li>Pagination dan ringkasan total; nanti bisa ditambah export CSV/Excel dan filter tanggal.</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="mt-6 text-xs text-gray-500">Halaman sudah memiliki penyimpanan & integrasi stok dasar. Mau saya tambahkan filter waktu atau export CSV/Excel?</div>
</div>
@endsection
