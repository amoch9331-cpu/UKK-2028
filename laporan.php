<?php
// laporan.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
$totalSiswa = mysqli_fetch_assoc($qSiswa)['total'] ?? 0;

$qGuru = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM t_guru");
$totalGuru = mysqli_fetch_assoc($qGuru)['total'] ?? 0;

$qKelas = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM t_kelas");
$totalKelas = mysqli_fetch_assoc($qKelas)['total'] ?? 0;

$qPelanggaran = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM t_pelanggaran_siswa");
$totalPelanggaran = mysqli_fetch_assoc($qPelanggaran)['total'] ?? 0;

// ===============================
// AMBIL DATA TABEL LAPORAN
// ===============================
$sql = "SELECT ps.id, s.nis, s.nama, pk.nama AS pelanggaran
        FROM t_pelanggaran_siswa AS ps
        JOIN t_siswa AS s ON ps.siswa_id = s.id
        JOIN t_pelanggaran_kategori AS pk ON ps.pelanggaran_id = pk.id
        ORDER BY ps.id DESC";
$data = mysqli_query($koneksi, $sql);

if (!$data) {
    die("Gagal mengambil data laporan: " . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
                <?php if ($role === "admin"): ?>
                    <li class="nav-item">
                        <a href="kelola_siswa.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'kelola_siswa.php') ? 'active' : '' ?>">
                            <span>👥</span> Data Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_guru.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'kelola_guru.php') ? 'active' : '' ?>">
                            <span>👩‍🏫</span> Data Guru
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_kelas.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'kelola_kelas.php') ? 'active' : '' ?>">
                            <span>🏫</span> Data Kelas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_tahun_ajaran.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'kelola_tahun_ajaran.php') ? 'active' : '' ?>">
                            <span>📅</span> Tahun Ajaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_wali_kelas.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'kelola_wali_kelas.php') ? 'active' : '' ?>">
                            <span>📋</span> Wali Kelas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="penempatan_siswa.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'penempatan_siswa.php') ? 'active' : '' ?>">
                            <span>📍</span> Penempatan Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_pelanggaran_kategori.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'kelola_pelanggaran_kategori.php') ? 'active' : '' ?>">
                            <span>⚠️</span> Kategori Pelanggaran
                        </a>
                    </li>
                <?php else: ?>
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
                <?php endif; ?>
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
            <h2 class="mb-1">Laporan Pelanggaran Siswa</h2>
            <p class="text-muted mb-4">
                <a href="dashboard.php" class="breadcrumb-link">Kembali ke Dashboard</a> | Laporan
            </p>

            <!-- Kartu Statistik -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1 opacity-75" style="font-size: 0.8rem;">Total Siswa</h6>
                                <h3 class="fw-bold mb-0"><?= $totalSiswa ?></h3>
                            </div>
                            <div class="fs-2 opacity-50"><i class="fas fa-users"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1 opacity-75" style="font-size: 0.8rem;">Total Guru</h6>
                                <h3 class="fw-bold mb-0"><?= $totalGuru ?></h3>
                            </div>
                            <div class="fs-2 opacity-50"><i class="fas fa-chalkboard-teacher"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1 opacity-75" style="font-size: 0.8rem;">Total Kelas</h6>
                                <h3 class="fw-bold mb-0"><?= $totalKelas ?></h3>
                            </div>
                            <div class="fs-2 opacity-50"><i class="fas fa-school"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1 opacity-75" style="font-size: 0.8rem;">Total Pelanggaran</h6>
                                <h3 class="fw-bold mb-0"><?= $totalPelanggaran ?></h3>
                            </div>
                            <div class="fs-2 opacity-50"><i class="fas fa-exclamation-triangle"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex gap-2 mb-4 d-print-none">
                <a href="cetak_laporan.php" target="_blank" class="btn btn-primary">
                    <i class="fas fa-print me-2"></i>Cetak Laporan
                </a>
                <a href="export_laporan.php" class="btn btn-success">
                    <i class="fas fa-file-excel me-2"></i>Export Excel
                </a>
            </div>

            <!-- Tabel Laporan -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Data Laporan Pelanggaran</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="tabelLaporan" class="table table-bordered table-striped table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Jenis Pelanggaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($data) > 0): ?>
                                    <?php $no = 1; ?>
                                    <?php while ($row = mysqli_fetch_assoc($data)): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td><?= e($row['nis'] ?? ''); ?></td>
                                            <td class="fw-semibold"><?= e($row['nama'] ?? ''); ?></td>
                                            <td><?= e($row['pelanggaran'] ?? ''); ?></td>
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
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            const tabel = $('#tabelLaporan');
            const adaData = tabel.find('tbody tr').length > 0 && tabel.find('tbody tr td[colspan]').length === 0;
            
            if (adaData) {
                tabel.DataTable({
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50, 100],
                    ordering: true,
                    searching: true,
                    p
