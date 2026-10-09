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
</head>
<body>

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
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($kelas['nama']) ?></td>
                    <td><?= htmlspecialchars($kelas['tingkat']) ?></td>
                    <td><?= htmlspecialchars($kelas['jurusan']) ?></td>
                    <td>
                        <?= $kelas['status_aktif'] ? 'Aktif' : 'Tidak Aktif' ?>
                    </td>
                    <td>
                        <a href="edit_kelas.php?id=<?= $kelas['id'] ?>">Edit</a>
                        |
                        <a
                            href="hapus_kelas.php?id=<?= $kelas['id'] ?>"
                            onclick="return confirm('Yakin ingin menghapus kelas ini?')"
                        >Hapus</a>
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