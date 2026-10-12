
<?php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data guru beserta email akun user
$sql = "SELECT 
            t_guru.*,
            t_users.email AS email_user
        FROM t_guru
        LEFT JOIN t_users ON t_guru.user_id = t_users.id
        ORDER BY t_guru.id DESC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Query data guru gagal: " . mysqli_error($koneksi));
}

// Ambil akun yang role-nya guru
$sql_user = "SELECT id, name, email
             FROM t_users
             WHERE role = 'guru'
             ORDER BY name ASC";

$query_user = mysqli_query($koneksi, $sql_user);

if (!$query_user) {
    die("Query akun guru gagal: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Guru</title>
</head>

<body>

    <h1>Kelola Guru</h1>

    <?php if (isset($_SESSION['pesan'])) { ?>

        <p>
            <?= htmlspecialchars($_SESSION['pesan']); ?>
        </p>

        <?php unset($_SESSION['pesan']); ?>

    <?php } ?>

    <hr>

    <h2>Tambah Data Guru</h2>

    <form action="proses_guru.php" method="POST">

        <input type="hidden" name="aksi" value="tambah">

        <table>

            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>
                    <input type="text" name="nip" required>
                </td>
            </tr>

            <tr>
                <td>Nama Guru</td>
                <td>:</td>
                <td>
                    <input type="text" name="nama" required>
                </td>
            </tr>

            <tr>
                <td>Email</td>
                <td>:</td>
                <td>
                    <input type="email" name="email" required>
                </td>
            </tr>

            <tr>
                <td>Akun Guru</td>
                <td>:</td>
                <td>

                    <select name="user_id" required>

                        <option value="">
                            -- Pilih Akun Guru --
                        </option>

                        <?php while ($user = mysqli_fetch_assoc($query_user)) { ?>

                            <option value="<?= $user['id']; ?>">

                                <?= htmlspecialchars($user['name']); ?>
                                -
                                <?= htmlspecialchars($user['email']); ?>

                            </option>

                        <?php } ?>

                    </select>

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
                    <button type="submit">
                        Tambah Guru
                    </button>
                </td>
            </tr>

        </table>
        <a href="dashboard.php">Kembali ke Dashboard</a>

    </form>

    <hr>

    <h2>Data Guru</h2>

    <table border="1" cellpadding="8" cellspacing="0">

        <thead>
            <tr>
                <th>ID</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Status</th>
                <th>User ID</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            <?php if (mysqli_num_rows($query) > 0) { ?>

                <?php while ($guru = mysqli_fetch_assoc($query)) { ?>

                    <tr>

                        <td>
                            <?= $guru['id']; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($guru['nip']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($guru['nama']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($guru['email_user'] ?? $guru['email'] ?? '-'); ?>
                        </td>

                        <td>

                            <?php if ($guru['status_aktif'] == 1) { ?>
                                Aktif
                            <?php } else { ?>
                                Tidak Aktif
                            <?php } ?>

                        </td>

                        <td>
                            <?= htmlspecialchars($guru['user_id'] ?? '-'); ?>
                        </td>

                        <td>

                            <a href="edit_guru.php?id=<?= $guru['id']; ?>">
                                Edit
                            </a>

                            |

                            <a href="proses_guru.php?aksi=hapus&id=<?= $guru['id']; ?>"
                               onclick="return confirm('Yakin ingin menghapus data guru ini?');">
                                Hapus
                            </a>

                        </td>

                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="7">
                        Belum ada data guru.
                    </td>
                </tr>

            <?php } ?>

        </tbody>

    </table>

</body>
</html>
