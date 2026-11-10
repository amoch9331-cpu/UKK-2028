
<?php
// proses_login.php
session_start();

include 'config/koneksi.php';

// Pastikan data dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

// Mengambil data dari form
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi input
if (empty($email) || empty($password)) {
    $_SESSION['error'] = "Email dan password wajib diisi!";
    header("Location: login.php");
    exit;
}

// Mencari email di database
$sql = "SELECT * FROM t_users WHERE email = ?";
$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

// Memeriksa apakah email ditemukan
if ($data) {

    // Memeriksa password
    if (password_verify($password, $data['password'])) {

        // Membuat session baru
        session_regenerate_id(true);

        $_SESSION['login'] = true;
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['name'] = $data['name'];
        $_SESSION['role'] = $data['role'];

        // Login berhasil
        header("Location: dashboard.php");
        exit;

    } else {

        $_SESSION['error'] = "Password salah!";
        header("Location: login.php");
        exit;
    }

} else {

    $_SESSION['error'] = "Email tidak ditemukan!";
    header("Location: login.php");
    exit;
}

mysqli_stmt_close($stmt);
?>
