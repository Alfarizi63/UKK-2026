
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID kelas tidak valid.');
}

$id = (int) $_GET['id'];
$pesan = '';

$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_kelas WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die('Data kelas tidak ditemukan.');
}

$nama = $data['nama'];
$jurusan = $data['jurusan'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');

    if ($nama === '') {
        $pesan = 'Nama kelas wajib diisi.';
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_kelas SET nama = ?, jurusan = ? WHERE id = ?"
        );
        mysqli_stmt_bind_param($stmt, "ssi", $nama, $jurusan, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: kelola_kelas.php");
            exit;
        } else {
            $pesan = 'Gagal mengubah kelas: ' . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Edit Kelas</h3>

    <?php if ($pesan !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($pesan); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama Kelas</label>
            <input type="text" name="nama" class="form-control"
                   value="<?= htmlspecialchars($nama); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Jurusan</label>
            <input type="text" name="jurusan" class="form-control"
                   value="<?= htmlspecialchars($jurusan); ?>">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="kelola_kelas.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
