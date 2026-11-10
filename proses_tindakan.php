
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
$jenis_tindakan = trim($_POST['jenis_tindakan'] ?? '');
$tanggal_tindakan = trim($_POST['tanggal_tindakan'] ?? '');
$keterangan_tindakan = trim($_POST['keterangan_tindakan'] ?? '');

if ($id <= 0) {
    kembaliDenganError('ID catatan pelanggaran tidak valid.');
}

$daftar_tindakan = [
    'Teguran Lisan',
    'Teguran Tertulis',
    'Pembinaan BK',
    'Pemanggilan Orang Tua',
    'Rekomendasi Tindakan Lanjutan'
];

if (!in_array($jenis_tindakan, $daftar_tindakan, true)) {
    kembaliDenganError('Jenis tindakan tidak valid.');
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
    kembaliDenganError('Gagal menyiapkan pemeriksaan data.');
}

mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$catatan = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$catatan) {
    kembaliDenganError('Catatan pelanggaran tidak ditemukan.');
}

if (strtolower(trim($catatan['status'] ?? '')) === 'sudah ditindaklanjuti') {
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
    kembaliDenganError('Gagal menghitung akumulasi poin.');
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

// Tentukan batas minimal tindakan
if ($total_poin <= 20) {
    $batas = 0;
} elseif ($total_poin <= 40) {
    $batas = 1;
} elseif ($total_poin <= 60) {
    $batas = 2;
} elseif ($total_poin <= 80) {
    $batas = 3;
} else {
    $batas = 4;
}

$index_tindakan = array_search(
    $jenis_tindakan,
    $daftar_tindakan,
    true
);

// Validasi tindakan sesuai poin
if ($total_poin <= 0 || $index_tindakan === false || $index_tindakan < $batas) {
    kembaliDenganError(
        'Tindakan ditolak! Total poin siswa adalah ' .
        $total_poin .
        '. Pilih tindakan sesuai batas poin.'
    );
}

// Simpan tindakan
$stmt = mysqli_prepare(
    $koneksi,
    "UPDATE t_pelanggaran_siswa
     SET tindakan = ?,
         tanggal_tindakan = ?,
         keterangan_tindakan = ?,
         status = 'Sudah ditindaklanjuti'
     WHERE id = ?
       AND status = 'Belum ditindaklanjuti'"
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

if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    kembaliDenganSukses('Tindakan pelanggaran berhasil disimpan.');
}

mysqli_stmt_close($stmt);

kembaliDenganError(
    'Tindakan gagal disimpan. Periksa kembali status catatan pelanggaran.'
);
?>
