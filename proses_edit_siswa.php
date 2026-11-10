
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Pastikan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['update'])) {
    header("Location: kelola_siswa.php");
    exit;
}

// Ambil data
$id = (int) ($_POST['id'] ?? 0);
$nis = trim($_POST['nis'] ?? '');
$nisn = trim($_POST['nisn'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
$tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$status_aktif = $_POST['status_aktif'] ?? '';

// Fungsi pesan error
function gagal($pesan) {
    echo "<script>
        alert(" . json_encode($pesan) . ");
        history.back();
    </script>";
    exit;
}

// Validasi ID
if ($id <= 0) {
    gagal("ID siswa tidak valid!");
}

// Cek siswa
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_siswa WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {
    mysqli_stmt_close($stmt);
    gagal("Data siswa tidak ditemukan!");
}

mysqli_stmt_close($stmt);

// Validasi data wajib
if (
    $nis === '' ||
    $nisn === '' ||
    $nama === '' ||
    $jenis_kelamin === '' ||
    $tanggal_lahir === '' ||
    $status_aktif === ''
) {
    gagal("Lengkapi semua data wajib!");
}

// Validasi NIS
if (!preg_match('/^[0-9]{1,15}$/', $nis)) {
    gagal("NIS harus berupa angka maksimal 15 digit!");
}

// Validasi NISN
if (!preg_match('/^[0-9]{10}$/', $nisn)) {
    gagal("NISN harus tepat 10 digit angka!");
}

// Validasi nama
if (mb_strlen($nama) > 100) {
    gagal("Nama siswa terlalu panjang!");
}

// Validasi jenis kelamin
if (!in_array($jenis_kelamin, ['L', 'P'], true)) {
    gagal("Jenis kelamin tidak valid!");
}

// Validasi tanggal lahir
$tanggal = DateTime::createFromFormat('Y-m-d', $tanggal_lahir);

if (!$tanggal || $tanggal->format('Y-m-d') !== $tanggal_lahir) {
    gagal("Tanggal lahir tidak valid!");
}

// Validasi status
if (!in_array((string) $status_aktif, ['0', '1'], true)) {
    gagal("Status siswa tidak valid!");
}

$status_aktif = (int) $status_aktif;

// Cek NIS duplikat
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_siswa WHERE nis = ? AND id != ?"
);

mysqli_stmt_bind_param($stmt, "si", $nis, $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    gagal("NIS sudah digunakan siswa lain!");
}

mysqli_stmt_close($stmt);

// Cek NISN duplikat
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_siswa WHERE nisn = ? AND id != ?"
);

mysqli_stmt_bind_param($stmt, "si", $nisn, $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    gagal("NISN sudah digunakan siswa lain!");
}

mysqli_stmt_close($stmt);

// Update data siswa
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
    gagal("Query gagal disiapkan: " . mysqli_error($koneksi));
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

gagal("Gagal mengupdate data: " . $error);
?>
