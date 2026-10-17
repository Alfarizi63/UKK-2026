<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "
    SELECT
        s.id,
        s.nis,
        s.nama,
        COUNT(ps.id) AS jumlah_pelanggaran,
        COALESCE(SUM(ps.poin), 0) AS total_poin
    FROM t_siswa s
    LEFT JOIN t_pelanggaran_siswa ps
        ON ps.siswa_id = s.id
    GROUP BY s.id, s.nis, s.nama
    ORDER BY total_poin DESC, s.nama ASC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die('Query gagal: ' . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <meta charset="UTF-8">
    <title>Rekap Poin Pelanggaran Siswa</title>
</head>
<body>
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
                    <a href="rekap_point.php" class="nav-link active">
                        rekap poin
                    </a>
                </li>
                 <li class="nav-item mb-2">
                    <a href="riwayat.php" class="nav-link text-white">
                        riwayat
                    </a>
                </li>
                 <li class="nav-item mb-2">
                    <a href="kelola_jenis_pelanggaran.php" class="nav-link text-white">
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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <?php if ($_SESSION['role'] == 'guru' ) { ?>
<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
       <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3 position-fixed top-0 start-0 overflow-auto">
            <h4 class="text-white mb-4">
                My Website
            </h4>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link active">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="catatan_pelanggaran.php" class="nav-link active">
                        catatan
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="tindakan.php" class="nav-link active">
                        tindakan
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link active">
                        laporan
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="riwayat.php" class="nav-link active">
                        riwayat
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="rekap_point.php" class="nav-link active">
                        rekap point
                    </a>
                </li>
            </ul>
        </div>
        <!-- KONTEN -->
         <main class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">
                <h1>selamat datang, <?php echo $_SESSION['name'];?></h1>
    <p>anda login sebagai: <?php echo $_SESSION['role']?></p>
<?php } ?>   
<div class="container-fluid">
   

<h2>Rekap Poin Pelanggaran Siswa</h2>

<a href="dashboard.php">Kembali ke Dashboard</a>
<button type="button" onclick="window.print()">Cetak Laporan</button>

<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Jumlah Pelanggaran</th>
            <th>Total Poin</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;

        while ($data = mysqli_fetch_assoc($query)) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($data['nis'] ?? ''); ?></td>
            <td><?= htmlspecialchars($data['nama'] ?? ''); ?></td>
            <td><?= $data['jumlah_pelanggaran']; ?></td>
            <td><?= $data['total_poin']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>