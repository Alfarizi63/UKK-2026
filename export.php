<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/cek_session.php';
require_once 'config/koneksi.php';

if (!isset($koneksi) || !($koneksi instanceof mysqli)) {
    die('Koneksi database tidak tersedia.');
}

mysqli_set_charset($koneksi, 'utf8mb4');

/* =====================================================
   FUNGSI BANTU
===================================================== */

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function tanggalValid($tanggal)
{
    if ($tanggal === '') {
        return true;
    }

    $date = DateTime::createFromFormat('Y-m-d', $tanggal);

    return $date && $date->format('Y-m-d') === $tanggal;
}

/* =====================================================
   IDENTITAS DAN HAK AKSES
===================================================== */

$role = $_SESSION['role'] ?? '';

$user_id = (int) (
    $_SESSION['user_id']
    ?? $_SESSION['id']
    ?? $_SESSION['user']['id']
    ?? 0
);

$guru_id = 0;

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    die('Akses ditolak.');
}

/*
 * Ambil ID guru berdasarkan user_id.
 * Guru dan wali kelas harus mempunyai data di t_guru.
 */

if ($role === 'guru' || $role === 'wali_kelas') {

    if ($user_id <= 0) {
        die('ID akun tidak ditemukan dalam session. Periksa session login.');
    }

    $stmt_guru = mysqli_prepare(
        $koneksi,
        "SELECT id FROM t_guru WHERE user_id = ? LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt_guru, "i", $user_id);
    mysqli_stmt_execute($stmt_guru);

    $hasil_guru = mysqli_stmt_get_result($stmt_guru);
    $data_guru = mysqli_fetch_assoc($hasil_guru);

    mysqli_stmt_close($stmt_guru);

    if (!$data_guru) {
        die('Data guru tidak ditemukan. Pastikan user_id pada t_guru sesuai dengan ID akun.');
    }

    $guru_id = (int) $data_guru['id'];
}

/* =====================================================
   FILTER
===================================================== */

$filter_tahun = (int) ($_GET['tahun_ajaran'] ?? 0);
$filter_kelas = (int) ($_GET['kelas'] ?? 0);

$tanggal_mulai = trim($_GET['tanggal_mulai'] ?? '');
$tanggal_selesai = trim($_GET['tanggal_selesai'] ?? '');

$jenis_export = $_GET['export'] ?? '';

if (!tanggalValid($tanggal_mulai) || !tanggalValid($tanggal_selesai)) {
    die('Format tanggal tidak valid.');
}

if (
    $tanggal_mulai !== '' &&
    $tanggal_selesai !== '' &&
    $tanggal_mulai > $tanggal_selesai
) {
    die('Tanggal mulai tidak boleh melewati tanggal selesai.');
}

/* =====================================================
   DATA TAHUN AJARAN
===================================================== */

$tahun_result = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     ORDER BY tanggal_mulai DESC"
);

if (!$tahun_result) {
    die('Gagal mengambil data tahun ajaran: ' . e(mysqli_error($koneksi)));
}

/* =====================================================
   DATA KELAS
   KOLOM NAMA KELAS ADALAH t_kelas.nama
===================================================== */

if ($role === 'wali_kelas') {

    $kelas_stmt = mysqli_prepare(
        $koneksi,
        "SELECT DISTINCT k.id, k.nama
         FROM t_kelas k
         INNER JOIN t_wali_kelas wk ON wk.kelas_id = k.id
         WHERE wk.guru_id = ?
         AND wk.status_aktif = 1
         ORDER BY k.nama ASC"
    );

    mysqli_stmt_bind_param($kelas_stmt, "i", $guru_id);
    mysqli_stmt_execute($kelas_stmt);

    $kelas_result = mysqli_stmt_get_result($kelas_stmt);

} else {

    $kelas_result = mysqli_query(
        $koneksi,
        "SELECT id, nama
         FROM t_kelas
         ORDER BY nama ASC"
    );
}

