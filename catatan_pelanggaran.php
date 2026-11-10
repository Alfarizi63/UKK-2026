
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$role = $_SESSION['role'] ?? '';
$nama_login = $_SESSION['name'] ?? '';
$email_login = $_SESSION['email'] ?? '';

$error = '';
$success = $_SESSION['success_catatan'] ?? '';

unset($_SESSION['success_catatan']);

if (isset($_SESSION['error_catatan'])) {
    $error = $_SESSION['error_catatan'];
    unset($_SESSION['error_catatan']);
}

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    header('Location: dashboard.php');
    exit;
}

// MENU
if ($role === 'admin') {
    $menu = $menu_admin ?? [];
} elseif ($role === 'guru') {
    $menu = $menu_guru ?? [];
} else {
    $menu = $menu_wali_kelas ?? [];
}

// AMBIL EMAIL LOGIN
if ($email_login === '') {
    $user_id = (int)($_SESSION['id'] ?? $_SESSION['user_id'] ?? 0);

    if ($user_id > 0) {
        $stmt = mysqli_prepare($koneksi, "
            SELECT email FROM t_users
            WHERE id = ? LIMIT 1
        ");

        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);

        $hasil = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($hasil);

        mysqli_stmt_close($stmt);

        $email_login = $user['email'] ?? '';
    }
}

// TAHUN AJARAN AKTIF
$q_tahun = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_tahun_ajaran
    WHERE status_aktif = 1
    LIMIT 1
");

$data_tahun = mysqli_fetch_assoc($q_tahun);
$tahun_ajaran_id = (int)($data_tahun['id'] ?? 0);

// IDENTITAS GURU
$guru_id = 0;
$nama_guru = '';
$data_guru = null;

