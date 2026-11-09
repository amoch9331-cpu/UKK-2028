
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
            color: #333;
        }

        /* SIDEBAR */
        .sidebar {
            width: 230px;
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
            margin-bottom: 25px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 11px;
            margin-bottom: 5px;
            border-radius: 5px;
            font-size: 14px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #087df1;
        }

        .sidebar .logout {
            color: #ff6b6b;
            margin-top: 20px;
        }

        /* MAIN CONTENT */
        .main {
            margin-left: 230px;
            padding: 30px;
            width: calc(100% - 230px);
            min-height: 100vh;
        }

        .main h1 {
            margin: 0 0 10px;
            font-size: 38px;
            font-weight: bold;
        }

        .breadcrumb {
            margin-bottom: 30px;
            color: #777;
            font-size: 16px;
        }

        .breadcrumb a {
            color: #087df1;
            text-decoration: none;
        }

        /* CARD ABOUT ME */
        .card {
            width: 100%;
            max-width: 1400px;
            min-height: 750px;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* PROFILE */
        .profile {
            display: flex;
            align-items: center;
            gap: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
            margin-bottom: 25px;
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
            font-size: 38px;
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
            margin: 0 0 10px;
            font-size: 30px;
        }

        .profile p {
            margin: 0;
            color: #777;
            line-height: 1.8;
            font-size: 17px;
        }

        /* INFORMASI PRIBADI */
        .card h3 {
            font-size: 24px;
            margin: 0 0 20px;
        }

        .info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .item {
            padding: 22px;
            min-height: 90px;
            background: #f8f9fc;
            border-radius: 10px;
        }

        .item small {
            display: block;
            color: #777;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .item strong {
            font-size: 18px;
            overflow-wrap: anywhere;
        }

        /* DESKRIPSI */
        .deskripsi {
            margin-top: 25px;
            padding: 25px;
            background: #eef5ff;
            border-radius: 10px;
            line-height: 1.9;
            font-size: 17px;
        }

        /* TOMBOL */
        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 15px 25px;
            background: #087df1;
            color: white;
            text-decoration: none;
            border-radius: 7px;
            font-size: 17px;
        }

        .btn:hover {
            background: #0069d9;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .main {
                padding: 20px;
            }

            .card {
                padding: 25px;
            }

            .main h1 {
                font-size: 32px;
            }

            .info {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 700px) {
            .sidebar {
                width: 160px;
                padding: 10px;
            }

            .sidebar h2 {
                font-size: 17px;
            }

            .sidebar a {
                font-size: 12px;
                padding: 9px 6px;
            }

            .main {
                margin-left: 160px;
                width: calc(100% - 160px);
                padding: 15px 10px;
            }

            .main h1 {
                font-size: 26px;
            }

            .card {
                padding: 18px;
                min-height: auto;
            }

            .profile {
                align-items: flex-start;
                flex-direction: column;
            }

            .profile h2 {
                font-size: 23px;
            }

            .card h3 {
                font-size: 20px;
            }

            .info {
                grid-template-columns: 1fr;
            }

            .item {
                padding: 18px;
            }

            .item strong {
                font-size: 16px;
            }

            .deskripsi {
                font-size: 15px;
                padding: 18px;
            }

            .btn {
                font-size: 15px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>My Website</h2>

        <a href="dashboard.php">Dashboard</a>
        <a href="data_siswa.php">Data Siswa</a>
        <a href="data_guru.php">Data Guru</a>
        <a href="kelas.php">Kelas</a>
        <a href="tahun_ajaran.php">Tahun Ajaran</a>
        <a href="penempatan_siswa.php">Penempatan Siswa</a>
        <a href="wali_kelas.php">Wali Kelas</a>
        <a href="laporan.php">Laporan</a>
        <a href="about_me.php" class="active">About Me</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <h1>About Me</h1>

        <div class="breadcrumb">
            <a href="dashboard.php">Dashboard</a> / About Me
        </div>

        <!-- CARD -->
        <div class="card">

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
                    <h2><?php echo htmlspecialchars($nama); ?></h2>
                    <p><?php echo htmlspecialchars($kelas); ?></p>
                    <p><?php echo htmlspecialchars($jurusan); ?></p>
                </div>

            </div>

            <!-- INFORMASI -->
            <h3>Informasi Pribadi</h3>

            <div class="info">

                <div class="item">
                    <small>Nama Lengkap</small>
                    <strong><?php echo htmlspecialchars($nama); ?></strong>
                </div>

                <div class="item">
                    <small>Kelas</small>
                    <strong><?php echo htmlspecialchars($kelas); ?></strong>
                </div>

                <div class="item">
                    <small>Sekolah</small>
                    <strong><?php echo htmlspecialchars($sekolah); ?></strong>
                </div>

                <div class="item">
                    <small>Jurusan</small>
                    <strong><?php echo htmlspecialchars($jurusan); ?></strong>
                </div>

                <div class="item">
                    <small>Hobi</small>
                    <strong><?php echo htmlspecialchars($hobi); ?></strong>
                </div>

                <div class="item">
                    <small>Cita-cita</small>
                    <strong><?php echo htmlspecialchars($cita_cita); ?></strong>
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
                Kembali ke Dashboard
            </a>

        </div>

    </div>

</body>
</html>
