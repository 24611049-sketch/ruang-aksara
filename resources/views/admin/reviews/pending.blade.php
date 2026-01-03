@extends('layouts.app')

@section('title', 'Kelola Review - Admin')

@section('content')
<div class="w-full py-6" style="margin: 0 !important; padding: 0 1rem 0 0 !important;">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-star mr-2 text-yellow-500"></i>Kelola Review Buku
        </h1>
        <p class="text-gray-600">Setujui atau hapus review dari pembaca</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Reviews List -->
    <div class="bg-white rounded-lg shadow">
        @forelse($reviews as $review)
            <div class="p-6 border-b border-gray-200 hover:bg-gray-50">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <!-- Book Info -->
                        <div class="mb-3">
                            <h3 class="text-lg font-bold text-gray-800">{{ $review->book->judul }}</h3>
                            <p class="text-sm text-gray-600">oleh {{ $review->book->penulis }}</p>
                        </div>

                        <!-- Review Info -->
                        <div class="mb-3">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $review->user->foto_profil ? asset('storage/' . $review->user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=2d5a3d&color=fff' }}" 
                                         alt="{{ $review->user->name }}" 
                                         class="w-8 h-8 rounded-full">
                                    <span class="font-semibold text-gray-800">{{ $review->user->name }}</span>
                                </div>
                                <div class="text-yellow-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                                    @endfor
                                    <span class="text-sm text-gray-600 ml-1">{{ $review->rating }}/5</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            
                            @if($review->comment)
                                <p class="text-gray-700 mt-2 p-3 bg-gray-50 rounded">{{ $review->comment }}</p>
                            @else
                                <p class="text-gray-500 italic text-sm">Tidak ada komentar</p>
                            @endif
                        </div>

                        <!-- Status -->
                        <div class="flex items-center gap-2">
                            @if($review->is_approved)
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    <i class="fas fa-check mr-1"></i>Disetujui
                                </span>
                            @else
                                <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full">
                                    <i class="fas fa-clock mr-1"></i>Menunggu Persetujuan
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 ml-4">
                        @if(!$review->is_approved)
                            <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm font-semibold">
                                    <i class="fas fa-check mr-1"></i>Setujui
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus review ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm font-semibold">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-inbox text-5xl mb-4 opacity-50"></i>
                <p class="text-lg">Tidak ada review yang menunggu persetujuan</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($reviews->hasPages())
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
