
<?php
// ==========================================
// PENEMPATAN SISWA - SIPENSI
// ==========================================

include 'includes/cek_session.php';
include 'config/koneksi.php';

// ==========================================
// PROSES TAMBAH PENEMPATAN
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['simpan_penempatan'])) {

    $siswa_id = (int) $_POST['siswa_id'];
    $tahun_ajaran_id = (int) $_POST['tahun_ajaran_id'];
    $kelas_id = (int) $_POST['kelas_id'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = !empty($_POST['tanggal_selesai'])
        ? $_POST['tanggal_selesai'] : null;
    $status_aktif = (int) $_POST['status_aktif'];

    $stmt = mysqli_prepare($koneksi, "
        INSERT INTO t_kelas_siswa
        (siswa_id, tahun_ajaran_id, kelas_id,
         tanggal_mulai, tanggal_selesai, status_aktif)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "iiissi",
            $siswa_id,
            $tahun_ajaran_id,
            $kelas_id,
            $tanggal_mulai,
            $tanggal_selesai,
            $status_aktif
        );

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = 'Penempatan siswa berhasil ditambahkan!';
        } else {
            $_SESSION['pesan_error'] = 'Gagal menyimpan: ' . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error'] = 'Query gagal: ' . mysqli_error($koneksi);
    }

    header("Location: penempatan_siswa.php");
    exit;
}

// ==========================================
// AMBIL DATA FORM
// ==========================================

$query_siswa = mysqli_query($koneksi, "
    SELECT id, nis, nama FROM t_siswa ORDER BY nama ASC
");

$query_tahun = mysqli_query($koneksi, "
    SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC
");

$query_kelas = mysqli_query($koneksi, "
    SELECT id, nama, tingkat, jurusan FROM t_kelas ORDER BY nama ASC
");

// ==========================================
// TAMPILKAN FORM TAMBAH
// ==========================================

$tampilkan_form = isset($_GET['tambah']);

// ==========================================
// AMBIL DATA PENEMPATAN
// ==========================================

$sql = "SELECT
            ks.id,
            s.nis,
            s.nama AS nama_siswa,
            ta.nama AS tahun_ajaran,
            k.nama AS nama_kelas,
            k.tingkat,
            k.jurusan,
            ks.tanggal_mulai,
            ks.tanggal_selesai,
            ks.status_aktif
        FROM t_kelas_siswa ks
        LEFT JOIN t_siswa s ON ks.siswa_id = s.id
        LEFT JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id = ta.id
        LEFT JOIN t_kelas k ON ks.kelas_id = k.id
        ORDER BY ks.id DESC";

$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Penempatan Siswa - SIPENSI</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="stylesheet"
href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #f4f6f9;
    font-family: 'Segoe UI', Arial, sans-serif;
    font-size: 14px;
}

.layout-wrapper {
    display: flex;
    min-height: 100vh;
}

.dashboard-content {
    flex: 1;
    min-width: 0;
    padding: 25px;
}

.judul-halaman {
    font-size: 26px;
    font-weight: 700;
    color: #212529;
}

.deskripsi-halaman {
    color: #6c757d;
    margin-bottom: 22px;
}

.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,.06);
    margin-bottom: 20px;
}

.card-header {
    background: white;
    padding: 17px 20px;
    border-bottom: 1px solid #eee;
}

.card-body {
    padding: 22px;
}

.judul-card {
    font-size: 17px;
    font-weight: 700;
    margin: 0;
}

.form-control {
    height: 42px;
    border-radius: 7px;
    font-size: 14px;
}

.btn-black {
    background: #212529;
    color: white;
    border-radius: 6px;
}

.btn-black:hover {
    background: #000;
    color: white;
}

#tabelPenempatan {
    width: 100% !important;
    font-size: 13px;
}

#tabelPenempatan thead th {
    background: #212529;
    color: white;
    white-space: nowrap;
    padding: 12px;
}

#tabelPenempatan tbody td {
    padding: 10px;
    vertical-align: middle;
}

.badge {
    padding: 6px 9px;
}

.alert {
    border-radius: 7px;
}

@media(max-width:768px) {
    .layout-wrapper {
        flex-direction: column;
    }

    .dashboard-content {
        padding: 15px;
    }

    .judul-halaman {
        font-size: 21px;
    }

    .card-body {
        padding: 15px;
    }
}

@media print {
    .d-print-none {
        display: none !important;
    }

    .dashboard-content {
        padding: 0;
    }
}

</style>
</head>

<body>

<div class="layout-wrapper">

<?php include 'layout/sidebar.php'; ?>

<main class="dashboard-content">

<h3 class="judul-halaman">
    <i class="bi bi-person-lines-fill"></i>
    Penempatan Siswa
</h3>

<p class="deskripsi-halaman">
    Kelola data penempatan siswa berdasarkan tahun ajaran dan kelas.
</p>

<a href="dashboard.php" class="btn btn-secondary btn-sm mb-3 d-print-none">
    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
</a>

