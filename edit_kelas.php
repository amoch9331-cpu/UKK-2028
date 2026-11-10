<?php
// edit_kelas.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = $_GET['id'];

$sql = "SELECT * FROM t_kelas WHERE id = '$id'";
$hasil = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($hasil);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas - Sistem Informasi Pelanggaran Siswa</title>
    <!-- Memuat Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome untuk ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content-wrapper {
            flex: 1;
        }
        .navbar-custom {
            background-color: #343a40;
        }
        .footer-custom {
            background-color: #343a40;
            color: #ffffff;
            text-align: center;
            padding: 15px 0;
            font-size: 14px;
        }
        .btn-dark-custom {
            background-color: #495057;
            color: white;
        }
        .btn-dark-custom:hover {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>

<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-custom navbar-dark px-4 py-3 shadow-sm">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1 fw-bold">Sistem Informasi Pelanggaran Siswa</span>
            <a href="dashboard.php" class="btn btn-outline-light btn-sm px-3">Dashboard</a>
        </div>
    </nav>

    <!-- Content Wrapper -->
    <div class="content-wrapper py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-4">
                            
                            <h4 class="card-title fw-bold text-dark mb-1">
                                <i class="fas fa-edit me-2"></i>Edit Kelas
                            </h4>
                            <p class="text-muted small mb-4">Ubah data kelas dengan lengkap dan benar.</p>

                            <form action="proses_edit_kelas.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                                <div class="mb-3">
                                    <label for="nama" class="form-label fw-semibold">Nama Kelas</label>
                                    <input type="text" class="form-control" id="nama" name="nama" 
                                           value="<?php echo htmlspecialchars($data['nama']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="tingkat" class="form-label fw-semibold">Tingkat</label>
                                    <input type="text" class="form-control" id="tingkat" name="tingkat" 
                                           value="<?php echo htmlspecialchars($data['tingkat']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="jurusan" class="form-label fw-semibold">Jurusan</label>
                                    <input type="text" class="form-control" id="jurusan" name="jurusan" 
                                           value="<?php echo htmlspecialchars($data['jurusan']); ?>" required>
                                </div>

                                <div class="mb-4">
                                    <label for="status_aktif" class="form-label fw-semibold">Status Aktif</label>
                                    <select class="form-select" id="status_aktif" name="status_aktif" required>
                                        <option value="1" <?php echo ($data['status_aktif'] == 1) ? 'selected' : ''; ?>>Aktif</option>
                                        <option value="0" <?php echo ($data['status_aktif'] == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-2">
                                    <a href="kelola_kelas.php" class="btn btn-dark-custom px-4">
                                        Kembali
                                    </a>
                                    <button type="submit" class="btn btn-dark-custom px-4">
                                        Simpan Perubahan
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-custom">
        &copy; 2026 Sistem Informasi Pelanggaran Siswa
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>