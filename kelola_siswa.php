<?php
// kelola_siswa.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

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

    <title>Kelola Siswa</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        main {
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .judul-halaman {
            font-weight: 600;
            margin-bottom: 20px;
        }

        #tabelSiswa th,
        #tabelSiswa td {
            vertical-align: middle;
        }

        .dataTables_wrapper {
            padding-top: 10px;
        }

        .btn-aksi {
            margin: 2px;
        }

        /* Mengubah warna tombol primary menjadi hitam pekat */
        .btn-primary {
            background-color: #000000 !important;
            border-color: #000000 !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #1a1a1a !important;
            border-color: #1a1a1a !important;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h3 class="judul-halaman">Kelola Siswa</h3>

            <div class="card">

                <div class="card-header bg-white d-flex
                            justify-content-between align-items-center">

                    <h5 class="mb-0">Data Siswa</h5>

                    <a href="tambah_siswa.php"
                       class="btn btn-primary btn-sm">
                        + Tambah Siswa
                    </a>

                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <a href="dashboard.php"
                           class="btn btn-secondary btn-sm">
                            Kembali ke Dashboard
                        </a>
                    </div>

                    <div class="table-responsive">

                        <table id="tabelSiswa"
                               class="table table-striped table-bordered table-hover"
                               style="width:100%">

                            <thead class="thead-dark">
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
                                ?>

                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $nis,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
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
                                        <?php
                                        } else {
                                        ?>
                                            <span class="badge badge-danger">
                                                Tidak Aktif
                                            </span>
                                        <?php } ?>
                                    </td>

                                    <td>
                                       <a href="edit_siswa.php?nis=<?= urlencode($nis); ?>"
                                           class="btn btn-warning btn-sm btn-aksi">
                                           Edit
                                        </a>

                                        <a
                                            href="hapus_siswa.php?nis=<?= urlencode($nis); ?>"
                                            class="btn btn-danger btn-sm btn-aksi"
                                            onclick="return confirm('Yakin ingin menghapus data siswa ini?');">
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

<!-- jQuery: wajib dimuat sebelum DataTables -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JavaScript -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<!-- Inisialisasi DataTables -->
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