
<?php
// ==========================================
// ABOUT ME - ADMIN
// ==========================================

include 'includes/cek_session.php';
include 'config/koneksi.php';

// CEK ROLE ADMIN
$role = strtolower(trim($_SESSION['role'] ?? ''));

if ($role !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// DATA PROFIL
$nama = "MUHAMMAD ADNAN";
$kelas = "XII RPL 2";
$sekolah = "SMK Muhammadiyah Tasikmalaya";
$jurusan = "Rekayasa Perangkat Lunak";
$hobi = "Mendengarkan Musik";
$cita_cita = "Seniman";

$foto = "assets/foto-profil.jpg";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Me - Pelanggaran Siswa</title>

    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        /* ==================================
           BODY
        ================================== */

        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100%;
            min-height: 100%;
            background: #f4f5f7;
            font-family: Arial, sans-serif;
            color: #212529;
            overflow-x: hidden;
        }

        /* ==================================
           SIDEBAR
        ================================== */

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            z-index: 1000;
        }

        /* ==================================
           KONTEN UTAMA
           TEPAT DI SAMPING SIDEBAR
        ================================== */

        .main-content {
            position: relative;
            margin-left: 260px !important;
            width: calc(100% - 260px);
            min-height: 100vh;
            padding: 22px 25px;
            background: #f4f5f7;
        }

        /* ==================================
           JUDUL
        ================================== */

        .page-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #212529;
        }

        .page-description {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 20px;
        }

        /* ==================================
           CARD
        ================================== */

        .profile-card {
            width: 100%;
            background: #fff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .profile-header {
            padding: 18px 22px;
            border-bottom: 1px solid #dee2e6;
            background: #fff;
        }

        .profile-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .profile-body {
            padding: 25px;
        }

        /* ==================================
           TOMBOL KEMBALI
        ================================== */

        .btn-kembali {
            display: inline-block;
            background: #6c757d;
            color: white;
            text-decoration: none;
            padding: 9px 14px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 25px;
            transition: 0.2s;
        }

        .btn-kembali:hover {
            background: #545b62;
            color: white;
        }

        /* ==================================
           PROFIL UTAMA
        ================================== */

        .profile {
            display: flex;
            align-items: center;
            gap: 25px;
            padding: 25px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            margin-bottom: 28px;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #e6efff;
            color: #087df1;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 35px;
            font-weight: bold;
            overflow: hidden;
            flex-shrink: 0;
            border: 4px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile h2 {
            font-size: 23px;
            font-weight: bold;
            margin: 0 0 8px;
        }

        .profile p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }

        /* ==================================
           INFORMASI PRIBADI
        ================================== */

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .info {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .item {
            padding: 20px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            min-height: 90px;
        }

        .item small {
            display: block;
            color: #6c757d;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .item strong {
            font-size: 15px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .item i {
            color: #0d6efd;
            margin-right: 7px;
        }

        /* ==================================
           DESKRIPSI
        ================================== */

        .deskripsi {
            margin-top: 25px;
            padding: 22px;
            background: #eef5ff;
            border: 1px solid #d6e6ff;
            border-radius: 8px;
            line-height: 1.8;
            font-size: 14px;
            color: #333;
        }

        .deskripsi h5 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* ==================================
           FOOTER
        ================================== */

        .footer-profile {
            margin-top: 25px;
            padding-top: 18px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            line-height: 1.8;
        }

        /* ==================================
           RESPONSIVE TABLET DAN HP
        ================================== */

        @media (max-width: 768px) {

            .main-content {
                margin-left: 0 !important;
                width: 100%;
                padding: 15px;
            }

            .profile-body {
                padding: 15px;
            }

            .profile {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 20px;
            }

            .profile h2 {
                font-size: 20px;
            }

            .info {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 22px;
            }
        }

    </style>
</head>

<body>

    <!-- ==================================
         SIDEBAR
    ================================== -->

    <?php include 'layout/sidebar.php'; ?>


    <!-- ==================================
         KONTEN UTAMA
    ================================== -->

    <main class="main-content">

        <!-- JUDUL -->
        <div class="mb-3">
            <h3 class="page-title">
                <i class="bi bi-person-circle"></i>
                About Me
            </h3>

            <p class="page-description">
                Informasi profil pembuat aplikasi.
            </p>
        </div>


        <!-- CARD PROFIL -->
        <div class="profile-card">

            <div class="profile-header">
                <h5>
                    <i class="bi bi-person-vcard me-2"></i>
                    Informasi Profil
                </h5>
            </div>


            <div class="profile-body">

                <!-- TOMBOL KEMBALI -->
                <a href="dashboard.php" class="btn-kembali">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Dashboard
                </a>


                <!-- PROFIL UTAMA -->
                <div class="profile">

                    <div class="avatar">

                        <?php if (file_exists($foto)) : ?>

                            <img
                                src="<?= htmlspecialchars($foto); ?>"
                                alt="Foto Profil">

                        <?php else : ?>

                            M.A

                        <?php endif; ?>

                    </div>

                    <div>

                        <h2>
                            <?= htmlspecialchars($nama); ?>
                        </h2>

                        <p>
                            <i class="bi bi-mortarboard-fill"></i>
                            <?= htmlspecialchars($kelas); ?>
                        </p>

                        <p>
                            <i class="bi bi-code-slash"></i>
                            <?= htmlspecialchars($jurusan); ?>
                        </p>

                        <span class="badge bg-primary">
                            Siswa RPL
                        </span>

                    </div>

                </div>


                <!-- INFORMASI PRIBADI -->
                <h5 class="section-title">
                    <i class="bi bi-person-lines-fill text-primary"></i>
                    Informasi Pribadi
                </h5>


                <div class="info">

                    <div class="item">
                        <small>
                            <i class="bi bi-person-fill"></i>
                            Nama Lengkap
                        </small>
                        <strong>
                            <?= htmlspecialchars($nama); ?>
                        </strong>
                    </div>

                    <div class="item">
                        <small>
                            <i class="bi bi-mortarboard-fill"></i>
                            Kelas
                        </small>
                        <strong>
                            <?= htmlspecialchars($kelas); ?>
                        </strong>
                    </div>

                    <div class="item">
                        <small>
                            <i class="bi bi-building"></i>
                            Sekolah
                        </small>
                        <strong>
                            <?= htmlspecialchars($sekolah); ?>
                        </strong>
                    </div>

                    <div class="item">
                        <small>
                            <i class="bi bi-code-slash"></i>
                            Jurusan
                        </small>
                        <strong>
                            <?= htmlspecialchars($jurusan); ?>
                        </strong>
                    </div>

                    <div class="item">
                        <small>
                            <i class="bi bi-music-note-beamed"></i>
                            Hobi
                        </small>
                        <strong>
                            <?= htmlspecialchars($hobi); ?>
                        </strong>
                    </div>

                    <div class="item">
                        <small>
                            <i class="bi bi-star-fill"></i>
                            Cita-cita
                        </small>
                        <strong>
                            <?= htmlspecialchars($cita_cita); ?>
                        </strong>
                    </div>

                </div>


                <!-- TENTANG SAYA -->
                <div class="deskripsi">

                    <h5>
                        <i class="bi bi-chat-left-text-fill text-primary"></i>
                        Tentang Saya
                    </h5>

                    Saya adalah pelajar SMK Muhammadiyah Tasikmalaya
                    jurusan Rekayasa Perangkat Lunak. Saya senang
                    mendengarkan musik, belajar hal baru, dan ingin
                    meraih cita-cita menjadi seorang seniman.

                </div>


                <!-- FOOTER -->
                <div class="footer-profile">

                    <i class="bi bi-code-slash"></i>

                    Dibuat sebagai bagian dari proyek aplikasi
                    Sistem Informasi Pelanggaran Siswa.

                    <br>

                    &copy; <?= date('Y'); ?>
                    <?= htmlspecialchars($nama); ?>

                </div>

            </div>
        </div>

    </main>


    <!-- JAVASCRIPT BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
