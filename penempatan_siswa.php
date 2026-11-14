
<?php
// ==========================================
// PENEMPATAN SISWA - SIPENSIS
// ==========================================

include 'includes/cek_session.php';
include 'config/koneksi.php';

// ==========================================
// PROSES TAMBAH PENEMPATAN
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['simpan_penempatan'])) {

    $siswa_id = (int)($_POST['siswa_id'] ?? 0);
    $tahun_ajaran_id = (int)($_POST['tahun_ajaran_id'] ?? 0);
    $kelas_id = (int)($_POST['kelas_id'] ?? 0);
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = !empty($_POST['tanggal_selesai'])
        ? $_POST['tanggal_selesai'] : null;
    $status_aktif = (int)($_POST['status_aktif'] ?? 0);

    if (
        $siswa_id <= 0 ||
        $tahun_ajaran_id <= 0 ||
        $kelas_id <= 0 ||
        $tanggal_mulai === '' ||
        !in_array($status_aktif, [0, 1], true)
    ) {
        $_SESSION['pesan_error'] = 'Semua data wajib diisi dengan benar.';
    } elseif (
        $tanggal_selesai !== null &&
        $tanggal_selesai < $tanggal_mulai
    ) {
        $_SESSION['pesan_error'] = 'Tanggal selesai tidak boleh sebelum tanggal mulai.';
    } else {

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
                $_SESSION['pesan_sukses'] =
                    'Penempatan siswa berhasil ditambahkan!';
            } else {
                $_SESSION['pesan_error'] =
                    'Gagal menyimpan: ' . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);

        } else {
            $_SESSION['pesan_error'] =
                'Query gagal: ' . mysqli_error($koneksi);
        }
    }

    header("Location: penempatan_siswa.php");
    exit;
}

// ==========================================
// TAMPILKAN FORM TAMBAH
// ==========================================

$tampilkan_form = isset($_GET['tambah']);

// ==========================================
// AMBIL DATA DROPDOWN
// ==========================================

if ($tampilkan_form) {

    $query_siswa = mysqli_query($koneksi, "
        SELECT id, nis, nama
        FROM t_siswa
        WHERE status_aktif = 1
        ORDER BY nama ASC
    ");

    $query_tahun = mysqli_query($koneksi, "
        SELECT id, nama
        FROM t_tahun_ajaran
        ORDER BY id DESC
    ");

    $query_kelas = mysqli_query($koneksi, "
        SELECT id, nama, tingkat, jurusan
        FROM t_kelas
        ORDER BY nama ASC
    ");

}

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

<title>Penempatan Siswa - SIPENSIS</title>

<!-- BOOTSTRAP -->
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<!-- BOOTSTRAP ICONS -->
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- DATATABLES -->
<link rel="stylesheet"
href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    background: #f4f5f7;
    font-family: Arial, sans-serif;
    font-size: 13px;
    color: #212529;
    overflow-x: hidden;
}

/* =================================
   LAYOUT SIDEBAR
================================= */

.layout-wrapper {
    display: flex;
    min-height: 100vh;
    width: 100%;
}

#sidebar {
    flex-shrink: 0;
}

/* =================================
   KONTEN UTAMA
================================= */

.dashboard-content {
    flex: 1;
    min-width: 0;
    padding: 25px;
    min-height: 100vh;
    background: #f4f5f7;
}

/* =================================
   JUDUL
================================= */

.judul-halaman {
    font-size: 26px;
    font-weight: 700;
    color: #212529;
    margin-bottom: 5px;
}

.deskripsi-halaman {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 22px;
}

/* =================================
   CARD
================================= */

.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 20px;
    background: white;
}

.card-header {
    background: white;
    padding: 17px 20px;
    border-bottom: 1px solid #dee2e6;
}

.judul-card {
    font-size: 17px;
    font-weight: 700;
    margin: 0;
}

.card-body {
    padding: 22px;
}

/* =================================
   FORM
================================= */

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    font-weight: 600;
    font-size: 13px;
    margin-bottom: 8px;
}

.form-control {
    height: 42px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 13px;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 3px rgba(0,123,255,.12);
}

/* =================================
   TOMBOL
================================= */

.btn-black {
    background: #212529;
    border: 1px solid #212529;
    color: white;
    font-size: 13px;
    font-weight: 600;
}

