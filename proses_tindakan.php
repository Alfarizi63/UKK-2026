
<?php
session_start();

include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tindakan.php');
    exit;
}

$pelanggaran_siswa_id = (int) ($_POST['pelanggaran_siswa_id'] ?? 0);
$tindakan = trim($_POST['tindakan'] ?? '');
$keterangan = trim($_POST['keterangan'] ?? '');
$tanggal = $_POST['tanggal'] ?? date('Y-m-d');

if (
    $pelanggaran_siswa_id <= 0 ||
    $tindakan === '' ||
    $tanggal === ''
) {
    $_SESSION['pesan'] = 'Semua data tindakan harus diisi.';
    header('Location: tindakan.php');
    exit;
}

$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO t_tindakan
    (pelanggaran_siswa_id, tindakan, keterangan, tanggal)
    VALUES (?, ?, ?, ?)"
);

if (!$stmt) {
    $_SESSION['pesan'] = 'Gagal menyiapkan data: ' . mysqli_error($koneksi);
    header('Location: tindakan.php');
    exit;
}

mysqli_stmt_bind_param(
    $stmt,
    'isss',
    $pelanggaran_siswa_id,
    $tindakan,
    $keterangan,
    $tanggal
);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['pesan'] = 'Data tindakan berhasil disimpan.';
} else {
    $_SESSION['pesan'] = 'Gagal menyimpan data tindakan: ' . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);

header('Location: tindakan.php');
exit;
?>
