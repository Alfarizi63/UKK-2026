```php
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID wali kelas tidak valid.');
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($koneksi, "DELETE FROM t_wali_kelas WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: kelola_wali_kelas.php");
    exit;
} else {
    echo "Gagal menghapus data wali kelas: " . mysqli_error($koneksi);
}
?>
```