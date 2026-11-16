
<?php
// ==========================================
// DASHBOARD GURU / PETUGAS
// ==========================================

include 'includes/cek_session.php';
include 'config/koneksi.php';

// CEK ROLE
$role = strtolower(trim($_SESSION['role'] ?? ''));

if ($role === 'admin') {
    header("Location: dashboard.php");
    exit;
}

// NAMA PENGGUNA
$nama = $_SESSION['name']
    ?? $_SESSION['nama_lengkap']
    ?? 'Guru / Petugas';

// ==========================================
// FUNGSI HITUNG DATA
// ==========================================

function hitungData($koneksi, $tabel)
{
    $tabelDiizinkan = [
        't_siswa',
        't_guru',
        't_pelanggaran_siswa',
        't_pelanggaran_kategori'
    ];

    if (!in_array($tabel, $tabelDiizinkan, true)) {
        return 0;
    }

    $query = @mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS total FROM `$tabel`"
    );

    if (!$query) {
        return 0;
    }

    $data = mysqli_fetch_assoc($query);

    return (int) ($data['total'] ?? 0);
}

// ==========================================
// STATISTIK
// ==========================================

$totalSiswa = hitungData($koneksi, 't_siswa');
$totalGuru = hitungData($koneksi, 't_guru');
$totalPelanggaran = hitungData($koneksi, 't_pelanggaran_siswa');
$totalKategori = hitungData($koneksi, 't_pelanggaran_kategori');

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Guru - Pelanggaran Siswa</title>

    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f4f5f7;
            font-family: Arial, sans-serif;
            color: #212529;
        }

        /* ==================================
           LAYOUT UTAMA
        ================================== */

        .app-layout {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* ==================================
           KONTEN DASHBOARD
        ================================== */

        .main-content {
            flex: 1;
            min-width: 0;
            min-height: 100vh;
            padding: 25px;
            background: #f4f5f7;
        }

        /* ==================================
           HEADER DASHBOARD
        ================================== */

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 25px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-description {
            font-size: 13px;
            color: #6c757d;
            margin: 0;
        }

        .tanggal {
            background: white;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            font-size: 13px;
            color: #495057;
        }

        /* ==================================
           SAPAAN
        ================================== */

        .welcome-card {
            background: linear-gradient(110deg, #0d6efd, #397ff0);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        }

        .welcome-card h4 {
            font-size: 21px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome-card p {
            font-size: 14px;
            margin: 0;
            opacity: 0.9;
        }

        .welcome-icon {
            font-size: 55px;
            opacity: 0.8;
        }

        /* ==================================
           STATISTIK
        ================================== */

        .stat-card {
            background: white;
            border: 1px solid #edf0f2;
            border-radius: 11px;
            padding: 20px;
            height: 100%;
            box-shadow: 0 3px 10px rgba(0,0,0,0.035);
            transition: 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .stat-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .stat-card small {
            display: block;
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            font-size: 27px;
            font-weight: 700;
            margin: 0;
            color: #212529;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .icon-blue {
            background: #e7f1ff;
            color: #0d6efd;
        }

        .icon-green {
            background: #e5f7ed;
            color: #198754;
        }

        .icon-red {
            background: #ffe8e8;
            color: #dc3545;
        }

        .icon-orange {
            background: #fff0df;
            color: #fd7e14;
        }

        /* ==================================
           CARD UMUM
        ================================== */

        .content-card {
            background: white;
            border: none;
            border-radius: 11px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.04);
            margin-top: 25px;
            overflow: hidden;
        }

        .content-card-header {
            padding: 17px 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .content-card-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }

        .content-card-body {
            padding: 20px;
        }

        /* ==================================
           MENU CEPAT
        ================================== */

        .quick-menu {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 9px;
            color: #212529;
            text-decoration: none;
            height: 100%;
            transition: 0.2s;
        }

        .quick-menu:hover {
            background: #eaf2ff;
            border-color: #b6d4fe;
            color: #0d6efd;
            transform: translateY(-2px);
        }

        .quick-menu i {
            font-size: 25px;
            color: #0d6efd;
        }

        .quick-menu strong {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .quick-menu small {
            color: #6c757d;
            font-size: 12px;
        }

        /* ==================================
           INFORMASI
        ================================== */

        .info-box {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 15px;
            background: #eef5ff;
            border: 1px solid #d6e6ff;
            border-radius: 9px;
            font-size: 13px;
            line-height: 1.7;
        }

        .info-box i {
            font-size: 20px;
            color: #0d6efd;
        }

        /* ==================================
           FOOTER
        ================================== */

        .footer {
            text-align: center;
            margin-top: 25px;
            padding: 15px;
            color: #6c757d;
            font-size: 12px;
        }

        /* ==================================
           RESPONSIVE
        ================================== */

        @media (max-width: 768px) {

            .app-layout {
                flex-direction: column;
            }

            .main-content {
                width: 100%;
                padding: 15px;
            }

            .page-title {
                font-size: 21px;
            }

            .welcome-card {
                padding: 20px;
            }

            .welcome-card h4 {
                font-size: 18px;
            }

            .tanggal {
                width: 100%;
            }

        }

    </style>

</head>

<body>

<div class="app-layout">

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar.php'; ?>


    <!-- ==================================
         KONTEN DASHBOARD
    ================================== -->

    <main class="main-content">

        <!-- HEADER -->
        <div class="page-header">

            <div>
                <h3 class="page-title">
                    <i class="bi bi-speedometer2 text-primary"></i>
                    Dashboard Guru
                </h3>

                <p class="page-description">
                    Halaman utama sistem informasi pelanggaran siswa.
                </p>
            </div>

            <div class="tanggal">
                <i class="bi bi-calendar3 text-primary"></i>
                <?= date('d-m-Y'); ?>
            </div>

        </div>


        <!-- SAPAAN -->
        <div class="welcome-card">

            <div class="row align-items-center">

                <div class="col">

                    <h4>
                        Selamat Datang,
                        <?= htmlspecialchars($nama); ?>!
                    </h4>

                    <p>
                        Selamat bekerja. Silakan gunakan menu yang tersedia
                        untuk mengelola dan memantau pelanggaran siswa.
                    </p>

                </div>

                <div class="col-auto d-none d-md-block">
                    <i class="bi bi-person-workspace welcome-icon"></i>
                </div>

            </div>

        </div>


        <!-- STATISTIK -->
        <div class="row g-3">

            <!-- TOTAL SISWA -->
            <div class="col-12 col-sm-6 col-xl-3">

                <div class="stat-card">

                    <div class="stat-content">

                        <div>
                            <small>Total Siswa</small>
                            <h3><?= number_format($totalSiswa); ?></h3>
                        </div>

                        <div class="stat-icon icon-blue">
                            <i class="bi bi-people-fill"></i>
                        </div>

                    </div>

                </div>

            </div>


            <!-- TOTAL GURU -->
            <div class="col-12 col-sm-6 col-xl-3">

                <div class="stat-card">

                    <div class="stat-content">

                        <div>
                            <small>Total Guru</small>
                            <h3><?= number_format($totalGuru); ?></h3>
                        </div>

                        <div class="stat-icon icon-green">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>

                    </div>

                </div>

            </div>


            <!-- TOTAL PELANGGARAN -->
            <div class="col-12 col-sm-6 col-xl-3">

                <div class="stat-card">

                    <div class="stat-content">

                        <div>
                            <small>Pelanggaran Tercatat</small>
                            <h3><?= number_format($totalPelanggaran); ?></h3>
                        </div>

                        <div class="stat-icon icon-red">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>

                    </div>

                </div>

            </div>


            <!-- TOTAL KATEGORI -->
            <div class="col-12 col-sm-6 col-xl-3">

                <div class="stat-card">

                    <div class="stat-content">

                        <div>
                            <small>Kategori Pelanggaran</small>
                            <h3><?= number_format($totalKategori); ?></h3>
                        </div>

                        <div class="stat-icon icon-orange">
                            <i class="bi bi-list-check"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- MENU CEPAT -->
        <div class="content-card">

            <div class="content-card-header">

                <h5>
                    <i class="bi bi-grid-fill text-primary me-2"></i>
                    Menu Akses Cepat
                </h5>

            </div>


            <div class="content-card-body">

                <div class="row g-3">

                    <!-- CATAT PELANGGARAN -->
                    <div class="col-12 col-md-6 col-xl-3">

                        <a href="catat_pelanggaran.php" class="quick-menu">

                            <i class="bi bi-pencil-square"></i>

                            <div>
                                <strong>Catat Pelanggaran</strong>
                                <small>Tambah catatan pelanggaran siswa</small>
                            </div>

                        </a>

                    </div>


                    <!-- TINDAKAN -->
                    <div class="col-12 col-md-6 col-xl-3">

                        <a href="tindakan.php" class="quick-menu">

                            <i class="bi bi-clipboard-check"></i>

                            <div>
                                <strong>Tindakan</strong>
                                <small>Kelola tindakan siswa</small>
                            </div>

                        </a>

                    </div>


                    <!-- RIWAYAT -->
                    <div class="col-12 col-md-6 col-xl-3">

                        <a href="riwayat.php" class="quick-menu">

                            <i class="bi bi-clock-history"></i>

                            <div>
                                <strong>Riwayat</strong>
                                <small>Lihat riwayat pelanggaran</small>
                            </div>

                        </a>

                    </div>


                    <!-- LAPORAN -->
                    <div class="col-12 col-md-6 col-xl-3">

                        <a href="laporan.php" class="quick-menu">

                            <i class="bi bi-file-earmark-text"></i>

                            <div>
                                <strong>Laporan</strong>
                                <small>Lihat laporan pelanggaran</small>
                            </div>

                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- INFORMASI -->
        <div class="content-card">

            <div class="content-card-header">

                <h5>
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Informasi Sistem
                </h5>

            </div>

            <div class="content-card-body">

                <div class="info-box">

                    <i class="bi bi-info-circle-fill"></i>

                    <div>
                        <strong>Petunjuk Penggunaan</strong>
                        <br>

                        Gunakan menu Catat Pelanggaran untuk memasukkan
                        data pelanggaran siswa. Menu Tindakan digunakan
                        untuk mengelola tindak lanjut, sedangkan menu
                        Riwayat dan Laporan digunakan untuk melihat
                        data pelanggaran yang sudah tercatat.
                    </div>

                </div>

            </div>

        </div>


        <!-- FOOTER -->
        <div class="footer">

            <i class="bi bi-code-slash"></i>

            Sistem Informasi Pelanggaran Siswa

            <br>

            &copy; <?= date('Y'); ?> SMK Muhammadiyah Tasikmalaya

        </div>

    </main>

</div>


<!-- JAVASCRIPT BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
