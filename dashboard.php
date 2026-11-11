
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['name'] ?? 'Pengguna';
$email_login = trim($_SESSION['email'] ?? '');

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    header('Location: login.php');
    exit;
}

$is_admin = ($role === 'admin');
$is_wali_kelas = false;

$guru_id = 0;
$kelas_wali_id = 0;
$nama_kelas_wali = '';
$nama_tahun_ajaran = '';

// ======================================
// MENGHITUNG DATA DARI DATABASE
// ======================================

$total_siswa = 0;
$total_guru = 0;
$total_kelas = 0;

// Total siswa
$query_siswa = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM t_siswa
");

if ($query_siswa) {
    $total_siswa = (int) mysqli_fetch_assoc($query_siswa)['total'];
}

// Total guru
$query_guru = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM t_guru
");

if ($query_guru) {
    $total_guru = (int) mysqli_fetch_assoc($query_guru)['total'];
}

// Total kelas
$query_kelas = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM t_kelas
");

if ($query_kelas) {
    $total_kelas = (int) mysqli_fetch_assoc($query_kelas)['total'];
}

// ======================================
// CEK TAHUN AJARAN AKTIF
// ======================================

if (!$is_admin) {

    $query_tahun = mysqli_query($koneksi, "
        SELECT id, nama
        FROM t_tahun_ajaran
        WHERE status_aktif = 1
        ORDER BY id DESC
        LIMIT 1
    ");

    if (!$query_tahun) {
        die("Gagal mengambil tahun ajaran: " . mysqli_error($koneksi));
    }

    $data_tahun = mysqli_fetch_assoc($query_tahun);

    $tahun_ajaran_aktif_id = (int) ($data_tahun['id'] ?? 0);
    $nama_tahun_ajaran = $data_tahun['nama'] ?? '';

    // ======================================
    // CARI DATA GURU YANG LOGIN
    // ======================================

    if ($email_login !== '') {

        $stmt_guru = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_guru WHERE email = ? LIMIT 1"
        );

        if (!$stmt_guru) {
            die("Gagal mencari data guru: " . mysqli_error($koneksi));
        }

        mysqli_stmt_bind_param($stmt_guru, "s", $email_login);
        mysqli_stmt_execute($stmt_guru);

        $result_guru = mysqli_stmt_get_result($stmt_guru);
        $data_guru = mysqli_fetch_assoc($result_guru);

        mysqli_stmt_close($stmt_guru);

        if ($data_guru) {
            $guru_id = (int) $data_guru['id'];
        }
    }

    // Cadangan pencarian berdasarkan nama
    if ($guru_id === 0 && $nama !== '') {

        $stmt_guru = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_guru WHERE nama = ? LIMIT 1"
        );

        if (!$stmt_guru) {
            die("Gagal mencari data guru: " . mysqli_error($koneksi));
        }

        mysqli_stmt_bind_param($stmt_guru, "s", $nama);
        mysqli_stmt_execute($stmt_guru);

        $result_guru = mysqli_stmt_get_result($stmt_guru);
        $data_guru = mysqli_fetch_assoc($result_guru);

        mysqli_stmt_close($stmt_guru);

        if ($data_guru) {
            $guru_id = (int) $data_guru['id'];
        }
    }

    // ======================================
    // CEK PENUGASAN WALI KELAS
    // ======================================

    if ($guru_id > 0 && $tahun_ajaran_aktif_id > 0) {

        $sql_wali = "
            SELECT
                wk.kelas_id,
                k.nama AS nama_kelas
            FROM t_wali_kelas wk
            INNER JOIN t_kelas k
                ON k.id = wk.kelas_id
            INNER JOIN t_tahun_ajaran ta
                ON ta.id = wk.tahun_ajaran_id
            WHERE wk.guru_id = ?
              AND wk.tahun_ajaran_id = ?
              AND wk.status_aktif = 1
              AND ta.status_aktif = 1
            LIMIT 1
        ";

        $stmt_wali = mysqli_prepare($koneksi, $sql_wali);

        if (!$stmt_wali) {
            die("Gagal mengecek wali kelas: " . mysqli_error($koneksi));
        }

        mysqli_stmt_bind_param(
            $stmt_wali,
            "ii",
            $guru_id,
            $tahun_ajaran_aktif_id
        );

        mysqli_stmt_execute($stmt_wali);

        $result_wali = mysqli_stmt_get_result($stmt_wali);
        $data_wali = mysqli_fetch_assoc($result_wali);

        mysqli_stmt_close($stmt_wali);

        if ($data_wali) {

            $is_wali_kelas = true;

            $kelas_wali_id = (int) $data_wali['kelas_id'];
            $nama_kelas_wali = $data_wali['nama_kelas'];
        }
    }
}

// ======================================
// LABEL ROLE DASHBOARD
// ======================================

$label_role = $role;