.btn-black:hover {
    background: #000;
    color: white;
}

.btn-kembali {
    background: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-kembali:hover {
    background: #545b62;
    color: white;
}

/* =================================
   TABEL
================================= */

#tabelPenempatan {
    width: 100% !important;
    font-size: 12px;
}

#tabelPenempatan thead th {
    background: #212529;
    color: white;
    font-weight: 700;
    padding: 13px 10px;
    vertical-align: middle;
    white-space: nowrap;
}

#tabelPenempatan tbody td {
    padding: 12px 10px;
    vertical-align: middle;
}

.table-striped tbody tr:nth-of-type(odd) {
    background: #f1f1f1;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.badge {
    padding: 6px 9px;
    font-size: 11px;
}

/* =================================
   DATATABLES
================================= */

.dataTables_wrapper {
    font-size: 13px;
}

/* Pencarian di sebelah kiri */
.dataTables_wrapper .dataTables_filter {
    text-align: left !important;
    margin-bottom: 15px;
}

.dataTables_wrapper .dataTables_filter label {
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: flex-start;
    margin: 0;
}

.dataTables_wrapper .dataTables_filter input {
    height: 35px;
    width: 185px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 5px 10px;
    margin-left: 0 !important;
    outline: none;
}

/* Tampilkan data di sebelah kanan */
.dataTables_wrapper .dataTables_length {
    text-align: right;
    margin-bottom: 15px;
}

.dataTables_wrapper .dataTables_length label {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    margin: 0;
    white-space: nowrap;
}

.dataTables_wrapper .dataTables_length select {
    width: 60px !important;
    height: 35px;
    padding: 4px 8px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    background: white;
}

/* Pagination */
.dataTables_wrapper .page-item.active .page-link {
    background: #212529;
    border-color: #212529;
}

.dataTables_wrapper .page-link {
    color: #212529;
}

/* =================================
   RESPONSIVE
================================= */

