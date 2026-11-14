
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Wali Kelas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f1f5f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* NAVBAR */
        .navbar-custom {
            background-color: #343a40;
            padding: 14px 0;
            color: white;
        }

        .navbar-container {
            max-width: 1140px;
            margin: auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            color: white;
            text-decoration: none;
            font-size: 21px;
            font-weight: bold;
        }

        .brand i {
            margin-right: 10px;
        }

        .btn-dashboard {
            color: white;
            border: 1px solid white;
            padding: 11px 25px;
            border-radius: 9px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-dashboard:hover {
            background-color: white;
            color: #343a40;
        }

        /* MAIN CONTENT */
        .main-content {
            width: 100%;
            max-width: 1015px;
            margin: 45px auto 30px;
            padding: 0 20px;
            flex: 1;
        }

        /* FORM CARD */
        .form-card {
            background-color: white;
            border-radius: 13px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        }

        .form-header {
            background-color: #f0f0f0;
            padding: 24px 30px;
            border-bottom: 1px solid #dee2e6;
        }

        .form-header h4 {
            font-size: 24px;
            font-weight: 700;
            color: #343a40;
            margin-bottom: 8px;
        }

        .form-header h4 i {
            margin-right: 10px;
        }

        .form-header p {
            color: #64748b;
            margin: 0;
            font-size: 16px;
        }

        /* FORM BODY */
        .form-body {
            padding: 35px;
        }

        .form-label {
            color: #334155;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 11px;
        }

        .form-control,
        .form-select {
            height: 51px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 15px;
            color: #334155;
            background-color: white;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #64748b;
            box-shadow: 0 0 0 3px rgba(52, 58, 64, 0.10);
        }

        /* BUTTON */
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #dee2e6;
            padding-top: 24px;
            margin-top: 35px;
        }

        .btn-kembali {
            background-color: #343a40;
            color: white;
            padding: 13px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-kembali:hover {
            background-color: #23272b;
            color: white;
        }

        .btn-simpan {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 13px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-simpan:hover {
            background-color: #343a40;
            color: white;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            padding: 15px;
            margin-top: auto;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .navbar-container {
                padding: 0 15px;
            }

            .brand {
                font-size: 16px;
            }

            .btn-dashboard {
                padding: 9px 13px;
                font-size: 13px;
            }

            .main-content {
                margin-top: 30px;
                padding: 0 15px;
            }

            .form-header {
                padding: 22px;
            }

            .form-header h4 {
                font-size: 21px;
            }

            .form-body {
                padding: 23px;
            }

            .form-footer {
                gap: 15px;
            }

            .btn-kembali,
            .btn-simpan {
                padding: 12px 17px;
                font-size: 14px;
            }
        }

        @media (max-width: 380px) {
            .brand {
                font-size: 14px;
            }

            .btn-dashboard {
                padding: 8px 10px;
            }

            .form-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-kembali,
            .btn-simpan {
                text-align: center;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar-custom">
        <div class="navbar-container">

            <a href="dashboard.php" class="brand">
                <i class="bi bi-mortarboard-fill"></i>
                Sistem Informasi Pelanggaran Siswa
            </a>

            <a href="dashboard.php" class="btn-dashboard">
                <i class="bi bi-house-door me-2"></i>
                Dashboard
            </a>

        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-content">

        <div class="form-card">

            <!-- HEADER -->
            <div class="form-header">
                <h4>
                    <i class="bi bi-plus-circle"></i>
                    Tambah Wali Kelas
                </h4>

                <p>
                    Masukkan data wali kelas dengan lengkap dan benar.
                </p>
            </div>

            <!-- FORM -->
            <div class="form-body">

                <form action="proses_tambah_wali_kelas.php" method="POST">

                    <div class="row g-4">

                        <!-- ID GURU -->
                        <div class="col-md-6">
                            <label class="form-label">ID Guru</label>

                            <input
                                type="number"
                                name="id_guru"
                                class="form-control"
                                placeholder="Contoh: 101"
                                required>
                        </div>

                        <!-- ID KELAS -->
                        <div class="col-md-6">
                            <label class="form-label">ID Kelas</label>

                            <input
                                type="number"
                                name="id_kelas"
                                class="form-control"
                                placeholder="Contoh: 12"
                                required>
                        </div>

                        <!-- TAHUN AJARAN -->
                        <div class="col-md-6">
                            <label for="tahun_ajaran" class="form-label">
                                Tahun Ajaran
                            </label>

                            <select
                                name="tahun_ajaran"
                                id="tahun_ajaran"
                                class="form-select"
                                required>

                                <option value="2026/2027" selected>
                                    2026/2027
                                </option>

                            </select>
                        </div>

                    </div>

                    <!-- BUTTON -->
                    <div class="form-footer">

                        <a href="kelola_wali_kelas.php" class="btn-kembali">
                            <i class="bi bi-arrow-left me-2"></i>
                            Kembali
                        </a>

                        <button type="submit" class="btn-simpan">
                            <i class="bi bi-save me-2"></i>
                            Simpan Data
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="footer">
        &copy; 2026 Sistem Informasi Pelanggaran Siswa
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
