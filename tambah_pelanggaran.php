
<?php
// ==========================================
// KELOLA DAN TAMBAH PELANGGARAN - SIPENSIS
// ==========================================

include 'includes/cek_session.php';
include 'config/koneksi.php';

$halaman = $_SERVER['PHP_SELF'];
$aksi = $_GET['aksi'] ?? '';
$error = '';
$pesan = $_GET['pesan'] ?? '';

// ==========================================
// PROSES SIMPAN DATA
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $kategori_id = (int)($_POST['pelanggaran_kategori_id'] ?? 0);
    $poin = (int)($_POST['poin'] ?? 0);

    if ($nama == '') {
        $error = 'Nama pelanggaran wajib diisi.';
        $aksi = 'tambah';
    } elseif ($kategori_id <= 0) {
        $error = 'Kategori pelanggaran wajib dipilih.';
        $aksi = 'tambah';
    } elseif ($poin <= 0) {
        $error = 'Poin harus lebih dari 0.';
        $aksi = 'tambah';
    } else {

        $sql = "INSERT INTO t_pelanggaran
                (nama, pelanggaran_kategori_id, poin)
                VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($koneksi, $sql);

        if (!$stmt) {
            $error = mysqli_error($koneksi);
            $aksi = 'tambah';
        } else {

            mysqli_stmt_bind_param(
                $stmt,
                "sii",
                $nama,
                $kategori_id,
                $poin
            );

            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_close($stmt);

                header("Location: " . $halaman . "?pesan=tambah_berhasil");
                exit;

            } else {

                $error = 'Gagal menyimpan: ' . mysqli_stmt_error($stmt);
                mysqli_stmt_close($stmt);
                $aksi = 'tambah';

            }
        }
    }
}

// ==========================================
// AMBIL DATA KATEGORI
// ==========================================

$sql_kategori = "SELECT * FROM t_pelanggaran_kategori ORDER BY nama ASC";
$query_kategori = mysqli_query($koneksi, $sql_kategori);

if (!$query_kategori) {
    die("Query kategori gagal: " . mysqli_error($koneksi));
}

// ==========================================
// AMBIL DATA PELANGGARAN
// ==========================================

$sql = "SELECT
            p.id,
            p.nama,
            p.poin,
            p.pelanggaran_kategori_id,
            k.nama AS nama_kategori
        FROM t_pelanggaran p
        LEFT JOIN t_pelanggaran_kategori k
        ON p.pelanggaran_kategori_id = k.id
        ORDER BY p.id DESC";

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

    <title>Kelola Pelanggaran - SIPENSIS</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- ICON -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- DATATABLES -->
    <link rel="stylesheet"
    href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f5f7;
            font-family: Arial, sans-serif;
            color: #212529;
        }

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: #1a1d20;
            z-index: 1000;
            overflow-y: auto;
        }

        main {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            padding: 20px 25px;
        }

        .judul-halaman {
            font-size: 25px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .deskripsi {
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
            overflow: hidden;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 17px 20px;
        }

        .card-header h5 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
        }

        .card-body {
            padding: 22px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
        }

        .form-control {
            height: 43px;
            font-size: 13px;
            border-radius: 6px;
        }

        .btn-tambah,
        .btn-simpan {
            background: #212529;
            color: white;
            border-color: #212529;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-tambah:hover,
        .btn-simpan:hover {
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

        .btn-edit {
            background: #ffc107;
            color: white;
        }

        .btn-hapus {
            background: #dc3545;
            color: white;
        }

        #tabelPelanggaran {
            width: 100%;
            font-size: 13px;
        }

        #tabelPelanggaran thead th {
            background: #212529;
            color: white;
            padding: 13px 10px;
            white-space: nowrap;
        }

        #tabelPelanggaran tbody td {
            padding: 12px 10px;
            vertical-align: middle;
        }

        .badge {
            font-size: 11px;
            padding: 7px 9px;
        }

        @media(max-width:768px) {

            #sidebar {
                width: 0;
                overflow: hidden;
            }

            main {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }

            .card-body {
                padding: 15px;
            }

        }

    </style>

</head>

<body>

