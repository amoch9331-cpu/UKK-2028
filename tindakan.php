<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$role = strtolower(trim($_SESSION['role'] ?? ''));
$current_page = basename($_SERVER['PHP_SELF']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $tindakan = mysqli_real_escape_string($koneksi, trim($_POST['tindakan'] ?? ''));

    $sql_update = "UPDATE t_pelanggaran_siswa SET tindakan = '$tindakan' WHERE id = $id";
    if (mysqli_query($koneksi, $sql_update)) {
        header('Location: tindakan.php?pesan=berhasil');
        exit;
    } else {
        $pesan_error = mysqli_error($koneksi);
    }
}

$sql = "SELECT id, nama_siswa, nama_kelas, nama_pelanggaran, poin, keterangan, tindakan 
        FROM t_pelanggaran_siswa 
        ORDER BY id DESC";
$hasil = mysqli_query($koneksi, $sql);
if (!$hasil) {
    die('Gagal mengambil data: ' . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tindakan Pelanggaran Siswa</title>
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
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
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
            <h2 class="mb-1">Tindakan Pelanggaran Siswa</h2>
            <p class="text-muted mb-4">
                <a href="dashboard.php" class="breadcrumb-link">Kembali ke Dashboard</a> | Tindakan
            </p>

            <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'berhasil'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Tindakan berhasil disimpan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($pesan_error)): ?>
                <div class="alert alert-danger" role="alert">
                    Gagal menyimpan tindakan: <?= htmlspecialchars($pesan_error) ?>
                </div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">📋 Data Pelanggaran Siswa</h5>
                    <div class="table-responsive">
                        <table id="tabelTindakan" class="table table-striped table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Pelanggaran</th>
                                    <th>Poin</th>
                                    <th>Keterangan</th>
                                    <th>Tindakan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($hasil)): ?>
                                <tr>
                                    <td></td>
                                    <td><?= htmlspecialchars($row['nama_siswa'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['nama_kelas'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['nama_pelanggaran'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">
                                            <?= (int)($row['poin'] ?? 0) ?> Poin
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
                                    <td>
                                        <form method="POST" action="tindakan.php" class="d-flex gap-1 align-items-center">
                                            <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                            <textarea name="tindakan" class="form-control form-control-sm" 
                                                      rows="2" placeholder="Masukkan tindakan yang diberikan..." required><?= 
                                                htmlspecialchars($row['tindakan'] ?? '') 
                                            ?></textarea>
                                            <button type="submit" class="btn btn-success btn-sm text-nowrap">Simpan</button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <?php if (!empty($row['tindakan'])): ?>
                                            <span class="badge bg-success">Ditindaklanjuti</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Belum</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
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
            $('#tabelTindakan').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [[1, 'asc']],
                columnDefs: [
                    { orderable: false, searchable: false, targets: [0, 6, 7] }
                ],
                drawCallback: function () {
                    var api = this.api();
                    var nomorAwal = api.page.info().start;
                    api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                        cell.innerHTML = nomorAwal + i + 1;
                    });
                }
            });
        });
    </script>
</body>
</html>
