
<?php
// edit_penempatan_siswa.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// =====================================
// VALIDASI ID
// =====================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kelola_penempatan_siswa.php");
    exit;
}

$id = (int) $_GET['id'];
$pesan = "";

// =====================================
// AMBIL DATA PENEMPATAN
// =====================================

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_kelas_siswa WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: kelola_penempatan_siswa.php?pesan=data_tidak_ditemukan");
    exit;
}

// =====================================
// PROSES UPDATE
// =====================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $siswa_id = (int) ($_POST['siswa_id'] ?? 0);
    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);

    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';

    $status_aktif = (int) ($_POST['status_aktif'] ?? 0);

    if (
        $siswa_id <= 0 ||
        $tahun_ajaran_id <= 0 ||
        $kelas_id <= 0 ||
        $tanggal_mulai == '' ||
        $tanggal_selesai == '' ||
        !in_array($status_aktif, [0, 1], true)
    ) {

        $pesan = "Semua data wajib diisi dengan benar.";

    } elseif ($tanggal_selesai < $tanggal_mulai) {

        $pesan = "Tanggal selesai tidak boleh sebelum tanggal mulai.";

    } else {

        $update = mysqli_prepare(
            $koneksi,
            "UPDATE t_kelas_siswa SET
                siswa_id = ?,
                tahun_ajaran_id = ?,
                kelas_id = ?,
                tanggal_mulai = ?,
                tanggal_selesai = ?,
                status_aktif = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $update,
            "iiissii",
            $siswa_id,
            $tahun_ajaran_id,
            $kelas_id,
            $tanggal_mulai,
            $tanggal_selesai,
            $status_aktif,
            $id
        );

        if (mysqli_stmt_execute($update)) {

            mysqli_stmt_close($update);

            header("Location: kelola_penempatan_siswa.php?pesan=edit_berhasil");
            exit;

        } else {

            $pesan = "Gagal memperbarui data: " . mysqli_stmt_error($update);

            mysqli_stmt_close($update);
        }
    }
}

// =====================================
// DATA FORM
// =====================================

$siswa_id = $_POST['siswa_id'] ?? $data['siswa_id'];
$tahun_ajaran_id = $_POST['tahun_ajaran_id'] ?? $data['tahun_ajaran_id'];
$kelas_id = $_POST['kelas_id'] ?? $data['kelas_id'];

$tanggal_mulai = $_POST['tanggal_mulai'] ?? $data['tanggal_mulai'];
$tanggal_selesai = $_POST['tanggal_selesai'] ?? $data['tanggal_selesai'];

$status_aktif = $_POST['status_aktif'] ?? $data['status_aktif'];

// =====================================
// AMBIL DATA DROPDOWN
// =====================================

$siswa = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama FROM t_siswa
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran
     ORDER BY tanggal_mulai DESC"
);

$kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_kelas
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Penempatan Siswa - SIPENSIS</title>

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

        body {
            margin: 0;
            padding: 0;
            background: #f4f5f7;
            font-family: Arial, sans-serif;
            color: #212529;
            font-size: 13px;
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
            padding: 20px 25px 30px !important;
            background: #f4f5f7;
        }

        /* JUDUL */

        .judul-halaman {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #212529;
        }

        .deskripsi-halaman {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 22px;
        }

        /* CARD */

        .card {
            width: 100%;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 17px 20px;
        }

        .judul-card {
            font-size: 17px;
            font-weight: 700;
            margin: 0;
        }

        .card-body {
            padding: 25px;
        }

        /* FORM */

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
            padding: 8px 12px;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.15);
        }

        /* TOMBOL */

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

        .alert {
            border-radius: 6px;
            font-size: 13px;
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

                    <i class="bi bi-people-fill mr-2"></i>
                    Edit Penempatan Siswa

                </h3>

                <p class="deskripsi-halaman">

                    Perbarui data penempatan siswa sesuai tahun ajaran dan kelas.

                </p>

            </div>

            <!-- NOTIFIKASI -->

            <?php if ($pesan != ''): ?>

                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle mr-2"></i>
                    <?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8'); ?>
                </div>

            <?php endif; ?>

            <!-- CARD FORM -->

            <div class="card">

                <div class="card-header">

                    <h5 class="judul-card">

                        <i class="bi bi-pencil-square mr-2"></i>
                        Form Edit Penempatan Siswa

                    </h5>

                </div>

                <div class="card-body">

                    <!-- KEMBALI -->

                    <div class="mb-4">

                        <a href="kelola_penempatan_siswa.php"
                           class="btn btn-kembali btn-sm">

                            <i class="bi bi-arrow-left mr-1"></i>
                            Kembali

                        </a>

                    </div>

                    <!-- FORM EDIT -->

                    <form method="POST"
                          action="">

                        <!-- SISWA -->

                        <div class="form-group">

                            <label>Nama Siswa</label>

                            <select name="siswa_id"
                                    class="form-control"
                                    required>

                                <option value="">-- Pilih Siswa --</option>

                                <?php while ($row = mysqli_fetch_assoc($siswa)): ?>

                                    <option value="<?= (int)$row['id']; ?>"
                                        <?= ((string)$siswa_id === (string)$row['id']) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($row['nis'] . ' - ' . $row['nama'], ENT_QUOTES, 'UTF-8'); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                        <!-- TAHUN AJARAN -->

                        <div class="form-group">

                            <label>Tahun Ajaran</label>

                            <select name="tahun_ajaran_id"
                                    class="form-control"
                                    required>

                                <option value="">-- Pilih Tahun Ajaran --</option>

                                <?php while ($row = mysqli_fetch_assoc($tahun)): ?>

                                    <option value="<?= (int)$row['id']; ?>"
                                        <?= ((string)$tahun_ajaran_id === (string)$row['id']) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8'); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                        <!-- KELAS -->

                        <div class="form-group">

                            <label>Nama Kelas</label>

                            <select name="kelas_id"
                                    class="form-control"
                                    required>

                                <option value="">-- Pilih Kelas --</option>

                                <?php while ($row = mysqli_fetch_assoc($kelas)): ?>

                                    <option value="<?= (int)$row['id']; ?>"
                                        <?= ((string)$kelas_id === (string)$row['id']) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8'); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                        <!-- TANGGAL MULAI -->

                        <div class="form-group">

                            <label>Tanggal Mulai</label>

                            <input type="date"
                                   name="tanggal_mulai"
                                   class="form-control"
                                   value="<?= htmlspecialchars($tanggal_mulai, ENT_QUOTES, 'UTF-8'); ?>"
                                   required>

                        </div>

                        <!-- TANGGAL SELESAI -->

                        <div class="form-group">

                            <label>Tanggal Selesai</label>

                            <input type="date"
                                   name="tanggal_selesai"
                                   class="form-control"
                                   value="<?= htmlspecialchars($tanggal_selesai, ENT_QUOTES, 'UTF-8'); ?>"
                                   required>

                        </div>

                        <!-- STATUS -->

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

                        <!-- TOMBOL -->

                        <div class="d-flex justify-content-between mt-4">

                            <a href="kelola_penempatan_siswa.php"
                               class="btn btn-kembali px-4">

                                <i class="bi bi-arrow-left mr-1"></i>
                                Kembali

                            </a>

                            <button type="submit"
                                    class="btn btn-simpan px-4">

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

</body>
</html>