if (!$kelas_result) {
    die('Gagal mengambil data kelas: ' . e(mysqli_error($koneksi)));
}

/* =====================================================
   QUERY DATA PELANGGARAN
===================================================== */

$where = [];

if ($filter_tahun > 0) {
    $where[] = "p.tahun_ajaran_id = " . $filter_tahun;
}

if ($filter_kelas > 0) {
    $where[] = "p.kelas_id = " . $filter_kelas;
}

if ($tanggal_mulai !== '') {
    $where[] = "p.tanggal >= '" . mysqli_real_escape_string($koneksi, $tanggal_mulai) . "'";
}

if ($tanggal_selesai !== '') {
    $where[] = "p.tanggal <= '" . mysqli_real_escape_string($koneksi, $tanggal_selesai) . "'";
}

/*
 * Admin:
 * Bisa melihat semua data.
 *
 * Guru:
 * Hanya melihat pelanggaran yang dicatat sendiri.
 *
 * Wali kelas:
 * Hanya melihat pelanggaran dari kelas yang menjadi tanggung jawabnya.
 */

if ($role === 'guru') {

    $where[] = "p.guru_id = " . $guru_id;

} elseif ($role === 'wali_kelas') {

    $where[] = "
        EXISTS (
            SELECT 1
            FROM t_wali_kelas wk
            WHERE wk.guru_id = " . $guru_id . "
            AND wk.kelas_id = p.kelas_id
            AND wk.tahun_ajaran_id = p.tahun_ajaran_id
            AND wk.status_aktif = 1
        )
    ";
}

$sql_where = '';

if (!empty($where)) {
    $sql_where = 'WHERE ' . implode(' AND ', $where);
}

/* =====================================================
   AMBIL DATA
===================================================== */

$sql = "
    SELECT
        p.id,
        p.tanggal,
        p.nama_siswa,
        p.nama_kelas,
        p.nama_pelanggaran,
        p.nama_guru,
        p.keterangan,
        p.poin,
        p.tindakan,
        p.status,
        ta.nama AS tahun_ajaran,
        s.nis

    FROM t_pelanggaran_siswa p

    LEFT JOIN t_tahun_ajaran ta
        ON ta.id = p.tahun_ajaran_id

    LEFT JOIN t_siswa s
        ON s.id = p.siswa_id

    $sql_where

    ORDER BY p.tanggal DESC, p.id DESC
";

$data_result = mysqli_query($koneksi, $sql);

if (!$data_result) {
    die('Gagal mengambil data pelanggaran: ' . e(mysqli_error($koneksi)));
}

/* =====================================================
   NAMA FILE EXPORT
===================================================== */

$nama_file = 'data_pelanggaran_' . date('Y-m-d');

/* =====================================================
   EXPORT CSV
===================================================== */

if ($jenis_export === 'csv') {

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $nama_file . '.csv"');

    $output = fopen('php://output', 'w');

    // BOM agar karakter Indonesia terbaca dengan baik di Excel
    fwrite($output, "\xEF\xBB\xBF");

    fputcsv($output, [
        'No',
        'Tanggal',
        'NIS',
        'Nama Siswa',
        'Kelas',
        'Tahun Ajaran',
        'Pelanggaran',
        'Guru Pencatat',
        'Keterangan',
        'Poin',
        'Tindakan',
        'Status'
    ], ';');

    $no = 1;

    while ($row = mysqli_fetch_assoc($data_result)) {

        fputcsv($output, [
            $no++,
            $row['tanggal'],
            $row['nis'],
            $row['nama_siswa'],
            $row['nama_kelas'],
            $row['tahun_ajaran'],
            $row['nama_pelanggaran'],
            $row['nama_guru'],
            $row['keterangan'],
            $row['poin'],
            $row['tindakan'],
            $row['status']
        ], ';');
    }

    fclose($output);
    exit;
}

