
<?php
// ==========================================
// TAMBAH PELANGGARAN - SIPENSIS
// ==========================================

include 'includes/cek_session.php';
include 'config/koneksi.php';

$pesan = "";

// ==========================================
// PROSES SIMPAN DATA
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $pelanggaran_kategori_id = (int)($_POST['pelanggaran_kategori_id'] ?? 0);
    $poin = (int)($_POST['poin'] ?? 0);

    if ($nama === '' || $pelanggaran_kategori_id <= 0 || $poin <= 0) {

        $pesan = "Semua data wajib diisi dengan benar.";

    } else {

        // Periksa kategori
        $cek = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_pelanggaran_kategori WHERE id = ?"
        );

        mysqli_stmt_bind_param($cek, "i", $pelanggaran_kategori_id);
        mysqli_stmt_execute($cek);

        $hasil_cek = mysqli_stmt_get_result($cek);
        $kategori_ada = mysqli_fetch_assoc($hasil_cek);

        mysqli_stmt_close($cek);

        if (!$kategori_ada) {

            $pesan = "Kategori pelanggaran tidak ditemukan.";

        } else {

            // Simpan data pelanggaran
            $stmt = mysqli_prepare(
                $koneksi,
                "INSERT INTO t_pelanggaran
                (nama, poin, pelanggaran_kategori_id)
                VALUES (?, ?, ?)"
            );

            if ($stmt) {

                mysqli_stmt_bind_param(
                    $stmt,
                    "sii",
                    $nama,
                    $poin,
                    $pelanggaran_kategori_id
                );

                if (mysqli_stmt_execute($stmt)) {

                    mysqli_stmt_close($stmt);

                    header("Location: kelola_pelanggaran.php?pesan=tambah_berhasil");
                    exit;

                } else {

                    $pesan = "Gagal menyimpan data: " . mysqli_stmt_error($stmt);

                }

                mysqli_stmt_close($stmt);

            } else {

                $pesan = "Query gagal: " . mysqli_error($koneksi);

            }
        }
    }
}

// ==========================================
// AMBIL DATA KATEGORI
// ==========================================

$query_kategori = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_pelanggaran_kategori ORDER BY nama ASC"
);

if (!$query_kategori) {
    die("Gagal mengambil kategori: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Pelanggaran - SIPENSIS</title>

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
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .judul-halaman i {
            font-size: 25px;
        }

        .deskripsi-halaman {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 22px;
        }

        /* CARD */

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
            font-size: 13px;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 8px;
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

            <!-- JUDUL -->

            <h3 class="judul-halaman">

                <i class="bi bi-exclamation-triangle"></i>
                Tambah Pelanggaran

            </h3>

            <p class="deskripsi-halaman">

                Tambahkan data jenis pelanggaran siswa ke dalam sistem.

            </p>

            <!-- PESAN ERROR -->

            <?php if ($pesan !== '') { ?>

                <div class="alert alert-danger alert-dismissible fade show">

                    <?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8'); ?>

                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>

                </div>

            <?php } ?>

            <!-- CARD FORM -->

            <div class="card">

                <!-- HEADER -->

                <div class="card-header">

                    <h5 class="judul-card">

                        <i class="bi bi-plus-circle mr-2"></i>
                        Form Tambah Data Pelanggaran

                    </h5>

                </div>

                <!-- BODY -->

                <div class="card-body">

                    <!-- TOMBOL KEMBALI -->

                    <div class="mb-4">

                        <a href="kelola_pelanggaran.php"
                           class="btn btn-kembali btn-sm">

                            <i class="bi bi-arrow-left mr-1"></i>
                            Kembali

                        </a>

                    </div>

                    <!-- FORM TAMBAH -->

                    <form action="tambah_pelanggaran.php" method="POST">

                        <!-- NAMA PELANGGARAN -->

                        <div class="form-group">

                            <label>Nama Pelanggaran</label>

                            <input type="text"
                                   name="nama"
                                   class="form-control"
                                   placeholder="Masukkan nama pelanggaran"
                                   value="<?= htmlspecialchars($_POST['nama'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                   required>

                        </div>

                        <!-- KATEGORI -->

                        <div class="form-group">

                            <label>Kategori Pelanggaran</label>

                            <select name="pelanggaran_kategori_id"
                                    class="form-control"
                                    required>

                                <option value="">-- Pilih Kategori Pelanggaran --</option>

                                <?php while ($kategori = mysqli_fetch_assoc($query_kategori)) { ?>

                                    <option value="<?= (int)$kategori['id']; ?>"
                                        <?= (($_POST['pelanggaran_kategori_id'] ?? '') == $kategori['id']) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($kategori['nama'], ENT_QUOTES, 'UTF-8'); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- POIN -->

                        <div class="form-group">

                            <label>Poin Pelanggaran</label>

                            <input type="number"
                                   name="poin"
                                   class="form-control"
                                   placeholder="Masukkan jumlah poin"
                                   min="1"
                                   value="<?= htmlspecialchars($_POST['poin'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                   required>

                        </div>

                        <hr>

                        <!-- TOMBOL SIMPAN -->

                        <div class="d-flex justify-content-between mt-4">

                            <a href="kelola_pelanggaran.php"
                               class="btn btn-kembali px-4">

                                <i class="bi bi-arrow-left mr-1"></i>
                                Kembali

                            </a>

                            <button type="submit"
                                    class="btn btn-simpan px-4">

                                <i class="bi bi-save mr-1"></i>
                                Simpan Data

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
