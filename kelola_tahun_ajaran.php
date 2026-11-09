
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
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        /* Pengaturan halaman */
        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            background-color: #f8f9fa;
        }

        /* Layout utama */
        .container-fluid {
            padding: 0;
            min-height: 100vh;
        }

        .container-fluid > .row {
            margin: 0;
            min-height: 100vh;
            align-items: stretch;
        }

        /* SIDEBAR HITAM */
        .container-fluid > .row > :first-child {
            background-color: #343a40;
            min-height: 100vh;
            height: auto;
            padding-bottom: 20px;
        }

        /* Konten utama */
        main {
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        /* Card */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        /* Judul halaman */
        .judul-halaman {
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* Tabel */
        #tabelTahunAjaran th,
        #tabelTahunAjaran td {
            vertical-align: middle;
        }

        .dataTables_wrapper {
            padding-top: 10px;
        }

        /* Tombol aksi */
        .btn-aksi {
            margin: 2px;
        }

        /* Tampilan HP */
        @media (max-width: 767px) {
            .container-fluid > .row > :first-child {
                min-height: auto;
                width: 100%;
            }

            main {
                padding: 15px !important;
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

            <h3 class="judul-halaman">Kelola Tahun Ajaran</h3>

            <div class="card">

                <!-- HEADER CARD -->
                <div class="card-header bg-white d-flex
                            justify-content-between align-items-center">

                    <h5 class="mb-0">Data Tahun Ajaran</h5>

                    <a href="tambah_tahun_ajaran.php"
                       class="btn btn-primary btn-sm">
                        + Tambah Tahun Ajaran
                    </a>

                </div>

                <!-- BODY CARD -->
                <div class="card-body">

                    <div class="mb-3">
                        <a href="dashboard.php"
                           class="btn btn-secondary btn-sm">
                            Kembali ke Dashboard
                        </a>
                    </div>

                    <!-- TABEL -->
                    <div class="table-responsive">

                        <table id="tabelTahunAjaran"
                               class="table table-striped table-bordered table-hover"
                               style="width:100%">

                            <thead class="thead-dark">
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
                                        <?php
                                        if ((int) $row['status_aktif'] === 1) {
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

                                    <td>

                                        <a
                                            href="edit_tahun_ajaran.php?id=<?= urlencode((string) $row['id']); ?>"
                                            class="btn btn-warning btn-sm btn-aksi">
                                            Edit
                                        </a>

                                        <a
                                            href="hapus_tahun_ajaran.php?id=<?= urlencode((string) $row['id']); ?>"
                                            class="btn btn-danger btn-sm btn-aksi"
                                            onclick="return confirm('Yakin ingin menghapus data tahun ajaran ini?');">
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JavaScript -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<!-- Inisialisasi DataTables -->
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
```