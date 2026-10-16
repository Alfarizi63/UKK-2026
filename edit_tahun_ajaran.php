
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID tahun ajaran tidak valid.');
}

$id = (int) $_GET['id'];
$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';

    if ($nama === '' || $tanggal_mulai === '' || $tanggal_selesai === '') {
        $pesan = 'Semua data wajib diisi.';
    } elseif ($tanggal_selesai < $tanggal_mulai) {
        $pesan = 'Tanggal selesai tidak boleh sebelum tanggal mulai.';
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_tahun_ajaran
             SET nama = ?, tanggal_mulai = ?, tanggal_selesai = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssi",
            $nama,
            $tanggal_mulai,
            $tanggal_selesai,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: kelola_tahun_ajaran.php");
            exit;
        } else {
            $pesan = 'Gagal mengubah data tahun ajaran: ' . mysqli_error($koneksi);
        }
    }
}

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_tahun_ajaran WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die('Data tahun ajaran tidak ditemukan.');
}

$nama = $_POST['nama'] ?? $data['nama'];
$tanggal_mulai = $_POST['tanggal_mulai'] ?? $data['tanggal_mulai'];
$tanggal_selesai = $_POST['tanggal_selesai'] ?? $data['tanggal_selesai'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Tahun Ajaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Edit Tahun Ajaran</h3>

    <?php if ($pesan !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($pesan); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama Tahun Ajaran</label>
            <input type="text" name="nama" class="form-control"
                   value="<?= htmlspecialchars($nama); ?>"
                   placeholder="Contoh: 2026/2027" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" class="form-control"
                   value="<?= htmlspecialchars($tanggal_mulai); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" class="form-control"
                   value="<?= htmlspecialchars($tanggal_selesai); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="kelola_tahun_ajaran.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
