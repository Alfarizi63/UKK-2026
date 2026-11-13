
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/cek_session.php';
include 'config/koneksi.php';

$role = $_SESSION['role'] ?? '';

function kembali($pesan)
{
    $_SESSION['error_catatan'] = $pesan;
    header('Location: catatan_pelanggaran.php');
    exit;
}

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: catatan_pelanggaran.php');
    exit;
}

// ========================================
// AMBIL INPUT
// ========================================

$tahun_ajaran_id = (int)($_POST['tahun_ajaran_id'] ?? 0);
$siswa_id = (int)($_POST['siswa_id'] ?? 0);
$pelanggaran_id = (int)($_POST['pelanggaran_id'] ?? 0);
$tanggal = trim($_POST['tanggal'] ?? '');
$keterangan = trim($_POST['keterangan'] ?? '');

if (
    $tahun_ajaran_id <= 0 ||
    $siswa_id <= 0 ||
    $pelanggaran_id <= 0 ||
    $tanggal === '' ||
    $keterangan === ''
) {
    kembali('Data catatan pelanggaran belum lengkap.');
}

$tanggal_obj = DateTime::createFromFormat('!Y-m-d', $tanggal);

if (!$tanggal_obj || $tanggal_obj->format('Y-m-d') !== $tanggal) {
    kembali('Format tanggal tidak valid.');
}

// ========================================
// VALIDASI TAHUN AJARAN
// ========================================

