
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Proses hapus data pelanggaran
if (isset($_GET['aksi']) && $_GET['aksi'] === 'hapus') {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$id) {
        die("ID pelanggaran tidak valid.");
    }

    $stmt = mysqli_prepare($koneksi, "DELETE FROM t_pelanggaran WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['pesan'] = "Data pelanggaran berhasil dihapus.";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus data pelanggaran.";
    }

    header("Location: catatan_pelanggaran.php");
    exit;
}

// Form tambah harus menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: catatan_pelanggaran.php");
    exit;
}

// Ambil data sesuai nama input formulir
$kategori_id = filter_input(
    INPUT_POST,
    'pelanggaran_kategori_id',
    FILTER_VALIDATE_INT
);

$kode = trim($_POST['kode'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$poin = filter_input(INPUT_POST, 'poin', FILTER_VALIDATE_INT);
$deskripsi = trim($_POST['deskripsi'] ?? '');
$status_aktif = filter_input(
    INPUT_POST,
    'status_aktif',
    FILTER_VALIDATE_INT
);

// Validasi data
if (
    !$kategori_id ||
    $kode === '' ||
    $nama === '' ||
    $poin === false ||
    $poin === null ||
    $poin < 0 ||
    $deskripsi === '' ||
    !in_array($status_aktif, [0, 1], true)
) {
    $_SESSION['pesan'] = "Data belum lengkap atau tidak valid.";
    header("Location: catatan_pelanggaran.php");
    exit;
}

// Simpan ke tabel t_pelanggaran
$sql = "INSERT INTO t_pelanggaran
        (pelanggaran_kategori_id, kode, nama, poin, deskripsi, status_aktif)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param(
    $stmt,
    "issisi",
    $kategori_id,
    $kode,
    $nama,
    $poin,
    $deskripsi,
    $status_aktif
);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['pesan'] = "Data jenis pelanggaran berhasil ditambahkan.";
    header("Location: catatan_pelanggaran.php");
    exit;
}

if (mysqli_stmt_errno($stmt) == 1062) {
    $_SESSION['pesan'] = "Kode pelanggaran sudah digunakan.";
    header("Location: catatan_pelanggaran.php");
    exit;
}

die("Gagal menyimpan data: " . mysqli_stmt_error($stmt));
?>