<div class="container-fluid p-0">

    <div class="row">

        <!-- SIDEBAR -->

        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN -->

        <main>

            <?php if ($aksi == 'tambah') { ?>

                <!-- =================================
                     HALAMAN TAMBAH PELANGGARAN
                ================================== -->

                <h3 class="judul-halaman">
                    <i class="bi bi-exclamation-triangle mr-2"></i>
                    Tambah Pelanggaran
                </h3>

                <p class="deskripsi">
                    Tambahkan data jenis pelanggaran siswa ke dalam sistem.
                </p>

                <?php if ($error != '') { ?>

                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </div>

                <?php } ?>

                <div class="card">

                    <div class="card-header">

                        <h5>
                            <i class="bi bi-plus-circle mr-2"></i>
                            Form Tambah Data Pelanggaran
                        </h5>

                    </div>

                    <div class="card-body">

                        <a href="<?= htmlspecialchars($halaman); ?>"
                           class="btn btn-kembali btn-sm mb-4">

                            <i class="bi bi-arrow-left"></i>
                            Kembali

                        </a>

                        <form method="POST"
                              action="<?= htmlspecialchars($halaman); ?>?aksi=tambah">

                            <div class="form-group">

                                <label>Nama Pelanggaran</label>

                                <input type="text"
                                       name="nama"
                                       class="form-control"
                                       placeholder="Masukkan nama pelanggaran"
                                       value="<?= htmlspecialchars($_POST['nama'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                       required>

                            </div>

                            <div class="form-group">

                                <label>Kategori Pelanggaran</label>

                                <select name="pelanggaran_kategori_id"
                                        class="form-control"
                                        required>

                                    <option value="">
                                        -- Pilih Kategori Pelanggaran --
                                    </option>

                                    <?php while ($kategori = mysqli_fetch_assoc($query_kategori)) { ?>

                                        <option value="<?= (int)$kategori['id']; ?>"
                                            <?= (isset($_POST['pelanggaran_kategori_id']) && $_POST['pelanggaran_kategori_id'] == $kategori['id']) ? 'selected' : ''; ?>>

                                            <?= htmlspecialchars($kategori['nama'], ENT_QUOTES, 'UTF-8'); ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <div class="form-group">

                                <label>Poin Pelanggaran</label>

                                <input type="number"
                                       name="poin"
                                       class="form-control"
                                       placeholder="Masukkan jumlah poin"
                                       min="1"
                                       value="<?= htmlspecialchars($_POST['poin'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                       required>

                            </div>

                            <hr>

                            <div class="d-flex justify-content-between">

                                <a href="<?= htmlspecialchars($halaman); ?>"
                                   class="btn btn-kembali">

                                    <i class="bi bi-arrow-left"></i>
                                    Kembali

                                </a>

                                <button type="submit" class="btn btn-simpan">

                                    <i class="bi bi-save"></i>
                                    Simpan Data

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            <?php } else { ?>

                <!-- =================================
                     HALAMAN KELOLA PELANGGARAN
                ================================== -->

                <h3 class="judul-halaman">

                    <i class="bi bi-exclamation-triangle mr-2"></i>
                    Kelola Pelanggaran

                </h3>

                <p class="deskripsi">
                    Kelola seluruh data jenis pelanggaran siswa.
                </p>

                <?php if ($pesan == 'tambah_berhasil') { ?>

                    <div class="alert alert-success alert-dismissible fade show">

                        Data pelanggaran berhasil ditambahkan.

                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>

                    </div>

                <?php } elseif ($pesan == 'hapus_berhasil') { ?>

                    <div class="alert alert-success alert-dismissible fade show">

                        Data pelanggaran berhasil dihapus.

                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>

                    </div>

                <?php } elseif ($pesan == 'hapus_gagal') { ?>

                    <div class="alert alert-danger">
                        Data pelanggaran gagal dihapus.
                    </div>

                <?php } ?>

                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">

                        <h5>
                            <i class="bi bi-list-ul mr-2"></i>
                            Data Pelanggaran
                        </h5>

                        <a href="<?= htmlspecialchars($halaman); ?>?aksi=tambah"
                           class="btn btn-tambah btn-sm">

                            <i class="bi bi-plus-lg"></i>
                            Tambah Pelanggaran

                        </a>

                    </div>

                    <div class="card-body">

                        <div class="mb-4">

                            <a href="dashboard.php"
                               class="btn btn-kembali btn-sm">

                                <i class="bi bi-arrow-left"></i>
                                Kembali ke Dashboard

                            </a>

                        </div>

                        <div class="table-responsive">

                            <table id="tabelPelanggaran"
                                   class="table table-striped table-bordered table-hover">

                                <thead>

                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Pelanggaran</th>
                                        <th>Kategori</th>
                                        <th>Poin</th>
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
                                                <strong>
                                                    <?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8'); ?>
                                                </strong>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['nama_kategori'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                                            </td>

                                            <td>
                                                <span class="badge badge-warning">
                                                    <?= (int)$row['poin']; ?> Poin
                                                </span>
                                            </td>

                                            <td style="white-space:nowrap;">

                                                <a href="edit_pelanggaran.php?id=<?= (int)$row['id']; ?>"
                                                   class="btn btn-edit btn-sm"
                                                   title="Edit">

                                                    <i class="bi bi-pencil-square"></i>

                                                </a>

                                                <a href="hapus_pelanggaran.php?id=<?= (int)$row['id']; ?>"
                                                   class="btn btn-hapus btn-sm"
                                                   title="Hapus"
                                                   onclick="return confirm('Yakin ingin menghapus pelanggaran ini?');">

                                                    <i class="bi bi-trash"></i>

                                                </a>

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

    </div>

</div>

<!-- JAVASCRIPT -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>

$(document).ready(function () {

    if ($('#tabelPelanggaran').length) {

        $('#tabelPelanggaran').DataTable({

            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                zeroRecords: "Data tidak ditemukan",
                emptyTable: "Belum ada data pelanggaran",

                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            },

            pageLength: 10,
            ordering: true,
            searching: true,
            autoWidth: false,

            columnDefs: [
                {
                    targets: [0, 4],
                    orderable: false
                }
            ]

        });

    }

});

</script>

</body>
</html>
