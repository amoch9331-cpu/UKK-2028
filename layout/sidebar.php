
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = strtolower(trim($_SESSION['role'] ?? ''));
$current_page = basename($_SERVER['PHP_SELF']);

function menuAktif($halaman, $current_page)
{
    return $current_page === $halaman ? 'active' : '';
}
?>

<nav id="sidebar" class="sidebar text-white">

    <div class="sidebar-inner">

        <!-- BRAND -->
        <div class="sidebar-brand">
            <h5>PELANGGARAN SISWA</h5>
            <small>
                Role: <?= htmlspecialchars(ucfirst($role)); ?>
            </small>
        </div>

        <hr>

        <!-- MENU -->
        <ul class="nav flex-column gap-1">

            <?php if ($role === 'admin'): ?>

                <!-- DASHBOARD ADMIN -->
                <li>
                    <a class="nav-link <?= menuAktif('dashboard.php', $current_page); ?>"
                       href="dashboard.php">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- DATA SISWA -->
                <li>
                    <a class="nav-link <?= menuAktif('kelola_siswa.php', $current_page); ?>"
                       href="kelola_siswa.php">
                        <i class="bi bi-people"></i>
                        <span>Data Siswa</span>
                    </a>
                </li>

                <!-- DATA GURU -->
                <li>
                    <a class="nav-link <?= menuAktif('kelola_guru.php', $current_page); ?>"
                       href="kelola_guru.php">
                        <i class="bi bi-person-badge"></i>
                        <span>Data Guru</span>
                    </a>
                </li>

                <!-- DATA KELAS -->
                <li>
                    <a class="nav-link <?= menuAktif('kelola_kelas.php', $current_page); ?>"
                       href="kelola_kelas.php">
                        <i class="bi bi-door-open"></i>
                        <span>Data Kelas</span>
                    </a>
                </li>

                <!-- TAHUN AJARAN -->
                <li>
                    <a class="nav-link <?= menuAktif('kelola_tahun_ajaran.php', $current_page); ?>"
                       href="kelola_tahun_ajaran.php">
                        <i class="bi bi-calendar"></i>
                        <span>Data Tahun Ajaran</span>
                    </a>
                </li>

                <!-- WALI KELAS -->
                <li>
                    <a class="nav-link <?= menuAktif('kelola_wali_kelas.php', $current_page); ?>"
                       href="kelola_wali_kelas.php">
                        <i class="bi bi-person-workspace"></i>
                        <span>Data Wali Kelas</span>
                    </a>
                </li>

                <!-- PENEMPATAN SISWA -->
                <li>
                    <a class="nav-link <?= menuAktif('penempatan_siswa.php', $current_page); ?>"
                       href="penempatan_siswa.php">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Penempatan Siswa</span>
                    </a>
                </li>

                <!-- KELOLA PELANGGARAN -->
                <li class="menu-pelanggaran">
                    <a class="nav-link <?= menuAktif('kelola_pelanggaran_kategori.php', $current_page); ?>"
                       href="kelola_pelanggaran_kategori.php">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>Kelola Pelanggaran</span>
                    </a>
                </li>

            <?php else: ?>

                <!-- DASHBOARD GURU -->
                <li>
                    <a class="nav-link <?= menuAktif('dashboard_guru.php', $current_page); ?>"
                       href="dashboard_guru.php">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- CATAT PELANGGARAN -->
                <li>
                    <a class="nav-link <?= menuAktif('catat_pelanggaran.php', $current_page); ?>"
                       href="catat_pelanggaran.php">
                        <i class="bi bi-pencil-square"></i>
                        <span>Catat Pelanggaran</span>
                    </a>
                </li>

                <!-- TINDAKAN -->
                <li>
                    <a class="nav-link <?= menuAktif('tindakan.php', $current_page); ?>"
                       href="tindakan.php">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Tindakan</span>
                    </a>
                </li>

                <!-- RIWAYAT -->
                <li>
                    <a class="nav-link <?= menuAktif('riwayat.php', $current_page); ?>"
                       href="riwayat.php">
                        <i class="bi bi-clock-history"></i>
                        <span>Riwayat</span>
                    </a>
                </li>

            <?php endif; ?>


            <!-- PEMISAH KHUSUS MENU GURU -->
            <?php if ($role !== 'admin'): ?>
                <li class="menu-divider">
                    <hr class="border-secondary">
                </li>

                <!-- REKAP POIN -->
                <li>
                    <a class="nav-link <?= menuAktif('rekap_poin.php', $current_page); ?>"
                       href="rekap_poin.php">
                        <i class="bi bi-bar-chart"></i>
                        <span>Rekap Poin</span>
                    </a>
                </li>
            <?php endif; ?>


            <!-- LAPORAN -->
            <li class="menu-laporan">
                <a class="nav-link <?= menuAktif('laporan.php', $current_page); ?>"
                   href="laporan.php">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan</span>
                </a>
            </li>


            <!-- ABOUT ME KHUSUS ADMIN -->
            <?php if ($role === 'admin'): ?>
                <li>
                    <a class="nav-link <?= menuAktif('about_me.php', $current_page); ?>"
                       href="about_me.php">
                        <i class="bi bi-person-circle"></i>
                        <span>About Me</span>
                    </a>
                </li>
            <?php endif; ?>

        </ul>


        <!-- LOGOUT -->
        <div class="logout-menu">
            <a href="logout.php"
               onclick="return confirm('Apakah Anda yakin ingin keluar?');">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </div>

    </div>
