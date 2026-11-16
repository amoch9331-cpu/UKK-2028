
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// ===================================
// PROSES UPDATE DATA
// ===================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) ($_POST['id'] ?? 0);
    $siswa_id = (int) ($_POST['siswa_id'] ?? 0);
    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $status = $_POST['status'] ?? '';

    if (
        $id <= 0 ||
        $siswa_id <= 0 ||
        $tahun_ajaran_id <= 0 ||
        $kelas_id <= 0 ||
        !in_array($status, ['aktif', 'nonaktif'], true)
    ) {
        echo "<script>
            alert('Data belum lengkap atau tidak valid!');
            window.history.back();
        </script>";
        exit;
    }

    $stmt = mysqli_prepare($koneksi, "
        UPDATE t_kelas_siswa
        SET siswa_id = ?,
            tahun_ajaran_id = ?,
            kelas_id = ?,
            status = ?
        WHERE id = ?
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "iiisi",
        $siswa_id,
        $tahun_ajaran_id,
        $kelas_id,
        $status,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);

        echo "<script>
            alert('Data penempatan siswa berhasil diperbarui!');
            window.location='penempatan_siswa.php';
        </script>";
        exit;
    } else {
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        die("Gagal memperbarui data: " . htmlspecialchars($error));
    }
}

// ===================================
// AMBIL ID
// ===================================
$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: penempatan_siswa.php");
    exit;
}

// ===================================
// AMBIL DATA PENEMPATAN
// ===================================
$stmt = mysqli_prepare($koneksi, "
    SELECT * FROM t_kelas_siswa WHERE id = ?
");

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    die("Data penempatan siswa tidak ditemukan!");
}

// ===================================
// AMBIL DATA SISWA
// ===================================
$siswa = mysqli_query($koneksi, "
    SELECT id, nama, nis
    FROM t_siswa
    ORDER BY nama ASC
");

// ===================================
// AMBIL DATA KELAS
// ===================================
$kelas = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_kelas
    ORDER BY nama ASC
");

// ===================================
// AMBIL TAHUN AJARAN
// ===================================
$tahun = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_tahun_ajaran
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Penempatan Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .form-label {
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <?php include 'layout/sidebar.php'; ?>

    <div class="content">

        <div class="mb-4">
            <h3 class="fw-bold">
                <i class="bi bi-pencil-square text-primary"></i>
                Edit Penempatan Siswa
            </h3>

            <p class="text-muted">
                Halaman untuk mengubah data penempatan siswa.
            </p>
        </div>

        <div class="card shadow-sm">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-person-lines-fill"></i>
                    Form Edit Penempatan Siswa
                </h5>
            </div>

            <div class="card-body p-4">

                <form method="POST" action="">

                    <input type="hidden" name="id"
                        value="<?= htmlspecialchars($data['id']); ?>">

                    <!-- NAMA SISWA -->
                    <div class="mb-3">
                        <label class="form-label">Nama Siswa</label>

                        <select name="siswa_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>

                            <?php while ($row = mysqli_fetch_assoc($siswa)) : ?>
                                <option value="<?= $row['id']; ?>"
                                    <?= $data['siswa_id'] == $row['id'] ? 'selected' : ''; ?>>

                                    <?= htmlspecialchars($row['nama']); ?>
                                    (<?= htmlspecialchars($row['nis']); ?>)

                                </option>
                            <?php endwhile; ?>

                        </select>
                    </div>

                    <!-- TAHUN AJARAN -->
                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran</label>

                        <select name="tahun_ajaran_id" class="form-select" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>

                            <?php while ($row = mysqli_fetch_assoc($tahun)) : ?>
                                <option value="<?= $row['id']; ?>"
                                    <?= $data['tahun_ajaran_id'] == $row['id'] ? 'selected' : ''; ?>>

                                    <?= htmlspecialchars($row['nama']); ?>

                                </option>
                            <?php endwhile; ?>

                        </select>
                    </div>

                    <!-- KELAS -->
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>

                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>

                            <?php while ($row = mysqli_fetch_assoc($kelas)) : ?>
                                <option value="<?= $row['id']; ?>"
                                    <?= $data['kelas_id'] == $row['id'] ? 'selected' : ''; ?>>

                                    <?= htmlspecialchars($row['nama']); ?>

                                </option>
                            <?php endwhile; ?>

                        </select>
                    </div>

                    <!-- STATUS -->
                    <div class="mb-4">
                        <label class="form-label">Status Penempatan</label>

                        <select name="status" class="form-select" required>

                            <option value="aktif"
                                <?= $data['status'] == 'aktif' ? 'selected' : ''; ?>>
                                Aktif
                            </option>

                            <option value="nonaktif"
                                <?= $data['status'] == 'nonaktif' ? 'selected' : ''; ?>>
                                Nonaktif
                            </option>

                        </select>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i>
                            Simpan Perubahan
                        </button>

                        <a href="penempatan_siswa.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </a>

                    </div>

                </form>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
