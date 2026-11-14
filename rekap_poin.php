
<?php
// rekap_poin.php

if (session_status() === PHP_SESSION_NONE) {
   
}

include 'config/koneksi.php';
include 'includes/cek_session.php';

$role = strtolower(trim($_SESSION['role'] ?? ''));
$current_page = basename($_SERVER['PHP_SELF']);

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// ===============================
// DASHBOARD SESUAI ROLE
// ===============================
$dashboard = ($role === 'guru') ? 'dashboard_guru.php' : 'dashboard.php';

// ===============================
// PENCARIAN
// ===============================
$cari = trim($_GET['cari'] ?? '');

$where = "";

if ($cari !== '') {
    $keyword = mysqli_real_escape_string($koneksi, $cari);

    $where = "WHERE nama_siswa LIKE '%$keyword%'
              OR nama_kelas LIKE '%$keyword%'";
}

// ===============================
// TOTAL SISWA MELANGGAR
// ===============================
$querySiswa = mysqli_query(
    $koneksi,
    "SELECT COUNT(DISTINCT siswa_id) AS total
     FROM t_pelanggaran_siswa
     WHERE siswa_id IS NOT NULL"
);

if (!$querySiswa) {
    die('Gagal menghitung siswa: ' . e(mysqli_error($koneksi)));
}

$totalSiswa = (int) mysqli_fetch_assoc($querySiswa)['total'];

// ===============================
// TOTAL KASUS PELANGGARAN
// ===============================
$queryPelanggaran = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM t_pelanggaran_siswa"
);

if (!$queryPelanggaran) {
    die('Gagal menghitung pelanggaran: ' . e(mysqli_error($koneksi)));
}

$totalPelanggaran = (int) mysqli_fetch_assoc($queryPelanggaran)['total'];

// ===============================
// TOTAL SELURUH POIN
// ===============================
$queryPoin = mysqli_query(
    $koneksi,
    "SELECT COALESCE(SUM(poin), 0) AS total
     FROM t_pelanggaran_siswa"
);

if (!$queryPoin) {
    die('Gagal menghitung poin: ' . e(mysqli_error($koneksi)));
}

$totalPoin = (int) mysqli_fetch_assoc($queryPoin)['total'];

// ===============================
// DATA REKAP POIN SISWA
// ===============================
$query = mysqli_query(
    $koneksi,
    "SELECT
        siswa_id,
        nama_siswa,
        kelas_id,
        nama_kelas,
        COUNT(*) AS jumlah_pelanggaran,
        SUM(poin) AS total_poin
     FROM t_pelanggaran_siswa
     $where
     GROUP BY siswa_id, nama_siswa, kelas_id, nama_kelas
     ORDER BY total_poin DESC, nama_siswa ASC"
);

if (!$query) {
    die('Query rekap poin gagal: ' . e(mysqli_error($koneksi)));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rekap Poin Pelanggaran Siswa</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        /* =========================
           LAYOUT
        ========================= */
        .layout-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
            align-items: stretch;
        }

        .dashboard-content {
            flex: 1;
            min-width: 0;
            padding: 30px;
            overflow-x: hidden;
        }

        /* =========================
           SIDEBAR
        ========================= */
        .sidebar {
            width: 260px;
            min-width: 260px;
            min-height: 100vh;
            flex-shrink: 0;
            background: #1a1d20;
            color: #fff;
            position: relative;
            z-index: 100;
        }

        .sidebar-inner {
            position: sticky;
            top: 0;
            padding: 15px;
        }

        .sidebar-header {
            padding: 15px 10px 12px;
        }

        .sidebar-header h5 {
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .sidebar-header p {
            color: #adb5bd;
            font-size: 12px;
            margin: 0;
        }

        .sidebar-divider {
            border-color: #495057;
            opacity: 0.7;
            margin: 15px 0;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #adb5bd;
            padding: 13px 12px;
            margin-bottom: 4px;
            border-radius: 9px;
            font-size: 14px;
            text-decoration: none;
            transition: 0.2s;
        }

        .sidebar .nav-link i {
            font-size: 17px;
            width: 18px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            background: #292e33;
            color: #fff;
        }

        .sidebar .nav-link.active {
            background: #0d6efd;
            color: #fff;
        }

        .sidebar .logout-link {
            color: #ff4d5a;
        }

        .sidebar .logout-link:hover {
            background: #292e33;
            color: #ff4d5a;
        }

        /* =========================
           CARD
        ========================= */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            padding: 15px 20px;
        }

        .breadcrumb-link {
            color: #0d6efd;
            text-decoration: none;
        }

        .breadcrumb-link:hover {
            text-decoration: underline;
        }

        /* =========================
           RESPONSIVE
        ========================= */
        @media (max-width: 768px) {
            .layout-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-width: 100%;
                min-height: auto;
            }

            .sidebar-inner {
                position: relative;
            }

            .dashboard-content {
                width: 100%;
                padding: 15px;
            }
        }

        /* =========================
           PRINT
        ========================= */
        @media print {
            body {
                background: #fff !important;
            }

            .sidebar,
            .d-print-none {
                display: none !important;
            }

            .layout-wrapper {
                display: block !important;
            }

            .dashboard-content {
                width: 100% !important;
                padding: 0 !important;
                overflow: visible !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                break-inside: avoid;
            }

            .card-header {
                color: #000 !important;
                background: #eee !important;
            }

            .table {
                font-size: 11px;
            }

            .badge {
                border: 1px solid #999;
                color: #000 !important;
                background: #fff !important;
            }
        }
    </style>
