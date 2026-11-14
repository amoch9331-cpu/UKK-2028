
<?php
// edit_wali_kelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($koneksi) || !$koneksi) {
    die("Koneksi database gagal.");
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: kelola_wali_kelas.php");
    exit;
}

$pesan = "";

// =====================================
// AMBIL DATA WALI KELAS
// =====================================

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_wali_kelas WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: kelola_wali_kelas.php?pesan=data_tidak_ditemukan");
    exit;
}

// =====================================
// PROSES UPDATE
// =====================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tahun_ajaran_id = (int)($_POST['tahun_ajaran_id'] ?? 0);
    $kelas_id = (int)($_POST['kelas_id'] ?? 0);
    $guru_id = (int)($_POST['guru_id'] ?? 0);

    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';

    $status_aktif = (int)($_POST['status_aktif'] ?? 0);

    if (
        $tahun_ajaran_id <= 0 ||
        $kelas_id <= 0 ||
        $guru_id <= 0 ||
        $tanggal_mulai === '' ||
        $tanggal_selesai === '' ||
        !in_array($status_aktif, [0, 1], true)
    ) {

        $pesan = "Semua data wajib diisi dengan benar.";

    } elseif ($tanggal_selesai < $tanggal_mulai) {

        $pesan = "Tanggal selesai tidak boleh sebelum tanggal mulai.";

    } else {

        $update = mysqli_prepare(
            $koneksi,
            "UPDATE t_wali_kelas
             SET tahun_ajaran_id = ?,
                 kelas_id = ?,
                 guru_id = ?,
                 tanggal_mulai = ?,
                 tanggal_selesai = ?,
                 status_aktif = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $update,
            "iiissii",
            $tahun_ajaran_id,
            $kelas_id,
            $guru_id,
            $tanggal_mulai,
            $tanggal_selesai,
            $status_aktif,
            $id
        );

        if (mysqli_stmt_execute($update)) {

            mysqli_stmt_close($update);

            header("Location: kelola_wali_kelas.php");
            exit;

        } else {

            $pesan = "Gagal memperbarui data: " .
                     mysqli_stmt_error($update);

            mysqli_stmt_close($update);
        }
    }
}

// =====================================
// DATA FORM
// =====================================

$tahun_ajaran_id = $_POST['tahun_ajaran_id'] ?? $data['tahun_ajaran_id'];
$kelas_id = $_POST['kelas_id'] ?? $data['kelas_id'];
$guru_id = $_POST['guru_id'] ?? $data['guru_id'];

$tanggal_mulai = $_POST['tanggal_mulai'] ?? $data['tanggal_mulai'];
$tanggal_selesai = $_POST['tanggal_selesai'] ?? $data['tanggal_selesai'];

$status_aktif = $_POST['status_aktif'] ?? $data['status_aktif'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Wali Kelas - SIPENSIS</title>

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

        /* =================================
           NOTIFIKASI
        ================================= */

        .alert {
            border-radius: 6px;
            font-size: 13px;
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

                    <i class="fas fa-user-tie mr-2"></i>
                    Edit Wali Kelas

                </h3>

                <p class="deskripsi-halaman">

                    Perbarui informasi wali kelas yang terdaftar dalam sistem.

                </p>

            </div>

            <!-- NOTIFIKASI -->

            <?php if ($pesan !== ''): ?>

                <div class="alert alert-danger alert-dismissible fade show">

                    <i class="fas fa-exclamation-circle mr-2"></i>

                    <?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8'); ?>

                    <button type="button"
                            class="close"
                            data-dismiss="alert">

                        <span>&times;</span>

                    </button>

                </div>

            <?php endif; ?>

            <!-- CARD FORM -->

            <div class="card">

                <div class="card-header">

                    <h5 class="judul-card">

                        <i class="fas fa-edit mr-2"></i>
                        Form Edit Data Wali Kelas

                    </h5>

                </div>

                <div class="card-body">

                    <!-- TOMBOL KEMBALI -->

                    <div class="mb-4">

                        <a href="kelola_wali_kelas.php"
                           class="btn btn-kembali btn-sm">

                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali ke Kelola Wali Kelas

                        </a>

                    </div>

                    <!-- FORM EDIT -->

                    <form method="POST">

                        <div class="form-group">

                            <label>ID Tahun Ajaran</label>

                            <input type="number"
                                   name="tahun_ajaran_id"
                                   class="form-control"
                                   value="<?= htmlspecialchars((string)$tahun_ajaran_id, ENT_QUOTES, 'UTF-8'); ?>"
                                   required>

                        </div>

                        <div class="form-group">

                            <label>ID Kelas</label>

                            <input type="number"
                                   name="kelas_id"
                                   class="form-control"
                                   value="<?= htmlspecialchars((string)$kelas_id, ENT_QUOTES, 'UTF-8'); ?>"
                                   required>

                        </div>

                        <div class="form-group">

                            <label>ID Guru</label>

                            <input type="number"
                                   name="guru_id"
                                   class="form-control"
                                   value="<?= htmlspecialchars((string)$guru_id, ENT_QUOTES, 'UTF-8'); ?>"
                                   required>

                        </div>

                        <div class="form-group">

                            <label>Tanggal Mulai</label>

                            <input type="date"
                                   name="tanggal_mulai"
                                   class="form-control"
                                   value="<?= htmlspecialchars((string)$tanggal_mulai, ENT_QUOTES, 'UTF-8'); ?>"
                                   required>

                        </div>

                        <div class="form-group">

                            <label>Tanggal Selesai</label>

                            <input type="date"
                                   name="tanggal_selesai"
                                   class="form-control"
                                   value="<?= htmlspecialchars((string)$tanggal_selesai, ENT_QUOTES, 'UTF-8'); ?>"
                                   required>

                        </div>

                        <div class="form-group">

                            <label>Status Aktif</label>

                            <select name="status_aktif"
                                    class="form-control"
                                    required>

                                <option value="1"
                                    <?= ((string)$status_aktif === '1') ? 'selected' : ''; ?>>

                                    Aktif

                                </option>

                                <option value="0"
                                    <?= ((string)$status_aktif === '0') ? 'selected' : ''; ?>>

                                    Tidak Aktif

                                </option>

                            </select>

                        </div>

                        <!-- TOMBOL FORM -->

                        <div class="d-flex justify-content-between mt-4">

                            <a href="kelola_wali_kelas.php"
                               class="btn btn-kembali px-4">

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
