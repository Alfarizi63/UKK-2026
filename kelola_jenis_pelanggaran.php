
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (isset($_SESSION['pesan'])) {
    $pesan = $_SESSION['pesan'];
    unset($_SESSION['pesan']);
}

$sql = "SELECT * FROM t_pelanggaran ORDER BY id DESC";
$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal mengambil data: " . htmlspecialchars(mysqli_error($koneksi)));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Jenis Pelanggaran</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    
<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
       <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3 position-fixed top-0 start-0 overflow-auto">
            <h4 class="text-white mb-4">
                My Website
            </h4>
           <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link text-white">
                        Data Siswa
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link text-white">
                        Data Guru
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link text-white">
                        Kelas
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_kategori_pelanggaran.php" class="nav-link text-white">
                        kelola kategori pelanggaran
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link text-white">
                        Kelola tahun ajaran
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link text-white">
                        Laporan
                    </a>
                     <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php" class="nav-link text-white">
                        kelola wali kelas
                    </a>
                </li>
                     
                      <li class="nav-item mb-2">
                    <a href="kelola_penempatan_siswa.php" class="nav-link text-white">
                        kelola penempatan siswa
                    </a>
                </li>
                
                 <li class="nav-item mb-2">
                    <a href="rekap_point.php" class="nav-link text-white">
                        rekap poin
                    </a>
                </li>
                 <li class="nav-item mb-2">
                    <a href="riwayat.php" class="nav-link text-white">
                        riwayat
                    </a>
                </li>
                 <li class="nav-item mb-2">
                    <a href="kelola_jenis_pelanggaran.php" class="nav-link active">
                        kelola jenis pelanggaran
                    </a>
                </li>
                 <li class="nav-item mb-2">
                    <a href="export_pelanggaran.php" class="nav-link text-white">
                        export pelanggaran
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="tentang_saya.php" class="nav-link text-white">
                        About Me
                    </a>
                </li>
                <hr class="text-secondary">
                <li class="nav-item">
                    <a href="login.php" class="nav-link text-danger">
                        Logout
                    </a>
                </li>
                
            </ul>
        </div>
        <!-- KONTEN -->
         <main class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">

<div class="container py-4">
    <h2>Kelola Jenis Pelanggaran</h2>
    <p class="text-muted">Data jenis pelanggaran dan poin siswa.</p>

    <?php if (!empty($pesan)): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($pesan); ?>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">Tambah Jenis Pelanggaran</div>
        <div class="card-body">
            <form action="proses_pelanggaran.php" method="POST">
                <input type="hidden" name="aksi" value="tambah">

                <div class="form-group">
                    <label>Nama Pelanggaran</label>
                    <input type="text" name="nama_pelanggaran"
                           class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Poin Pelanggaran</label>
                    <input type="number" name="poin"
                           class="form-control" min="1" required>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan"
                              class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
                <a href="dashboard.php" class="btn btn-secondary">
                    Kembali
                </a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Daftar Jenis Pelanggaran</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama Pelanggaran</th>
                        <th>Poin</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?= (int)$row['id']; ?></td>
                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                <td><?= (int)$row['poin']; ?></td>
                                <td><?= htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
                                <td>
                                    <a class="btn btn-warning btn-sm"
                                       href="edit_pelanggaran.php?id=<?= (int)$row['id']; ?>">
                                        Edit
                                    </a>

                                    <a class="btn btn-danger btn-sm"
                                       href="proses_pelanggaran.php?aksi=hapus&id=<?= (int)$row['id']; ?>"
                                       onclick="return confirm('Yakin ingin menghapus jenis pelanggaran ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">
                                Belum ada data pelanggaran.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
