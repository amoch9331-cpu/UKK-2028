
<?php
// ==========================================
// PENEMPATAN SISWA - SIPENSIS
// ==========================================

include 'includes/cek_session.php';
include 'config/koneksi.php';

$pesan = $_GET['pesan'] ?? '';
$error = '';

$mode_tambah = isset($_GET['tambah']) && $_GET['tambah'] == '1';

// ==========================================
// PROSES SIMPAN PENEMPATAN
// ==========================================

if (isset($_POST['simpan'])) {

    $siswa_id = (int)($_POST['siswa_id'] ?? 0);
    $tahun_ajaran_id = (int)($_POST['tahun_ajaran_id'] ?? 0);
    $kelas_id = (int)($_POST['kelas_id'] ?? 0);

    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    $status_aktif = $_POST['status_aktif'] ?? 'Aktif';

    if (
        $siswa_id <= 0 ||
        $tahun_ajaran_id <= 0 ||
        $kelas_id <= 0 ||
        $tanggal_mulai == ''
    ) {
        $error = "Semua data wajib diisi kecuali tanggal selesai.";

    } elseif (
        $tanggal_selesai != '' &&
        $tanggal_selesai < $tanggal_mulai
    ) {
        $error = "Tanggal selesai tidak boleh sebelum tanggal mulai.";

    } else {

        // CEK SISWA
        $cek_siswa = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_siswa WHERE id = ?"
        );

        mysqli_stmt_bind_param($cek_siswa, "i", $siswa_id);
        mysqli_stmt_execute($cek_siswa);
        mysqli_stmt_store_result($cek_siswa);

        if (mysqli_stmt_num_rows($cek_siswa) == 0) {

            $error = "Siswa tidak ditemukan. Silakan pilih siswa kembali.";

        } else {

            // CEK PENEMPATAN GANDA
            $cek = mysqli_prepare(
                $koneksi,
                "SELECT id
                 FROM t_kelas_siswa
                 WHERE siswa_id = ?
                 AND tahun_ajaran_id = ?"
            );

            mysqli_stmt_bind_param(
                $cek,
                "ii",
                $siswa_id,
                $tahun_ajaran_id
            );

            mysqli_stmt_execute($cek);
            mysqli_stmt_store_result($cek);

            if (mysqli_stmt_num_rows($cek) > 0) {

                $error = "Siswa sudah memiliki penempatan pada tahun ajaran tersebut.";

            } else {

                // INSERT PENEMPATAN
                $sql_insert = "
                    INSERT INTO t_kelas_siswa
                    (
                        siswa_id,
                        tahun_ajaran_id,
                        kelas_id,
                        tanggal_mulai,
                        tanggal_selesai,
                        status_aktif
                    )
                    VALUES (?, ?, ?, ?, ?, ?)
                ";

                $stmt = mysqli_prepare($koneksi, $sql_insert);

                mysqli_stmt_bind_param(
                    $stmt,
                    "iiisss",
                    $siswa_id,
                    $tahun_ajaran_id,
                    $kelas_id,
                    $tanggal_mulai,
                    $tanggal_selesai,
                    $status_aktif
                );

                if (mysqli_stmt_execute($stmt)) {

                    header("Location: penempatan_siswa.php?pesan=tambah_berhasil");
                    exit;

                } else {

                    $error = "Gagal menyimpan penempatan: " .
                             mysqli_error($koneksi);
                }
            }
        }
    }
}

// ==========================================
// AMBIL DATA SISWA
// ==========================================

$query_siswa = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama
     FROM t_siswa
     ORDER BY nama ASC"
);

if (!$query_siswa) {
    die("Gagal mengambil data siswa: " . mysqli_error($koneksi));
}

// ==========================================
// AMBIL DATA TAHUN AJARAN
// ==========================================

$query_tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     ORDER BY tanggal_mulai DESC"
);

if (!$query_tahun) {
    die("Gagal mengambil tahun ajaran: " . mysqli_error($koneksi));
}

// ==========================================
// AMBIL DATA KELAS
// ==========================================

$query_kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_kelas
     ORDER BY nama ASC"
);

if (!$query_kelas) {
    die("Gagal mengambil data kelas: " . mysqli_error($koneksi));
}

// ==========================================
// AMBIL DATA PENEMPATAN
// ==========================================

$sql_data = "
    SELECT
        ks.id,
        s.nis,
        s.nama AS nama_siswa,
        ta.nama AS tahun_ajaran,
        k.nama AS nama_kelas,
        ks.tanggal_mulai,
        ks.tanggal_selesai,
        ks.status_aktif
    FROM t_kelas_siswa ks
    LEFT JOIN t_siswa s
        ON ks.siswa_id = s.id
    LEFT JOIN t_tahun_ajaran ta
        ON ks.tahun_ajaran_id = ta.id
    LEFT JOIN t_kelas k
        ON ks.kelas_id = k.id
    ORDER BY ks.id DESC
