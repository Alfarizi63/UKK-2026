
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/cek_session.php';
include 'config/koneksi.php';

$role = $_SESSION['role'] ?? '';
$email = $_SESSION['email'] ?? '';
$nama_login = $_SESSION['name'] ?? '';

function kembali($pesan)
{
    $_SESSION['error_catatan'] = $pesan;
    header('Location: catatan_pelanggaran.php');
    exit;
}

// Validasi role
if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    header('Location: dashboard.php');
    exit;
}

// Pastikan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: catatan_pelanggaran.php');
    exit;
}

// Ambil data form
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

// Validasi tanggal
$tanggal_obj = DateTime::createFromFormat('!Y-m-d', $tanggal);

if (!$tanggal_obj || $tanggal_obj->format('Y-m-d') !== $tanggal) {
    kembali('Format tanggal tidak valid.');
}

// Cek tahun ajaran aktif
$stmt = mysqli_prepare($koneksi, "
    SELECT id
    FROM t_tahun_ajaran
    WHERE id = ? AND status_aktif = 1
    LIMIT 1
");

mysqli_stmt_bind_param($stmt, 'i', $tahun_ajaran_id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data_tahun = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$data_tahun) {
    kembali('Tahun ajaran tidak aktif atau tidak ditemukan.');
}

// Identitas guru
if ($role === 'admin') {

    $guru_id = (int)($_POST['guru_id'] ?? 0);

} else {

    $data_guru = null;

    if ($email !== '') {
        $stmt = mysqli_prepare($koneksi, "
            SELECT id
            FROM t_guru
            WHERE email = ?
            LIMIT 1
        ");

        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);

        $hasil = mysqli_stmt_get_result($stmt);
        $data_guru = mysqli_fetch_assoc($hasil);

        mysqli_stmt_close($stmt);
    }

    if (!$data_guru && $nama_login !== '') {
        $stmt = mysqli_prepare($koneksi, "
            SELECT id
            FROM t_guru
            WHERE nama = ?
            LIMIT 1
        ");

        mysqli_stmt_bind_param($stmt, 's', $nama_login);
        mysqli_stmt_execute($stmt);

        $hasil = mysqli_stmt_get_result($stmt);
        $data_guru = mysqli_fetch_assoc($hasil);

        mysqli_stmt_close($stmt);
    }

    $guru_id = (int)($data_guru['id'] ?? 0);
}

if ($guru_id <= 0) {
    kembali('Data guru pencatat tidak ditemukan.');
}

// Validasi guru
$stmt = mysqli_prepare($koneksi, "
    SELECT id, nama
    FROM t_guru
    WHERE id = ?
    LIMIT 1
");

mysqli_stmt_bind_param($stmt, 'i', $guru_id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data_guru = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$data_guru) {
    kembali('Data guru tidak ditemukan.');
}

$nama_guru = $data_guru['nama'];

// Ambil data siswa dan kelas
$stmt = mysqli_prepare($koneksi, "
    SELECT
        s.id,
        s.nama AS nama_siswa,
        ks.kelas_id,
        k.nama AS nama_kelas
    FROM t_kelas_siswa ks
    INNER JOIN t_siswa s
        ON s.id = ks.siswa_id
    INNER JOIN t_kelas k
        ON k.id = ks.kelas_id
    WHERE s.id = ?
        AND ks.tahun_ajaran_id = ?
        AND s.status_aktif = 1
    LIMIT 1
");

mysqli_stmt_bind_param(
    $stmt,
    'ii',
    $siswa_id,
    $tahun_ajaran_id
);

mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data_siswa = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$data_siswa) {
    kembali('Data siswa tidak ditemukan pada tahun ajaran aktif.');
}

$nama_siswa = $data_siswa['nama_siswa'];
$kelas_id = (int)$data_siswa['kelas_id'];
$nama_kelas = $data_siswa['nama_kelas'];

// Validasi wali kelas
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

    mysqli_stmt_bind_param(
        $stmt,
        'iii',
        $guru_id,
        $kelas_id,
        $tahun_ajaran_id
    );

    mysqli_stmt_execute($stmt);

    $hasil = mysqli_stmt_get_result($stmt);
    $data_wali = mysqli_fetch_assoc($hasil);

    mysqli_stmt_close($stmt);

    if (!$data_wali) {
        kembali('Anda tidak memiliki akses mencatat pelanggaran di kelas tersebut.');
    }
}

// Ambil poin langsung dari database
$stmt = mysqli_prepare($koneksi, "
    SELECT
        id,
        nama,
        poin,
        pelanggaran_kategori_id
    FROM t_pelanggaran
    WHERE id = ?
        AND status_aktif = 1
    LIMIT 1
");

mysqli_stmt_bind_param($stmt, 'i', $pelanggaran_id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data_pelanggaran = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$data_pelanggaran) {
    kembali('Jenis pelanggaran tidak ditemukan atau sudah tidak aktif.');
}

$nama_pelanggaran = $data_pelanggaran['nama'];
$poin = (int)$data_pelanggaran['poin'];
$pelanggaran_kategori_id = (int)$data_pelanggaran['pelanggaran_kategori_id'];

// Simpan catatan
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
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
        '',
        'Belum ditindaklanjuti'
    )
");

if (!$stmt) {
    kembali('Query penyimpanan gagal disiapkan.');
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

    $_SESSION['success_catatan'] =
        'Catatan pelanggaran berhasil disimpan.';

    header('Location: catatan_pelanggaran.php');
    exit;

} else {

    $pesan_error = mysqli_stmt_error($stmt);

    mysqli_stmt_close($stmt);

    kembali('Gagal menyimpan catatan: ' . $pesan_error);
}