@media(max-width: 768px) {

    .layout-wrapper {
        flex-direction: column;
    }

    .dashboard-content {
        padding: 15px;
    }

    .judul-halaman {
        font-size: 22px;
    }

    .card-body {
        padding: 15px;
    }

    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_length {
        text-align: left;
        margin-bottom: 12px;
    }

    .dataTables_wrapper .dataTables_filter input {
        width: 150px;
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

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- KONTEN UTAMA -->
    <main class="dashboard-content">

        <!-- JUDUL -->
        <h3 class="judul-halaman">
            <i class="bi bi-person-lines-fill"></i>
            Penempatan Siswa
        </h3>

        <p class="deskripsi-halaman">
            Kelola data penempatan siswa berdasarkan tahun ajaran dan kelas.
        </p>

        <!-- TOMBOL KEMBALI -->
        <a href="dashboard.php"
           class="btn btn-kembali btn-sm mb-3 d-print-none">

            <i class="bi bi-arrow-left"></i>
            Kembali ke Dashboard

        </a>

        <!-- NOTIFIKASI -->
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


        <!-- =================================
             FORM TAMBAH PENEMPATAN
        ================================== -->

        <?php if ($tampilkan_form) { ?>

        <div class="card">

            <div class="card-header">

                <h5 class="judul-card">
                    <i class="bi bi-plus-circle mr-2"></i>
                    Form Tambah Penempatan Siswa
                </h5>

            </div>

            <div class="card-body">

                <a href="penempatan_siswa.php"
                   class="btn btn-kembali btn-sm mb-4">

                    <i class="bi bi-arrow-left"></i>
                    Kembali

                </a>

                <form method="POST" action="penempatan_siswa.php">

                    <!-- SISWA -->
                    <div class="form-group">

                        <label>Nama Siswa</label>

                        <select name="siswa_id"
                                class="form-control"
                                required>

                            <option value="">-- Pilih Siswa --</option>

                            <?php while ($s = mysqli_fetch_assoc($query_siswa)) { ?>

                                <option value="<?= (int)$s['id']; ?>">

                                    <?= htmlspecialchars(
                                        $s['nis'] . ' - ' . $s['nama'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <!-- TAHUN AJARAN -->
                    <div class="form-group">

                        <label>Tahun Ajaran</label>

                        <select name="tahun_ajaran_id"
                                class="form-control"
                                required>

                            <option value="">-- Pilih Tahun Ajaran --</option>

                            <?php while ($ta = mysqli_fetch_assoc($query_tahun)) { ?>

                                <option value="<?= (int)$ta['id']; ?>">

                                    <?= htmlspecialchars($ta['nama']); ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <!-- KELAS -->
                    <div class="form-group">

                        <label>Kelas</label>

                        <select name="kelas_id"
                                class="form-control"
                                required>

                            <option value="">-- Pilih Kelas --</option>

                            <?php while ($k = mysqli_fetch_assoc($query_kelas)) { ?>

                                <option value="<?= (int)$k['id']; ?>">

                                    <?= htmlspecialchars(
                                        $k['nama'] . ' - ' .
                                        $k['tingkat'] . ' - ' .
                                        $k['jurusan']
                                    ); ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <!-- TANGGAL MULAI -->
                    <div class="form-group">

                        <label>Tanggal Mulai</label>

                        <input type="date"
                               name="tanggal_mulai"
                               class="form-control"
                               required>

                    </div>

                    <!-- TANGGAL SELESAI -->
                    <div class="form-group">

                        <label>Tanggal Selesai</label>

                        <input type="date"
                               name="tanggal_selesai"
                               class="form-control">

                    </div>

                    <!-- STATUS -->
                    <div class="form-group">

                        <label>Status Penempatan</label>

                        <select name="status_aktif"
                                class="form-control"
                                required>

                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>

                        </select>

                    </div>

                    <hr>

                    <!-- TOMBOL -->
                    <div class="d-flex justify-content-between">

                        <a href="penempatan_siswa.php"
                           class="btn btn-kembali px-4">

                            <i class="bi bi-arrow-left"></i>
                            Kembali

                        </a>

                        <button type="submit"
                                name="simpan_penempatan"
                                class="btn btn-black px-4">

                            <i class="bi bi-save"></i>
                            Simpan Penempatan

                        </button>

                    </div>

                </form>

            </div>

        </div>

        <?php } else { ?>


        <!-- =================================
             TABEL DATA PENEMPATAN
             HANYA TAMPIL JIKA BUKAN FORM TAMBAH
        ================================== -->

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="judul-card">

                    <i class="bi bi-people-fill mr-2"></i>
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
                           class="table table-striped table-bordered table-hover">

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

                                        <span class="badge badge-success">
                                            Aktif
                                        </span>

                                    <?php } else { ?>

                                        <span class="badge badge-danger">
                                            Tidak Aktif
                                        </span>

                                    <?php } ?>

                                </td>

                                <td style="white-space:nowrap;">

                                    <!-- EDIT -->
                                    <a href="edit_penempatan.php?id=<?= (int)$row['id']; ?>"
                                       class="btn btn-warning btn-sm text-white"
                                       title="Edit">

                                        <i class="bi bi-pencil-square"></i>

                                    </a>

                                    <!-- HAPUS -->
                                    <a href="hapus_penempatan.php?id=<?= (int)$row['id']; ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus data penempatan ini?')"
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

        <?php } ?>

    </main>

</div>


<!-- =================================
     JAVASCRIPT
================================= -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>

$(document).ready(function() {

    // Jalankan DataTables hanya jika tabel tersedia
    if ($('#tabelPenempatan').length) {

        $('#tabelPenempatan').DataTable({

            // CARI KIRI, TAMPILKAN DATA KANAN
            dom:
                '<"row mb-3"' +
                    '<"col-sm-6"f>' +
                    '<"col-sm-6"l>' +
                '>' +
                'rt' +
                '<"row mt-3"' +
                    '<"col-sm-6"i>' +
                    '<"col-sm-6"p>' +
                '>',

            language: {

                search: "Cari:",

                lengthMenu: "Tampilkan _MENU_ data",

                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

                infoEmpty: "Tidak ada data",

                infoFiltered: "(difilter dari _MAX_ total data)",

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

            paging: true,

            autoWidth: false,

            columnDefs: [

                {
                    targets: [0, 10],
                    orderable: false
                }

            ],

            order: [[2, 'asc']]

        });

    }

});

</script>

</body>
</html>
