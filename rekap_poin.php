<?php
// rekap_poin.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config/koneksi.php';
include 'includes/cek_session.php';

$role = strtolower(trim($_SESSION['role'] ?? ''));
$current_page = basename($_SERVER['PHP_SELF']);

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$cari = trim($_GET['cari'] ?? '');
$where = "";
if ($cari !== '') {
    $keyword = mysqli_real_escape_string($koneksi, $cari);
    $where = "WHERE nama_siswa LIKE '%$keyword%' OR nama_kelas LIKE '%$keyword%'";
}

$querySiswa = mysqli_query($koneksi, "SELECT COUNT(DISTINCT siswa_id) AS total FROM t_pelanggaran_siswa WHERE siswa_id IS NOT NULL");
$totalSiswa = (int) mysqli_fetch_assoc($querySiswa)['total'];

$queryPelanggaran = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM t_pelanggaran_siswa");
$totalPelanggaran = (int) mysqli_fetch_assoc($queryPelanggaran)['total'];

$queryPoin = mysqli_query($koneksi, "SELECT COALESCE(SUM(poin), 0) AS total FROM t_pelanggaran_siswa");
$totalPoin = (int) mysqli_fetch_assoc($queryPoin)['total'];

$query = mysqli_query($koneksi, "
    SELECT siswa_id, nama_siswa, kelas_id, nama_kelas, COUNT(*) AS jumlah_pelanggaran, SUM(poin) AS total_poin 
    FROM t_pelanggaran_siswa $where GROUP BY siswa_id, nama_siswa, kelas_id, nama_kelas ORDER BY total_poin DESC, nama_siswa ASC
");

if (!$query) {
    die('Query rekap poin gagal: ' . mysqli_error($koneksi));
}

$dashboard = ($role === 'guru') ? 'dashboard_guru.php' : 'dashboard.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Poin Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background-color: #212529;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 100;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 0.6rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
        }
        .sidebar .nav-link:hover {
            background-color: #343a40;
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
        }
        .main-content {
            margin-left: 250px;
            padding: 1.5rem;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }
        .breadcrumb-link {
            color: #0d6efd;
            text-decoration: none;
        }
        .breadcrumb-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }
        @media print {
            .sidebar, .d-print-none { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <h5 class="text-white mb-4">PELANGGARAN SISWA</h5>
            <p class="text-secondary small mb-3">Role: <?= htmlspecialchars(ucfirst($role)) ?></p>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
                        <span>📊</span> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="catat_pelanggaran.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'catat_pelanggaran.php') ? 'active' : '' ?>">
                        <span>📝</span> Catat Pelanggaran
                    </a>
                </li>
                <li class="nav-item">
                    <a href="tindakan.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'tindakan.php') ? 'active' : '' ?>">
                        <span>⚖️</span> Tindakan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="riwayat.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'riwayat.php') ? 'active' : '' ?>">
                        <span>🕒</span> Riwayat
                    </a>
                </li>
                <li class="nav-item">
                    <a href="rekap_poin.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'rekap_poin.php') ? 'active' : '' ?>">
                        <span>📈</span> Rekap Poin
                    </a>
                </li>
                <li class="nav-item">
                    <a href="laporan.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'laporan.php') ? 'active' : '' ?>">
                        <span>📄</span> Laporan
                    </a>
                </li>
                <hr class="text-secondary my-3">
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger d-flex align-items-center gap-2">
                        <span>➡️</span> Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <h2 class="mb-1">Rekap Poin Pelanggaran Siswa</h2>
            <p class="text-muted mb-4">
                <a href="<?= e($dashboard) ?>" class="breadcrumb-link">Kembali ke Dashboard</a> | Rekap Poin
            </p>

            <!-- Statistik -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1 opacity-75" style="font-size: 0.8rem;">Total Siswa Melanggar</h6>
                                <h3 class="fw-bold mb-0"><?= $totalSiswa ?></h3>
                            </div>
                            <div class="fs-2 opacity-50"><i class="fas fa-users"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1 opacity-75" style="font-size: 0.8rem;">Total Kasus Pelanggaran</h6>
                                <h3 class="fw-bold mb-0"><?= $totalPelanggaran ?></h3>
                            </div>
                            <div class="fs-2 opacity-50"><i class="fas fa-exclamation-triangle"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-dark h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1 opacity-75" style="font-size: 0.8rem;">Total Seluruh Poin</h6>
                                <h3 class="fw-bold mb-0"><?= $totalPoin ?></h3>
                            </div>
                            <div class="fs-2 opacity-50"><i class="fas fa-chart-bar"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pencarian & Filter -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Pencarian Rekap Poin</h5>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-center">
                        <div class="col-md-9">
                            <label class="form-label small fw-bold text-muted">Cari Siswa</label>
                            <input type="text" name="cari" class="form-control" placeholder="Nama siswa atau kelas..." value="<?= e($cari) ?>">
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-50">Cari</button>
                            <a href="rekap_poin.php" class="btn btn-secondary w-50 text-center">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Data Rekap Poin Pelanggaran</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th class="text-center">Jumlah Pelanggaran</th>
                                    <th class="text-center">Total Poin</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center d-print-none" width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($query) > 0): ?>
                                    <?php $no = 1; ?>
                                    <?php while ($data = mysqli_fetch_assoc($query)): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td class="fw-semibold"><?= e($data['nama_siswa']) ?></td>
                                            <td><?= e($data['nama_kelas']) ?></td>
                                            <td class="text-center"><?= (int)$data['jumlah_pelanggaran'] ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark px-2 py-1">
                                                    <?= (int)$data['total_poin'] ?> Poin
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?php if ((int)$data['total_poin'] >= 50): ?>
                                                    <span class="badge bg-danger">Perlu Pembinaan</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Terkendali</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center d-print-none">
                                                <a href="riwayat.php?cari=<?= e($data['nama_siswa']) ?>" class="btn btn-sm btn-dark text-white px-3">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Data rekap poin siswa tidak ditemukan.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tombol Cetak -->
            <div class="mt-4 d-print-none">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print me-2"></i>Cetak Rekap Poin
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
