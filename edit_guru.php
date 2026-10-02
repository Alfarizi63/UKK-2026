<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil ID guru dari URL
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['pesan'] = "ID guru tidak valid!";
    header("Location: menu2.php");
    exit;
}

// Ambil data guru berdasarkan ID
$sql = "SELECT 
            t_guru.*,
            t_users.email AS email_user
        FROM t_guru
        LEFT JOIN t_users ON t_guru.user_id = t_users.id
        WHERE t_guru.id = ?";

$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$guru = mysqli_fetch_assoc($result);

if (!$guru) {
    $_SESSION['pesan'] = "Data guru tidak ditemukan!";
    header("Location: menu2.php");
    exit;
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
    <title>Edit Data Guru</title>
</head>
<body>

    <h1>Edit Data Guru</h1>

    <hr>

    <form action="proses_edit_guru.php" method="POST">

        <input type="hidden" name="id" value="<?= $guru['id']; ?>">

        <table>

            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>
                    <input
                        type="text"
                        name="nip"
                        value="<?= htmlspecialchars($guru['nip']); ?>"
                        required
                    >
                </td>
            </tr>

            <tr>
                <td>Nama Guru</td>
                <td>:</td>
                <td>
                    <input
                        type="text"
                        name="nama"
                        value="<?= htmlspecialchars($guru['nama']); ?>"
                        required
                    >
                </td>
            </tr>

            <tr>
                <td>Email</td>
                <td>:</td>
                <td>
                    <input
                        type="email"
                        name="email"
                        value="<?= htmlspecialchars($guru['email'] ?? ''); ?>"
                        required
                    >
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

                            <option
                                value="<?= $user['id']; ?>"
                                <?= ($guru['user_id'] == $user['id']) ? 'selected' : ''; ?>
                            >
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

                        <option value="1"
                            <?= ($guru['status_aktif'] == 1) ? 'selected' : ''; ?>>
                            Aktif
                        </option>

                        <option value="0"
                            <?= ($guru['status_aktif'] == 0) ? 'selected' : ''; ?>>
                            Tidak Aktif
                        </option>

                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <button type="submit">
                        Simpan Perubahan
                    </button>

                    <a href="menu2.php">
                        Batal
                    </a>
                </td>
            </tr>

        </table>

    </form>

</body>
</html>