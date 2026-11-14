
<?php
// edit_guru.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// =====================================
// AMBIL ID GURU
// =====================================

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: kelola_guru.php");
    exit;
}

// =====================================
// AMBIL DATA GURU
// =====================================

$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_guru WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$data) {
    echo "<script>
        alert('Data guru tidak ditemukan!');
        window.location='kelola_guru.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Guru - SIPENSIS</title>

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

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            background: #f4f5f7;
            font-family: Arial, sans-serif;
            color: #212529;
            font-size: 13px;
        }

        body {
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
            margin: 0;
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
            padding: 20px 25px !important;
            background: #f4f5f7;
        }

        /* JUDUL */

        .judul-halaman {
            font-size: 26px;
            font-weight: 700;
            margin: 0 0 5px;
            color: #212529;
        }

        .subjudul {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 20px;
        }

        /* CARD */

        .card {
            width: 100%;
            border: none;
            border-radius: 12px;
            background: #ffffff;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #dee2e6;
            padding: 17px 22px;
        }

        .judul-card {
            font-size: 17px;
            font-weight: 700;
            margin: 0;
            color: #212529;
        }

        .card-body {
            padding: 22px;
        }

        /* TOMBOL KEMBALI */

        .btn-kembali {
            background: #6c757d;
            border: 1px solid #6c757d;
            color: white;
            font-size: 13px;
            padding: 8px 18px;
        }

        .btn-kembali:hover {
            background: #545b62;
            color: white;
        }

        /* TOMBOL SIMPAN */

        .btn-simpan {
            background: #212529;
            border: 1px solid #212529;
            color: white;
            font-size: 13px;
            padding: 8px 22px;
        }

        .btn-simpan:hover {
            background: #000000;
            color: white;
        }

        /* FORM */

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 7px;
            color: #343a40;
        }

        .form-control {
            height: 40px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 13px;
            padding: 8px 12px;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.12);
        }

        .pemisah {
            border-top: 1px solid #e9ecef;
            margin: 22px 0;
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
                padding: 15px;
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
                    <i class="fas fa-user-edit mr-2"></i>
                    Edit Guru
                </h3>

                <p class="subjudul">
                    Ubah dan perbarui informasi data guru yang terdaftar dalam sistem.
                </p>

            </div>

            <!-- CARD FORM -->

            <div class="card">

                <div class="card-header">

                    <h5 class="judul-card">
                        <i class="fas fa-edit mr-2"></i>
                        Form Edit Data Guru
                    </h5>

                </div>

                <div class="card-body">

                    <!-- TOMBOL KEMBALI ATAS -->

                    <div class="mb-4">

                        <a href="kelola_guru.php"
                           class="btn btn-kembali btn-sm">

                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali ke Kelola Guru

                        </a>

                    </div>

                    <!-- FORM EDIT GURU -->

                    <form action="proses_edit_guru.php" method="POST">

                        <input type="hidden"
                               name="id"
                               value="<?= (int)$data['id']; ?>">

                        <!-- NIP -->

                        <div class="form-group">

                            <label>NIP</label>

                            <input type="text"
                                   name="nip"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['nip'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                   placeholder="Masukkan NIP guru"
                                   required>

                        </div>

                        <!-- NAMA GURU -->

                        <div class="form-group">

                            <label>Nama Guru</label>

                            <input type="text"
                                   name="nama"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['nama'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                   placeholder="Masukkan nama guru"
                                   required>

                        </div>

                        <!-- EMAIL -->

                        <div class="form-group">

                            <label>Email</label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                   placeholder="Masukkan email guru"
                                   required>

                        </div>

                        <!-- STATUS -->

                        <div class="form-group">

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

                        <!-- USER ID -->

                        <?php if (array_key_exists('user_id', $data)) { ?>

                        <div class="form-group">

                            <label>User ID</label>

                            <input type="number"
                                   name="user_id"
                                   class="form-control"
                                   value="<?= htmlspecialchars((string)($data['user_id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                   placeholder="Masukkan User ID">

                        </div>

                        <?php } ?>

                        <div class="pemisah"></div>

                        <!-- TOMBOL BAWAH -->

                        <div class="d-flex justify-content-between">

                            <!-- TOMBOL KEMBALI -->

                            <a href="kelola_guru.php"
                               class="btn btn-kembali">

                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali

                            </a>

                            <!-- TOMBOL SIMPAN -->

                            <button type="submit"
                                    class="btn btn-simpan">

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
