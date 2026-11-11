
<?php
// tambah_guru.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data user dari database
$query_user = mysqli_query($koneksi, "SELECT id FROM t_users ORDER BY id ASC");

if (!$query_user) {
    die("Gagal mengambil data user: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Guru - Sistem Pelanggaran Siswa</title>

    <!-- Bootstrap 5 -->
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
            background-color: #f1f5f9;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            color: #333;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
        }

        .navbar-brand:hover {
            color: #ddd !important;
        }

        .container-form {
            max-width: 1100px;
            margin: 40px auto;
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background: white;
        }

        .card-header {
            background-color: #f1f1f1;
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
        }

        .card-body {
            padding: 35px;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 10px;
        }

        .form-control,
        .form-select {
            padding: 13px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background-color: #fff;
            color: #333;
            font-size: 15px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6c757d;
            box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.15);
        }

        .password-group {
            position: relative;
        }

        .password-group .form-control {
            padding-right: 48px;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            font-size: 18px;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-primary:hover {
            background-color: #565e64;
            border-color: #565e64;
            color: white;
        }

        .btn-secondary {
            background-color: #343a40;
            border-color: #343a40;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #23272b;
            border-color: #23272b;
            color: white;
        }

        .btn-outline-light {
            border-color: white;
            color: white;
        }

        .btn-outline-light:hover {
            background-color: white;
            color: #343a40;
        }

        .footer {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            padding: 20px;
        }

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
            <i class="bi bi-mortarboard-fill me-2"></i>
            Pelanggaran Siswa
        </a>

        <a href="dashboard.php" class="btn btn-outline-light btn-sm">
            <i class="bi bi-house-door me-1"></i>
            Dashboard
        </a>

    </div>
</nav>

<!-- KONTEN FORM -->
<div class="container container-form">

    <div class="card">

        <!-- HEADER -->
        <div class="card-header">

            <h4>
                <i class="bi bi-person-plus-fill me-2"></i>
                Tambah Guru
            </h4>

            <p class="mb-0 mt-2">
                Lengkapi formulir untuk menambahkan data guru.
            </p>

        </div>

        <!-- FORM -->
        <div class="card-body">

            <form action="proses_tambah_guru.php" method="POST" id="formGuru">

                <div class="row">

                    <!-- NIP -->
                    <div class="col-md-6 mb-3">

                        <label for="nip" class="form-label">
                            NIP
                        </label>

                        <input type="text"
                               class="form-control"
                               id="nip"
                               name="nip"
                               placeholder="Masukkan NIP (18 digit)"
                               minlength="18"
                               maxlength="18"
                               pattern="[0-9]{18}"
                               inputmode="numeric"
                               title="NIP harus terdiri dari 18 angka"
                               required>

                    </div>

                    <!-- NAMA GURU -->
                    <div class="col-md-6 mb-3">

                        <label for="nama" class="form-label">
                            Nama Guru
                        </label>

                        <input type="text"
                               class="form-control"
                               id="nama"
                               name="nama"
                               placeholder="Masukkan nama guru"
                               required>

                    </div>

                    <!-- EMAIL -->
                    <div class="col-md-6 mb-3">

                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input type="email"
                               class="form-control"
                               id="email"
                               name="email"
                               placeholder="contoh@email.com"
                               required>

                    </div>

                    <!-- PASSWORD -->
                    <div class="col-md-6 mb-3">

                        <label for="password" class="form-label">
                            Password
                        </label>

                        <div class="password-group">

                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   name="password"
                                   placeholder="Masukkan password"
                                   minlength="6"
                                   autocomplete="new-password"
                                   required>

                            <button type="button"
                                    class="toggle-password"
                                    onclick="togglePassword('password', 'iconPassword')">

                                <i class="bi bi-eye" id="iconPassword"></i>

                            </button>

                        </div>

                        <small class="text-muted">
                            Minimal 6 karakter.
                        </small>

                    </div>

                    <!-- KONFIRMASI PASSWORD -->
                    <div class="col-md-6 mb-3">

                        <label for="konfirmasi_password" class="form-label">
                            Konfirmasi Password
                        </label>

                        <div class="password-group">

                            <input type="password"
                                   class="form-control"
                                   id="konfirmasi_password"
                                   name="konfirmasi_password"
                                   placeholder="Ulangi password"
                                   minlength="6"
                                   autocomplete="new-password"
                                   required>

                            <button type="button"
                                    class="toggle-password"
                                    onclick="togglePassword('konfirmasi_password', 'iconKonfirmasi')">

                                <i class="bi bi-eye" id="iconKonfirmasi"></i>

                            </button>

                        </div>

                        <small id="pesanPassword" class="text-muted">
                            Masukkan ulang password yang sama.
                        </small>

                    </div>

                    <!-- STATUS AKTIF -->
                    <div class="col-md-6 mb-3">

                        <label for="status_aktif" class="form-label">
                            Status Aktif
                        </label>

                        <select class="form-select"
                                id="status_aktif"
                                name="status_aktif"
                                required>

                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>

                        </select>

                    </div>

                    <!-- USER -->
                    <div class="col-md-6 mb-3">

                        <label for="user_id" class="form-label">
                            User
                        </label>

                        <select class="form-select"
                                id="user_id"
                                name="user_id"
                                required>

                            <option value="">
                                -- Pilih User --
                            </option>

                            <?php while ($user = mysqli_fetch_assoc($query_user)) { ?>

                                <option value="<?= htmlspecialchars($user['id']); ?>">
                                    User ID: <?= htmlspecialchars($user['id']); ?>
                                </option>

                            <?php } ?>

                        </select>

                    </div>

                </div>

                <!-- TOMBOL -->
                <div class="d-flex justify-content-between align-items-center
                            mt-4 pt-3 border-top">

                    <a href="kelola_guru.php"
                       class="btn btn-secondary">

                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-save me-1"></i>
                        Simpan Data

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- FOOTER -->
<div class="footer">
    &copy; 2026 Sistem Informasi Pelanggaran Siswa
</div>

<!-- JAVASCRIPT -->
<script>

// Tampilkan / sembunyikan password
function togglePassword(inputId, iconId) {

    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === "password") {

        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");

    } else {

        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");

    }
}

// Validasi password harus sama
const password = document.getElementById("password");
const konfirmasi = document.getElementById("konfirmasi_password");
const pesan = document.getElementById("pesanPassword");
const form = document.getElementById("formGuru");

function cekPassword() {

    if (konfirmasi.value === "") {

        konfirmasi.setCustomValidity("");
        pesan.textContent = "Masukkan ulang password yang sama.";
        pesan.className = "text-muted";

    } else if (password.value !== konfirmasi.value) {

        konfirmasi.setCustomValidity("Password tidak sama!");
        pesan.textContent = "Password tidak sama!";
        pesan.className = "text-danger";

    } else {

        konfirmasi.setCustomValidity("");
        pesan.textContent = "Password cocok!";
        pesan.className = "text-success";

    }

}

password.addEventListener("input", cekPassword);
konfirmasi.addEventListener("input", cekPassword);

form.addEventListener("submit", function(event) {

    cekPassword();

    if (!form.checkValidity()) {
        event.preventDefault();
        form.reportValidity();
    }

});

</script>

</body>
</html>
