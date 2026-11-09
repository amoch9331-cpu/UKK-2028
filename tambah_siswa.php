<?php
include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
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
        }

        .navbar-brand {
            color: white;
            font-weight: bold;
        }

        .navbar-brand:hover {
            color: #ddd;
        }

        /* CONTAINER FORM */
        .container-form {
            max-width: 1100px;
            margin: 40px auto;
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

        .card-header small {
            color: #666;
        }

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
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background-color: #fff;
            color: #333;
            font-size: 15px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6c757d;
            box-shadow: 0 0 0 0.2rem rgba(108,117,125,0.15);
        }

        textarea.form-control {
            resize: vertical;
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
            color: white;
        }

        .btn-secondary {
            background: #343a40;
            border-color: #343a40;
            color: white;
        }

        .btn-secondary:hover {
            background: #23272b;
            border-color: #23272b;
            color: white;
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
            background: #343a40;
            color: white;
            text-align: center;
            padding: 18px;
            margin-top: 50px;
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
                font-size: 15px;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
    <div class="container">

        <a class="navbar-brand" href="dashboard.php">
            Sistem Informasi Pelanggaran Siswa
        </a>

        <a href="dashboard.php" class="btn btn-outline-light btn-sm">
            Dashboard
        </a>

    </div>
</nav>

<!-- FORM TAMBAH SISWA -->
<div class="container container-form">

    <div class="card">

        <!-- HEADER -->
        <div class="card-header">

            <h4>Tambah Siswa</h4>

            <small>
                Masukkan data siswa dengan lengkap dan benar.
            </small>

        </div>

        <!-- FORM -->
        <div class="card-body">

            <form action="proses_tambah_siswa.php" method="POST">

                <div class="row">

                    <!-- NIS (Limit 15 Karakter) -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label">NIS</label>

                        <input type="text"
                               name="nis"
                               class="form-control"
                               placeholder="Masukkan NIS (15 digit)"
                               minlength="15"
                               maxlength="15"
                               pattern="[0-9]{15}"
                               inputmode="numeric"
                               required>

                    </div>

                    <!-- NISN (Limit 10 Karakter) -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label">NISN</label>

                        <input type="text"
                               name="nisn"
                               class="form-control"
                               placeholder="Masukkan NISN (10 digit)"
                               minlength="10"
                               maxlength="10"
                               pattern="[0-9]{10}"
                               inputmode="numeric"
                               required>

                    </div>

                    <!-- NAMA SISWA -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Nama Siswa</label>

                        <input type="text"
                               name="nama"
                               class="form-control"
                               placeholder="Masukkan nama lengkap siswa"
                               required>

                    </div>

                    <!-- JENIS KELAMIN -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Jenis Kelamin</label>

                        <select name="jenis_kelamin"
                                class="form-select"
                                required>

                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>

                        </select>

                    </div>

                    <!-- TANGGAL LAHIR -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Tanggal Lahir</label>

                        <input type="date"
                               name="tanggal_lahir"
                               class="form-control"
                               required>

                    </div>

                    <!-- STATUS AKTIF -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Status Aktif</label>

                        <select name="status_aktif"
                                class="form-select"
                                required>

                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>

                        </select>

                    </div>

                    <!-- ALAMAT -->
                    <div class="col-md-12 mb-3">

                        <label class="form-label">Alamat</label>

                        <textarea name="alamat"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Masukkan alamat lengkap siswa"
                                  required></textarea>

                    </div>

                </div>

                <!-- TOMBOL -->
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">

                    <a href="kelola_siswa.php"
                       class="btn btn-secondary">

                        Kembali

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

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