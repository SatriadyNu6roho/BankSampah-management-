<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Bank Sampah</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* 1. CUSTOM FONTS */
        @font-face {
            font-family: 'FerroRosso';
            src: url("{{ asset('fonts/FerroRosso.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'LTWaveUI';
            src: url("{{ asset('fonts/LTWaveUI-Medium.otf') }}") format('opentype');
            font-weight: normal;
            font-style: normal;
        }

        /* 2. BASE STYLING & WHITE SPACE UNTUK SCROLLING */
        body {
            background: url("{{ asset('images/walp.png') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            font-family: 'LTWaveUI', sans-serif !important;
            color: #1c3225;
            margin: 0;
            padding-bottom: 120px;
        }

        .brand-title {
            font-family: 'FerroRosso', cursive, sans-serif !important;
            letter-spacing: 0.5px;
            font-size: 1.25rem !important;
        }

        /* 3. GLASSMORPHISM CARDS */
        .glass-card {
            background: rgba(255, 255, 255, 0.60);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(30, 50, 38, 0.08);
            margin-bottom: 20px;
        }

        /* 4. HEADER NAVBAR ATAS */
        .top-header {
            background: rgba(255, 255, 255, 0.70);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.7);
            padding: 10px 16px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* 5. FLOATING BOTTOM NAV (3 TOMBOL INTERAKTIF) */
        .bottom-nav {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: auto;
            background: rgba(20, 38, 27, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 50px;
            padding: 6px 10px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            z-index: 1000;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        }

        .bottom-nav .nav-link {
            color: #a0b8a8;
            text-align: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.25s ease;
            border: none;
            background: transparent;
            padding: 0;
        }

        .bottom-nav .nav-link i {
            font-size: 1.2rem;
        }

        .bottom-nav .nav-link.active {
            background: #28a745;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(40, 167, 69, 0.45);
        }

        .bottom-nav .nav-link:hover:not(.active) {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
        }

        /* 6. TABEL & CARDS STYLING */
        .glass-table {
            color: #1c3225;
            margin-bottom: 0;
        }

        .glass-table th {
            background: rgba(42, 71, 54, 0.1) !important;
            color: #1b3325 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 10px 12px;
        }

        .glass-table td {
            background: rgba(255, 255, 255, 0.3) !important;
            color: #243d2e !important;
            border-color: rgba(255, 255, 255, 0.4);
            padding: 10px 12px;
            font-size: 0.85rem;
        }

        .user-mobile-card {
            background: rgba(255, 255, 255, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 14px;
            padding: 12px;
            margin-bottom: 10px;
        }

        /* BADGES */
        .badge-organik {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.6);
            color: #145a27;
            font-weight: 600;
            font-size: 0.7rem;
        }

        .badge-nonorganik {
            background: rgba(30, 58, 41, 0.15);
            border: 1px solid rgba(30, 58, 41, 0.5);
            color: #1e3a29;
            font-weight: 600;
            font-size: 0.7rem;
        }

        /* MODAL GLASS */
        .modal-glass {
            background: rgba(245, 248, 245, 0.96) !important;
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            color: #1c3225;
            box-shadow: 0 10px 40px rgba(0,0,0,0.25);
        }

        /* 7. PROGRESS CHART STYLING */
        .chart-container-box {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto;
        }

        .chart-center-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            pointer-events: none;
        }

        .chart-center-text .percent {
            font-size: 1.35rem;
            font-weight: 800;
            color: #1c3225;
            line-height: 1;
        }

        .chart-center-text .label {
            font-size: 0.62rem;
            color: #4a6353;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            margin-top: 3px;
        }

        .stat-chip {
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            padding: 10px 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .stat-chip:hover, .stat-chip.active {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        .dot-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        @media (min-width: 769px) {
            .mobile-only {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .desktop-only {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    @php
        $pctOrganik = $totalLaporan > 0 ? round(($totalOrganik / $totalLaporan) * 100, 1) : 0;
        $pctNonOrganik = $totalLaporan > 0 ? round(($totalNonOrganik / $totalLaporan) * 100, 1) : 0;
        $namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    @endphp

    <!-- TOP HEADER -->
    <div class="top-header shadow-sm mb-3">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-leaf text-success fs-4 me-2"></i>
                <span class="brand-title text-success mb-0">Bank Sampah</span>
            </div>

            <div class="d-flex align-items-center bg-white bg-opacity-60 px-2 py-1 rounded-pill border border-white">
                <div class="rounded-circle bg-success text-white p-1 me-1 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                    <i class="fa-solid fa-user-shield" style="font-size: 0.65rem;"></i>
                </div>
                <span class="fw-bold text-success" style="font-size: 0.75rem;">Pak RT</span>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="container-fluid px-2 px-md-4">
        
        <!-- NOTIFIKASI SUKSES -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show glass-card text-success border-0 mb-3 p-2 px-3" role="alert" style="font-size: 0.8rem;">
                <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close p-2" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- PROJECT PROGRESS CARD (RING DONUT) -->
        <div class="glass-card p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-success mb-0" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-chart-donut me-1"></i> Progres Pengumpulan Sampah
                </h6>
            </div>

            <div class="row align-items-center g-3">
                <!-- CHART DONUT RING -->
                <div class="col-12 col-md-5 col-lg-4 text-center">
                    <div class="chart-container-box">
                        <canvas id="wasteProgressChart"></canvas>
                        <div class="chart-center-text">
                            <div class="percent" id="centerPercentVal">{{ $pctOrganik }}%</div>
                            <div class="label" id="centerPercentLabel">Organik</div>
                        </div>
                    </div>
                </div>

                <!-- STAT CHIPS -->
                <div class="col-12 col-md-7 col-lg-8">
                    <div class="row g-2">
                        <!-- CHIP TOTAL -->
                        <div class="col-4">
                            <div class="stat-chip text-center" id="btnChipTotal" onclick="updateChartCenter('total')">
                                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                    <span class="dot-indicator" style="background: #1c3225;"></span>
                                    <small class="text-muted fw-bold" style="font-size: 0.65rem;">Total</small>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">{{ $totalLaporan }}</h6>
                                <small class="text-muted d-block mt-1" style="font-size: 0.6rem;">100% Data</small>
                            </div>
                        </div>

                        <!-- CHIP ORGANIK -->
                        <div class="col-4">
                            <div class="stat-chip text-center active" id="btnChipOrganik" onclick="updateChartCenter('organik')">
                                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                    <span class="dot-indicator" style="background: #48bb78;"></span>
                                    <small class="text-success fw-bold" style="font-size: 0.65rem;">Organik</small>
                                </div>
                                <h6 class="fw-bold mb-0 text-success" style="font-size: 0.95rem;">{{ $totalOrganik }}</h6>
                                <small class="text-success d-block mt-1 fw-bold" style="font-size: 0.6rem;">{{ $pctOrganik }}%</small>
                            </div>
                        </div>

                        <!-- CHIP NON-ORGANIK -->
                        <div class="col-4">
                            <div class="stat-chip text-center" id="btnChipNonOrganik" onclick="updateChartCenter('nonorganik')">
                                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                    <span class="dot-indicator" style="background: #1e3a29;"></span>
                                    <small class="fw-bold" style="font-size: 0.65rem; color: #1e3a29;">Non-Org</small>
                                </div>
                                <h6 class="fw-bold mb-0" style="font-size: 0.95rem; color: #1e3a29;">{{ $totalNonOrganik }}</h6>
                                <small class="d-block mt-1 fw-bold" style="font-size: 0.6rem; color: #1e3a29;">{{ $pctNonOrganik }}%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB CONTENTS -->
        <div class="tab-content" id="nav-tabContent">
            
            <!-- TAB 1: DAFTAR LAPORAN SAMPAH -->
            <div class="tab-pane fade show active" id="pills-laporan" role="tabpanel">
                <div class="glass-card p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h6 class="fw-bold mb-0 text-success"><i class="fa-solid fa-list me-1"></i>Setor Sampah Warga</h6>
                        <div class="d-flex gap-1">
                            <!-- TOMBOL EXCEL HYBRID (SUPPORT DESKTOP & MOBILE WEBVIBEW) -->
                            <button type="button" class="btn btn-sm btn-success rounded-pill px-2 py-1 shadow-sm" onclick="downloadExcelReport()" style="font-size: 0.7rem;">
                                <i class="fa-solid fa-file-excel me-1"></i> Excel
                            </button>
                            <button class="btn btn-sm btn-outline-success rounded-pill px-2 py-1" onclick="location.reload();" style="font-size: 0.7rem;">
                                <i class="fa-solid fa-rotate-right"></i>
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table glass-table align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Warga</th>
                                    <th>Jenis</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Berat / Hasil</th>
                                    <th class="text-center">Foto</th>
                                    <th>Hari & Waktu Setor</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $index => $report)
                                    @php
                                        $hariSetor = $report->created_at ? $namaHari[$report->created_at->dayOfWeek] : '-';
                                    @endphp
                                    <tr>
                                        <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                        <td><strong>{{ $report->user->name ?? 'Warga' }}</strong></td>
                                        <td>
                                            @if($report->jenis_sampah == 'Organik')
                                                <span class="badge badge-organik px-2 py-1 rounded-pill">Organik</span>
                                            @else
                                                <span class="badge badge-nonorganik px-2 py-1 rounded-pill">Non-Org</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($report->status == 'Disetujui')
                                                <span class="badge bg-success rounded-pill px-2 py-1" style="font-size:0.65rem;">Disetujui</span>
                                            @elseif($report->status == 'Ditolak')
                                                <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size:0.65rem;">Ditolak</span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill px-2 py-1" style="font-size:0.65rem;">Menunggu</span>
                                            @endif
                                        </td>
                                        <td class="text-center" style="font-size:0.75rem;">
                                            @if($report->status == 'Disetujui')
                                                <strong>{{ $report->bobot_kg }} Kg</strong><br>
                                                <small class="text-success fw-bold">+{{ $report->poin }} Pts | Rp {{ number_format($report->saldo_rp, 0, ',', '.') }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($report->foto)
                                                <a href="{{ asset('storage/uploads/sampah/' . $report->foto) }}" target="_blank">
                                                    <!-- OPTIMASI: LAZY LOADING & DECODING ASYNC -->
                                                    <img src="{{ asset('storage/uploads/sampah/' . $report->foto) }}" width="36" height="36" style="object-fit: cover; border-radius:8px;" alt="Foto" loading="lazy" decoding="async">
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td style="font-size: 0.75rem;">
                                            <strong class="text-success">{{ $hariSetor }}</strong>, {{ $report->created_at ? $report->created_at->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-success rounded-pill px-2 py-0 me-1" data-bs-toggle="modal" data-bs-target="#modalVerifikasi{{ $report->id }}" title="Verifikasi">
                                                <i class="fa-solid fa-check-to-slot"></i>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-0" data-bs-toggle="modal" data-bs-target="#modalHapusLaporan{{ $report->id }}" title="Hapus">
                                                <i class="fa-solid fa-trash" style="font-size:0.75rem;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center text-muted py-3 small">Belum ada laporan setor sampah.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: PROJECT ANALYTICS -->
            <div class="tab-pane fade" id="pills-analytics" role="tabpanel">
                <div class="glass-card p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="fw-bold mb-0 text-success"><i class="fa-solid fa-chart-column me-1"></i> Project Analytics</h6>
                            <small class="text-muted" style="font-size:0.7rem;">Analisis frekuensi warga menyubmit sampah per hari</small>
                        </div>
                        <span class="badge bg-success text-white rounded-pill px-2 py-1" style="font-size:0.65rem;">
                            Teramai: {{ $hariTeramai }} ({{ $maxSetor }} Setoran)
                        </span>
                    </div>

                    <div class="p-3 bg-white bg-opacity-50 rounded-4 border border-white mb-3">
                        <div style="height: 180px; position: relative;">
                            <canvas id="dailyAnalyticsChart"></canvas>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table glass-table align-middle">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th class="text-center">Jumlah Setor</th>
                                    <th class="text-center">Persentase</th>
                                    <th class="text-center">Status Frekuensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyStats as $hari => $jumlah)
                                    @php
                                        $pctHari = $totalLaporan > 0 ? round(($jumlah / $totalLaporan) * 100, 1) : 0;
                                    @endphp
                                    <tr>
                                        <td><strong>Hari {{ $hari }}</strong></td>
                                        <td class="text-center fw-bold">{{ $jumlah }} Setoran</td>
                                        <td class="text-center">{{ $pctHari }}%</td>
                                        <td class="text-center">
                                            @if($hari == $hariTeramai && $maxSetor > 0)
                                                <span class="badge bg-success rounded-pill px-2 py-1" style="font-size:0.65rem;">Paling Ramai</span>
                                            @elseif($jumlah > 0)
                                                <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-2 py-1" style="font-size:0.65rem;">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-25 text-dark rounded-pill px-2 py-1" style="font-size:0.65rem;">Sepi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: KELOLA AKUN WARGA -->
            <div class="tab-pane fade" id="pills-users" role="tabpanel">
                <div class="glass-card p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-success"><i class="fa-solid fa-users me-1"></i>Kelola Akun Warga</h6>
                        <button type="button" class="btn btn-sm btn-success rounded-pill px-3 py-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-user-plus me-1"></i> Tambah
                        </button>
                    </div>

                    <!-- TAMPILAN MOBILE: FORMAT CARD -->
                    <div class="mobile-only">
                        @foreach($users as $index => $u)
                            <div class="user-mobile-card d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex align-items-center gap-1 mb-1">
                                        <strong style="font-size: 0.85rem; color:#1c3225;">{{ $u->name }}</strong>
                                        <span class="badge {{ $u->role == 'admin' ? 'bg-danger' : 'bg-success' }} rounded-pill" style="font-size:0.6rem;">
                                            {{ strtoupper($u->role) }}
                                        </span>
                                    </div>
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">ID: <code class="text-dark">{{ $u->username }}</code></small>
                                </div>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:32px; height:32px;" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $u->id }}">
                                        <i class="fa-solid fa-key" style="font-size: 0.75rem;"></i>
                                    </button>

                                    <form action="/admin/users/delete/{{ $u->id }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:32px; height:32px;">
                                            <i class="fa-solid fa-trash" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- TAMPILAN DESKTOP: FORMAT TABEL -->
                    <div class="table-responsive desktop-only">
                        <table class="table glass-table align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Username / ID</th>
                                    <th>Nama Lengkap</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $index => $u)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                        <td><code>{{ $u->username }}</code></td>
                                        <td><strong>{{ $u->name }}</strong></td>
                                        <td class="text-center">
                                            <span class="badge {{ $u->role == 'admin' ? 'bg-danger' : 'bg-success' }} px-2 py-1 rounded-pill" style="font-size:0.65rem;">
                                                {{ strtoupper($u->role) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-warning text-dark rounded-pill px-2 py-0 me-1" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $u->id }}">
                                                <i class="fa-solid fa-key"></i> Edit
                                            </button>

                                            <form action="/admin/users/delete/{{ $u->id }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus user ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-0">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- FLOATING BOTTOM NAVIGATION -->
    <div class="bottom-nav nav" id="v-pills-tab" role="tablist">
        <button class="nav-link active" id="pills-laporan-tab" data-bs-toggle="pill" data-bs-target="#pills-laporan" type="button" role="tab" title="Data Setor Sampah">
            <i class="fa-solid fa-chart-pie"></i>
        </button>

        <button class="nav-link" id="pills-analytics-tab" data-bs-toggle="pill" data-bs-target="#pills-analytics" type="button" role="tab" title="Project Analytics Hari">
            <i class="fa-solid fa-chart-column"></i>
        </button>

        <button class="nav-link" id="pills-users-tab" data-bs-toggle="pill" data-bs-target="#pills-users" type="button" role="tab" title="Kelola Akun">
            <i class="fa-solid fa-users-gear"></i>
        </button>
    </div>

    <!-- MODAL POPUPS VERIFIKASI & HAPUS LAPORAN -->
    @foreach($reports as $report)
        <div class="modal fade" id="modalVerifikasi{{ $report->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-glass">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h6 class="modal-title fw-bold text-success"><i class="fa-solid fa-scale-balanced me-1"></i> Verifikasi Setoran Sampah</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/admin/reports/verify/{{ $report->id }}" method="POST">
                        @csrf
                        <div class="modal-body text-start">
                            <div class="p-2 mb-3 rounded-3 bg-white bg-opacity-50 border border-white" style="font-size: 0.8rem;">
                                <div><strong>Warga:</strong> {{ $report->user->name ?? 'Warga' }}</div>
                                <div><strong>Jenis Sampah:</strong> {{ $report->jenis_sampah }}</div>
                                <div><strong>Waktu Kirim:</strong> {{ $report->created_at ? $report->created_at->format('d/m/Y H:i') : '-' }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold mb-1">Status Verifikasi</label>
                                <select name="status" class="form-select form-select-sm rounded-pill px-3" required>
                                    <option value="Disetujui" {{ $report->status == 'Disetujui' ? 'selected' : '' }}>Disetujui (Hitung Poin & Saldo)</option>
                                    <option value="Ditolak" {{ $report->status == 'Ditolak' ? 'selected' : '' }}>Ditolak (Foto Tidak Valid)</option>
                                </select>
                            </div>

                            <div class="mb-2">
                                <label class="form-label text-muted small fw-bold mb-1">Berat Sampah (Kilogram / Kg)</label>
                                <input type="number" step="0.1" name="bobot_kg" class="form-control form-control-sm rounded-pill px-3" value="{{ $report->bobot_kg }}" placeholder="Contoh: 2.5" required>
                                <small class="text-muted d-block mt-1" style="font-size: 0.68rem;">
                                    *Organik: 1 kg = 10 Poin / Rp 1.000<br>
                                    *Non-Organik: 1 kg = 30 Poin / Rp 3.000
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-4">Simpan Verifikasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalHapusLaporan{{ $report->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content modal-glass text-center p-3">
                    <div class="modal-body pt-2">
                        <div class="text-warning mb-2">
                            <i class="fa-solid fa-triangle-exclamation fa-2x"></i>
                        </div>
                        <h6 class="fw-bold mb-1">Hapus Laporan?</h6>
                        <p class="text-muted small mb-3">Laporan dari <strong>{{ $report->user->name ?? 'Warga' }}</strong> akan dihapus permanen.</p>
                        <form action="/admin/reports/delete/{{ $report->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill fw-bold">Ya, Hapus</button>
                                <button type="button" class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- MODAL EDIT & TAMBAH USER -->
    @foreach($users as$u)
        <div class="modal fade" id="modalEditUser{{ $u->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-glass">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h6 class="modal-title fw-bold"><i class="fa-solid fa-key me-2"></i>Ubah User ({{ $u->username }})</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/admin/users/update/{{ $u->id }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-2 text-start">
                                <label class="form-label text-muted small fw-bold mb-1">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control form-control-sm rounded-pill px-3" value="{{ $u->name }}" required>
                            </div>
                            <div class="mb-2 text-start">
                                <label class="form-label text-muted small fw-bold mb-1">Password Baru (Opsional)</label>
                                <input type="password" name="password" class="form-control form-control-sm rounded-pill px-3" placeholder="Isi jika ganti password...">
                            </div>
                            <div class="mb-2 text-start">
                                <label class="form-label text-muted small fw-bold mb-1">Role Akses</label>
                                <select name="role" class="form-select form-select-sm rounded-pill px-3">
                                    <option value="user" {{ $u->role == 'user' ? 'selected' : '' }}>User (Warga)</option>
                                    <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>Admin (Pengelola)</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-4">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-glass">
                <div class="modal-header border-bottom-0 pb-0">
                    <h6 class="modal-title fw-bold"><i class="fa-solid fa-user-plus me-2"></i>Tambah User Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/admin/users/store" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-2 text-start">
                            <label class="form-label text-muted small fw-bold mb-1">Username / NIK ID</label>
                            <input type="text" name="username" class="form-control form-control-sm rounded-pill px-3" placeholder="Contoh: kk003" required>
                        </div>
                        <div class="mb-2 text-start">
                            <label class="form-label text-muted small fw-bold mb-1">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control form-control-sm rounded-pill px-3" placeholder="Contoh: Ahmad Yani" required>
                        </div>
                        <div class="mb-2 text-start">
                            <label class="form-label text-muted small fw-bold mb-1">Password Akses</label>
                            <input type="password" name="password" class="form-control form-control-sm rounded-pill px-3" placeholder="Ketik password..." required>
                        </div>
                        <div class="mb-2 text-start">
                            <label class="form-label text-muted small fw-bold mb-1">Role Akses</label>
                            <select name="role" class="form-select form-select-sm rounded-pill px-3">
                                <option value="user">User (Warga)</option>
                                <option value="admin">Admin (Pengelola)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-4">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SCRIPT UTAMA CHART & DOWNLOAD EXCEL -->
    <script>
        // 0. LOGIKA DOWNLOAD EXCEL HYBRID (SUPPORT PC & MOBILE WEVIEWER)
        function downloadExcelReport() {
            const fullDownloadUrl = window.location.origin + '/admin/reports/export-spreadsheet';

            // Jika dibuka di dalam WebViewer MIT App Inventor
            if (typeof AppInventor !== 'undefined' && AppInventor.setWebViewString) {
                // Kirim sinyal khusus ke App Inventor
                AppInventor.setWebViewString('DOWNLOAD_EXCEL:' + fullDownloadUrl);
            } else {
                // Jika dibuka di browser biasa (Desktop PC atau Chrome Mobile HP)
                fetch(fullDownloadUrl)
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal mendownload file');
                        return response.blob();
                    })
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        a.download = 'Laporan_Bank_Sampah_' + new Date().toISOString().slice(0, 10) + '.csv';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                    })
                    .catch(err => {
                        // Fallback jika fetch blob diblokir browser: buka langsung di tab baru
                        window.open(fullDownloadUrl, '_blank');
                    });
            }
        }

        // 1. CHART DONUT PROGRESS
        const totalVal = {{ $totalLaporan }};
        const organikVal = {{ $totalOrganik }};
        const nonOrganikVal = {{ $totalNonOrganik }};

        const pctOrganik = {{ $pctOrganik }};
        const pctNonOrganik = {{ $pctNonOrganik }};

        const ctxDonut = document.getElementById('wasteProgressChart').getContext('2d');
        const wasteChart = new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Organik', 'Non-Organik'],
                datasets: [{
                    data: [organikVal > 0 || nonOrganikVal > 0 ? organikVal : 1, nonOrganikVal],
                    backgroundColor: ['#48bb78', '#1e3a29'],
                    hoverBackgroundColor: ['#38a169', '#14271c'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '76%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(20, 38, 27, 0.92)',
                        bodyFont: { family: 'LTWaveUI', size: 10, weight: 'bold' },
                        padding: { top: 3, bottom: 3, left: 6, right: 6 },
                        cornerRadius: 6,
                        displayColors: false,
                        yAlign: 'bottom',
                        caretSize: 4,
                        callbacks: {
                            title: function() { return ''; },
                            label: function(context) {
                                let value = context.raw;
                                let percentage = totalVal > 0 ? Math.round((value / totalVal) * 100) : 0;
                                return `${percentage}%`;
                            }
                        }
                    }
                },
                onClick: (e, elements) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        if (index === 0) updateChartCenter('organik');
                        else updateChartCenter('nonorganik');
                    }
                }
            }
        });

        function updateChartCenter(type) {
            const valElem = document.getElementById('centerPercentVal');
            const labelElem = document.getElementById('centerPercentLabel');

            document.querySelectorAll('.stat-chip').forEach(c => c.classList.remove('active'));

            if (type === 'organik') {
                valElem.innerText = `${pctOrganik}%`;
                labelElem.innerText = 'Organik';
                document.getElementById('btnChipOrganik').classList.add('active');
            } else if (type === 'nonorganik') {
                valElem.innerText = `${pctNonOrganik}%`;
                labelElem.innerText = 'Non-Org';
                document.getElementById('btnChipNonOrganik').classList.add('active');
            } else {
                valElem.innerText = `${totalVal}`;
                labelElem.innerText = 'Total Setor';
                document.getElementById('btnChipTotal').classList.add('active');
            }
        }

        // 2. CHART BAR ANALISIS HARIAN
        const ctxBar = document.getElementById('dailyAnalyticsChart').getContext('2d');
        const dailyData = [
            {{ $dailyStats['Minggu'] }},
            {{ $dailyStats['Senin'] }},
            {{ $dailyStats['Selasa'] }},
            {{ $dailyStats['Rabu'] }},
            {{ $dailyStats['Kamis'] }},
            {{ $dailyStats['Jumat'] }},
            {{ $dailyStats['Sabtu'] }}
        ];

        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['M', 'S', 'S', 'R', 'K', 'J', 'S'],
                datasets: [{
                    label: 'Jumlah Setoran',
                    data: dailyData,
                    backgroundColor: function(context) {
                        const val = context.raw;
                        const max = Math.max(...dailyData);
                        return (val === max && max > 0) ? '#1e3a29' : '#48bb78';
                    },
                    borderRadius: 20,
                    borderSkipped: false,
                    barThickness: 22
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(20, 38, 27, 0.9)',
                        cornerRadius: 8,
                        callbacks: {
                            title: function(items) {
                                const fullDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                return fullDays[items[0].dataIndex];
                            },
                            label: function(context) {
                                return `${context.raw} Setoran Sampah`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'LTWaveUI', size: 11, weight: 'bold' }, color: '#1c3225' }
                    },
                    y: {
                        display: false,
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>