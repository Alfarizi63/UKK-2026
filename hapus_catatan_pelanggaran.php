
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

function kembali($pesan) {
    $_SESSION['error_catatan'] = $pesan;
    header('Location: catatan_pelanggaran.php');
    exit;
}

$role = $_SESSION['role'] ?? '';
$user_id = (int)($_SESSION['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: catatan_pelanggaran.php');
    exit;
}

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    kembali('Akses tidak diizinkan.');
}

if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    kembali('Permintaan tidak valid.');
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    kembali('ID catatan tidak valid.');
}

$guru_id = 0;

if ($role !== 'admin') {
    $stmt = mysqli_prepare($koneksi, "
        SELECT id
        FROM t_guru
        WHERE user_id = ? AND status_aktif = 1
        LIMIT 1
    ");

    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $guru = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if (!$guru) {
        kembali('Data guru tidak ditemukan.');
    }

    $guru_id = (int)$guru['id'];
}

// Hanya catatan milik sendiri untuk guru dan wali kelas.
$sql = "
    DELETE FROM t_pelanggaran_siswa
    WHERE id = ?
      AND status = 'Belum ditindaklanjuti'
";

if ($role !== 'admin') {
    $sql .= " AND guru_id = ?";
}

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    kembali('Gagal menyiapkan penghapusan: ' . mysqli_error($koneksi));
}

if ($role === 'admin') {
    mysqli_stmt_bind_param($stmt, 'i', $id);
} else {
    mysqli_stmt_bind_param($stmt, 'ii', $id, $guru_id);
}

if (!mysqli_stmt_execute($stmt)) {
    $pesan = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    kembali('Gagal menghapus catatan: ' . $pesan);
}

$jumlah = mysqli_stmt_affected_rows($stmt);
mysqli_stmt_close($stmt);

if ($jumlah > 0) {
    $_SESSION['success_catatan'] = 'Catatan pelanggaran berhasil dihapus.';
} else {
    $_SESSION['error_catatan'] = 'Catatan tidak ditemukan, bukan milik kamu, atau sudah ditindaklanjuti.';
}

header('Location: catatan_pelanggaran.php');
exit;
