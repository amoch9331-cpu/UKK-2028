<?php
// edit_wali_kelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($koneksi) || !$koneksi) {
    die("Koneksi database gagal.");
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("ID wali kelas tidak valid.");
}

$pesan = "";

/* Ambil data wali kelas */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_wali_kelas WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data wali kelas tidak ditemukan.");
}

/* Proses update */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $guru_id = (int) ($_POST['guru_id'] ?? 0);
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    $status_aktif = (int) ($_POST['status_aktif'] ?? 0);

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
            header("Location: kelola_wali_kelas.php");
            exit;
        } else {
            $pesan = "Gagal memperbarui data: " . mysqli_stmt_error($update);
        }
    }
}

/* Tampilkan kembali data yang dimasukkan */
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
    <title>Edit Wali Kelas - Sistem Informasi Pelanggaran Siswa</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <!-- FontAwesome untuk ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            background-color: #f4f6f9;
        }
        .container-fluid, .row {
            height: 100%;
        }
        /* Memastikan sidebar memanjang penuh ke bawah */
        .row > div:first-child {
            height: 100%;
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e3e6f0;
            font-weight: bold;
        }
        .btn-dark-custom {
            background-color: #343a40;
            color: white;
        }
        .btn-dark-custom:hover {
            background-color: #23272b;
            color: white;
        }
    </style>
</head>

<body>

<div class="container-fluid p-0">
    <div class="row no-gutters h-100">

        <!-- Sidebar -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 col-lg-10 p-4" style="overflow-y: auto; height: 100vh;">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="h3 mb-0 text-dark fw-bold">Edit Wali Kelas</h3>
            </div>

            <?php if ($pesan !== ''): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($pesan); ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark font-weight-bold"><i class="fas fa-edit me-2"></i> Form Edit Data Wali Kelas</h5>
                </div>

                <div class="card-body">

                    <a href="kelola_wali_kelas.php" class="btn btn-dark-custom btn-sm mb-4">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Kelola Wali Kelas
                    </a>

                    <form method="POST">

                        <div class="form-group mb-3">
                            <label class="font-weight-semibold">ID Tahun Ajaran</label>
                            <input type="number" name="tahun_ajaran_id"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars((string)$tahun_ajaran_id); ?>"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-semibold">ID Kelas</label>
                            <input type="number" name="kelas_id"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars((string)$kelas_id); ?>"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-semibold">ID Guru</label>
                            <input type="number" name="guru_id"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars((string)$guru_id); ?>"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-semibold">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($tanggal_mulai); ?>"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-semibold">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($tanggal_selesai); ?>"
                                   required>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-semibold">Status Aktif</label>
                            <select name="status_aktif" class="form-control" required>
                                <option value="1" <?php echo ((string)$status_aktif === '1') ? 'selected' : ''; ?>>Aktif</option>
                                <option value="0" <?php echo ((string)$status_aktif === '0') ? 'selected' : ''; ?>>Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="kelola_wali_kelas.php" class="btn btn-secondary px-4">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-dark-custom px-4">
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </main>

    </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>