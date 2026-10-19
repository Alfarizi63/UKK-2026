
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$sql = "SELECT * FROM t_kelas_siswa WHERE id = $id";
$query = mysqli_query($koneksi, $sql);

if (!$query || mysqli_num_rows($query) == 0) {
    die('Data penempatan siswa tidak ditemukan.');
}

$data = mysqli_fetch_assoc($query);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siswa_id = (int) $_POST['siswa_id'];
    $kelas_id = (int) $_POST['kelas_id'];
    $tahun_ajaran_id = (int) $_POST['tahun_ajaran_id'];

    if ($siswa_id <= 0 || $kelas_id <= 0 || $tahun_ajaran_id <= 0) {
        $error = 'Semua data harus dipilih.';
    } else {
        $cek = mysqli_query(
            $koneksi,
            "SELECT id FROM t_kelas_siswa
             WHERE siswa_id = $siswa_id
             AND tahun_ajaran_id = $tahun_ajaran_id
             AND id != $id"
        );

        if (!$cek) {
            $error = 'Gagal memeriksa data: ' . mysqli_error($koneksi);
        } elseif (mysqli_num_rows($cek) > 0) {
            $error = 'Siswa sudah ditempatkan pada tahun ajaran tersebut.';
        } else {
            $update = mysqli_query(
                $koneksi,
                "UPDATE t_kelas_siswa
                 SET siswa_id = $siswa_id,
                     kelas_id = $kelas_id,
                     tahun_ajaran_id = $tahun_ajaran_id
                 WHERE id = $id"
            );

            if ($update) {
                header('Location: kelola_penempatan_siswa.php');
                exit;
            } else {
                $error = 'Gagal mengubah data: ' . mysqli_error($koneksi);
            }
        }
    }
}

$siswa_query = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama FROM t_siswa ORDER BY nama ASC"
);

$kelas_query = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan FROM t_kelas ORDER BY tingkat, jurusan, nama"
);

$ta_query = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Penempatan Siswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <style>
        body { background: #f8f9fa; margin: 0; }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 250px;
            background: #343a40;
            padding: 20px 15px;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar h4 { color: white; font-weight: bold; margin-bottom: 20px; }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px 14px;
            border-radius: 4px;
            text-decoration: none;
        }
        .sidebar a:hover { background: #495057; }
        .sidebar a.active { background: #007bff; }
        .konten {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 25px;
            min-width: 0;
        }
        @media (max-width: 767px) {
            .sidebar { position: relative; width: 100%; height: auto; }
            .konten { margin-left: 0; width: 100%; padding: 15px; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4>My Website</h4>
    <ul class="nav nav-pills flex-column">
        <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
        <li class="nav-item mb-2"><a href="kelola_siswa.php" class="nav-link">Data Siswa</a></li>
        <li class="nav-item mb-2"><a href="kelola_guru.php" class="nav-link">Data Guru</a></li>
        <li class="nav-item mb-2"><a href="kelola_kelas.php" class="nav-link">Kelas</a></li>
        <li class="nav-item mb-2"><a href="kelola_kategori_pelanggaran.php" class="nav-link">kelola kategori pelanggaran</a></li>
        <li class="nav-item mb-2"><a href="kelola_tahun_ajaran.php" class="nav-link">kelola tahun ajaran</a></li>
        <li class="nav-item mb-2"><a href="laporan.php" class="nav-link">Laporan</a></li>
        <li class="nav-item mb-2"><a href="kelola_wali_kelas.php" class="nav-link">kelola wali kelas</a></li>
        <li class="nav-item mb-2"><a href="kelola_penempatan_siswa.php" class="nav-link active">kelola penempatan siswa</a></li>
        <li class="nav-item mb-2"><a href="rekap_point.php" class="nav-link">rekap poin</a></li>
        <li class="nav-item mb-2"><a href="riwayat.php" class="nav-link">riwayat</a></li>
        <li class="nav-item mb-2"><a href="kelola_jenis_pelanggaran.php" class="nav-link">kelola jenis pelanggaran</a></li>
        <li class="nav-item mb-2"><a href="export_pelanggaran.php" class="nav-link">export pelanggaran</a></li>
        <li class="nav-item mb-2"><a href="tentang_saya.php" class="nav-link">About Me</a></li>
        <hr class="text-secondary">
        <li class="nav-item"><a href="logout.php" class="nav-link text-danger">Logout</a></li>
    </ul>
</div>

<main class="konten">
    <h2>Edit Penempatan Siswa</h2>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Siswa</label>
            <select name="siswa_id" class="form-control" required>
                <option value="">-- Pilih Siswa --</option>
                <?php while ($siswa = mysqli_fetch_assoc($siswa_query)): ?>
                    <option value="<?= (int) $siswa['id'] ?>"
                        <?= ((int) $siswa['id'] === (int) $data['siswa_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($siswa['nis'] . ' - ' . $siswa['nama']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Kelas</label>
            <select name="kelas_id" class="form-control" required>
                <option value="">-- Pilih Kelas --</option>
                <?php while ($kelas = mysqli_fetch_assoc($kelas_query)): ?>
                    <option value="<?= (int) $kelas['id'] ?>"
                        <?= ((int) $kelas['id'] === (int) $data['kelas_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($kelas['nama'] . ' - ' . $kelas['tingkat'] . ' - ' . $kelas['jurusan']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Tahun Ajaran</label>
            <select name="tahun_ajaran_id" class="form-control" required>
                <option value="">-- Pilih Tahun Ajaran --</option>
                <?php while ($ta = mysqli_fetch_assoc($ta_query)): ?>
                    <option value="<?= (int) $ta['id'] ?>"
                        <?= ((int) $ta['id'] === (int) $data['tahun_ajaran_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ta['nama']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="kelola_penempatan_siswa.php" class="btn btn-secondary">Batal</a>
    </form>
</main>

</body>
</html>
