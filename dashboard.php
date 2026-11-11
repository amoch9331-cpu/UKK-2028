
<?php
// dashboard.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// =====================================
// DATA STATISTIK
// =====================================

// Jumlah siswa
$query_siswa = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM t_siswa"
);

if (!$query_siswa) {
    die("Gagal mengambil data siswa: " . mysqli_error($koneksi));
}

$jml_siswa = mysqli_fetch_assoc($query_siswa)['total'];


// Jumlah guru
$query_guru = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM t_guru"
);

if (!$query_guru) {
    die("Gagal mengambil data guru: " . mysqli_error($koneksi));
}

$jml_guru = mysqli_fetch_assoc($query_guru)['total'];


// Jumlah kelas
$query_kelas = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM t_kelas"
);

if (!$query_kelas) {
    die("Gagal mengambil data kelas: " . mysqli_error($koneksi));
}

$jml_kelas = mysqli_fetch_assoc($query_kelas)['total'];


// =====================================
// DATA GRAFIK
// =====================================

$bulan_labels = [
    'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
    'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
];

// Data grafik sementara
$data_pelanggaran = [12, 19, 8, 15, 22, 10, 14, 25, 18, 20, 11, 9];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard - Sistem Informasi Pelanggaran Siswa</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
          rel="stylesheet">

    <style>

/* LAYOUT UTAMA */
body {
    background-color: #f8f9fa;
    font-family: Arial, sans-serif;
    margin: 0;
    overflow-x: hidden;
}

.container-fluid {
    padding: 0;
}

.layout-wrapper {
    display: flex;
    width: 100%;
    min-height: 100vh;
    align-items: stretch;
}

/* SIDEBAR */
.sidebar {
    width: 260px;
    min-width: 260px;
    min-height: 100vh;
    flex-shrink: 0;
}

/* KONTEN DASHBOARD */
.dashboard-content {
    flex: 1;
    min-width: 0;
    padding: 30px;
    overflow-x: hidden;
}

/* KARTU STATISTIK */
.stat-card,
.chart-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    background: white;
}

.stat-card {
    transition: 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
}

/* ICON */
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

/* JUDUL */
.dashboard-title {
    font-size: 30px;
    font-weight: bold;
}

.stat-number {
    font-size: 26px;
    font-weight: bold;
    color: #212529;
}

.stat-label {
    font-size: 14px;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .layout-wrapper {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        min-width: 100%;
        min-height: auto;
    }

    .dashboard-content {
        width: 100%;
        padding: 15px;
    }

    .dashboard-title {
        font-size: 25px;
    }
}

    </style>

</head>


<body>

<div class="layout-wrapper">

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- KONTEN DASHBOARD -->
    <main class="dashboard-content">

        <div class="mb-4">
            <h2 class="dashboard-title text-dark">
                Dashboard
            </h2>

            <p class="text-muted">
                Selamat datang kembali di Sistem Informasi Pelanggaran Siswa.
            </p>
        </div>

        <!-- KARTU STATISTIK -->
        <div class="row g-4 mb-4">

            <div class="col-md-6 col-xl-4">
                <div class="stat-card p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="stat-label mb-1">Data Siswa</h6>
                            <h3 class="stat-number mb-0">
                                <?= number_format($jml_siswa); ?>
                            </h3>
                        </div>

                        <div class="icon-box">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="stat-card p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="stat-label mb-1">Data Guru</h6>
                            <h3 class="stat-number mb-0">
                                <?= number_format($jml_guru); ?>
                            </h3>
                        </div>

                        <div class="icon-box">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="stat-card p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="stat-label mb-1">Data Kelas</h6>
                            <h3 class="stat-number mb-0">
                                <?= number_format($jml_kelas); ?>
                            </h3>
                        </div>

                        <div class="icon-box">
                            <i class="bi bi-door-open-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- GRAFIK PELANGGARAN -->
        <div class="chart-card p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-bar-chart-line-fill me-2"></i>
                    Grafik Pelanggaran Siswa
                </h5>

                <span class="badge bg-dark">
                    Tahun <?= date('Y'); ?>
                </span>

            </div>

            <div style="position: relative; height:350px;">
                <canvas id="chartPelanggaran"></canvas>
            </div>

        </div>

    </main>

</div>
</body>
</html>


<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

// =====================================
// GRAFIK PELANGGARAN SISWA
// =====================================

const ctx = document.getElementById('chartPelanggaran').getContext('2d');

const chartPelanggaran = new Chart(ctx, {

    type: 'line',

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
