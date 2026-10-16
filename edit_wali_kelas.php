
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID wali kelas tidak valid.');
}

$id = (int) $_GET['id'];
$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guru_id = (int) ($_POST['guru_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);

    if ($guru_id <= 0 || $kelas_id <= 0) {
        $pesan = 'Guru dan kelas wajib dipilih.';
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_wali_kelas SET guru_id = ?, kelas_id = ? WHERE id = ?"
        );
        mysqli_stmt_bind_param($stmt, "iii", $guru_id, $kelas_id, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: kelola_wali_kelas.php");
            exit;
        } else {
            $pesan = 'Gagal mengubah data wali kelas: ' . mysqli_error($koneksi);
        }
    }
}

$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_wali_kelas WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die('Data wali kelas tidak ditemukan.');
}

$guru_terpilih = $_POST['guru_id'] ?? $data['guru_id'];
$kelas_terpilih = $_POST['kelas_id'] ?? $data['kelas_id'];

$guru = mysqli_query($koneksi, "SELECT id, nama FROM t_guru ORDER BY nama ASC");
$kelas = mysqli_query($koneksi, "SELECT id, nama FROM t_kelas ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Wali Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Edit Wali Kelas</h3>

    <?php if ($pesan !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($pesan); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama Guru</label>
            <select name="guru_id" class="form-select" required>
                <option value="">-- Pilih Guru --</option>
                <?php while ($g = mysqli_fetch_assoc($guru)): ?>
                    <option value="<?= (int)$g['id']; ?>"
                        <?= (string)$guru_terpilih === (string)$g['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($g['nama']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select name="kelas_id" class="form-select" required>
                <option value="">-- Pilih Kelas --</option>
                <?php while ($k = mysqli_fetch_assoc($kelas)): ?>
                    <option value="<?= (int)$k['id']; ?>"
                        <?= (string)$kelas_terpilih === (string)$k['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($k['nama']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="kelola_wali_kelas.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
