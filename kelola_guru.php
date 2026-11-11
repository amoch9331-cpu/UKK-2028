<?php
// kelola_guru.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Mengambil data guru
$sql = "SELECT * FROM t_guru ORDER BY nama ASC";
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

    <title>Kelola Guru - SIPENSI</title>

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
            font-size: 13.5px; /* Sedikit diperkecil agar keseluruhan lebih compact */
        }

        /* KONTEN UTAMA */
        main {
            min-height: 100vh;
            padding: 20px !important;
        }

        .judul-halaman {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 3px;
            color: #212529;
        }

        .subjudul {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 15px;
        }

        /* CARD */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header {
            padding: 12px 18px;
            border-bottom: 1px solid #e9ecef;
        }

        .card-header h5 {
            font-size: 16px;
            font-weight: 600;
        }

        .card-body {
            padding: 15px;
        }

        /* BUTTON */
        .btn-primary {
            background: #212529 !important;
            border-color: #212529 !important;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background: #343a40 !important;
        }

        .btn-secondary {
            border-radius: 5px;
        }

        .btn-aksi {
            padding: 3px 7px;
            border-radius: 4px;
            margin: 1px;
            font-size: 12px;
        }

        /* TABLE */
        .table-responsive {
            width: 100%;
        }

        #tabelGuru {
            width: 100% !important;
            border-collapse: collapse;
            font-size: 13px; /* Font tabel dikecilkan agar muat banyak baris */
        }

        #tabelGuru thead th {
            background: #212529;
            color: white;
            padding: 10px 8px; /* Padding header dipadatkan */
            border-color: #343a40;
            white-space: nowrap;
            vertical-align: middle;
        }

        #tabelGuru tbody td {
            padding: 7px 8px; /* Padding baris data diperkecil agar muat sampai no 10+ */
            vertical-align: middle;
            border-color: #dee2e6;
        }

        #tabelGuru tbody tr:hover {
            background: #eef2f7;
        }

        /* BADGE */
        .badge-success {
            background: #28a745;
            padding: 4px 6px;
            font-size: 11px;
        }

        .badge-danger {
            background: #dc3545;
            padding: 4px 6px;
            font-size: 11px;
        }

        /* DATATABLES */
        .dataTables_wrapper {
            padding-top: 5px;
            font-size: 13px;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 4px 8px;
            outline: none;
            font-size: 13px;
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
            padding: 5px 10px;
            font-size: 13px;
        }

        .page-link:hover {
            color: #000;
        }

        /* NOTIFIKASI */
        .alert {
            border-radius: 6px;
            font-size: 13px;
            padding: 10px 15px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            main {
                padding: 10px !important;
            }

            .judul-halaman {
                font-size: 20px;
            }

            .card-body {
                padding: 10px;
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
            <div class="mb-3">
                <h3 class="judul-halaman">
                    Kelola Guru
                </h3>
                <p class="subjudul">
                    Kelola dan pantau seluruh data guru yang terdaftar dalam sistem.
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
                        Data guru berhasil dihapus!
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>';
                } elseif ($pesan == 'hapus_gagal') {
                    echo '
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-circle-fill mr-2"></i>
                        Data guru gagal dihapus! Periksa relasi database.
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>';
                } elseif ($pesan == 'data_tidak_ditemukan') {
                    echo '
                    <div class="alert alert-warning alert-dismissible fade show">
                        Data guru tidak ditemukan!
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>';
                }
            }
            ?>

            <!-- CARD DATA GURU -->
            <div class="card">

                <!-- HEADER CARD -->
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge-fill mr-2"></i>
                        Data Guru
                    </h5>
                    <a href="tambah_guru.php" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg mr-1"></i>
                        Tambah Guru
                    </a>
                </div>

                <!-- BODY CARD -->
                <div class="card-body">

                    <div class="mb-2">
                        <a href="dashboard.php" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left mr-1"></i>
                            Kembali ke Dashboard
                        </a>
                    </div>

                    <!-- TABEL GURU (Ditambahkan class table-sm agar lebih padat) -->
                    <div class="table-responsive">
                        <table id="tabelGuru" class="table table-striped table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>ID</th>
                                    <th>NIP</th>
                                    <th>Nama Guru</th>
                                    <th>Email</th>
                                    <th>Status Aktif</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($hasil)) {
                                    $id = (int) $row['id'];
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars((string) $row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars((string) ($row['nip'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars((string) ($row['nama'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars((string) ($row['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <?php
                                        if ((int) $row['status_aktif'] === 1) {
                                        ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php } else { ?>
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        <?php } ?>
                                    </td>
                                    <!-- AKSI -->
                                    <td style="white-space: nowrap;">
                                        <!-- EDIT -->
                                        <a href="edit_guru.php?id=<?= $id; ?>" class="btn btn-warning btn-sm btn-aksi">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <!-- HAPUS -->
                                        <form action="hapus_guru.php" method="POST" style="display:inline;" onsubmit="return confirm('Apakah kamu yakin ingin menghapus data guru ini?');">
                                            <input type="hidden" name="id" value="<?= $id; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm btn-aksi">
                                                <i class="bi bi-trash"></i> Hapus
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
    $('#tabelGuru').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data guru",
            infoEmpty: "Tidak ada data guru",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data guru tidak ditemukan",
            emptyTable: "Belum ada data guru",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        },
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        ordering: true,
        searching: true,
        paging: true,
        autoWidth: false,
        columnDefs: [
            {
                targets: [0, 6],
                orderable: false
            }
        ],
        order: [[3, 'asc']]
    });
});
</script>

</body>
</html>