
<?php
// edit_siswa.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// =====================================
// AMBIL DATA SISWA
// =====================================

if (!isset($_GET['nis']) || trim($_GET['nis']) === '') {
    header("Location: kelola_siswa.php");
    exit;
}

$nis = trim($_GET['nis']);

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_siswa WHERE nis = ? LIMIT 1"
);

if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "s", $nis);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$data) {
    echo "<script>
        alert('Data siswa tidak ditemukan!');
        window.location.href='kelola_siswa.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Siswa - SIPENSIS</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
            overflow-x: hidden;
        }

        /* SIDEBAR */

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            min-height: 100vh;
            background: #1a1d20;
            z-index: 1000;
            overflow-y: auto;
        }

        /* KONTEN UTAMA */

        .container-fluid {
            padding: 0;
        }

        .row {
            display: block;
            margin: 0;
        }

        main {
            display: block;
            margin-left: 260px !important;
            width: calc(100% - 260px) !important;
            max-width: none !important;
            min-height: 100vh;
            padding: 15px 25px 25px !important;
            background: #f4f5f7;
        }

        /* JUDUL */

        .judul-halaman {
            font-size: 26px;
            font-weight: 700;
            margin: 0 0 5px;
            color: #212529;
        }

        .deskripsi-halaman {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        /* CARD */

        .card {
            width: 100%;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #dee2e6;
            padding: 17px 20px;
        }

        .judul-card {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            color: #212529;
        }

        .card-body {
            padding: 25px;
        }

        /* FORM */

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            color: #343a40;
        }

        .form-control {
            height: 42px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 14px;
            padding: 8px 12px;
        }

        textarea.form-control {
            height: auto;
            min-height: 100px;
            resize: vertical;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.12);
        }

        /* TOMBOL */

        .btn-dark-custom {
            background: #212529;
            border: 1px solid #212529;
            color: white;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: 5px;
        }

        .btn-dark-custom:hover {
            background: #000000;
            color: white;
        }

        .btn-kembali {
            background: #6c757d;
            border: 1px solid #6c757d;
            color: white;
            font-size: 13px;
            padding: 8px 13px;
            border-radius: 5px;
        }

        .btn-kembali:hover {
            background: #5a6268;
            color: white;
        }

        /* RESPONSIVE */

        @media (max-width: 768px) {

            #sidebar {
                width: 0;
                overflow: hidden;
            }

            main {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 15px !important;
            }

            .judul-halaman {
                font-size: 22px;
            }

            .card-body {
                padding: 16px;
            }

            .card-header {
                padding: 14px;
            }

        }

    </style>

</head>

<body>

<div class="container-fluid">

    <div class="row">

        <!-- SIDEBAR -->

        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->

        <main>

            <!-- JUDUL HALAMAN -->

            <div class="mb-3">

                <h3 class="judul-halaman">
                    Edit Siswa
                </h3>

                <p class="deskripsi-halaman">
                    Perbarui informasi dan data siswa yang terdaftar dalam sistem.
                </p>

            </div>

            <!-- CARD FORM EDIT -->

            <div class="card">

                <!-- HEADER -->

                <div class="card-header">

                    <h5 class="judul-card">

                        <i class="fas fa-user-edit mr-2"></i>
                        Form Edit Data Siswa

                    </h5>

                </div>

                <!-- BODY -->

                <div class="card-body">

                    <!-- TOMBOL KEMBALI -->

                    <div class="mb-4">

                        <a href="kelola_siswa.php"
                           class="btn btn-kembali btn-sm">

                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali ke Kelola Siswa

                        </a>

                    </div>

                    <!-- FORM EDIT -->

                    <form action="proses_edit_siswa.php" method="POST">

                        <!-- ID SISWA -->

                        <input type="hidden"
                               name="id"
                               value="<?= htmlspecialchars(
                                   (string)($data['id'] ?? ''),
                                   ENT_QUOTES,
                                   'UTF-8'
                               ); ?>">

                        <!-- NIS LAMA -->

                        <input type="hidden"
                               name="nis_lama"
                               value="<?= htmlspecialchars(
                                   (string)$data['nis'],
                                   ENT_QUOTES,
                                   'UTF-8'
                               ); ?>">

                        <!-- NIS -->

                        <div class="form-group">

                            <label>NIS</label>

                            <input type="text"
                                   name="nis"
                                   class="form-control"
                                   value="<?= htmlspecialchars(
                                       (string)$data['nis'],
                                       ENT_QUOTES,
                                       'UTF-8'
                                   ); ?>"
                                   required>

                        </div>

                        <!-- NISN -->

                        <div class="form-group">

                            <label>NISN</label>

                            <input type="text"
                                   name="nisn"
                                   class="form-control"
                                   value="<?= htmlspecialchars(
                                       (string)$data['nisn'],
                                       ENT_QUOTES,
                                       'UTF-8'
                                   ); ?>"
                                   required>

                        </div>

                        <!-- NAMA SISWA -->

                        <div class="form-group">

                            <label>Nama Siswa</label>

                            <input type="text"
                                   name="nama"
                                   class="form-control"
                                   value="<?= htmlspecialchars(
                                       (string)$data['nama'],
                                       ENT_QUOTES,
                                       'UTF-8'
                                   ); ?>"
                                   required>

                        </div>

                        <!-- JENIS KELAMIN -->

                        <div class="form-group">

                            <label>Jenis Kelamin</label>

                            <select name="jenis_kelamin"
                                    class="form-control"
                                    required>

                                <option value="L"
                                    <?= $data['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>
                                    Laki-laki
                                </option>

                                <option value="P"
                                    <?= $data['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>
                                    Perempuan
                                </option>

                            </select>

                        </div>

                        <!-- TANGGAL LAHIR -->

                        <div class="form-group">

                            <label>Tanggal Lahir</label>

                            <input type="date"
                                   name="tanggal_lahir"
                                   class="form-control"
                                   value="<?= htmlspecialchars(
                                       (string)$data['tanggal_lahir'],
                                       ENT_QUOTES,
                                       'UTF-8'
                                   ); ?>"
                                   required>

                        </div>

                        <!-- ALAMAT -->

                        <div class="form-group">

                            <label>Alamat</label>

                            <textarea name="alamat"
                                      class="form-control"
                                      rows="4"
                                      required><?= htmlspecialchars(
                                          (string)$data['alamat'],
                                          ENT_QUOTES,
                                          'UTF-8'
                                      ); ?></textarea>

                        </div>

                        <!-- STATUS AKTIF -->

                        <div class="form-group mb-4">

                            <label>Status Aktif</label>

                            <select name="status_aktif"
                                    class="form-control"
                                    required>

                                <option value="1"
                                    <?= (string)$data['status_aktif'] === '1' ? 'selected' : ''; ?>>
                                    Aktif
                                </option>

                                <option value="0"
                                    <?= (string)$data['status_aktif'] === '0' ? 'selected' : ''; ?>>
                                    Tidak Aktif
                                </option>

                            </select>

                        </div>

                        <hr>

                        <!-- TOMBOL KEMBALI DAN SIMPAN -->

                        <div class="d-flex justify-content-between">

                            <a href="kelola_siswa.php"
                               class="btn btn-kembali">

                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali

                            </a>

                            <button type="submit"
                                    class="btn btn-dark-custom">

                                <i class="fas fa-save mr-1"></i>
                                Simpan Perubahan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </main>

    </div>

</div>

<!-- JAVASCRIPT -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
