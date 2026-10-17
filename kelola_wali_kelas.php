<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "
    SELECT
        wk.id,
        ta.nama AS tahun_ajaran,
        k.nama AS nama_kelas,
        g.nama AS nama_guru,
        wk.tanggal_mulai,
        wk.tanggal_selesai,
        wk.status_aktif
    FROM t_wali_kelas wk
    LEFT JOIN t_tahun_ajaran ta
        ON wk.tahun_ajaran_id = ta.id
    LEFT JOIN t_kelas k
        ON wk.kelas_id = k.id
    LEFT JOIN t_guru g
        ON wk.guru_id = g.id
    ORDER BY wk.id DESC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal mengambil data wali kelas: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Wali Kelas</title>
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
                    <a href="kelola_wali_kelas.php" class="nav-link active">
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
                    <a href="logout.php" class="nav-link text-danger">
                        Logout
                    </a>
                </li>
                
            </ul>
        </div>
        <!-- KONTEN -->
         <main class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">

    <h2>Kelola Wali Kelas</h2>
    <p>Halaman pengelolaan data wali kelas sekolah.</p>

    <a href="dashboard.php">Kembali ke Dashboard</a>
    |
    <a href="tambah_wali_kelas.php">Tambah Wali Kelas</a>

    <br><br>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Tahun Ajaran</th>
                <th>Kelas</th>
                <th>Nama Guru</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $no = 1;

            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
            ?>
                <tr>
                    <td><?= $no++ ?></td>

                    <td>
                        <?= htmlspecialchars($row['tahun_ajaran'] ?? '-') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nama_kelas'] ?? '-') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nama_guru'] ?? '-') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['tanggal_mulai']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['tanggal_selesai']) ?>
                    </td>

                    <td>
                        <?= $row['status_aktif'] == 1
                            ? 'Aktif'
                            : 'Tidak Aktif' ?>
                    </td>

                    <td>
                        <a href="edit_wali_kelas.php?id=<?= (int) $row['id'] ?>">
                            Edit
                        </a>
                        |
                        <a
                            href="hapus_wali_kelas.php?id=<?= (int) $row['id'] ?>"
                            onclick="return confirm('Yakin ingin menghapus data wali kelas ini?')"
                        >
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr>
                    <td colspan="8">Belum ada data wali kelas.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>