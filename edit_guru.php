<?php
// edit_guru.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = $_GET['id'];

$sql = "SELECT * FROM t_guru WHERE id = '$id'";
$hasil = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($hasil);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Guru - Sistem Informasi Pelanggaran Siswa</title>

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

        <!-- Sidebar (Pastikan file layout/sidebar.php memiliki background gelap/hitam sesuai gambar) -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 col-lg-10 p-4" style="overflow-y: auto; height: 100vh;">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="h3 mb-0 text-dark fw-bold">Edit Guru</h3>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark font-weight-bold"><i class="fas fa-edit me-2"></i> Form Edit Data Guru</h5>
                </div>

                <div class="card-body">

                    <a href="kelola_guru.php" class="btn btn-dark-custom btn-sm mb-4">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Kelola Guru
                    </a>

                    <form action="proses_edit_guru.php" method="POST">

                        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                        <div class="form-group mb-3">
                            <label class="font-weight-semibold">NIP</label>
                            <input type="text" name="nip"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($data['nip']); ?>"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-semibold">Nama Guru</label>
                            <input type="text" name="nama"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($data['nama']); ?>"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-semibold">Email</label>
                            <input type="email" name="email"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($data['email']); ?>"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-semibold">Status Aktif</label>
                            <select name="status_aktif" class="form-control" required>
                                <option value="1" <?php if ($data['status_aktif'] == 1) echo 'selected'; ?>>Aktif</option>
                                <option value="0" <?php if ($data['status_aktif'] == 0) echo 'selected'; ?>>Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-semibold">User ID</label>
                            <input type="number" name="user_id"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($data['user_id']); ?>">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="kelola_guru.php" class="btn btn-secondary px-4">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-dark-custom px-4">
                                Update
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