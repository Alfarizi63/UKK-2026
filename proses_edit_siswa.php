
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['update'])) {
    header("Location: kelola_siswa.php");
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$nis = trim($_POST['nis'] ?? '');
$nisn = trim($_POST['nisn'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
$tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$status_aktif = (int) ($_POST['status_aktif'] ?? -1);

// Validasi ID
if ($id <= 0) {
    die("ID siswa tidak valid!");
}

// Pastikan siswa tersedia
$stmt = mysqli_prepare($koneksi, "SELECT id FROM t_siswa WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {
    mysqli_stmt_close($stmt);
    die("Data siswa tidak ditemukan!");
}
mysqli_stmt_close($stmt);

// Validasi data kosong
if (
    $nis === '' ||
    $nisn === '' ||
    $nama === '' ||
    $jenis_kelamin === '' ||
    $tanggal_lahir === '' ||
    $alamat === ''
) {
    echo "<script>alert('Semua data wajib diisi!'); history.back();</script>";
    exit;
}

// Validasi NIS
if (!preg_match('/^[0-9]{1,9}$/', $nis)) {
    echo "<script>alert('NIS harus berupa angka dan maksimal 9 digit!'); history.back();</script>";
    exit;
}

// Validasi NISN
if (!preg_match('/^[0-9]{10}$/', $nisn)) {
    echo "<script>alert('NISN harus tepat 10 digit!'); history.back();</script>";
    exit;
}

// Validasi jenis kelamin
if (!in_array($jenis_kelamin, ['L', 'P'], true)) {
    echo "<script>alert('Jenis kelamin tidak valid!'); history.back();</script>";
    exit;
}

// Validasi tanggal
$tanggal = DateTime::createFromFormat('Y-m-d', $tanggal_lahir);

if (!$tanggal || $tanggal->format('Y-m-d') !== $tanggal_lahir) {
    echo "<script>alert('Tanggal lahir tidak valid!'); history.back();</script>";
    exit;
}

// Validasi status
if (!in_array($status_aktif, [0, 1], true)) {
    echo "<script>alert('Status siswa tidak valid!'); history.back();</script>";
    exit;
}

// Cek NIS duplikat, kecuali ID siswa sendiri
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_siswa WHERE nis = ? AND id != ?"
);
mysqli_stmt_bind_param($stmt, "si", $nis, $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    echo "<script>alert('NIS sudah digunakan siswa lain!'); history.back();</script>";
    exit;
}
mysqli_stmt_close($stmt);

// Cek NISN duplikat, kecuali ID siswa sendiri
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_siswa WHERE nisn = ? AND id != ?"
);
mysqli_stmt_bind_param($stmt, "si", $nisn, $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    echo "<script>alert('NISN sudah digunakan siswa lain!'); history.back();</script>";
    exit;
}
mysqli_stmt_close($stmt);

// Update data
$sql = "UPDATE t_siswa SET
        nis = ?,
        nisn = ?,
        nama = ?,
        jenis_kelamin = ?,
        tanggal_lahir = ?,
        alamat = ?,
        status_aktif = ?,
        updated_at = NOW()
        WHERE id = ?";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die("Query gagal disiapkan: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssssii",
    $nis,
    $nisn,
    $nama,
    $jenis_kelamin,
    $tanggal_lahir,
    $alamat,
    $status_aktif,
    $id
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: kelola_siswa.php?pesan=update");
    exit;
}

$error = mysqli_stmt_error($stmt);
mysqli_stmt_close($stmt);

echo "Gagal mengupdate data: " . htmlspecialchars($error);
echo "<br><a href='kelola_siswa.php'>Kembali</a>";
?>
