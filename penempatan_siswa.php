
<?php
// penempatan_siswa.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil daftar siswa aktif
$siswa = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama
     FROM t_siswa
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Ambil daftar tahun ajaran aktif
$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     WHERE status_aktif = 1
     ORDER BY id DESC"
);

// Ambil daftar kelas aktif
$kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Periksa query pilihan
if (!$siswa || !$tahun || !$kelas) {
    die("Query pilihan gagal: " . mysqli_error($koneksi));
}

// Ambil data penempatan siswa
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

    <title>Penempatan Siswa</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>

        body {
            background-color: #f4f5f7;
            font-family: Arial, sans-serif;
            color: #212529;
        }

        main {
            min-height: 100vh;
        }

        /* JUDUL HALAMAN */
        .judul-halaman {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #212529;
        }

        .deskripsi-halaman {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 22px;
        }

        /* CARD */
        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 22px;
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
            padding: 16px 20px;
        }

        .judul-card {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            color: #212529;
        }

        .judul-card i {
            margin-right: 8px;
            font-size: 18px;
        }

        .card-body {
            padding: 18px;
        }

        /* TOMBOL */
        .btn-simpan {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-simpan:hover {
            background-color: #0069d9;
            color: white;
        }

        .btn-kembali {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
            font-size: 13px;
        }

        .btn-kembali:hover {
            background-color: #5a6268;
            color: white;
        }

        /* FORM */
        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #343a40;
        }

        .form-control {
            font-size: 13px;
            border-radius: 5px;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
        }

        /* TABEL */
        #tabelPenempatan {
            width: 100% !important;
            font-size: 13px;
        }

        #tabelPenempatan thead th {
            background-color: #212529;
            color: white;
            font-weight: 700;
            padding: 13px 10px;
            vertical-align: middle;
            white-space: nowrap;
        }

        #tabelPenempatan tbody td {
            padding: 13px 10px;
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f1f1f1;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .badge {
            font-size: 11px;
            padding: 5px 7px;
        }

        /* DATATABLES */
        .dataTables_wrapper {
            font-size: 13px;
            padding-top: 5px;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 13px;
        }

        /* TAMPILKAN DATA */
        .dataTables_wrapper .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
            margin-bottom: 10px;
            font-weight: 400;
        }

        /* KOTAK ANGKA */
        .dataTables_wrapper .dataTables_length select {
            width: 64px !important;
            height: 34px !important;
            padding: 4px 8px !important;
            margin: 0 3px !important;
            border: 1px solid #ced4da !important;
            border-radius: 5px !important;
            background-color: #ffffff !important;
            font-size: 13px !important;
            text-align: center;
            cursor: pointer;
            box-sizing: border-box;
            display: inline-block;
        }

        /* PENCARIAN */
        .dataTables_wrapper .dataTables_filter label {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 5px;
            white-space: nowrap;
            font-weight: 400;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 160px;
            height: 34px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 5px 8px;
            outline: none;
            margin-left: 3px;
        }

        .dataTables_wrapper .dataTables_filter input:focus,
        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #80bdff !important;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
        }

        /* INFORMASI DATA */
        .dataTables_wrapper .dataTables_info {
            padding-top: 10px;
        }

        /* PAGINATION */
        .dataTables_wrapper .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        .dataTables_wrapper .page-link {
            color: #007bff;
            font-size: 13px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {

            main {
                padding: 15px !important;
            }

            .judul-halaman {
                font-size: 22px;
            }

            .card-body {
                padding: 12px;
            }

            .card-header {
                padding: 12px;
            }

            .judul-card {
                font-size: 16px;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 130px;
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
        <main class="col-md-9 col-lg-10 p-4">

            <!-- JUDUL HALAMAN -->
            <h3 class="judul-halaman">
                Penempatan Siswa
            </h3>

            <p class="deskripsi-halaman">
                Kelola penempatan siswa berdasarkan tahun ajaran dan kelas.
            </p>

            <!-- TOMBOL KEMBALI -->
            <div class="mb-3">
                <a href="dashboard.php" class="btn btn-kembali btn-sm">
                    <i class="bi bi-arrow-left mr-1"></i>
                    Kembali ke Dashboard
                </a>
            </div>

            <!-- PESAN SUKSES -->
            <?php if (isset($_SESSION['pesan_sukses'])) { ?>

                <div class="alert alert-success alert-dismissible fade show">

                    <?= htmlspecialchars(
                        $_SESSION['pesan_sukses'],
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>

                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>

                </div>

                <?php unset($_SESSION['pesan_sukses']); ?>

            <?php } ?>

            <!-- PESAN ERROR -->
            <?php if (isset($_SESSION['pesan_error'])) { ?>

                <div class="alert alert-danger alert-dismissible fade show">

                    <?= htmlspecialchars(
                        $_SESSION['pesan_error'],
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>

                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>

                </div>

                <?php unset($_SESSION['pesan_error']); ?>

            <?php } ?>


            <!-- FORM PENEMPATAN SISWA -->
            <div class="card">

                <div class="card-header">

                    <h5 class="judul-card">
                        <i class="bi bi-person-plus-fill"></i>
                        Form Penempatan Siswa
                    </h5>

                </div>

                <div class="card-body">

                    <form action="proses_simpan_penempatan.php" method="POST">

                        <!-- PILIH SISWA -->
                        <div class="form-group">

                            <label for="siswa_id" class="form-label">
                                Siswa
                            </label>

                            <select name="siswa_id"
                                    id="siswa_id"
                                    class="form-control"
                                    required>

                                <option value="">-- Pilih Siswa --</option>

                                <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>

                                    <option value="<?= htmlspecialchars(
                                        (string) $s['id'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>">

                                        <?= htmlspecialchars(
                                            $s['nis'] . ' - ' . $s['nama'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- PILIH TAHUN AJARAN -->
                        <div class="form-group">

                            <label for="tahun_ajaran_id" class="form-label">
                                Tahun Ajaran
                            </label>

                            <select name="tahun_ajaran_id"
                                    id="tahun_ajaran_id"
                                    class="form-control"
                                    required>

                                <option value="">-- Pilih Tahun Ajaran --</option>

                                <?php while ($ta = mysqli_fetch_assoc($tahun)) { ?>

                                    <option value="<?= htmlspecialchars(
                                        (string) $ta['id'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>">

                                        <?= htmlspecialchars(
                                            $ta['nama'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- PILIH KELAS -->
                        <div class="form-group">

                            <label for="kelas_id" class="form-label">
                                Kelas
                            </label>

                            <select name="kelas_id"
                                    id="kelas_id"
                                    class="form-control"
                                    required>

                                <option value="">-- Pilih Kelas --</option>

                                <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>

                                    <option value="<?= htmlspecialchars(
                                        (string) $k['id'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>">

                                        <?= htmlspecialchars(
                                            $k['nama'] . ' - Tingkat ' .
                                            $k['tingkat'] . ' - ' . $k['jurusan'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- TANGGAL -->
                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label for="tanggal_mulai" class="form-label">
                                        Tanggal Mulai
                                    </label>

                                    <input type="date"
                                           name="tanggal_mulai"
                                           id="tanggal_mulai"
                                           class="form-control"
                                           required>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label for="tanggal_selesai" class="form-label">
                                        Tanggal Selesai
                                    </label>

                                    <input type="date"
                                           name="tanggal_selesai"
                                           id="tanggal_selesai"
                                           class="form-control"
                                           required>

                                </div>

                            </div>

                        </div>

                        <!-- STATUS -->
                        <div class="form-group">

                            <label for="status_aktif" class="form-label">
                                Status
                            </label>

                            <select name="status_aktif"
                                    id="status_aktif"
                                    class="form-control"
                                    required>

                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>

                            </select>

                        </div>

                        <!-- TOMBOL SIMPAN -->
                        <button type="submit" class="btn btn-simpan btn-sm">

                            <i class="bi bi-save mr-1"></i>
                            Simpan Penempatan

                        </button>

                    </form>

                </div>
            </div>


            <!-- TABEL DATA PENEMPATAN -->
            <div class="card">

                <div class="card-header">

                    <h5 class="judul-card">
                        <i class="bi bi-people-fill"></i>
                        Data Penempatan Siswa
                    </h5>

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
                                </tr>

                            </thead>

                            <tbody>

                                <?php
                                $no = 1;

                                while ($row = mysqli_fetch_assoc($hasil)) {
                                ?>

                                    <tr>

                                        <td><?= $no++; ?></td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row['nis'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row['nama_siswa'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row['tahun_ajaran'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row['nama_kelas'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                (string) ($row['tingkat'] ?? '-'),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row['jurusan'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row['tanggal_mulai'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row['tanggal_selesai'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </td>

                                        <td>

                                            <?php if ((int) $row['status_aktif'] === 1) { ?>

                                                <span class="badge badge-success">
                                                    Aktif
                                                </span>

                                            <?php } else { ?>

                                                <span class="badge badge-danger">
                                                    Tidak Aktif
                                                </span>

                                            <?php } ?>

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
</div>


<!-- JAVASCRIPT -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>


<!-- DATATABLES -->
<script>

$(document).ready(function () {

    $('#tabelPenempatan').DataTable({

        language: {

            search: "Cari:",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data penempatan siswa",

            infoEmpty: "Tidak ada data penempatan siswa",

            infoFiltered: "(difilter dari _MAX_ total data)",

            zeroRecords: "Data penempatan siswa tidak ditemukan",

            emptyTable: "Belum ada data penempatan siswa",

            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }

        },

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        searching: true,

        ordering: true,

        paging: true,

        autoWidth: false,

        columnDefs: [
            {
                targets: 0,
                orderable: false
            }
        ],

        order: [[1, 'asc']]

    });

});

</script>

</body>
</html>
