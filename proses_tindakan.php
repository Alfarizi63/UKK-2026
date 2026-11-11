
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function kembaliDenganError($pesan)
{
    $_SESSION['error_tindakan'] = $pesan;
    header('Location: tindakan.php');
    exit;
}

function kembaliDenganSukses($pesan)
{
    $_SESSION['success_tindakan'] = $pesan;
    header('Location: tindakan.php');
    exit;
}

// Hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tindakan.php');
    exit;
}

// Ambil input
$id = (int)($_POST['id'] ?? 0);
$tanggal_tindakan = trim($_POST['tanggal_tindakan'] ?? '');
$keterangan_tindakan = trim($_POST['keterangan_tindakan'] ?? '');

if ($id <= 0) {
    kembaliDenganError('ID catatan pelanggaran tidak valid.');
}

if ($keterangan_tindakan === '') {
    kembaliDenganError('Keterangan tindakan wajib diisi.');
}

// Validasi tanggal
$tanggal = DateTime::createFromFormat('!Y-m-d', $tanggal_tindakan);

if (!$tanggal || $tanggal->format('Y-m-d') !== $tanggal_tindakan) {
    kembaliDenganError('Tanggal tindakan tidak valid.');
}

if ($tanggal_tindakan > date('Y-m-d')) {
    kembaliDenganError('Tanggal tindakan tidak boleh melebihi hari ini.');
}

// Ambil data catatan
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id, siswa_id, tahun_ajaran_id, status
     FROM t_pelanggaran_siswa
     WHERE id = ?
     LIMIT 1"
);

if (!$stmt) {
    kembaliDenganError('Gagal memeriksa data pelanggaran.');
}

mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$catatan = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$catatan) {
    kembaliDenganError('Catatan pelanggaran tidak ditemukan.');
}

$status = strtolower(trim($catatan['status'] ?? ''));

if ($status === 'sudah ditindaklanjuti') {
    kembaliDenganError('Catatan ini sudah ditindaklanjuti.');
}

$siswa_id = (int)$catatan['siswa_id'];
$tahun_ajaran_id = (int)$catatan['tahun_ajaran_id'];

// Hitung total poin siswa pada tahun ajaran yang sama
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT COALESCE(SUM(poin), 0) AS total_poin
     FROM t_pelanggaran_siswa
     WHERE siswa_id = ?
       AND tahun_ajaran_id = ?"
);

if (!$stmt) {
    kembaliDenganError('Gagal menghitung total poin.');
}

mysqli_stmt_bind_param(
    $stmt,
    'ii',
    $siswa_id,
    $tahun_ajaran_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data_poin = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

$total_poin = (int)($data_poin['total_poin'] ?? 0);

// Tentukan tindakan otomatis
if ($total_poin <= 0) {

    kembaliDenganError('Total poin siswa belum tersedia.');

} elseif ($total_poin <= 20) {

    $jenis_tindakan = 'Teguran Lisan';

} elseif ($total_poin <= 40) {

    $jenis_tindakan = 'Teguran Tertulis';

} elseif ($total_poin <= 60) {

    $jenis_tindakan = 'Pembinaan BK';

} elseif ($total_poin <= 80) {

    $jenis_tindakan = 'Pemanggilan Orang Tua';

} else {

    $jenis_tindakan = 'Rekomendasi Tindakan Lanjutan';
}

// Simpan tindakan dan selesaikan status
$stmt = mysqli_prepare(
    $koneksi,
    "UPDATE t_pelanggaran_siswa
     SET tindakan = ?,
         tanggal_tindakan = ?,
         keterangan_tindakan = ?,
         status = 'Sudah ditindaklanjuti'
     WHERE id = ?
       AND LOWER(TRIM(COALESCE(status, ''))) != 'sudah ditindaklanjuti'"
);

if (!$stmt) {
    kembaliDenganError('Gagal menyiapkan penyimpanan tindakan.');
}

mysqli_stmt_bind_param(
    $stmt,
    'sssi',
    $jenis_tindakan,
    $tanggal_tindakan,
    $keterangan_tindakan,
    $id
);

$berhasil = mysqli_stmt_execute($stmt);
$jumlah_data = mysqli_stmt_affected_rows($stmt);

mysqli_stmt_close($stmt);

if ($berhasil && $jumlah_data > 0) {

    kembaliDenganSukses(
        'Tindakan ' . $jenis_tindakan .
        ' berhasil diselesaikan dan masuk ke riwayat.'
    );

}

kembaliDenganError(
    'Tindakan gagal disimpan. Silakan periksa kembali data.'
);
?>
