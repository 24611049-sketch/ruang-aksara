<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Poin - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-gradient-to-br from-green-50 via-white to-green-50">
    @php
        /** @var \Illuminate\Pagination\LengthAwarePaginator $logs */
        /** @var \Illuminate\Support\Collection $users */
    @endphp
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Riwayat Poin (Admin)</h1>

        <form method="GET" class="mb-4 flex gap-2 items-center">
            <label class="text-sm">Filter user:</label>
            <select name="user_id" class="border rounded px-3 py-2">
                <option value="">Semua</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                @endforeach
            </select>
            <button type="submit" class="px-3 py-2 bg-green-600 text-white rounded">Filter</button>
        </form>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Poin</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Meta</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $log->user->name }}<div class="text-xs text-gray-500">{{ $log->user->email }}</div></td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $log->points }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($log->type) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">@if($log->meta) {{ json_encode($log->meta) }} @endif</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada riwayat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</body>
</html>
