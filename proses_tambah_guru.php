
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['simpan'])) {
    header('Location: tambah_guru.php');
    exit;
}

$nama = trim($_POST['nama'] ?? '');
$nip = trim($_POST['nip'] ?? '');
$jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
$no_hp = trim($_POST['no_hp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');

if ($nama === '' || $nip === '' ||
    !in_array($jenis_kelamin, ['Laki-laki', 'Perempuan'], true)) {
    header('Location: tambah_guru.php?error=' . urlencode('Data wajib belum lengkap.'));
    exit;
}

$sql = "INSERT INTO t_guru
        (nama, nip, jenis_kelamin, no_hp, alamat)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $koneksi->prepare($sql);

if (!$stmt) {
    exit('Gagal menyiapkan query: ' . htmlspecialchars($koneksi->error));
}

$stmt->bind_param(
    'sssss',
    $nama,
    $nip,
    $jenis_kelamin,
    $no_hp,
    $alamat
);

try {
    if ($stmt->execute()) {
        $stmt->close();
        header('Location: data_guru.php?status=berhasil');
        exit;
    }
} catch (mysqli_sql_exception $e) {
    $stmt->close();

    // Misalnya NIP duplikat jika kolom NIP memiliki UNIQUE.
    if ($e->getCode() === 1062) {
        header('Location: tambah_guru.php?error=' . urlencode('NIP sudah terdaftar.'));
        exit;
    }

    error_log($e->getMessage());
    header('Location: tambah_guru.php?error=' . urlencode('Gagal menyimpan data guru.'));
    exit;
}

$stmt->close();

header('Location: tambah_guru.php?error=' . urlencode('Gagal menyimpan data guru.'));
exit;
?>
