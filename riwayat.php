<?php
// riwayat.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$role = strtolower(trim($_SESSION['role'] ?? ''));
$current_page = basename($_SERVER['PHP_SELF']);

$cari = isset($_GET['cari'])
    ? mysqli_real_escape_string($koneksi, trim($_GET['cari']))
    : '';

$status = isset($_GET['status'])
    ? mysqli_real_escape_string($koneksi, trim($_GET['status']))
    : '';

$sql = "SELECT *
        FROM t_pelanggaran_siswa
        WHERE 1=1";

if ($cari != '') {
    $sql .= " AND (
        nama_siswa LIKE '%$cari%'
        OR nama_pelanggaran LIKE '%$cari%'
        OR nama_kelas LIKE '%$cari%'
        OR nama_guru LIKE '%$cari%'
    )";
}

if ($status != '') {
    $sql .= " AND status = '$status'";
}

$sql .= " ORDER BY tanggal DESC, id DESC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die('Gagal mengambil data: ' . mysqli_error($koneksi));
}

$jumlah = mysqli_num_rows($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background-color: #212529;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 100;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 0.6rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
        }
        .sidebar .nav-link:hover {
            background-color: #343a40;
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
        }
        .main-content {
            margin-left: 250px;
            padding: 1.5rem;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }
        .breadcrumb-link {
            color: #0d6efd;
            text-decoration: none;
        }
        .breadcrumb-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <h5 class="text-white mb-4">PELANGGARAN SISWA</h5>
            <p class="text-secondary small mb-3">Role: <?= htmlspecialchars(ucfirst($role)) ?></p>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
                        <span>📊</span> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="catat_pelanggaran.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'catat_pelanggaran.php') ? 'active' : '' ?>">
                        <span>📝</span> Catat Pelanggaran
                    </a>
                </li>
                <li class="nav-item">
                    <a href="tindakan.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'tindakan.php') ? 'active' : '' ?>">
                        <span>⚖️</span> Tindakan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="riwayat.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'riwayat.php') ? 'active' : '' ?>">
                        <span>🕒</span> Riwayat
                    </a>
                </li>
                <li class="nav-item">
                    <a href="rekap_poin.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'rekap_poin.php') ? 'active' : '' ?>">
                        <span>📈</span> Rekap Poin
                    </a>
                </li>
                <li class="nav-item">
                    <a href="laporan.php" class="nav-link d-flex align-items-center gap-2 <?= ($current_page == 'laporan.php') ? 'active' : '' ?>">
                        <span>📄</span> Laporan
                    </a>
                </li>
                <hr class="text-secondary my-3">
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger d-flex align-items-center gap-2">
                        <span>➡️</span> Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <h2 class="mb-1">Riwayat Pelanggaran Siswa</h2>
            <p class="text-muted mb-4">
                <a href="dashboard.php" class="breadcrumb-link">Kembali ke Dashboard</a> | Riwayat
            </p>

            <!-- Ringkasan jumlah data -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="text-muted">Jumlah Riwayat Ditampilkan</h6>
                    <h3 class="fw-bold text-primary"><?= $jumlah; ?> data</h3>
                </div>
            </div>

            <!-- Pesan jika tidak ada hasil -->
            <?php if ($jumlah == 0 && ($cari != '' || $status != '')): ?>
                <div class="alert alert-info mb-4">
                    Tidak ada riwayat pelanggaran yang sesuai dengan pencarian Anda. Silakan coba kriteria lain.
                </div>
            <?php endif; ?>

            <!-- Pencarian dan Filter -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Pencarian dan Filter Riwayat</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="riwayat.php">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="cari" class="form-label">Cari Riwayat</label>
                                <input type="text" name="cari" id="cari" class="form-control"
                                       placeholder="Nama siswa, pelanggaran, kelas, guru..."
                                       value="<?= htmlspecialchars($cari); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label">Filter Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="belum" <?= $status == 'belum' ? 'selected' : ''; ?>>
                                        Belum Ditindak
                                    </option>
                                    <option value="selesai" <?= $status == 'selesai' ? 'selected' : ''; ?>>
                                        Selesai
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary">Cari</button>
                                <a href="riwayat.php" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Riwayat -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Data Riwayat Pelanggaran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabelRiwayat" class="table table-striped table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jenis Pelanggaran</th>
                                    <th>Poin</th>
                                    <th>Guru Pencatat</th>
                                    <th>Keterangan</th>
                                    <th>Tindakan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <?php
                                            if (!empty($data['tanggal'])) {
                                                echo date('d/m/Y', strtotime($data['tanggal']));
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($data['nama_siswa'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($data['nama_kelas'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($data['nama_pelanggaran'] ?? '-'); ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark">
                                                <?= htmlspecialchars((string) ($data['poin'] ?? 0)); ?> Poin
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($data['nama_guru'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($data['keterangan'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($data['tindakan'] ?? '-'); ?></td>
                                        <td class="text-center">
                                            <?php
                                            $status_data = strtolower(trim($data['status'] ?? ''));
                                            if ($status_data == 'selesai') {
                                                echo '<span class="badge bg-success">Selesai</span>';
                                            } elseif ($status_data == 'belum' || $status_data == 'belum ditindak') {
                                                echo '<span class="badge bg-danger">Belum Ditindak</span>';
                                            } else {
                                                echo '<span class="badge bg-secondary">'
                                                    . htmlspecialchars($data['status'] ?? '-')
                                                    . '</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#tabelRiwayat').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                searching: false,
                ordering: true,
                paging: true,
                order: [[1, 'desc']],
                columnDefs: [
                    { orderable: false, searchable: false, targets: 0 }
                ],
                drawCallback: function () {
                    var api = this.api();
                    var nomorAwal = api.page.info().start;
                    api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                        cell.innerHTML = nomorAwal + i + 1;
                    });
                },
                language: {
                    search: "Cari di tabel:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data riwayat",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    zeroRecords: "Riwayat pelanggaran tidak ditemukan",
                    emptyTable: "Belum ada riwayat pelanggaran",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });
    </script>
</body>
</html>
