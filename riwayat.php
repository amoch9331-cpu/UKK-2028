
<?php
// riwayat.php

if (session_status() === PHP_SESSION_NONE) {
    
}

include 'includes/cek_session.php';
include 'config/koneksi.php';

$role = strtolower(trim($_SESSION['role'] ?? ''));
$current_page = basename($_SERVER['PHP_SELF']);

// ===============================
// PENCARIAN
// ===============================
$cari = trim($_GET['cari'] ?? '');
$status = trim($_GET['status'] ?? '');

$cari_sql = mysqli_real_escape_string($koneksi, $cari);
$status_sql = mysqli_real_escape_string($koneksi, $status);

// ===============================
// QUERY RIWAYAT
// ===============================
$sql = "SELECT *
        FROM t_pelanggaran_siswa
        WHERE 1=1";

if ($cari !== '') {
    $sql .= " AND (
        nama_siswa LIKE '%$cari_sql%'
        OR nama_pelanggaran LIKE '%$cari_sql%'
        OR nama_kelas LIKE '%$cari_sql%'
        OR nama_guru LIKE '%$cari_sql%'
    )";
}

if ($status !== '') {
    $sql .= " AND status = '$status_sql'";
}

$sql .= " ORDER BY tanggal DESC, id DESC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die('Gagal mengambil data: ' . htmlspecialchars(mysqli_error($koneksi), ENT_QUOTES, 'UTF-8'));
}

$jumlah = mysqli_num_rows($query);

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Pelanggaran Siswa</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- DATATABLES -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

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
           TABEL
        ========================= */
        .table th,
        .table td {
            vertical-align: middle;
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

        <h2 class="mb-1">Riwayat Pelanggaran Siswa</h2>

        <p class="text-muted mb-4">
            <a href="<?= $role === 'guru' ? 'dashboard_guru.php' : 'dashboard.php' ?>"
               class="breadcrumb-link">
                Kembali ke Dashboard
            </a>
            / Riwayat
        </p>

        <!-- =========================
             JUMLAH RIWAYAT
        ========================= -->
        <div class="card mb-4">
            <div class="card-body">

                <h6 class="text-muted">
                    Jumlah Riwayat Ditampilkan
                </h6>

                <h3 class="fw-bold text-primary mb-0">
                    <?= $jumlah ?> data
                </h3>

            </div>
        </div>

        <!-- =========================
             PESAN DATA KOSONG
        ========================= -->
        <?php if ($jumlah == 0 && ($cari != '' || $status != '')): ?>

            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle me-2"></i>
                Tidak ada riwayat pelanggaran yang sesuai dengan pencarian Anda.
                Silakan coba kriteria lain.
            </div>

        <?php endif; ?>

        <!-- =========================
             PENCARIAN DAN FILTER
        ========================= -->
        <div class="card mb-4">

            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-search me-2"></i>
                    Pencarian dan Filter Riwayat
                </h5>
            </div>

            <div class="card-body">

                <form method="GET" action="riwayat.php">

                    <div class="row g-3">

                        <!-- PENCARIAN -->
                        <div class="col-md-6">

                            <label for="cari" class="form-label">
                                Cari Riwayat
                            </label>

                            <input
                                type="text"
                                name="cari"
                                id="cari"
                                class="form-control"
                                placeholder="Nama siswa, pelanggaran, kelas, guru..."
                                value="<?= e($cari) ?>"
                            >

                        </div>

                        <!-- FILTER STATUS -->
                        <div class="col-md-3">

                            <label for="status" class="form-label">
                                Filter Status
                            </label>

                            <select name="status" id="status" class="form-select">

                                <option value="">Semua Status</option>

                                <option value="belum" <?= $status == 'belum' ? 'selected' : '' ?>>
                                    Belum Ditindak
                                </option>

                                <option value="selesai" <?= $status == 'selesai' ? 'selected' : '' ?>>
                                    Selesai
                                </option>

                            </select>

                        </div>

                        <!-- TOMBOL -->
                        <div class="col-md-3 d-flex align-items-end gap-2">

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>
                                Cari
                            </button>

                            <a href="riwayat.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Reset
                            </a>

                        </div>

                    </div>

                </form>

            </div>
        </div>

        <!-- =========================
             TABEL RIWAYAT
        ========================= -->
        <div class="card">

            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Data Riwayat Pelanggaran
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="tabelRiwayat"
                           class="table table-striped table-bordered table-hover align-middle w-100">

                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Poin</th>
                                <th>Guru Pencatat</th>
                                <th>Keterangan</th>
                                <th>Tindakan</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php while ($data = mysqli_fetch_assoc($query)): ?>

                                <tr>

                                    <td></td>

                                    <td>
                                        <?php
                                        if (!empty($data['tanggal'])) {
                                            echo e(date('d/m/Y', strtotime($data['tanggal'])));
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <?= e($data['nama_siswa'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= e($data['nama_kelas'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= e($data['nama_pelanggaran'] ?? '-') ?>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">
                                            <?= e($data['poin'] ?? 0) ?> Poin
                                        </span>
                                    </td>

                                    <td>
                                        <?= e($data['nama_guru'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= e($data['keterangan'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= e($data['tindakan'] ?? '-') ?>
                                    </td>

                                    <td class="text-center">

                                        <?php
                                        $status_data = strtolower(trim($data['status'] ?? ''));

                                        if ($status_data == 'selesai') {
                                            echo '<span class="badge bg-success">Selesai</span>';
                                        } elseif (
                                            $status_data == 'belum' ||
                                            $status_data == 'belum ditindak'
                                        ) {
                                            echo '<span class="badge bg-danger">Belum Ditindak</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">'
                                                . e($data['status'] ?? '-')
                                                . '</span>';
                                        }
                                        ?>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

    </main>

</div>

<!-- =========================
     JAVASCRIPT
========================= -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {

    $('#tabelRiwayat').DataTable({

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        searching: false,

        ordering: true,

        paging: true,

        order: [[1, 'desc']],

        columnDefs: [
            {
                orderable: false,
                searchable: false,
                targets: 0
            }
        ],

        drawCallback: function () {

            var api = this.api();
            var nomorAwal = api.page.info().start;

            api.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {

                cell.innerHTML = nomorAwal + i + 1;

            });

        },

        language: {

            search: "Cari di tabel:",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

            infoEmpty: "Tidak ada data riwayat",

            infoFiltered: "(difilter dari _MAX_ total data)",

            zeroRecords: "Riwayat pelanggaran tidak ditemukan",

            emptyTable: "Belum ada riwayat pelanggaran",

            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }

        }

    });

});
</script>

</body>
</html>
