
<?php
// kelola_tahun_ajaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_tahun_ajaran ORDER BY tanggal_mulai DESC";
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

    <title>Kelola Tahun Ajaran</title>

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
            margin-bottom: 20px;
            color: #212529;
        }

        /* CARD */
        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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

        /* TOMBOL TAMBAH */
        .btn-tambah {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-tambah:hover {
            background-color: #0069d9;
            color: white;
        }

        /* TOMBOL KEMBALI */
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

        /* TABEL */
        #tabelTahunAjaran {
            width: 100% !important;
            font-size: 13px;
        }

        #tabelTahunAjaran thead th {
            background-color: #212529;
            color: white;
            font-weight: 700;
            padding: 13px 10px;
            vertical-align: middle;
            white-space: nowrap;
        }

        #tabelTahunAjaran tbody td {
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

        /* TOMBOL AKSI */
        .btn-aksi {
            font-size: 12px;
            font-weight: 600;
            padding: 7px 11px;
            margin-right: 3px;
        }

        .btn-edit {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .btn-edit:hover {
            background-color: #e0a800;
            color: #212529;
        }

        .btn-hapus {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn-hapus:hover {
            background-color: #c82333;
            color: white;
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

        /* LABEL TAMPILKAN */
        .dataTables_wrapper .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
            margin-bottom: 10px;
            font-weight: 400;
        }

        /* KOTAK ANGKA JUMLAH DATA */
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

        /* KOTAK PENCARIAN */
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

        /* INFORMASI JUMLAH DATA */
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

            <h3 class="judul-halaman">
                Kelola Tahun Ajaran
            </h3>

            <!-- NOTIFIKASI -->
            <?php
            $pesan = $_GET['pesan'] ?? '';

            if ($pesan == 'hapus_berhasil') {
            ?>
                <div class="alert alert-success alert-dismissible fade show">
                    Data tahun ajaran berhasil dihapus.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php
            } elseif ($pesan == 'hapus_gagal') {
            ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    Data tahun ajaran gagal dihapus.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php } ?>

            <!-- CARD DATA TAHUN AJARAN -->
            <div class="card">

                <!-- HEADER CARD -->
                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="judul-card">
                        <i class="bi bi-calendar3"></i>
                        Data Tahun Ajaran
                    </h5>

                    <a href="tambah_tahun_ajaran.php"
                       class="btn btn-tambah btn-sm">

                        <i class="bi bi-plus-lg mr-1"></i>
                        Tambah Tahun Ajaran

                    </a>

                </div>

                <!-- BODY CARD -->
                <div class="card-body">

                    <!-- TOMBOL KEMBALI -->
                    <div class="mb-4">

                        <a href="dashboard.php"
                           class="btn btn-kembali btn-sm">

                            <i class="bi bi-arrow-left mr-1"></i>
                            Kembali ke Dashboard

                        </a>

                    </div>

                    <!-- TABEL -->
                    <div class="table-responsive">

                        <table id="tabelTahunAjaran"
                               class="table table-striped table-bordered table-hover">

                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>ID</th>
                                    <th>Nama Tahun Ajaran</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status Aktif</th>
                                    <th>Dibuat</th>
                                    <th>Diubah</th>
                                    <th>Aksi</th>
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
                                            (string) $row['id'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) $row['nama'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) $row['tanggal_mulai'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) $row['tanggal_selesai'],
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

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) ($row['created_at'] ?? ''),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) ($row['updated_at'] ?? ''),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td style="white-space: nowrap;">

                                        <!-- TOMBOL EDIT -->
                                        <a href="edit_tahun_ajaran.php?id=<?= urlencode((string) $row['id']); ?>"
                                           class="btn btn-edit btn-sm btn-aksi">

                                            <i class="bi bi-pencil-square"></i>
                                            Edit

                                        </a>

                                        <!-- TOMBOL HAPUS -->
                                        <a href="hapus_tahun_ajaran.php?id=<?= urlencode((string) $row['id']); ?>"
                                           class="btn btn-hapus btn-sm btn-aksi"
                                           onclick="return confirm('Yakin ingin menghapus data tahun ajaran ini?');">

                                            <i class="bi bi-trash"></i>
                                            Hapus

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
</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<!-- DATATABLES -->
<script>
$(document).ready(function () {

    $('#tabelTahunAjaran').DataTable({

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data tahun ajaran",
            infoEmpty: "Tidak ada data tahun ajaran",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data tahun ajaran tidak ditemukan",
            emptyTable: "Belum ada data tahun ajaran",

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
                targets: [0, 8],
                orderable: false
            }
        ],

        order: [[1, 'desc']]

    });

});
</script>

</body>
</html>
