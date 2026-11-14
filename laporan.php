
<?php
// laporan.php

if (session_status() === PHP_SESSION_NONE) {
    
}

include 'includes/cek_session.php';
include 'config/koneksi.php';

$role = strtolower(trim($_SESSION['role'] ?? ''));
$current_page = basename($_SERVER['PHP_SELF']);

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// ===============================
// AMBIL STATISTIK KARTU
// ===============================
$qSiswa = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM t_siswa");
$totalSiswa = $qSiswa ? (int)(mysqli_fetch_assoc($qSiswa)['total'] ?? 0) : 0;

$qGuru = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM t_guru");
$totalGuru = $qGuru ? (int)(mysqli_fetch_assoc($qGuru)['total'] ?? 0) : 0;

$qKelas = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM t_kelas");
$totalKelas = $qKelas ? (int)(mysqli_fetch_assoc($qKelas)['total'] ?? 0) : 0;

$qPelanggaran = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM t_pelanggaran_siswa");
$totalPelanggaran = $qPelanggaran ? (int)(mysqli_fetch_assoc($qPelanggaran)['total'] ?? 0) : 0;

// ===============================
// AMBIL DATA TABEL LAPORAN
// ===============================
$sql = "SELECT
            ps.id,
            s.nis,
            s.nama,
            pk.nama AS pelanggaran
        FROM t_pelanggaran_siswa AS ps
        JOIN t_siswa AS s
            ON ps.siswa_id = s.id
        JOIN t_pelanggaran_kategori AS pk
            ON ps.pelanggaran_id = pk.id
        ORDER BY ps.id DESC";

$data = mysqli_query($koneksi, $sql);

if (!$data) {
    die("Gagal mengambil data laporan: " . mysqli_error($koneksi));
}

$jumlahData = mysqli_num_rows($data);

$dashboard = ($role === 'guru')
    ? 'dashboard_guru.php'
    : 'dashboard.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Pelanggaran Siswa</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        /* Layout mengikuti Dashboard */
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

        /* Tampilan kartu */
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }

        .card-header {
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }

        .stat-card {
            border: none;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        /* Breadcrumb */
        .breadcrumb-link {
            color: #0d6efd;
            text-decoration: none;
        }

        .breadcrumb-link:hover {
            text-decoration: underline;
        }

        /* Tabel */
        .table th,
        .table td {
            vertical-align: middle;
        }

        /* DataTables */
        .dt-container {
            width: 100%;
        }

        .dt-search input,
        .dt-length select {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.375rem 0.5rem;
        }

        .dt-search input {
            margin-left: 0.5rem;
        }

        .dt-length select {
            margin: 0 0.4rem;
        }

        .dt-info {
            color: #6c757d;
            font-size: 14px;
        }

        /* Tampilan HP */
        @media (max-width: 768px) {
            .layout-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-width: 100%;
                min-height: auto;
            }

            .dashboard-content {
                width: 100%;
                padding: 15px;
            }
        }

        /* Tampilan cetak */
        @media print {
            .sidebar,
            .d-print-none {
                display: none !important;
            }

            .dashboard-content {
                width: 100% !important;
                padding: 0 !important;
            }

            body {
                background: white !important;
            }

            .card {
                box-shadow: none !important;
            }
        }
    </style>
</head>

<body>

<div class="layout-wrapper">

    <!-- SIDEBAR ASLI: TIDAK DIUBAH -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- KONTEN UTAMA -->
    <main class="dashboard-content">

        <!-- JUDUL HALAMAN -->
        <h2 class="mb-1">Laporan Pelanggaran Siswa</h2>

        <p class="text-muted mb-4">
            <a href="<?= e($dashboard) ?>" class="breadcrumb-link">
                Kembali ke Dashboard
            </a>
            / Laporan
        </p>

        <!-- KARTU STATISTIK -->
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card stat-card bg-primary text-white h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-semibold mb-1 opacity-75" style="font-size: 0.8rem;">
                                Total Siswa
                            </h6>
                            <h3 class="fw-bold mb-0"><?= $totalSiswa ?></h3>
                        </div>
                        <div class="fs-2 opacity-50">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card bg-success text-white h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-semibold mb-1 opacity-75" style="font-size: 0.8rem;">
                                Total Guru
                            </h6>
                            <h3 class="fw-bold mb-0"><?= $totalGuru ?></h3>
                        </div>
                        <div class="fs-2 opacity-50">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card bg-info text-white h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-semibold mb-1 opacity-75" style="font-size: 0.8rem;">
                                Total Kelas
                            </h6>
                            <h3 class="fw-bold mb-0"><?= $totalKelas ?></h3>
                        </div>
                        <div class="fs-2 opacity-50">
                            <i class="fas fa-school"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card bg-danger text-white h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-semibold mb-1 opacity-75" style="font-size: 0.8rem;">
                                Total Pelanggaran
                            </h6>
                            <h3 class="fw-bold mb-0"><?= $totalPelanggaran ?></h3>
                        </div>
                        <div class="fs-2 opacity-50">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- TOMBOL AKSI -->
        <div class="d-flex flex-wrap gap-2 mb-4 d-print-none">

            <a href="cetak_laporan.php"
               target="_blank"
               class="btn btn-primary">
                <i class="fas fa-print me-2"></i>
                Cetak Laporan
            </a>

            <a href="export_laporan.php"
               class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>
                Export Excel
            </a>

        </div>

        <!-- TABEL LAPORAN -->
        <div class="card">

            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Data Laporan Pelanggaran
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="tabelLaporan"
                           class="table table-bordered table-striped table-hover align-middle w-100">

                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Jenis Pelanggaran</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if ($jumlahData > 0): ?>

                                <?php $no = 1; ?>

                                <?php while ($row = mysqli_fetch_assoc($data)): ?>

                                    <tr>
                                        <td class="text-center">
                                            <?= $no++ ?>
                                        </td>

                                        <td>
                                            <?= e($row['nis'] ?? '-') ?>
                                        </td>

                                        <td class="fw-semibold">
                                            <?= e($row['nama'] ?? '-') ?>
                                        </td>

                                        <td>
                                            <?= e($row['pelanggaran'] ?? '-') ?>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Belum ada data pelanggaran siswa.
                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

    </main>
</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {

    const tabel = $('#tabelLaporan');

    const adaData = tabel.find('tbody tr').length > 0 &&
                    tabel.find('tbody tr td[colspan]').length === 0;

    if (adaData) {

        tabel.DataTable({
            pageLength: 10,

            lengthMenu: [5, 10, 25, 50, 100],

            ordering: true,

            searching: true,

            paging: true,

            order: [[0, 'asc']],

            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data laporan",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Data laporan tidak ditemukan",
                emptyTable: "Belum ada data laporan",

                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            }
        });

    }

});
</script>

</body>
</html>
