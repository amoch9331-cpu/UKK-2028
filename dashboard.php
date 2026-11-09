<?php
// dashboard.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil jumlah data statistik ringkas
$jml_siswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM t_siswa"));
$jml_guru  = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM t_guru"));
$jml_kelas = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM t_kelas"));

// Ambil data statistik pelanggaran per bulan (Contoh Query)
// Silakan sesuaikan nama tabel & kolom dengan database Anda (misal: t_pelanggaran, tanggal)
$bulan_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$data_pelanggaran = [12, 19, 8, 15, 22, 10, 14, 25, 18, 20, 11, 9]; // Data dummy / default
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Sistem Informasi Pelanggaran Siswa</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        main {
            min-height: 100vh;
        }
        /* Style untuk Kartu Statistik & Grafik */
        .stat-card, .chart-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            background: white;
        }
        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }
        .icon-box {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 24px;
            background-color: #f1f5f9;
            color: #343a40;
        }
        /* Penyesuaian warna aktif sidebar/aksen menjadi Hitam */
        .nav-link.active, .bg-primary {
            background-color: #000000 !important;
            border-color: #000000 !important;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA DASHBOARD -->
        <main class="col-md-9 col-lg-10 p-4">

            <!-- Header Sambutan -->
            <div class="mb-4">
                <h2 class="fw-bold text-dark">Dashboard</h2>
                <p class="text-muted">Selamat datang kembali di Sistem Informasi Pelanggaran Siswa.</p>
            </div>

            <!-- Baris Kartu Statistik -->
            <div class="row g-4 mb-4">
                
                <!-- Card Data Siswa -->
                <div class="col-md-4">
                    <div class="stat-card p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fs-7 fw-semibold mb-1">Data Siswa</h6>
                                <h3 class="fw-bold mb-0 text-dark"><?= $jml_siswa; ?></h3>
                            </div>
                            <div class="icon-box">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Data Guru -->
                <div class="col-md-4">
                    <div class="stat-card p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fs-7 fw-semibold mb-1">Data Guru</h6>
                                <h3 class="fw-bold mb-0 text-dark"><?= $jml_guru; ?></h3>
                            </div>
                            <div class="icon-box">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Kelas -->
                <div class="col-md-4">
                    <div class="stat-card p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fs-7 fw-semibold mb-1">Kelas</h6>
                                <h3 class="fw-bold mb-0 text-dark"><?= $jml_kelas; ?></h3>
                            </div>
                            <div class="icon-box">
                                <i class="bi bi-door-open-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- GRAFIK PELANGGARAN SISWA -->
            <div class="row">
                <div class="col-12">
                    <div class="chart-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0">
                                <i class="bi bi-bar-chart-line-fill me-2"></i>Grafik Pelanggaran Siswa
                            </h5>
                            <span class="badge bg-dark">Tahun 2026</span>
                        </div>
                        <div style="position: relative; height:350px;">
                            <canvas id="chartPelanggaran"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </main>

    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Script Inisialisasi Grafik -->
<script>
const ctx = document.getElementById('chartPelanggaran').getContext('2d');
const chartPelanggaran = new Chart(ctx, {
    type: 'line', // Bisa diganti 'bar' jika ingin grafik batang
    data: {
        labels: <?= json_encode($bulan_labels); ?>,
        datasets: [{
            label: 'Jumlah Pelanggaran',
            data: <?= json_encode($data_pelanggaran); ?>,
            borderColor: '#000000',
            backgroundColor: 'rgba(0, 0, 0, 0.05)',
            borderWidth: 2,
            tension: 0.3,
            fill: true,
            pointBackgroundColor: '#000000',
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});
</script>

</body>
</html>