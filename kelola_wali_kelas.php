
<?php
// kelola_wali_kelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT
            w.*,
            k.nama AS nama_kelas,
            g.nama AS nama_guru
        FROM t_wali_kelas w
        LEFT JOIN t_kelas k ON w.kelas_id = k.id
        LEFT JOIN t_guru g ON w.guru_id = g.id
        ORDER BY w.id DESC";

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

    <title>Kelola Wali Kelas</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        body {
            background-color: #f4f5f7;
            font-family: Arial, sans-serif;
            color: #212529;
        }

        main {
            min-height: 100vh;
        }

        .judul-halaman {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 3px;
            color: #212529;
        }

        .deskripsi-halaman {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 22px;
        }

        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
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
        }

        .card-body {
            padding: 18px;
        }

        .btn-tambah {
            background-color: #212529;
            border-color: #212529;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-tambah:hover {
            background-color: #343a40;
            color: #ffffff;
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

        #tabelWaliKelas {
            width: 100% !important;
            font-size: 13px;
        }

        #tabelWaliKelas thead th {
            background-color: #212529;
            color: white;
            font-weight: 700;
            padding: 15px 10px;
            vertical-align: middle;
            white-space: nowrap;
        }

        #tabelWaliKelas tbody td {
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
            padding-top: 10px;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 13px;
        }

        /* Kotak pencarian */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 5px 8px;
            outline: none;
            margin-left: 5px;
        }

        /* Kotak pilihan jumlah data */
        .dataTables_wrapper .dataTables_length select {
            width: 60px !important;
            height: 34px !important;
            padding: 4px 5px !important;
            margin: 0 5px !important;
            border: 1px solid #ced4da !important;
            border-radius: 5px !important;
            font-size: 13px !important;
            text-align: center;
            background-color: #ffffff !important;
            box-sizing: border-box;
            cursor: pointer;
            display: inline-block;
            vertical-align: middle;
        }

        /* Tulisan Tampilkan dan data */
        .dataTables_wrapper .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
            margin-bottom: 10px;
        }

        /* Pagination */
        .dataTables_wrapper .page-item.active .page-link {
            background-color: #212529;
            border-color: #212529;
        }

        .dataTables_wrapper .page-link {
            color: #212529;
        }

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
            <h3 class="judul-halaman">Kelola Wali Kelas</h3>

            <p class="deskripsi-halaman">
                Kelola dan pantau seluruh data wali kelas yang terdaftar dalam sistem.
            </p>

            <!-- NOTIFIKASI -->
            <?php if ($pesan == 'hapus_berhasil') { ?>

                <div class="alert alert-success alert-dismissible fade show">
                    Data wali kelas berhasil dihapus.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>

            <?php } elseif ($pesan == 'hapus_gagal') { ?>

                <div class="alert alert-danger alert-dismissible fade show">
                    Data wali kelas gagal dihapus.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>

            <?php } elseif ($pesan == 'data_tidak_ditemukan') { ?>

                <div class="alert alert-warning alert-dismissible fade show">
                    Data wali kelas tidak ditemukan.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>

            <?php } ?>

            <!-- CARD DATA WALI KELAS -->
            <div class="card">

                <!-- HEADER CARD -->
                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="judul-card">
                        <i class="bi bi-person-badge mr-2"></i>
                        Data Wali Kelas
                    </h5>

                    <a href="tambah_wali_kelas.php" class="btn btn-tambah btn-sm">
                        <i class="bi bi-plus-lg mr-1"></i>
                        Tambah Wali Kelas
                    </a>

                </div>

                <!-- ISI CARD -->
                <div class="card-body">

                    <!-- TOMBOL KEMBALI -->
                    <div class="mb-4">
                        <a href="dashboard.php" class="btn btn-kembali btn-sm">
                            <i class="bi bi-arrow-left mr-1"></i>
                            Kembali ke Dashboard
                        </a>
                    </div>

                    <!-- TABEL -->
                    <div class="table-responsive">

                        <table id="tabelWaliKelas"
                               class="table table-striped table-bordered table-hover">

                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>ID</th>
                                    <th>Tahun Ajaran ID</th>
                                    <th>Kelas</th>
                                    <th>Guru</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $no = 1;

                                while ($row = mysqli_fetch_assoc($hasil)) {
                                    $id = (string) $row['id'];
                                ?>

                                <tr>

                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) ($row['tahun_ajaran_id'] ?? ''),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) ($row['nama_kelas'] ?? '-'),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) ($row['nama_guru'] ?? '-'),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) ($row['tanggal_mulai'] ?? ''),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) ($row['tanggal_selesai'] ?? ''),
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

                                    <td style="white-space: nowrap;">

                                        <!-- TOMBOL EDIT -->
                                        <a href="edit_wali_kelas.php?id=<?= urlencode($id); ?>"
                                           class="btn btn-edit btn-sm btn-aksi">

                                            <i class="bi bi-pencil-square"></i>
                                            Edit

                                        </a>

                                        <!-- TOMBOL HAPUS -->
                                        <a href="hapus_wali_kelas.php?id=<?= urlencode($id); ?>"
                                           class="btn btn-hapus btn-sm btn-aksi"
                                           onclick="return confirm('Yakin ingin menghapus data wali kelas ini?');">

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

    $('#tabelWaliKelas').DataTable({

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data wali kelas",
            infoEmpty: "Tidak ada data wali kelas",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data wali kelas tidak ditemukan",
            emptyTable: "Belum ada data wali kelas",

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
