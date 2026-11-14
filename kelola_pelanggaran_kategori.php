
<?php
// kelola_pelanggaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// =====================================
// AMBIL DATA PELANGGARAN
// =====================================

$sql = "SELECT
            p.id,
            p.nama,
            p.poin,
            p.pelanggaran_kategori_id,
            k.nama AS nama_kategori
        FROM t_pelanggaran p
        LEFT JOIN t_pelanggaran_kategori k
            ON p.pelanggaran_kategori_id = k.id
        ORDER BY p.id DESC";

$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}

$pesan = $_GET['pesan'] ?? '';

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Pelanggaran - SIPENSIS</title>

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
            color: #212529;
            overflow-x: hidden;
        }

        /* SIDEBAR */

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            min-height: 100vh;
            background: #1a1d20;
            z-index: 1000;
            overflow-y: auto;
        }

        /* KONTEN UTAMA */

        .container-fluid {
            padding: 0;
        }

        .row {
            display: block;
            margin: 0;
        }

        main {
            display: block;
            margin-left: 260px !important;
            width: calc(100% - 260px) !important;
            max-width: none !important;
            min-height: 100vh;
            padding: 15px 25px 25px !important;
            background: #f4f5f7;
        }

        /* JUDUL */

        .judul-halaman {
            font-size: 26px;
            font-weight: 700;
            margin: 0 0 18px;
            color: #212529;
        }

        /* CARD */

        .card {
            width: 100%;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 16px 20px;
        }

        .judul-card {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            color: #212529;
        }

        .card-body {
            padding: 18px;
        }

        /* TOMBOL */

        .btn-tambah {
            background: #212529;
            border-color: #212529;
            color: white;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-tambah:hover {
            background: #000;
            color: white;
        }

        .btn-kembali {
            background: #6c757d;
            border-color: #6c757d;
            color: white;
            font-size: 13px;
        }

        .btn-kembali:hover {
            background: #545b62;
            color: white;
        }

        .btn-edit {
            background: #ffc107;
            border-color: #ffc107;
            color: white;
        }

        .btn-hapus {
            background: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn-aksi {
            font-size: 12px;
            padding: 7px 10px;
            margin-right: 3px;
        }

        /* TABEL */

        #tabelPelanggaran {
            width: 100% !important;
            font-size: 13px;
        }

        #tabelPelanggaran thead th {
            background: #212529;
            color: white;
            font-weight: 700;
            padding: 13px 10px;
            white-space: nowrap;
            vertical-align: middle;
        }

        #tabelPelanggaran tbody td {
            padding: 13px 10px;
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background: #f1f1f1;
        }

        /* DATATABLES */

        .dataTables_wrapper {
            font-size: 13px;
            padding-top: 5px;
        }

        /* BARIS PENCARIAN */

        .dataTables_wrapper > .row:first-child {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .dataTables_wrapper > .row:first-child > div {
            width: 100%;
            max-width: 100%;
            flex: 0 0 100%;
            text-align: left !important;
        }

        .dataTables_wrapper .dataTables_length {
            float: left;
            text-align: left;
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_filter {
            float: left !important;
            text-align: left !important;
            margin-bottom: 10px;
            width: 100%;
        }

        .dataTables_wrapper .dataTables_filter label {
            display: flex;
            align-items: center;
            justify-content: flex-start !important;
            gap: 5px;
            white-space: nowrap;
            font-weight: 400;
        }

        .dataTables_wrapper .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
            font-weight: 400;
        }

        .dataTables_wrapper .dataTables_length select {
            width: 65px;
            height: 34px;
            padding: 4px 8px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            margin: 0 3px;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 180px;
            height: 34px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 5px 8px;
            outline: none;
            margin-left: 3px;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 3px rgba(0,123,255,.15);
        }

        .dataTables_wrapper .dataTables_info {
            padding-top: 10px;
        }

        .dataTables_wrapper .page-item.active .page-link {
            background: #212529;
            border-color: #212529;
            color: white;
        }

        .dataTables_wrapper .page-link {
            color: #212529;
            font-size: 13px;
        }

        .badge {
            font-size: 11px;
            padding: 6px 8px;
        }

        /* RESPONSIVE */

        @media(max-width:768px) {

            #sidebar {
                width: 0;
                overflow: hidden;
            }

            main {
                margin-left: 0 !important;
                width: 100% !important;
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

            .dataTables_wrapper .dataTables_filter input {
                width: 140px;
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
        <main>

            <h3 class="judul-halaman">
                <i class="bi bi-exclamation-triangle mr-2"></i>
                Kelola Pelanggaran
            </h3>

            <!-- NOTIFIKASI -->

            <?php if ($pesan == 'hapus_berhasil') { ?>

                <div class="alert alert-success alert-dismissible fade show">
                    Data pelanggaran berhasil dihapus.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>

            <?php } elseif ($pesan == 'hapus_gagal') { ?>

                <div class="alert alert-danger alert-dismissible fade show">
                    Data pelanggaran gagal dihapus.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>

            <?php } ?>

            <!-- CARD DATA PELANGGARAN -->

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="judul-card">
                        <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                        Data Pelanggaran
                    </h5>

                    <a href="tambah_pelanggaran.php"
                       class="btn btn-tambah btn-sm">

                        <i class="bi bi-plus-lg mr-1"></i>
                        Tambah Pelanggaran

                    </a>

                </div>

                <div class="card-body">

                    <!-- KEMBALI -->

                    <div class="mb-4">

                        <a href="dashboard.php"
                           class="btn btn-kembali btn-sm">

                            <i class="bi bi-arrow-left mr-1"></i>
                            Kembali ke Dashboard

                        </a>

                    </div>

                    <!-- TABEL -->

                    <div class="table-responsive">

                        <table id="tabelPelanggaran"
                               class="table table-striped table-bordered table-hover">

                            <thead>

                                <tr>
                                    <th>No.</th>
                                    <th>Nama Pelanggaran</th>
                                    <th>Kategori</th>
                                    <th>Poin</th>
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
                                        <strong>
                                            <?= htmlspecialchars(
                                                (string)$row['nama'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string)($row['nama_kategori'] ?? '-'),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td>
                                        <span class="badge badge-warning">
                                            <?= (int)$row['poin']; ?> Poin
                                        </span>
                                    </td>

                                    <td style="white-space:nowrap;">

                                        <a href="edit_pelanggaran.php?id=<?= (int)$row['id']; ?>"
                                           class="btn btn-edit btn-sm btn-aksi"
                                           title="Edit">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>

                                        <a href="hapus_pelanggaran.php?id=<?= (int)$row['id']; ?>"
                                           class="btn btn-hapus btn-sm btn-aksi"
                                           title="Hapus"
                                           onclick="return confirm('Yakin ingin menghapus pelanggaran ini?');">

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

</div>

<!-- JAVASCRIPT -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>

$(document).ready(function () {

    $('#tabelPelanggaran').DataTable({

        language: {

            search: "Cari:",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data pelanggaran",

            infoEmpty: "Tidak ada data pelanggaran",

            infoFiltered: "(difilter dari _MAX_ total data)",

            zeroRecords: "Data pelanggaran tidak ditemukan",

            emptyTable: "Belum ada data pelanggaran",

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
                targets: [0, 4],
                orderable: false
            }
        ],

        order: [[0, 'asc']]

    });

});

</script>

</body>
</html>