$stmt = mysqli_prepare($koneksi, "
    SELECT id
    FROM t_tahun_ajaran
    WHERE id = ? AND status_aktif = 1
    LIMIT 1
");

if (!$stmt) {
    kembali('Gagal memeriksa tahun ajaran.');
}

mysqli_stmt_bind_param($stmt, 'i', $tahun_ajaran_id);
mysqli_stmt_execute($stmt);

$data_tahun = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

mysqli_stmt_close($stmt);

if (!$data_tahun) {
    kembali('Tahun ajaran tidak aktif atau tidak ditemukan.');
}

// ========================================
// AMBIL IDENTITAS GURU
// ========================================

if ($role === 'admin') {

    // Admin memilih guru pencatat.
    $guru_id = (int)($_POST['guru_id'] ?? 0);

} else {

    // Guru dan wali kelas otomatis menggunakan akun login.
    $user_id = (int)($_SESSION['id'] ?? 0);

    if ($user_id <= 0) {
        kembali('Identitas akun login tidak ditemukan.');
    }

    $stmt = mysqli_prepare($koneksi, "
        SELECT id
        FROM t_guru
        WHERE user_id = ?
          AND status_aktif = 1
        LIMIT 1
    ");

    if (!$stmt) {
        kembali('Gagal mengambil identitas guru.');
    }

    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);

    $data_guru = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    mysqli_stmt_close($stmt);

    $guru_id = (int)($data_guru['id'] ?? 0);
}

if ($guru_id <= 0) {
    kembali('Data guru pencatat tidak ditemukan. Periksa hubungan user_id pada tabel t_guru.');
}

// Ambil nama guru berdasarkan ID yang sudah ditemukan.
$stmt = mysqli_prepare($koneksi, "
    SELECT id, nama
    FROM t_guru
    WHERE id = ?
      AND status_aktif = 1
    LIMIT 1
");

if (!$stmt) {
    kembali('Gagal memeriksa data guru.');
}

mysqli_stmt_bind_param($stmt, 'i', $guru_id);
mysqli_stmt_execute($stmt);

$data_guru = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

mysqli_stmt_close($stmt);

if (!$data_guru) {
    kembali('Guru tidak ditemukan atau tidak aktif.');
}

$guru_id = (int)$data_guru['id'];
$nama_guru = $data_guru['nama'];

// ========================================
// AMBIL DATA SISWA DAN KELAS
// ========================================

$stmt = mysqli_prepare($koneksi, "
    SELECT
        s.id,
        s.nama AS nama_siswa,
        ks.kelas_id,
        k.nama AS nama_kelas
    FROM t_kelas_siswa ks
    INNER JOIN t_siswa s ON s.id = ks.siswa_id
    INNER JOIN t_kelas k ON k.id = ks.kelas_id
    WHERE s.id = ?
      AND ks.tahun_ajaran_id = ?
      AND s.status_aktif = 1
    LIMIT 1
");

if (!$stmt) {
    kembali('Gagal mengambil data siswa.');
}

mysqli_stmt_bind_param($stmt, 'ii', $siswa_id, $tahun_ajaran_id);
mysqli_stmt_execute($stmt);

$data_siswa = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

mysqli_stmt_close($stmt);

if (!$data_siswa) {
    kembali('Data siswa tidak ditemukan pada tahun ajaran tersebut.');
}

$nama_siswa = $data_siswa['nama_siswa'];
$kelas_id = (int)$data_siswa['kelas_id'];
$nama_kelas = $data_siswa['nama_kelas'];

// ========================================
// VALIDASI WALI KELAS
// ========================================

if ($role === 'wali_kelas') {

    $stmt = mysqli_prepare($koneksi, "
        SELECT id
        FROM t_wali_kelas
        WHERE guru_id = ?
          AND kelas_id = ?
          AND tahun_ajaran_id = ?
          AND status_aktif = 1
        LIMIT 1
    ");

    if (!$stmt) {
        kembali('Gagal memeriksa penugasan wali kelas.');
    }

    mysqli_stmt_bind_param(
        $stmt,
        'iii',
        $guru_id,
        $kelas_id,
        $tahun_ajaran_id
    );

    mysqli_stmt_execute($stmt);

    $data_wali = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    mysqli_stmt_close($stmt);

    if (!$data_wali) {
        kembali('Anda tidak memiliki akses mencatat pelanggaran di kelas tersebut.');
    }
}

// ========================================
// AMBIL DATA JENIS PELANGGARAN
// ========================================

$stmt = mysqli_prepare($koneksi, "
    SELECT id, nama, poin, pelanggaran_kategori_id
    FROM t_pelanggaran
    WHERE id = ?
      AND status_aktif = 1
    LIMIT 1
");

if (!$stmt) {
    kembali('Gagal mengambil jenis pelanggaran.');
}

mysqli_stmt_bind_param($stmt, 'i', $pelanggaran_id);
mysqli_stmt_execute($stmt);

$data_pelanggaran = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

mysqli_stmt_close($stmt);

if (!$data_pelanggaran) {
    kembali('Jenis pelanggaran tidak ditemukan atau tidak aktif.');
}

$nama_pelanggaran = $data_pelanggaran['nama'];
$poin = (int)$data_pelanggaran['poin'];
$pelanggaran_kategori_id = (int)$data_pelanggaran['pelanggaran_kategori_id'];

// ========================================
// SIMPAN CATATAN PELANGGARAN
// ========================================

$stmt = mysqli_prepare($koneksi, "
    INSERT INTO t_pelanggaran_siswa (
        tahun_ajaran_id,
        siswa_id,
        nama_siswa,
        kelas_id,
        nama_kelas,
        pelanggaran_id,
        nama_pelanggaran,
        pelanggaran_kategori_id,
        guru_id,
        nama_guru,
        tanggal,
        keterangan,
        poin,
        tindakan,
        status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '', 'Belum ditindaklanjuti')
");

if (!$stmt) {
    kembali('Gagal menyiapkan penyimpanan: ' . mysqli_error($koneksi));
}

mysqli_stmt_bind_param(
    $stmt,
    'iisisiisiissi',
    $tahun_ajaran_id,
    $siswa_id,
    $nama_siswa,
    $kelas_id,
    $nama_kelas,
    $pelanggaran_id,
    $nama_pelanggaran,
    $pelanggaran_kategori_id,
    $guru_id,
    $nama_guru,
    $tanggal,
    $keterangan,
    $poin
);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    $_SESSION['success_catatan'] = 'Catatan pelanggaran berhasil disimpan.';

    header('Location: catatan_pelanggaran.php');
    exit;

}

$pesan_error = mysqli_stmt_error($stmt);

mysqli_stmt_close($stmt);

kembali('Gagal menyimpan catatan: ' . $pesan_error);
