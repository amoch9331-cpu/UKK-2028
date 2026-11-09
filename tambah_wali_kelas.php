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

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f1f5f9;
        }

        /* SIDEBAR */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: #111827;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            padding: 35px 25px;
        }

        .sidebar h3 {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 45px;
        }

        .sidebar a {
            display: block;
            color: #cbd5e1;
            text-decoration: none;
            padding: 16px 18px;
            border-radius: 10px;
            margin-bottom: 12px;
            font-size: 17px;
            transition: 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #2563eb;
            color: white;
        }

        /* CONTENT */
        .content {
            margin-left: 280px;
            padding: 50px;
        }

        .header h2 {
            font-size: 38px;
            font-weight: 700;
            color: #1e293b;
        }

        .header p {
            color: #64748b;
            font-size: 18px;
        }

        /* FORM CARD */
        .form-card {
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
            max-width: 1100px;
            width: 100%;
        }

        .form-card h4 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #1e293b;
        }

        .form-card .subtitle {
            color: #64748b;
            font-size: 17px;
            margin-bottom: 40px;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 19px;
            margin-bottom: 12px;
        }

        .form-control {
            padding: 17px 20px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-size: 18px;
            min-height: 60px;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        /* BUTTON */
        .btn-simpan {
            background: #2563eb;
            color: white;
            padding: 16px 32px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 18px;
            transition: 0.3s;
        }

        .btn-simpan:hover {
            background: #1d4ed8;
            color: white;
        }

        .btn-kembali {
            background: #e2e8f0;
            color: #334155;
            padding: 16px 32px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 18px;
            transition: 0.3s;
        }

        .btn-kembali:hover {
            background: #cbd5e1;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 20px;
            }

            .sidebar h3 {
                margin-bottom: 20px;
            }

            .content {
                margin-left: 0;
                padding: 20px;
            }

            .header h2 {
                font-size: 28px;
            }

            .form-card {
                padding: 25px;
            }

            .form-card h4 {
                font-size: 23px;
            }

            .form-control {
                min-height: 55px;
                font-size: 16px;
            }

            .form-label {
                font-size: 17px;
            }

            .btn-simpan,
            .btn-kembali {
                padding: 13px 20px;
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h3>SIPENSI</h3>

        <a href="dashboard.php">Dashboard</a>
        <a href="kelola_siswa.php">Data Siswa</a>
        <a href="kelola_guru.php">Data Guru</a>
        <a href="kelola_kelas.php">Data Kelas</a>
        <a href="kelola_pelanggaran.php">Data Pelanggaran</a>
        <a href="tahun_ajaran.php">Tahun Ajaran</a>
        <a href="kelola_wali_kelas.php" class="active">Wali Kelas</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <div class="header mb-5">
            <h2>Tambah Wali Kelas</h2>
            <p>Tambahkan data wali kelas baru ke dalam sistem.</p>
        </div>

        <div class="form-card">

            <h4>Form Data Wali Kelas</h4>

            <p class="subtitle">
                Silakan lengkapi informasi wali kelas di bawah ini.
            </p>

            <form action="proses_tambah_wali_kelas.php" method="POST">

                <div class="mb-5">
                    <label class="form-label">ID Guru</label>
                    <input type="number"
                           name="id_guru"
                           class="form-control"
                           placeholder="Masukkan ID Guru"
                           required>
                </div>

                <div class="mb-5">
                    <label class="form-label">ID Kelas</label>
                    <input type="number"
                           name="id_kelas"
                           class="form-control"
                           placeholder="Masukkan ID Kelas"
                           required>
                </div>

                <div class="mb-5">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text"
                           name="tahun_ajaran"
                           class="form-control"
                           placeholder="Contoh: 2026/2027"
                           pattern="[0-9]{4}/[0-9]{4}"
                           required>
                </div>

                <div class="d-flex gap-3 mt-4 flex-wrap">
                    <button type="submit" class="btn-simpan">
                        Simpan Data
                    </button>

                    <a href="kelola_wali_kelas.php" class="btn-kembali">
                        Kembali
                    </a>
                </div>

            </form>

        </div>

    </div>

</body>
</html>
