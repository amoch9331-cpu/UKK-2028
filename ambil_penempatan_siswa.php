
<?php

include 'includes/cek_session.php';
include 'config/koneksi.php';

header('Content-Type: application/json; charset=utf-8');

if (
    !isset($_GET['siswa_id']) ||
    !ctype_digit($_GET['siswa_id'])
) {
    echo json_encode([
        'status' => false,
        'pesan' => 'Siswa tidak valid.'
    ]);
    exit;
}

$siswa_id = (int) $_GET['siswa_id'];

$sql = "SELECT
            ks.kelas_id,
            ks.tahun_ajaran_id,
            k.nama AS nama_kelas,
            ta.nama AS nama_tahun

        FROM t_kelas_siswa ks

        LEFT JOIN t_kelas k
            ON ks.kelas_id = k.id

        LEFT JOIN t_tahun_ajaran ta
            ON ks.tahun_ajaran_id = ta.id

        WHERE ks.siswa_id = ?
          AND ks.status_aktif = 1

        ORDER BY ks.id DESC

        LIMIT 1";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    echo json_encode([
        'status' => false,
        'pesan' => 'Query gagal diproses.'
    ]);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $siswa_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$data = mysqli_fetch_assoc($result);

if ($data) {

    echo json_encode([
        'status' => true,
        'kelas_id' => $data['kelas_id'],
        'nama_kelas' => $data['nama_kelas'],
        'tahun_ajaran_id' => $data['tahun_ajaran_id'],
        'nama_tahun' => $data['nama_tahun']
    ]);

} else {

    echo json_encode([
        'status' => false,
        'pesan' => 'Penempatan siswa belum tersedia atau tidak aktif.'
    ]);

}

mysqli_stmt_close($stmt);
