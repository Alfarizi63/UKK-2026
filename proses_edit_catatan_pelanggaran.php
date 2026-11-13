
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
    kembali('Permintaan tidak valid. Silakan coba lagi.');
}

$id = (int)($_POST['id'] ?? 0);
$pelanggaran_id = (int)($_POST['pelanggaran_id'] ?? 0);
$tanggal = trim($_POST['tanggal'] ?? '');
$keterangan = trim($_POST['keterangan'] ?? '');

if ($id <= 0 || $pelanggaran_id <= 0 || $tanggal === '' || $keterangan === '') {
    kembali('Semua data wajib diisi.');
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

// Pastikan catatan milik pengguna dan masih belum ditindaklanjuti.
$sql = "
    SELECT id, guru_id, status
    FROM t_pelanggaran_siswa
    WHERE id = ?
";

if ($role !== 'admin') {
    $sql .= " AND guru_id = ?";
}

$sql .= " LIMIT 1";

$stmt = mysqli_prepare($koneksi, $sql);

if ($role === 'admin') {
    mysqli_stmt_bind_param($stmt, 'i', $id);
} else {
    mysqli_stmt_bind_param($stmt, 'ii', $id, $guru_id);
}

mysqli_stmt_execute($stmt);
$catatan = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$catatan) {
    kembali('Catatan tidak ditemukan atau bukan milik akun kamu.');
}

if (strtolower(trim($catatan['status'] ?? '')) !== 'belum ditindaklanjuti') {
    kembali('Catatan sudah ditindaklanjuti dan tidak bisa diedit.');
}

// Ambil data master pelanggaran.
$stmt = mysqli_prepare($koneksi, "
    SELECT id, nama, poin, pelanggaran_kategori_id
    FROM t_pelanggaran
    WHERE id = ? AND status_aktif = 1
    LIMIT 1
");

mysqli_stmt_bind_param($stmt, 'i', $pelanggaran_id);
mysqli_stmt_execute($stmt);
$pelanggaran = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$pelanggaran) {
    kembali('Jenis pelanggaran tidak ditemukan atau sudah tidak aktif.');
}

// Validasi tanggal.
$tanggal_obj = DateTime::createFromFormat('!Y-m-d', $tanggal);

if (!$tanggal_obj || $tanggal_obj->format('Y-m-d') !== $tanggal) {
    kembali('Format tanggal tidak valid.');
}

$nama_pelanggaran = $pelanggaran['nama'];
$poin = (int)$pelanggaran['poin'];
$kategori_id = (int)$pelanggaran['pelanggaran_kategori_id'];

$stmt = mysqli_prepare($koneksi, "
    UPDATE t_pelanggaran_siswa
    SET
        pelanggaran_id = ?,
        nama_pelanggaran = ?,
        pelanggaran_kategori_id = ?,
        poin = ?,
        tanggal = ?,
        keterangan = ?
    WHERE id = ?
      AND status = 'Belum ditindaklanjuti'
");

if (!$stmt) {
    kembali('Gagal menyiapkan perubahan: ' . mysqli_error($koneksi));
}

mysqli_stmt_bind_param(
    $stmt,
    'isiissi',
    $pelanggaran_id,
    $nama_pelanggaran,
    $kategori_id,
    $poin,
    $tanggal,
    $keterangan,
    $id
);

if (!mysqli_stmt_execute($stmt)) {
    $pesan = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    kembali('Gagal mengubah catatan: ' . $pesan);
}

$berubah = mysqli_stmt_affected_rows($stmt);
mysqli_stmt_close($stmt);

$_SESSION['success_catatan'] = $berubah >= 0
    ? 'Catatan pelanggaran berhasil diperbarui.'
    : 'Tidak ada perubahan data.';

header('Location: catatan_pelanggaran.php');
exit;
