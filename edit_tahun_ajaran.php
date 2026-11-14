
<?php
// edit_tahun_ajaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// =====================================
// AMBIL ID TAHUN AJARAN
// =====================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kelola_tahun_ajaran.php");
    exit;
}

$id = (int) $_GET['id'];

// =====================================
// AMBIL DATA TAHUN AJARAN
// =====================================

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_tahun_ajaran WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: kelola_tahun_ajaran.php?pesan=data_tidak_ditemukan");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Tahun Ajaran - SIPENSIS</title>

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
            font-size: 13px;
            overflow-x: hidden;
        }

        /* =================================
           SIDEBAR
        ================================= */

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

        /* =================================
           KONTEN UTAMA
        ================================= */

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

        /* =================================
           JUDUL HALAMAN
        ================================= */

        .judul-halaman {
            font-size: 26px;
            font-weight: 700;
            margin: 0 0 5px;
            color: #212529;
        }

        .deskripsi-halaman {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 20px;
        }

        /* =================================
           CARD
        ================================= */

        .card {
            width: 100%;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 16px 20px;
        }

        .judul-card {
            font-size: 17px;
            font-weight: 700;
            margin: 0;
            color: #212529;
        }

        .card-body {
            padding: 25px;
        }

        /* =================================
           FORM
        ================================= */

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #343a40;
        }

        .form-control {
            height: 42px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 13px;
            padding: 9px 12px;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.15);
        }

        /* =================================
           TOMBOL
        ================================= */

        .btn-kembali {
            background: #6c757d;
            border-color: #6c757d;
            color: white;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-kembali:hover {
            background: #545b62;
            color: white;
        }

        .btn-simpan {
            background: #212529;
            border-color: #212529;
            color: white;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-simpan:hover {
            background: #000;
            color: white;
        }

        .btn-batal {
            background: #6c757d;
            border-color: #6c757d;
            color: white;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-batal:hover {
            background: #545b62;
            color: white;
        }

        /* =================================
           RESPONSIVE
        ================================= */

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

            .card-header {
                padding: 12px;
            }

            .card-body {
                padding: 15px;
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

                    <i class="fas fa-calendar-alt mr-2"></i>
                    Edit Tahun Ajaran

                </h3>

                <p class="deskripsi-halaman">

                    Perbarui informasi tahun ajaran yang terdaftar dalam sistem.

                </p>

            </div>

            <!-- CARD FORM EDIT -->

            <div class="card">

                <!-- HEADER CARD -->

                <div class="card-header">

                    <h5 class="judul-card">

                        <i class="fas fa-edit mr-2"></i>
                        Form Edit Data Tahun Ajaran

                    </h5>

                </div>

                <!-- BODY CARD -->

                <div class="card-body">

                    <!-- TOMBOL KEMBALI -->

                    <div class="mb-4">

                        <a href="kelola_tahun_ajaran.php"
                           class="btn btn-kembali btn-sm">

                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali ke Kelola Tahun Ajaran

                        </a>

                    </div>

                    <!-- FORM EDIT -->

                    <form action="proses_edit_tahun_ajaran.php"
                          method="POST">

                        <input type="hidden"
                               name="id"
                               value="<?= htmlspecialchars((string)$data['id'], ENT_QUOTES, 'UTF-8'); ?>">

                        <!-- NAMA TAHUN AJARAN -->

                        <div class="form-group">

                            <label>Nama Tahun Ajaran</label>

                            <input type="text"
                                   name="nama"
                                   class="form-control"
                                   value="<?= htmlspecialchars((string)$data['nama'], ENT_QUOTES, 'UTF-8'); ?>"
                                   placeholder="Contoh: 2026/2027"
                                   required>

                        </div>

                        <!-- TANGGAL MULAI -->

                        <div class="form-group">

                            <label>Tanggal Mulai</label>

                            <input type="date"
                                   name="tanggal_mulai"
                                   class="form-control"
                                   value="<?= htmlspecialchars((string)$data['tanggal_mulai'], ENT_QUOTES, 'UTF-8'); ?>"
                                   required>

                        </div>

                        <!-- TANGGAL SELESAI -->

                        <div class="form-group">

                            <label>Tanggal Selesai</label>

                            <input type="date"
                                   name="tanggal_selesai"
                                   class="form-control"
                                   value="<?= htmlspecialchars((string)$data['tanggal_selesai'], ENT_QUOTES, 'UTF-8'); ?>"
                                   required>

                        </div>

                        <!-- STATUS AKTIF -->

                        <div class="form-group">

                            <label>Status Aktif</label>

                            <select name="status_aktif"
                                    class="form-control"
                                    required>

                                <option value="1"
                                    <?= ((int)$data['status_aktif'] === 1) ? 'selected' : ''; ?>>

                                    Aktif

                                </option>

                                <option value="0"
                                    <?= ((int)$data['status_aktif'] === 0) ? 'selected' : ''; ?>>

                                    Tidak Aktif

                                </option>

                            </select>

                        </div>

                        <!-- TOMBOL FORM -->

                        <div class="d-flex justify-content-between mt-4">

                            <a href="kelola_tahun_ajaran.php"
                               class="btn btn-batal px-4">

                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali

                            </a>

                            <button type="submit"
                                    class="btn btn-simpan px-4">

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
