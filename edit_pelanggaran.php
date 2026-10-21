<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $koneksi->prepare(
    "SELECT * FROM t_pelanggaran WHERE id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    die("Data pelanggaran tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $poin = (int) ($_POST['poin'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    $stmt = $koneksi->prepare(
        "UPDATE t_pelanggaran
         SET nama = ?, poin = ?, deskripsi = ?
         WHERE id = ?"
    );
    $stmt->bind_param("sisi", $nama, $poin, $deskripsi, $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: catatan_pelanggaran.php");
        exit;
    }

    die("Gagal menyimpan perubahan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pelanggaran</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4">
        <h4>Edit Pelanggaran</h4>

        <form method="POST">
            <div class="form-group">
                <label>Nama Pelanggaran</label>
                <input type="text" name="nama"
                       class="form-control"
                       value="<?= htmlspecialchars($data['nama']) ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Poin</label>
                <input type="number" name="poin"
                       class="form-control"
                       min="1"
                       value="<?= (int) $data['poin'] ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi"
                          class="form-control"><?= htmlspecialchars($data['deskripsi'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan
            </button>

            <a href="catatan_pelanggaran.php" class="btn btn-secondary">
                Batal
            </a>
        </form>
    </div>
</div>
</body>
</html>