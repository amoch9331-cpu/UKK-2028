
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = strtolower(trim($_SESSION['role'] ?? ''));
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav id="sidebar" class="sidebar text-white">

    <div class="sidebar-inner">

        <!-- BRAND -->
        <div class="sidebar-brand">
            <h5>PELАNGGARAN SISWA</h5>
            <small>Role: <?= htmlspecialchars(ucfirst($role)); ?></small>
        </div>

        <hr>

        <!-- MENU -->
        <ul class="nav flex-column gap-1">

            <?php if ($role === 'admin'): ?>

                <li>
                    <a class="nav-link <?= $current_page == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                <li>
                    <a class="nav-link <?= $current_page == 'kelola_siswa.php' ? 'active' : ''; ?>" href="kelola_siswa.php">
                        <i class="bi bi-people"></i> Data Siswa
                    </a>
                </li>

                <li>
                    <a class="nav-link <?= $current_page == 'kelola_guru.php' ? 'active' : ''; ?>" href="kelola_guru.php">
                        <i class="bi bi-person-badge"></i> Data Guru
                    </a>
                </li>

                <li>
                    <a class="nav-link <?= $current_page == 'kelola_kelas.php' ? 'active' : ''; ?>" href="kelola_kelas.php">
                        <i class="bi bi-door-open"></i> Data Kelas
                    </a>
                </li>

                <li>
                    <a class="nav-link <?= $current_page == 'kelola_tahun_ajaran.php' ? 'active' : ''; ?>" href="kelola_tahun_ajaran.php">
                        <i class="bi bi-calendar"></i> Data Tahun Ajaran
                    </a>
                </li>

                <li>
                    <a class="nav-link <?= $current_page == 'kelola_wali_kelas.php' ? 'active' : ''; ?>" href="kelola_wali_kelas.php">
                        <i class="bi bi-person-workspace"></i> Data Wali Kelas
                    </a>
                </li>

                <li>
                    <a class="nav-link <?= $current_page == 'penempatan_siswa.php' ? 'active' : ''; ?>" href="penempatan_siswa.php">
                        <i class="bi bi-person-lines-fill"></i> Penempatan Siswa
                    </a>
                </li>

                <li>
                    <a class="nav-link <?= $current_page == 'kelola_pelanggaran_kategori.php' ? 'active' : ''; ?>" href="kelola_pelanggaran_kategori.php">
                        <i class="bi bi-exclamation-triangle"></i> Kelola Pelanggaran
                    </a>
                </li>

            <?php else: ?>

                <li>
                    <a class="nav-link <?= $current_page == 'dashboard_guru.php' ? 'active' : ''; ?>" href="dashboard_guru.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                <li>
                    <a class="nav-link <?= $current_page == 'catat_pelanggaran.php' ? 'active' : ''; ?>" href="catat_pelanggaran.php">
                        <i class="bi bi-pencil-square"></i> Catat Pelanggaran
                    </a>
                </li>

                <li>
                    <a class="nav-link <?= $current_page == 'tindakan.php' ? 'active' : ''; ?>" href="tindakan.php">
                        <i class="bi bi-clipboard-check"></i> Tindakan
                    </a>
                </li>

                <li>
                    <a class="nav-link <?= $current_page == 'riwayat.php' ? 'active' : ''; ?>" href="riwayat.php">
                        <i class="bi bi-clock-history"></i> Riwayat
                    </a>
                </li>

            <?php endif; ?>

            <hr class="border-secondary">

            <li>
                <a class="nav-link <?= $current_page == 'rekap_poin.php' ? 'active' : ''; ?>" href="rekap_poin.php">
                    <i class="bi bi-bar-chart"></i> Rekap Poin
                </a>
            </li>

            <li>
                <a class="nav-link <?= $current_page == 'laporan.php' ? 'active' : ''; ?>" href="laporan.php">
                    <i class="bi bi-file-earmark-text"></i> Laporan
                </a>
            </li>

        </ul>

        <!-- LOGOUT -->
        <div class="logout-menu">
            <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>

    </div>
</nav>

<style>
.sidebar {
    width: 260px;
    min-width: 260px;
    min-height: 100vh;
    flex-shrink: 0;
    background: #1a1d20;
    color: white;
    position: relative;
    z-index: 10;
}

.sidebar-inner {
    padding: 25px 15px;
    position: sticky;
    top: 0;
}

.sidebar-brand {
    padding: 10px;
}

.sidebar-brand h5 {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 8px;
}

.sidebar-brand small {
    color: #adb5bd;
    font-size: 12px;
}

.sidebar .nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #ced4da;
    padding: 11px 13px;
    border-radius: 8px;
    font-size: 14px;
    transition: 0.2s;
}

.sidebar .nav-link:hover {
    color: white;
    background: #343a40;
}

.sidebar .nav-link.active {
    background: #0d6efd;
    color: white;
}

.sidebar .nav-link i {
    font-size: 17px;
}

.logout-menu {
    margin-top: 25px;
    border-top: 1px solid #495057;
    padding-top: 15px;
}

.logout-menu a {
    display: block;
    padding: 11px 13px;
    color: #ff6b6b;
    text-decoration: none;
    border-radius: 8px;
}

.logout-menu a:hover {
    background: rgba(220,53,69,0.15);
}
    
@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        min-width: 100%;
        min-height: auto;
    }

    .sidebar-inner {
        position: relative;
    }
}
</style>
