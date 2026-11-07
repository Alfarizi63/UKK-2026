
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kelola_siswa.php');
    exit;
}

$nis             = trim($_POST['nis'] ?? '');
$nisn            = trim($_POST['nisn'] ?? '');
$nama            = trim($_POST['nama'] ?? '');
$jenis_kelamin   = trim($_POST['jenis_kelamin'] ?? '');
$tanggal_lahir   = trim($_POST['tanggal_lahir'] ?? '');
$alamat          = trim($_POST['alamat'] ?? '');
$status_aktif    = $_POST['status_aktif'] ?? '';
$kelas_id        = (int)($_POST['kelas_id'] ?? 0);
$tahun_ajaran_id = (int)($_POST['tahun_ajaran_id'] ?? 0);

// Validasi input wajib
if (
    $nis === '' || $nisn === '' || $nama === '' ||
    $jenis_kelamin === '' || $tanggal_lahir === '' ||
    $alamat === '' || $kelas_id <= 0 ||
    $tahun_ajaran_id <= 0 ||
    !in_array($status_aktif, ['0', '1'], true)
) {
    $_SESSION['pesan'] = 'Semua data wajib diisi.';
    header('Location: kelola_siswa.php');
    exit;
}

// NIS maksimal 15 digit
if (!preg_match('/^[0-9]{1,15}$/', $nis)) {
    $_SESSION['pesan'] =
        'NIS harus berupa angka dan maksimal 15 digit.';
    header('Location: kelola_siswa.php');
    exit;
}

// NISN tepat 10 digit
if (!preg_match('/^[0-9]{10}$/', $nisn)) {
    $_SESSION['pesan'] =
        'NISN harus tepat 10 digit angka.';
    header('Location: kelola_siswa.php');
    exit;
}

// Validasi jenis kelamin
if (!in_array($jenis_kelamin, ['L', 'P'], true)) {
    $_SESSION['pesan'] = 'Jenis kelamin tidak valid.';
    header('Location: kelola_siswa.php');
    exit;
}

// Validasi tanggal lahir
$date = DateTime::createFromFormat('Y-m-d', $tanggal_lahir);

if (!$date || $date->format('Y-m-d') !== $tanggal_lahir) {
    $_SESSION['pesan'] = 'Tanggal lahir tidak valid.';
    header('Location: kelola_siswa.php');
    exit;
}

// Pastikan kelas aktif
$stmt = $koneksi->prepare(
    "SELECT id FROM t_kelas
     WHERE id = ? AND status_aktif = 1"
);

$stmt->bind_param("i", $kelas_id);
$stmt->execute();

$kelasValid = $stmt->get_result()->num_rows > 0;
$stmt->close();

// Pastikan tahun ajaran aktif
$stmt = $koneksi->prepare(
    "SELECT id FROM t_tahun_ajaran
     WHERE id = ? AND status_aktif = 1"
);

$stmt->bind_param("i", $tahun_ajaran_id);
$stmt->execute();

$tahunValid = $stmt->get_result()->num_rows > 0;
$stmt->close();

if (!$kelasValid || !$tahunValid) {
    $_SESSION['pesan'] =
        'Kelas atau tahun ajaran tidak aktif/tidak ditemukan.';
    header('Location: kelola_siswa.php');
    exit;
}

// Cek NIS dan NISN duplikat
$stmt = $koneksi->prepare(
    "SELECT id FROM t_siswa
     WHERE nis = ? OR nisn = ?
     LIMIT 1"
);

$stmt->bind_param("ss", $nis, $nisn);
$stmt->execute();

$duplikat = $stmt->get_result()->num_rows > 0;
$stmt->close();

if ($duplikat) {
    $_SESSION['pesan'] =
        'NIS atau NISN sudah digunakan siswa lain.';
    header('Location: kelola_siswa.php');
    exit;
}

// Simpan menggunakan transaksi
$koneksi->begin_transaction();

try {

    // Simpan identitas siswa
    $stmt = $koneksi->prepare(
        "INSERT INTO t_siswa
        (nis, nisn, nama, jenis_kelamin, tanggal_lahir,
         alamat, status_aktif)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    $status = (int)$status_aktif;

    $stmt->bind_param(
        "ssssssi",
        $nis,
        $nisn,
        $nama,
        $jenis_kelamin,
        $tanggal_lahir,
        $alamat,
        $status
    );

    $stmt->execute();

    $siswa_id = $koneksi->insert_id;

    $stmt->close();

    $tanggal_mulai = date('Y-m-d');

    // Ambil tanggal selesai tahun ajaran
    $stmt = $koneksi->prepare(
        "SELECT tanggal_selesai
         FROM t_tahun_ajaran
         WHERE id = ? AND status_aktif = 1"
    );

    $stmt->bind_param("i", $tahun_ajaran_id);
    $stmt->execute();

    $dataTahun = $stmt->get_result()->fetch_assoc();

    $stmt->close();

    if (!$dataTahun || empty($dataTahun['tanggal_selesai'])) {
        throw new Exception(
            'Tanggal selesai pada tahun ajaran belum diisi.'
        );
    }

    $tanggal_selesai = $dataTahun['tanggal_selesai'];

    // Simpan penempatan siswa
    $stmt = $koneksi->prepare(
        "INSERT INTO t_kelas_siswa
        (siswa_id, tahun_ajaran_id, kelas_id,
         tanggal_mulai, tanggal_selesai, status_aktif)
        VALUES (?, ?, ?, ?, ?, 1)"
    );

    $stmt->bind_param(
        "iiiss",
        $siswa_id,
        $tahun_ajaran_id,
        $kelas_id,
        $tanggal_mulai,
        $tanggal_selesai
    );

    $stmt->execute();
    $stmt->close();

    $koneksi->commit();

    $_SESSION['pesan'] =
        'Data siswa berhasil ditambahkan beserta kelas dan tahun ajarannya.';

} catch (Throwable $e) {

    $koneksi->rollback();

    $_SESSION['pesan'] =
        'Gagal menyimpan data siswa: ' . $e->getMessage();
}

header('Location: kelola_siswa.php');
exit;