<?php if (isset($_SESSION['pesan_sukses'])) { ?>

<div class="alert alert-success alert-dismissible fade show">
    <?= htmlspecialchars($_SESSION['pesan_sukses']); ?>
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>

<?php unset($_SESSION['pesan_sukses']); } ?>

<?php if (isset($_SESSION['pesan_error'])) { ?>

<div class="alert alert-danger alert-dismissible fade show">
    <?= htmlspecialchars($_SESSION['pesan_error']); ?>
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>

<?php unset($_SESSION['pesan_error']); } ?>


<!-- =====================================
     FORM TAMBAH PENEMPATAN
===================================== -->

<?php if ($tampilkan_form) { ?>

<div class="card">

    <div class="card-header">
        <h5 class="judul-card">
            <i class="bi bi-plus-circle"></i>
            Form Tambah Penempatan Siswa
        </h5>
    </div>

    <div class="card-body">

        <form method="POST" action="penempatan_siswa.php">

            <div class="form-group">
                <label>Nama Siswa</label>

                <select name="siswa_id" class="form-control" required>
                    <option value="">-- Pilih Siswa --</option>

                    <?php while ($s = mysqli_fetch_assoc($query_siswa)) { ?>
                    <option value="<?= (int)$s['id']; ?>">
                        <?= htmlspecialchars($s['nis'].' - '.$s['nama']); ?>
                    </option>
                    <?php } ?>

                </select>
            </div>

            <div class="form-group">
                <label>Tahun Ajaran</label>

                <select name="tahun_ajaran_id" class="form-control" required>
                    <option value="">-- Pilih Tahun Ajaran --</option>

                    <?php while ($ta = mysqli_fetch_assoc($query_tahun)) { ?>
                    <option value="<?= (int)$ta['id']; ?>">
                        <?= htmlspecialchars($ta['nama']); ?>
                    </option>
                    <?php } ?>

                </select>
            </div>

            <div class="form-group">
                <label>Kelas</label>

                <select name="kelas_id" class="form-control" required>
                    <option value="">-- Pilih Kelas --</option>

                    <?php while ($k = mysqli_fetch_assoc($query_kelas)) { ?>
                    <option value="<?= (int)$k['id']; ?>">
                        <?= htmlspecialchars(
                            $k['nama'].' - '.$k['tingkat'].' - '.$k['jurusan']
                        ); ?>
                    </option>
                    <?php } ?>

                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai"
                       class="form-control" required>
            </div>

            <div class="form-group">
                <label>Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai"
                       class="form-control">
            </div>

            <div class="form-group">
                <label>Status Penempatan</label>

                <select name="status_aktif" class="form-control" required>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <hr>

            <button type="submit" name="simpan_penempatan"
                    class="btn btn-black">
                <i class="bi bi-save"></i>
                Simpan Penempatan
            </button>

            <a href="penempatan_siswa.php"
               class="btn btn-secondary">
                Batal
            </a>

        </form>

    </div>
</div>

<?php } ?>


<!-- =====================================
     TABEL DATA PENEMPATAN
===================================== -->

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="judul-card">
            <i class="bi bi-people-fill"></i>
            Data Penempatan Siswa
        </h5>

        <a href="penempatan_siswa.php?tambah=1"
           class="btn btn-black btn-sm d-print-none">

            <i class="bi bi-plus-lg"></i>
            Tambah Penempatan

        </a>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table id="tabelPenempatan"
                   class="table table-striped table-bordered table-hover table-sm">

                <thead>
                <tr>
                    <th>No.</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Tahun Ajaran</th>
                    <th>Kelas</th>
                    <th>Tingkat</th>
                    <th>Jurusan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
                </thead>

                <tbody>

                <?php $no = 1; ?>

                <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>

                <tr>

                    <td><?= $no++; ?></td>

                    <td>
                        <?= htmlspecialchars($row['nis'] ?? '-'); ?>
                    </td>

                    <td>
                        <strong>
                        <?= htmlspecialchars($row['nama_siswa'] ?? '-'); ?>
                        </strong>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['tahun_ajaran'] ?? '-'); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nama_kelas'] ?? '-'); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['tingkat'] ?? '-'); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['jurusan'] ?? '-'); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['tanggal_mulai'] ?? '-'); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['tanggal_selesai'] ?? '-'); ?>
                    </td>

                    <td>
                        <?php if ((int)$row['status_aktif'] === 1) { ?>
                            <span class="badge badge-success">Aktif</span>
                        <?php } else { ?>
                            <span class="badge badge-danger">Tidak Aktif</span>
                        <?php } ?>
                    </td>

                    <td class="text-center" style="white-space:nowrap">

                        <a href="edit_penempatan.php?id=<?= (int)$row['id']; ?>"
                           class="btn btn-warning btn-sm text-white"
                           title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <a href="hapus_penempatan.php?id=<?= (int)$row['id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin ingin menghapus data ini?')"
                           title="Hapus">
                            <i class="bi bi-trash"></i>
                        </a>

                    </td>

                </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {

    $('#tabelPenempatan').DataTable({

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data penempatan",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        },

        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        ordering: true,
        searching: true,
        autoWidth: false

    });

});
</script>

</body>
</html>
