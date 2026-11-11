<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>


<div class="bg-dark text-white min-vh-100 d-flex flex-column p-3"
     style="width: 275px; position: fixed; top: 0; left: 0;">

    <h5 class="fw-bold mb-4">Pelanggaran Siswa</h5>

    <ul class="nav nav-pills flex-column gap-2">

        <li class="nav-item">
            <a href="dashboard_guru.php"
               class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'dashboard_guru.php' ? 'active' : '' ?>">
                Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="catat_pelanggaran.php"
               class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'catat_pelanggaran.php' ? 'active' : '' ?>">
                Catat Pelanggaran
            </a>
        </li>

        <li class="nav-item">
            <a href="tindakan_pelanggaran.php"
               class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'tindakan_pelanggaran.php' ? 'active' : '' ?>">
                Tindakan
            </a>
        </li>

        <li class="nav-item">
            <a href="rekap_poin.php"
                class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'rekap_poin.php' ? 'active' : '' ?>">
                Rekap Poin
            </a>
        </li>

        <li class="nav-item">
            <a href="riwayat_pelanggaran.php"
               class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'riwayat_pelanggaran.php' ? 'active' : '' ?>">
                Riwayat
            </a>
        </li>

        <li class="nav-item">
            <a href="Laporan.php"
               class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'Laporan_guru.php' ? 'active' : '' ?>">
                Laporan
            </a>
        </li>

    </ul>

    <div class="mt-auto pt-3 border-top">
        <a href="logout.php" class="nav-link text-danger">
            Logout
        </a>
    </div>

</div>


<style>
    .nav-link {
        padding: 10px 15px;
        transition: 0.2s;
    }

    .nav-link:hover {
        background-color: #343a40;
        color: white !important;
    }

    .nav-link.active {
        background-color: #0d6efd !important;
        color: white !important;
    }
</style>