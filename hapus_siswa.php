
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kelola_siswa.php");
    exit;
}

$id = (int) $_GET['id'];

mysqli_begin_transaction($koneksi);

try {

    // Hapus relasi siswa dengan kelas terlebih dahulu
    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_kelas_siswa WHERE siswa_id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Hapus data siswa
    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_siswa WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) < 1) {
        throw new Exception("Data siswa tidak ditemukan.");
    }

    mysqli_stmt_close($stmt);

    mysqli_commit($koneksi);

    header("Location: kelola_siswa.php?pesan=hapus_berhasil");
    exit;

} catch (Throwable $e) {

    mysqli_rollback($koneksi);

    die("Gagal menghapus siswa: " . $e->getMessage());
}