";

$hasil = mysqli_query($koneksi, $sql_data);

if (!$hasil) {
    die("Query penempatan gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Penempatan Siswa - SIPENSIS</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- DATATABLES -->
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            width: 100%;
        }

        body {
            background: #f4f5f7;
            font-family: Arial, sans-serif;
            color: #212529;
            overflow-x: hidden;
        }

        /* =====================================
           SIDEBAR
        ===================================== */

        #sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;

            width: 208px !important;
            min-width: 208px !important;
            max-width: 208px !important;

            height: 100vh !important;
            min-height: 100vh !important;

            background: #1b1f22 !important;
            color: #ffffff !important;

            z-index: 1000 !important;

            overflow-y: auto !important;
            overflow-x: hidden !important;

            padding: 0 !important;
        }

        /* AREA BRAND SIDEBAR */
        #sidebar .sidebar-header,
        #sidebar .brand,
        #sidebar .sidebar-brand {
            padding: 22px 15px 15px !important;
            color: #ffffff !important;
        }

        #sidebar h4,
        #sidebar h5,
        #sidebar .sidebar-title,
        #sidebar .brand {
            color: #ffffff !important;
            font-size: 13px !important;
            font-weight: 700 !important;
        }

        #sidebar small,
        #sidebar p {
            font-size: 10px;
            color: #adb5bd;
        }

        #sidebar hr {
            border-top: 1px solid #454d55;
            margin: 15px 10px;
        }

        /* MENU SIDEBAR */
        #sidebar a {
            display: flex !important;
            align-items: center !important;

            width: calc(100% - 20px) !important;
            min-height: 34px;

            margin: 3px 10px !important;
            padding: 8px 10px !important;

            color: #e9ecef !important;
            font-size: 10px !important;
            font-weight: 500 !important;

            text-decoration: none !important;
            border-radius: 7px !important;

            transition: background .2s ease;
        }

        #sidebar a i {
            width: 17px;
            min-width: 17px;
            margin-right: 7px;
            font-size: 12px;
            text-align: center;
        }

        #sidebar a:hover {
            background: #343a40 !important;
            color: #ffffff !important;
        }

        /* MENU AKTIF */
        #sidebar a.active,
        #sidebar a.menu-active,
        #sidebar .active > a {
            background: #0d6efd !important;
            color: #ffffff !important;
        }

        /* LOGOUT */
        #sidebar a[href*="logout"] {
            color: #ff4d5a !important;
            font-weight: 600 !important;
        }

        /* =====================================
           KONTEN UTAMA
        ===================================== */

        .content-area {
            position: relative !important;

            display: block !important;

            margin-left: 208px !important;
            width: calc(100% - 208px) !important;

            min-height: 100vh;
            padding: 15px 18px !important;

            background: #f4f5f7;
        }

        .judul-halaman {
            font-size: 21px;
            font-weight: 700;
            color: #212529;
            margin: 0 0 5px;
        }

        .deskripsi {
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 17px;
        }

        /* =====================================
           CARD
        ===================================== */

        .card {
            width: 100%;
            border: none;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
            overflow: hidden;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            background: #ffffff;
            border-bottom: 1px solid #dee2e6;

            padding: 13px 15px;
            min-height: 47px;
        }

        .card-header h5 {
            font-size: 13px;
            font-weight: 700;
            color: #212529;
            margin: 0;
        }

        .card-body {
            padding: 15px;
        }

        /* =====================================
           FORM
        ===================================== */

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-control {
            height: 35px;
            font-size: 11px;
            border-radius: 5px;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 2px rgba(0,123,255,.12);
        }

        /* =====================================
           TOMBOL
        ===================================== */

        .btn {
            font-size: 11px;
            border-radius: 3px;
            padding: 6px 10px;
        }

        .btn-tambah,
        .btn-simpan {
            background: #212529;
            border: 1px solid #212529;
            color: #ffffff;
        }

        .btn-tambah:hover,
        .btn-simpan:hover {
            background: #000000;
            color: #ffffff;
        }

        .btn-kembali {
            background: #6c757d;
            border: 1px solid #6c757d;
            color: #ffffff;
        }

        .btn-kembali:hover {
            background: #545b62;
            color: #ffffff;
        }

        .btn-edit {
            background: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .btn-hapus {
            background: #dc3545;
            border-color: #dc3545;
            color: #ffffff;
        }

        .btn-aksi {
            padding: 4px 7px;
            font-size: 10px;
        }

        /* =====================================
           TABEL
        ===================================== */

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        #tabelPenempatan {
            width: 100% !important;
            border-collapse: collapse;
            font-size: 11px;
        }

        #tabelPenempatan thead th {
            background: #212529;
            color: #ffffff;
            font-weight: 700;
            padding: 11px 9px;
            white-space: nowrap;
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }

        #tabelPenempatan tbody td {
            padding: 10px 9px;
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }

        #tabelPenempatan tbody tr:nth-child(odd) {
            background: #f1f1f1;
        }

        #tabelPenempatan tbody tr:nth-child(even) {
            background: #ffffff;
        }

        #tabelPenempatan tbody tr:hover {
            background: #e9ecef;
        }

        .badge-status {
            display: inline-block;
            padding: 5px 7px;
            font-size: 10px;
            border-radius: 4px;
        }

        /* =====================================
           DATATABLES
        ===================================== */

        .dataTables_wrapper {
            width: 100%;
            font-size: 11px;
        }

        .dataTables_filter input {
            height: 30px;
            font-size: 11px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 5px 8px;
        }

        .dataTables_length select {
            height: 30px;
            font-size: 11px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        .dataTables_info {
            font-size: 11px;
            padding-top: 10px !important;
        }

        .dataTables_paginate {
            font-size: 11px;
        }

        .page-link {
            font-size: 11px;
            color: #212529;
        }

        .page-item.active .page-link {
            background: #212529;
            border-color: #212529;
            color: #ffffff;
        }

        .alert {
            font-size: 11px;
        }

        /* =====================================
           RESPONSIVE
        ===================================== */

        @media (max-width: 768px) {

            #sidebar {
                width: 0 !important;
                min-width: 0 !important;
                max-width: 0 !important;
                overflow: hidden !important;
            }

            .content-area {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 15px !important;
            }

            .judul-halaman {
                font-size: 19px;
            }

            .card-header {
                padding: 12px;
                gap: 8px;
                flex-wrap: wrap;
            }

            .card-body {
                padding: 12px;
            }

            .dataTables_wrapper .dataTables_filter {
                text-align: left;
            }

            .dataTables_wrapper .dataTables_filter input {
                max-width: 100%;
            }

            .dataTables_wrapper .dataTables_paginate {
                overflow-x: auto;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>

<!-- =====================================
     SIDEBAR
===================================== -->

<?php include 'layout/sidebar.php'; ?>

<!-- =====================================
     KONTEN UTAMA
===================================== -->

<main class="content-area">

    <h3 class="judul-halaman">
        <i class="bi bi-people-fill mr-1"></i>
        Penempatan Siswa
    </h3>

    <p class="deskripsi">
        Kelola data penempatan siswa berdasarkan tahun ajaran dan kelas.
    </p>

    <!-- NOTIFIKASI -->
    <?php if ($pesan == 'tambah_berhasil') { ?>

        <div class="alert alert-success alert-dismissible fade show">
            Penempatan siswa berhasil ditambahkan.

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>

    <?php } ?>

    <!-- MODE TAMBAH -->
    <?php if ($mode_tambah) { ?>

        <div class="card">

            <div class="card-header">
                <h5>
                    <i class="bi bi-plus-circle mr-2"></i>
                    Form Tambah Penempatan Siswa
                </h5>
            </div>

            <div class="card-body">

                <a href="penempatan_siswa.php"
                   class="btn btn-kembali mb-3">

                    <i class="bi bi-arrow-left"></i>
                    Kembali

                </a>

                <?php if ($error != '') { ?>

                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </div>

                <?php } ?>

                <form method="POST"
                      action="penempatan_siswa.php?tambah=1">

                    <!-- NAMA SISWA -->
                    <div class="form-group">

                        <label>Nama Siswa</label>

                        <select name="siswa_id"
                                class="form-control"
                                required>

                            <option value="">
                                -- Pilih Siswa --
                            </option>

                            <?php while ($siswa = mysqli_fetch_assoc($query_siswa)) { ?>

                                <option value="<?= (int)$siswa['id']; ?>"
                                    <?= (
                                        isset($_POST['siswa_id']) &&
                                        (int)$_POST['siswa_id'] === (int)$siswa['id']
                                    ) ? 'selected' : ''; ?>>

                                    <?= htmlspecialchars(
                                        $siswa['nis'] . ' - ' . $siswa['nama'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </option>

                            <?php } ?>

                        </select>

                        <small class="text-muted">
                            Pilihan siswa diambil langsung dari tabel t_siswa.
                        </small>

                    </div>

                    <!-- TAHUN AJARAN -->
                    <div class="form-group">

                        <label>Tahun Ajaran</label>

                        <select name="tahun_ajaran_id"
                                class="form-control"
                                required>

                            <option value="">
                                -- Pilih Tahun Ajaran --
                            </option>

                            <?php while ($tahun = mysqli_fetch_assoc($query_tahun)) { ?>

                                <option value="<?= (int)$tahun['id']; ?>"
                                    <?= (
                                        isset($_POST['tahun_ajaran_id']) &&
                                        (int)$_POST['tahun_ajaran_id'] === (int)$tahun['id']
                                    ) ? 'selected' : ''; ?>>

                                    <?= htmlspecialchars(
                                        $tahun['nama'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <!-- KELAS -->
                    <div class="form-group">

                        <label>Kelas</label>

                        <select name="kelas_id"
                                class="form-control"
                                required>

                            <option value="">
                                -- Pilih Kelas --
                            </option>

                            <?php while ($kelas = mysqli_fetch_assoc($query_kelas)) { ?>

                                <option value="<?= (int)$kelas['id']; ?>"
                                    <?= (
                                        isset($_POST['kelas_id']) &&
                                        (int)$_POST['kelas_id'] === (int)$kelas['id']
                                    ) ? 'selected' : ''; ?>>

                                    <?= htmlspecialchars(
                                        $kelas['nama'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <!-- TANGGAL MULAI -->
                    <div class="form-group">

                        <label>Tanggal Mulai</label>

                        <input type="date"
                               name="tanggal_mulai"
                               class="form-control"
                               value="<?= htmlspecialchars($_POST['tanggal_mulai'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                               required>

                    </div>

                    <!-- TANGGAL SELESAI -->
                    <div class="form-group">

                        <label>Tanggal Selesai</label>

                        <input type="date"
                               name="tanggal_selesai"
                               class="form-control"
                               value="<?= htmlspecialchars($_POST['tanggal_selesai'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                    </div>

                    <!-- STATUS -->
                    <div class="form-group">

                        <label>Status Aktif</label>

                        <select name="status_aktif"
                                class="form-control"
                                required>

                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>

                        </select>

                    </div>

                    <hr>

                    <button type="submit"
                            name="simpan"
                            class="btn btn-simpan">

                        <i class="bi bi-save mr-1"></i>
                        Simpan Penempatan

                    </button>

                    <a href="penempatan_siswa.php"
                       class="btn btn-kembali">

                        Batal

                    </a>

                </form>

            </div>
        </div>

    <?php } else { ?>

        <!-- MODE DAFTAR -->
        <div class="card">

            <div class="card-header">

                <h5>
                    <i class="bi bi-list-ul mr-2"></i>
                    Data Penempatan Siswa
                </h5>

                <a href="penempatan_siswa.php?tambah=1"
                   class="btn btn-tambah">

                    <i class="bi bi-plus-lg mr-1"></i>
                    Tambah Penempatan Siswa

                </a>

            </div>

            <div class="card-body">

                <a href="dashboard.php"
                   class="btn btn-kembali mb-3">

                    <i class="bi bi-arrow-left mr-1"></i>
                    Kembali ke Dashboard

                </a>

                <div class="table-responsive">

                    <table id="tabelPenempatan"
                           class="table table-bordered table-striped table-hover">

                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Tahun Ajaran</th>
                                <th>Kelas</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
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
                                        $row['nis'] ?? '-',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $row['nama_siswa'] ?? '-',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $row['tahun_ajaran'] ?? '-',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $row['nama_kelas'] ?? '-',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $row['tanggal_mulai'] ?? '-',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $row['tanggal_selesai'] ?? '-',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </td>

                                <td>
                                    <span class="badge badge-success badge-status">
                                        <?= htmlspecialchars(
                                            $row['status_aktif'] ?? '-',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </span>
                                </td>

                            </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>
            </div>
        </div>

    <?php } ?>

</main>

<!-- =====================================
     JAVASCRIPT
===================================== -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {

    if ($('#tabelPenempatan').length) {

        $('#tabelPenempatan').DataTable({

            language: {
                search: "Cari:",
                searchPlaceholder: "Masukkan nama siswa atau kelas...",

                lengthMenu: "Tampilkan _MENU_ data",

                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data penempatan",

                infoEmpty: "Tidak ada data penempatan",

                infoFiltered: "(difilter dari _MAX_ total data)",

                zeroRecords: "Data penempatan tidak ditemukan",

                emptyTable: "Belum ada data penempatan",

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

            autoWidth: false

        });

    }

});
</script>

</body>
</html>
