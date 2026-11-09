
<?php
include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Tahun Ajaran</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
          rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: #f1f5f9;
            font-family: Arial, sans-serif;
            color: #333;
            min-height: 100vh;
        }

        /* NAVBAR */
        .navbar {
            background: #343a40;
            padding: 15px 0;
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
            font-size: 20px;
        }

        /* CONTAINER */
        .container-form {
            max-width: 1000px;
            margin: 45px auto;
        }

        /* CARD */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            background: white;
        }

        /* HEADER */
        .card-header {
            background: #f1f1f1;
            color: #333;
            padding: 25px 30px;
            border-bottom: 1px solid #ddd;
        }

        .card-header h4 {
            margin: 0;
            font-weight: bold;
        }

        .card-header p {
            color: #666;
            margin-top: 8px;
            margin-bottom: 0;
        }

        /* BODY */
        .card-body {
            padding: 35px;
        }

        /* LABEL */
        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 10px;
        }

        /* INPUT */
        .form-control,
        .form-select {
            padding: 13px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
            background: white;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6c757d;
            box-shadow: 0 0 0 0.2rem rgba(108,117,125,0.15);
        }

        /* BUTTON */
        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-primary {
            background: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-primary:hover {
            background: #565e64;
            border-color: #565e64;
        }

        .btn-secondary {
            background: #343a40;
            border-color: #343a40;
            color: white;
        }

        .btn-secondary:hover {
            background: #23272b;
            border-color: #23272b;
        }

        .btn-outline-light {
            border-color: white;
            color: white;
        }

        .btn-outline-light:hover {
            background: white;
            color: #343a40;
        }

        /* FOOTER */
        footer {
            text-align: center;
            color: #64748b;
            padding: 20px;
            margin-top: 40px;
            font-size: 14px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .container-form {
                margin: 25px 15px;
            }

            .card-body {
                padding: 20px;
            }

            .card-header {
                padding: 20px;
            }

            .navbar-brand {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">

    <div class="container">

        <a class="navbar-brand" href="dashboard.php">
            <i class="bi bi-mortarboard-fill me-2"></i>
            Sistem Informasi Pelanggaran Siswa
        </a>

        <a href="dashboard.php" class="btn btn-outline-light btn-sm">
            <i class="bi bi-house-door me-1"></i>
            Dashboard
        </a>

    </div>

</nav>

<!-- FORM TAMBAH TAHUN AJARAN -->
<div class="container container-form">

    <div class="card">

        <!-- HEADER -->
        <div class="card-header">

            <h4>
                <i class="bi bi-calendar-plus me-2"></i>
                Tambah Tahun Ajaran
            </h4>

            <p>
                Masukkan data tahun ajaran dengan lengkap dan benar.
            </p>

        </div>

        <!-- BODY -->
        <div class="card-body">

            <form action="proses_tambah_tahun_ajaran.php" method="POST">

                <div class="row">

                    <!-- NAMA TAHUN AJARAN -->
                    <div class="col-md-6 mb-4">

                        <label for="nama_tahun_ajaran" class="form-label">
                            Nama Tahun Ajaran
                        </label>

                        <input type="text"
                               class="form-control"
                               id="nama_tahun_ajaran"
                               name="nama_tahun_ajaran"
                               placeholder="Contoh: 2026/2027"
                               required>

                    </div>

                    <!-- TANGGAL MULAI -->
                    <div class="col-md-6 mb-4">

                        <label for="tanggal_mulai" class="form-label">
                            Tanggal Mulai
                        </label>

                        <input type="date"
                               class="form-control"
                               id="tanggal_mulai"
                               name="tanggal_mulai"
                               required>

                    </div>

                    <!-- TANGGAL SELESAI -->
                    <div class="col-md-6 mb-4">

                        <label for="tanggal_selesai" class="form-label">
                            Tanggal Selesai
                        </label>

                        <input type="date"
                               class="form-control"
                               id="tanggal_selesai"
                               name="tanggal_selesai"
                               required>

                    </div>

                    <!-- STATUS AKTIF -->
                    <div class="col-md-6 mb-4">

                        <label for="status_aktif" class="form-label">
                            Status Aktif
                        </label>

                        <select class="form-select"
                                id="status_aktif"
                                name="status_aktif"
                                required>

                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>

                        </select>

                    </div>

                </div>

                <!-- TOMBOL -->
                <div class="d-flex justify-content-between align-items-center
                            mt-3 pt-4 border-top">

                    <a href="tahun_ajaran.php"
                       class="btn btn-secondary">

                        <i class="bi bi-arrow-left me-2"></i>
                        Kembali

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-save me-2"></i>
                        Simpan Data

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- FOOTER -->
<footer>
    &copy; 2026 Sistem Informasi Pelanggaran Siswa
</footer>

</body>
</html>
