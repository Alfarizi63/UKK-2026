<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$query = mysqli_query($koneksi, "
    SELECT * FROM t_kelas
    ORDER BY id DESC
");

if (!$query) {
    die("Gagal mengambil data kelas: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kelas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3 position-fixed" style="top: 0; bottom: 0; overflow-y: auto;">
            <h4 class="text-white mb-4">
                My Website
            </h4>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php"class="nav-link text-white">
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
                    <a href="kelola_kelas.php" class="nav-link active">
                        Kelas
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link text-white">
                        Laporan                 
                     <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php" class="nav-link text-white">
                        kelola wali kelas
                    </a>
                </li>
                      <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link text-white">
                        tahun ajaran
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
            </ul>
        </div>
        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <h2>Kelola Kelas</h2>
    <p>Halaman untuk mengelola data kelas.</p>

    <a href="dashboard.php">Kembali ke Dashboard</a>
    |
    <a href="tambah_kelas.php">Tambah Kelas</a>

    <br><br>

    <table border="1" cellpadding="10" cellspacing="0">
        <head>
            <tr>
                <th>No</th>
                <th>Nama Kelas</th>
                <th>Tingkat</th>
                <th>Jurusan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </head>

        <body>
            <?php
            $no = 1;

            if (mysqli_num_rows($query) > 0) {
                while ($kelas = mysqli_fetch_assoc($query)) {
            ?>
                <tr>
                    <td><?= (int)$kelas['id'] ?></td>
                    <td><?= htmlspecialchars($kelas['nama']) ?></td>
                    <td><?= htmlspecialchars($kelas['tingkat']) ?></td>
                    <td><?= htmlspecialchars($kelas['jurusan']) ?></td>
                    <td>
                        <?= $kelas['status_aktif'] ? 'Aktif' : 'Tidak Aktif' ?>
                    </td>
                  
<td>
    <a href="edit_kelas.php?id=<?= (int)$kelas['id'] ?>"
       class="btn btn-primary">
        Edit
    </a>

    <a href="hapus_kelas.php?id=<?= (int)$kelas['id'] ?>"
       class="btn btn-danger"
       onclick="return confirm('Yakin ingin menghapus kelas ini?')">
        Hapus
    </a>
</td>

                </tr>
            <?php
                }
            } else {
            ?>
                <tr>
                    <td colspan="6">Belum ada data kelas.</td>
                </tr>
            <?php } ?>
        </body>
    </table>

</body>
</html>