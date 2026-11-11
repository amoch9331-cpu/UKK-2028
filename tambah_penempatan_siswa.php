<?php
// Mulai sesi jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Proses simpan data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siswa_id        = (int)($_POST['siswa_id'] ?? 0);
    $tahun_ajaran_id = (int)($_POST['tahun_ajaran_id'] ?? 0);
    $kelas_id        = (int)($_POST['kelas_id'] ?? 0);
    $tanggal_mulai   = mysqli_real_escape_string($koneksi, $_POST['tanggal_mulai'] ?? '');
    $tanggal_selesai = mysqli_real_escape_string($koneksi, $_POST['tanggal_selesai'] ?? '');
    $status_aktif    = (int)($_POST['status_aktif'] ?? 1);

    if ($siswa_id > 0 && $tahun_ajaran_id > 0 && $kelas_id > 0) {
        $sql_insert = "INSERT INTO t_kelas_siswa (siswa_id, tahun_ajaran_id, kelas_id, tanggal_mulai, tanggal_selesai, status_aktif) 
                       VALUES ($siswa_id, $tahun_ajaran_id, $kelas_id, '$tanggal_mulai', '$tanggal_selesai', $status_aktif)";
        
        if (mysqli_query($koneksi, $sql_insert)) {
            $_SESSION['pesan_sukses'] = "Data penempatan siswa berhasil ditambahkan.";
            header("Location: penempatan_siswa.php");
            exit;
        } else {
            $pesan_error = "Gagal menyimpan data: " . mysqli_error($koneksi);
        }
    } else {
        $pesan_error = "Semua pilihan wajib diisi dengan benar!";
    }
}

// Ambil data untuk dropdown Siswa
$query_siswa = mysqli_query($koneksi, "SELECT id, nis, nama FROM t_siswa ORDER BY nama ASC");

// Ambil data untuk dropdown Tahun Ajaran
$query_ta = mysqli_query($koneksi, "SELECT id, nama FROM t_tahun_ajaran ORDER BY nama DESC");

// Ambil data untuk dropdown Kelas
$query_kelas = mysqli_query($koneksi, "SELECT id, nama, tingkat, jurusan FROM t_kelas ORDER BY tingkat ASC, nama ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penempatan Siswa - SIPENSI</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #212529;
            font-size: 13.5px;
            overflow-x: hidden;
            margin: 0;
        }

        /* Mengatur posisi konten utama agar pas di sebelah kanan sidebar (lebar 250px) */
        main {
            margin-left: 250px;
            width: calc(100% - 250px);
            min-height: 100vh;
            padding: 24px !important;
            transition: all 0.3s ease;
        }

        .judul-halaman {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 3px;
            color: #212529;
        }

        .deskripsi-halaman {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            margin-bottom: 20px;
            background: #ffffff;
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 20px;
        }

        .judul-card {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            color: #212529;
        }

        .card-body {
            padding: 25px;
        }

        .btn-kembali {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
            font-size: 12.5px;
            border-radius: 5px;
        }

        .btn-kembali:hover {
            background-color: #5a6268;
            color: white;
        }

        .btn-black {
            background-color: #212529;
            border-color: #212529;
            color: white;
            font-size: 12.5px;
            font-weight: 600;
            border-radius: 5px;
        }

        .btn-black:hover {
            background-color: #000000;
            border-color: #000000;
            color: white;
        }

        @media (max-width: 768px) {
            main {
                margin-left: 0;
                width: 100%;
                padding: 15px !important;
            }
        }
    </style>
</head>

<body>

    <!-- MEMANGGIL SIDEBAR DI SEBELAH KIRI -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- KONTEN UTAMA -->
    <main>

        <!-- JUDUL HALAMAN -->
        <div class="mb-2">
            <h3 class="judul-halaman">Tambah Penempatan Siswa</h3>
            <p class="deskripsi-halaman">Formulir untuk memasukkan siswa ke dalam kelas dan tahun ajaran tertentu.</p>
        </div>

        <!-- TOMBOL KEMBALI -->
        <div class="mb-3">
            <a href="penempatan_siswa.php" class="btn btn-kembali btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Data Penempatan
            </a>
        </div>

        <!-- NOTIFIKASI ERROR -->
        <?php if (isset($pesan_error)) { ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= htmlspecialchars($pesan_error, ENT_QUOTES, 'UTF-8'); ?>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        <?php } ?>

        <!-- FORM TAMBAH -->
        <div class="card">
            <div class="card-header">
                <h5 class="judul-card">
                    <i class="bi bi-plus-circle-fill mr-1"></i> Form Tambah Penempatan
                </h5>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    
                    <div class="form-group">
                        <label for="siswa_id" class="font-weight-bold">Pilih Siswa</label>
                        <select name="siswa_id" id="siswa_id" class="form-control" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php while ($s = mysqli_fetch_assoc($query_siswa)) { ?>
                                <option value="<?= $s['id']; ?>"><?= htmlspecialchars($s['nis'] . ' - ' . $s['nama'], ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tahun_ajaran_id" class="font-weight-bold">Tahun Ajaran</label>
                                <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control" required>
                                    <option value="">-- Pilih Tahun Ajaran --</option>
                                    <?php while ($ta = mysqli_fetch_assoc($query_ta)) { ?>
                                        <option value="<?= $ta['id']; ?>"><?= htmlspecialchars($ta['nama'], ENT_QUOTES, 'UTF-8'); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kelas_id" class="font-weight-bold">Kelas</label>
                                <select name="kelas_id" id="kelas_id" class="form-control" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php while ($k = mysqli_fetch_assoc($query_kelas)) { ?>
                                        <option value="<?= $k['id']; ?>"><?= htmlspecialchars($k['nama'] . ' (' . $k['tingkat'] . ' ' . $k['jurusan'] . ')', ENT_QUOTES, 'UTF-8'); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_mulai" class="font-weight-bold">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_selesai" class="font-weight-bold">Tanggal Selesai (Opsional)</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status_aktif" class="font-weight-bold">Status Penempatan</label>
                        <select name="status_aktif" id="status_aktif" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="form-group mt-4 mb-0">
                        <button type="submit" class="btn btn-black px-4">
                            <i class="bi bi-save mr-1"></i> Simpan Penempatan
                        </button>
                        <a href="penempatan_siswa.php" class="btn btn-secondary px-4 ml-2">Batal</a>
                    </div>

                </form>
            </div>
        </div>

    </main>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>