if ($is_admin) {
    $label_role = 'Admin';
} elseif ($is_wali_kelas) {
    $label_role = 'Wali Kelas';
} elseif ($role === 'guru') {
    $label_role = 'Guru';
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">

    <div class="bg-white p-4 rounded shadow-sm mb-4">

        <h1>
            Selamat datang, <?= htmlspecialchars($nama) ?>
        </h1>

        <p class="text-secondary mb-0">
            Anda login sebagai:
            <strong><?= htmlspecialchars($label_role) ?></strong>
        </p>

    </div>

    <?php if ($is_admin) : ?>

        <!-- ======================================
             DASHBOARD ADMIN
        ======================================= -->

        <h2 class="mb-3">Dashboard Admin</h2>

        <div class="row">

            <!-- DATA SISWA -->
            <div class="col-md-4 mb-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <h5 class="card-title">Data Siswa</h5>

                        <h2 class="fw-bold">
                            <?= number_format($total_siswa) ?>
                        </h2>

                        <small class="text-muted">
                            Total seluruh siswa
                        </small>

                    </div>

                </div>

            </div>

            <!-- DATA GURU -->
            <div class="col-md-4 mb-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <h5 class="card-title">Data Guru</h5>

                        <h2 class="fw-bold">
                            <?= number_format($total_guru) ?>
                        </h2>

                        <small class="text-muted">
                            Total seluruh guru
                        </small>

                    </div>

                </div>

            </div>

            <!-- DATA KELAS -->
            <div class="col-md-4 mb-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <h5 class="card-title">Kelas</h5>

                        <h2 class="fw-bold">
                            <?= number_format($total_kelas) ?>
                        </h2>

                        <small class="text-muted">
                            Total seluruh kelas
                        </small>

                    </div>

                </div>

            </div>

        </div>

    <?php else : ?>

        <!-- ======================================
             DASHBOARD GURU / WALI KELAS
        ======================================= -->

        <h2 class="mb-3">
            Dashboard <?= htmlspecialchars($label_role) ?>
        </h2>

        <?php if ($is_wali_kelas) : ?>

            <div class="alert alert-info">

                <strong>Informasi Wali Kelas</strong>

                <p class="mb-1 mt-2">
                    Anda terdaftar sebagai wali kelas:
                    <strong><?= htmlspecialchars($nama_kelas_wali) ?></strong>
                </p>

                <p class="mb-0">
                    Tahun ajaran:
                    <strong><?= htmlspecialchars($nama_tahun_ajaran) ?></strong>
                </p>

            </div>

        <?php else : ?>

            <div class="alert alert-secondary">
                Anda login sebagai guru biasa dan belum memiliki penugasan wali kelas pada tahun ajaran aktif.
            </div>

        <?php endif; ?>

        <div class="row g-3">

            <!-- CATATAN -->
            <div class="col-md-6 col-xl-4 mb-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <h5>Catatan</h5>

                        <p class="text-secondary">
                            Kelola catatan pelanggaran siswa.
                        </p>

                        <a href="catatan_pelanggaran.php"
                           class="btn btn-primary btn-sm">
                            Buka Menu
                        </a>

                    </div>

                </div>

            </div>

            <!-- TINDAKAN -->
            <div class="col-md-6 col-xl-4 mb-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <h5>Tindakan</h5>

                        <p class="text-secondary">
                            Kelola tindakan pelanggaran siswa.
                        </p>

                        <a href="tindakan.php"
                           class="btn btn-primary btn-sm">
                            Buka Menu
                        </a>

                    </div>

                </div>

            </div>

            <!-- LAPORAN KHUSUS WALI KELAS -->
            <?php if ($is_wali_kelas) : ?>

                <div class="col-md-6 col-xl-4 mb-3">

                    <div class="card shadow-sm h-100">

                        <div class="card-body">

                            <h5>Laporan</h5>

                            <p class="text-secondary">
                                Lihat laporan penempatan siswa kelas Anda.
                            </p>

                            <a href="laporan.php"
                               class="btn btn-primary btn-sm">
                                Buka Menu
                            </a>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

            <!-- RIWAYAT -->
            <div class="col-md-6 col-xl-4 mb-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <h5>Riwayat</h5>

                        <p class="text-secondary">
                            Lihat riwayat pelanggaran siswa.
                        </p>

                        <a href="riwayat.php"
                           class="btn btn-primary btn-sm">
                            Buka Menu
                        </a>

                    </div>

                </div>

            </div>

            <!-- REKAP POIN -->
            <div class="col-md-6 col-xl-4 mb-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <h5>Rekap Poin</h5>

                        <p class="text-secondary">
                            Lihat rekap poin pelanggaran siswa.
                        </p>

                        <a href="rekap_point.php"
                           class="btn btn-primary btn-sm">
                            Buka Menu
                        </a>

                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
