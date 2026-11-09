<?php
// kelola_kelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_kelas ORDER BY nama ASC";
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

    <title>Kelola Kelas</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

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
            color: #212529;
        }

        #tabelKelas th,
        #tabelKelas td {
            vertical-align: middle;
        }

        .dataTables_wrapper {
            padding-top: 10px;
        }

        .btn-aksi {
            margin: 2px;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR (Lebar proporsional col-md-3 / col-lg-2 seperti standar admin template) -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h3 class="judul-halaman">Kelola Kelas</h3>

            <div class="card">

                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 font-weight-bold">Data Kelas</h5>
                    <a href="tambah_kelas.php" class="btn btn-primary btn-sm font-weight-bold">
                        + Tambah Kelas
                    </a>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <a href="dashboard.php" class="btn btn-secondary btn-sm">
                            Kembali ke Dashboard
                        </a>
                    </div>

                    <div class="table-responsive">

                        <table id="tabelKelas" class="table table-striped table-bordered table-hover" style="width:100%">

                            <thead class="thead-dark">
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
                                        <?= htmlspecialchars((string) $row['id'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string) $row['nama'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string) $row['tingkat'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string) $row['jurusan'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?php if ((int) $row['status_aktif'] === 1) { ?>
                                            <span class="badge badge-success px-2 py-1">Aktif</span>
                                        <?php } else { ?>
                                            <span class="badge badge-danger px-2 py-1">Tidak Aktif</span>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string) ($row['created_at'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string) ($row['updated_at'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <a href="edit_kelas.php?id=<?= urlencode((string) $row['id']); ?>" class="btn btn-warning btn-sm btn-aksi text-dark font-weight-bold">
                                            Edit
                                        </a>

                                        <a href="hapus_kelas.php?id=<?= urlencode((string) $row['id']); ?>" class="btn btn-danger btn-sm btn-aksi font-weight-bold" onclick="return confirm('Yakin ingin menghapus data kelas ini?');">
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