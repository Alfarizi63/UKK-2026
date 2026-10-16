
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: kelola_jenis_pelanggaran.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nama = trim($_POST['nama'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');

if (!$id || $nama === '') {
    die("ID dan nama kategori wajib diisi.");
}

$stmt = mysqli_prepare(
    $koneksi,
    "UPDATE t_pelanggaran_kategori
     SET nama = ?, deskripsi = ?
     WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "ssi", $nama, $deskripsi, $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: kelola_jenis_pelanggaran.php");
    exit;
}

die("Gagal memperbarui kategori: " . mysqli_stmt_error($stmt));
