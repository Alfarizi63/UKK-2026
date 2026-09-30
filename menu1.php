<?php

session_start();

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil semua data siswa
$sql = "SELECT * FROM t_siswa ORDER BY id DESC";
$query = mysqli_query($koneksi, $sql);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Siswa</title>
</head>

<body>

    <h1>Kelola Siswa</h1>

    <?php if (isset($_SESSION['pesan'])) { ?>
        <p>
            <?php
            echo $_SESSION['pesan'];
            unset($_SESSION['pesan']);
            ?>
        </p>
    <?php } ?>

    <hr>

    <h2>Tambah Data Siswa</h2>

    <form action="proses_siswa.php" method="POST">

        <input type="hidden" name="aksi" value="tambah">

        <table>

            <tr>
                <td>NIS</td>
                <td>:</td>
                <td>
                    <input type="text" name="nis" required>
                </td>
            </tr>

            <tr>
                <td>NISN</td>
                <td>:</td>
                <td>
                    <input type="text" name="nisn" required>
                </td>
            </tr>

            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>
                    <input type="text" name="nama" required>
                </td>
            </tr>

            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>
                    <select name="jenis_kelamin" required>
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Tanggal Lahir</td>
                <td>:</td>
                <td>
                    <input type="date" name="tanggal_lahir" required>
                </td>
            </tr>

            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>
                    <textarea name="alamat" required></textarea>
                </td>
            </tr>

            <tr>
                <td>Status Aktif</td>
                <td>:</td>
                <td>
                    <select name="status_aktif" required>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <button type="submit">Tambah Siswa</button>
                </td>
            </tr>

        </table>

    </form>

    <hr>

    <h2>Data Siswa</h2>

    <table border="1" cellpadding="8" cellspacing="0">

        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>NISN</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

        <?php

        $no = 1;

        if (mysqli_num_rows($query) > 0) {

            while ($siswa = mysqli_fetch_assoc($query)) {

        ?>

            <tr>

                <td><?php echo $no++; ?></td>

                <td>
                    <?php echo htmlspecialchars($siswa['nis']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($siswa['nisn']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($siswa['nama']); ?>
                </td>

                <td>
                    <?php
                    if ($siswa['jenis_kelamin'] == 'L') {
                        echo "Laki-laki";
                    } else {
                        echo "Perempuan";
                    }
                    ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($siswa['tanggal_lahir']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($siswa['alamat']); ?>
                </td>

                <td>
                    <?php
                    if ($siswa['status_aktif'] == 1) {
                        echo "Aktif";
                    } else {
                        echo "Tidak Aktif";
                    }
                    ?>
                </td>

                <td>

                    <a href="edit_siswa.php?id=<?php echo $siswa['id']; ?>">
                        Edit
                    </a>

                    |

                    <a
                        href="proses_siswa.php?aksi=hapus&id=<?php echo $siswa['id']; ?>"
                        onclick="return confirm('Yakin ingin menghapus siswa ini?');"
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
                <td colspan="9">
                    Belum ada data siswa.
                </td>
            </tr>

        <?php } ?>

        </tbody>

    </table>

</body>

</html>