/* =====================================================
   EXPORT EXCEL
===================================================== */

if ($jenis_export === 'excel') {

    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $nama_file . '.xls"');

    echo "\xEF\xBB\xBF";

    ?>
    <table border="1">
        <thead>
            <tr>
                <th colspan="12" style="font-size:16px;">
                    LAPORAN DATA PELANGGARAN SISWA
                </th>
            </tr>

            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Tahun Ajaran</th>
                <th>Pelanggaran</th>
                <th>Guru Pencatat</th>
                <th>Keterangan</th>
                <th>Poin</th>
                <th>Tindakan</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $no = 1;

            while ($row = mysqli_fetch_assoc($data_result)) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= e($row['tanggal']); ?></td>
                    <td><?= e($row['nis']); ?></td>
                    <td><?= e($row['nama_siswa']); ?></td>
                    <td><?= e($row['nama_kelas']); ?></td>
                    <td><?= e($row['tahun_ajaran']); ?></td>
                    <td><?= e($row['nama_pelanggaran']); ?></td>
                    <td><?= e($row['nama_guru']); ?></td>
                    <td><?= e($row['keterangan']); ?></td>
                    <td><?= e($row['poin']); ?></td>
                    <td><?= e($row['tindakan']); ?></td>
                    <td><?= e($row['status']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php

    exit;
}

/* =====================================================
   SIAPKAN DATA UNTUK TAMPILAN DAN PDF
===================================================== */

$data_pelanggaran = [];

while ($row = mysqli_fetch_assoc($data_result)) {
    $data_pelanggaran[] = $row;
}

$jumlah_data = count($data_pelanggaran);

$nama_role = [
    'admin' => 'Administrator',
    'guru' => 'Guru',
    'wali_kelas' => 'Wali Kelas'
];

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Export Pelanggaran</title>

    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <style>
/* PERBAIKAN POSISI KONTEN SIDEBAR */
.konten {
    margin-left: 250px;
    width: calc(100% - 250px);
    min-height: 100vh;
    padding: 25px;
    box-sizing: border-box;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .konten {
        margin-left: 0;
        width: 100%;
        padding: 15px;
    }
}

