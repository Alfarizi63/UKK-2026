
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['name'] ?? 'Pengguna';

if (!in_array($role, ['admin', 'guru', 'wali_kelas'])) {
    header('Location: login.php');
    exit;
}

// ======================================
// MENGHITUNG DATA DARI DATABASE
// ======================================

$total_siswa = 0;
$total_guru = 0;
$total_kelas = 0;

// Menghitung jumlah siswa
$query_siswa = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total FROM t_siswa
");

if ($query_siswa) {
    $total_siswa = mysqli_fetch_assoc($query_siswa)['total'];
}

// Menghitung jumlah kelas
$query_kelas = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total FROM t_kelas
");

if ($query_kelas) {
    $total_kelas = mysqli_fetch_assoc($query_kelas)['total'];
}

// ======================================
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
        <h1>Selamat datang, <?= htmlspecialchars($nama) ?></h1>
        <p class="text-secondary mb-0">
            Anda login sebagai: <?= htmlspecialchars($role) ?>
        </p>
    </div>

    <?php if ($role === 'admin') : ?>

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
                        <small class="text-muted">Total seluruh siswa</small>
                    </div>
                </div>
            </div>

            <!-- DATA GURU -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Data Guru</h5>
                        <h2 class="fw-bold">
                            <?= $total_guru === 0 ? '-' : number_format($total_guru) ?>
                        </h2>
                        <small class="text-muted">Total seluruh guru</small>
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
                        <small class="text-muted">Total seluruh kelas</small>
                    </div>
                </div>
            </div>

        </div>

    <?php elseif ($role === 'guru' || $role === 'wali_kelas') : ?>

        <h2 class="mb-3">Dashboard</h2>

        <div class="row g-3">

            <div class="col-md-6 col-xl-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5>Catatan</h5>
                        <p class="text-secondary">
                            Kelola catatan pelanggaran siswa.
                        </p>
                        <a href="catatan_pelanggaran.php" class="btn btn-primary btn-sm">
                            Buka Menu
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5>Tindakan</h5>
                        <p class="text-secondary">
                            Kelola tindakan pelanggaran siswa.
                        </p>
                        <a href="tindakan.php" class="btn btn-primary btn-sm">
                            Buka Menu
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5>Laporan</h5>
                        <p class="text-secondary">
                            Lihat laporan pelanggaran siswa.
                        </p>
                        <a href="laporan.php" class="btn btn-primary btn-sm">
                            Buka Menu
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5>Riwayat</h5>
                        <p class="text-secondary">
                            Lihat riwayat pelanggaran siswa.
                        </p>
                        <a href="riwayat.php" class="btn btn-primary btn-sm">
                            Buka Menu
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5>Rekap Poin</h5>
                        <p class="text-secondary">
                            Lihat rekap poin pelanggaran siswa.
                        </p>
                        <a href="rekap_point.php" class="btn btn-primary btn-sm">
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
