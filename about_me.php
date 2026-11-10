
<?php
session_start();
include "config/koneksi.php";

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
    <title>About Me</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            color: #212529;
        }

        /* SIDEBAR */
        .sidebar {
            width: 289px;
            height: 100vh;
            background: #343a40;
            position: fixed;
            padding: 15px;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        .sidebar h2 {
            color: white;
            font-size: 22px;
            font-weight: normal;
            margin: 0 0 25px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 11px 14px;
            margin-bottom: 5px;
            border-radius: 5px;
            font-size: 14px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #087df1;
        }

        .sidebar .logout {
            color: #ff4757;
            margin-top: 20px;
        }

        /* MAIN CONTENT */
        .main {
            margin-left: 289px;
            padding: 25px 22px;
            width: calc(100% - 289px);
            min-height: 100vh;
        }

        .main h1 {
            margin: 0 0 7px;
            font-size: 26px;
            font-weight: bold;
        }

        .breadcrumb {
            margin-bottom: 24px;
            color: #63758b;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #63758b;
            text-decoration: none;
        }

        /* CARD UTAMA */
        .card {
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        /* HEADER CARD */
        .card-header {
            padding: 17px 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        /* ISI CARD */
        .card-body {
            padding: 18px;
        }

        /* TOMBOL KEMBALI */
        .btn-kembali {
            display: inline-block;
            background: #6c757d;
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 13px;
            margin-bottom: 25px;
        }

        .btn-kembali:hover {
            background: #5a6268;
        }

        /* PROFILE */
        .profile {
            display: flex;
            align-items: center;
            gap: 22px;
            padding: 20px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .avatar {
            width: 110px;
            height: 110px;
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
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile h2 {
            margin: 0 0 8px;
            font-size: 23px;
        }

        .profile p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }

        /* INFORMASI PRIBADI */
        .section-title {
            font-size: 17px;
            margin: 0 0 17px;
            font-weight: bold;
        }

        .info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .item {
            padding: 18px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 7px;
            min-height: 85px;
        }

        .item small {
            display: block;
            color: #6c757d;
            margin-bottom: 9px;
            font-size: 13px;
        }

        .item strong {
            font-size: 15px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        /* DESKRIPSI */
        .deskripsi {
            margin-top: 22px;
            padding: 20px;
            background: #eef5ff;
            border: 1px solid #d6e6ff;
            border-radius: 7px;
            line-height: 1.8;
            font-size: 14px;
            color: #333;
        }

        /* TOMBOL */
        .btn {
            display: inline-block;
            margin-top: 22px;
            padding: 10px 16px;
            background: #212529;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 13px;
        }

        .btn:hover {
            background: #343a40;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .sidebar {
                width: 220px;
            }

            .main {
                margin-left: 220px;
                width: calc(100% - 220px);
                padding: 20px 15px;
            }
        }

        @media (max-width: 600px) {
            .sidebar {
                width: 160px;
                padding: 10px;
            }

            .sidebar h2 {
                font-size: 16px;
            }

            .sidebar a {
                font-size: 12px;
                padding: 10px 7px;
            }

            .main {
                margin-left: 160px;
                width: calc(100% - 160px);
                padding: 15px 10px;
            }

            .main h1 {
                font-size: 22px;
            }

            .card-body {
                padding: 12px;
            }

            .card-header {
                padding: 15px;
            }

            .profile {
                flex-direction: column;
                align-items: flex-start;
            }

            .profile h2 {
                font-size: 19px;
            }

            .info {
                grid-template-columns: 1fr;
            }

            .item {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <h2>Pelanggaran Siswa</h2>

        <a href="dashboard.php">Dashboard</a>
        <a href="data_siswa.php">Data Siswa</a>
        <a href="kelola_guru.php">Data Guru</a>
        <a href="kelola_kelas.php">Data Kelas</a>
        <a href="kelola_tahun_ajaran.php">Data Tahun Ajaran</a>
        <a href="kelola_wali_kelas.php">Data Wali Kelas</a>
        <a href="penempatan_siswa.php">Penempatan Siswa</a>
        <a href="kelola_pelanggaran_kategori.php">Kategori Pelanggaran</a>
        <a href="about_me.php" class="active">About Me</a>

        <a href="logout.php" class="logout">Logout</a>

    </div>

    <!-- MAIN CONTENT -->
    <div class="main">

        <h1>About Me</h1>

        <div class="breadcrumb">
            Kelola dan lihat informasi profil pemilik akun.
        </div>

        <!-- CARD -->
        <div class="card">

            <!-- CARD HEADER -->
            <div class="card-header">
                <h3>👤 Informasi Profil</h3>
            </div>

            <!-- CARD BODY -->
            <div class="card-body">

                <a href="dashboard.php" class="btn-kembali">
                    ← Kembali ke Dashboard
                </a>

                <!-- PROFILE -->
                <div class="profile">

                    <div class="avatar">

                        <?php if (file_exists($foto)) { ?>

                            <img src="<?php echo htmlspecialchars($foto); ?>"
                                alt="Foto Profil">

                        <?php } else { ?>

                            M.A

                        <?php } ?>

                    </div>

                    <div>
                        <h2>
                            <?php echo htmlspecialchars($nama); ?>
                        </h2>

                        <p>
                            <?php echo htmlspecialchars($kelas); ?>
                        </p>

                        <p>
                            <?php echo htmlspecialchars($jurusan); ?>
                        </p>
                    </div>

                </div>

                <!-- INFORMASI PRIBADI -->
                <h3 class="section-title">
                    Informasi Pribadi
                </h3>

                <div class="info">

                    <div class="item">
                        <small>Nama Lengkap</small>
                        <strong>
                            <?php echo htmlspecialchars($nama); ?>
                        </strong>
                    </div>

                    <div class="item">
                        <small>Kelas</small>
                        <strong>
                            <?php echo htmlspecialchars($kelas); ?>
                        </strong>
                    </div>

                    <div class="item">
                        <small>Sekolah</small>
                        <strong>
                            <?php echo htmlspecialchars($sekolah); ?>
                        </strong>
                    </div>

                    <div class="item">
                        <small>Jurusan</small>
                        <strong>
                            <?php echo htmlspecialchars($jurusan); ?>
                        </strong>
                    </div>

                    <div class="item">
                        <small>Hobi</small>
                        <strong>
                            <?php echo htmlspecialchars($hobi); ?>
                        </strong>
                    </div>

                    <div class="item">
                        <small>Cita-cita</small>
                        <strong>
                            <?php echo htmlspecialchars($cita_cita); ?>
                        </strong>
                    </div>

                </div>

                <!-- DESKRIPSI -->
                <div class="deskripsi">

                    Saya adalah pelajar SMK Muhammadiyah Tasikmalaya
                    jurusan Rekayasa Perangkat Lunak. Saya senang
                    mendengarkan musik, belajar hal baru, dan ingin
                    meraih cita-cita menjadi seorang seniman.

                </div>

                <!-- BUTTON -->
                <a href="dashboard.php" class="btn">
                    ← Kembali ke Dashboard
                </a>

            </div>

        </div>

    </div>

</body>
</html>
