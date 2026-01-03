@extends('layouts.app')

@section('title', 'Riwayat Poin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Riwayat Poin</h1>
            <div class="text-right">
                <p class="text-sm text-gray-500">Saldo saat ini</p>
                <p class="text-lg font-semibold text-blue-600">{{ number_format($userPoints) }} poin</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Poin</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-no-wrap text-sm text-gray-700">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm text-gray-900">{{ $log->points > 0 ? '+' . number_format($log->points) : number_format($log->points) }} </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm text-gray-700">{{ ucfirst($log->type) }}</td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm text-gray-600">@if($log->meta) {{ json_encode($log->meta) }} @endif</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat poin</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 bg-gray-50">
                @if(is_object($logs) && method_exists($logs, 'links'))
                    {{ $logs->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
