
<?php
// kelola_siswa.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Mengambil data siswa
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

    <title>Kelola Siswa - SIPENSI</title>

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
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f6f8;
            font-family: Arial, sans-serif;
            color: #212529;
        }

        /* KONTEN UTAMA */
        main {
            min-height: 100vh;
            padding: 25px !important;
        }

        .judul-halaman {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #212529;
        }

        .subjudul {
            color: #64748b;
            font-size: 15px;
            margin-bottom: 23px;
        }

        /* CARD */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header {
            padding: 17px 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .card-header h5 {
            font-size: 18px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        /* BUTTON */
        .btn-primary {
            background: #212529 !important;
            border-color: #212529 !important;
            border-radius: 6px;
        }

        .btn-primary:hover {
            background: #343a40 !important;
        }

        .btn-secondary {
            border-radius: 6px;
        }

        .btn-aksi {
            padding: 6px 10px;
            border-radius: 5px;
            margin: 2px;
            font-size: 13px;
        }

        /* TABLE */
        .table-responsive {
            width: 100%;
        }

        #tabelSiswa {
            width: 100% !important;
            border-collapse: collapse;
            font-size: 14px;
        }

        #tabelSiswa thead th {
            background: #212529;
            color: white;
            padding: 15px 10px;
            border-color: #343a40;
            white-space: nowrap;
            vertical-align: middle;
        }

        #tabelSiswa tbody td {
            padding: 13px 10px;
            vertical-align: middle;
            border-color: #dee2e6;
        }

        #tabelSiswa tbody tr:hover {
            background: #eef2f7;
        }

        /* BADGE */
        .badge-success {
            background: #28a745;
            padding: 5px 8px;
        }

        .badge-danger {
            background: #dc3545;
            padding: 5px 8px;
        }

        /* DATATABLES */
        .dataTables_wrapper {
            padding-top: 10px;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 5px 9px;
            outline: none;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #343a40;
            box-shadow: 0 0 0 2px rgba(33, 37, 41, 0.1);
        }

        .page-item.active .page-link {
            background: #212529;
            border-color: #212529;
        }

        .page-link {
            color: #212529;
        }

        .page-link:hover {
            color: #000;
        }

        /* NOTIFIKASI */
        .alert {
            border-radius: 8px;
            font-size: 14px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            main {
                padding: 15px !important;
            }

            .judul-halaman {
                font-size: 23px;
            }

            .card-body {
                padding: 15px;
            }

            .card-header {
                gap: 10px;
                flex-wrap: wrap;
            }

            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length {
                text-align: left !important;
                margin-bottom: 12px;
            }

            #tabelSiswa {
                font-size: 12px;
            }

            #tabelSiswa thead th,
            #tabelSiswa tbody td {
                padding: 10px 8px;
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
        <main class="col-md-9 col-lg-10">

            <!-- JUDUL -->
            <div class="mb-4">

                <h3 class="judul-halaman">
                    Kelola Siswa
                </h3>

                <p class="subjudul">
                    Kelola dan pantau seluruh data siswa yang terdaftar dalam sistem.
                </p>

            </div>


            <!-- NOTIFIKASI -->
            <?php
            if (isset($_GET['pesan'])) {

                $pesan = $_GET['pesan'];

                if ($pesan == 'hapus_berhasil') {
                    echo '
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill mr-2"></i>
                        Data siswa berhasil dihapus!
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>';

                } elseif ($pesan == 'hapus_gagal') {
                    echo '
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-circle-fill mr-2"></i>
                        Data siswa gagal dihapus! Periksa relasi database.
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>';

                } elseif ($pesan == 'data_tidak_ditemukan') {
                    echo '
                    <div class="alert alert-warning alert-dismissible fade show">
                        Data siswa tidak ditemukan!
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>';

                } elseif ($pesan == 'id_tidak_valid') {
                    echo '
                    <div class="alert alert-warning alert-dismissible fade show">
                        ID siswa tidak valid!
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>';
                }
            }
            ?>


            <!-- CARD DATA SISWA -->
            <div class="card">

                <!-- HEADER CARD -->
                <div class="card-header bg-white d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        <i class="bi bi-people-fill mr-2"></i>
                        Data Siswa
                    </h5>

                    <a href="tambah_siswa.php"
                        class="btn btn-primary btn-sm">

                        <i class="bi bi-plus-lg mr-1"></i>
                        Tambah Siswa

                    </a>

                </div>


                <!-- BODY CARD -->
                <div class="card-body">

                    <div class="mb-3">

                        <a href="dashboard.php"
                            class="btn btn-secondary btn-sm">

                            <i class="bi bi-arrow-left mr-1"></i>
                            Kembali ke Dashboard

                        </a>

                    </div>


                    <!-- TABEL SISWA -->
                    <div class="table-responsive">

                        <table id="tabelSiswa"
                            class="table table-striped table-bordered table-hover">

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

                                while ($siswa = mysqli_fetch_assoc($hasil)) {

                                    $nis = (string) ($siswa['nis'] ?? '');
                                    $id = (int) ($siswa['id'] ?? 0);
                                ?>

                                <tr>

                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= htmlspecialchars($nis, ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) ($siswa['nisn'] ?? ''),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) ($siswa['nama'] ?? ''),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) ($siswa['jenis_kelamin'] ?? ''),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) ($siswa['tanggal_lahir'] ?? ''),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string) ($siswa['alamat'] ?? ''),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </td>

                                    <td>

                                        <?php
                                        $status = $siswa['status_aktif'] ?? 0;

                                        if ((string) $status === '1') {
                                        ?>

                                            <span class="badge badge-success">
                                                Aktif
                                            </span>

                                        <?php } else { ?>

                                            <span class="badge badge-danger">
                                                Tidak Aktif
                                            </span>

                                        <?php } ?>

                                    </td>


                                    <!-- AKSI -->
                                    <td style="white-space: nowrap;">

                                        <!-- EDIT -->
                                        <a href="edit_siswa.php?nis=<?= urlencode($nis); ?>"
                                            class="btn btn-warning btn-sm btn-aksi">

                                            <i class="bi bi-pencil-square"></i>
                                            Edit

                                        </a>


                                        <!-- HAPUS -->
                                        <form action="hapus_siswa.php"
                                            method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Apakah kamu yakin ingin menghapus data siswa ini?');">

                                            <input type="hidden"
                                                name="id"
                                                value="<?= $id; ?>">

                                            <button type="submit"
                                                class="btn btn-danger btn-sm btn-aksi">

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

    $('#tabelSiswa').DataTable({

        language: {

            search: "Cari:",

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

        columnDefs: [
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
