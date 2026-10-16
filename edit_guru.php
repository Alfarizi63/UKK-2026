
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = (int)($_GET['id'] ?? 0);

$sql = "SELECT t_guru.*, t_users.name AS nama_akun,
               t_users.email AS email_akun
        FROM t_guru
        LEFT JOIN t_users ON t_guru.user_id = t_users.id
        WHERE t_guru.id = ?";

$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$guru = mysqli_fetch_assoc($result);

if (!$guru) {
    die("Data guru tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Guru</title>
</head>
<body>

<h2>Edit Data Guru</h2>

<?php if (isset($_SESSION['pesan'])): ?>
    <p><?= htmlspecialchars($_SESSION['pesan']); ?></p>
    <?php unset($_SESSION['pesan']); ?>
<?php endif; ?>

<form action="proses_edit_guru.php" method="POST">
    <input type="hidden" name="id" value="<?= $guru['id']; ?>">

    <p>NIP</p>
    <input type="text" name="nip"
           value="<?= htmlspecialchars($guru['nip'] ?? ''); ?>" required>

    <p>Nama Guru</p>
    <input type="text" name="nama"
           value="<?= htmlspecialchars($guru['nama'] ?? ''); ?>" required>

    <p>Nama Akun</p>
    <input type="text" name="name"
           value="<?= htmlspecialchars($guru['nama_akun'] ?? ''); ?>" required>

    <p>Email Akun</p>
    <input type="email" name="email"
           value="<?= htmlspecialchars($guru['email_akun'] ?? ''); ?>" required>

    <p>Password Baru (opsional)</p>
    <input type="password" name="password">

    <p>Status</p>
    <select name="status_aktif">
        <option value="1" <?= ($guru['status_aktif'] == 1) ? 'selected' : ''; ?>>Aktif</option>
        <option value="0" <?= ($guru['status_aktif'] == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
    </select>

    <br><br>
    <button type="submit">Simpan Perubahan</button>
    <a href="kelola_guru.php">Batal</a>
</form>

</body>
</html>