</nav>


<style>

/* ==================================
   SIDEBAR UTAMA
================================== */

.sidebar {
    width: 260px;
    min-width: 260px;
    height: 100vh;
    flex-shrink: 0;
    background: #1a1d20;
    color: white;
    position: relative;
    z-index: 1000;
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: thin;
    scrollbar-color: #495057 #1a1d20;
}

.sidebar-inner {
    padding: 20px 12px;
    min-height: 100%;
    display: flex;
    flex-direction: column;
}

/* ==================================
   BRAND
================================== */

.sidebar-brand {
    padding: 10px 8px;
}

.sidebar-brand h5 {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 8px;
    color: #fff;
}

.sidebar-brand small {
    color: #adb5bd;
    font-size: 12px;
}

.sidebar hr {
    border-color: #495057;
    opacity: 1;
    margin: 12px 0;
}

/* ==================================
   MENU
================================== */

.sidebar .nav {
    flex: 1;
    padding: 0;
    margin: 0;
}

.sidebar .nav li {
    margin: 0;
    padding: 0;
}

.sidebar .nav-link {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    min-height: 40px;
    padding: 10px 12px;
    color: #ced4da;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    transition: 0.2s;
    text-decoration: none;
    cursor: pointer;
    pointer-events: auto;
}

.sidebar .nav-link i {
    width: 20px;
    min-width: 20px;
    font-size: 16px;
    text-align: center;
}

.sidebar .nav-link:hover {
    color: white;
    background: #343a40;
}

.sidebar .nav-link.active {
    background: #0d6efd;
    color: white;
    font-weight: 600;
}

/* ==================================
   JARAK PELANGGARAN DAN LAPORAN
================================== */

.sidebar .menu-pelanggaran {
    margin-bottom: 0 !important;
}

.sidebar .menu-laporan {
    margin-top: 0 !important;
    margin-bottom: 4px !important;
}

/* ==================================
   PEMISAH MENU GURU
================================== */

.sidebar .menu-divider {
    margin: 10px 0 !important;
}

.sidebar .menu-divider hr {
    margin: 0;
}

/* ==================================
   LOGOUT
================================== */

.logout-menu {
    margin-top: 20px;
    border-top: 1px solid #495057;
    padding-top: 12px;
    flex-shrink: 0;
}

.logout-menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 12px;
    color: #ff6b6b;
    text-decoration: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.logout-menu a i {
    width: 20px;
    text-align: center;
    font-size: 16px;
}

.logout-menu a:hover {
    background: rgba(220,53,69,0.15);
    color: #ff8585;
}

/* ==================================
   RESPONSIVE
================================== */

@media (max-width: 768px) {

    .sidebar {
        width: 100%;
        min-width: 100%;
        height: auto;
        min-height: auto;
    }

    .sidebar-inner {
        min-height: auto;
    }

    .sidebar .nav {
        flex: none;
    }

    .logout-menu {
        margin-top: 15px;
    }

}

</style>