</head>

<body>

<div class="layout-wrapper">

    <!-- =========================
         SIDEBAR
    ========================= -->
<?php include 'layout/sidebar.php'; ?>

    <!-- =========================
         MAIN CONTENT
    ========================= -->
    <main class="dashboard-content">

        <h2 class="mb-1">Rekap Poin Pelanggaran Siswa</h2>

        <p class="text-muted mb-4">
            <a href="<?= e($dashboard) ?>" class="breadcrumb-link">
                Kembali ke Dashboard
            </a>
            / Rekap Poin
        </p>

        <!-- =========================
             STATISTIK
        ========================= -->
        <div class="row g-3 mb-4">

            <!-- TOTAL SISWA -->
            <div class="col-md-4">

                <div class="card bg-primary text-white h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-uppercase fw-semibold mb-1 opacity-75"
                                style="font-size: 0.8rem;">
                                Total Siswa Melanggar
                            </h6>

                            <h3 class="fw-bold mb-0">
                                <?= $totalSiswa ?>
                            </h3>
                        </div>

                        <div class="fs-2 opacity-50">
                            <i class="bi bi-people"></i>
                        </div>

                    </div>
                </div>
            </div>

            <!-- TOTAL PELANGGARAN -->
            <div class="col-md-4">

                <div class="card bg-danger text-white h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-uppercase fw-semibold mb-1 opacity-75"
                                style="font-size: 0.8rem;">
                                Total Kasus Pelanggaran
                            </h6>

                            <h3 class="fw-bold mb-0">
                                <?= $totalPelanggaran ?>
                            </h3>
                        </div>

                        <div class="fs-2 opacity-50">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>

                    </div>
                </div>
            </div>

            <!-- TOTAL POIN -->
            <div class="col-md-4">

                <div class="card bg-warning text-dark h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-uppercase fw-semibold mb-1 opacity-75"
                                style="font-size: 0.8rem;">
                                Total Seluruh Poin
                            </h6>

                            <h3 class="fw-bold mb-0">
                                <?= $totalPoin ?>
                            </h3>
                        </div>

                        <div class="fs-2 opacity-50">
                            <i class="bi bi-bar-chart"></i>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- =========================
             PENCARIAN
        ========================= -->
        <div class="card mb-4">

            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-search me-2"></i>
                    Pencarian Rekap Poin
                </h5>
            </div>

            <div class="card-body">

                <form method="GET" action="rekap_poin.php">

                    <div class="row g-3 align-items-end">

                        <div class="col-md-9">

                            <label class="form-label fw-semibold">
                                Cari Siswa
                            </label>

                            <input
                                type="text"
                                name="cari"
                                class="form-control"
                                placeholder="Nama siswa atau kelas..."
                                value="<?= e($cari) ?>"
                            >

                        </div>

                        <div class="col-md-3 d-flex gap-2">

                            <button type="submit" class="btn btn-primary w-50">
                                <i class="bi bi-search me-1"></i>
                                Cari
                            </button>

                            <a href="rekap_poin.php"
                               class="btn btn-secondary w-50 text-center">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Reset
                            </a>

                        </div>

                    </div>

                </form>

            </div>
        </div>

        <!-- =========================
             TABEL REKAP POIN
        ========================= -->
        <div class="card">

            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-table me-2"></i>
                    Data Rekap Poin Pelanggaran
                </h5>
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

                                        <td class="text-center">
                                            <?= $no++ ?>
                                        </td>

                                        <td class="fw-semibold">
                                            <?= e($data['nama_siswa']) ?>
                                        </td>

                                        <td>
                                            <?= e($data['nama_kelas']) ?>
                                        </td>

                                        <td class="text-center">
                                            <?= (int)$data['jumlah_pelanggaran'] ?>
                                        </td>

                                        <td class="text-center">

                                            <span class="badge bg-warning text-dark px-2 py-1">
                                                <?= (int)$data['total_poin'] ?> Poin
                                            </span>

                                        </td>

                                        <td class="text-center">

                                            <?php if ((int)$data['total_poin'] >= 50): ?>

                                                <span class="badge bg-danger">
                                                    Perlu Pembinaan
                                                </span>

                                            <?php else: ?>

                                                <span class="badge bg-success">
                                                    Terkendali
                                                </span>

                                            <?php endif; ?>

                                        </td>

                                        <td class="text-center d-print-none">

                                            <a
                                                href="riwayat.php?cari=<?= urlencode($data['nama_siswa'] ?? '') ?>"
                                                class="btn btn-sm btn-dark text-white px-3"
                                            >
                                                <i class="bi bi-eye me-1"></i>
                                                Detail
                                            </a>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        Data rekap poin siswa tidak ditemukan.
                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

        <!-- =========================
             TOMBOL CETAK
        ========================= -->
        <div class="mt-4 d-print-none">

            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer me-2"></i>
                Cetak Rekap Poin
            </button>

        </div>

    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
