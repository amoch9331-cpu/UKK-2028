<?php
// catat_pelanggaran.php

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
// DAFTAR SISWA
// ===============================
$siswa = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama
     FROM t_siswa
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// ===============================
// DAFTAR PELANGGARAN
// ===============================
$pelanggaran = mysqli_query(
    $koneksi,
    "SELECT id, nama, poin, pelanggaran_kategori_id
     FROM t_pelanggaran
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// ===============================
// CEK QUERY
// ===============================
if (!$siswa || !$pelanggaran) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}

// ===============================
// DATA CATATAN PELANGGARAN
// ===============================
$sql = "SELECT
            ps.*,
            ta.nama AS tahun_ajaran,
            s.nis,
            s.nama AS nama_siswa,
            k.nama AS nama_kelas,
            p.nama AS nama_pelanggaran
        FROM t_pelanggaran_siswa ps
        LEFT JOIN t_tahun_ajaran ta
            ON ps.tahun_ajaran_id = ta.id
        LEFT JOIN t_siswa s
            ON ps.siswa_id = s.id
        LEFT JOIN t_kelas k
            ON ps.kelas_id = k.id
        LEFT JOIN t_pelanggaran p
            ON ps.pelanggaran_id = p.id
        ORDER BY ps.id DESC";

$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Gagal mengambil data pelanggaran: " . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catat Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
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
        .select2-container {
            width: 100% !important;
        }
        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px !important;
            padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
        .status-badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
            font-size: 0.875em;
            font-weight: 500;
        }
        .status-ditindaklanjuti {
            background: #cfe2ff;
            color: #084298;
        }
        .status-proses {
            background: #fff3cd;
            color: #664d03;
        }
        .status-selesai {
            background: #d1e7dd;
            color: #0f5132;
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
            <h2 class="mb-1">Catat Pelanggaran Siswa</h2>
            <p class="text-muted mb-4">
                <a href="dashboard.php" class="breadcrumb-link">Kembali ke Dashboard</a> | Catat Pelanggaran
            </p>

            <!-- Notifikasi Sukses -->
            <?php if (isset($_SESSION['pesan_sukses'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= e($_SESSION['pesan_sukses']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['pesan_sukses']); ?>
            <?php endif; ?>

            <!-- Notifikasi Error -->
            <?php if (isset($_SESSION['pesan_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= e($_SESSION['pesan_error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['pesan_error']); ?>
            <?php endif; ?>

            <!-- Form Catat Pelanggaran -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Form Catat Pelanggaran</h5>
                </div>
                <div class="card-body">
                    <form action="proses_simpan_pelanggaran.php" method="POST" id="formPelanggaran">
                        <div class="row">
                            <!-- Siswa -->
                            <div class="col-md-6 mb-3">
                                <label for="siswa_id" class="form-label">Siswa</label>
                                <select name="siswa_id" id="siswa_id" class="form-select" required>
                                    <option value=""></option>
                                    <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                                        <option value="<?= (int)$s['id'] ?>">
                                            <?= e($s['nis'] . ' - ' . $s['nama']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <small class="text-muted">Ketik NIS atau nama siswa untuk mencari.</small>
                            </div>

                            <!-- Tahun Ajaran -->
                            <div class="col-md-6 mb-3">
                                <label for="nama_tahun" class="form-label">Tahun Ajaran</label>
                                <input type="text" id="nama_tahun" class="form-control" placeholder="Otomatis sesuai siswa" readonly required>
                                <input type="hidden" name="tahun_ajaran_id" id="tahun_ajaran_id">
                            </div>

                            <!-- Kelas -->
                            <div class="col-md-6 mb-3">
                                <label for="nama_kelas" class="form-label">Kelas</label>
                                <input type="text" id="nama_kelas" class="form-control" placeholder="Otomatis sesuai siswa" readonly required>
                                <input type="hidden" name="kelas_id" id="kelas_id">
                            </div>

                            <!-- Jenis Pelanggaran -->
                            <div class="col-md-6 mb-3">
                                <label for="pelanggaran" class="form-label">Jenis Pelanggaran</label>
                                <select name="pelanggaran_id" id="pelanggaran" class="form-select" required>
                                    <option value="">-- Pilih Pelanggaran --</option>
                                    <?php while ($p = mysqli_fetch_assoc($pelanggaran)): ?>
                                        <option value="<?= (int)$p['id'] ?>"
                                                data-poin="<?= (int)$p['poin'] ?>"
                                                data-kategori="<?= (int)$p['pelanggaran_kategori_id'] ?>">
                                            <?= e($p['nama'] . ' (' . $p['poin'] . ' poin)') ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <input type="hidden" name="pelanggaran_kategori_id" id="kategori">
                            </div>

                            <!-- Tanggal -->
                            <div class="col-md-6 mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>

                            <!-- Poin -->
                            <div class="col-md-6 mb-3">
                                <label for="poin" class="form
