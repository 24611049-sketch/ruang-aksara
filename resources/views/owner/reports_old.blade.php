<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Owner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* BACKGROUND SAMA DENGAN HALAMAN LAIN */
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

        /* SIDEBAR STYLING */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease, width 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
        }

        .sidebar.collapsed {
            transform: translateX(-250px);
            width: 250px;
        }

        .sidebar-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
        }

        .sidebar-toggle.visible {
            left: 265px;
        }

        .sidebar .position-sticky {
            padding-top: 80px !important;
        }

        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 1rem 1.5rem;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
            margin: 0.5rem 0;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #3498db;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background-color: rgba(52, 152, 219, 0.2);
            border-left-color: #3498db;
            color: #fff;
        }

        /* MAIN CONTENT */
        main {
            margin-left: 250px;
            margin-top: 0;
            transition: margin-left 0.3s ease;
            background: rgba(255, 255, 255, 0.97) !important;
            backdrop-filter: blur(10px);
            border-radius: 0;
            min-height: 100vh;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.05);
        }

        main.expanded {
            margin-left: 0;
        }

        /* HEADER */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .page-header h1 {
            margin: 0;
            font-weight: 700;
            font-size: 2rem;
        }

        .page-header .text-muted {
            color: rgba(255, 255, 255, 0.8) !important;
            font-size: 0.9rem;
        }

        /* TABS */
        .nav-tabs {
            border-bottom: 2px solid #e3e6f0;
            gap: 0.5rem;
        }

        .nav-tabs .nav-link {
            color: #636363;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 1rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px 8px 0 0;
        }

        .nav-tabs .nav-link:hover {
            color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
            border-bottom-color: #667eea;
        }

        .nav-tabs .nav-link.active {
            color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
            border-bottom-color: #667eea;
        }
        
        /* KONTEN DENGAN BACKGROUND TRANSPARAN */
        .tab-pane {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <!-- Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle" title="Toggle Sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="sidebar">
                <div class="position-sticky">
                    <div style="padding: 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); margin-bottom: 1rem;">
                        <h5 class="text-white mb-0">
                            <i class="fas fa-chart-bar"></i> Menu
                        </h5>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt" style="margin-right: 0.75rem; width: 20px;"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('owner.reports') }}">
                                <i class="fas fa-chart-line" style="margin-right: 0.75rem; width: 20px;"></i>
                                <span>Laporan</span>
                            </a>
                        </li>
                        <li class="nav-item mt-4 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
                            <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                                @csrf
                                <button type="submit" class="nav-link w-100" style="text-align: left;">
                                    <i class="fas fa-sign-out-alt" style="margin-right: 0.75rem; width: 20px;"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" id="mainContent">
                <div class="page-header">
                    <h1>
                        <i class="fas fa-chart-bar"></i> Laporan Owner
                    </h1>
                    <div class="text-muted mt-2">
                        Terakhir update: {{ now()->translatedFormat('l, d F Y H:i') }}
                    </div>
                </div>

                <!-- Tab Navigation -->
                <ul class="nav nav-tabs mb-5" id="reportTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="penjualan-tab" data-bs-toggle="tab" data-bs-target="#penjualan" type="button" role="tab">
                            <i class="fas fa-shopping-cart"></i> Penjualan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="peminjaman-tab" data-bs-toggle="tab" data-bs-target="#peminjaman" type="button" role="tab">
                            <i class="fas fa-book-open"></i> Peminjaman
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="presensi-tab" data-bs-toggle="tab" data-bs-target="#presensi" type="button" role="tab">
                            <i class="fas fa-user-check"></i> Presensi
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pengguna-tab" data-bs-toggle="tab" data-bs-target="#pengguna" type="button" role="tab">
                            <i class="fas fa-users"></i> Pengguna
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="reportTabsContent">
                    <!-- Dashboard Tab -->
                    <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
                <div class="row mb-5">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">
                                            Total Pendapatan
                                        </div>
                                        <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">
                                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                        </div>
                                        <div class="text-muted mt-2" style="font-size: 0.85rem;">
                                            <i class="fas fa-check-circle text-success"></i> {{ $totalBooksSold }} buku terjual
                                        </div>
                                    </div>
                                    <div style="font-size: 2.5rem; opacity: 0.1; color: #667eea;">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">
                                            Total Pengguna
                                        </div>
                                        <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">
                                            {{ $totalUsers }}
                                        </div>
                                        <div class="text-muted mt-2" style="font-size: 0.85rem;">
                                            <i class="fas fa-users text-info"></i> Terdaftar di sistem
                                        </div>
                                    </div>
                                    <div style="font-size: 2.5rem; opacity: 0.1; color: #1cc88a;">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">
                                            Buku Terjual
                                        </div>
                                        <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">
                                            {{ $totalBooksSold }}
                                        </div>
                                        <div class="text-muted mt-2" style="font-size: 0.85rem;">
                                            <i class="fas fa-box text-primary"></i> Unit terjual
                                        </div>
                                    </div>
                                    <div style="font-size: 2.5rem; opacity: 0.1; color: #36b9cc;">
                                        <i class="fas fa-book"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">
                                            Pesanan Pending
                                        </div>
                                        <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">
                                            {{ $pendingOrders }}
                                        </div>
                                        <div class="text-muted mt-2" style="font-size: 0.85rem;">
                                            <i class="fas fa-clock text-warning"></i> Menunggu konfirmasi
                                        </div>
                                    </div>
                                    <div style="font-size: 2.5rem; opacity: 0.1; color: #f6c23e;">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row mb-5">
                    <div class="col-md-8 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom pb-3">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-line" style="color: #667eea;"></i>
                                    Grafik Pendapatan 6 Bulan Terakhir
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="revenueChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom pb-3">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-pie" style="color: #667eea;"></i>
                                    Distribusi Kategori Buku
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="categoryChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reports Content -->
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom pb-3">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-table" style="color: #667eea;"></i>
                                    Ringkasan Laporan Bulanan
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm">
                                        <thead class="table-light">
                                            <tr style="border-top: 2px solid #667eea;">
                                                <th style="color: #2c3e50; font-weight: 600;">Bulan</th>
                                                <th style="color: #2c3e50; font-weight: 600;">Pendapatan</th>
                                                <th style="color: #2c3e50; font-weight: 600;">Buku Terjual</th>
                                                <th style="color: #2c3e50; font-weight: 600;">Pengguna Baru</th>
                                                <th style="color: #2c3e50; font-weight: 600;">Total Pesanan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($monthlyReports as $report)
                                                <tr>
                                                    <td>{{ $report['month'] }}</td>
                                                    <td>Rp {{ number_format($report['revenue'], 0, ',', '.') }}</td>
                                                    <td>{{ $report['books_sold'] }}</td>
                                                    <td>{{ $report['new_users'] }}</td>
                                                    <td>{{ $report['orders'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-secondary">
                                            <tr>
                                                <th>Total</th>
                                                <th>Rp {{ number_format(array_sum(array_column($monthlyReports, 'revenue')), 0, ',', '.') }}</th>
                                                <th>{{ array_sum(array_column($monthlyReports, 'books_sold')) }}</th>
                                                <th>{{ array_sum(array_column($monthlyReports, 'new_users')) }}</th>
                                                <th>{{ array_sum(array_column($monthlyReports, 'orders')) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info Section -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-trophy"></i>
                                    5 Buku Terlaris
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Buku</th>
                                                <th>Terjual</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($bestsellers as $order)
                                                <tr>
                                                    <td>{{ $order->book->judul ?? 'Buku Tidak Ditemukan' }}</td>
                                                    <td class="text-end">{{ $order->total_sold }} unit</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center">Belum ada data penjualan</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-bar"></i>
                                    Status Pesanan
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="orderStatusChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-warning">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Stok Menipis
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Buku</th>
                                                <th>Stok</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($lowStockBooks as $book)
                                                <tr class="{{ $book->stok == 0 ? 'table-danger' : 'table-warning' }}">
                                                    <td>{{ $book->judul }}</td>
                                                    <td class="text-end">{{ $book->stok }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center">Semua stok aman</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>

                    <!-- Penjualan Tab -->
                    <div class="tab-pane fade" id="penjualan" role="tabpanel">
                        <div class="row mb-5">
                            <div class="col-md-3 mb-4">
                                <div class="card stat-card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem;">
                                                    Total Pendapatan
                                                </div>
                                                <div style="font-size: 1.5rem; font-weight: 700; color: #2c3e50;">
                                                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                                </div>
                                            </div>
                                            <div style="font-size: 2rem; opacity: 0.1; color: #667eea;">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card stat-card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem;">
                                                    Total Terjual
                                                </div>
                                                <div style="font-size: 1.5rem; font-weight: 700; color: #2c3e50;">
                                                    {{ $totalBooksSold }} unit
                                                </div>
                                            </div>
                                            <div style="font-size: 2rem; opacity: 0.1; color: #1cc88a;">
                                                <i class="fas fa-box"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-5">
                            <div class="col-12 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom pb-3">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-chart-bar" style="color: #667eea;"></i> Grafik Penjualan
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="salesChart" height="80"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom pb-3">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-trophy" style="color: #667eea;"></i> 5 Buku Terlaris
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="color: #2c3e50; font-weight: 600;">Buku</th>
                                                        <th style="color: #2c3e50; font-weight: 600;">Terjual</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($bestsellers as $order)
                                                        <tr>
                                                            <td>{{ $order->book->judul ?? 'Buku Tidak Ditemukan' }}</td>

                                    <div class="card-header bg-white border-bottom pb-3">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-trophy" style="color: #667eea;"></i> 5 Buku Terlaris
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="color: #2c3e50; font-weight: 600;">Buku</th>
                                                        <th style="color: #2c3e50; font-weight: 600;">Terjual</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($bestsellers as $order)
                                                        <tr>
                                                            <td>{{ $order->book->judul ?? 'Buku Tidak Ditemukan' }}</td>
                                                            <td class="text-end">{{ $order->total_sold }} unit</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2" class="text-center">Belum ada data penjualan</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom pb-3">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-chart-bar" style="color: #667eea;"></i> Status Pesanan
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="orderStatusChart" width="400" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom pb-3">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-exclamation-triangle" style="color: #f6c23e;"></i> Stok Menipis
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="color: #2c3e50; font-weight: 600;">Buku</th>
                                                        <th style="color: #2c3e50; font-weight: 600;">Stok</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($lowStockBooks as $book)
                                                        <tr class="{{ $book->stok == 0 ? 'table-danger' : 'table-warning' }}">
                                                            <td>{{ $book->judul }}</td>
                                                            <td class="text-end">{{ $book->stok }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2" class="text-center">Semua stok aman</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Peminjaman Tab -->
                    <div class="tab-pane fade" id="peminjaman" role="tabpanel">
                        <div class="row mb-5">
                            <div class="col-md-3 mb-4">
                                <div class="card stat-card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem;">
                                                    Peminjaman Aktif
                                                </div>
                                                <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">
                                                    {{ $activeLoans }}
                                                </div>
                                            </div>
                                            <div style="font-size: 2.5rem; opacity: 0.1; color: #36b9cc;">
                                                <i class="fas fa-book-open"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card stat-card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem;">
                                                    Sudah Dikembalikan
                                                </div>
                                                <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">
                                                    {{ $returnedLoans }}
                                                </div>
                                            </div>
                                            <div style="font-size: 2.5rem; opacity: 0.1; color: #1cc88a;">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card stat-card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem;">
                                                    Overdue
                                                </div>
                                                <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">
                                                    {{ $overdueLoans }}</div>
                                            </div>
                                            <div style="font-size: 2.5rem; opacity: 0.1; color: #ff6b6b;">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-12 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom pb-3">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-list" style="color: #667eea;"></i> 10 Peminjam Terbanyak
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-light">
                                                    <tr style="border-top: 2px solid #667eea;">
                                                        <th style="color: #2c3e50; font-weight: 600;">Nama Pengguna</th>
                                                        <th style="color: #2c3e50; font-weight: 600;">Email</th>
                                                        <th style="color: #2c3e50; font-weight: 600;">Total Pinjam</th>
                                                        <th style="color: #2c3e50; font-weight: 600;">Pinjam Terakhir</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($loanByUsers as $loan)
                                                        <tr>
                                                            <td>{{ $loan->user ? $loan->user->name : 'User Tidak Ditemukan' }}</td>
                                                            <td>{{ $loan->user ? $loan->user->email : '-' }}</td>
                                                            <td><span class="badge bg-primary">{{ $loan->total_loans }}</span></td>
                                                            <td>{{ $loan->last_loan_date ? \Carbon\Carbon::parse($loan->last_loan_date)->translatedFormat('d F Y') : '-' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center">Belum ada data peminjaman</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Presensi Tab -->
                    <div class="tab-pane fade" id="presensi" role="tabpanel">
                        <div class="row mb-5">
                            <div class="col-md-3 mb-4">
                                <div class="card stat-card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem;">
                                                    Hadir
                                                </div>
                                                <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">
                                                    {{ $totalPresensi }}
                                                </div>
                                                <div class="text-muted mt-2" style="font-size: 0.85rem;">
                                                    ({{ number_format(($totalPresensi / ($totalAttendance ?? 1)) * 100, 1) }}%)
                                                </div>
                                            </div>
                                            <div style="font-size: 2.5rem; opacity: 0.1; color: #1cc88a;">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card stat-card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem;">
                                                    Alpha
                                                </div>
                                                <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">
                                                    {{ $totalAlpha }}
                                                </div>
                                                <div class="text-muted mt-2" style="font-size: 0.85rem;">
                                                    ({{ number_format(($totalAlpha / ($totalAttendance ?? 1)) * 100, 1) }}%)
                                                </div>
                                            </div>
                                            <div style="font-size: 2.5rem; opacity: 0.1; color: #ff6b6b;">
                                                <i class="fas fa-times"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card stat-card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem;">
                                                    Izin
                                                </div>
                                                <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">
                                                    {{ $totalIzin }}
                                                </div>
                                                <div class="text-muted mt-2" style="font-size: 0.85rem;">
                                                    ({{ number_format(($totalIzin / ($totalAttendance ?? 1)) * 100, 1) }}%)
                                                </div>
                                            </div>
                                            <div style="font-size: 2.5rem; opacity: 0.1; color: #f6c23e;">
                                                <i class="fas fa-info-circle"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom pb-3">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-table" style="color: #667eea;"></i> Rincian Presensi
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-light">
                                                    <tr style="border-top: 2px solid #667eea;">
                                                        <th style="color: #2c3e50; font-weight: 600;">Status</th>
                                                        <th style="color: #2c3e50; font-weight: 600;">Jumlah</th>
                                                        <th style="color: #2c3e50; font-weight: 600;">Persentase</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><span class="badge bg-success">Hadir</span></td>
                                                        <td>{{ $totalPresensi }}</td>
                                                        <td>{{ number_format(($totalPresensi / ($totalAttendance ?? 1)) * 100, 1) }}%</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-danger">Alpha</span></td>
                                                        <td>{{ $totalAlpha }}</td>
                                                        <td>{{ number_format(($totalAlpha / ($totalAttendance ?? 1)) * 100, 1) }}%</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-warning">Izin</span></td>
                                                        <td>{{ $totalIzin }}</td>
                                                        <td>{{ number_format(($totalIzin / ($totalAttendance ?? 1)) * 100, 1) }}%</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pengguna Tab -->
                    <div class="tab-pane fade" id="pengguna" role="tabpanel">
                        <div class="row mb-5">
                            <div class="col-md-4 mb-4">
                                <div class="card stat-card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem;">
                                                    Admin
                                                </div>
                                                <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">
                                                    {{ $adminUsers }}
                                                </div>
                                            </div>
                                            <div style="font-size: 2.5rem; opacity: 0.1; color: #ff6b6b;">
                                                <i class="fas fa-shield-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card stat-card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem;">
                                                    Owner
                                                </div>
                                                <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">
                                                    {{ $ownerUsers }}
                                                </div>
                                            </div>
                                            <div style="font-size: 2.5rem; opacity: 0.1; color: #36b9cc;">
                                                <i class="fas fa-crown"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card stat-card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.75rem;">
                                                    Pengguna Biasa
                                                </div>
                                                <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">
                                                    {{ $normalUsers }}
                                                </div>
                                            </div>
                                            <div style="font-size: 2.5rem; opacity: 0.1; color: #75b9cc;">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom pb-3">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-pie-chart" style="color: #667eea;"></i> Distribusi Pengguna
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="userChart" width="400" height="150"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom pb-3">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-info-circle" style="color: #667eea;"></i> Total Pengguna
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <div style="font-size: 3rem; font-weight: 700; color: #667eea;">
                                                {{ $totalUsers }}
                                            </div>
                                            <div class="text-muted mt-3">
                                                Pengguna terdaftar dalam sistem
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
                                                <div class="card bg-danger text-white">
                                                    <div class="card-body">
                                                        <h6>Terlambat</h6>
                                                        <h3>{{ $overdueLoans }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <h6 class="mt-4 mb-3"><i class="fas fa-list"></i> Peminjam Terbanyak</h6>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Nama Pengguna</th>
                                                        <th>Total Peminjaman</th>
                                                        <th>Peminjaman Terakhir</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($loanByUsers as $loan)
                                                        <tr>
                                                            <td>
                                                                <strong>{{ $loan->user->name ?? 'Pengguna Tidak Ditemukan' }}</strong>
                                                                <br>
                                                                <small class="text-muted">{{ $loan->user->email ?? '-' }}</small>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-primary">{{ $loan->total_loans }} kali</span>
                                                            </td>
                                                            <td>
                                                                {{ $loan->last_loan_date ? \Carbon\Carbon::parse($loan->last_loan_date)->translatedFormat('d F Y') : '-' }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted">
                                                                <i class="fas fa-inbox"></i> Belum ada data peminjaman
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Presensi Tab -->
                    <div class="tab-pane fade" id="presensi" role="tabpanel">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"><i class="fas fa-user-check"></i> Laporan Presensi</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <div class="card bg-success text-white">
                                                    <div class="card-body">
                                                        <h6>Hadir</h6>
                                                        <h3>{{ $totalAttendance }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card bg-danger text-white">
                                                    <div class="card-body">
                                                        <h6>Alpha</h6>
                                                        <h3>{{ $totalAlpha }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card bg-warning text-white">
                                                    <div class="card-body">
                                                        <h6>Izin</h6>
                                                        <h3>{{ $totalIzin }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card bg-info text-white">
                                                    <div class="card-body">
                                                        <h6>Total Presensi</h6>
                                                        <h3>{{ $totalPresensi }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Status</th>
                                                        <th>Total</th>
                                                        <th>Persentase</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><span class="badge bg-success">Hadir</span></td>
                                                        <td>{{ $totalAttendance }}</td>
                                                        <td>{{ $totalPresensi > 0 ? round(($totalAttendance / $totalPresensi) * 100, 1) : 0 }}%</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-danger">Alpha</span></td>
                                                        <td>{{ $totalAlpha }}</td>
                                                        <td>{{ $totalPresensi > 0 ? round(($totalAlpha / $totalPresensi) * 100, 1) : 0 }}%</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-warning text-dark">Izin</span></td>
                                                        <td>{{ $totalIzin }}</td>
                                                        <td>{{ $totalPresensi > 0 ? round(($totalIzin / $totalPresensi) * 100, 1) : 0 }}%</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pengguna Tab -->
                    <div class="tab-pane fade" id="pengguna" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"><i class="fas fa-users"></i> Statistik Pengguna</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Total Pengguna:</strong> {{ $totalUsers }}</p>
                                        <p><strong>Admin:</strong> {{ $adminUsers }}</p>
                                        <p><strong>Owner:</strong> {{ $ownerUsers }}</p>
                                        <p><strong>Pengguna Biasa:</strong> {{ $normalUsers }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"><i class="fas fa-chart-pie"></i> Distribusi Pengguna</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="userChart" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart - DATA DINAMIS
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json($revenueChartLabels),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @json($revenueChartData),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });

            // Category Chart - DATA DINAMIS
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            const categoryChart = new Chart(categoryCtx, {
                type: 'pie',
                data: {
                    labels: @json($categoryLabels),
                    datasets: [{
                        data: @json($categoryCounts),
                        backgroundColor: [
                            'rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)', 'rgb(153, 102, 255)', 'rgb(255, 159, 64)',
                            'rgb(201, 203, 207)', 'rgb(255, 99, 132)', 'rgb(54, 162, 235)'
                        ]
                    }]
                }
            });

            // Order Status Chart - DATA DINAMIS
            const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
            const orderStatusChart = new Chart(orderStatusCtx, {
                type: 'bar',
                data: {
                    labels: @json($orderStatusData->pluck('status')),
                    datasets: [{
                        label: 'Jumlah Pesanan',
                        data: @json($orderStatusData->pluck('total')),
                        backgroundColor: 'rgba(54, 162, 235, 0.8)'
                    }]
                }
            });
        });
    </script>

    <script>
        // Sales Chart dengan data dinamis dari server
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            new Chart(salesCtx, {
                type: 'bar',
                data: {
                    labels: @json($revenueChartLabels),
                    datasets: [{
                        label: 'Penjualan (Rp)',
                        data: @json($revenueChartData),
                        backgroundColor: 'rgba(76, 175, 80, 0.5)',
                        borderColor: 'rgb(76, 175, 80)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { 
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        // User Distribution Chart dengan data dinamis
        const userCtx = document.getElementById('userChart');
        if (userCtx) {
            new Chart(userCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Admin', 'Owner', 'Pengguna Biasa'],
                    datasets: [{
                        data: [{{ $adminUsers }}, {{ $ownerUsers }}, {{ $normalUsers }}],
                        backgroundColor: ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(75, 192, 192)'],
                        borderColor: ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(75, 192, 192)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true
                }
            });
        }
    </script>

    <!-- Sidebar Toggle Script -->
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            sidebarToggle.classList.toggle('visible');
        });

        // Close sidebar when clicking on a link (mobile)
        const navLinks = sidebar.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                    sidebarToggle.classList.add('visible');
                }
            });
        });
    </script>
</body>
</html>
