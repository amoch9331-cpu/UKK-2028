
<?php
// kelola_kelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_kelas ORDER BY nama ASC";
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

    <title>Kelola Kelas</title>

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

        #tabelKelas {
            width: 100% !important;
            font-size: 13px;
        }

        #tabelKelas thead th {
            background-color: #212529;
            color: white;
            font-weight: 700;
            padding: 15px 10px;
            vertical-align: middle;
            white-space: nowrap;
        }

        #tabelKelas tbody td {
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
        }

        /* Memperbaiki kotak pilihan jumlah data */
        .dataTables_wrapper .dataTables_length select {
            width: 60px !important;
            height: 34px;
            padding: 5px 8px;
            margin: 0 5px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 13px;
            text-align: center;
            background-color: #fff;
            box-sizing: border-box;
            cursor: pointer;
            display: inline-block;
        }

       
/* ==============================
   DATATABLES
============================== */

.dataTables_wrapper {
    font-size: 13px;
}

/* Bagian tampilkan data dan pencarian */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 13px;
}

/* Tulisan Tampilkan dan data */
.dataTables_wrapper .dataTables_length label {
    display: inline-flex !important;
    align-items: center !important;
    gap: 7px;
    margin: 0;
    white-space: nowrap;
    line-height: 34px;
    font-weight: normal;
}

/* Kotak pilihan jumlah data */
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_length .custom-select {
    width: 60px !important;
    min-width: 60px !important;
    height: 34px !important;
    padding: 4px 8px !important;
    margin: 0 !important;
    border: 1px solid #ced4da !important;
    border-radius: 5px !important;
    background-color: #fff !important;
    color: #212529 !important;
    font-family: Arial, sans-serif;
    font-size: 13px !important;
    line-height: 24px !important;
    text-align: center !important;
    text-align-last: center !important;
    box-sizing: border-box !important;
    vertical-align: middle !important;
    cursor: pointer;
}

/* Kotak pencarian */
.dataTables_wrapper .dataTables_filter input {
    height: 34px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    padding: 5px 8px;
    outline: none;
}

/* Pagination */
.dataTables_wrapper .page-item.active .page-link {
    background-color: #212529;
    border-color: #212529;
}

.dataTables_wrapper .page-link {
    color: #212529;
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
            <h3 class="judul-halaman">Kelola Kelas</h3>

            <p class="deskripsi-halaman">
                Kelola dan pantau seluruh data kelas yang terdaftar dalam sistem.
            </p>

            <!-- NOTIFIKASI -->
            <?php if ($pesan == 'hapus_berhasil') { ?>

                <div class="alert alert-success alert-dismissible fade show">
                    Data kelas berhasil dihapus.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>

            <?php } elseif ($pesan == 'hapus_gagal') { ?>

                <div class="alert alert-danger alert-dismissible fade show">
                    Data kelas gagal dihapus.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>

            <?php } elseif ($pesan == 'data_tidak_ditemukan') { ?>

                <div class="alert alert-warning alert-dismissible fade show">
                    Data kelas tidak ditemukan.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>

            <?php } ?>

            <!-- CARD DATA KELAS -->
            <div class="card">

                <!-- HEADER CARD -->
                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="judul-card">
                        <i class="bi bi-building mr-2"></i>
                        Data Kelas
                    </h5>

                    <a href="tambah_kelas.php" class="btn btn-tambah btn-sm">
                        <i class="bi bi-plus-lg mr-1"></i>
                        Tambah Kelas
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

                        <table id="tabelKelas"
                               class="table table-striped table-bordered table-hover">

                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>ID</th>
                                    <th>Nama Kelas</th>
                                    <th>Tingkat</th>
                                    <th>Jurusan</th>
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
                                        <?= htmlspecialchars((string)$row['id'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)$row['nama'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)$row['tingkat'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)$row['jurusan'], ENT_QUOTES, 'UTF-8'); ?>
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

                                    <td>
                                        <?= htmlspecialchars((string)($row['created_at'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)($row['updated_at'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td style="white-space: nowrap;">

                                        <!-- TOMBOL EDIT -->
                                        <a href="edit_kelas.php?id=<?= urlencode((string)$row['id']); ?>"
                                           class="btn btn-edit btn-sm btn-aksi">

                                            <i class="bi bi-pencil-square"></i>
                                            Edit

                                        </a>

                                        <!-- TOMBOL HAPUS -->
                                        <form action="hapus_kelas.php"
                                              method="POST"
                                              style="display:inline;"
                                              onsubmit="return confirm('Yakin ingin menghapus data kelas ini?');">

                                            <input type="hidden"
                                                   name="id"
                                                   value="<?= htmlspecialchars((string)$row['id'], ENT_QUOTES, 'UTF-8'); ?>">

                                            <button type="submit"
                                                    class="btn btn-hapus btn-sm btn-aksi">

                                                <i class="bi bi-trash"></i>
                                                Hapus

                                            </button>

                                        </form>

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

    $('#tabelKelas').DataTable({

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data kelas",
            infoEmpty: "Tidak ada data kelas",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data kelas tidak ditemukan",
            emptyTable: "Belum ada data kelas",

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

        order: [[2, 'asc']]

    });

});
</script>

</body>
</html>
