<?php
// edit_pelanggaran_kategori.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = $_GET['id'] ?? '';

$sql = "SELECT * FROM t_pelanggaran_kategori WHERE id = '$id'";
$hasil = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($hasil);

if (!$data) {
    die("Data kategori pelanggaran tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori Pelanggaran - Sistem Informasi Pelanggaran Siswa</title>

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
                <h3 class="h3 mb-0 text-dark fw-bold">Edit Kategori Pelanggaran</h3>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark font-weight-bold"><i class="fas fa-edit me-2"></i> Form Edit Kategori Pelanggaran</h5>
                </div>

                <div class="card-body">

                    <a href="kelola_pelanggaran_kategori.php" class="btn btn-dark-custom btn-sm mb-4">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Kelola Kategori Pelanggaran
                    </a>

                    <form action="proses_edit_pelanggaran_kategori.php" method="POST">

                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>">

                        <div class="form-group mb-3">
                            <label class="font-weight-semibold">Nama Kategori</label>
                            <input type="text" name="nama"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($data['nama']); ?>"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-semibold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="4" required><?php echo htmlspecialchars($data['deskripsi'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-semibold">Status Aktif</label>
                            <select name="status_aktif" class="form-control" required>
                                <option value="1" <?php echo ($data['status_aktif'] == 1) ? 'selected' : ''; ?>>Aktif</option>
                                <option value="0" <?php echo ($data['status_aktif'] == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="kelola_pelanggaran_kategori.php" class="btn btn-secondary px-4">
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