<?php
// dashboard.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/cek_session.php';
include 'config/koneksi.php';

$role = strtolower(trim($_SESSION['role'] ?? ''));
$current_page = basename($_SERVER['PHP_SELF']);

// =====================================
// DATA STATISTIK
// =====================================

// Jumlah siswa
$query_siswa = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM t_siswa");
if (!$query_siswa) die("Gagal mengambil data siswa: " . mysqli_error($koneksi));
$jml_siswa = mysqli_fetch_assoc($query_siswa)['total'];

// Jumlah guru
$query_guru = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM t_guru");
if (!$query_guru) die("Gagal mengambil data guru: " . mysqli_error($koneksi));
$jml_guru = mysqli_fetch_assoc($query_guru)['total'];

// Jumlah kelas
$query_kelas = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM t_kelas");
if (!$query_kelas) die("Gagal mengambil data kelas: " . mysqli_error($koneksi));
$jml_kelas = mysqli_fetch_assoc($query_kelas)['total'];

// =====================================
// DATA GRAFIK
// =====================================
$bulan_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$data_pelanggaran = [12, 19, 8, 15, 22, 10, 14, 25, 18, 20, 11, 9];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Informasi Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .stat-card {
            transition: transform 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }
        .icon-box {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            font-size: 1.5rem;
            background-color: #e9ecef;
            color: #495057;
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
            <h2 class="mb-1">Dashboard</h2>
            <p class="text-muted mb-4">Selamat datang kembali di Sistem Informasi Pelanggaran Siswa.</p>

            <!-- Kartu Statistik -->
            <div class="row g-3 mb-4">
                <div class="col-md-6 col-xl-4">
                    <div class="card stat-card p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted small text-uppercase fw-semibold mb-1">Data Siswa</h6>
                                <h3 class="fw-bold mb-0"><?= number_format($jml_siswa); ?></h3>
                            </div>
                            <div class="icon-box">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card stat-card p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted small text-uppercase fw-semibold mb-1">Data Guru</h6>
                                <h3 class="fw-bold mb-0"><?= number_format($jml_guru); ?></h3>
                            </div>
                            <div class="icon-box">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card stat-card p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted small text-uppercase fw-semibold mb-1">Data Kelas</h6>
                                <h3 class="fw-bold mb-0"><?= number_format($jml_kelas); ?></h3>
                            </div>
                            <div class="icon-box">
                                <i class="bi bi-door-open-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Pelanggaran -->
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-bar-chart-line-fill me-2 text-primary"></i>
                        Grafik Pelanggaran Siswa
                    </h5>
                    <span class="badge bg-primary">Tahun <?= date('Y'); ?></span>
                </div>
                <div style="position: relative; height: 350px;">
                    <canvas id="chartPelanggaran"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ctx = document.getElementById('chartPelanggaran').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($bulan_labels); ?>,
                datasets: [{
                    label: 'Jumlah Pelanggaran',
                    data: <?= json_encode($data_pelanggaran); ?>,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.05)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#0d6efd',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    </script>
</body>
</html>
