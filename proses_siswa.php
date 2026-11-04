
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: kelola_siswa.php");
    exit;
}

$nis = trim($_POST['nis'] ?? '');
$nisn = trim($_POST['nisn'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
$tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$status_aktif = (int) ($_POST['status_aktif'] ?? 1);

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

// Validasi NIS: angka, maksimal 9 digit
if (!preg_match('/^[0-9]{1,9}$/', $nis)) {
    echo "<script>alert('NIS harus berupa angka dan maksimal 9 digit!'); history.back();</script>";
    exit;
}

// Validasi NISN: harus tepat 10 digit
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
    echo "<script>alert('Format tanggal lahir tidak valid!'); history.back();</script>";
    exit;
}

// Validasi status
if (!in_array($status_aktif, [0, 1], true)) {
    echo "<script>alert('Status siswa tidak valid!'); history.back();</script>";
    exit;
}

// Cek NIS duplikat
$stmt = mysqli_prepare($koneksi, "SELECT id FROM t_siswa WHERE nis = ?");
mysqli_stmt_bind_param($stmt, "s", $nis);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    echo "<script>alert('NIS sudah digunakan siswa lain!'); history.back();</script>";
    exit;
}
mysqli_stmt_close($stmt);

// Cek NISN duplikat
$stmt = mysqli_prepare($koneksi, "SELECT id FROM t_siswa WHERE nisn = ?");
mysqli_stmt_bind_param($stmt, "s", $nisn);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    echo "<script>alert('NISN sudah digunakan siswa lain!'); history.back();</script>";
    exit;
}
mysqli_stmt_close($stmt);

// Simpan data
$sql = "INSERT INTO t_siswa
        (nis, nisn, nama, jenis_kelamin, tanggal_lahir, alamat, status_aktif)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die("Query gagal disiapkan: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssssi",
    $nis,
    $nisn,
    $nama,
    $jenis_kelamin,
    $tanggal_lahir,
    $alamat,
    $status_aktif
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: kelola_siswa.php?pesan=berhasil");
    exit;
}

$error = mysqli_stmt_error($stmt);
mysqli_stmt_close($stmt);

echo "Gagal menambahkan siswa: " . htmlspecialchars($error);
echo "<br><a href='kelola_siswa.php'>Kembali</a>";
?>
