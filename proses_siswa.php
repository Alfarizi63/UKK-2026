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

if (
    $nis === '' ||
    $nisn === '' ||
    $nama === '' ||
    $jenis_kelamin === '' ||
    $tanggal_lahir === '' ||
    $alamat === ''
) {
    echo "<script>
        alert('Semua data wajib diisi!');
        history.back();
    </script>";
    exit;
}

if (!in_array($jenis_kelamin, ['L', 'P', 'Laki-laki', 'Perempuan'], true)) {
    echo "<script>
        alert('Jenis kelamin tidak valid!');
        history.back();
    </script>";
    exit;
}

if (!in_array($status_aktif, [0, 1], true)) {
    $status_aktif = 1;
}

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