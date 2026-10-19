
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_GET['id']) || (int) $_GET['id'] <= 0) {
    header('Location: kelola_penempatan_siswa.php');
    exit;
}

$id = (int) $_GET['id'];

$sql = "DELETE FROM t_kelas_siswa WHERE id = $id";
$query = mysqli_query($koneksi, $sql);

if ($query) {
    header('Location: kelola_penempatan_siswa.php');
    exit;
}

die('Gagal menghapus penempatan siswa: ' . mysqli_error($koneksi));
?>
