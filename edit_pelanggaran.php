
<?php
// edit_pelanggaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// =====================================
// AMBIL ID PELANGGARAN
// =====================================

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    die("ID pelanggaran tidak valid.");
}

// =====================================
// AMBIL DATA PELANGGARAN
// =====================================

$stmt = mysqli_prepare($koneksi, "
    SELECT
        p.id,
        p.nama,
        p.poin,
        p.pelanggaran_kategori_id,
        k.nama AS nama_kategori
    FROM t_pelanggaran p
    LEFT JOIN t_pelanggaran_kategori k
        ON p.pelanggaran_kategori_id = k.id
    WHERE p.id = ?
");

if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$data) {
    die("Data pelanggaran tidak ditemukan.");
}

// =====================================
// AMBIL DATA KATEGORI
// =====================================

$kategori = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_pelanggaran_kategori
    ORDER BY nama ASC
");

if (!$kategori) {
    die("Gagal mengambil kategori: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Pelanggaran - SIPENSIS</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
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
            margin: 0 0 18px;
            color: #212529;
        }

        /* CARD */

        .card {
            width: 100%;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 16px 20px;
            font-size: 18px;
            font-weight: 700;
        }

        .card-body {
            padding: 20px;
        }

        /* FORM */

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #343a40;
        }

        .form-control {
            width: 100%;
            min-height: 39px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
            padding: 8px 12px;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 3px rgba(0,123,255,.15);
        }

        /* TOMBOL */

        .btn-dark-custom {
            background: #212529;
            border-color: #212529;
            color: white;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-dark-custom:hover {
            background: #000;
            border-color: #000;
            color: white;
        }

        .btn-secondary {
            color: white;
            background: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background: #545b62;
            color: white;
        }

        /* RESPONSIVE */

        @media(max-width:768px) {

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
                padding: 12px;
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

            <!-- JUDUL -->
            <h3 class="judul-halaman">

                <i class="bi bi-pencil-square mr-2"></i>
                Edit Pelanggaran

            </h3>

            <!-- CARD FORM -->
            <div class="card">

                <div class="card-header">

                    <i class="bi bi-pencil-square mr-2"></i>
                    Form Edit Pelanggaran

                </div>

                <div class="card-body">

                    <!-- TOMBOL KEMBALI ATAS -->
                    <a href="./kelola_pelanggaran.php"
                       class="btn btn-secondary btn-sm mb-4">

                        <i class="bi bi-arrow-left mr-1"></i>
                        Kembali

                    </a>

                    <!-- FORM EDIT -->
                    <form action="proses_edit_pelanggaran.php"
                          method="POST">

                        <input type="hidden"
                               name="id"
                               value="<?= (int)$data['id']; ?>">

                        <!-- NAMA PELANGGARAN -->
                        <div class="form-group">

                            <label>Nama Pelanggaran</label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $data['nama'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                required>

                        </div>

                        <!-- KATEGORI PELANGGARAN -->
                        <div class="form-group">

                            <label>Kategori Pelanggaran</label>

                            <select
                                name="pelanggaran_kategori_id"
                                class="form-control"
                                required>

                                <option value="">
                                    -- Pilih Kategori --
                                </option>

                                <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>

                                    <option
                                        value="<?= (int)$k['id']; ?>"
                                        <?= (
                                            (int)$data['pelanggaran_kategori_id']
                                            === (int)$k['id']
                                        ) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars(
                                            $k['nama'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- POIN PELANGGARAN -->
                        <div class="form-group mb-4">

                            <label>Poin Pelanggaran</label>

                            <input
                                type="number"
                                name="poin"
                                class="form-control"
                                min="0"
                                value="<?= (int)$data['poin']; ?>"
                                required>

                        </div>

                        <!-- TOMBOL AKSI -->
                        <div class="d-flex justify-content-between">

                            <a href="./kelola_pelanggaran.php"
                               class="btn btn-secondary px-4">

                                <i class="bi bi-arrow-left mr-1"></i>
                                Kembali

                            </a>

                            <button
                                type="submit"
                                class="btn btn-dark-custom px-4">

                                <i class="bi bi-save mr-1"></i>
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
