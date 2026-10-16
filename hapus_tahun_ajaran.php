
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID tahun ajaran tidak valid.');
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($koneksi, "DELETE FROM t_tahun_ajaran WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: kelola_tahun_ajaran.php");
    exit;
} else {
    echo "Gagal menghapus tahun ajaran. Data mungkin sedang digunakan oleh penempatan siswa.";
}
?>