if ($role !== 'admin') {

    if ($email_login !== '') {
        $stmt = mysqli_prepare($koneksi, "
            SELECT id, nama
            FROM t_guru
            WHERE email = ?
            LIMIT 1
        ");

        mysqli_stmt_bind_param($stmt, 's', $email_login);
        mysqli_stmt_execute($stmt);

        $hasil = mysqli_stmt_get_result($stmt);
        $data_guru = mysqli_fetch_assoc($hasil);

        mysqli_stmt_close($stmt);
    }

    if (!$data_guru && $nama_login !== '') {
        $stmt = mysqli_prepare($koneksi, "
            SELECT id, nama
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

    if ($data_guru) {
        $guru_id = (int)$data_guru['id'];
        $nama_guru = $data_guru['nama'];
    } else {
        $error = 'Data guru tidak ditemukan. Cocokkan akun login dengan data guru.';
    }
}

// DATA GURU UNTUK ADMIN
$data_guru_admin = [];

if ($role === 'admin') {
    $q_guru = mysqli_query($koneksi, "
        SELECT id, nama
        FROM t_guru
        ORDER BY nama ASC
    ");

    while ($row = mysqli_fetch_assoc($q_guru)) {
        $data_guru_admin[] = $row;
    }
}

// DATA SISWA
$data_siswa = [];

if ($tahun_ajaran_id > 0 && ($role === 'admin' || $guru_id > 0)) {

    if ($role === 'wali_kelas') {

        $stmt = mysqli_prepare($koneksi, "
            SELECT
                s.id,
                s.nis,
                s.nama,
                k.id AS kelas_id,
                k.nama AS nama_kelas
            FROM t_kelas_siswa ks
            INNER JOIN t_siswa s ON s.id = ks.siswa_id
            INNER JOIN t_kelas k ON k.id = ks.kelas_id
            INNER JOIN t_wali_kelas wk
                ON wk.kelas_id = ks.kelas_id
                AND wk.tahun_ajaran_id = ks.tahun_ajaran_id
            WHERE ks.tahun_ajaran_id = ?
                AND s.status_aktif = 1
                AND wk.guru_id = ?
                AND wk.status_aktif = 1
            ORDER BY k.nama ASC, s.nama ASC
        ");

        mysqli_stmt_bind_param($stmt, 'ii', $tahun_ajaran_id, $guru_id);

    } else {

        $stmt = mysqli_prepare($koneksi, "
            SELECT
                s.id,
                s.nis,
                s.nama,
                k.id AS kelas_id,
                k.nama AS nama_kelas
            FROM t_kelas_siswa ks
            INNER JOIN t_siswa s ON s.id = ks.siswa_id
            INNER JOIN t_kelas k ON k.id = ks.kelas_id
            WHERE ks.tahun_ajaran_id = ?
                AND s.status_aktif = 1
            ORDER BY k.nama ASC, s.nama ASC
        ");

        mysqli_stmt_bind_param($stmt, 'i', $tahun_ajaran_id);
    }

    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($hasil)) {
        $data_siswa[] = $row;
    }

    mysqli_stmt_close($stmt);
}

// DATA PELANGGARAN
$data_pelanggaran = [];

$q_pelanggaran = mysqli_query($koneksi, "
    SELECT id, kode, nama, poin
    FROM t_pelanggaran
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

while ($row = mysqli_fetch_assoc($q_pelanggaran)) {
    $data_pelanggaran[] = $row;
}

// ==================================================
// RIWAYAT CATATAN PELANGGARAN
// ==================================================

$data_catatan = [];

$sql_catatan = "
    SELECT
        ps.id,
        ps.tahun_ajaran_id,
        ps.siswa_id,
        ps.nama_siswa,
        ps.kelas_id,
        ps.nama_kelas,
        ps.pelanggaran_id,
        ps.nama_pelanggaran,
        ps.pelanggaran_kategori_id,
        ps.guru_id,
        ps.nama_guru,
        ps.tanggal,
        ps.keterangan,
        ps.poin,
        ps.tindakan,
        ps.status,

        s.nis,
        s.nisn,

        ta.nama AS nama_tahun_ajaran,

        pk.nama AS nama_kategori

    FROM t_pelanggaran_siswa ps

    LEFT JOIN t_siswa s
        ON s.id = ps.siswa_id

    LEFT JOIN t_tahun_ajaran ta
        ON ta.id = ps.tahun_ajaran_id

    LEFT JOIN t_pelanggaran_kategori pk
        ON pk.id = ps.pelanggaran_kategori_id
";

// BATASAN BERDASARKAN ROLE
if ($role === 'guru') {

    $sql_catatan .= "
        WHERE ps.guru_id = ?
    ";

} elseif ($role === 'wali_kelas') {

    $sql_catatan .= "
        WHERE EXISTS (
            SELECT 1
            FROM t_wali_kelas wk
            WHERE wk.guru_id = ?
                AND wk.kelas_id = ps.kelas_id
                AND wk.tahun_ajaran_id = ps.tahun_ajaran_id
                AND wk.status_aktif = 1
        )
    ";
}

$sql_catatan .= "
    ORDER BY ps.tanggal DESC, ps.id DESC
";

if ($role === 'admin') {

    $q_catatan = mysqli_query($koneksi, $sql_catatan);

} else {

    $stmt_catatan = mysqli_prepare($koneksi, $sql_catatan);

    mysqli_stmt_bind_param(
        $stmt_catatan,
        'i',
        $guru_id
    );

    mysqli_stmt_execute($stmt_catatan);

    $q_catatan = mysqli_stmt_get_result($stmt_catatan);
}

if (!$q_catatan) {
    die('Gagal mengambil riwayat catatan: ' . mysqli_error($koneksi));
}

while ($row = mysqli_fetch_assoc($q_catatan)) {
    $data_catatan[] = $row;
}

if (isset($stmt_catatan)) {
    mysqli_stmt_close($stmt_catatan);
}

// TOTAL POIN
$total_poin = 0;

foreach ($data_catatan as $catatan) {
    $total_poin += (int)$catatan['poin'];
}

// FUNGSI ESCAPE
function e($value)
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Catatan Pelanggaran</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

<style>

body {
    background: #f5f6fa;
    font-family: Arial, sans-serif;
}

.main-content {
    margin-left: 250px;
    padding: 25px;
    min-height: 100vh;
}

.card {
    border: none;
    border-radius: 13px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    overflow: hidden;
    margin-bottom: 25px;
}

.card-header {
    background: #212529;
    color: white;
    padding: 17px 20px;
}

.form-label {
    font-weight: 600;
}

.form-control,
.form-select {
    padding: 11px;
    border-radius: 8px;
}

.select2-container {
    width: 100% !important;
}

.select2-container .select2-selection--single {
    height: 46px !important;
    border: 1px solid #ced4da !important;
    border-radius: 8px !important;
}

.select2-container--default
.select2-selection--single
.select2-selection__rendered {
    line-height: 44px !important;
    padding-left: 12px;
}

.select2-container--default
.select2-selection--single
.select2-selection__arrow {
    height: 44px !important;
}

.poin-box {
    background: #f1f3f5;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 15px;
}

.poin-value {
    font-size: 27px;
    font-weight: bold;
}

.table thead th {
    background: #212529;
    color: white;
    font-size: 12px;
    white-space: nowrap;
    padding: 13px;
    vertical-align: middle;
}

.table tbody td {
    padding: 12px;
    vertical-align: middle;
    font-size: 13px;
}

.table tbody tr:hover {
    background: #f8f9fa;
}

.badge-status {
    padding: 7px 10px;
    border-radius: 20px;
    font-size: 11px;
}

.status-belum {
    background: #fff3cd;
    color: #856404;
}

.status-proses {
    background: #cfe2ff;
    color: #084298;
}

.status-selesai {
    background: #d1e7dd;
    color: #0f5132;
}

.poin-badge {
    background: #212529;
    color: white;
    padding: 6px 10px;
    border-radius: 7px;
    font-weight: bold;
}

.info-card {
    border-radius: 12px;
    padding: 20px;
    background: white;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
}

.info-number {
    font-size: 27px;
    font-weight: bold;
}

.search-box {
    max-width: 350px;
}

.btn {
    border-radius: 8px;
}

@media(max-width:768px) {

    .main-content {
        margin-left: 0;
        padding: 15px;
    }

}

</style>

</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

    <!-- JUDUL -->
    <div class="mb-4">

        <h3 class="fw-bold mb-1">
            Catatan Pelanggaran
        </h3>

        <p class="text-muted mb-0">
            Pencatatan dan riwayat pelanggaran siswa
        </p>

    </div>

    <!-- NOTIFIKASI -->
    <?php if ($success): ?>

        <div class="alert alert-success alert-dismissible fade show">
            <?= e($success); ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>

    <?php endif; ?>

    <?php if ($error): ?>

        <div class="alert alert-danger alert-dismissible fade show">
            <?= e($error); ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>

    <?php endif; ?>

    <!-- RINGKASAN -->
    <div class="row g-3 mb-4">

        <div class="col-md-6">

            <div class="info-card">

                <div class="text-muted small">
                    Total Catatan Pelanggaran
                </div>

                <div class="info-number">
                    <?= count($data_catatan); ?>
                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="info-card">

                <div class="text-muted small">
                    Total Akumulasi Poin
                </div>

                <div class="info-number">
                    <?= $total_poin; ?>
                    <span class="fs-6">Poin</span>
                </div>

            </div>

        </div>

    </div>

    <!-- FORM INPUT -->
    <?php if ($tahun_ajaran_id <= 0): ?>

        <div class="alert alert-warning">
            Belum ada tahun ajaran yang aktif.
        </div>

    <?php elseif ($role !== 'admin' && $guru_id <= 0): ?>

        <div class="alert alert-danger">
            Data guru tidak ditemukan.
        </div>

    <?php else: ?>

    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="bi bi-plus-circle"></i>
                Form Catatan Pelanggaran
            </h5>

        </div>

        <div class="card-body p-4">

            <form action="proses_catatan_pelanggaran.php"
                  method="POST">

                <input type="hidden"
                       name="tahun_ajaran_id"
                       value="<?= $tahun_ajaran_id; ?>">

                <div class="mb-3">

                    <label class="form-label">
                        Tahun Ajaran
                    </label>

                    <input type="text"
                           class="form-control"
                           value="<?= e($data_tahun['nama']); ?>"
                           readonly>

                </div>

                <?php if ($role === 'admin'): ?>

                    <div class="mb-3">

                        <label class="form-label">
                            Guru yang Mencatat
                        </label>

                        <select name="guru_id"
                                class="form-select select-search"
                                required>

                            <option value="">
                                -- Cari Guru --
                            </option>

                            <?php foreach ($data_guru_admin as $guru): ?>

                                <option value="<?= (int)$guru['id']; ?>">
                                    <?= e($guru['nama']); ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                <?php else: ?>

                    <div class="mb-3">

                        <label class="form-label">
                            Guru yang Mencatat
                        </label>

                        <input type="text"
                               class="form-control"
                               value="<?= e($nama_guru); ?>"
                               readonly>

                        <input type="hidden"
                               name="guru_id"
                               value="<?= $guru_id; ?>">

                    </div>

                <?php endif; ?>

                <!-- PILIH SISWA -->
                <div class="mb-3">

                    <label class="form-label">
                        Pilih Siswa
                    </label>

                    <select name="siswa_id"
                            id="siswa_id"
                            class="form-select select-search"
                            required>

                        <option value="">
                            -- Cari NIS atau Nama Siswa --
                        </option>

                        <?php foreach ($data_siswa as $siswa): ?>

                            <option
                                value="<?= (int)$siswa['id']; ?>"
                                data-kelas="<?= (int)$siswa['kelas_id']; ?>">

                                <?= e($siswa['nis']); ?>
                                -
                                <?= e($siswa['nama']); ?>
                                (<?= e($siswa['nama_kelas']); ?>)

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <input type="hidden"
                           name="kelas_id"
                           id="kelas_id">

                </div>

                <!-- PILIH PELANGGARAN -->
                <div class="mb-3">

                    <label class="form-label">
                        Jenis Pelanggaran
                    </label>

                    <select name="pelanggaran_id"
                            id="pelanggaran_id"
                            class="form-select select-search"
                            required>

                        <option value="">
                            -- Cari Jenis Pelanggaran --
                        </option>

                        <?php foreach ($data_pelanggaran as $pelanggaran): ?>

                            <option
                                value="<?= (int)$pelanggaran['id']; ?>"
                                data-poin="<?= (int)$pelanggaran['poin']; ?>">

                                <?= e($pelanggaran['kode']); ?>
                                -
                                <?= e($pelanggaran['nama']); ?>
                                (<?= (int)$pelanggaran['poin']; ?> Poin)

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- POIN -->
                <div class="mb-3">

                    <label class="form-label">
                        Poin Pelanggaran
                    </label>

                    <div class="poin-box">

                        <div class="text-muted small">
                            Poin yang akan diberikan
                        </div>

                        <div class="poin-value">
                            <span id="poin_tampil">0</span>
                            Poin
                        </div>

                    </div>

                </div>

                <!-- TANGGAL -->
                <div class="mb-3">

                    <label class="form-label">
                        Tanggal Pelanggaran
                    </label>

                    <input type="date"
                           name="tanggal"
                           class="form-control"
                           value="<?= date('Y-m-d'); ?>"
                           required>

                </div>

                <!-- KETERANGAN -->
                <div class="mb-4">

                    <label class="form-label">
                        Keterangan
                    </label>

                    <textarea name="keterangan"
                              class="form-control"
                              rows="4"
                              placeholder="Tuliskan keterangan pelanggaran..."
                              required></textarea>

                </div>

                <button type="submit"
                        class="btn btn-dark"
                        <?= (empty($data_siswa) || empty($data_pelanggaran)) ? 'disabled' : ''; ?>>

                    Simpan Catatan

                </button>

                <a href="dashboard.php"
                   class="btn btn-secondary">

                    Kembali

                </a>

            </form>

        </div>

    </div>

    <?php endif; ?>

    <!-- TABEL RIWAYAT -->
    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">
                Riwayat Catatan Pelanggaran
            </h5>

        </div>

        <div class="card-body">

            <!-- PENCARIAN -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">

                <div>

                    <h6 class="fw-bold mb-1">
                        Data Pelanggaran Siswa
                    </h6>

                    <small class="text-muted">
                        Total <?= count($data_catatan); ?> catatan ditemukan
                    </small>

                </div>

                <div class="search-box">

                    <input type="text"
                           id="searchCatatan"
                           class="form-control"
                           placeholder="Cari NIS, nama, kelas, pelanggaran...">

                </div>

            </div>

            <!-- TABEL -->
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle"
                       id="tabelCatatan">

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>NISN</th>
                            <th>Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Jenis Pelanggaran</th>
                            <th>Kategori</th>
                            <th>Poin</th>
                            <th>Tanggal</th>
                            <th>Guru Pencatat</th>
                            <th>Keterangan</th>
                            <th>Tindakan</th>
                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if (empty($data_catatan)): ?>

                        <tr>

                            <td colspan="14"
                                class="text-center text-muted py-4">

                                <div class="fw-bold">
                                    Belum ada catatan pelanggaran
                                </div>

                                <small>
                                    Catatan pelanggaran yang disimpan akan muncul di sini.
                                </small>

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php $no = 1; ?>

                        <?php foreach ($data_catatan as $catatan): ?>

                            <?php

                            $status = $catatan['status'] ?? '';

                            $status_class = 'status-belum';

                            if (
                                stripos($status, 'selesai') !== false ||
                                stripos($status, 'ditindaklanjuti') !== false
                            ) {
                                $status_class = 'status-selesai';
                            } elseif (
                                stripos($status, 'proses') !== false ||
                                stripos($status, 'pembinaan') !== false
                            ) {
                                $status_class = 'status-proses';
                            }

                            ?>

                            <tr>

                                <td>
                                    <?= $no++; ?>
                                </td>

                                <td>
                                    <?= e($catatan['nis'] ?: '-'); ?>
                                </td>

                                <td>
                                    <strong>
                                        <?= e($catatan['nama_siswa']); ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= e($catatan['nisn'] ?: '-'); ?>
                                </td>

                                <td>
                                    <?= e($catatan['nama_kelas']); ?>
                                </td>

                                <td>
                                    <?= e($catatan['nama_tahun_ajaran'] ?: '-'); ?>
                                </td>

                                <td>
                                    <?= e($catatan['nama_pelanggaran']); ?>
                                </td>

                                <td>
                                    <?= e($catatan['nama_kategori'] ?: '-'); ?>
                                </td>

                                <td>
                                    <span class="poin-badge">
                                        <?= (int)$catatan['poin']; ?>
                                    </span>
                                </td>

                                <td>
                                    <?= e(date('d-m-Y', strtotime($catatan['tanggal']))); ?>
                                </td>

                                <td>
                                    <?= e($catatan['nama_guru']); ?>
                                </td>

                                <td>
                                    <?= e($catatan['keterangan'] ?: '-'); ?>
                                </td>

                                <td>
                                    <?= e($catatan['tindakan'] ?: '-'); ?>
                                </td>

                                <td>
                                    <span class="badge-status <?= $status_class; ?>">
                                        <?= e($status ?: 'Belum ditindaklanjuti'); ?>
                                    </span>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

                <div id="hasilKosong"
                     class="text-center text-muted py-3"
                     style="display:none;">

                    Data yang dicari tidak ditemukan.

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>

$(document).ready(function () {

    // SELECT2
    $('.select-search').select2({
        width: '100%',
        placeholder: 'Ketik untuk mencari...',
        allowClear: true
    });

    // KELAS SISWA
    $('#siswa_id').on('change', function () {

        let kelas = $(this).find(':selected').data('kelas') || '';

        $('#kelas_id').val(kelas);

    });

    // POIN OTOMATIS
    $('#pelanggaran_id').on('change', function () {

        let poin = $(this).find(':selected').data('poin');

        $('#poin_tampil').text(
            poin !== undefined ? poin : 0
        );

    });

    // PENCARIAN TABEL
    $('#searchCatatan').on('keyup', function () {

        let keyword = $(this).val().toLowerCase();

        let jumlah = 0;

        $('#tabelCatatan tbody tr').each(function () {

            let teks = $(this).text().toLowerCase();

            let cocok = teks.includes(keyword);

            $(this).toggle(cocok);

            if (cocok) {
                jumlah++;
            }

        });

        if (jumlah === 0 && keyword !== '') {
            $('#hasilKosong').show();
        } else {
            $('#hasilKosong').hide();
        }

    });

});

</script>

</body>
</html>
