
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hapus semua data sesi login
$_SESSION = [];

// Hapus sesi
session_destroy();

// Kembali ke halaman login
header("Location: login.php");
exit;
?>
