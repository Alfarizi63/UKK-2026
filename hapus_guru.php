```php
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hanya admin yang boleh menghapus guru
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kelola_guru.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['pesan'] = 'ID guru tidak valid!';
    header('Location: kelola_guru.php');
    exit;
}

try {
    $stmt = $koneksi->prepare(
        "DELETE FROM t_guru WHERE id = ?"
    );

    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['pesan'] = 'Data guru berhasil dihapus!';
    } else {
        $_SESSION['pesan'] = 'Data guru tidak ditemukan!';
    }

    $stmt->close();

} catch (mysqli_sql_exception $e) {
    error_log('Gagal menghapus guru: ' . $e->getMessage());
    $_SESSION['pesan'] =
        'Data guru gagal dihapus. Periksa relasi database terlebih dahulu.';
}

header('Location: kelola_guru.php');
exit;
?>
```