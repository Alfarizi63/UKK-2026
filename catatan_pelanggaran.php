<?php

session_start();

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data pelanggaran + kategori
$sql = "SELECT 
            t_pelanggaran.*,
            t_pelanggaran_kategori.nama AS nama_kategori
        FROM t_pelanggaran
        LEFT JOIN t_pelanggaran_kategori
        ON t_pelanggaran.pelanggaran_kategori_id = t_pelanggaran_kategori.id
        ORDER BY t_pelanggaran.id DESC";

$query = mysqli_query($koneksi, $sql);


// Ambil data kategori
$sql_kategori = "SELECT * FROM t_pelanggaran_kategori
                 ORDER BY nama ASC";

$query_kategori = mysqli_query($koneksi, $sql_kategori);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Pelanggaran</title>
</head>

<body>
    \<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <h1>selamat datang, <?php echo $_SESSION['name'];?></h1>
    <p>anda login sebagai: <?php echo $_SESSION['role']?></p>
    <?php if ($_SESSION['role'] == 'admin'  )  {?>

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
   <?php } ?>
       <?php if ($_SESSION['role'] == 'guru' ) { ?>
<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
       <div class="col-md-3 col-lg-2 bg-dark text-white p-3 position-fixed top-0 bottom-0 start-0 overflow-y-auto">
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
    <h1>Kelola Pelanggaran</h1>

    <?php if (isset($_SESSION['pesan'])) { ?>

        <p>
            <?php
            echo $_SESSION['pesan'];
            unset($_SESSION['pesan']);
            ?>
        </p>

    <?php } ?>


    <hr>


    <h2>Tambah Pelanggaran</h2>

    <form action="proses_pelanggaran.php" method="POST">

        <input type="hidden" name="aksi" value="tambah">

        <table>

            <tr>

                <td>Kategori</td>

                <td>:</td>

                <td>

                    <select name="pelanggaran_kategori_id" required>

                        <option value="">
                            -- Pilih Kategori --
                        </option>

                        <?php while ($kategori = mysqli_fetch_assoc($query_kategori)) { ?>

                            <option value="<?php echo $kategori['id']; ?>">

                                <?php
                                echo htmlspecialchars($kategori['nama']);
                                ?>

                            </option>

                        <?php } ?>

                    </select>

                </td>

            </tr>


            <tr>

                <td>Kode</td>

                <td>:</td>

                <td>

                    <input
                        type="text"
                        name="kode"
                        placeholder="PG-001"
                        required
                    >

                </td>

            </tr>


            <tr>

                <td>Nama Pelanggaran</td>

                <td>:</td>

                <td>

                    <input
                        type="text"
                        name="nama"
                        required
                    >

                </td>

            </tr>


            <tr>

                <td>Poin</td>

                <td>:</td>

                <td>

                    <input
                        type="number"
                        name="poin"
                        min="0"
                        required
                    >

                </td>

            </tr>


            <tr>

                <td>Deskripsi</td>

                <td>:</td>

                <td>

                    <textarea
                        name="deskripsi"
                        rows="4"
                        required
                    ></textarea>

                </td>

            </tr>


            <tr>

                <td>Status Aktif</td>

                <td>:</td>

                <td>

                    <select name="status_aktif" required>

                        <option value="1">
                            Aktif
                        </option>

                        <option value="0">
                            Tidak Aktif
                        </option>

                    </select>

                </td>

            </tr>


            <tr>

                <td colspan="3">

                    <button type="submit">
                        Tambah Pelanggaran
                    </button>

                </td>

            </tr>

        </table>

    </form>


    <hr>
    <a href="dashboard.php">Kembali ke Dashboard</a>


    <h2>Data Pelanggaran</h2>


    <table border="1" cellpadding="8" cellspacing="0">

        <thead>

            <tr>

                <th>No</th>
                <th>Kategori</th>
                <th>Kode</th>
                <th>Nama Pelanggaran</th>
                <th>Poin</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Aksi</th>

            </tr>

        </thead>


        <tbody>

        <?php

        $no = 1;

        if (mysqli_num_rows($query) > 0) {

            while ($pelanggaran = mysqli_fetch_assoc($query)) {

        ?>

            <tr>

                <td>
                    <?php echo $no++; ?>
                </td>


                <td>

                    <?php
                    echo htmlspecialchars(
                        $pelanggaran['nama_kategori'] ?? '-'
                    );
                    ?>

                </td>


                <td>

                    <?php
                    echo htmlspecialchars($pelanggaran['kode']);
                    ?>

                </td>


                <td>

                    <?php
                    echo htmlspecialchars($pelanggaran['nama']);
                    ?>

                </td>


                <td>

                    <?php
                    echo htmlspecialchars($pelanggaran['poin']);
                    ?>

                </td>


                <td>

                    <?php
                    echo htmlspecialchars($pelanggaran['deskripsi']);
                    ?>

                </td>


                <td>

                    <?php if ($pelanggaran['status_aktif'] == 1) { ?>

                        Aktif

                    <?php } else { ?>

                        Tidak Aktif

                    <?php } ?>

                </td>


                <td>

                    <a href="edit_pelanggaran.php?id=<?php echo $pelanggaran['id']; ?>">
                        Edit
                    </a>

                    |

                    <a
                        href="proses_pelanggaran.php?aksi=hapus&id=<?php echo $pelanggaran['id']; ?>"
                        onclick="return confirm('Yakin ingin menghapus pelanggaran ini?');"
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

                <td colspan="8">
                    Belum ada data pelanggaran.
                </td>

            </tr>

        <?php } ?>

        </body>

    </table>

</body>

</html>