@media print {
    .konten {
        margin-left: 0 !important;
        width: 100% !important;
        padding: 0 !important;
    }
}
    </style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<main class="konten">

    <div class="container-fluid">

        <h3 class="judul">Export Data Pelanggaran</h3>

        <p class="subjudul">
            Pilih data pelanggaran yang ingin diekspor.
        </p>

        <!-- INFORMASI -->
        <div class="row mb-3">

            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">Total Data Ditampilkan</small>
                        <div class="jumlah-data">
                            <?= $jumlah_data; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">Hak Akses</small>
                        <div class="font-weight-bold mt-2">
                            <?= e($nama_role[$role]); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">Tanggal Export</small>
                        <div class="font-weight-bold mt-2">
                            <?= date('d-m-Y'); ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- FILTER -->
        <div class="card filter-card">

            <div class="card-header">
                Filter Data Pelanggaran
            </div>

            <div class="card-body">

                <form method="GET">

                    <div class="form-row">

                        <div class="form-group col-md-3">

                            <label>Tahun Ajaran</label>

                            <select name="tahun_ajaran" class="form-control">

                                <option value="0">Semua Tahun Ajaran</option>

                                <?php while ($tahun = mysqli_fetch_assoc($tahun_result)) { ?>

                                    <option value="<?= (int) $tahun['id']; ?>"
                                        <?= $filter_tahun === (int) $tahun['id'] ? 'selected' : ''; ?>>

                                        <?= e($tahun['nama']); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <div class="form-group col-md-3">

                            <label>Kelas</label>

                            <select name="kelas" class="form-control">

                                <option value="0">Semua Kelas</option>

                                <?php while ($kelas = mysqli_fetch_assoc($kelas_result)) { ?>

                                    <option value="<?= (int) $kelas['id']; ?>"
                                        <?= $filter_kelas === (int) $kelas['id'] ? 'selected' : ''; ?>>

                                        <?= e($kelas['nama']); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <div class="form-group col-md-3">

                            <label>Tanggal Mulai</label>

                            <input type="date"
                                name="tanggal_mulai"
                                class="form-control"
                                value="<?= e($tanggal_mulai); ?>">

                        </div>

                        <div class="form-group col-md-3">

                            <label>Tanggal Selesai</label>

                            <input type="date"
                                name="tanggal_selesai"
                                class="form-control"
                                value="<?= e($tanggal_selesai); ?>">

                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Tampilkan Data
                    </button>

                    <a href="export.php" class="btn btn-secondary">
                        Reset Filter
                    </a>

                </form>

            </div>

        </div>

        <!-- TOMBOL EXPORT -->
        <div class="card tombol-export">

            <div class="card-header">
                Pilihan Export
            </div>

            <div class="card-body">

                <?php
                $parameter = $_GET;
                unset($parameter['export']);

                $query_parameter = http_build_query($parameter);

                $url_csv = 'export.php?' . $query_parameter . '&export=csv';
                $url_excel = 'export.php?' . $query_parameter . '&export=excel';
                ?>

                <a href="<?= e($url_csv); ?>" class="btn btn-success mb-2">
                    Export CSV
                </a>

                <a href="<?= e($url_excel); ?>" class="btn btn-primary mb-2">
                    Export Excel
                </a>

                <button type="button"
                    onclick="window.print()"
                    class="btn btn-danger mb-2">

                    Cetak / Simpan PDF

                </button>

            </div>

        </div>

        <!-- TABEL DATA -->
        <div class="card">

            <div class="card-header">
                Daftar Data Pelanggaran
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-hover">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Tahun Ajaran</th>
                                <th>Pelanggaran</th>
                                <th>Guru Pencatat</th>
                                <th>Keterangan</th>
                                <th>Poin</th>
                                <th>Tindakan</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if ($jumlah_data > 0) { ?>

                                <?php $no = 1; ?>

                                <?php foreach ($data_pelanggaran as $row) { ?>

                                    <tr>

                                        <td><?= $no++; ?></td>

                                        <td>
                                            <?= date('d-m-Y', strtotime($row['tanggal'])); ?>
                                        </td>

                                        <td>
                                            <?= e($row['nis']); ?>
                                        </td>

                                        <td>
                                            <?= e($row['nama_siswa']); ?>
                                        </td>

                                        <td>
                                            <?= e($row['nama_kelas']); ?>
                                        </td>

                                        <td>
                                            <?= e($row['tahun_ajaran']); ?>
                                        </td>

                                        <td>
                                            <?= e($row['nama_pelanggaran']); ?>
                                        </td>

                                        <td>
                                            <?= e($row['nama_guru']); ?>
                                        </td>

                                        <td>
                                            <?= e($row['keterangan']); ?>
                                        </td>

                                        <td class="text-center">
                                            <?= e($row['poin']); ?>
                                        </td>

                                        <td>
                                            <?= e($row['tindakan']); ?>
                                        </td>

                                        <td>
                                            <?php
                                            $status = strtolower(trim($row['status']));

                                            if ($status === 'selesai') {
                                                $badge = 'success';
                                            } elseif ($status === 'proses') {
                                                $badge = 'warning';
                                            } else {
                                                $badge = 'secondary';
                                            }
                                            ?>

                                            <span class="badge badge-<?= $badge; ?> badge-status">
                                                <?= e($row['status']); ?>
                                            </span>
                                        </td>

                                    </tr>

                                <?php } ?>

                            <?php } else { ?>

                                <tr>
                                    <td colspan="12" class="text-center text-muted py-4">
                                        Tidak ada data pelanggaran yang ditemukan.
                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</main>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</body>
</html>