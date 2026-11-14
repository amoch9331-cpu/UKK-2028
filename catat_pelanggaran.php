
<?php
// catat_pelanggaran.php

if (session_status() === PHP_SESSION_NONE) {
  
}

include 'includes/cek_session.php';
include 'config/koneksi.php';

$role = strtolower(trim($_SESSION['role'] ?? ''));
$current_page = basename($_SERVER['PHP_SELF']);

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// ===============================
// DAFTAR SISWA
// ===============================
$siswa = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama
     FROM t_siswa
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// ===============================
// DAFTAR PELANGGARAN
// ===============================
$pelanggaran = mysqli_query(
    $koneksi,
    "SELECT id, nama, poin, pelanggaran_kategori_id
     FROM t_pelanggaran
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

if (!$siswa || !$pelanggaran) {
    die('Gagal mengambil data: ' . e(mysqli_error($koneksi)));
}

// ===============================
// DATA CATATAN PELANGGARAN
// ===============================
$sql = "SELECT
            ps.*,
            ta.nama AS tahun_ajaran,
            s.nis,
            s.nama AS nama_siswa,
            k.nama AS nama_kelas,
            p.nama AS nama_pelanggaran
        FROM t_pelanggaran_siswa ps
        LEFT JOIN t_tahun_ajaran ta
            ON ps.tahun_ajaran_id = ta.id
        LEFT JOIN t_siswa s
            ON ps.siswa_id = s.id
        LEFT JOIN t_kelas k
            ON ps.kelas_id = k.id
        LEFT JOIN t_pelanggaran p
            ON ps.pelanggaran_id = p.id
        ORDER BY ps.id DESC";

$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die('Gagal mengambil data pelanggaran: ' . e(mysqli_error($koneksi)));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catat Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        .layout-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
            align-items: stretch;
        }

        .dashboard-content {
            flex: 1;
            min-width: 0;
            padding: 30px;
            overflow-x: hidden;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }

        .breadcrumb-link {
            color: #0d6efd;
            text-decoration: none;
        }

        .breadcrumb-link:hover {
            text-decoration: underline;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
        }

        .select2-container--default .select2-selection--single
        .select2-selection__rendered {
            line-height: 36px !important;
            padding-left: 12px;
        }

        .select2-container--default .select2-selection--single
        .select2-selection__arrow {
            height: 36px !important;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-ditindaklanjuti {
            background: #cfe2ff;
            color: #084298;
        }

        .status-proses {
            background: #fff3cd;
            color: #664d03;
        }

        .status-selesai {
            background: #d1e7dd;
            color: #0f5132;
        }

        @media (max-width: 768px) {
            .layout-wrapper {
                flex-direction: column;
            }

            .dashboard-content {
                width: 100%;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<div class="layout-wrapper">

    <!-- SIDEBAR UTAMA -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- KONTEN HALAMAN -->
    <main class="dashboard-content">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-1">Catat Pelanggaran Siswa</h2>
                <p class="text-muted mb-0">
                    <a href="dashboard.php" class="breadcrumb-link">Kembali ke Dashboard</a>
                    / Catat Pelanggaran
                </p>
            </div>
        </div>

        <!-- NOTIFIKASI SUKSES -->
        <?php if (isset($_SESSION['pesan_sukses'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <?= e($_SESSION['pesan_sukses']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['pesan_sukses']); ?>
        <?php endif; ?>

        <!-- NOTIFIKASI ERROR -->
        <?php if (isset($_SESSION['pesan_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?= e($_SESSION['pesan_error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['pesan_error']); ?>
        <?php endif; ?>

        <!-- FORM CATAT PELANGGARAN -->
        <div class="card mb-4">

            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-pencil-square me-2"></i>
                    Form Catat Pelanggaran
                </h5>
            </div>

            <div class="card-body">

                <form action="proses_simpan_pelanggaran.php" method="POST" id="formPelanggaran">

                    <div class="row">

                        <!-- SISWA -->
                        <div class="col-md-6 mb-3">
                            <label for="siswa_id" class="form-label">Siswa</label>

                            <select name="siswa_id" id="siswa_id" class="form-select" required>
                                <option value=""></option>

                                <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                                    <option value="<?= (int)$s['id'] ?>">
                                        <?= e($s['nis'] . ' - ' . $s['nama']) ?>
                                    </option>
                                <?php endwhile; ?>

                            </select>

                            <small class="text-muted">
                                Ketik NIS atau nama siswa untuk mencari.
                            </small>
                        </div>

                        <!-- TAHUN AJARAN -->
                        <div class="col-md-6 mb-3">
                            <label for="nama_tahun" class="form-label">Tahun Ajaran</label>

                            <input
                                type="text"
                                id="nama_tahun"
                                class="form-control"
                                placeholder="Otomatis sesuai siswa"
                                readonly
                            >

                            <input
                                type="hidden"
                                name="tahun_ajaran_id"
                                id="tahun_ajaran_id"
                            >
                        </div>

                        <!-- KELAS -->
                        <div class="col-md-6 mb-3">
                            <label for="nama_kelas" class="form-label">Kelas</label>

                            <input
                                type="text"
                                id="nama_kelas"
                                class="form-control"
                                placeholder="Otomatis sesuai siswa"
                                readonly
                            >

                            <input
                                type="hidden"
                                name="kelas_id"
                                id="kelas_id"
                            >
                        </div>

                        <!-- JENIS PELANGGARAN -->
                        <div class="col-md-6 mb-3">
                            <label for="pelanggaran" class="form-label">
                                Jenis Pelanggaran
                            </label>

                            <select
                                name="pelanggaran_id"
                                id="pelanggaran"
                                class="form-select"
                                required
                            >
                                <option value="">-- Pilih Pelanggaran --</option>

                                <?php while ($p = mysqli_fetch_assoc($pelanggaran)): ?>
                                    <option
                                        value="<?= (int)$p['id'] ?>"
                                        data-poin="<?= (int)$p['poin'] ?>"
                                        data-kategori="<?= (int)$p['pelanggaran_kategori_id'] ?>"
                                    >
                                        <?= e($p['nama'] . ' (' . $p['poin'] . ' poin)') ?>
                                    </option>
                                <?php endwhile; ?>

                            </select>

                            <input
                                type="hidden"
                                name="pelanggaran_kategori_id"
                                id="kategori"
                            >
                        </div>

                        <!-- TANGGAL -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>

                            <input
                                type="date"
                                name="tanggal"
                                id="tanggal"
                                class="form-control"
                                value="<?= date('Y-m-d') ?>"
                                required
                            >
                        </div>

                        <!-- POIN -->
                        <div class="col-md-6 mb-3">
                            <label for="poin" class="form-label">Poin Pelanggaran</label>

                            <input
                                type="number"
                                name="poin"
                                id="poin"
                                class="form-control"
                                placeholder="Poin otomatis"
                                min="0"
                                readonly
                                required
                            >
                        </div>

                        <!-- KETERANGAN -->
                        <div class="col-12 mb-3">
                            <label for="keterangan" class="form-label">
                                Keterangan
                            </label>

                            <textarea
                                name="keterangan"
                                id="keterangan"
                                class="form-control"
                                rows="3"
                                placeholder="Masukkan keterangan tambahan jika ada..."
                            ></textarea>
                        </div>

                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>
                            Simpan Pelanggaran
                        </button>

                        <button type="reset" class="btn btn-secondary" id="btnReset">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </button>
                    </div>

                </form>

            </div>
        </div>

        <!-- TABEL RIWAYAT CATATAN -->
        <div class="card">

            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    Data Catatan Pelanggaran
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="tabelPelanggaran" class="table table-bordered table-striped table-hover w-100">

                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tahun Ajaran</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Poin</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $no = 1;

                            while ($row = mysqli_fetch_assoc($hasil)):
                            ?>

                                <tr>
                                    <td><?= $no++ ?></td>

                                    <td>
                                        <?= e($row['tahun_ajaran'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= e($row['nis'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= e($row['nama_siswa'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= e($row['nama_kelas'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= e($row['nama_pelanggaran'] ?? '-') ?>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-danger">
                                            <?= e($row['poin'] ?? 0) ?> poin
                                        </span>
                                    </td>

                                    <td>
                                        <?php
                                        if (!empty($row['tanggal'])) {
                                            echo e(date('d-m-Y', strtotime($row['tanggal'])));
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                </tr>

                            <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

    </main>
</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {

    // SELECT2 SISWA
    $('#siswa_id').select2({
        placeholder: '-- Cari NIS atau Nama Siswa --',
        allowClear: true,
        width: '100%'
    });

    // SELECT2 PELANGGARAN
    $('#pelanggaran').select2({
        placeholder: '-- Pilih Pelanggaran --',
        allowClear: true,
        width: '100%'
    });

    // DATATABLES
    $('#tabelPelanggaran').DataTable({
        pageLength: 10,
        ordering: true,
        responsive: false,
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            zeroRecords: 'Data tidak ditemukan',
            emptyTable: 'Belum ada catatan pelanggaran',
            paginate: {
                first: 'Pertama',
                last: 'Terakhir',
                next: 'Berikutnya',
                previous: 'Sebelumnya'
            }
        }
    });

    // OTOMATIS MENGISI POIN DAN KATEGORI
    $('#pelanggaran').on('change', function () {

        const selected = $(this).find(':selected');

        const poin = selected.data('poin') || '';
        const kategori = selected.data('kategori') || '';

        $('#poin').val(poin);
        $('#kategori').val(kategori);
    });

    // RESET FORM
    $('#btnReset').on('click', function () {

        setTimeout(function () {
            $('#siswa_id').val('').trigger('change');
            $('#pelanggaran').val('').trigger('change');

            $('#nama_tahun').val('');
            $('#tahun_ajaran_id').val('');

            $('#nama_kelas').val('');
            $('#kelas_id').val('');

            $('#poin').val('');
            $('#kategori').val('');

            $('#tanggal').val('<?= date('Y-m-d') ?>');
        }, 10);

    });

    // VALIDASI SEBELUM SIMPAN
    $('#formPelanggaran').on('submit', function (e) {

        if (!$('#siswa_id').val()) {
            e.preventDefault();
            alert('Silakan pilih siswa terlebih dahulu.');
            return false;
        }

        if (!$('#pelanggaran').val()) {
            e.preventDefault();
            alert('Silakan pilih jenis pelanggaran.');
            return false;
        }

        if (!$('#poin').val()) {
            e.preventDefault();
            alert('Poin pelanggaran belum terisi.');
            return false;
        }

    });

});
</script>

</body>
</html>
