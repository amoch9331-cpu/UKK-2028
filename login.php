<?php
// login.php
session_start();
// Jika sudah login, arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Informasi Pelanggaran Siswa</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f1f5f9;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 450px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background: white;
        }
        .card-header {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 25px;
            border-bottom: none;
        }
        .card-body {
            padding: 35px;
        }
        .form-label {
            font-weight: 600;
            color: #334155;
        }
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
        }
        .form-control:focus {
            border-color: #343a40;
            box-shadow: 0 0 0 0.2rem rgba(52, 58, 64, 0.15);
        }
        .btn-dark {
            background-color: #343a40;
            border-color: #343a40;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-dark:hover {
            background-color: #23272b;
            border-color: #1d2124;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            
            <div class="login-card">
                <div class="card-header">
                    <h4 class="mb-1"><i class="bi bi-shield-lock-fill me-2"></i>Login</h4>
                    <p class="mb-0 text-muted small text-white-50">Sistem Informasi Pelanggaran Siswa</p>
                </div>

                <div class="card-body">
                    
                    <!-- Notifikasi Error jika ada -->
                    <?php if (isset($_SESSION['error'])) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php } ?>

                    <form action="proses_login.php" method="POST">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="nama@email.com" 
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Masukkan password" 
                                   required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="text-center mt-3 text-muted small">
                &copy; 2026 Sistem Informasi Pelanggaran Siswa
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>