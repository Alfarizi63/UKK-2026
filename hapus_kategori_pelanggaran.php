```php
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kelola_kategori_pelanggaran.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['pesan'] = 'ID kategori tidak valid!';
    header('Location: kelola_kategori_pelanggaran.php');
    exit;
}

try {
    $stmt = $koneksi->prepare(
        "DELETE FROM t_pelanggaran_kategori WHERE id = ?"
    );

    $stmt->bind_param('i', $id);
    $stmt->execute();

    $_SESSION['pesan'] = $stmt->affected_rows > 0
        ? 'Kategori pelanggaran berhasil dihapus!'
        : 'Kategori tidak ditemukan.';

    $stmt->close();

} catch (mysqli_sql_exception $e) {
    error_log('Hapus kategori: ' . $e->getMessage());

    $_SESSION['pesan'] =
        'Kategori tidak dapat dihapus karena masih digunakan oleh data lain.';
}

header('Location: kelola_kategori_pelanggaran.php');
exit;
?>
```