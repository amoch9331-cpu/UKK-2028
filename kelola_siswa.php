
<?php
// kelola_siswa.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// =====================================
// AMBIL DATA SISWA
// =====================================

$sql = "SELECT * FROM t_siswa ORDER BY nama ASC";
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

<title>Kelola Siswa - SIPENSIS</title>

<!-- BOOTSTRAP -->
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<!-- FONT AWESOME -->
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
    font-size: 13px;
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
    margin: 0 0 5px;
    color: #212529;
}

.deskripsi-halaman {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 20px;
}

/* CARD */

.card {
    width: 100%;
    border: none;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.card-header {
    background: white;
    border-bottom: 1px solid #dee2e6;
    padding: 16px 20px;
}

.judul-card {
    font-size: 17px;
    font-weight: 700;
    margin: 0;
    color: #212529;
}

.card-body {
    padding: 20px;
}

/* TOMBOL */

.btn-tambah {
    background: #212529;
    border: 1px solid #212529;
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

.btn-aksi {
    padding: 6px 10px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 4px;
    margin: 1px;
}

/* TABEL */

.table-responsive {
    width: 100%;
}

#tabelSiswa {
    width: 100% !important;
    border-collapse: collapse;
    font-size: 13px;
}

#tabelSiswa thead th {
    background: #212529;
    color: white;
    padding: 12px 10px;
    border-color: #343a40;
    white-space: nowrap;
    vertical-align: middle;
}

#tabelSiswa tbody td {
    padding: 10px;
    vertical-align: middle;
    border-color: #dee2e6;
}

#tabelSiswa tbody tr:hover {
    background: #eef2f7;
}

.table-striped tbody tr:nth-of-type(odd) {
    background: #f1f1f1;
}

/* STATUS */

.badge-success {
    background: #28a745;
    padding: 5px 8px;
    font-size: 11px;
}

.badge-danger {
    background: #dc3545;
    padding: 5px 8px;
    font-size: 11px;
}

/* =================================
   DATATABLES
================================= */

.dataTables_wrapper {
    padding-top: 8px;
    font-size: 13px;
}

/* BARIS PENCARIAN DAN JUMLAH DATA */

.dataTables_wrapper .row:first-child {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 0 0 15px;
}

/* PENCARIAN SEBELAH KIRI */

.dataTables_wrapper .dataTables_filter {
    float: none !important;
    text-align: left !important;
    margin: 0;
    width: 100%;
}

.dataTables_wrapper .dataTables_filter label {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 8px;
    white-space: nowrap;
    font-weight: 600;
    margin: 0;
}

.dataTables_wrapper .dataTables_filter input {
    width: 350px !important;
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 13px;
    outline: none;
    margin: 0;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.15);
}

/* JUMLAH DATA SEBELAH KANAN */

.dataTables_wrapper .dataTables_length {
    float: none !important;
    text-align: right;
    margin: 0;
}

.dataTables_wrapper .dataTables_length label {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 5px;
    white-space: nowrap;
    font-weight: 400;
    margin: 0;
}

.dataTables_wrapper .dataTables_length select {
    width: 65px !important;
    height: 34px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    padding: 4px 8px;
}

/* INFORMASI TABEL */

.dataTables_wrapper .dataTables_info {
    padding-top: 12px;
}

/* PAGINATION */

.dataTables_wrapper .page-item.active .page-link {
    background: #212529;
    border-color: #212529;
    color: white;
}

.dataTables_wrapper .page-link {
    color: #212529;
    font-size: 13px;
}

/* RESPONSIVE */

@media (max-width: 768px) {

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

    .dataTables_wrapper .row:first-child {
        display: block;
    }

    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 15px;
    }

    .dataTables_wrapper .dataTables_filter input {
        width: 230px !important;
        max-width: 100%;
    }

    .dataTables_wrapper .dataTables_length {
        text-align: left;
    }

    .dataTables_wrapper .dataTables_length label {
        justify-content: flex-start;
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

            <div class="mb-3">

                <h3 class="judul-halaman">
                    <i class="fas fa-users mr-2"></i>
                    Kelola Siswa
                </h3>

                <p class="deskripsi-halaman">
                    Kelola dan pantau seluruh data siswa yang terdaftar dalam sistem.
                </p>

            </div>

            <!-- CARD DATA SISWA -->

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="judul-card">
                        <i class="fas fa-users mr-2"></i>
                        Data Siswa
                    </h5>

                    <a href="tambah_siswa.php"
                       class="btn btn-tambah btn-sm">

                        <i class="fas fa-plus mr-1"></i>
                        Tambah Siswa

                    </a>

                </div>

                <div class="card-body">

                    <!-- KEMBALI -->

                    <div class="mb-4">

                        <a href="dashboard.php"
                           class="btn btn-kembali btn-sm">

                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali ke Dashboard

                        </a>

                    </div>

                    <!-- TABEL SISWA -->

                    <div class="table-responsive">

                        <table id="tabelSiswa"
                               class="table table-striped table-bordered table-hover table-sm">

                            <thead>

                                <tr>
                                    <th>No.</th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Alamat</th>
                                    <th>Status Aktif</th>
                                    <th>Aksi</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php
                                $no = 1;

                                while ($row = mysqli_fetch_assoc($hasil)) {

                                    $nis = (string)$row['nis'];
                                ?>

                                <tr>

                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= htmlspecialchars($nis, ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)$row['nisn'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)$row['nama'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)$row['jenis_kelamin'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)$row['tanggal_lahir'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)$row['alamat'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>

                                        <?php if ((string)$row['status_aktif'] === '1') { ?>

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

                                        <!-- EDIT -->

                                        <a href="edit_siswa.php?nis=<?= urlencode($nis); ?>"
                                           class="btn btn-warning btn-sm btn-aksi">

                                            <i class="fas fa-edit"></i>
                                            Edit

                                        </a>

                                        <!-- HAPUS -->

                                        <a href="hapus_siswa.php?nis=<?= urlencode($nis); ?>"
                                           class="btn btn-danger btn-sm btn-aksi"
                                           onclick="return confirm('Apakah kamu yakin ingin menghapus data siswa ini?');">

                                            <i class="fas fa-trash"></i>
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

<!-- DATATABLES PENCARIAN -->

<script>

$(document).ready(function () {

    $('#tabelSiswa').DataTable({

        // PENCARIAN KIRI
        // JUMLAH DATA KANAN

        dom:
            '<"row mb-3"<"col-md-6"f><"col-md-6"l>>' +
            'rt' +
            '<"row mt-3"<"col-md-6"i><"col-md-6"p>>',

        language: {

            search: "Cari Siswa:",

            searchPlaceholder: "Masukkan NIS, NISN, atau Nama Siswa...",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data siswa",

            infoEmpty: "Tidak ada data siswa",

            infoFiltered: "(difilter dari _MAX_ total data)",

            zeroRecords: "Data siswa tidak ditemukan",

            emptyTable: "Belum ada data siswa",

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

        // HANYA NIS, NISN, DAN NAMA BISA DICARI

        columnDefs: [

            {
                targets: [0, 4, 5, 6, 7, 8],
                searchable: false
            },

            {
                targets: [0, 8],
                orderable: false
            }

        ],

        order: [[3, 'asc']]

    });

});

</script>

</body>
</html>
